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
use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Gedmo\DoctrineExtensions;
use Gedmo\Timestampable\TimestampableListener;
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
     * @return MappingDriver
     */
    protected function createAnnotationDriver(CommanderConfig $config)
    {
        AnnotationRegistry::registerFile(
            __DIR__ . '/../../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php'
        );

        $driverChain = new MappingDriverChain();
        $reader = new CachedReader(new AnnotationReader(), $this->createCache($config));

        DoctrineExtensions::registerAbstractMappingIntoDriverChainORM($driverChain, $reader);

        $annotationDriver = new AnnotationDriver($reader, [__DIR__ . '/../../Entity']);
        $driverChain->addDriver($annotationDriver, 'GravityMedia\Commander\Entity');

        return $driverChain;
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
        $configuration->setProxyNamespace('GravityMedia\Commander\Entity');
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
     * @return EntityManagerInterface
     */
    public function createEntityManager(CommanderConfig $config)
    {
        $connection = [
            'driver' => 'pdo_sqlite',
            'path' => $config->getDatabasePath()
        ];

        $configuration = $this->createEntityManagerConfiguration($config);

        $metadataDriver = $configuration->getMetadataDriverImpl();

        $timestampableListener = new TimestampableListener();
        if ($metadataDriver instanceof AnnotationDriver) {
            $timestampableListener->setAnnotationReader($metadataDriver->getReader());
        }

        $eventManager = new EventManager();
        $eventManager->addEventSubscriber($timestampableListener);

        return EntityManager::create($connection, $configuration, $eventManager);
    }
}
