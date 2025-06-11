<?php
namespace Icao\DataCollection\Controller\Adminhtml\Whitelist;

use Magento\Backend\App\Action;
use Icao\DataCollection\Model\WhitelistFactory;

class Delete extends Action
{
    const ADMIN_RESOURCE = 'Icao_DataCollection::whitelist';

    private $whitelistFactory;

    public function __construct(Action\Context $context, WhitelistFactory $whitelistFactory)
    {
        parent::__construct($context);
        $this->whitelistFactory = $whitelistFactory;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        if ($id) {
            $model = $this->whitelistFactory->create()->load($id);
            if ($model->getId()) {
                $model->delete();
                $this->messageManager->addSuccessMessage(__('Whitelist entry deleted.'));
                return $this->_redirect('*/*/');
            }
        }
        $this->messageManager->addErrorMessage(__('Unable to find entry to delete.'));
        return $this->_redirect('*/*/');
    }
}
