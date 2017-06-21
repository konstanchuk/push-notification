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
use Konstanchuk\PushNotification\Api\TemplateRepositoryInterface;
use Konstanchuk\PushNotification\Model\TemplateFactory;


abstract class Template extends Action
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
     * Template model factory
     *
     * @var \Konstanchuk\PushNotification\Model\TemplateFactory
     */
    protected $templateFactory;

    /*
     * @var $templateRepository TemplateRepositoryInterface
     */
    protected $templateRepository;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param TemplateFactory $templateFactory
     * @param TemplateRepositoryInterface $templateRepository
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        TemplateFactory $templateFactory,
        TemplateRepositoryInterface $templateRepository
    )
    {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->templateFactory = $templateFactory;
        $this->templateRepository = $templateRepository;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Konstanchuk_PushNotification::manage_template');
    }
}