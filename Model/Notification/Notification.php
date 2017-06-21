<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Model\Notification;

use Konstanchuk\PushNotification\Helper\Data as Helper;
use Konstanchuk\PushNotification\Model\Notification\WebPush;
use Konstanchuk\PushNotification\Api\ProcessRepositoryInterface;
use Konstanchuk\PushNotification\Api\TemplateRepositoryInterface;
use Konstanchuk\PushNotification\Api\UserRepositoryInterface;
use Konstanchuk\PushNotification\Model\Template;
use Magento\Framework\Exception\NoSuchEntityException;
use \Konstanchuk\PushNotification\Model\Process\Status as ProcessStatus;


class Notification
{
    /** @var Helper  */
    protected $_helper;

    /** @var WebPush  */
    protected $_webPush;

    /** @var ProcessRepositoryInterface  */
    protected $_processRepository;

    /** @var UserRepositoryInterface  */
    protected $_userRepository;

    /** @var TemplateRepositoryInterface  */
    protected $_templateRepository;

    public function __construct(Helper $helper,
                                WebPush $webPush,
                                ProcessRepositoryInterface $processRepository,
                                UserRepositoryInterface $userRepository,
                                TemplateRepositoryInterface $templateRepository)
    {
        $this->_helper = $helper;
        $this->_webPush = $webPush;
        $this->_processRepository = $processRepository;
        $this->_userRepository = $userRepository;
        $this->_templateRepository = $templateRepository;
    }

    public function sendNext()
    {
        try {
            // return true if all process with some status finished
            $result = $this->executeProcess(\Konstanchuk\PushNotification\Model\Process\Status::IN_PROCESS);
            $this->_helper->getLogger()->addError($result);
            if ($result) {
                $result = $this->executeProcess(\Konstanchuk\PushNotification\Model\Process\Status::AWAITED);
            }
            $this->_helper->getLogger()->addError($result);
            return true;
        } catch (\Exception $e) {
            $this->_helper->getLogger()->critical(__('PUSH NOTIFICATION EXECUTE ERROR: %1', $e->getMessage()));
            return false;
        }
    }

    protected function executeProcess($status)
    {
        $process = $this->_processRepository->getNextProcess($status);
        if (!$process->getId()) {
            return true;
        }
        try {
            $templateId = $process->getTemplateId();
            if (!$templateId) {
                throw new \Exception('invalid template id');
            }
            $template = $this->_templateRepository->get($templateId);
            if ($template->getStatus() == Template\Status::DISABLED) {
                throw new \Exception('invalid template status');
            }
        } catch (\Exception $e) {
            $this->changeProcessStatus($process, ProcessStatus::CANCELED);
            return false;
        }

        $this->changeProcessStatus($process, ProcessStatus::IN_PROCESS);
        $params = $this->getProcessParams($process);
        if ($params && isset($params['users'])) { //specific users
            $users = $this->_userRepository->loadByIds($params['users']);
        } else { //all users
            $users = $this->_userRepository->loadByIds(null);
        }
        $this->checkWebPushResult($this->_webPush->sendPushNotification($template, $users));
        $this->changeProcessStatus($process, ProcessStatus::FINISHED);
        return false;
    }

    protected function checkWebPushResult($result)
    {
        $this->_helper->getLogger()->info(print_r($result, true));
    }

    protected function getProcessParams(\Konstanchuk\PushNotification\Api\Data\ProcessInterface $process)
    {
        $params = $process->getParams();
        if ($params) {
            $params = json_decode($params);
        }
        return $params;
    }

    protected function changeProcessStatus(\Konstanchuk\PushNotification\Api\Data\ProcessInterface $process, $status)
    {
        $process->setStatus($status);
        $this->_processRepository->save($process);
    }
}