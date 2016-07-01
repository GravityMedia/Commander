<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\SchemaValidator;
use Gedmo\DoctrineExtensions;
use Gedmo\Timestampable\TimestampableListener;
use GravityMedia\Commander\Config\CommanderConfig;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * Commander class.
 *
 * @package GravityMedia\Commander
 */
class Commander
{
    /**
     * The entity namespace.
     */
    const ENTITY_NAMESPACE = 'GravityMedia\Commander\Entity';

    /**
     * @var CommanderConfig
     */
    protected $config;

    /**
     * The mapping driver.
     *
     * @var MappingDriver
     */
    protected $mappingDriver;

    /**
     * The entity manager config.
     *
     * @var Configuration
     */
    protected $entityManagerConfig;

    /**
     * The entity manager.
     *
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * The schema validator.
     *
     * @var SchemaValidator
     */
    protected $schemaValidator;

    /**
     * The schema tool.
     *
     * @var SchemaTool
     */
    protected $schemaTool;

    /**
     * The logger.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Create commander object.
     *
     * @param CommanderConfig $config
     */
    public function __construct(CommanderConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Create cache object.
     *
     * @return Cache
     */
    protected function createCache()
    {
        $directory = $this->config->getDatabaseCacheDirectory();
        if (null === $directory) {
            return new ArrayCache();
        }

        return new FilesystemCache($directory);
    }

    /**
     * Get mapping driver.
     *
     * @return MappingDriver
     */
    public function getMappingDriver()
    {
        if (null === $this->mappingDriver) {
            AnnotationRegistry::registerFile(
                __DIR__ . '/../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php'
            );

            $driverChain = new MappingDriverChain();
            $reader = new CachedReader(new AnnotationReader(), $this->createCache());

            DoctrineExtensions::registerAbstractMappingIntoDriverChainORM($driverChain, $reader);

            $annotationDriver = new AnnotationDriver($reader, [__DIR__ . '/Entity']);
            $driverChain->addDriver($annotationDriver, static::ENTITY_NAMESPACE);

            $this->mappingDriver = $driverChain;
        }

        return $this->mappingDriver;
    }

    /**
     * Get entity manager config.
     *
     * @return Configuration
     */
    public function getEntityManagerConfig()
    {
        if (null === $this->entityManagerConfig) {
            $proxyDirectory = $this->config->getDatabaseCacheDirectory();
            if (null === $proxyDirectory) {
                $proxyDirectory = sys_get_temp_dir();
            }

            $config = new Configuration();
            $config->setAutoGenerateProxyClasses(true);
            $config->setProxyDir($proxyDirectory);
            $config->setProxyNamespace(static::ENTITY_NAMESPACE);
            $config->setMetadataDriverImpl($this->getMappingDriver());
            $config->setMetadataCacheImpl($this->createCache());
            $config->setQueryCacheImpl($this->createCache());
            $config->setResultCacheImpl($this->createCache());
            $config->setHydrationCacheImpl($this->createCache());

            $this->entityManagerConfig = $config;
        }

        return $this->entityManagerConfig;
    }

    /**
     * Get entity manager.
     *
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        if (null === $this->entityManager) {
            $connection = [
                'driver' => 'pdo_sqlite',
                'path' => $this->config->getDatabaseFilePath()
            ];

            $configuration = $this->getEntityManagerConfig();
            $metadataDriver = $configuration->getMetadataDriverImpl();

            $timestampableListener = new TimestampableListener();
            if ($metadataDriver instanceof AnnotationDriver) {
                $timestampableListener->setAnnotationReader($metadataDriver->getReader());
            }

            $eventManager = new EventManager();
            $eventManager->addEventSubscriber($timestampableListener);

            $this->entityManager = EntityManager::create($connection, $configuration, $eventManager);
        }

        return $this->entityManager;
    }

    /**
     * Get schema validator.
     *
     * @return SchemaValidator
     */
    public function getSchemaValidator()
    {
        if (null === $this->schemaValidator) {
            $entityManager = $this->getEntityManager();

            $this->schemaValidator = new SchemaValidator($entityManager);
        }

        return $this->schemaValidator;
    }

    /**
     * Get schema tool.
     *
     * @return SchemaTool
     */
    public function getSchemaTool()
    {
        if (null === $this->schemaTool) {
            $entityManager = $this->getEntityManager();

            $this->schemaTool = new SchemaTool($entityManager);
        }

        return $this->schemaTool;
    }

    /**
     * Get logger.
     *
     * @return LoggerInterface
     */
    public function getLogger()
    {
        if (null === $this->logger) {
            $logger = new Logger('COMMANDER');

            $path = $this->config->getLogFilePath();
            if (null !== $path) {
                $logger->pushHandler(new StreamHandler($path));
            }

            $this->logger = $logger;
        }

        return $this->logger;
    }

    /**
     * Return whether or not the schema is valid.
     *
     * @return bool
     */
    public function isSchemaValid()
    {
        return $this->getSchemaValidator()->schemaInSyncWithMetadata();
    }

    /**
     * Update schema.
     *
     * @return $this
     */
    public function updateSchema()
    {
        $classes = $this->getEntityManager()->getMetadataFactory()->getAllMetadata();
        $this->getSchemaTool()->updateSchema($classes);

        return $this;
    }
}
