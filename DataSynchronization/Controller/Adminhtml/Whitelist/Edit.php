<?php
namespace Icao\DataSynchronization\Controller\Adminhtml\Whitelist;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Icao\DataSynchronization\Model\WhitelistFactory;
use Magento\Framework\Registry;

/**
 * Controller for editing an existing Whitelist item or preparing a new one using UI Component form.
 */
class Edit extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Icao_DataSynchronization::whitelist';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var WhitelistFactory
     */
    protected $whitelistFactory;

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param WhitelistFactory $whitelistFactory
     * @param Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        WhitelistFactory $whitelistFactory,
        Registry $coreRegistry
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->whitelistFactory = $whitelistFactory;
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * Edit or create a Whitelist item.
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        $model = $this->whitelistFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Whitelist item no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        // Register the model in the registry for data provider to pick up (if needed, though DataProvider often loads its own)
        $this->coreRegistry->register('icao_datasynchronization_whitelist', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Icao_DataSynchronization::whitelist_management');
        $resultPage->addBreadcrumb(__('Whitelist'), __('Whitelist'));
        $resultPage->addBreadcrumb(
            $id ? __('Edit Whitelist Item') : __('New Whitelist Item'),
            $id ? __('Edit Whitelist Item') : __('New Whitelist Item')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Whitelist Management'));
        $resultPage->getConfig()->getTitle()->prepend(
            $model->getId() ? $model->getScope() : __('New Whitelist Item')
        );

        return $resultPage;
    }

    /**
     * Check if the user has authorization to access the module.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }
}
