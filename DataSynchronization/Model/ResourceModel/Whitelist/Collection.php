<?php
namespace Icao\DataSynchronization\Model\ResourceModel\Whitelist;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Icao\DataSynchronization\Model\Whitelist::class,
            \Icao\DataSynchronization\Model\ResourceModel\Whitelist::class
        );
    }
}
