<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Model\Template;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\Registry;
use Magento\Framework\App\RequestInterface;
use Konstanchuk\PushNotification\Api\TemplateRepositoryInterface;
use Konstanchuk\PushNotification\Model\ResourceModel\Template\CollectionFactory;


class DataProvider extends AbstractDataProvider
{
    /** @var array  */
    protected $loadedData;

    /** @var \Magento\Framework\Registry  */
    protected $registry;

    /** @var TemplateRepositoryInterface  */
    protected $templateRepository;

    /** @var \Magento\Framework\App\RequestInterface */
    protected $request;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Registry $registry,
        RequestInterface $request,
        TemplateRepositoryInterface $templateRepository,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->templateRepository = $templateRepository;
        $this->registry = $registry;
        $this->request = $request;
        $this->collection = $collectionFactory->create();
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        try {
            $template = $this->getCurrentTemplate();
            $data = $template->getData();
            if (isset($data['image'])) {
                unset($data['image']);
                $data['image'][0] = [
                    'name' => $template->getImage(),
                    'url' => $template->getImageFullUrl(),
                ];
            }
            $this->loadedData[$template->getId()] = $data;
        } catch (\Exception $e) {
            $this->loadedData = [];
        }
        return $this->loadedData;
    }

    /**
     * @return \Konstanchuk\PushNotification\Api\Data\TemplateInterface
     */
    public function getCurrentTemplate()
    {
        /** @var \Konstanchuk\PushNotification\Api\Data\TemplateInterface $template */
        $template = $this->registry->registry('push_notification_template');
        if ($template && $template->getId()) {
            return $template;
        }
        $requestId = $this->request->getParam($this->requestFieldName);
        if ($requestId) {
            return $this->templateRepository->get($requestId);
        }
        throw new NoSuchEntityException(__('This template no longer exists.'));
    }
}