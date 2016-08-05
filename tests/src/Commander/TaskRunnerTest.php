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
use GravityMedia\Commander\Commander\TaskRunner;
use GravityMedia\Commander\ORM\TaskEntity;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Task runner test class.
 *
 * @package GravityMedia\CommanderTest\Commander
 *
 * @covers  GravityMedia\Commander\Commander\TaskRunner
 * @uses    GravityMedia\Commander\Commander\Task
 * @uses    GravityMedia\Commander\ORM\TaskEntity
 */
class TaskRunnerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the task runner runs all tasks.
     */
    public function testTaskRunnerRunsAllTasks()
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $entityOne = new TaskEntity();
        $entityOne->setCommandline('cd');

        $entityTwo = new TaskEntity();
        $entityTwo->setCommandline('foobarbaz');

        $taskManager = $this->createMock(TaskManager::class);
        $taskManager
            ->expects($this->exactly(3))
            ->method('findNextTask')
            ->will($this->onConsecutiveCalls(
                new Task($entityManager, $entityOne),
                new Task($entityManager, $entityTwo),
                null
            ));

        $output = $this->createMock(OutputInterface::class);

        $logger = $this->createMock(LoggerInterface::class);

        $taskRunner = new TaskRunner($taskManager, $output, $logger);
        $taskRunner->runAll(60);

        $this->assertInternalType('integer', $entityOne->getPid());
        $this->assertInternalType('integer', $entityTwo->getPid());
        $this->assertEquals(0, $entityOne->getExitCode());
        $this->assertNotEquals(0, $entityTwo->getExitCode());
    }
}
