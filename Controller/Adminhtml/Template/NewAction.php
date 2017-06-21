<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Controller\Adminhtml\Template;

use Konstanchuk\PushNotification\Controller\Adminhtml\Template;


class NewAction extends Template
{
    /**
     * Create new template action
     *
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}