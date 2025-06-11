<?php
namespace Icao\DataCollection\Api;

interface WhitelistRepositoryInterface
{
    /**
     * Check if a scope+type combination is allowed
     *
     * @param string $scope
     * @param string $type
     * @return bool
     */
    public function isAllowed(string $scope, string $type): bool;
}
