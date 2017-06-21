<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Model\ResourceModel\User\Grid;
use Magento\Customer\Ui\Component\DataProvider\Document;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Psr\Log\LoggerInterface as Logger;
use Magento\Eav\Model\ResourceModel\Entity\Attribute as EavResourceModel;
use Magento\Eav\Model\Entity\Attribute as EavAttribute;


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
        $mainTable = 'konstanchuk_pn_user',
        $resourceModel = '\Konstanchuk\PushNotification\Model\ResourceModel\User'
    )
    {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->addFilterToMap('customer_firstname', 'customer_entity.firstname');
        $this->addFilterToMap('customer_lastname', 'customer_entity.lastname');
    }

    protected function _renderFiltersBefore()
    {
        $this->getSelect()
            ->joinLeft(
                ['customer_entity' => $this->getTable('customer_entity')],
                'customer_entity.entity_id=main_table.customer_id',
                [
                    'customer_firstname' => 'firstname',
                    'customer_lastname' => 'lastname'
                ]
            );
        parent::_renderFiltersBefore();
    }
}
