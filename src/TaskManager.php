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
 * Task manager class.
 *
 * @package GravityMedia\Commander
 */
class TaskManager
{
    /**
     * The entity manager.
     *
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * Create task manager object.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Get all tasks.
     *
     * @param array $criteria
     *
     * @return TaskEntity[]
     */
    public function getTasks(array $criteria = [])
    {
        if (0 === count($criteria)) {
            return $this->entityManager->getRepository(TaskEntity::class)->findAll();
        }

        return $this->entityManager->getRepository(TaskEntity::class)->findBy($criteria);
    }

    /**
     * Get single task by criteria.
     *
     * @param array $criteria
     *
     * @return null|TaskEntity
     */
    public function getTask(array $criteria)
    {
        return $this->entityManager->getRepository(TaskEntity::class)->findOneBy($criteria);
    }

    /**
     * Add task.
     *
     * @param string $script
     * @param int    $priority
     *
     * @return TaskEntity
     */
    public function addTask($script, $priority = TaskEntity::DEFAULT_PRIORITY)
    {
        $entity = new TaskEntity();
        $entity->setScript($script);
        $entity->setPriority($priority);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    /**
     * Update PID.
     *
     * @param TaskEntity $entity
     * @param int        $pid
     *
     * @return $this
     */
    public function updatePid(TaskEntity $entity, $pid)
    {
        $entity->setPid($pid);

        $this->entityManager->flush();

        return $this;
    }

    /**
     * Update exit code.
     *
     * @param TaskEntity $entity
     * @param int        $exitCode
     *
     * @return $this
     */
    public function updateExitCode(TaskEntity $entity, $exitCode)
    {
        $entity->setExitCode($exitCode);

        $this->entityManager->flush();

        return $this;
    }
}
