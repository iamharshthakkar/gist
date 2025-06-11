<?php
namespace Icao\DataSynchronization\Api;

interface DataSynchronizationInterface
{
    /**
     * Append a new payload under a given scope.
     *
     * @param string $scope
     * @param \Icao\DataSynchronization\Api\DataInterface $dataWrapper
     * @return bool True on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function append(string $scope, DataInterface $dataWrapper): bool;

    /**
     * Replace existing payload(s) under a given scope.
     * Deletes previous data for the scope and then inserts the new payload.
     *
     * @param string $scope
     * @param \Icao\DataSynchronization\Api\DataInterface $dataWrapper
     * @return bool True on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function replace(string $scope, DataInterface $dataWrapper): bool;
}
