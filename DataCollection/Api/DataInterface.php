<?php
namespace Icao\DataCollection\Api;

interface DataInterface
{
    /**
     * Get type identifier
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Get arbitrary JSON data
     *
     * @return array
     */
    public function getData(): array;
}
