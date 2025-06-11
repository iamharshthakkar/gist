<?php
namespace Icao\DataSynchronization\Model\ResourceModel\Whitelist;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Whitelist Collection.
 * Used to retrieve collections of Whitelist models from the database.
 */
class Collection extends AbstractCollection
{
    /**
     * Initialize collection model.
     * Maps the collection to the Whitelist model and its resource model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Icao\DataSynchronization\Model\Whitelist::class,
            \Icao\DataSynchronization\Model\ResourceModel\Whitelist::class
        );
    }
}
