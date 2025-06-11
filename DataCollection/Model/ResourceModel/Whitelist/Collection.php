<?php
namespace Icao\DataCollection\Model\ResourceModel\Whitelist;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Icao\DataCollection\Model\Whitelist::class,
            \Icao\DataCollection\Model\ResourceModel\Whitelist::class
        );
    }
}
