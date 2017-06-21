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
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Konstanchuk\PushNotification\Api\ProcessRepositoryInterface;
use Konstanchuk\PushNotification\Model\ProcessFactory;
use Konstanchuk\PushNotification\Model\Process\TypeMailingList;


class Save extends Process
{
    /**
     * @var \Magento\Framework\Reflection\DataObjectProcessor
     */
    protected $dataObjectProcessor;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        ProcessFactory $processFactory,
        ProcessRepositoryInterface $processRepository,
        DataObjectProcessor $dataObjectProcessor
    )
    {
        parent::__construct($context, $coreRegistry, $resultPageFactory, $processFactory, $processRepository);
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $request = $this->getRequest();
        try {
            if (!$request->getPost()) {
                return;
            }
            /** @var \Konstanchuk\PushNotification\Api\Data\ProcessInterface $processModel */
            $processModel = $this->processFactory->create();
            $processModel->setTemplateId($request->getParam('template_id'));
            $processModel->setTitle($request->getParam('title'));
            $processModel->setStatus(\Konstanchuk\PushNotification\Model\Process\Status::AWAITED);
            $params = [];
            if ($request->getParam('type_mailing_list') == TypeMailingList::SELECTED_USERS) {
                $_selectedUsers = $request->getParam('params');
                $_selectedUsers = explode(',', $_selectedUsers);
                $_selectedUsers = array_map('intval', $_selectedUsers);
                $_selectedUsers = array_unique($_selectedUsers);
                if (count($_selectedUsers)) {
                    $params['users'] = $_selectedUsers;
                } else {

                }
            }
            $processModel->setParams(json_encode($params));
            // Save process
            $this->processRepository->save($processModel);

            // Display success message
            $this->messageManager->addSuccessMessage(__('The process has been saved.'));

            // Check if 'Save and Continue'
            if ($this->getRequest()->getParam('back')) {
                $this->_redirect('*/*/edit', ['id' => $processModel->getId(), '_current' => true]);
                return;
            }

            // Go to grid page
            $this->_redirect('*/*/');
            return;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        /*
                $this->_getSession()->setFormData($this->dataObjectProcessor->buildOutputDataArray(
                    $processModel,
                    '\Konstanchuk\PushNotification\Api\Data\ProcessInterface'
                ));
                $this->_redirect('*edit', ['id' => $processId]);
                */
    }
}
