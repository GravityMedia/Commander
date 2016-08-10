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
use GravityMedia\Commander\Console\Command\JoinCommand;
use GravityMedia\Commander\ORM\TaskEntity;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Join command test class.
 *
 * @package GravityMedia\CommanderTest\Console\Command
 *
 * @covers  GravityMedia\Commander\Console\Command\JoinCommand
 */
class JoinCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that a new task can be created.
     */
    public function testCreatingNewTask()
    {
        $entity = $this->createMock(TaskEntity::class);
        $entity
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('441798A8-5F37-4147-B166-5A63C095B2DA'));

        $task = $this->createMock(Commander\Task::class);
        $task
            ->expects($this->any())
            ->method('getEntity')
            ->will($this->returnValue($entity));

        $taskManager = $this->createMock(TaskManager::class);
        $taskManager
            ->expects($this->once())
            ->method('findNextTask')
            ->will($this->returnValue(null));
        $taskManager
            ->expects($this->once())
            ->method('newTask')
            ->will($this->returnValue($task));

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
            ->getMockBuilder(JoinCommand::class)
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
            ->with('Created new task 441798A8-5F37-4147-B166-5A63C095B2DA');

        $method = new \ReflectionMethod($command, 'execute');
        $method->setAccessible(true);
        $method->invoke($command, $input, $output);
    }

    /**
     * Test that a task with a different priority will be updated.
     */
    public function testUpdatingExistingTask()
    {
        $entity = $this->createMock(TaskEntity::class);
        $entity
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('441798A8-5F37-4147-B166-5A63C095B2DA'));

        $task = $this->createMock(Task::class);
        $task
            ->expects($this->any())
            ->method('getEntity')
            ->will($this->returnValue($entity));

        $taskManager = $this->createMock(TaskManager::class);
        $taskManager
            ->expects($this->once())
            ->method('findNextTask')
            ->will($this->returnValue($task));

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
            ->getMockBuilder(JoinCommand::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCommander'])
            ->getMock();
        $command
            ->expects($this->once())
            ->method('getCommander')
            ->will($this->returnValue($commander));

        $input = $this->createMock(InputInterface::class);
        $input
            ->expects($this->once())
            ->method('getOption')
            ->with('priority')
            ->will($this->returnValue(1000));

        $output = $this->createMock(OutputInterface::class);
        $output
            ->expects($this->once())
            ->method('writeln')
            ->with('Updated the priority of task 441798A8-5F37-4147-B166-5A63C095B2DA');

        $method = new \ReflectionMethod($command, 'execute');
        $method->setAccessible(true);
        $method->invoke($command, $input, $output);
    }

    /**
     * Test that an existing task will be ignored when the priority does not change.
     */
    public function testIgnoringExistingTask()
    {
        $entity = $this->createMock(TaskEntity::class);
        $entity
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('441798A8-5F37-4147-B166-5A63C095B2DA'));

        $task = $this->createMock(Commander\Task::class);
        $task
            ->expects($this->any())
            ->method('getEntity')
            ->will($this->returnValue($entity));

        $taskManager = $this->createMock(TaskManager::class);
        $taskManager
            ->expects($this->once())
            ->method('findNextTask')
            ->will($this->returnValue($task));

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
            ->getMockBuilder(JoinCommand::class)
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
            ->with('Task 441798A8-5F37-4147-B166-5A63C095B2DA already present, ignoring');

        $method = new \ReflectionMethod($command, 'execute');
        $method->setAccessible(true);
        $method->invoke($command, $input, $output);
    }
}
