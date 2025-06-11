<?php
namespace Icao\DataCollection\Controller\Adminhtml\Whitelist;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    const ADMIN_RESOURCE = 'Icao_DataCollection::whitelist';

    private $resultPageFactory;

    public function __construct(Action\Context $context, PageFactory $resultPageFactory)
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $page = $this->resultPageFactory->create();
        $page->setActiveMenu('Icao_DataCollection::whitelist');
        $page->getConfig()->getTitle()->prepend(__('Data Collection Whitelist'));
        return $page;
    }
}
