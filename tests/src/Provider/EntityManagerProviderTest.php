<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Gravitymedia\CommanderTest\Provider;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use GravityMedia\Commander\Config;
use GravityMedia\Commander\Provider\EntityManagerProvider;

/**
 * Mapping driver provider test class.
 *
 * @package GravityMedia\CommanderTest\Provider
 *
 * @covers  GravityMedia\Commander\Provider\EntityManagerProvider
 */
class EntityManagerProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that a entity manager config can be created.
     */
    public function testEntityManagerConfigCreation()
    {
        $config = $this->createMock(Config::class);

        $cache = $this->createMock(Cache::class);

        $mappingDriver = $this->createMock(MappingDriver::class);

        $provider = new EntityManagerProvider($config, $cache, $mappingDriver);

        $this->assertInstanceOf(Configuration::class, $provider->getEntityManagerConfig());
    }

    /**
     * Test that a event manager can be created.
     */
    public function testEventManagerCreation()
    {
        $config = $this->createMock(Config::class);

        $cache = $this->createMock(Cache::class);

        $mappingDriver = $this->createMock(AnnotationDriver::class);

        $provider = new EntityManagerProvider($config, $cache, $mappingDriver);

        $this->assertInstanceOf(EventManager::class, $provider->getEventManager());
    }

    /**
     * Test that a connection can be created.
     */
    public function testConnectionCreation()
    {
        $config = $this->createMock(Config::class);

        $cache = $this->createMock(Cache::class);

        $mappingDriver = $this->createMock(MappingDriver::class);

        $provider = new EntityManagerProvider($config, $cache, $mappingDriver);

        $this->assertInstanceOf(Connection::class, $provider->getConnection());
    }

    /**
     * Test that a entity manager can be created.
     */
    public function testEntityManagerCreation()
    {
        $config = $this->createMock(Config::class);

        $cache = $this->createMock(Cache::class);

        $mappingDriver = $this->createMock(MappingDriver::class);

        $provider = new EntityManagerProvider($config, $cache, $mappingDriver);

        $this->assertInstanceOf(EntityManagerInterface::class, $provider->getEntityManager());
    }
}
