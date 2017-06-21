<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Controller\Statistics;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Konstanchuk\PushNotification\Api\TemplateRepositoryInterface;
use Konstanchuk\PushNotification\Helper\Data as Helper;


class Click extends Action
{
    /** @var TemplateRepositoryInterface */
    protected $_templateRepository;

    /** @var Helper */
    protected $_helper;

    public function __construct(
        Context $context,
        TemplateRepositoryInterface $templateRepository,
        Helper $helper
    )
    {
        parent::__construct($context);
        $this->_templateRepository = $templateRepository;
        $this->_helper = $helper;
    }

    public function execute()
    {
        if (!($this->getRequest()->isPost() && $this->_helper->isEnabled())) {
            return;
        }
        $key = $this->getRequest()->getParam('key');
        if (!$key) {
            return;
        }
        try {
            $template = $this->_templateRepository->getByKey($key);
            if ($template->getActiveTransitions()) {
                $template->setCountTransitions($template->getCountTransitions() + 1);
                $this->_templateRepository->save($template);
            }
        } catch (\Exception $e) {
            $this->_helper->getLogger()->addError($e->getMessage());
        }
    }
}