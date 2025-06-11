<?php
namespace Icao\DataSynchronization\Model\Whitelist;

use Icao\DataSynchronization\Model\ResourceModel\Whitelist\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

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
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->collectionFactory = $collectionFactory;
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
            $this->loadedData[$item->getId()] = $item->getData();
        }

        return $this->loadedData;
    }
}
