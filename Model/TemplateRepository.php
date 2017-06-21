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
use Konstanchuk\PushNotification\Api\TemplateRepositoryInterface;


class TemplateRepository implements TemplateRepositoryInterface
{
    /**
     * @var $entities \Konstanchuk\PushNotification\Api\Data\TemplateInterface[]
     */
    protected $entities = [];

    /**
     * @var $allLoaded bool
     */
    protected $allLoaded = false;

    /**
     * @var $templateResource \Konstanchuk\PushNotification\Model\ResourceModel\Template
     */
    protected $templateResource;

    /**
     * @var $templateFactory \Konstanchuk\PushNotification\Model\TemplateFactory
     */
    protected $templateFactory;

    /**
     * @var $collectionFactory \Konstanchuk\PushNotification\Model\ResourceModel\Template\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var $searchResultsFactory \Konstanchuk\PushNotification\Api\Data\TemplateSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /** @var $dataObjectHelper \Magento\Framework\Api\DataObjectHelper; */
    protected $dataObjectHelper;

    public function __construct(
        \Konstanchuk\PushNotification\Model\ResourceModel\Template $templateResource,
        \Konstanchuk\PushNotification\Model\TemplateFactory $templateFactory,
        \Konstanchuk\PushNotification\Model\ResourceModel\Template\CollectionFactory $collectionFactory,
        \Konstanchuk\PushNotification\Api\Data\TemplateSearchResultsInterfaceFactory $searchResultsFactory,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
    )
    {
        $this->templateResource = $templateResource;
        $this->templateFactory = $templateFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * @param \Konstanchuk\PushNotification\Api\Data\TemplateInterface $template
     * @return int
     */
    public function save(\Konstanchuk\PushNotification\Api\Data\TemplateInterface $template)
    {
        try {
            $this->templateResource->save($template);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $template->getId();
    }

    /**
     * @param $templateId
     * @return \Konstanchuk\PushNotification\Api\Data\TemplateInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($templateId)
    {
        if (isset($this->entities[$templateId])) {
            return $this->entities[$templateId];
        }
        /* @var $template \Konstanchuk\PushNotification\Model\Template */
        $template = $this->templateFactory->create();
        $this->templateResource->load($template, $templateId);
        if (!$template->getId()) {
            throw new NoSuchEntityException(__('Template does not exist'));
        }
        $this->entities[$templateId] = $template;
        return $template;
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Konstanchuk\PushNotification\Api\Data\TemplateSearchResultsInterface
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
        /** @var \Konstanchuk\PushNotification\Model\Template $item */
        foreach ($collection as $item) {
            /** @var \Konstanchuk\PushNotification\Model\Template $model */
            $model = $this->templateFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $model,
                $item->getData(),
                'Konstanchuk\PushNotification\Api\Data\TemplateInterface'
            );
            $items[] = $model;
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * @param int $templateId
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function deleteById($templateId)
    {
        $template = $this->get($templateId);
        return $this->delete($template);
    }

    public function delete(\Konstanchuk\PushNotification\Api\Data\TemplateInterface $template)
    {
        try {
            $this->templateResource->delete($template);
            if (isset($this->entities[$template->getId()])) {
                unset($this->entities[$template->getId()]);
            }
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Unable to remove Template with id "%1"', $template->getId()),
                $exception
            );
        }
        return true;
    }

    /**
     * @param $key
     * @return \Konstanchuk\PushNotification\Api\Data\TemplateInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByKey($key)
    {
        /* @var $template \Konstanchuk\PushNotification\Model\Template */
        $template = $this->templateFactory->create();
        $this->templateResource->load($template, $key, 'key');
        if (!$template->getId()) {
            throw new NoSuchEntityException(__('Template does not exist'));
        }
        return $template;
    }
}