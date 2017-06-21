<?php

/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\PushNotification\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Konstanchuk\PushNotification\Model\Notification\Notification;


class SendNotification extends Command
{
    const TESTING_FLAG = 'testing';

    /** @var Notification  */
    protected $_notification;

    public function __construct(Notification $notification, $name = null)
    {
        parent::__construct($name);
        $this->_notification = $notification;
    }

    protected function configure()
    {
        $this->setName('push-notification:run')
            ->setDescription('push notification (only for testing)')
            ->setDefinition([
                new InputOption(
                    self::TESTING_FLAG,
                    '-t',
                    InputOption::VALUE_NONE,
                    'testing flag'
                ),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $testing = $input->getOption(self::TESTING_FLAG);
        if (is_null($testing)) {
            throw new \InvalidArgumentException(sprintf('Option \'%s\' is missing.', self::TESTING_FLAG));
        }
        $this->_notification->sendNext();
        $output->writeln('process finished');
    }
}