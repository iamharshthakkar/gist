<?php
namespace Icao\DataSynchronization\Api;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Whitelist interface.
 * @api
 */
interface WhitelistInterface extends ExtensibleDataInterface
{
    const ENTITY_ID = 'entity_id';
    const SCOPE = 'scope';
    const TYPE = 'type';
    const CREATED_AT = 'created_at';

    /**
     * Get entity ID.
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set entity ID.
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get scope.
     *
     * @return string|null
     */
    public function getScope();

    /**
     * Set scope.
     *

     * @param string $scope
     * @return $this
     */
    public function setScope($scope);

    /**
     * Get type.
     *
     * @return string|null
     */
    public function getType();

    /**
     * Set type.
     *
     * @param string $type
     * @return $this
     */
    public function setType($type);

    /**
     * Get created at timestamp.
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created at timestamp.
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Icao\DataSynchronization\Api\Data\WhitelistExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Icao\DataSynchronization\Api\Data\WhitelistExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\Icao\DataSynchronization\Api\Data\WhitelistExtensionInterface $extensionAttributes);
}
