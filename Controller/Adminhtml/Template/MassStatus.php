<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Controller\Adminhtml\Template;

use Magento\Ui\Component\MassAction\Filter;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Konstanchuk\PushNotification\Controller\Adminhtml\Template;
use Konstanchuk\PushNotification\Api\TemplateRepositoryInterface;
use Konstanchuk\PushNotification\Model\TemplateFactory;
use Konstanchuk\PushNotification\Model\ResourceModel\Template\CollectionFactory;
use Konstanchuk\PushNotification\Model\Template\Status as TemplateStatus;


class MassStatus extends Template
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
        TemplateFactory $templateFactory,
        TemplateRepositoryInterface $templateRepository,
        Filter $filter,
        CollectionFactory $collectionFactory
    )
    {
        parent::__construct($context, $coreRegistry, $resultPageFactory, $templateFactory, $templateRepository);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $templateChanged = 0;
        $newStatus = $this->getRequest()->getParam('status') == 1 ? TemplateStatus::ENABLED : TemplateStatus::DISABLED;
        try {
            /** @var \Konstanchuk\PushNotification\Model\Template $template */
            foreach ($collection->getItems() as $template) {
                if ($template->getStatus() != $newStatus) {
                    $template->setStatus($newStatus);
                    $this->templateRepository->save($template);
                }
                ++$templateChanged;
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        if ($templateChanged) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) were changed.', $templateChanged)
            );
        }

        $this->_redirect('*/*/index');
    }
}
