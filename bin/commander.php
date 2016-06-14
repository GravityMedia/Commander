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
use GravityMedia\Commander\Console\Application;
use GravityMedia\Commander\Console\ApplicationFactory;
use GravityMedia\Commander\Console\Command\AddCommand;
use GravityMedia\Commander\Console\Helper\ConfigSerializerHelper;
use GravityMedia\Commander\Console\Helper\EntityManagerHelper;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\ServiceManager\ServiceManager;

/**
 * Create service manager
 */
$serviceManager = new ServiceManager([
    'factories' => [
        Application::class => ApplicationFactory::class,
        AddCommand::class => InvokableFactory::class,
        ConfigSerializerHelper::class => InvokableFactory::class,
        EntityManagerHelper::class => InvokableFactory::class
    ]
]);

/**
 * Run application
 */
exit($serviceManager->get(Application::class)->run());
