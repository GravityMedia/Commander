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
     * @param string $class
     */
    public function testLoggerCreation($path, $count, $class)
    {
        $config = $this->createMock(Config::class);
        $config
            ->expects($this->once())
            ->method('getLogFilePath')
            ->will($this->returnValue($path));

        $provider = new LoggerProvider($config);

        $this->assertCount($count, $provider->getHandlers());
        if (null === $class) {
            $this->assertNull($provider->getLogger());
        } else {
            $this->assertInstanceOf($class, $provider->getLogger());
        }
    }

    /**
     * Provide log file paths.
     *
     * @return array
     */
    public function provideLogFilePaths()
    {
        return [
            [null, 0, null],
            [sys_get_temp_dir(), 1, LoggerInterface::class]
        ];
    }
}
