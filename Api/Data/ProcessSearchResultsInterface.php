<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;


interface ProcessSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Konstanchuk\PushNotification\Api\Data\ProcessInterface[]
     */
    public function getItems();

    /**
     * @param \Konstanchuk\PushNotification\Api\Data\ProcessInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}