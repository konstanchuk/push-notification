<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Model\Template;

use Magento\Framework\Data\OptionSourceInterface;
use Konstanchuk\PushNotification\Model\ResourceModel\Template\CollectionFactory;


class TemplateList implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var array
     */
    protected $options = null;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            /** @var \Konstanchuk\PushNotification\Model\ResourceModel\Template\Collection $matchingNamesCollection */
            $collection = $this->collectionFactory->create();
            $collection->addFieldToSelect(['id', 'title'])
                ->addFieldToFilter('status', ['eq' => Status::ENABLED]);
            $this->options = [];
            /** @var \Konstanchuk\PushNotification\Api\Data\TemplateInterface $item */
            foreach ($collection as $item) {
                $this->options[] = [
                    'label' => $item->getTitle(),
                    'value' => $item->getId(),
                ];
            }
        }
        return $this->options;
    }
}