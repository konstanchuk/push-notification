<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Model\Process;

use Magento\Framework\Data\OptionSourceInterface;


class TypeMailingList implements OptionSourceInterface
{
    const EVERYONE  = 0;
    const SELECTED_USERS = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            [
                'label' => __('Send to everyone'),
                'value' => self::EVERYONE,
            ],
            [
                'label' => __('Send to selected users'),
                'value' => self::SELECTED_USERS,
            ],
        ];

        return $options;
    }
}