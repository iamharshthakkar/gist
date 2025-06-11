<?php
namespace Icao\DataSynchronization\Model;

use Icao\DataSynchronization\Api\DataSynchronizationInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table; // Use Ddl\Table for creating table objects
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

/**
 * Service class for Icao Data Synchronization API.
 * Handles dynamic table creation and data insertion based on type and scope.
 *
 * WARNING: This implementation uses direct DDL operations for dynamic table creation.
 * This approach bypasses Magento's declarative schema and migration system.
 * It is NOT recommended for production environments due to potential issues with
 * database schema management, upgrades, performance, and security.
 * Consider storing JSON data in a flexible predefined table with a TEXT/JSON column instead.
 */
class DataSynchronization implements DataSynchronizationInterface
{
    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var AdapterInterface
     */
    protected $connection;

    /**
     * Constructor.
     *
     * @param ResourceConnection $resourceConnection Magento's resource connection for database access.
     * @param LoggerInterface $logger               Logger for debugging and error reporting.
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        LoggerInterface $logger
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->logger = $logger;
        // Get the default database connection for write operations.
        $this->connection = $resourceConnection->getConnection();
    }

    /**
     * Synchronize generic data into dynamic database tables.
     *
     * @param string $type The type of data (e.g., "accounts_charter").
     * @param string $scope The scope of data (e.g., "icao_accounting").
     * @param string $jsonData JSON string representing the data to be synchronized.
     * @return bool True if data was synchronized successfully.
     * @throws LocalizedException If input is invalid, JSON is malformed, or DB operation fails.
     */
    public function syncData(string $type, string $scope, string $jsonData): bool
    {
        // Validate input parameters
        if (empty($type) || empty($scope) || empty($jsonData)) {
            $this->logger->error('API Input Error: Type, scope, or JSON data cannot be empty.');
            throw new LocalizedException(__('Type, scope, and JSON data cannot be empty.'));
        }

        try {
            // Decode the incoming JSON data
            $data = json_decode($jsonData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->logger->error('API JSON Parse Error: ' . json_last_error_msg());
                throw new LocalizedException(__('Invalid JSON data provided: %1', json_last_error_msg()));
            }

            // Build the dynamic table name (e.g., 'icao_accounting_accounts_charter')
            // Using resourceConnection->getTableName() to ensure table prefix is applied.
            $tableName = $this->resourceConnection->getTableName($this->buildDynamicTableName($scope, $type));

            // Check if table exists, create if not
            if (!$this->connection->isTableExists($tableName)) {
                $this->createDynamicTable($tableName, $data);
            }

            // Insert data into the dynamically created table
            $this->insertData($tableName, $data);

            return true;
        } catch (LocalizedException $e) {
            // Catch and re-throw LocalizedExceptions for user-friendly messages
            $this->logger->error('Data Synchronization Localized Error: ' . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            // Catch any other unexpected exceptions and log them as critical
            $this->logger->critical('Data Synchronization Critical Error: ' . $e->getMessage());
            throw new LocalizedException(__('An unexpected error occurred during data synchronization.'));
        }
    }

    /**
     * Builds a sanitized dynamic table name from scope and type.
     * Ensures the name is lowercase, uses underscores, and contains only alphanumeric characters.
     *
     * @param string $scope The scope part of the table name.
     * @param string $type The type part of the table name.
     * @return string The sanitized base table name (without Magento prefix).
     */
    protected function buildDynamicTableName(string $scope, string $type): string
    {
        // Sanitize to ensure valid table name characters (alphanumeric, underscores)
        $scope = preg_replace('/[^a-z0-9_]/', '', strtolower($scope));
        $type = preg_replace('/[^a-z0-9_]/', '', strtolower($type));

        // Construct the table name as per requirement: icao_accounting_accounts_charter
        return 'icao_' . $scope . '_' . $type;
    }

    /**
     * Creates a dynamic database table based on the keys present in the input JSON data.
     *
     * WARNING: This method uses direct DDL operations. This bypasses Magento's declarative
     * schema and migration system, which is intended for managed database changes.
     * Using this in production environments can lead to unmanageable schemas.
     *
     * @param string $tableName The full table name including Magento prefix.
     * @param array $data The associative array parsed from the JSON data, used to determine columns.
     * @throws LocalizedException If table creation fails.
     */
    protected function createDynamicTable(string $tableName, array $data): void
    {
        $this->logger->info(sprintf('Attempting to create dynamic table: "%s".', $tableName));
        try {
            // Create a new DDL Table object
            $table = new Table();
            $table->setName($tableName)
                  ->setOption('charset', 'utf8') // Set character set
                  ->setOption('collate', 'utf8_general_ci'); // Set collation

            // Add 'id' as the primary auto-increment column
            $table->addColumn(
                'id',
                Table::TYPE_BIGINT, // Using BIGINT for primary key
                null, // No specific length for BIGINT
                ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
                'Primary ID for the dynamic table'
            );

            // Add columns based on JSON data keys
            foreach ($data as $key => $value) {
                // Sanitize column name to ensure it's a valid database column name
                $columnName = preg_replace('/[^a-z0-9_]/', '', strtolower($key));
                if (empty($columnName) || $columnName === 'id' || isset($table->getColumns()[$columnName])) {
                    // Skip if sanitized name is empty, conflicts with the primary 'id', or already added
                    $this->logger->warning(sprintf('Skipping invalid, reserved, or duplicate column name "%s" for table "%s".', $key, $tableName));
                    continue;
                }

                // Determine column type based on the PHP type of the value
                $columnType = Table::TYPE_TEXT; // Default to TEXT
                $length = 255; // Default length for text

                if (is_int($value)) {
                    $columnType = Table::TYPE_INTEGER;
                    $length = null; // No specific length for INTEGER
                } elseif (is_float($value)) {
                    $columnType = Table::TYPE_DECIMAL;
                    $length = '12,4'; // Default precision for DECIMAL (e.g., 99999999.9999)
                } elseif (is_bool($value)) {
                    $columnType = Table::TYPE_SMALLINT; // TINYINT or SMALLINT for boolean (0 or 1)
                    $length = 1; // Smallest integer length
                } elseif (is_array($value) || is_object($value)) {
                    // Store nested arrays/objects as JSON strings
                    $columnType = Table::TYPE_TEXT;
                    $length = Table::MAX_TEXT_SIZE; // Use MAX_TEXT_SIZE for potentially large JSON data
                }
                // String remains TYPE_TEXT, length 255 by default, or could be adjusted if needed for very long strings.

                $table->addColumn(
                    $columnName,
                    $columnType,
                    $length,
                    ['nullable' => true], // Allow NULL values for flexibility
                    ucwords(str_replace('_', ' ', $columnName)) . ' Data' // Column comment
                );
            }

            // Add standard created_at and updated_at columns for auditing purposes
            // Check if they already exist, to avoid adding them twice if the table structure implies them
            if (!isset($table->getColumns()['created_at'])) {
                $table->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Creation Time'
                );
            }
            if (!isset($table->getColumns()['updated_at'])) {
                $table->addColumn(
                    'updated_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                    'Update Time'
                );
            }

            // Execute the DDL statement to create the table
            $this->connection->createTable($table);
            $this->logger->info(sprintf('Dynamic table "%s" created successfully with inferred columns.', $tableName));
        } catch (\Exception $e) {
            $this->logger->critical('Error creating dynamic table: ' . $e->getMessage());
            throw new LocalizedException(__('Failed to create dynamic table "%1". Error: %2', $tableName, $e->getMessage()));
        }
    }

