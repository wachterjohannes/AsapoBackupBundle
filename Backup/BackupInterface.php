<?php

namespace Asapo\Bundle\BackupBundle\Backup;

interface BackupInterface
{
    public function getName();

    public function execute($fileHandler);

    public function restore($fileHandler);

    public function getExtension();
}
