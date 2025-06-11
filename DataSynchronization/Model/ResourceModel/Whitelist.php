<?php
namespace Icao\DataSynchronization\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Whitelist Resource Model.
 * Defines the mapping between the Whitelist model and the database table.
 */
class Whitelist extends AbstractDb
{
    /**
     * Initialize resource model.
     * This method maps the model to the database table and defines the primary key.
     *
     * @return void
     */
    protected function _construct()
    {
        // 'icao_data_synchronization_whitelist' is the table name, 'entity_id' is the primary key column.
        $this->_init('icao_data_synchronization_whitelist', 'entity_id');
    }
}