    /**
     * Inserts data into the dynamic table.
     * This method prepares the data to match the table's schema and inserts it.
     * For true "upsert" (update if exists, insert if new), you would need a unique
     * identifier from the incoming JSON data to check for existing records.
     * This current implementation will always insert a new record.
     *
     * @param string $tableName The full table name including Magento prefix.
     * @param array $data The associative array parsed from the JSON data.
     * @throws LocalizedException If data insertion fails.
     */
    protected function insertData(string $tableName, array $data): void
    {
        // Get the current columns of the table to ensure we only insert valid data
        $columns = $this->connection->describeTable($tableName);
        $insertData = [];

        foreach ($data as $key => $value) {
            $columnName = preg_replace('/[^a-z0-9_]/', '', strtolower($key));

            // Only include columns that actually exist in the table (to prevent SQL errors)
            if (isset($columns[$columnName])) {
                if (is_array($value) || is_object($value)) {
                    // Serialize arrays/objects to JSON string for storage
                    $insertData[$columnName] = json_encode($value);
                } else {
                    $insertData[$columnName] = $value;
                }
            } else {
                $this->logger->warning(sprintf('Column "%s" from JSON data does not exist in table "%s". Skipping data for this column.', $key, $tableName));
            }
        }

        // Add timestamps for auditing, if not already present in the incoming data
        if (isset($columns['created_at']) && !isset($insertData['created_at'])) {
            $insertData['created_at'] = $this->connection->formatDate(time());
        }
        if (isset($columns['updated_at']) && !isset($insertData['updated_at'])) {
            $insertData['updated_at'] = $this->connection->formatDate(time());
        }

        if (empty($insertData)) {
            $this->logger->warning(sprintf('No valid data to insert into table "%s" after filtering. Request data was: %s', $tableName, json_encode($data)));
            return;
        }

        try {
            // Perform the insert operation
            $this->connection->insert($tableName, $insertData);
            $this->logger->info(sprintf('Data inserted into dynamic table "%s". Record ID: %d', $tableName, $this->connection->lastInsertId($tableName)));
        } catch (\Exception $e) {
            $this->logger->critical('Error inserting data into dynamic table: ' . $e->getMessage());
            throw new LocalizedException(__('Failed to insert data into table "%1". Error: %2', $tableName, $e->getMessage()));
        }
    }
}
