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


class Status implements OptionSourceInterface
{
    const FINISHED  = 1;
    const CANCELED = 2;
    const AWAITED = 3;
    const IN_PROCESS = 4;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            [
                'label' => __('Awaited'),
                'value' => self::AWAITED,
            ],
            [
                'label' => __('Finished'),
                'value' => self::FINISHED,
            ],
            [
                'label' => __('Canceled'),
                'value' => self::CANCELED,
            ],
            [
                'label' => __('In Process'),
                'value' => self::IN_PROCESS,
            ],
        ];

        return $options;
    }

    public function toArray()
    {
        return [
            self::AWAITED => __('Awaited'),
            self::FINISHED => __('Finished'),
            self::CANCELED => __('Canceled'),
            self::IN_PROCESS => __('In Process')
        ];
    }
}