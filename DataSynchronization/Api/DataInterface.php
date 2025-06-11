<?php
namespace Icao\DataSynchronization\Api;

interface DataInterface
{
    public function getType(): string;
    public function getData(): array;
}
