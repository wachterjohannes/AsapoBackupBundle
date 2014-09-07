<?php

namespace Asapo\Bundle\BackupBundle\Database;

use Asapo\Bundle\BackupBundle\Backup\BackupInterface;
use Symfony\Component\Process\Process;

class MysqlBackup implements BackupInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $database;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $port;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @param resource $fileHandler
     */
    public function execute($fileHandler)
    {
        $this->executeCommand($this->getDumpCommand());

        $content = file_get_contents($this->filename);
        fwrite($fileHandler, $content);

        unlink($this->filename);
    }

    public function restore($fileHandler)
    {
        $tempHandler = fopen($this->getFilename(), 'w+');
        while (!feof($fileHandler)) {
            fwrite($tempHandler, fread($fileHandler, 1));
        }
        fclose($tempHandler);

        $this->executeCommand($this->getRestoreCommand());

        unlink($this->filename);
    }

    protected function executeCommand($command)
    {
        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }
    }

    protected function getDumpCommand()
    {
        return sprintf(
            'mysqldump %s %s > "%s"',
            $this->getAuth(),
            $this->getDatabase(),
            $this->getFilename()
        );
    }

    protected function getRestoreCommand()
    {
        return sprintf(
            'mysql %s %s < "%s"',
            $this->getAuth(),
            $this->getDatabase(),
            $this->getFilename()
        );
    }

    protected function getFilename()
    {
        if ($this->filename === null) {
            $this->filename = tempnam($this->cacheDir, $this->prefix);
        }

        return $this->filename;
    }

    protected function getAuth()
    {
        if ($this->user !== null && $this->password !== null) {
            return sprintf(
                "--host='%s' --port='%d' --user='%s' --password='%s'",
                $this->host,
                $this->port,
                $this->user,
                $this->password
            );
        } elseif ($this->user !== null) {
            return sprintf('-u%s', $this->user);
        }

        return '';
    }

    protected function getDatabase()
    {
        return $this->database;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getExtension()
    {
        return 'sql';
    }
}
