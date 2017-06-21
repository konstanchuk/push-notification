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
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Konstanchuk\PushNotification\Api\TemplateRepositoryInterface;
use Konstanchuk\PushNotification\Model\TemplateFactory;


class Save extends Template
{
    /**
     * @var \Magento\Framework\Reflection\DataObjectProcessor
     */
    protected $dataObjectProcessor;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        TemplateFactory $templateFactory,
        TemplateRepositoryInterface $templateRepository,
        DataObjectProcessor $dataObjectProcessor
    )
    {
        parent::__construct($context, $coreRegistry, $resultPageFactory, $templateFactory, $templateRepository);
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * @return void
     */
    public function execute()
    {
        if (!$this->getRequest()->getPost()) {
            return;
        }
        $templateId = $this->getRequest()->getParam('id');

        /* @var $templateModel \Konstanchuk\PushNotification\Model\Template */
        $templateModel = null;
        if ($templateId) {
            try {
                $templateModel = $this->templateRepository->get($templateId);
                $templateModel->setOrigData();
            } catch (\Exception $e) {
            }
        }

        if (is_null($templateModel)) {
            $templateModel = $this->templateFactory->create();
        }

        $request = $this->getRequest();
        $templateModel->setTitle($request->getParam('title'));
        $templateModel->setLink($request->getParam('link'));
        $templateModel->setText($request->getParam('text'));
        $templateModel->setStatus($request->getParam('status'));
        $templateModel->setTTL(abs($request->getParam('ttl')) ?? null);
        $templateModel->setActiveTransitions($request->getParam('active_transitions'));
        $image = $request->getParam('image');
        if ($image && isset($image[0]) && isset($image[0]['file'])) {
            $templateModel->setImage($image[0]['file']);
        } else {
            $templateModel->setImage(null);
        }

        try {
            // Save template
            $this->templateRepository->save($templateModel);

            // Display success message
            $this->messageManager->addSuccessMessage(__('The template has been saved.'));

            // Check if 'Save and Continue'
            if ($this->getRequest()->getParam('back')) {
                $this->_redirect('*/*/edit', ['id' => $templateModel->getId(), '_current' => true]);
                return;
            }

            // Go to grid page
            $this->_redirect('*/*/');
            return;
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                $message = __('the field must be unique');
            } else {
                $message = $e->getMessage();
            }
            $this->messageManager->addErrorMessage($message);
        }

        $this->_getSession()->setFormData($this->dataObjectProcessor->buildOutputDataArray(
            $templateModel,
            '\Konstanchuk\PushNotification\Api\Data\TemplateInterface'
        ));
        $this->_redirect('*/*/edit', ['id' => $templateId]);
    }
}
