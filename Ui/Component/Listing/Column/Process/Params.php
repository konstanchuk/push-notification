<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Ui\Component\Listing\Column\Process;

use Magento\Ui\Component\Listing\Columns\Column;


class Params extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
                $data = json_decode($item['params']);
                if ($data == null) {
                    $html = '';
                } else {
                    $html = '<div class="push-notification-json">%s</div>';
                    $html = sprintf($html, json_encode($data, JSON_PRETTY_PRINT));
                }
                $item[$fieldName] = $html;
            }
        }
        return $dataSource;
    }
}
