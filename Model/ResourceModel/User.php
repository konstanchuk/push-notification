<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;


class User extends AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init(\Konstanchuk\PushNotification\Setup\InstallSchema::USER_TABLE, 'id');
    }
}