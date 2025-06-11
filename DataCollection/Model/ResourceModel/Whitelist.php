<?php
namespace Icao\DataCollection\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Whitelist extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('icao_data_collection_whitelist', 'entity_id');
    }
}
