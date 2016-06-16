<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use GravityMedia\Commander\Entity\TaskEntity;

/**
 * Task manager class
 *
 * @package GravityMedia\Commander
 */
class TaskManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var SchemaTool
     */
    protected $schemaTool;

    /**
     * Create task manager object
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->schemaTool = new SchemaTool($entityManager);
    }

    /**
     * Update schema
     */
    public function updateSchema()
    {
        $classes = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $this->schemaTool->updateSchema($classes);

        return $this;
    }

    /**
     * Get tasks
     *
     * @return TaskEntity[]
     */
    public function getTasks()
    {
        return $this->entityManager->getRepository(TaskEntity::class)->findAll();
    }

    /**
     * Add task
     *
     * @param TaskEntity $entity
     *
     * @return $this
     */
    public function addTask(TaskEntity $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $this;
    }
}
