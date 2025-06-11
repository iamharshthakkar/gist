<?php
namespace Icao\DataCollection\Controller\Adminhtml\Whitelist;

use Magento\Backend\App\Action;

class NewAction extends Action
{
    const ADMIN_RESOURCE = 'Icao_DataCollection::whitelist';

    public function execute()
    {
        return $this->_forward('edit');
    }
}
