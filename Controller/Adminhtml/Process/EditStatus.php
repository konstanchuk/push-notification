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


class EditStatus extends Process
{
    /**
     * @return void
     */
    public function execute()
    {
        try {
            $processId = $this->getRequest()->getParam('process_id');
            $status = $this->getRequest()->getParam('new_status');
            if ($processId && $status) {
                $process = $this->processRepository->get($processId);
                if ($process->getStatus() != ProcessStatus::IN_PROCESS && $status != ProcessStatus::IN_PROCESS) {
                    if ($process->getTemplateId()) {
                        $process->setStatus($status);
                    } else {
                        $process->setStatus(ProcessStatus::CANCELED);
                    }
                    $this->processRepository->save($process);
                    $this->messageManager->addSuccessMessage(__('The status of the process was changed.'));
                }
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }
}
