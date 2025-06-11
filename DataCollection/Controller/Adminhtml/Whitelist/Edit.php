<?php
namespace Icao\DataCollection\Controller\Adminhtml\Whitelist;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Icao\DataCollection\Model\WhitelistFactory;

class Edit extends Action
{
    const ADMIN_RESOURCE = 'Icao_DataCollection::whitelist';

    private $resultPageFactory;
    private $whitelistFactory;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        WhitelistFactory $whitelistFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->whitelistFactory  = $whitelistFactory;
    }

    public function execute()
    {
        $id    = $this->getRequest()->getParam('entity_id');
        $model = $this->whitelistFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This entry no longer exists.'));
                return $this->_redirect('*/*/');
            }
        }

        $data = $this->_session->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $page = $this->resultPageFactory->create();
        $page->setActiveMenu('Icao_DataCollection::whitelist');
        $title = $id ? __('Edit Whitelist Entry') : __('New Whitelist Entry');
        $page->getConfig()->getTitle()->prepend($title);
        return $page;
    }
}
