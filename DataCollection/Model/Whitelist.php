<?php
namespace Icao\DataCollection\Model;

use Magento\Framework\Model\AbstractModel;

class Whitelist extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Icao\DataCollection\Model\ResourceModel\Whitelist::class);
    }
}
