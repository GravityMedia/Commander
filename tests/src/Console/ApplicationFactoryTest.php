<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Gravitymedia\CommanderTest\Console;

use GravityMedia\Commander\Console\Application;
use GravityMedia\Commander\Console\ApplicationFactory;

/**
 * Application factory test class.
 *
 * @package GravityMedia\CommanderTest\Console
 *
 * @covers  GravityMedia\Commander\Console\ApplicationFactory
 * @uses    GravityMedia\Commander\Config\Loader
 * @uses    GravityMedia\Commander\Config\LoaderFactory
 * @uses    GravityMedia\Commander\Config\Serializer
 * @uses    GravityMedia\Commander\Config\SerializerFactory
 * @uses    GravityMedia\Commander\Console\Application
 * @uses    GravityMedia\Commander\Console\Command\Command
 * @uses    GravityMedia\Commander\Console\Command\JoinCommand
 * @uses    GravityMedia\Commander\Console\Command\PurgeCommand
 * @uses    GravityMedia\Commander\Console\Command\RunCommand
 * @uses    GravityMedia\Commander\Console\Command\ShowCommand
 * @uses    GravityMedia\Commander\Console\Helper\ConfigLoaderHelper
 * @uses    GravityMedia\Commander\Console\Helper\ConfigLoaderHelperFactory
 */
class SerializerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that a application can be created.
     */
    public function testApplicationFactory()
    {
        $container = require __DIR__ . '/../../../config/container.config.php';

        $factory = new ApplicationFactory();

        $this->assertInstanceOf(Application::class, $factory->__invoke($container, Application::class));
    }
}
