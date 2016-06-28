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
     * @return Task[]
     */
    public function getTasks(array $criteria = [])
    {
        return array_map(function (TaskEntity $entity) {
            return new Task($this->entityManager, $entity);
        }, $this->entityManager->getRepository(TaskEntity::class)->findBy($criteria));
    }

    /**
     * Get single task by criteria.
     *
     * @param array $criteria
     *
     * @return null|Task
     */
    public function getTask(array $criteria)
    {
        /** @var TaskEntity $entity */
        $entity = $this->entityManager->getRepository(TaskEntity::class)->findOneBy($criteria);
        if (null === $entity) {
            return null;
        }

        return new Task($this->entityManager, $entity);
    }

    /**
     * Add task.
     *
     * @param string $script
     * @param int    $priority
     *
     * @return Task
     */
    public function addTask($script, $priority = TaskEntity::DEFAULT_PRIORITY)
    {
        $entity = new TaskEntity();
        $entity->setScript($script);
        $entity->setPriority($priority);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return new Task($this->entityManager, $entity);
    }
}
