<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Ui\Component\Listing\Column\Process;

use Konstanchuk\PushNotification\Model\Process\Status as StatusOptions;
use Magento\Ui\Component\Listing\Columns\Column;


class Status extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            /** @var \Konstanchuk\PushNotification\Model\Process\Status $status */
            $status = $objectManager->get('Konstanchuk\PushNotification\Model\Process\Status');
            $options = array_column($status->toOptionArray(), 'label', 'value');
            foreach ($dataSource['data']['items'] as &$item) {
                $class = '';
                switch ($item['status']) {
                    case StatusOptions::CANCELED:
                        $class = 'grid-severity-critical';
                        break;
                    case StatusOptions::FINISHED:
                        $class = 'grid-severity-notice';
                        break;
                    case StatusOptions::AWAITED:
                        $class = 'grid-severity-notice pn-awaited-status';
                        break;
                    case StatusOptions::IN_PROCESS:
                        $class = 'grid-severity-notice pn-in-process-status';
                        break;
                }
                $item['__original_' . $fieldName] = $item['status']; //fix for actions
                $text = $options[$item['status']] ?? $item['status'];
                $item[$fieldName] = '<span class="' . $class . '"><span>' . htmlspecialchars($text) . '</span></span>';
            }
        }
        return $dataSource;
    }
}
