<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Controller\User;

use Konstanchuk\PushNotification\Controller\User;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\NoSuchEntityException;


class Unsubscribe extends User
{
    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $endpoint = trim($this->getRequest()->getPost('endpoint'));
        try {
            if (!$endpoint) {
                throw new \Exception('endpoint is not set');
            }
            try {
                $user = $this->_userRepository->getByEndpoint($endpoint);
                $this->_userRepository->delete($user);
            } catch (NoSuchEntityException $e) {
            }
            $status = true;
        } catch (\Exception $e) {
            //echo $e->getMessage();
            $status = false;
        }
        /** @var \Magento\Framework\Controller\Result\Json $result */
        $result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON);
        $result->setData(['status' => $status ? 'success' : 'error']);
        return $result;
    }
}