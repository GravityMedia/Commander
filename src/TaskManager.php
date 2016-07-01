<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander;

use Doctrine\Common\Persistence\ObjectRepository;
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
     * The repository.
     *
     * @var ObjectRepository
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
     * @return ObjectRepository
     */
    public function getRepository()
    {
        if (null === $this->repository) {
            $this->repository = $this->entityManager->getRepository(TaskEntity::class);
        }

        return $this->repository;
    }

    /**
     * Find all tasks.
     *
     * @param array      $criteria
     * @param null|array $orderBy
     * @param null|int   $limit
     *
     * @return Task[]
     */
    public function findAllTasks(array $criteria = [], array $orderBy = null, $limit = null)
    {
        return array_map(function (TaskEntity $entity) {
            return new Task($this->entityManager, $entity);
        }, $this->getRepository()->findBy($criteria, $orderBy, $limit));
    }

    /**
     * Find next task.
     *
     * @param array $criteria
     *
     * @return null|Task
     */
    public function findNextTask(array $criteria = [])
    {
        $tasks = $this->findAllTasks($criteria, ['priority' => 'ASC', 'createdAt' => 'ASC', 'updatedAt' => 'ASC'], 1);
        if (count($tasks) < 1) {
            return null;
        }

        return $tasks[0];
    }

    /**
     * Find single task by criteria.
     *
     * @param array $criteria
     *
     * @return null|Task
     */
    public function findTask(array $criteria)
    {
        /** @var TaskEntity $entity */
        $entity = $this->getRepository()->findOneBy($criteria);
        if (null === $entity) {
            return null;
        }

        return new Task($this->entityManager, $entity);
    }

    /**
     * Create new task.
     *
     * @param string $script
     * @param int    $priority
     *
     * @return Task
     */
    public function newTask($script, $priority = TaskEntity::DEFAULT_PRIORITY)
    {
        $entity = new TaskEntity();
        $entity->setScript($script);
        $entity->setPriority($priority);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return new Task($this->entityManager, $entity);
    }
}
