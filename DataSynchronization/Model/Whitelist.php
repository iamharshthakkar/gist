<?php
namespace Icao\DataSynchronization\Model;

use Magento\Framework\Model\AbstractModel;

class Whitelist extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Icao\DataSynchronization\Model\ResourceModel\Whitelist::class);
    }
}
