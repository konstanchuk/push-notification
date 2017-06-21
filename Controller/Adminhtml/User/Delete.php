<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Controller\Adminhtml\User;

use Konstanchuk\PushNotification\Controller\Adminhtml\User;
use Magento\Framework\Exception\NoSuchEntityException;


class Delete extends User
{
    /**
     * @return void
     */
    public function execute()
    {
        $userId = (int)$this->getRequest()->getParam('id');
        if ($userId) {
            $user = null;
            try {
                $user = $this->userRepository->get($userId);
                $this->userRepository->delete($user);
                $this->messageManager->addSuccessMessage(__('The user has been deleted.'));
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('This user no longer exists.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                if ($user && $user->getId()) {
                    $this->_redirect('*/*/edit', ['id' => $user->getId()]);
                }
            }
        }
        $this->_redirect('*/*/');
    }
}