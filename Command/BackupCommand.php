<?php

namespace Asapo\Bundle\BackupBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Backup command
 */
class BackupCommand extends ContainerAwareCommand implements ContainerAwareInterface
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {

    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
