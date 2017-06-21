<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Api\Data;


interface UserInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getEndpoint();

    /**
     * @param string $endpoint
     * @return $this
     */
    public function setEndpoint($endpoint);

    /**
     * @return string
     */
    public function getP256DH();

    /**
     * @param string $p256dh
     * @return $this
     */
    public function setP256DH($p256dh);

    /**
     * @return string
     */
    public function getAuth();

    /**
     * @param string $auth
     * @return $this
     */
    public function setAuth($auth);

    /**
     * @return string
     */
    public function getBrowser();

    /**
     * @param string $browser
     * @return $this
     */
    public function setBrowser($browser);

    /**
     * @return int
     */
    public function getCustomerId();

    /**
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId($customerId);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);
}