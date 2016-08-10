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
use GravityMedia\Commander\Console\Command\ShowCommand;
use GravityMedia\Commander\ORM\TaskEntity;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Show command test class.
 *
 * @package GravityMedia\CommanderTest\Console\Command
 *
 * @covers  GravityMedia\Commander\Console\Command\ShowCommand
 */
class ShowCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that an empty task list can be shown.
     */
    public function testShowingEmptyTaskList()
    {
        $taskManager = $this->createMock(TaskManager::class);
        $taskManager
            ->expects($this->once())
            ->method('findAllTasks')
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
            ->getMockBuilder(ShowCommand::class)
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
     * Test that a task list can be shown.
     */
    public function testShowingTaskList()
    {
        $entity = $this->createMock(TaskEntity::class);
        $entity
            ->expects($this->any())
            ->method('getCreatedAt')
            ->will($this->returnValue(new \DateTime()));
        $entity
            ->expects($this->any())
            ->method('getUpdatedAt')
            ->will($this->returnValue(new \DateTime()));

        $task = $this->createMock(Task::class);
        $task
            ->expects($this->any())
            ->method('getEntity')
            ->will($this->returnValue($entity));

        $taskManager = $this->createMock(TaskManager::class);
        $taskManager
            ->expects($this->once())
            ->method('findAllTasks')
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
            ->getMockBuilder(ShowCommand::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCommander'])
            ->getMock();
        $command
            ->expects($this->once())
            ->method('getCommander')
            ->will($this->returnValue($commander));

        $input = $this->createMock(InputInterface::class);

        $formatter = $this->createMock(OutputFormatterInterface::class);

        $output = $this->createMock(OutputInterface::class);
        $output
            ->expects($this->any())
            ->method('getFormatter')
            ->will($this->returnValue($formatter));

        $method = new \ReflectionMethod($command, 'execute');
        $method->setAccessible(true);
        $method->invoke($command, $input, $output);
    }
}
