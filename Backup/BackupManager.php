<?php

namespace Asapo\Bundle\BackupBundle\Backup;

class BackupManager
{
    /**
     * @var BackupInterface[]
     */
    private $pool;

    /**
     * @var BackupStorageInterface
     */
    private $backupStorage;

    public function add(BackupInterface $backup)
    {
        $this->pool[$backup->getName()] = $backup;
    }

    public function backup($targets)
    {
        $this->backupStorage->init();
        $configHandler = $this->backupStorage->openConfigFile();

        foreach ($targets as $target) {
            fwrite($configHandler, $target . ',');

            $fileHandler = $this->backupStorage->openFile($this->pool[$target]);
            $this->pool[$target]->execute($fileHandler);
            fclose($fileHandler);
        }

        fclose($configHandler);
    }

    public function restore($backupName)
    {
        $this->backupStorage->open($backupName);
        $configHandler = $this->backupStorage->openConfigFile();
        $content = stream_get_contents($configHandler);
        fclose($configHandler);
        $targets = explode(',', $content);

        foreach ($targets as $target) {
            $fileHandler = $this->backupStorage->openFile($this->pool[$target]);
            $this->pool[$target]->restore($fileHandler);
            fclose($fileHandler);
        }
    }

    public function getBackups()
    {
        return $this->backupStorage->getBackups();
    }
}
