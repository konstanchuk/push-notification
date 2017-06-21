<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Api;


interface UserRepositoryInterface
{
    /**
     * @param \Konstanchuk\PushNotification\Api\Data\UserInterface $user
     * @return int
     */
    public function save(\Konstanchuk\PushNotification\Api\Data\UserInterface $user);

    /**
     * @param $userId
     * @return \Konstanchuk\PushNotification\Api\Data\UserInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($userId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Konstanchuk\PushNotification\Api\Data\UserSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @param \Konstanchuk\PushNotification\Api\Data\UserInterface $user
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Konstanchuk\PushNotification\Api\Data\UserInterface $user);

    /**
     * @param int $userId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($userId);

    /**
     * @param $endpoint
     * @return \Konstanchuk\PushNotification\Api\Data\UserInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByEndpoint($endpoint);

    /**
     * @param $ids array
     * @return \Konstanchuk\PushNotification\Model\ResourceModel\User\Collection
     */
    public function loadByIds($ids);
}