<?php
namespace Icao\DataCollection\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class WhitelistActions extends Column
{
    const URL_PATH_EDIT   = 'data_collection/whitelist/edit';
    const URL_PATH_DELETE = 'data_collection/whitelist/delete';

    protected $urlBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['entity_id'])) {
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href'  => $this->urlBuilder->getUrl(self::URL_PATH_EDIT,   ['entity_id' => $item['entity_id']]),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href'    => $this->urlBuilder->getUrl(self::URL_PATH_DELETE, ['entity_id' => $item['entity_id']]),
                            'label'   => __('Delete'),
                            'confirm' => [
                                'title'   => __('Delete "${ $.$data.scope } - ${ $.$data.type }"'),
                                'message' => __('Are you sure you want to delete this entry?')
                            ]
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }
}
