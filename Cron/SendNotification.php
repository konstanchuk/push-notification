<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Cron;

use Konstanchuk\PushNotification\Model\Notification\Notification;


class SendNotification
{
    /** @var Notification  */
    protected $_notification;

    public function __construct(Notification $notification)
    {
        $this->_notification = $notification;
    }

    public function execute()
    {
        $this->_notification->sendNext();
        return $this;
    }
}