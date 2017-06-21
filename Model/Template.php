<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Model;

use Konstanchuk\PushNotification\Api\Data\TemplateInterface;
use Magento\Framework\Model\AbstractModel;


class Template extends AbstractModel implements TemplateInterface
{
    /** @var  \Konstanchuk\PushNotification\Model\Template\Image */
    protected $imageModel;

    /** @var  \Konstanchuk\PushNotification\Model\ProcessFactory */
    protected $processFactory;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Konstanchuk\PushNotification\Model\Template\Image $imageModel,
        \Konstanchuk\PushNotification\Model\ProcessFactory $processFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->imageModel = $imageModel;
        $this->processFactory = $processFactory;
    }

    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('Konstanchuk\PushNotification\Model\ResourceModel\Template');
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
     * @return string
     */
    public function getLink()
    {
        return $this->_getData('link');
    }

    /**
     * @param string $link
     * @return $this
     */
    public function setLink($link)
    {
        return $this->setData('link', $link);
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
     * @return int
     */
    public function getTTL()
    {
        return $this->_getData('ttl');
    }

    /**
     * @param int $ttl
     * @return $this
     */
    public function setTTL($ttl)
    {
        return $this->setData('ttl', $ttl);
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
     * @return string
     */
    public function getText()
    {
        return $this->_getData('text');
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText($text)
    {
        return $this->setData('text', $text);
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->_getData('image');
    }

    /**
     * @param string $image
     * @return $this
     */
    public function setImage($image)
    {
        return $this->setData('image', $image);
    }

    /**
     * @return bool
     */
    public function getActiveTransitions()
    {
        return $this->_getData('active_transitions');
    }

    /**
     * @param bool $status
     * @return $this
     */
    public function setActiveTransitions($status)
    {
        return $this->setData('active_transitions', $status);
    }

    /**
     * @return int
     */
    public function getCountTransitions()
    {
        return $this->_getData('count_transitions');
    }

    /**
     * @param int $count
     * @return $this
     */
    public function setCountTransitions($count)
    {
        return $this->setData('count_transitions', $count);
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->_getData('key');
    }

    /**
     * @param string $key
     * @return $this
     */
    public function setKey($key)
    {
        return $this->setData('key', $key);
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

    public function getImageFullUrl()
    {
        return $this->imageModel->getImageUrl($this->getImage());
    }

    public function getImageFullDir()
    {
        return $this->imageModel->getImageDir($this->getImage());
    }

    public function getRandomKey()
    {
        return uniqid();
    }

    public function beforeDelete()
    {
        $return = parent::beforeDelete();
        $image = $this->getImageFullDir();
        $this->removeFile($image);
        /** @var \Konstanchuk\PushNotification\Model\ResourceModel\Process\Collection $collection */
        $collection = $this->processFactory->create()->getCollection()
            ->addFieldToFilter('template_id', array('eq' => $this->getId()));
        /** @var \Konstanchuk\PushNotification\Model\Process $item */
        foreach($collection as $item) {
            $item->setStatus(\Konstanchuk\PushNotification\Model\Process\Status::CANCELED);
        }
        $collection->save();
        return $return;
    }

    public function beforeSave()
    {
        $return = parent::beforeSave();
        if (!$this->getCreatedAt()) {
            $this->setCreatedAt(time());
        }
        $this->setUpdatedAt(time());
        if (!$this->getKey()) {
            $this->setKey($this->getRandomKey());
        }
        if ($this->dataHasChangedFor('image')) {
            $image = $this->imageModel->getImageDir($this->getOrigData('image'));
            $this->removeFile($image);
        }
        return $return;
    }

    protected function removeFile($file)
    {
        if ($file && file_exists($file) && is_file($file)) {
            @unlink($file);
        }
    }
}