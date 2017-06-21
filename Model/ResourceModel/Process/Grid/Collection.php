<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Model\ResourceModel\Process\Grid;

use Magento\Customer\Ui\Component\DataProvider\Document;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Psr\Log\LoggerInterface as Logger;


class Collection extends \Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult
{
    /**
     * @inheritdoc
     */
    protected $document = Document::class;

    /**
     * Initialize dependencies.
     *
     * @param EntityFactory $entityFactory
     * @param Logger $logger
     * @param FetchStrategy $fetchStrategy
     * @param EventManager $eventManager
     * @param string $mainTable
     * @param string $resourceModel
     */
    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        $mainTable = 'konstanchuk_pn_process',
        $resourceModel = '\Konstanchuk\PushNotification\Model\ResourceModel\Process'
    )
    {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->addFilterToMap('status', 'main_table.status');
        $this->addFilterToMap('title', 'main_table.title');
        $this->addFilterToMap('template_title', 'pn_template.title');
    }

    protected function _renderFiltersBefore()
    {
        $this->getSelect()->joinleft(
            ['pn_template' => $this->getTable('konstanchuk_pn_template')],
            'main_table.template_id = pn_template.id',
            ['template_title' => 'title']
        );
        parent::_renderFiltersBefore();
    }
}
