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
     * Test that a logger can be created.
     *
     * @dataProvider provideLogFilePaths()
     *
     * @param string $path
     * @param int    $count
     */
    public function testLoggerCreation($path, $count)
    {
        $config = $this->createMock(Config::class);
        $config
            ->expects($this->once())
            ->method('getLogFilePath')
            ->will($this->returnValue($path));

        $provider = new LoggerProvider($config);

        $this->assertCount($count, $provider->getHandlers());
        $this->assertInstanceOf(LoggerInterface::class, $provider->getLogger());
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
}
