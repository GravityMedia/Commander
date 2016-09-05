<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Commander;

use Doctrine\ORM\EntityManagerInterface;
use GravityMedia\Commander\ORM\TaskEntity;

/**
 * Task class.
 *
 * @package GravityMedia\Commander\Commander
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
     * Prioritize task.
     *
     * @param int $priority
     *
     * @return $this
     */
    public function prioritize($priority)
    {
        $this->entity->setPriority($priority);
        $this->entityManager->flush();

        return $this;
    }

    /**
     * Defer task.
     *
     * @param int      $pid
     * @param callable $deferrer
     *
     * @return $this
     */
    public function defer($pid, $deferrer)
    {
        $connection = $this->entityManager->getConnection();

        $this->entity->setPid($pid);
        $this->entityManager->flush();

        $connection->close();
        $exitCode = $deferrer();
        $connection->connect();

        $this->entity->setExitCode($exitCode);
        $this->entityManager->flush();

        return $this;
    }

    /**
     * Remove task.
     *
     * @return $this
     */
    public function remove()
    {
        $this->entityManager->remove($this->entity);
        $this->entityManager->flush();

        return $this;
    }
}
