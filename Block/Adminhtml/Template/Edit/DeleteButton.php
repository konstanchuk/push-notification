<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Block\Adminhtml\Template\Edit;

use Konstanchuk\PushNotification\Block\Adminhtml\GenericButton;


class DeleteButton extends GenericButton
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        if ($this->getTemplate() && $this->getTemplate()->getId()) {
            $data = [
                'label' => __('Delete Template'),
                'class' => 'delete',
                'id' => 'template-delete-button',
                'data_attribute' => [
                    'url' => $this->getDeleteUrl()
                ],
                'on_click' => '',
                'sort_order' => 20,
            ];
        } else {
            $data = [];
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['id' => $this->getTemplate()->getId()]);
    }

    /**
     * @return \Konstanchuk\PushNotification\Model\Template
     */
    public function getTemplate()
    {
        return $this->registry->registry('push_notification_template');
    }
}

