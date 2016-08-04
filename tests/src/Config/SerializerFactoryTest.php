<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Gravitymedia\CommanderTest\Config;

use GravityMedia\Commander\Config\Serializer;
use GravityMedia\Commander\Config\SerializerFactory;

/**
 * Serializer factory test class.
 *
 * @package GravityMedia\CommanderTest\Config
 *
 * @covers  GravityMedia\Commander\Config\SerializerFactory
 * @uses    GravityMedia\Commander\Config\Serializer
 */
class SerializerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that a serializer can be created.
     */
    public function testSerializerFactory()
    {
        $container = require __DIR__ . '/../../../config/container.config.php';

        $factory = new SerializerFactory();

        $this->assertInstanceOf(Serializer::class, $factory->__invoke($container, Serializer::class));
    }
}
