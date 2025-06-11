<?php
namespace Icao\DataSynchronization\Api;

use Icao\DataSynchronization\Api\WhitelistInterface;
use Icao\DataSynchronization\Api\WhitelistSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder; // Added for sort order processing

/**
 * Whitelist CRUD interface.
 * @api
 */
interface WhitelistRepositoryInterface
{
    /**
     * Save Whitelist.
     *
     * @param \Icao\DataSynchronization\Api\WhitelistInterface $whitelist
     * @return \Icao\DataSynchronization\Api\WhitelistInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(WhitelistInterface $whitelist);

    /**
     * Retrieve Whitelist by ID.
     *
     * @param int $id
     * @return \Icao\DataSynchronization\Api\WhitelistInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($id);

    /**
     * Retrieve Whitelist matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Icao\DataSynchronization\Api\WhitelistSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete Whitelist.
     *
     * @param \Icao\DataSynchronization\Api\WhitelistInterface $whitelist
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(WhitelistInterface $whitelist);

    /**
     * Delete Whitelist by ID.
     *
     * @param int $id
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($id);
}
