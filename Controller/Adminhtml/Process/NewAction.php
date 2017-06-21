<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Controller\Adminhtml\Process;

use Konstanchuk\PushNotification\Controller\Adminhtml\Process;


class NewAction extends Process
{
    /**
     * Create new process action
     *
     * @return void
     */
    public function execute()
    {
        $model = $this->processFactory->create();

        // Restore previously entered form data from session
        /*$data = $this->_getSession()->getFFFFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }*/
        $this->coreRegistry->register('push_notification_process', $model);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Push Notification Manage Process'));
        return $resultPage;
    }
}