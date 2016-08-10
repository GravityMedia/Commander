<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Gravitymedia\CommanderTest\Commander;

use Doctrine\ORM\EntityManagerInterface;
use GravityMedia\Commander\Commander\Task;
use GravityMedia\Commander\Commander\TaskManager;
use GravityMedia\Commander\ORM\TaskEntity;
use GravityMedia\Commander\ORM\TaskEntityRepository;

/**
 * Task manager test class.
 *
 * @package GravityMedia\CommanderTest\Commander
 *
 * @covers  GravityMedia\Commander\Commander\TaskManager
 * @uses    GravityMedia\Commander\Commander\Task
 * @uses    GravityMedia\Commander\ORM\TaskEntity
 */
class TaskManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the task manager can be created.
     */
    public function testTaskManagerCreation()
    {
        $repository = $this->createMock(TaskEntityRepository::class);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(TaskEntity::class)
            ->will($this->returnValue($repository));

        $taskManager = new TaskManager($entityManager);

        $this->assertSame($repository, $taskManager->getRepository());
    }

    /**
     * Test that all tasks can be found.
     */
    public function testFindingAllTasks()
    {
        $entities = [
            $this->createMock(TaskEntity::class),
            $this->createMock(TaskEntity::class),
            $this->createMock(TaskEntity::class),
        ];

        $repository = $this->createMock(TaskEntityRepository::class);
        $repository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue($entities));

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(TaskEntity::class)
            ->will($this->returnValue($repository));

        $taskManager = new TaskManager($entityManager);

        $tasks = $taskManager->findAllTasks();

        $this->assertCount(3, $tasks);
        $this->assertContainsOnlyInstancesOf(Task::class, $tasks);
    }

    /**
     * Test that all terminated tasks can be found.
     */
    public function testFindingAllTerminatedTasks()
    {
        $entities = [
            $this->createMock(TaskEntity::class),
            $this->createMock(TaskEntity::class),
            $this->createMock(TaskEntity::class),
        ];

        $repository = $this->createMock(TaskEntityRepository::class);
        $repository
            ->expects($this->once())
            ->method('findAllWithNoExitCode')
            ->will($this->returnValue($entities));

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(TaskEntity::class)
            ->will($this->returnValue($repository));

        $taskManager = new TaskManager($entityManager);

        $tasks = $taskManager->findAllTerminatedTasks();

        $this->assertCount(3, $tasks);
        $this->assertContainsOnlyInstancesOf(Task::class, $tasks);
    }

    /**
     * Test that the next task can be found.
     */
    public function testFindingNextTask()
    {
        $entity = $this->createMock(TaskEntity::class);

        $repository = $this->createMock(TaskEntityRepository::class);
        $repository
            ->expects($this->once())
            ->method('findNext')
            ->will($this->returnValue($entity));

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(TaskEntity::class)
            ->will($this->returnValue($repository));

        $taskManager = new TaskManager($entityManager);

        $this->assertInstanceOf(Task::class, $taskManager->findNextTask());
    }

    /**
     * Test that the next task can be null when it can not be found.
     */
    public function testFindingNextTaskReturnsNull()
    {
        $repository = $this->createMock(TaskEntityRepository::class);
        $repository
            ->expects($this->once())
            ->method('findNext')
            ->will($this->returnValue(null));

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(TaskEntity::class)
            ->will($this->returnValue($repository));

        $taskManager = new TaskManager($entityManager);

        $this->assertNull($taskManager->findNextTask());
    }

    /**
     * Test that a new task can be created.
     *
     * @dataProvider provideArgumentsForTaskCreation()
     *
     * @param string   $commandline
     * @param int|null $priority
     * @param int      $expectedPriority
     */
    public function testCreatingNewTask($commandline, $priority, $expectedPriority)
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('persist');
        $entityManager
            ->expects($this->once())
            ->method('flush');

        $taskManager = new TaskManager($entityManager);

        $task = $taskManager->newTask($commandline, $priority);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertSame($commandline, $task->getEntity()->getCommandline());
        $this->assertSame($expectedPriority, $task->getEntity()->getPriority());
    }

    /**
     * Provide arguments for task creation.
     *
     * @return array
     */
    public function provideArgumentsForTaskCreation()
    {
        return [
            ['pwd', null, TaskEntity::DEFAULT_PRIORITY],
            ['foo', 100, 100],
        ];
    }
}
