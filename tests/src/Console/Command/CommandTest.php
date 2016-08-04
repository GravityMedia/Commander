<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Gravitymedia\CommanderTest\Console\Command;

use GravityMedia\Commander\Config;
use GravityMedia\Commander\Config\Loader;
use GravityMedia\Commander\Console\Command\Command;
use GravityMedia\Commander\Console\Helper\ConfigLoaderHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command test class.
 *
 * @package GravityMedia\CommanderTest\Console\Command
 *
 * @covers  GravityMedia\Commander\Console\Command\Command
 * @uses    GravityMedia\Commander\Console\Helper\ConfigLoaderHelper
 */
class CommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that all options are available.
     */
    public function testAvailableOptions()
    {
        $command = new Command('command');

        $this->assertTrue($command->getDefinition()->hasOption('configuration'));
    }

    /**
     * Test that the configuration is available after initialization.
     */
    public function testConfigurationIsAvailableAfterInitialization()
    {
        $command = new Command('command');

        $input = $this->createMock(InputInterface::class);

        $output = $this->createMock(OutputInterface::class);

        $reflection = new \ReflectionClass(Command::class);

        $method = $reflection->getMethod('Initialize');
        $method->setAccessible(true);
        $method->invoke($command, $input, $output);

        $this->assertInstanceOf(Config::class, $command->getConfiguration());
    }

    /**
     * Test that the configuration is available after initialization with configuration option.
     */
    public function testConfigurationIsAvailableAfterInitializationWithConfigurationOption()
    {
        $loader = $this->createMock(Loader::class);
        $loader
            ->expects($this->once())
            ->method('load')
            ->with(__DIR__ . '/../../../resources/commander.json')
            ->will($this->returnValue(new Config()));

        $helperSet = new HelperSet([
            new ConfigLoaderHelper($loader)
        ]);

        $command = new Command('command');
        $command->setHelperSet($helperSet);

        $input = $this->createMock(InputInterface::class);
        $input
            ->expects($this->once())
            ->method('getOption')
            ->with('configuration')
            ->will($this->returnValue(__DIR__ . '/../../../resources/commander.json'));

        $output = $this->createMock(OutputInterface::class);

        $reflection = new \ReflectionClass(Command::class);

        $method = $reflection->getMethod('Initialize');
        $method->setAccessible(true);
        $method->invoke($command, $input, $output);

        $this->assertInstanceOf(Config::class, $command->getConfiguration());
    }

    /**
     * Test that an exception will be thrown when the command was not initialized before.
     *
     * @expectedException \LogicException
     */
    public function testConfigurationThrowsExceptionBeforeInitialization()
    {
        $command = new Command('command');

        $command->getConfiguration();
    }
}
