<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Gravitymedia\CommanderTest;

use GravityMedia\Commander\Config;

/**
 * Config test class.
 *
 * @package GravityMedia\CommanderTest
 *
 * @covers  GravityMedia\Commander\Config
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the default config returns the expected standard values.
     */
    public function testDefaultConfigValues()
    {
        $config = new Config();

        $this->assertSame(Config::DEFAULT_DATABASE_FILE_PATH, $config->getDatabaseFilePath());
        $this->assertNull($config->getCacheDirectory());
        $this->assertNull($config->getLogFilePath());
        $this->assertSame(Config::DEFAULT_PROCESS_TIMEOUT, $config->getProcessTimeout());
    }

    /**
     * Test that the expected custom values are returned.
     */
    public function testCustomConfigValues()
    {
        $config = new Config();
        $config->setDatabaseFilePath('/path/to/database.file');
        $config->setCacheDirectory('/cache/directory');
        $config->setLogFilePath('/path/to/log.file');
        $config->setProcessTimeout(Config::DEFAULT_PROCESS_TIMEOUT + 10);

        $this->assertSame('/path/to/database.file', $config->getDatabaseFilePath());
        $this->assertSame('/cache/directory', $config->getCacheDirectory());
        $this->assertSame('/path/to/log.file', $config->getLogFilePath());
        $this->assertSame(Config::DEFAULT_PROCESS_TIMEOUT + 10, $config->getProcessTimeout());
    }
}
