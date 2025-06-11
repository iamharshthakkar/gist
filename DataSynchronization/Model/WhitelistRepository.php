<?php
namespace Icao\DataSynchronization\Model;

use Icao\DataSynchronization\Api\WhitelistRepositoryInterface;
use Icao\DataSynchronization\Model\ResourceModel\Whitelist\CollectionFactory;

class WhitelistRepository implements WhitelistRepositoryInterface
{
    private $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function isAllowed(string $scope, string $type): bool
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('scope', $scope)
            ->addFieldToFilter('type', $type)
            ->setPageSize(1);

        return (bool)$collection->getSize();
    }
}
