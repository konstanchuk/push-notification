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
use Konstanchuk\PushNotification\Api\UserRepositoryInterface;
use Konstanchuk\PushNotification\Model\UserFactory;


abstract class User extends Action
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
     * User model factory
     *
     * @var \Konstanchuk\PushNotification\Model\UserFactory
     */
    protected $userFactory;

    /*
     * @var $userRepository UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param UserFactory $userFactory
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        UserFactory $userFactory,
        UserRepositoryInterface $userRepository
    )
    {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Konstanchuk_PushNotification::manage_user');
    }
}