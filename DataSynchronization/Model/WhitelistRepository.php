<?php
namespace Icao\DataSynchronization\Model;

use Icao\DataSynchronization\Api\WhitelistRepositoryInterface;
use Icao\DataSynchronization\Api\WhitelistInterface;
use Icao\DataSynchronization\Api\WhitelistSearchResultsInterfaceFactory;
use Icao\DataSynchronization\Model\ResourceModel\Whitelist as ResourceWhitelist;
use Icao\DataSynchronization\Model\ResourceModel\Whitelist\CollectionFactory as WhitelistCollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\SortOrder; // Explicitly added SortOrder

/**
 * WhitelistRepository class.
 */
class WhitelistRepository implements WhitelistRepositoryInterface
{
    /**
     * @var ResourceWhitelist
     */
    protected $resource;

    /**
     * @var WhitelistFactory
     */
    protected $whitelistFactory;

    /**
     * @var WhitelistCollectionFactory
     */
    protected $whitelistCollectionFactory;

    /**
     * @var WhitelistSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var JoinProcessorInterface
     */
    protected $extensionAttributesJoinProcessor;

    /**
     * @var ExtensibleDataObjectConverter
     */
    protected $extensibleDataObjectConverter;

    /**
     * Constructor
     *
     * @param ResourceWhitelist $resource
     * @param WhitelistFactory $whitelistFactory
     * @param WhitelistCollectionFactory $whitelistCollectionFactory
     * @param WhitelistSearchResultsInterfaceFactory $searchResultsFactory
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceWhitelist $resource,
        WhitelistFactory $whitelistFactory,
        WhitelistCollectionFactory $whitelistCollectionFactory,
        WhitelistSearchResultsInterfaceFactory $searchResultsFactory,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->whitelistFactory = $whitelistFactory;
        $this->whitelistCollectionFactory = $whitelistCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * Save Whitelist.
     *
     * @param WhitelistInterface $whitelist
     * @return WhitelistInterface
     * @throws LocalizedException
     */
    public function save(WhitelistInterface $whitelist)
    {
        try {
            $this->resource->save($whitelist);
        } catch (\Exception $exception) {
            throw new LocalizedException(__('Could not save the whitelist: %1', $exception->getMessage()));
        }
        return $whitelist;
    }

    /**
     * Retrieve Whitelist by ID.
     *
     * @param int $id
     * @return WhitelistInterface
     * @throws NoSuchEntityException
     */
    public function getById($id)
    {
        $whitelist = $this->whitelistFactory->create();
        $this->resource->load($whitelist, $id);
        if (!$whitelist->getId()) {
            throw new NoSuchEntityException(__('Whitelist with id "%1" does not exist.', $id));
        }
        return $whitelist;
    }

    /**
     * Retrieve Whitelist matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Icao\DataSynchronization\Api\WhitelistSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->whitelistCollectionFactory->create();

        // Add filters from search criteria
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[] = $filter->getField();
                $conditions[] = [$filter->getConditionType() => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }

        $this->extensionAttributesJoinProcessor->process($collection, WhitelistInterface::class);

        // Add sorting from search criteria
        foreach ($searchCriteria->getSortOrders() as $sortOrder) {
            $collection->addOrder(
                $sortOrder->getField(),
                ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
            );
        }

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Delete Whitelist.
     *
     * @param WhitelistInterface $whitelist
     * @return bool
     * @throws LocalizedException
     */
    public function delete(WhitelistInterface $whitelist)
    {
        try {
            $this->resource->delete($whitelist);
        } catch (\Exception $exception) {
            throw new LocalizedException(__('Could not delete the whitelist: %1', $exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Whitelist by ID.
     *
     * @param int $id
     * @return bool
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }
}
