<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Gravitymedia\CommanderTest\Config;

use GravityMedia\Commander\Config\Loader;
use GravityMedia\Commander\Config\LoaderFactory;
use GravityMedia\Commander\Config\Serializer;

/**
 * Loader factory test class.
 *
 * @package GravityMedia\CommanderTest\Config
 *
 * @covers  GravityMedia\Commander\Config\LoaderFactory
 * @uses    GravityMedia\Commander\Config\Loader
 * @uses    GravityMedia\Commander\Config\SerializerFactory
 * @uses    GravityMedia\Commander\Config\Serializer
 */
class LoaderFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that a loader can be created.
     */
    public function testLoaderFactory()
    {
        $container = require __DIR__ . '/../../../config/container.config.php';

        $factory = new LoaderFactory();

        $this->assertInstanceOf(Loader::class, $factory->__invoke($container, Serializer::class));
    }
}
