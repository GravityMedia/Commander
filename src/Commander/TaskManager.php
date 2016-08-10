<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Commander;

use Doctrine\ORM\EntityManagerInterface;
use GravityMedia\Commander\ORM\TaskEntity;
use GravityMedia\Commander\ORM\TaskEntityRepository;

/**
 * Task manager class.
 *
 * @package GravityMedia\Commander\Commander
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
     * The repository.
     *
     * @var TaskEntityRepository
     */
    protected $repository;

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
     * Get repository.
     *
     * @return TaskEntityRepository
     */
    public function getRepository()
    {
        if (null === $this->repository) {
            $this->repository = $this->entityManager->getRepository(TaskEntity::class);
        }

        return $this->repository;
    }

    /**
     * Map entities.
     *
     * @param TaskEntity[] $entities
     *
     * @return Task[]
     */
    protected function mapEntities(array $entities)
    {
        return array_map(function (TaskEntity $entity) {
            return new Task($this->entityManager, $entity);
        }, $entities);
    }

    /**
     * Find all tasks.
     *
     * @return Task[]
     */
    public function findAllTasks()
    {
        return $this->mapEntities($this->getRepository()->findAll());
    }

    /**
     * Find all terminated tasks.
     *
     * @return Task[]
     */
    public function findAllTerminatedTasks()
    {
        return $this->mapEntities($this->getRepository()->findAllWithNoExitCode());
    }

    /**
     * Find next task.
     *
     * @param array $criteria
     *
     * @return Task|null
     */
    public function findNextTask(array $criteria = [])
    {
        $entity = $this->getRepository()->findNext($criteria);
        if (null === $entity) {
            return null;
        }

        return new Task($this->entityManager, $entity);
    }

    /**
     * Create and persist new task.
     *
     * @param string   $commandline
     * @param int|null $priority
     *
     * @return Task
     */
    public function newTask($commandline, $priority = null)
    {
        if (null === $priority) {
            $priority = TaskEntity::DEFAULT_PRIORITY;
        }

        $entity = new TaskEntity();
        $entity->setCommandline($commandline);
        $entity->setPriority($priority);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return new Task($this->entityManager, $entity);
    }
}
