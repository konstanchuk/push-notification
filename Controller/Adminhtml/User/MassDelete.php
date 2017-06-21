<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Controller\Adminhtml\User;

use Magento\Ui\Component\MassAction\Filter;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Konstanchuk\PushNotification\Controller\Adminhtml\User;
use Konstanchuk\PushNotification\Api\UserRepositoryInterface;
use Konstanchuk\PushNotification\Model\UserFactory;
use Konstanchuk\PushNotification\Model\ResourceModel\User\CollectionFactory;


class MassDelete extends User
{
    /**
     * Massactions filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        UserFactory $userFactory,
        UserRepositoryInterface $userRepository,
        Filter $filter,
        CollectionFactory $collectionFactory
    )
    {
        parent::__construct($context, $coreRegistry, $resultPageFactory, $userFactory, $userRepository);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $userDeleted = 0;
        try {
            /** @var \Konstanchuk\PushNotification\Api\Data\UserInterface $user */
            foreach ($collection->getItems() as $user) {
                $this->userRepository->delete($user);
                ++$userDeleted;
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        if ($userDeleted) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) were deleted.', $userDeleted)
            );
        }

        $this->_redirect('*/*/index');
    }
}
