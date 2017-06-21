<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Ui\Component\Listing\Column\Template;

use Magento\Ui\Component\Listing\Columns\Column;


class ActivateTransitions extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                if ($item['activate_transitions']) {
                    $class = 'grid-severity-notice';
                    $text = __('Enabled');
                } else {
                    $class = 'grid-severity-critical';
                    $text = __('Disabled');
                }
                $item[$fieldName] = '<span class="' . $class . '"><span>' . $text . '</span></span>';
            }
        }
        return $dataSource;
    }
}
