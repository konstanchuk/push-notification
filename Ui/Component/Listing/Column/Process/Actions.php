<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Ui\Component\Listing\Column\Process;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Konstanchuk\PushNotification\Model\Process\Status as ProcessStatus;


class Actions extends Column
{
    /** Url path */
    const URL_PATH_STATUS_EDIT = 'push_notification/process/editStatus';
    const URL_PATH_DELETE = 'push_notification/process/delete';

    /** @var UrlInterface */
    protected $urlBuilder;

    /** @var  ProcessStatus */
    protected $processStatus;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param ProcessStatus $processStatus
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        ProcessStatus $processStatus,
        array $components = [],
        array $data = []
    )
    {
        $this->urlBuilder = $urlBuilder;
        $this->processStatus = $processStatus;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                $idFieldName = $item['id_field_name'] ?? 'id';
                if (!isset($item[$idFieldName])) {
                    continue;
                }

                $status = $item['__original_status'] ?? $item['status'];

                if (isset($item['template_id'])) {
                    switch ($status) {
                        case ProcessStatus::IN_PROCESS:
                            break;
                        case ProcessStatus::CANCELED:
                            $item[$name]['status_' . ProcessStatus::AWAITED] = $this->getStatusItemParams(ProcessStatus::AWAITED, $item, $idFieldName);
                            break;
                        case ProcessStatus::FINISHED:
                            $params = $this->getStatusItemParams(ProcessStatus::AWAITED, $item, $idFieldName);
                            $params['label'] = __('repeat send notifications');
                            $item[$name]['status_' . ProcessStatus::AWAITED] = $params;
                            break;
                        case ProcessStatus::AWAITED:
                            $item[$name]['status_' . ProcessStatus::CANCELED] = $this->getStatusItemParams(ProcessStatus::CANCELED, $item, $idFieldName);
                            break;
                        default:
                            break;
                    }
                } else if ($status != ProcessStatus::CANCELED && $status != ProcessStatus::IN_PROCESS) {
                    $item[$name]['status_' . ProcessStatus::CANCELED] = $this->getStatusItemParams(ProcessStatus::CANCELED, $item, $idFieldName);
                }

                if ($status != ProcessStatus::IN_PROCESS) {
                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(self::URL_PATH_DELETE, ['id' => $item[$idFieldName]]),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete "${ $.$data.title }"'),
                            'message' => __('Are you sure you wan\'t to delete a "${ $.$data.title }" record?')
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }

    protected function getStatusItemParams($status, $item, $idFieldName)
    {
        $options = $this->processStatus->toArray();
        if (!isset($options[$status])) {
            return null;
        }
        $newStatus = $options[$status];
        return [
            'href' => $this->urlBuilder->getUrl(static::URL_PATH_STATUS_EDIT, [
                'process_id' => $item[$idFieldName],
                'new_status' => $status,
            ]),
            'label' => __('change status on "%1"', $newStatus),
            'confirm' => [
                'title' => __('Change status "${ $.$data.title }"'),
                'message' => __('Are you sure you wan\'t to change status on "<b>%1</b>" a "<b>${ $.$data.title }</b>" record?', $newStatus),
            ]
        ];
    }

}