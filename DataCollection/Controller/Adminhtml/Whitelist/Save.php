<?php
namespace Icao\DataCollection\Controller\Adminhtml\Whitelist;

use Magento\Backend\App\Action;
use Icao\DataCollection\Model\WhitelistFactory;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action
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
        $data = $this->getRequest()->getPostValue();
        if (!$data) {
            return $this->_redirect('*/*/');
        }

        $id    = $this->getRequest()->getParam('entity_id');
        $model = $this->whitelistFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('Entry no longer exists.'));
                return $this->_redirect('*/*/');
            }
        }

        $model->setScope($data['scope']);
        $model->setType($data['type']);

        try {
            $model->save();
            $this->messageManager->addSuccessMessage(__('Whitelist entry saved.'));
            if ($this->getRequest()->getParam('back')) {
                return $this->_redirect('*/*/edit', ['entity_id' => $model->getId()]);
            }
            return $this->_redirect('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong.'));
        }

        $this->_session->setFormData($data);
        return $this->_redirect('*/*/edit', ['entity_id' => $id]);
    }
}
