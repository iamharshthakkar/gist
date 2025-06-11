<?php
namespace Icao\DataSynchronization\Model;

use Icao\DataSynchronization\Api\DataInterface;

class Data implements DataInterface
{
    private $type; private $data;
    public function __construct(string $t, array $d){ $this->type=$t; $this->data=$d; }
    public function getType(): string { return $this->type; }
    public function getData(): array  { return $this->data; }
}
