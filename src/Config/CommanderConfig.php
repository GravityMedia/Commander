<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Config;

/**
 * Commander config class.
 *
 * @package GravityMedia\Commander\Config
 */
class CommanderConfig
{
    /**
     * The default database file path.
     *
     * @const string
     */
    const DEFAULT_DATABASE_FILE_PATH = 'commander.sqlite';

    /**
     * The default timeout for command execution.
     *
     * @const string
     */
    const DEFAULT_COMMAND_TIMEOUT = 60;

    /**
     * The database file path.
     *
     * @var null|string
     */
    private $databaseFilePath;

    /**
     * The database cache directory.
     *
     * @var null|string
     */
    private $databaseCacheDirectory;

    /**
     * The log file path.
     *
     * @var null|string
     */
    private $logFilePath;

    /**
     * The command timeout.
     *
     * @var null|int
     */
    private $commandTimeout;

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
     * @return null|string
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
     * @return null|string
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
     * Get command timeout.
     *
     * @return int
     */
    public function getCommandTimeout()
    {
        if (null === $this->commandTimeout) {
            return static::DEFAULT_COMMAND_TIMEOUT;
        }

        return $this->commandTimeout;
    }

    /**
     * Set command timeout.
     *
     * @param int $commandTimeout
     *
     * @return $this
     */
    public function setCommandTimeout($commandTimeout)
    {
        $this->commandTimeout = $commandTimeout;
        return $this;
    }
}
