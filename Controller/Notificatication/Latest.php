<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Controller\Notification;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Konstanchuk\PushNotification\Model\ResourceModel\Process\CollectionFactory as ProcessCollectionFactory;


class Latest extends Action
{
    /**
     * Process Collection factory
     *
     * @var \Konstanchuk\PushNotification\Model\ResourceModel\Process\CollectionFactory
     */
    protected $_processCollectionFactory;

    public function __construct(
        Context $context,
        ProcessCollectionFactory $processCollectionFactory
    )
    {
        parent::__construct($context);
        $this->_processCollectionFactory = $processCollectionFactory;
    }

    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $result */
        $result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON);
    }
}