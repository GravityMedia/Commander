<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Gravitymedia\CommanderTest\ORM;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\SchemaTool;
use Gedmo\DoctrineExtensions;
use Gedmo\Timestampable\TimestampableListener;
use GravityMedia\Commander\Commander;
use GravityMedia\Commander\ORM\TaskEntity;
use GravityMedia\Commander\ORM\TaskEntityRepository;

/**
 * Task entity repository test class.
 *
 * @package GravityMedia\CommanderTest\ORM
 *
 * @covers  GravityMedia\Commander\ORM\TaskEntityRepository
 * @uses    GravityMedia\Commander\ORM\TaskEntity
 */
class TaskEntityRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The entity manager.
     *
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * Get entity manager.
     *
     * @return EntityManagerInterface
     */
    protected function getEntityManager()
    {
        if (null === $this->entityManager) {
            $params = [
                'driver' => 'pdo_sqlite',
                'memory' => true
            ];

            $cache = new ArrayCache();

            /** @var AnnotationReader $reader */
            $reader = new CachedReader(new AnnotationReader(), $cache);

            $annotationDriver = new AnnotationDriver($reader, [__DIR__ . '/../../../src/ORM']);

            $driverChain = new MappingDriverChain();
            $driverChain->addDriver($annotationDriver, Commander::ENTITY_NAMESPACE);

            DoctrineExtensions::registerAbstractMappingIntoDriverChainORM($driverChain, $reader);

            $config = new Configuration();
            $config->setAutoGenerateProxyClasses(true);
            $config->setProxyDir(sys_get_temp_dir());
            $config->setProxyNamespace(Commander::ENTITY_NAMESPACE);
            $config->setMetadataDriverImpl($driverChain);
            $config->setMetadataCacheImpl($cache);
            $config->setQueryCacheImpl($cache);
            $config->setResultCacheImpl($cache);
            $config->setHydrationCacheImpl($cache);

            $timestampableListener = new TimestampableListener();
            $timestampableListener->setAnnotationReader($annotationDriver->getReader());

            $eventManager = new EventManager();
            $eventManager->addEventSubscriber($timestampableListener);

            $entityManager = EntityManager::create($params, $config, $eventManager);

            $schemaTool = new SchemaTool($entityManager);
            $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

            $this->entityManager = $entityManager;
        }

        return $this->entityManager;
    }

    /**
     * Test that all terminated tasks can be found.
     *
     * @group database
     */
    public function testFindingAllTerminated()
    {
        $entityManager = $this->getEntityManager();

        $entityOne = new TaskEntity();
        $entityOne->setPriority(TaskEntity::DEFAULT_PRIORITY);
        $entityOne->setCommandline('foo');
        $entityOne->setExitCode(0);

        $entityTwo = new TaskEntity();
        $entityTwo->setPriority(TaskEntity::DEFAULT_PRIORITY);
        $entityTwo->setCommandline('bar');

        $entityManager->persist($entityOne);
        $entityManager->persist($entityTwo);

        $entityManager->flush();

        $class = $entityManager->getClassMetadata(TaskEntity::class);

        $repository = new TaskEntityRepository($entityManager, $class);

        $entities = $repository->findAllWithNoExitCode();

        $this->assertCount(1, $entities);
        $this->assertSame('foo', $entities[0]->getCommandline());
    }

    /**
     * Test that the next entity can be found.
     */
    public function testFindingNext()
    {
        $entityManager = $this->getEntityManager();

        $createdAt = new \DateTime('yesterday');

        $entityOne = new TaskEntity();
        $entityOne->setPriority(1);
        $entityOne->setCommandline('foo');
        $entityOne->setCreatedAt($createdAt);
        $entityOne->setUpdatedAt(new \DateTime('now'));

        $entityTwo = new TaskEntity();
        $entityTwo->setPriority(1);
        $entityTwo->setCommandline('bar');
        $entityTwo->setCreatedAt($createdAt);
        $entityTwo->setUpdatedAt(new \DateTime('now - 1 minute'));

        $entityManager->persist($entityOne);
        $entityManager->persist($entityTwo);

        $entityManager->flush();

        $class = $entityManager->getClassMetadata(TaskEntity::class);

        $repository = new TaskEntityRepository($entityManager, $class);

        $entity = $repository->findNext();

        $this->assertInstanceOf(TaskEntity::class, $entity);
        $this->assertSame('bar', $entity->getCommandline());
    }

    /**
     * Test that the next entity can be found by commandline.
     */
    public function testFindingNextByCommandline()
    {
        $entityManager = $this->getEntityManager();

        $entityOne = new TaskEntity();
        $entityOne->setPriority(1000);
        $entityOne->setCommandline('foo');

        $entityTwo = new TaskEntity();
        $entityTwo->setPriority(1);
        $entityTwo->setCommandline('bar');

        $entityManager->persist($entityOne);
        $entityManager->persist($entityTwo);

        $entityManager->flush();

        $class = $entityManager->getClassMetadata(TaskEntity::class);

        $repository = new TaskEntityRepository($entityManager, $class);

        $entity = $repository->findNextByCommandline('foo');

        $this->assertInstanceOf(TaskEntity::class, $entity);
        $this->assertSame('foo', $entity->getCommandline());
    }
}
