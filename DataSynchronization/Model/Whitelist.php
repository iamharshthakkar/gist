<?php
namespace Icao\DataSynchronization\Model;

use Magento\Framework\Model\AbstractModel;
use Icao\DataSynchronization\Api\WhitelistInterface;
use Icao\DataSynchronization\Api\Data\WhitelistExtensionInterface; // Use the correct extension interface namespace
use Magento\Framework\Api\AttributeValueFactory; // Added for Extension Attributes
use Magento\Framework\Api\ExtensionAttributesFactory; // Added for Extension Attributes

/**
 * Whitelist Model.
 * Represents a single Whitelist entity.
 */
class Whitelist extends AbstractModel implements WhitelistInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'icao_datasynchronization_whitelist';

    /**
     * @var string
     */
    protected $_eventObject = 'whitelist';

    /**
     * @var ExtensionAttributesFactory
     */
    protected $extensionAttributesFactory;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Icao\DataSynchronization\Model\ResourceModel\Whitelist $resource
     * @param \Icao\DataSynchronization\Model\ResourceModel\Whitelist\Collection $resourceCollection
     * @param ExtensionAttributesFactory $extensionAttributesFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Icao\DataSynchronization\Model\ResourceModel\Whitelist $resource,
        \Icao\DataSynchronization\Model\ResourceModel\Whitelist\Collection $resourceCollection,
        ExtensionAttributesFactory $extensionAttributesFactory,
        array $data = []
    ) {
        $this->extensionAttributesFactory = $extensionAttributesFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Icao\DataSynchronization\Model\ResourceModel\Whitelist::class);
    }

    /**
     * Get entity ID.
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Set entity ID.
     *
     * @param int $id
     * @return \Icao\DataSynchronization\Api\WhitelistInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * Get scope.
     *
     * @return string|null
     */
    public function getScope()
    {
        return $this->getData(self::SCOPE);
    }

    /**
     * Set scope.
     *
     * @param string $scope
     * @return \Icao\DataSynchronization\Api\WhitelistInterface
     */
    public function setScope($scope)
    {
        return $this->setData(self::SCOPE, $scope);
    }

    /**
     * Get type.
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * Set type.
     *
     * @param string $type
     * @return \Icao\DataSynchronization\Api\WhitelistInterface
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * Get created at timestamp.
     *
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set created at timestamp.
     *
     * @param string $createdAt
     * @return \Icao\DataSynchronization\Api\WhitelistInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Icao\DataSynchronization\Api\Data\WhitelistExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        $extensionAttributes = $this->getData(self::KEY_EXTENSION_ATTRIBUTES);
        if ($extensionAttributes === null) {
            $extensionAttributes = $this->extensionAttributesFactory->create(
                WhitelistExtensionInterface::class
            );
            $this->setExtensionAttributes($extensionAttributes);
        }
        return $extensionAttributes;
    }

    /**
     * Set an extension attributes object.
     *
     * @param \Icao\DataSynchronization\Api\Data\WhitelistExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(WhitelistExtensionInterface $extensionAttributes)
    {
        return $this->setData(self::KEY_EXTENSION_ATTRIBUTES, $extensionAttributes);
    }
}
