<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\UrlInterface;


class Data extends AbstractHelper
{
    const XML_PATH_IS_ENABLED = 'web/konstanchuk_push_notification/active';
    const XML_PATH_SITE_NAME = 'web/konstanchuk_push_notification/site_name';
    const XML_PATH_GCM_SERVER_KEY = 'web/konstanchuk_push_notification/gcm_server_key';
    const XML_PATH_GCM_SENDER_ID = 'web/konstanchuk_push_notification/gcm_sender_id';
    const XML_PATH_DEFAULT_IMAGE = 'web/konstanchuk_push_notification/default_image';
    const XML_PATH_SUBSCRIBE_ON_BTN = 'web/konstanchuk_push_notification/subscribe_on_btn';
    const XML_PATH_SUBSCRIBE_BTN_CSS_CLASS = 'web/konstanchuk_push_notification/subscribe_btn_css_class';
    const XML_PATH_SUBSCRIBE_BTN_TEXT = 'web/konstanchuk_push_notification/subscribe_btn_text';
    const XML_PATH_UNSUBSCRIBE_BTN_TEXT = 'web/konstanchuk_push_notification/unsubscribe_btn_text';
    const XML_PATH_NOTIFICATION_TTL = 'web/konstanchuk_push_notification/notification_ttl';
    const XML_PATH_NOTIFICATION_URGENCY = 'web/konstanchuk_push_notification/notification_urgency';
    const XML_PATH_NOTIFICATION_BATCH_SIZE = 'web/konstanchuk_push_notification/notification_batch_size';

    const MANIFEST = 'manifest.json';

    const IMAGE_DIR = 'push_notification/config/image';

    /** @var \Konstanchuk\PushNotification\Logger\Logger  */
    protected $_pushNotificationLogger;

    public function __construct(Context $context, \Konstanchuk\PushNotification\Logger\Logger $pushNotificationLogger)
    {
        parent::__construct($context);
        $this->_pushNotificationLogger = $pushNotificationLogger;
    }

    public function isEnabled()
    {
        return (bool)$this->scopeConfig->getValue(static::XML_PATH_IS_ENABLED);
    }

    public function getSiteName()
    {
        return $this->scopeConfig->getValue(static::XML_PATH_SITE_NAME);
    }

    public function getGCMSenderId()
    {
        return trim($this->scopeConfig->getValue(static::XML_PATH_GCM_SENDER_ID));
    }

    public function getGCMServerKey()
    {
        return trim($this->scopeConfig->getValue(static::XML_PATH_GCM_SERVER_KEY));
    }

    public function getDefaultImage()
    {
        return $this->scopeConfig->getValue(static::XML_PATH_DEFAULT_IMAGE);
    }

    public function getDefaultImageUrl()
    {
        $defaultImage = trim($this->getDefaultImage(), '/');
        if ($defaultImage) {
            return rtrim($this->_urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]), '/')
                . '/' . trim(self::IMAGE_DIR, '/') . '/' . $defaultImage;
        }
        return null;
    }

    public function getSubscribeOnBtn()
    {
        return (bool)$this->scopeConfig->getValue(static::XML_PATH_SUBSCRIBE_ON_BTN);
    }

    public function getSubscribeBtnCssClass()
    {
        return trim($this->scopeConfig->getValue(static::XML_PATH_SUBSCRIBE_BTN_CSS_CLASS));
    }

    public function getSubscribeBtnText()
    {
        return $this->scopeConfig->getValue(static::XML_PATH_SUBSCRIBE_BTN_TEXT);
    }

    public function getUnSubscribeBtnText()
    {
        return $this->scopeConfig->getValue(static::XML_PATH_UNSUBSCRIBE_BTN_TEXT);
    }

    public function getDefaultNotificationUrgency()
    {
        $value = $this->scopeConfig->getValue(static::XML_PATH_NOTIFICATION_URGENCY);
        return $value ?? \Konstanchuk\PushNotification\Model\Notification\Urgency::NORMAL;
    }

    public function getDefaultNotificationTTL()
    {
        $value = abs($this->scopeConfig->getValue(static::XML_PATH_NOTIFICATION_TTL));
        return $value ?? 2419200; // defaults to 4 weeks
    }

    public function getDefaultNotificationBatchSize()
    {
        $value = abs($this->scopeConfig->getValue(static::XML_PATH_NOTIFICATION_BATCH_SIZE));
        return $value ?? 1000;
    }

    public function getLogger()
    {
        return $this->_pushNotificationLogger;
    }

    public function getManifestFile()
    {
        return static::MANIFEST;
    }
}