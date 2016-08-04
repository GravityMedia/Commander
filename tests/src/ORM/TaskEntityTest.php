<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Gravitymedia\CommanderTest\ORM;

use GravityMedia\Commander\ORM\TaskEntity;

/**
 * Task entity test class.
 *
 * @package GravityMedia\CommanderTest\ORM
 *
 * @covers  GravityMedia\Commander\ORM\TaskEntity
 */
class TaskEntityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that all values can be set and are returned afterwards.
     */
    public function testTaskEntityAccessors()
    {
        $createdAt = new \DateTime('yesterday');
        $updatedAt = new \DateTime();

        $entity = new TaskEntity();
        $entity
            ->setPriority(100)
            ->setCommandline('commandline')
            ->setPid(1001)
            ->setExitCode(0)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt);

        $this->assertNull($entity->getId());
        $this->assertSame(100, $entity->getPriority());
        $this->assertSame('commandline', $entity->getCommandline());
        $this->assertSame(1001, $entity->getPid());
        $this->assertSame(0, $entity->getExitCode());
        $this->assertSame($createdAt, $entity->getCreatedAt());
        $this->assertSame($updatedAt, $entity->getUpdatedAt());
    }
}
