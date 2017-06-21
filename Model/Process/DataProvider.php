<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Model\Process;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\Registry;
use Magento\Framework\App\RequestInterface;
use Konstanchuk\PushNotification\Api\ProcessRepositoryInterface;
use Konstanchuk\PushNotification\Model\ResourceModel\Process\CollectionFactory;


class DataProvider extends AbstractDataProvider
{
    /** @var array  */
    protected $loadedData;

    /** @var \Magento\Framework\Registry  */
    protected $registry;

    /** @var ProcessRepositoryInterface  */
    protected $processRepository;

    /** @var \Magento\Framework\App\RequestInterface */
    protected $request;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Registry $registry,
        RequestInterface $request,
        ProcessRepositoryInterface $processRepository,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->processRepository = $processRepository;
        $this->registry = $registry;
        $this->request = $request;
        $this->collection = $collectionFactory->create();
    }

    public function getData()
    {
        return [];
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        try {
            $process = $this->getCurrentProcess();
            $this->loadedData[$process->getId()] = $process->getData();
        } catch (\Exception $e) {
            $this->loadedData = [];
        }
        return $this->loadedData;
    }

    /**
     * @return \Konstanchuk\PushNotification\Api\Data\ProcessInterface
     */
    public function getCurrentProcess()
    {
        /** @var \Konstanchuk\PushNotification\Api\Data\ProcessInterface $process */
        $process = $this->registry->registry('push_notification_process');
        if ($process && $process->getId()) {
            return $process;
        }
        $requestId = $this->request->getParam($this->requestFieldName);
        if ($requestId) {
            return $this->processRepository->get($requestId);
        }
        throw new NoSuchEntityException(__('This process no longer exists.'));
    }
}