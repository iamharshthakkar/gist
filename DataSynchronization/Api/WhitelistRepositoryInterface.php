<?php
namespace Icao\DataSynchronization\Api;

interface WhitelistRepositoryInterface
{
    public function isAllowed(string $scope, string $type): bool;
}
