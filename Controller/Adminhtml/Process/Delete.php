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
use Konstanchuk\PushNotification\Model\Process\Status as ProcessStatus;
use Magento\Framework\Exception\NoSuchEntityException;


class Delete extends Process
{
    /**
     * @return void
     */
    public function execute()
    {
        $processId = (int)$this->getRequest()->getParam('id');
        if ($processId) {
            $process = null;
            try {
                $process = $this->processRepository->get($processId);
                if ($process->getStatus() != ProcessStatus::IN_PROCESS) {
                    $this->processRepository->delete($process);
                    $this->messageManager->addSuccessMessage(__('The process has been deleted.'));
                } else {
                    $this->messageManager->addNoticeMessage(__('Only processes that do not have the status "in process" can be deleted.'));
                }
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('This process no longer exists.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                if ($process && $process->getId()) {
                    $this->_redirect('*/*/edit', ['id' => $process->getId()]);
                }
            }
        }
        $this->_redirect('*/*/');
    }
}