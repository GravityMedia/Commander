#!/usr/bin/env php
<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

/**
 * Set correct timezone
 */
if (!ini_get('date.timezone')) {
    ini_set('date.timezone', 'UTC');
}

/**
 * Initialize autoloader
 */
foreach (array(__DIR__ . '/../..', __DIR__ . '/../vendor') as $dir) {
    if (file_exists($dir . '/autoload.php')) {
        require $dir . '/autoload.php';
        break;
    }
}

unset($dir);

/**
 * Import classes
 */
use GravityMedia\Commander\Console;
use GravityMedia\Commander\Console\Command;
use GravityMedia\Commander\Console\Helper;
use GravityMedia\Commander\Loader;
use GravityMedia\Commander\Serializer;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\ServiceManager\ServiceManager;

/**
 * Create service manager
 */
$serviceManager = new ServiceManager([
    'factories' => [
        Console\Application::class => Console\ApplicationFactory::class,
        Command\AddCommand::class => InvokableFactory::class,
        Command\ShowCommand::class => InvokableFactory::class,
        Helper\CommanderConfigLoaderHelper::class => Helper\CommanderConfigLoaderHelperFactory::class,
        Loader\CommanderConfigLoader::class => Loader\CommanderConfigLoaderFactory::class,
        Serializer\ConfigSerializer::class => Serializer\ConfigSerializerFactory::class
    ]
]);

/**
 * Run application
 */
exit($serviceManager->get(Console\Application::class)->run());
