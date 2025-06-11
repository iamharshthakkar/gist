<?php
namespace Icao\DataCollection\Model;

use Icao\DataCollection\Api\DataInterface;

class Data implements DataInterface
{
    private $type;
    private $data;

    public function __construct(string $type, array $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
