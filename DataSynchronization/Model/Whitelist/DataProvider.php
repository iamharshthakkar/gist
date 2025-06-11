<?php
namespace Icao\DataSynchronization\Model\Whitelist;

use Icao\DataSynchronization\Model\ResourceModel\Whitelist\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\RequestInterface; // Added for RequestInterface

/**
 * DataProvider for the Whitelist UI Component Form.
 * This class fetches and prepares data for the form.
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var RequestInterface
     */
    protected $request; // Added RequestInterface

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request // Injected RequestInterface
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request, // Added RequestInterface to constructor
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->collectionFactory = $collectionFactory;
        $this->request = $request; // Assign RequestInterface
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        foreach ($items as $item) {
            // The UI Component form expects data in an array indexed by the primary key
            // and often nested under a specific key (e.g., 'whitelist').
            // Ensure this matches your form XML's dataScope if you use one.
            $this->loadedData[$item->getId()] = $item->getData();
            // If your form expects `whitelist[field_name]`, you might need:
            // $this->loadedData[$item->getId()]['whitelist'] = $item->getData();
        }

        // If creating a new record, ensure an empty data structure is provided for the form.
        $id = $this->request->getParam($this->getRequestFieldName()); // Use injected request object
        if (!$id && empty($this->loadedData)) {
            $this->loadedData[null] = $this->collection->getNewEmptyItem()->getData();
        }

        return $this->loadedData;
    }
}
