<?php
namespace Icao\DataSynchronization\Controller\Adminhtml\Whitelist;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Controller for displaying the Whitelist UI Component grid.
 * This controller is responsible for setting up the basic page layout and title
 * for the admin grid, which is then rendered by the UI Component system.
 */
class Index extends Action
{
    /**
     * Authorization level of a basic admin session
     * This constant defines the ACL resource that a user must have permission for
     * to access this controller action.
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Icao_DataSynchronization::whitelist';

    /**
     * @var PageFactory
     * Factory to create Magento Backend page results.
     */
    protected $resultPageFactory;

    /**
     * Constructor.
     *
     * @param Context $context           Magento's backend action context.
     * @param PageFactory $resultPageFactory Factory for creating page results.
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Execute action to display the Whitelist grid page.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        // Set the active menu item in the backend navigation.
        $resultPage->setActiveMenu('Icao_DataSynchronization::whitelist_management');

        // Add breadcrumbs for navigation hierarchy.
        $resultPage->addBreadcrumb(__('Whitelist'), __('Whitelist'));
        $resultPage->addBreadcrumb(__('Manage Whitelist'), __('Manage Whitelist'));

        // Set the page title in the browser and admin panel header.
        $resultPage->getConfig()->getTitle()->prepend(__('Whitelist Management'));

        // Return the page result, which will render the UI Component defined in its layout XML.
        return $resultPage;
    }

    /**
     * Check if the current user has authorization to access this resource.
     * This method is called internally by Magento's security layer.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        // Check if the user is allowed to access the defined ADMIN_RESOURCE.
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }
}
