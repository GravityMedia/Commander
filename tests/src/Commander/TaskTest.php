<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Gravitymedia\CommanderTest\Commander;

use Doctrine\ORM\EntityManagerInterface;
use GravityMedia\Commander\Commander\Task;
use GravityMedia\Commander\ORM\TaskEntity;

/**
 * Task test class.
 *
 * @package GravityMedia\CommanderTest\Commander
 *
 * @covers  GravityMedia\Commander\Commander\Task
 */
class TaskTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the task can be created.
     */
    public function testTaskCreation()
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entity = $this->createMock(TaskEntity::class);

        $task = new Task($entityManager, $entity);

        $this->assertSame($entity, $task->getEntity());
    }

    /**
     * Test that the task can be prioritized.
     */
    public function testTaskPrioritization()
    {
        $entity = $this->createMock(TaskEntity::class);
        $entity->expects($this->once())
            ->method('setPriority')
            ->with(100);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('flush');

        $task = new Task($entityManager, $entity);

        $this->assertSame($task, $task->prioritize(100));
    }

    /**
     * Test that the task can be started.
     */
    public function testTaskBegin()
    {
        $entity = $this->createMock(TaskEntity::class);
        $entity->expects($this->once())
            ->method('setPid')
            ->with(1001);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('flush');

        $task = new Task($entityManager, $entity);

        $this->assertSame($task, $task->begin(1001));
    }

    /**
     * Test that the task can be finished.
     */
    public function testTaskFinish()
    {
        $entity = $this->createMock(TaskEntity::class);
        $entity->expects($this->once())
            ->method('setExitCode')
            ->with(0);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('flush');

        $task = new Task($entityManager, $entity);

        $this->assertSame($task, $task->finish(0));
    }

    /**
     * Test that the task can be finished.
     */
    public function testTaskRemoval()
    {
        $entity = $this->createMock(TaskEntity::class);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('remove')
            ->with($entity);
        $entityManager->expects($this->once())
            ->method('flush');

        $task = new Task($entityManager, $entity);

        $this->assertSame($task, $task->remove());
    }
}
