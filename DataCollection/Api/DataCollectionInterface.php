<?php
namespace Icao\DataCollection\Api;

interface DataCollectionInterface
{
    /**
     * Append a new payload under a scope+type.
     *
     * @param string $scope
     * @param \Icao\DataCollection\Api\DataInterface $dataWrapper
     * @return bool
     */
    public function append(string $scope, DataInterface $dataWrapper): bool;

    /**
     * Replace existing payloads under a scope+type.
     *
     * @param string $scope
     * @param \Icao\DataCollection\Api\DataInterface $dataWrapper
     * @return bool
     */
    public function replace(string $scope, DataInterface $dataWrapper): bool;
}
