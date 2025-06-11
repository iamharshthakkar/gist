<?php
namespace Icao\DataSynchronization\Controller\Adminhtml\Whitelist;

use Magento\Backend\App\Action;

class NewAction extends Action
{
    const ADMIN_RESOURCE = 'Icao_DataSynchronization::whitelist';

    public function execute()
    {
        return $this->_forward('edit');
    }
}
