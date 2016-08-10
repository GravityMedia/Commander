<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Gravitymedia\CommanderTest\Console\Command;

use GravityMedia\Commander\Commander;
use GravityMedia\Commander\Commander\Task;
use GravityMedia\Commander\Commander\TaskManager;
use GravityMedia\Commander\Console\Command\PurgeCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Purge command test class.
 *
 * @package GravityMedia\CommanderTest\Console\Command
 *
 * @covers  GravityMedia\Commander\Console\Command\PurgeCommand
 */
class PurgeCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that purging does not fail when there are no terminated tasks available.
     */
    public function testPurgingNoTerminatedTasks()
    {
        $taskManager = $this->createMock(TaskManager::class);
        $taskManager
            ->expects($this->once())
            ->method('findAllTerminatedTasks')
            ->will($this->returnValue([]));

        $commander = $this->createMock(Commander::class);
        $commander
            ->expects($this->once())
            ->method('initialize')
            ->willReturnSelf();
        $commander
            ->expects($this->once())
            ->method('getTaskManager')
            ->will($this->returnValue($taskManager));

        $command = $this
            ->getMockBuilder(PurgeCommand::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCommander'])
            ->getMock();
        $command
            ->expects($this->once())
            ->method('getCommander')
            ->will($this->returnValue($commander));

        $input = $this->createMock(InputInterface::class);

        $output = $this->createMock(OutputInterface::class);
        $output
            ->expects($this->once())
            ->method('writeln')
            ->with('No tasks found');

        $method = new \ReflectionMethod($command, 'execute');
        $method->setAccessible(true);
        $method->invoke($command, $input, $output);
    }

    /**
     * Test that terminated tasks can be purged.
     */
    public function testPurgingTasks()
    {
        $task = $this->createMock(Task::class);
        $task
            ->expects($this->once())
            ->method('remove');

        $taskManager = $this->createMock(TaskManager::class);
        $taskManager
            ->expects($this->once())
            ->method('findAllTerminatedTasks')
            ->will($this->returnValue([$task]));

        $commander = $this->createMock(Commander::class);
        $commander
            ->expects($this->once())
            ->method('initialize')
            ->willReturnSelf();
        $commander
            ->expects($this->once())
            ->method('getTaskManager')
            ->will($this->returnValue($taskManager));

        $command = $this
            ->getMockBuilder(PurgeCommand::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCommander'])
            ->getMock();
        $command
            ->expects($this->once())
            ->method('getCommander')
            ->will($this->returnValue($commander));

        $input = $this->createMock(InputInterface::class);

        $output = $this->createMock(OutputInterface::class);
        $output
            ->expects($this->once())
            ->method('writeln')
            ->with('Removed 1 terminated task(s)');

        $method = new \ReflectionMethod($command, 'execute');
        $method->setAccessible(true);
        $method->invoke($command, $input, $output);
    }
}
