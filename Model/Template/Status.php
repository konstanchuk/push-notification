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


class Status implements OptionSourceInterface
{
    const ENABLED  = 1;
    const DISABLED = 0;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            [
                'label' => __('Enabled'),
                'value' => self::ENABLED,
            ],
            [
                'label' => __('Disabled'),
                'value' => self::DISABLED,
            ],
        ];

        return $options;
    }
}