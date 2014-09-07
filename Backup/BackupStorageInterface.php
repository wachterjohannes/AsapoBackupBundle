<?php

namespace Asapo\Bundle\BackupBundle\Backup;

interface BackupStorageInterface
{
    public function init();

    /**
     * @param BackupInterface $target
     * @return resource
     */
    public function openFile(BackupInterface $target);

    /**
     * @return resource
     */
    public function openConfigFile();

    /**
     * @return string[]
     */
    public function getBackups();

    /**
     * @param string $backupName
     */
    public function open($backupName);
}
