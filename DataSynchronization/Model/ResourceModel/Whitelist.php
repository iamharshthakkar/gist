<?php
namespace Icao\DataSynchronization\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Whitelist extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('icao_data_synchronization_whitelist','entity_id');
    }
}
