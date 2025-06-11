<?php
namespace Icao\DataSynchronization\Api;

/**
 * Interface for Icao Data Synchronization API.
 * This interface defines the contract for synchronizing generic data
 * to dynamically created database tables in Adobe Commerce.
 * @api
 */
interface DataSynchronizationInterface
{
    /**
     * Synchronize generic data into dynamic database tables.
     * The table name will be derived from 'scope' and 'type', and columns
     * will be inferred from the 'jsonData' keys.
     *
     * @param string $type      The type of data (e.g., "accounts_charter").
     * @param string $scope     The scope of data (e.g., "icao_accounting").
     * @param string $jsonData  JSON string representing the data to be synchronized.
     * Example: '{"field1": "value1", "field2": 123}'
     * @return bool True if data was synchronized successfully.
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function syncData(string $type, string $scope, string $jsonData): bool;
}
