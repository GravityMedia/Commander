<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Gravitymedia\CommanderTest\Console\Helper;

use GravityMedia\Commander\Console\Helper\ConfigLoaderHelper;
use GravityMedia\Commander\Console\Helper\ConfigLoaderHelperFactory;

/**
 * Config loader helper factory test class.
 *
 * @package GravityMedia\CommanderTest\Console\Helper
 *
 * @covers  GravityMedia\Commander\Console\Helper\ConfigLoaderHelperFactory
 * @uses    GravityMedia\Commander\Console\Helper\ConfigLoaderHelper
 * @uses    GravityMedia\Commander\Config\Loader
 * @uses    GravityMedia\Commander\Config\LoaderFactory
 * @uses    GravityMedia\Commander\Config\Serializer
 * @uses    GravityMedia\Commander\Config\SerializerFactory
 */
class ConfigLoaderHelperFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that a config loader helper can be created.
     */
    public function testConfigLoaderHelperFactory()
    {
        $container = require __DIR__ . '/../../../../config/container.config.php';

        $factory = new ConfigLoaderHelperFactory();

        $this->assertInstanceOf(ConfigLoaderHelper::class, $factory->__invoke($container, ConfigLoaderHelper::class));
    }
}
