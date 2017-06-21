<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;


class InstallSchema implements InstallSchemaInterface
{
    const USER_TABLE = 'konstanchuk_pn_user';
    const TEMPLATE_TABLE = 'konstanchuk_pn_template';
    const PROCESS_TABLE = 'konstanchuk_pn_process';

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $this->createUserTable($installer);
        $this->createTemplateTable($installer);
        $this->createProcessTable($installer);
        $installer->endSetup();
    }

    protected function createUserTable(SchemaSetupInterface $installer)
    {
        $tableName = $installer->getTable(static::USER_TABLE);
        if ($installer->getConnection()->isTableExists($tableName) == true) {
            return false;
        }
        $table = $installer->getConnection()
            ->newTable($tableName)
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'ID'
            )
            ->addColumn(
                'endpoint',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Browser endpoint'
            )
            ->addColumn(
                'p256dh',
                Table::TYPE_TEXT,
                100,
                ['nullable' => true, 'default' => null],
                'Browser p256dh'
            )
            ->addColumn(
                'auth',
                Table::TYPE_TEXT,
                100,
                ['nullable' => true, 'default' => null],
                'Browser auth'
            )
            ->addColumn(
                'browser',
                Table::TYPE_TEXT,
                50,
                ['nullable' => false, 'default' => ''],
                'Browser'
            )
            ->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                11,
                ['nullable' => true, 'default' => null],
                'Customer_id'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'Created At'
            )
            ->addIndex(
                $installer->getIdxName(
                    $tableName,
                    ['endpoint',],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['endpoint',],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE,]
            )
            ->setComment('Konstanchuk Push Notification User Table')
            ->setOption('type', 'InnoDB')
            ->setOption('charset', 'utf8');
        $installer->getConnection()->createTable($table);
    }

    protected function createTemplateTable(SchemaSetupInterface $installer)
    {
        $tableName = $installer->getTable(static::TEMPLATE_TABLE);
        if ($installer->getConnection()->isTableExists($tableName) == true) {
            return false;
        }
        $table = $installer->getConnection()
            ->newTable($tableName)
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'ID'
            )
            ->addColumn(
                'title',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Title'
            )
            ->addColumn(
                'text',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => ''],
                'Text'
            )
            ->addColumn(
                'link',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => ''],
                'Text'
            )
            ->addColumn(
                'image',
                Table::TYPE_TEXT,
                100,
                ['nullable' => true, 'default' => null],
                'Image'
            )
            ->addColumn(
                'status',
                Table::TYPE_SMALLINT,
                1,
                ['nullable' => true, 'default' => null],
                'Status'
            )
            ->addColumn(
                'activate_transitions',
                Table::TYPE_SMALLINT,
                1,
                ['nullable' => false, 'default' => 0],
                'Active Transitions'
            )
            ->addColumn(
                'count_transitions',
                Table::TYPE_INTEGER,
                11,
                ['nullable' => false, 'default' => 0],
                'Count Transitions'
            )
            ->addColumn(
                'ttl',
                Table::TYPE_INTEGER,
                11,
                ['nullable' => true, 'default' => null, 'unsigned' => true],
                'Notification TTL'
            )
            ->addColumn(
                'key',
                Table::TYPE_TEXT,
                30,
                ['nullable' => false],
                'Unique Identifier'
            )
            ->addColumn(
                'updated_at',
                Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'Updated At'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'Created At'
            )
            ->addIndex(
                $installer->getIdxName(
                    $tableName,
                    ['key',],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['key',],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE,]
            )
            ->setComment('Konstanchuk Push Notification Template Table')
            ->setOption('type', 'InnoDB')
            ->setOption('charset', 'utf8');
        $installer->getConnection()->createTable($table);
    }

    protected function createProcessTable(SchemaSetupInterface $installer)
    {
        $tableName = $installer->getTable(static::PROCESS_TABLE);
        if ($installer->getConnection()->isTableExists($tableName) == true) {
            return false;
        }
        $table = $installer->getConnection()
            ->newTable($tableName)
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'ID'
            )
            ->addColumn(
                'template_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => true,
                ],
                'Template ID'
            )
            ->addColumn(
                'title',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Title'
            )
            ->addColumn(
                'params',
                Table::TYPE_TEXT,
                '64k',
                ['nullable' => false],
                'Params'
            )
            ->addColumn(
                'status',
                Table::TYPE_SMALLINT,
                1,
                ['nullable' => false],
                'Status'
            )
            ->addColumn(
                'updated_at',
                Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'Updated At'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'Created At'
            )
            ->addForeignKey(
                $installer->getFkName(
                    static::PROCESS_TABLE,
                    'template_id',
                    static::TEMPLATE_TABLE,
                    'id'
                ),
                'template_id',
                $installer->getTable(static::TEMPLATE_TABLE),
                'id',
                Table::ACTION_SET_NULL
            )
            ->setComment('Konstanchuk Push Notification Process Table')
            ->setOption('type', 'InnoDB')
            ->setOption('charset', 'utf8');
        $installer->getConnection()->createTable($table);
    }
}