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


class TemplateTitle extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
                $text = isset($item['template_id']) && isset($item['template_title'])
                    ? htmlspecialchars($item['template_title'])
                    : sprintf('<b>%s</b><br /><i>%s</i>', __('The template was deleted.'), __('The process does not participate in the mailing.'));
                $item[$fieldName] = $text;
            }
        }
        return $dataSource;
    }
}
