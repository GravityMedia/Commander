<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Provider;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration as EntityManagerConfig;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Gedmo\Timestampable\TimestampableListener;
use GravityMedia\Commander\Commander;
use GravityMedia\Commander\Config;

/**
 * Entity manager provider class.
 *
 * @package GravityMedia\Commander\Provider
 */
class EntityManagerProvider
{
    /**
     * The config.
     *
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
     * The entity manager config.
     *
     * @var EntityManagerConfig
     */
    protected $entityManagerConfig;

    /**
     * The event manager.
     *
     * @var EventManager
     */
    protected $eventManager;

    /**
     * The connection.
     *
     * @var Connection
     */
    protected $connection;

    /**
     * The entity manager.
     *
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * Create entity manager provider object.
     *
     * @param Config        $config
     * @param Cache         $cache
     * @param MappingDriver $mappingDriver
     */
    public function __construct(Config $config, Cache $cache, MappingDriver $mappingDriver)
    {
        $this->config = $config;
        $this->cache = $cache;
        $this->mappingDriver = $mappingDriver;
    }

    /**
     * Get entity manager config.
     *
     * @return EntityManagerConfig
     */
    public function getEntityManagerConfig()
    {
        if (null === $this->entityManagerConfig) {
            $cacheDirectory = $this->config->getCacheDirectory();
            if (null === $cacheDirectory) {
                $cacheDirectory = sys_get_temp_dir();
            }

            $config = new EntityManagerConfig();
            $config->setAutoGenerateProxyClasses(true);
            $config->setProxyDir(rtrim($cacheDirectory, '/') . '/proxy');
            $config->setProxyNamespace(Commander::ENTITY_NAMESPACE);
            $config->setMetadataDriverImpl($this->mappingDriver);
            $config->setMetadataCacheImpl($this->cache);
            $config->setQueryCacheImpl($this->cache);
            $config->setResultCacheImpl($this->cache);
            $config->setHydrationCacheImpl($this->cache);

            $this->entityManagerConfig = $config;
        }

        return $this->entityManagerConfig;
    }

    /**
     * Get event manager.
     *
     * @return EventManager
     */
    public function getEventManager()
    {
        if (null === $this->eventManager) {
            $metadataDriver = $this->getEntityManagerConfig()->getMetadataDriverImpl();

            $timestampableListener = new TimestampableListener();
            if ($metadataDriver instanceof AnnotationDriver) {
                $timestampableListener->setAnnotationReader($metadataDriver->getReader());
            }

            $eventManager = new EventManager();
            $eventManager->addEventSubscriber($timestampableListener);

            $this->eventManager = $eventManager;
        }

        return $this->eventManager;
    }

    /**
     * Get connection.
     *
     * @return Connection
     */
    public function getConnection()
    {
        if (null === $this->connection) {
            $params = [
                'driver' => 'pdo_sqlite',
                'path' => $this->config->getDatabaseFilePath()
            ];

            $config = $this->getEntityManagerConfig();

            $eventManager = $this->getEventManager();

            $this->connection = DriverManager::getConnection($params, $config, $eventManager);
        }

        return $this->connection;
    }

    /**
     * Get entity manager.
     *
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        if (null === $this->entityManager) {
            $connection = $this->getConnection();

            $config = $this->getEntityManagerConfig();

            $eventManager = $this->getEventManager();

            $this->entityManager = EntityManager::create($connection, $config, $eventManager);
        }

        return $this->entityManager;
    }
}
