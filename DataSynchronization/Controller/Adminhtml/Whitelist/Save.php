<?php
namespace Icao\DataSynchronization\Controller\Adminhtml\Whitelist;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Icao\DataSynchronization\Model\WhitelistFactory;
use Magento\Framework\Exception\LocalizedException;

/**
 * Controller for saving a Whitelist item from the UI Component form.
 */
class Save extends Action
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
     * Save action.
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // Get all post data. For UI Components, data is usually nested under the form name.
        $data = $this->getRequest()->getPostValue();

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $model = $this->whitelistFactory->create();
            $id = $this->getRequest()->getParam('entity_id');

            if ($id) {
                $model->load($id);
            }

            // Important: if your form fields are nested (e.g., whitelist[scope]),
            // you might need to extract the relevant array: $data = $data['whitelist'];
            // For this example, assuming direct fields.
            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Whitelist item.'));
                $this->_getSession()->setFormData(false); // Clear form data from session

                // Redirect based on 'back' parameter from UI Component form
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Whitelist item.'));
            }

            // Restore data to form if there was an error
            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }
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
