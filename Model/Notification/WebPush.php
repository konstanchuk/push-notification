<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Model\Notification;

use Konstanchuk\PushNotification\Helper\Data as Helper;
use Konstanchuk\PushNotification\Model\Template;
use Konstanchuk\PushNotification\Model\User;
use Konstanchuk\PushNotification\Model\ResourceModel\User\Collection as CollectionUsers;


class WebPush
{
    /** @var Helper  */
    protected $_helper;

    /** @var \Minishlink\WebPush\WebPush  */
    protected $_webPush;

    /** @var array  */
    protected $_payloadCache = [];

    public function __construct(Helper $helper)
    {
        $this->_helper = $helper;
        $this->initWebPush();
    }

    protected function initWebPush()
    {
        //change in future
        $auth = [
            'GCM' => $this->_helper->getGCMServerKey(), // deprecated and optional, it's here only for compatibility reasons
        ];
        $defaultOptions = [];
        $defaultOptions['TTL'] = $this->_helper->getDefaultNotificationTTL();
        $defaultOptions['urgency'] = $this->_helper->getDefaultNotificationUrgency();
        $defaultOptions['batchSize'] = $this->_helper->getDefaultNotificationBatchSize();;
        $this->_webPush = new \Minishlink\WebPush\WebPush($auth, $defaultOptions);
    }

    public function sendPushNotification(Template $template, CollectionUsers $users)
    {
        $payload = $this->generatePayload($template);
        /** @var User $user */
        foreach ($users as $user) {
            $this->_webPush->sendNotification($user->getEndpoint(), $payload, $user->getP256DH(), $user->getAuth());
        }
        return $this->_webPush->flush();
    }

    protected function generatePayload(Template $template)
    {
        $templateId = $template->getId();
        if (isset($this->_payloadCache[$templateId])) {
            return $this->_payloadCache[$templateId];
        }
        $data = [
            'title' => $template->getTitle(),
            'href' => $template->getLink(),
            'message' => $template->getText(),
            'icon' => '', //change in future
        ];
        $payload = json_encode($data);
        $this->_payloadCache[$templateId] = $payload;
        return $payload;
    }
}