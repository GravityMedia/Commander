<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Config;

/**
 * Commander config class
 *
 * @package GravityMedia\Commander\Config
 */
class CommanderConfig
{
    /**
     * The memory database path
     */
    const DEFAULT_DATABASE_PATH = 'commander.sqlite';

    /**
     * The default timeout for command execution
     */
    const DEFAULT_COMMAND_TIMEOUT = 60;

    /**
     * @var string
     */
    private $databasePath;

    /**
     * @var string
     */
    private $databaseCacheDirectory;

    /**
     * @var int
     */
    private $commandTimeout;

    /**
     * Get database path
     *
     * @return string
     */
    public function getDatabasePath()
    {
        if (null === $this->databasePath) {
            return static::DEFAULT_DATABASE_PATH;
        }

        return $this->databasePath;
    }

    /**
     * Set database path
     *
     * @param string $databasePath
     *
     * @return $this
     */
    public function setDatabasePath($databasePath)
    {
        $this->databasePath = $databasePath;
        return $this;
    }

    /**
     * Get database cache directory
     *
     * @return null|string
     */
    public function getDatabaseCacheDirectory()
    {
        return $this->databaseCacheDirectory;
    }

    /**
     * Set database cache directory
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
     * Get command timeout
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
     * Set command timeout
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
