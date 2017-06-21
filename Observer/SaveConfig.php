<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Observer;

use Magento\Framework\Event\ObserverInterface;
use Konstanchuk\PushNotification\Helper\Data as Helper;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;


class SaveConfig implements ObserverInterface
{
    /** @var Helper */
    protected $_helper;

    /** @var DirectoryList */
    protected $_directoryList;

    /** @var Filesystem */
    protected $_filesystem;

    public function __construct(
        Helper $helper,
        DirectoryList $directoryList,
        Filesystem $filesystem
    )
    {
        $this->_helper = $helper;
        $this->_directoryList = $directoryList;
        $this->_filesystem = $filesystem;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $filename = $this->_helper->getManifestFile();
            $dir = $this->_filesystem->getDirectoryWrite(DirectoryList::ROOT);
            if ($this->_helper->isEnabled()) {
                $data = [
                    'name' => $this->_helper->getSiteName(),
                    'display' => 'standalone',
                    'gcm_sender_id' => $this->_helper->getGCMSenderId(),
                ];
                $dir->writeFile($filename, json_encode($data));
            } else {
                if ($dir->isExist($filename)) {
                    $dir->delete($filename);
                }
            }
        } catch (\Exception $e) {
            $this->_helper->getLogger()->critical(__('PUSH NOTIFICATION ERROR: %1', $e->getMessage()));
        }
    }
}
