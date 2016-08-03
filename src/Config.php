<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander;

/**
 * Commander config class.
 *
 * @package GravityMedia\Commander
 */
class Config
{
    /**
     * The default database file path.
     *
     * @const string
     */
    const DEFAULT_DATABASE_FILE_PATH = 'commander.sqlite';

    /**
     * The default timeout for process execution.
     *
     * @const string
     */
    const DEFAULT_PROCESS_TIMEOUT = 60;

    /**
     * The database file path.
     *
     * @var string|null
     */
    private $databaseFilePath;

    /**
     * The database cache directory.
     *
     * @var string|null
     */
    private $databaseCacheDirectory;

    /**
     * The log file path.
     *
     * @var string|null
     */
    private $logFilePath;

    /**
     * The process timeout.
     *
     * @var int|null
     */
    private $processTimeout;

    /**
     * Get database file path.
     *
     * @return string
     */
    public function getDatabaseFilePath()
    {
        if (null === $this->databaseFilePath) {
            return static::DEFAULT_DATABASE_FILE_PATH;
        }

        return $this->databaseFilePath;
    }

    /**
     * Set database file path.
     *
     * @param string $databaseFilePath
     *
     * @return $this
     */
    public function setDatabaseFilePath($databaseFilePath)
    {
        $this->databaseFilePath = $databaseFilePath;
        return $this;
    }

    /**
     * Get database cache directory.
     *
     * @return string|null
     */
    public function getDatabaseCacheDirectory()
    {
        return $this->databaseCacheDirectory;
    }

    /**
     * Set database cache directory.
     *
     * @param string $databaseCacheDirectory
     *
     * @return $this
     */
    public function setDatabaseCacheDirectory($databaseCacheDirectory)
    {
        $this->databaseCacheDirectory = $databaseCacheDirectory;
        return $this;
    }

    /**
     * Get log file path.
     *
     * @return string|null
     */
    public function getLogFilePath()
    {
        return $this->logFilePath;
    }

    /**
     * Set log file path.
     *
     * @param string $logFilePath
     *
     * @return $this
     */
    public function setLogFilePath($logFilePath)
    {
        $this->logFilePath = $logFilePath;
        return $this;
    }

    /**
     * Get process timeout.
     *
     * @return int
     */
    public function getProcessTimeout()
    {
        if (null === $this->processTimeout) {
            return static::DEFAULT_PROCESS_TIMEOUT;
        }

        return $this->processTimeout;
    }

    /**
     * Set process timeout.
     *
     * @param int $processTimeout
     *
     * @return $this
     */
    public function setProcessTimeout($processTimeout)
    {
        $this->processTimeout = $processTimeout;
        return $this;
    }
}
