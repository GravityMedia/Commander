<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Gravitymedia\CommanderTest\Console\Command;

use GravityMedia\Commander\Commander;
use GravityMedia\Commander\Commander\TaskManager;
use GravityMedia\Commander\Config;
use GravityMedia\Commander\Console\Command\RunCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Run command test class.
 *
 * @package GravityMedia\CommanderTest\Console\Command
 *
 * @covers  GravityMedia\Commander\Console\Command\RunCommand
 * @uses    GravityMedia\Commander\Commander\TaskRunner
 */
class RunCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that tasks can be run.
     */
    public function testRunningTasks()
    {
        $taskManager = $this->createMock(TaskManager::class);
        $taskManager
            ->expects($this->once())
            ->method('findNextTask')
            ->will($this->returnValue(null));

        $logger = $this->createMock(LoggerInterface::class);

        $commander = $this->createMock(Commander::class);
        $commander
            ->expects($this->once())
            ->method('initialize')
            ->willReturnSelf();
        $commander
            ->expects($this->once())
            ->method('getTaskManager')
            ->will($this->returnValue($taskManager));
        $commander
            ->expects($this->once())
            ->method('getLogger')
            ->will($this->returnValue($logger));

        $config = $this->createMock(Config::class);

        $command = $this
            ->getMockBuilder(RunCommand::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCommander', 'getConfiguration'])
            ->getMock();
        $command
            ->expects($this->once())
            ->method('getCommander')
            ->will($this->returnValue($commander));
        $command
            ->expects($this->once())
            ->method('getConfiguration')
            ->will($this->returnValue($config));

        $input = $this->createMock(InputInterface::class);

        $output = $this->createMock(OutputInterface::class);

        $method = new \ReflectionMethod($command, 'execute');
        $method->setAccessible(true);
        $method->invoke($command, $input, $output);
    }
}
