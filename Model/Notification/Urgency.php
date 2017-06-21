<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Model\Notification;

use Magento\Framework\Data\OptionSourceInterface;


class Urgency implements OptionSourceInterface
{
    const NORMAL  = 'normal';
    const VERY_LOW = 'very-low';
    const LOW = 'low';
    const HIGH = 'high';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            [
                'label' => __('normal'),
                'value' => self::NORMAL,
            ],
            [
                'label' => __('very-low'),
                'value' => self::VERY_LOW,
            ],
            [
                'label' => __('low'),
                'value' => self::LOW,
            ],
            [
                'label' => __('high'),
                'value' => self::HIGH,
            ],
        ];

        return $options;
    }
}