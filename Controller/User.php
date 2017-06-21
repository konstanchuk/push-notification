<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Controller;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\HTTP\Header as HttpHeader;
use Magento\Customer\Model\Session as CustomerSession;
use Konstanchuk\PushNotification\Model\UserFactory;
use Konstanchuk\PushNotification\Api\UserRepositoryInterface;
use Magento\Framework\Controller\Result\JsonFactory;


abstract class User extends Action
{
    /** @var HttpHeader */
    protected $_httpHeader;

    /** @var UserFactory */
    protected $_userFactory;

    /** @var UserRepositoryInterface */
    protected $_userRepository;

    /** @var CustomerSession */
    protected $_customerSession;

    public function __construct(
        Context $context,
        HttpHeader $httpHeader,
        CustomerSession $customerSession,
        UserFactory $userFactory,
        UserRepositoryInterface $userRepository
    )
    {
        parent::__construct($context);
        $this->_httpHeader = $httpHeader;
        $this->_userFactory = $userFactory;
        $this->_userRepository = $userRepository;
        $this->_customerSession = $customerSession;
    }

    public function getBrowser($userAgent)
    {
        if (strpos($userAgent, 'MSIE') !== false && strpos($userAgent, 'Opera') === false && strpos($userAgent, 'Netscape') === false) {
            if (preg_match('/Blazer\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/', $userAgent, $matches)) {
                return 'Blazer ' . $matches[1];
            }
            if (preg_match('/MSIE ([0-9]{1,2}\.[0-9]{1,2})/', $userAgent, $matches)) {
                return 'Internet Explorer ' . $matches[1];
            }
        } elseif (strpos($userAgent, 'IEMobile') !== false) {
            if (preg_match('/IEMobile\/([0-9]{1,2}\.[0-9]{1,2})/', $userAgent, $matches)) {
                return 'Internet Explorer Mobile ' . $matches[1];
            }
        } elseif (strpos($userAgent, 'Gecko')) {
            if (preg_match('/Firefox\/([0-9]{1,2}\.[0-9]{1,2}(\.[0-9]{1,2})?)/', $userAgent, $matches)) {
                return 'Mozilla Firefox ' . $matches[1];
            }
            if (preg_match('/Netscape\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/', $userAgent, $matches)) {
                return 'Netscape ' . $matches[1];
            }
            if (preg_match('/Chrome\/([^\s]+)/', $userAgent, $matches)) {
                return 'Google Chrome ' . $matches[1];
            }
            if (preg_match('/Safari\/([0-9]{2,3}(\.[0-9])?)/', $userAgent, $matches)) {
                return 'Safari ' . $matches[1];
            }
            if (preg_match('/Galeon\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/', $userAgent, $matches)) {
                return 'Galeon ' . $matches[1];
            }
            if (preg_match('/Konqueror\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/', $userAgent, $matches)) {
                return 'Konqueror ' . $matches[1];
            }
            if (preg_match('/Fennec\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/', $userAgent, $matches)) {
                return 'Fennec' . $matches[1];
            }
            if (preg_match('/Maemo\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/', $userAgent, $matches)) {
                return 'Maemo' . $matches[1];
            }
            return 'Gecko based';
        } elseif (strpos($userAgent, 'Opera') !== false) {
            if (preg_match('/Opera[\/ ]([0-9]{1}\.[0-9]{1}([0-9])?)/', $userAgent, $matches)) {
                return 'Opera ' . $matches[1];
            }
        } elseif (strpos($userAgent, 'Lynx') !== false) {
            if (preg_match('/Lynx\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/', $userAgent, $matches)) {
                return 'Lynx ' . $matches[1];
            }
        } elseif (strpos($userAgent, 'Netscape') !== false) {
            if (preg_match('/Netscape\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/', $userAgent, $matches)) {
                return 'Netscape ' . $matches[1];
            }
        } else {
            return 'unknown';
        }
    }
}