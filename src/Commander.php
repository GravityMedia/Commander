<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\SchemaValidator;
use GravityMedia\Commander\Commander\TaskManager;
use GravityMedia\Commander\Provider\CacheProvider;
use GravityMedia\Commander\Provider\EntityManagerProvider;
use GravityMedia\Commander\Provider\LoggerProvider;
use GravityMedia\Commander\Provider\MappingDriverProvider;
use GravityMedia\Commander\Provider\SchemaToolProvider;
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
    const ENTITY_NAMESPACE = 'GravityMedia\Commander\ORM';

    /**
     * The logger name.
     */
    const LOGGER_NAME = 'COMMANDER';

    /**
     * @var bool
     */
    protected $initialized;

    /**
     * @var Config
     */
    protected $config;

    /**
     * The cache.
     *
     * @var Cache
     */
    protected $cache;

    /**
     * The mapping driver.
     *
     * @var MappingDriver
     */
    protected $mappingDriver;

    /**
     * The entity manager.
     *
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * The schema tool.
     *
     * @var SchemaTool
     */
    protected $schemaTool;

    /**
     * The schema validator.
     *
     * @var SchemaValidator
     */
    protected $schemaValidator;

    /**
     * The logger.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * The task manager.
     *
     * @var TaskManager
     */
    protected $taskManager;

    /**
     * Create commander object.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->initialized = false;
        $this->config = $config;
    }

    /**
     * Initialize commander.
     *
     * @return $this
     */
    public function initialize()
    {
        if ($this->initialized) {
            return $this;
        }

        if ($this->getSchemaValidator()->schemaInSyncWithMetadata()) {
            $this->initialized = true;

            return $this;
        }

        $classes = $this->getEntityManager()->getMetadataFactory()->getAllMetadata();
        $this->getSchemaTool()->updateSchema($classes);

        $this->initialized = true;

        return $this;
    }

    /**
     * Get cache.
     *
     * @return Cache
     */
    public function getCache()
    {
        if (null === $this->cache) {
            $provider = new CacheProvider($this->config);

            $this->cache = $provider->getCache();
        }

        return $this->cache;
    }

    /**
     * Get mapping driver.
     *
     * @return MappingDriver
     */
    public function getMappingDriver()
    {
        if (null === $this->mappingDriver) {
            $provider = new MappingDriverProvider($this->getCache());

            $this->mappingDriver = $provider->getMappingDriver();
        }

        return $this->mappingDriver;
    }

    /**
     * Get entity manager.
     *
     * @return EntityManagerInterface
     *
     * @throws \LogicException
     */
    public function getEntityManager()
    {
        if (!$this->initialized) {
            throw new \LogicException('The entity manager is not available before initialization');
        }

        if (null === $this->entityManager) {
            $provider = new EntityManagerProvider($this->config, $this->getCache(), $this->getMappingDriver());

            $this->entityManager = $provider->getEntityManager();
        }

        return $this->entityManager;
    }

    /**
     * Get schema tool.
     *
     * @return SchemaTool
     */
    public function getSchemaTool()
    {
        if (null === $this->schemaTool) {
            $provider = new SchemaToolProvider($this->getEntityManager());

            $this->schemaTool = $provider->getSchemaTool();
        }

        return $this->schemaTool;
    }

    /**
     * Get schema validator.
     *
     * @return SchemaValidator
     */
    public function getSchemaValidator()
    {
        if (null === $this->schemaValidator) {
            $provider = new SchemaToolProvider($this->getEntityManager());

            $this->schemaValidator = $provider->getSchemaValidator();
        }

        return $this->schemaValidator;
    }

    /**
     * Get logger.
     *
     * @return LoggerInterface
     */
    public function getLogger()
    {
        if (null === $this->logger) {
            $provider = new LoggerProvider($this->config);

            $this->logger = $provider->getLogger();
        }

        return $this->logger;
    }

    /**
     * Get task manager.
     *
     * @return TaskManager
     */
    public function getTaskManager()
    {
        if (null === $this->taskManager) {
            $this->taskManager = new TaskManager($this->getEntityManager());
        }

        return $this->taskManager;
    }
}
