<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Gravitymedia\CommanderTest\Provider;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\SchemaValidator;
use GravityMedia\Commander\Provider\SchemaToolProvider;

/**
 * Schema tool provider test class.
 *
 * @package GravityMedia\CommanderTest\Provider
 *
 * @covers  GravityMedia\Commander\Provider\SchemaToolProvider
 */
class SchemaToolProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that a schema tool can be created.
     */
    public function testSchemaToolCreation()
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

        $provider = new SchemaToolProvider($entityManager);

        $this->assertInstanceOf(SchemaTool::class, $provider->getSchemaTool());
    }

    /**
     * Test that a schema validator can be created.
     */
    public function testSchemaValidatorCreation()
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $provider = new SchemaToolProvider($entityManager);

        $this->assertInstanceOf(SchemaValidator::class, $provider->getSchemaValidator());
    }
}
