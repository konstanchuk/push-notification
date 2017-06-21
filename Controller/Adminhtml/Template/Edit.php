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


class Edit extends Template
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $templateId = $this->getRequest()->getParam('id');
        /** @var \Konstanchuk\PushNotification\Model\ResourceModel\Template $model */
        if ($templateId) {
            try {
                $model = $this->templateRepository->get($templateId);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('This template no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        } else {
            $model = $this->templateFactory->create();
        }

        // Restore previously entered form data from session
        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $this->coreRegistry->register('push_notification_template', $model);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Push Notification Manage Template'));
        return $resultPage;
    }
}