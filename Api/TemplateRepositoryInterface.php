<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Api;


interface TemplateRepositoryInterface
{
    /**
     * @param \Konstanchuk\PushNotification\Api\Data\TemplateInterface $template
     * @return int
     */
    public function save(\Konstanchuk\PushNotification\Api\Data\TemplateInterface $template);

    /**
     * @param $templateId
     * @return \Konstanchuk\PushNotification\Api\Data\TemplateInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($templateId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Konstanchuk\PushNotification\Api\Data\TemplateSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @param \Konstanchuk\PushNotification\Api\Data\TemplateInterface $template
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Konstanchuk\PushNotification\Api\Data\TemplateInterface $template);

    /**
     * @param int $templateId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($templateId);

    /**
     * @param $key
     * @return \Konstanchuk\PushNotification\Api\Data\TemplateInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByKey($key);
}