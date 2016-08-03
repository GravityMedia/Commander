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
use GravityMedia\Commander\Config;
use GravityMedia\Commander\Console;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\ServiceManager\ServiceManager;

/**
 * Create service manager
 */
$serviceManager = new ServiceManager([
    'factories' => [
        Config\Loader::class => Config\LoaderFactory::class,
        Config\Serializer::class => Config\SerializerFactory::class,
        Console\Application::class => Console\ApplicationFactory::class,
        Console\Command\NewCommand::class => InvokableFactory::class,
        Console\Command\PruneCommand::class => InvokableFactory::class,
        Console\Command\RunCommand::class => InvokableFactory::class,
        Console\Command\ShowCommand::class => InvokableFactory::class,
        Console\Helper\ConfigLoaderHelper::class => Console\Helper\ConfigLoaderHelperFactory::class,
    ]
]);

/**
 * Run application
 */
exit($serviceManager->get(Console\Application::class)->run());
