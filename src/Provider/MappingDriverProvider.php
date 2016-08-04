<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Provider;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Gedmo\DoctrineExtensions;
use GravityMedia\Commander\Commander;

/**
 * Mapping driver provider class.
 *
 * @package GravityMedia\Commander\Provider
 */
class MappingDriverProvider
{
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
     * Create mapping driver provider object.
     *
     * @param Cache $cache
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
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
                __DIR__ . '/../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php'
            );

            $driverChain = new MappingDriverChain();
            $reader = new CachedReader(new AnnotationReader(), $this->cache);

            DoctrineExtensions::registerAbstractMappingIntoDriverChainORM($driverChain, $reader);

            $annotationDriver = new AnnotationDriver($reader, [__DIR__ . '/../ORM']);
            $driverChain->addDriver($annotationDriver, Commander::ENTITY_NAMESPACE);

            $this->mappingDriver = $driverChain;
        }

        return $this->mappingDriver;
    }
}
