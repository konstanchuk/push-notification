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
use Konstanchuk\PushNotification\Api\UserRepositoryInterface;


class UserRepository implements UserRepositoryInterface
{
    /**
     * @var $entities \Konstanchuk\PushNotification\Api\Data\UserInterface[]
     */
    protected $entities = [];

    /**
     * @var $allLoaded bool
     */
    protected $allLoaded = false;

    /**
     * @var $userResource \Konstanchuk\PushNotification\Model\ResourceModel\User
     */
    protected $userResource;

    /**
     * @var $userFactory \Konstanchuk\PushNotification\Model\UserFactory
     */
    protected $userFactory;

    /**
     * @var $collectionFactory \Konstanchuk\PushNotification\Model\ResourceModel\User\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var $searchResultsFactory \Konstanchuk\PushNotification\Api\Data\UserSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /** @var $dataObjectHelper \Magento\Framework\Api\DataObjectHelper; */
    protected $dataObjectHelper;

    /** @var \Magento\Framework\Api\SearchCriteriaBuilderFactory */
    protected $searchCriteriaBuilderFactory;

    public function __construct(
        \Konstanchuk\PushNotification\Model\ResourceModel\User $userResource,
        \Konstanchuk\PushNotification\Model\UserFactory $userFactory,
        \Konstanchuk\PushNotification\Model\ResourceModel\User\CollectionFactory $collectionFactory,
        \Konstanchuk\PushNotification\Api\Data\UserSearchResultsInterfaceFactory $searchResultsFactory,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Framework\Api\SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
    )
    {
        $this->userResource = $userResource;
        $this->userFactory = $userFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
    }

    /**
     * @param \Konstanchuk\PushNotification\Api\Data\UserInterface $user
     * @return int
     */
    public function save(\Konstanchuk\PushNotification\Api\Data\UserInterface $user)
    {
        try {
            $this->userResource->save($user);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $user->getId();
    }

    /**
     * @param $userId
     * @return \Konstanchuk\PushNotification\Api\Data\UserInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($userId)
    {
        if (isset($this->entities[$userId])) {
            return $this->entities[$userId];
        }
        /* @var $user \Konstanchuk\PushNotification\Model\User */
        $user = $this->userFactory->create();
        $this->userResource->load($user, $userId);
        if (!$user->getId()) {
            throw new NoSuchEntityException(__('User does not exist'));
        }
        $this->entities[$userId] = $user;
        return $user;
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Konstanchuk\PushNotification\Api\Data\UserSearchResultsInterface
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
        /** @var \Konstanchuk\PushNotification\Model\User $item */
        foreach ($collection as $item) {
            /** @var \Konstanchuk\PushNotification\Model\User $model */
            $model = $this->userFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $model,
                $item->getData(),
                'Konstanchuk\PushNotification\Api\Data\UserInterface'
            );
            $items[] = $model;
        }
        $searchResults->setItems($items);
        return $searchResults;

    }

    /**
     * @param int $userId
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function deleteById($userId)
    {
        $user = $this->get($userId);
        return $this->delete($user);
    }

    public function delete(\Konstanchuk\PushNotification\Api\Data\UserInterface $user)
    {
        try {
            if (isset($this->entities[$user->getId()])) {
                unset($this->entities[$user->getId()]);
            }
            $this->userResource->delete($user);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Unable to remove User with id "%1"', $user->getId()),
                $exception
            );
        }
        return true;
    }

    /**
     * @param $endpoint
     * @return \Konstanchuk\PushNotification\Api\Data\UserInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByEndpoint($endpoint)
    {
        /* @var $user \Konstanchuk\PushNotification\Model\User */
        $user = $this->userFactory->create();
        $this->userResource->load($user, $endpoint, 'endpoint');
        if (!$user->getId()) {
            throw new NoSuchEntityException(__('User does not exist'));
        }
        return $user;
    }

    /**
     * @param $ids array
     * @return \Konstanchuk\PushNotification\Model\ResourceModel\User\Collection
     */
    public function loadByIds($ids)
    {
        /** @var \Konstanchuk\PushNotification\Model\ResourceModel\User\Collection $collection */
        $collection = $this->collectionFactory->create();
        if ($ids) {
            $collection->addFieldToFilter('id', array('in' => $ids));
        }
        return $collection;
    }
}