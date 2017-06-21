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


class MassDelete extends Template
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
        $templateDeleted = 0;
        try {
            /** @var \Konstanchuk\PushNotification\Model\Template $template */
            foreach ($collection->getItems() as $template) {
                $this->templateRepository->delete($template);
                ++$templateDeleted;
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        if ($templateDeleted) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) were deleted.', $templateDeleted)
            );
        }

        $this->_redirect('*/*/index');
    }
}
