<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Console\Helper;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use GravityMedia\Commander\Config\CommanderConfig;
use Symfony\Component\Console\Helper\Helper;

/**
 * Entity manager helper class
 *
 * @package GravityMedia\Commander\Console\Helper
 */
class EntityManagerHelper extends Helper
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return static::class;
    }

    /**
     * Create cache
     *
     * @param CommanderConfig $config
     *
     * @return Cache
     */
    protected function createCache(CommanderConfig $config)
    {
        $directory = $config->getDatabaseCacheDirectory();
        if (null === $directory) {
            return new ArrayCache();
        }

        return new FilesystemCache($directory);
    }

    /**
     * Create annotation driver
     *
     * @param CommanderConfig $config
     *
     * @return AnnotationDriver
     */
    protected function createAnnotationDriver(CommanderConfig $config)
    {
        AnnotationRegistry::registerFile(
            __DIR__ . '/../../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php'
        );

        $reader = new CachedReader(new AnnotationReader(), $this->createCache($config));

        return new AnnotationDriver($reader, [__DIR__ . '/../../Entity']);
    }

    /**
     * Create entity manager configuration
     *
     * @param CommanderConfig $config
     *
     * @return Configuration
     */
    public function createEntityManagerConfiguration(CommanderConfig $config)
    {
        $proxyDirectory = $config->getDatabaseCacheDirectory();
        if (null === $proxyDirectory) {
            $proxyDirectory = sys_get_temp_dir();
        }

        $configuration = new Configuration();
        $configuration->setAutoGenerateProxyClasses(true);
        $configuration->setProxyDir($proxyDirectory);
        $configuration->setProxyNamespace('Evolver\ScheduleUtilModule\Entity');
        $configuration->setMetadataDriverImpl($this->createAnnotationDriver($config));
        $configuration->setMetadataCacheImpl($this->createCache($config));
        $configuration->setQueryCacheImpl($this->createCache($config));
        $configuration->setResultCacheImpl($this->createCache($config));
        $configuration->setHydrationCacheImpl($this->createCache($config));

        return $configuration;
    }

    /**
     * Create entity manager
     *
     * @param CommanderConfig $config
     *
     * @return EntityManager
     */
    public function createEntityManager(CommanderConfig $config)
    {
        $connection = [
            'driver' => 'pdo_sqlite',
            'path' => $config->getDatabasePath()
        ];

        return EntityManager::create($connection, $this->createEntityManagerConfiguration($config));
    }
}
