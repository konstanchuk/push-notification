<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Api;


interface ProcessRepositoryInterface
{
    /**
     * @param \Konstanchuk\PushNotification\Api\Data\ProcessInterface $process
     * @return int
     */
    public function save(\Konstanchuk\PushNotification\Api\Data\ProcessInterface $process);

    /**
     * @param $processId
     * @return \Konstanchuk\PushNotification\Api\Data\ProcessInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($processId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Konstanchuk\PushNotification\Api\Data\ProcessSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @param \Konstanchuk\PushNotification\Api\Data\ProcessInterface $process
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Konstanchuk\PushNotification\Api\Data\ProcessInterface $process);

    /**
     * @param int $processId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($processId);

    /**
     * @param int $status
     * @return \Konstanchuk\PushNotification\Api\Data\ProcessInterface
     */
    public function getNextProcess($status);
}