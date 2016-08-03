<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\ORM;

use Doctrine\ORM\EntityRepository;

/**
 * Task entity repository class.
 *
 * @package GravityMedia\Commander\ORM
 **/
class TaskEntityRepository extends EntityRepository
{
    /**
     * Finds all terminated entities.
     *
     * @return TaskEntity[]
     */
    public function findAllTerminated()
    {
        $queryBuilder = $this->createQueryBuilder('task');
        $queryBuilder->where(
            $queryBuilder->expr()->isNotNull('task.exitCode')
        );

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Find next entity.
     *
     * @param array $criteria
     *
     * @return TaskEntity|null
     */
    public function findNext(array $criteria = [])
    {
        $criteria = array_merge($criteria, ['pid' => null]);
        $orderBy = ['priority' => 'ASC', 'createdAt' => 'ASC', 'updatedAt' => 'ASC'];

        return $this->findOneBy($criteria, $orderBy);
    }

    /**
     * Find next entity by commandline.
     *
     * @param string $commandline
     *
     * @return TaskEntity|null
     */
    public function findNextByCommandline($commandline)
    {
        return $this->findNext(['commandline' => $commandline]);
    }
}
