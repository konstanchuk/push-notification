<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Controller\Adminhtml;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;
use Magento\Framework\Registry;
use Konstanchuk\PushNotification\Api\ProcessRepositoryInterface;
use Konstanchuk\PushNotification\Model\ProcessFactory;


abstract class Process extends Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * Result page factory
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Process model factory
     *
     * @var \Konstanchuk\PushNotification\Model\ProcessFactory
     */
    protected $processFactory;

    /*
     * @var $processRepository ProcessRepositoryInterface
     */
    protected $processRepository;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param ProcessFactory $processFactory
     * @param ProcessRepositoryInterface $processRepository
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        ProcessFactory $processFactory,
        ProcessRepositoryInterface $processRepository
    )
    {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->processFactory = $processFactory;
        $this->processRepository = $processRepository;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Konstanchuk_PushNotification::manage_process');
    }
}