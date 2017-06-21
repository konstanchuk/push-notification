<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Ui\Component\Listing\Column\Template;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Konstanchuk\PushNotification\Helper\Data as Helper;


class Image extends Column
{
    /** Url path */
    const URL_PATH_EDIT = 'push_notification/template/edit';
    const URL_PATH_DELETE = 'push_notification/template/delete';

    /** @var UrlInterface */
    protected $urlBuilder;

    /** @var StoreManagerInterface */
    protected $storeManager;

    /** @var Helper */
    protected $helper;

    /**
     * @var string
     */
    private $editUrl;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     * @param string $editUrl
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        StoreManagerInterface $storemanager,
        Helper $helper,
        array $components = [],
        array $data = [],
        $editUrl = self::URL_PATH_EDIT
    ) {
        $this->storeManager = $storemanager;
        $this->urlBuilder = $urlBuilder;
        $this->editUrl = $editUrl;
        $this->helper = $helper;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        /** @var \Konstanchuk\PushNotification\Model\Template\Image $imageModel */
        $imageModel = $objectManager->get('Konstanchuk\PushNotification\Model\Template\Image');

        $defaultImage = $this->helper->getDefaultImageUrl();

        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $template = new \Magento\Framework\DataObject($item);
                if ($template->getImage()) {
                    $imageUrl = $imageModel->getImageUrl($template->getImage());
                } else if ($defaultImage) {
                    $imageUrl = $defaultImage;
                } else {
                    continue;
                }
                $item[$fieldName . '_src'] = $imageUrl;
                $item[$fieldName . '_alt'] = $template->getTitle();
                $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                    'push_notification/template/edit',
                    [
                        'id' => $template->getId(),
                        'store' => $this->context->getRequestParam('store'),
                    ]
                );
                $item[$fieldName . '_orig_src'] = $imageUrl;
            }
        }

        return $dataSource;
    }
}