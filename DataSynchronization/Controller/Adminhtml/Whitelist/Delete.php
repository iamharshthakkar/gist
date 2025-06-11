<?php
namespace Icao\DataSynchronization\Controller\Adminhtml\Whitelist;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Icao\DataSynchronization\Model\WhitelistFactory;
use Magento\Framework\Exception\LocalizedException;

/**
 * Controller for deleting a Whitelist item from the UI Component form.
 */
class Delete extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Icao_DataSynchronization::whitelist';

    /**
     * @var WhitelistFactory
     */
    protected $whitelistFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param WhitelistFactory $whitelistFactory
     */
    public function __construct(
        Context $context,
        WhitelistFactory $whitelistFactory
    ) {
        parent::__construct($context);
        $this->whitelistFactory = $whitelistFactory;
    }

    /**
     * Delete action.
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // Check if we got a valid ID from the request
        $id = $this->getRequest()->getParam('entity_id');
        if ($id) {
            try {
                // Init model and delete
                $model = $this->whitelistFactory->create();
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('The Whitelist item has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('We can\'t delete the Whitelist item right now. Please review the log and try again.'));
                $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a Whitelist item to delete.'));
        return $resultRedirect->setPath('*/*/');
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
