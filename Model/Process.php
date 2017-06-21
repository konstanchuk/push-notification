<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Model;

use Konstanchuk\PushNotification\Api\Data\ProcessInterface;
use Magento\Framework\Model\AbstractModel;


class Process extends AbstractModel implements ProcessInterface
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('Konstanchuk\PushNotification\Model\ResourceModel\Process');
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_getData('title');
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        return $this->setData('title', $title);
    }

    /**
     * @return int
     */
    public function getTemplateId()
    {
        return $this->_getData('template_id');
    }

    /**
     * @param int $templateId
     * @return $this
     */
    public function setTemplateId($templateId)
    {
        return $this->setData('template_id', $templateId);
    }

    /**
     * @return string
     */
    public function getParams()
    {
        return $this->_getData('params');
    }

    /**
     * @param string $params
     * @return $this
     */
    public function setParams($params)
    {
        return $this->setData('params', $params);
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->_getData('status');
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus($status)
    {
        return $this->setData('status', $status);
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->_getData('updated_at');
    }

    /**
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData('updated_at', $updatedAt);
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

    public function beforeSave()
    {
        if (!$this->getCreatedAt()) {
            $this->setCreatedAt(time());
        }
        $this->setUpdatedAt(time());
        return parent::beforeSave();
    }
}