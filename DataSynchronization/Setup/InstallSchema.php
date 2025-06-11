<?php
namespace Icao\DataSynchronization\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * InstallSchema class for Icao_DataSynchronization module.
 * Creates the static 'icao_data_synchronization_whitelist' table.
 *
 * NOTE: This InstallSchema is ONLY for the static 'whitelist' table.
 * Dynamic tables created via API are handled by DataSynchronization.php at runtime.
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Install DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        // Get database connection
        $connection = $installer->getConnection();

        // Define table name for whitelist
        $tableName = $installer->getTable('icao_data_synchronization_whitelist');

        // Check if the table already exists
        if ($connection->isTableExists($tableName) !== true) {
            $table = $connection->newTable(
                $tableName
            )->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ],
                'Whitelist ID'
            )->addColumn(
                'scope',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Data Scope (e.g., product, category, customer)'
            )->addColumn(
                'type',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Data Type within Scope (e.g., sku, email, id)'
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Creation Time'
            )->setComment(
                'Icao Data Synchronization Whitelist Table'
            );
            $connection->createTable($table);
            $installer->endSetup();
        }
    }
}
