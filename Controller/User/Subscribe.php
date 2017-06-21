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


class Subscribe extends User
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
        /** @var \Magento\Framework\Controller\Result\Json $result */
        $result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON);
        try {
            if (!$endpoint) {
                throw new \Exception('endpoint is not set');
            }
            $browser = $this->getBrowser($this->_httpHeader->getHttpUserAgent());
            if ($this->_customerSession->isLoggedIn()) {
                $customerId = $this->_customerSession->getCustomer()->getId();
            } else {
                $customerId = null;
            }
            try {
                $user = $this->_userRepository->getByEndpoint($endpoint);
                if ($customerId && !$user->getCustomerId()) {
                    $user->setCustomerId($customerId);
                }
            } catch (NoSuchEntityException $e) {
                /** @var \Konstanchuk\PushNotification\Api\Data\UserInterface $user */
                $user = $this->_userFactory->create();
                $user->setEndpoint($endpoint);
                $user->setCustomerId($customerId);
                $user->setP256DH($this->getRequest()->getPost('p256dh'));
                $user->setAuth($this->getRequest()->getPost('auth'));
                $user->setBrowser($browser);
            }
            $this->_userRepository->save($user);
            $result->setData([
                'status' => 'success',
                'customer' => (bool)$customerId,
            ]);
        } catch (\Exception $e) {
            $result->setData([
                'status' => 'error',
            ]);
        }
        return $result;
    }
}