<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Model;

use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Konstanchuk\PushNotification\Api\ProcessRepositoryInterface;


class ProcessRepository implements ProcessRepositoryInterface
{
    /**
     * @var $entities \Konstanchuk\PushNotification\Api\Data\ProcessInterface[]
     */
    protected $entities = [];

    /**
     * @var $allLoaded bool
     */
    protected $allLoaded = false;

    /**
     * @var $processResource \Konstanchuk\PushNotification\Model\ResourceModel\Process
     */
    protected $processResource;

    /**
     * @var $processFactory \Konstanchuk\PushNotification\Model\ProcessFactory
     */
    protected $processFactory;

    /**
     * @var $collectionFactory \Konstanchuk\PushNotification\Model\ResourceModel\Process\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var $searchResultsFactory \Konstanchuk\PushNotification\Api\Data\ProcessSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /** @var \Magento\Framework\Api\DataObjectHelper  */
    protected $dataObjectHelper;

    public function __construct(
        \Konstanchuk\PushNotification\Model\ResourceModel\Process $processResource,
        \Konstanchuk\PushNotification\Model\ProcessFactory $processFactory,
        \Konstanchuk\PushNotification\Model\ResourceModel\Process\CollectionFactory $collectionFactory,
        \Konstanchuk\PushNotification\Api\Data\ProcessSearchResultsInterfaceFactory $searchResultsFactory,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
    )
    {
        $this->processResource = $processResource;
        $this->processFactory = $processFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * @param \Konstanchuk\PushNotification\Api\Data\ProcessInterface $process
     * @return int
     */
    public function save(\Konstanchuk\PushNotification\Api\Data\ProcessInterface $process)
    {
        try {
            $this->processResource->save($process);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $process->getId();
    }

    /**
     * @param $processId
     * @return \Konstanchuk\PushNotification\Api\Data\ProcessInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($processId)
    {
        if (isset($this->entities[$processId])) {
            return $this->entities[$processId];
        }
        /* @var $process \Konstanchuk\PushNotification\Model\Process */
        $process = $this->processFactory->create();
        $this->processResource->load($process, $processId);
        if (!$process->getId()) {
            throw new NoSuchEntityException(__('Process does not exist'));
        }
        $this->entities[$processId] = $process;
        return $process;
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Konstanchuk\PushNotification\Api\Data\ProcessSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $collection = $this->collectionFactory->create();
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrdersData = $searchCriteria->getSortOrders();
        if ($sortOrdersData) {
            foreach ($sortOrdersData as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    $sortOrder->getDirection()
                );
            }
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $items = [];
        /** @var \Konstanchuk\PushNotification\Model\Process $item */
        foreach ($collection as $item) {
            /** @var \Konstanchuk\PushNotification\Model\Process $model */
            $model = $this->processFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $model,
                $item->getData(),
                'Konstanchuk\PushNotification\Api\Data\ProcessInterface'
            );
            $items[] = $model;
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * @param int $processId
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function deleteById($processId)
    {
        $process = $this->get($processId);
        return $this->delete($process);
    }

    public function delete(\Konstanchuk\PushNotification\Api\Data\ProcessInterface $process)
    {
        try {
            $this->processResource->delete($process);
            if (isset($this->entities[$process->getId()])) {
                unset($this->entities[$process->getId()]);
            }
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Unable to remove Process with id "%1"', $process->getId()),
                $exception
            );
        }
        return true;
    }

    /**
     * @param int $status
     * @return \Konstanchuk\PushNotification\Api\Data\ProcessInterface
     */
    public function getNextProcess($status = \Konstanchuk\PushNotification\Model\Process\Status::AWAITED)
    {
        /** @var \Konstanchuk\PushNotification\Model\ResourceModel\Process\Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('status', $status);
        /** @var \Konstanchuk\PushNotification\Api\Data\ProcessInterface $process */
        $process = $collection->getFirstItem();
        return $process;
    }
}