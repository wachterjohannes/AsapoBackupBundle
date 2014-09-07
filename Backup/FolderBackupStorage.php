<?php

namespace Asapo\Bundle\BackupBundle\Backup;

class FolderBackupStorage implements BackupStorageInterface
{
    /**
     * @var string
     */
    private $baseFolder;

    /**
     * @var string
     */
    private $folder;

    public function init()
    {
        $this->folder = $this->baseFolder . '/' . date('Y-m-d_H-i-s') . '_backup';

        mkdir($this->folder);
    }

    public function openFile(BackupInterface $target)
    {
        return fopen($this->folder . '/' . $target->getName() . '.' . $target->getExtension(), 'w+');
    }

    /**
     * @return resource
     */
    public function openConfigFile()
    {
        return fopen($this->folder . '/backup_config.txt', 'w+');
    }

    /**
     * @return string[]
     */
    public function getBackups()
    {
        return scandir($this->baseFolder);
    }

    /**
     * @param string $backupName
     */
    public function open($backupName)
    {
        $this->folder = $this->baseFolder . '/' . $backupName;

        // TODO exception for non existing folder
    }
}
