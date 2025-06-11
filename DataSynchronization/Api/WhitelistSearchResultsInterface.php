<?php
namespace Icao\DataSynchronization\Api;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for Whitelist search results.
 * @api
 */
interface WhitelistSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get items.
     *
     * @return \Icao\DataSynchronization\Api\WhitelistInterface[]
     */
    public function getItems();

    /**
     * Set items.
     *
     * @param \Icao\DataSynchronization\Api\WhitelistInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
