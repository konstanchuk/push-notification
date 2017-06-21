<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Controller\Adminhtml\Template;

use Konstanchuk\PushNotification\Controller\Adminhtml\Template;
use Magento\Framework\Exception\NoSuchEntityException;


class Delete extends Template
{
    /**
     * @return void
     */
    public function execute()
    {
        $templateId = (int)$this->getRequest()->getParam('id');
        if ($templateId) {
            $template = null;
            try {
                $template = $this->templateRepository->get($templateId);
                $this->templateRepository->delete($template);
                $this->messageManager->addSuccessMessage(__('The template has been deleted.'));
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('This template no longer exists.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                if ($template && $template->getId()) {
                    $this->_redirect('*/*/edit', ['id' => $template->getId()]);
                }
            }
        }
        $this->_redirect('*/*/');
    }
}