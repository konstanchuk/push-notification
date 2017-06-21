<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Api\Data;


interface TemplateInterface
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
    public function getTitle();

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getLink();

    /**
     * @param string $link
     * @return $this
     */
    public function setLink($link);

    /**
     * @return string
     */
    public function getText();

    /**
     * @param string $text
     * @return $this
     */
    public function setText($text);

    /**
     * @return string
     */
    public function getImage();

    /**
     * @param string $image
     * @return $this
     */
    public function setImage($image);

    /**
     * @return int
     */
    public function getStatus();

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * @return bool
     */
    public function getActiveTransitions();

    /**
     * @param bool $status
     * @return $this
     */
    public function setActiveTransitions($status);

    /**
     * @return int
     */
    public function getCountTransitions();

    /**
     * @param int $count
     * @return $this
     */
    public function setCountTransitions($count);

    /**
     * @return int
     */
    public function getTTL();

    /**
     * @param int $ttl
     * @return $this
     */
    public function setTTL($ttl);

    /**
     * @return string
     */
    public function getKey();

    /**
     * @param string $key
     * @return $this
     */
    public function setKey($key);

    /**
     * @return string
     */
    public function getUpdatedAt();

    /**
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * @return string
     */
    public function getImageFullUrl();
}