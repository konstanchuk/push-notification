<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Controller\Adminhtml\Process;

use Magento\Ui\Component\MassAction\Filter;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Konstanchuk\PushNotification\Controller\Adminhtml\Process;
use Konstanchuk\PushNotification\Api\ProcessRepositoryInterface;
use Konstanchuk\PushNotification\Model\ProcessFactory;
use Konstanchuk\PushNotification\Model\ResourceModel\Process\CollectionFactory;
use Konstanchuk\PushNotification\Model\Process\Status as ProcessStatus;


class MassDelete extends Process
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
        ProcessFactory $processFactory,
        ProcessRepositoryInterface $processRepository,
        Filter $filter,
        CollectionFactory $collectionFactory
    )
    {
        parent::__construct($context, $coreRegistry, $resultPageFactory, $processFactory, $processRepository);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $processDeleted = 0;
        $notDeleted = false;
        try {
            /** @var \Konstanchuk\PushNotification\Api\Data\ProcessInterface $process */
            foreach ($collection->getItems() as $process) {
                if ($process->getStatus() != ProcessStatus::IN_PROCESS) {
                    $this->processRepository->delete($process);
                    ++$processDeleted;
                } else {
                    $notDeleted = true;
                }
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        if ($processDeleted) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) were deleted.', $processDeleted)
            );
        }

        if ($notDeleted) {
            $this->messageManager->addNoticeMessage(__('Only processes that do not have the status "in process" can be deleted.'));
        }

        $this->_redirect('*/*/index');
    }
}
