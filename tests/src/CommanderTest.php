<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Gravitymedia\CommanderTest;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\SchemaValidator;
use GravityMedia\Commander\Commander;
use GravityMedia\Commander\Commander\TaskManager;
use GravityMedia\Commander\Config;
use Psr\Log\LoggerInterface;

/**
 * Commander test class.
 *
 * @package GravityMedia\CommanderTest
 *
 * @covers  GravityMedia\Commander\Commander
 * @uses    GravityMedia\Commander\Provider\CacheProvider
 * @uses    GravityMedia\Commander\Provider\MappingDriverProvider
 * @uses    GravityMedia\Commander\Provider\EntityManagerProvider
 * @uses    GravityMedia\Commander\Provider\SchemaToolProvider
 * @uses    GravityMedia\Commander\Provider\LoggerProvider
 * @uses    GravityMedia\Commander\Commander\TaskManager
 */
class CommanderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that initializing checks that the schema is in sync with metadata.
     */
    public function testInitializeCommanderWithSynchronizedSchema()
    {
        $schemaValidator = $this->createMock(SchemaValidator::class);
        $schemaValidator
            ->expects($this->once())
            ->method('schemaInSyncWithMetadata')
            ->will($this->returnValue(true));

        $commander = $this
            ->getMockBuilder(Commander::class)
            ->setMethods(['getSchemaValidator'])
            ->disableOriginalConstructor()
            ->getMock();
        $commander
            ->expects($this->once())
            ->method('getSchemaValidator')
            ->will($this->returnValue($schemaValidator));

        $this->assertSame($commander, $commander->initialize());
    }

    /**
     * Test that initializing updates the schema when necessary.
     */
    public function testInitializeCommanderWithNonSynchronizedSchema()
    {
        $schemaValidator = $this->createMock(SchemaValidator::class);
        $schemaValidator
            ->expects($this->once())
            ->method('schemaInSyncWithMetadata')
            ->will($this->returnValue(false));

        $metadataFactory = $this->createMock(ClassMetadataFactory::class);
        $metadataFactory
            ->expects($this->once())
            ->method('getAllMetadata')
            ->will($this->returnValue([]));

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('getMetadataFactory')
            ->will($this->returnValue($metadataFactory));

        $schemaTool = $this->createMock(SchemaTool::class);
        $schemaTool
            ->expects($this->once())
            ->method('updateSchema')
            ->with([]);

        $commander = $this
            ->getMockBuilder(Commander::class)
            ->setMethods(['getSchemaValidator', 'getEntityManager', 'getSchemaTool'])
            ->disableOriginalConstructor()
            ->getMock();
        $commander
            ->expects($this->once())
            ->method('getSchemaValidator')
            ->will($this->returnValue($schemaValidator));
        $commander
            ->expects($this->once())
            ->method('getEntityManager')
            ->will($this->returnValue($entityManager));
        $commander
            ->expects($this->once())
            ->method('getSchemaTool')
            ->will($this->returnValue($schemaTool));

        $this->assertSame($commander, $commander->initialize());
    }

    /**
     * Test that a cache can be returned.
     */
    public function testGettingCache()
    {
        $config = $this->createMock(Config::class);

        $commander = new Commander($config);

        $this->assertInstanceOf(Cache::class, $commander->getCache());
    }

    /**
     * Test that a mapping driver can be returned.
     */
    public function testGettingMappingDriver()
    {
        $config = $this->createMock(Config::class);

        $commander = new Commander($config);

        $this->assertInstanceOf(MappingDriver::class, $commander->getMappingDriver());
    }

    /**
     * Test that a entity manager can be returned.
     */
    public function testGettingEntityManager()
    {
        $schemaValidator = $this->createMock(SchemaValidator::class);
        $schemaValidator
            ->expects($this->once())
            ->method('schemaInSyncWithMetadata')
            ->will($this->returnValue(true));

        $config = $this->createMock(Config::class);

        $commander = $this
            ->getMockBuilder(Commander::class)
            ->setMethods(['getSchemaValidator'])
            ->setConstructorArgs([$config])
            ->getMock();
        $commander
            ->expects($this->once())
            ->method('getSchemaValidator')
            ->will($this->returnValue($schemaValidator));

        $commander->initialize();

        $this->assertInstanceOf(EntityManagerInterface::class, $commander->getEntityManager());
    }

    /**
     * Thest that an exception is thrown before initialization.
     *
     * @expectedException \LogicException
     */
    public function testGettingEntityManagerThrowsExceptionBeforeInitialization()
    {
        $config = $this->createMock(Config::class);

        $commander = new Commander($config);

        $this->assertInstanceOf(EntityManagerInterface::class, $commander->getEntityManager());
    }

    /**
     * Test that a schema tool can be returned.
     */
    public function testGettingSchemaTool()
    {
        $connection = $this->createMock(Connection::class);

        $configuration = $this->createMock(Configuration::class);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('getConnection')
            ->will($this->returnValue($connection));
        $entityManager
            ->expects($this->once())
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));

        $commander = $this
            ->getMockBuilder(Commander::class)
            ->setMethods(['getEntityManager'])
            ->disableOriginalConstructor()
            ->getMock();
        $commander
            ->expects($this->once())
            ->method('getEntityManager')
            ->will($this->returnValue($entityManager));

        $this->assertInstanceOf(SchemaTool::class, $commander->getSchemaTool());
    }

    /**
     * Test that a schema validator can be returned.
     */
    public function testGettingSchemaValidator()
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $commander = $this
            ->getMockBuilder(Commander::class)
            ->setMethods(['getEntityManager'])
            ->disableOriginalConstructor()
            ->getMock();
        $commander
            ->expects($this->once())
            ->method('getEntityManager')
            ->will($this->returnValue($entityManager));

        $this->assertInstanceOf(SchemaValidator::class, $commander->getSchemaValidator());
    }

    /**
     * Test that a logger can be returned.
     */
    public function testGettingLogger()
    {
        $config = $this->createMock(Config::class);

        $commander = new Commander($config);

        $this->assertInstanceOf(LoggerInterface::class, $commander->getLogger());
    }

    /**
     * Test that a task manager can be returned.
     */
    public function testGettingTaskManager()
    {
        $schemaValidator = $this->createMock(SchemaValidator::class);
        $schemaValidator
            ->expects($this->once())
            ->method('schemaInSyncWithMetadata')
            ->will($this->returnValue(true));

        $config = $this->createMock(Config::class);

        $commander = $this
            ->getMockBuilder(Commander::class)
            ->setMethods(['getSchemaValidator'])
            ->setConstructorArgs([$config])
            ->getMock();
        $commander
            ->expects($this->once())
            ->method('getSchemaValidator')
            ->will($this->returnValue($schemaValidator));

        $commander->initialize();

        $this->assertInstanceOf(TaskManager::class, $commander->getTaskManager());
    }
}
