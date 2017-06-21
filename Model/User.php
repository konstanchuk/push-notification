<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Model;

use Konstanchuk\PushNotification\Api\Data\UserInterface;
use Magento\Framework\Model\AbstractModel;


class User extends AbstractModel implements UserInterface
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('Konstanchuk\PushNotification\Model\ResourceModel\User');
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->_getData('endpoint');
    }

    /**
     * @param string $endpoint
     * @return $this
     */
    public function setEndpoint($endpoint)
    {
        return $this->setData('endpoint', $endpoint);
    }

    /**
     * @return string
     */
    public function getP256DH()
    {
        return $this->_getData('p256dh');
    }

    /**
     * @param string $p256dh
     * @return $this
     */
    public function setP256DH($p256dh)
    {
        return $this->setData('p256dh', $p256dh);
    }

    /**
     * @return string
     */
    public function getAuth()
    {
        return $this->_getData('auth');
    }

    /**
     * @param string $auth
     * @return $this
     */
    public function setAuth($auth)
    {
        return $this->setData('auth', $auth);
    }

    /**
     * @return string
     */
    public function getBrowser()
    {
        return $this->_getData('browser');
    }

    /**
     * @param string $browser
     * @return $this
     */
    public function setBrowser($browser)
    {
        return $this->setData('browser', $browser);
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->_getData('created_at');
    }

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData('created_at', $createdAt);
    }

    /**
     * @return int
     */
    public function getCustomerId()
    {
        return $this->_getData('customer_id');
    }

    /**
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId($customerId)
    {
        return $this->setData('customer_id', $customerId);
    }

    public function beforeSave()
    {
        if (!$this->getCreatedAt()) {
            $this->setCreatedAt(time());
        }
        return parent::beforeSave();
    }
}