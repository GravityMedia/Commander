<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander;

use Doctrine\ORM\EntityManagerInterface;
use GravityMedia\Commander\Entity\TaskEntity;

/**
 * Task class.
 *
 * @package GravityMedia\Commander
 */
class Task
{
    /**
     * The entity manager.
     *
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * The entity.
     *
     * @var TaskEntity
     */
    protected $entity;

    /**
     * Create task object.
     *
     * @param EntityManagerInterface $entityManager
     * @param TaskEntity             $entity
     */
    public function __construct(EntityManagerInterface $entityManager, TaskEntity $entity)
    {
        $this->entityManager = $entityManager;
        $this->entity = $entity;
    }

    /**
     * Get entity
     *
     * @return TaskEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Update priority.
     *
     * @param int $priority
     *
     * @return $this
     */
    public function updatePriority($priority)
    {
        $this->entity->setPriority($priority);
        $this->entityManager->flush();

        return $this;
    }

    /**
     * Update PID.
     *
     * @param int $pid
     *
     * @return $this
     */
    public function updatePid($pid)
    {
        $this->entity->setPid($pid);
        $this->entityManager->flush();

        return $this;
    }

    /**
     * Update exit code.
     *
     * @param int $exitCode
     *
     * @return $this
     */
    public function updateExitCode($exitCode)
    {
        $this->entity->setExitCode($exitCode);
        $this->entityManager->flush();

        return $this;
    }
}
