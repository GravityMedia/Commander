<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Gravitymedia\CommanderTest\Provider;

use GravityMedia\Commander\Config;
use GravityMedia\Commander\Provider\LoggerProvider;
use Psr\Log\LoggerInterface;

/**
 * Logger provider test class.
 *
 * @package GravityMedia\CommanderTest\Provider
 *
 * @covers  GravityMedia\Commander\Provider\LoggerProvider
 */
class LoggerProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the number of handlers provided.
     *
     * @dataProvider provideLogFilePaths()
     *
     * @param string $path
     * @param int    $count
     */
    public function testHandlersProvided($path, $count)
    {
        $config = $this->createMock(Config::class);
        $config
            ->expects($this->once())
            ->method('getLogFilePath')
            ->will($this->returnValue($path));

        $provider = new LoggerProvider($config);

        $this->assertCount($count, $provider->getHandlers());
    }

    /**
     * Provide log file paths.
     *
     * @return array
     */
    public function provideLogFilePaths()
    {
        return [
            [null, 0],
            [sys_get_temp_dir(), 1]
        ];
    }

    /**
     * Test that a logger can be created.
     */
    public function testLoggerCreation()
    {
        $config = $this->createMock(Config::class);
        $config
            ->expects($this->once())
            ->method('getLogFilePath')
            ->will($this->returnValue(null));

        $provider = new LoggerProvider($config);

        $this->assertInstanceOf(LoggerInterface::class, $provider->getLogger());
    }
}
