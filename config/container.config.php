<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

/**
 * Import classes
 */
use GravityMedia\Commander\Config;
use GravityMedia\Commander\Console;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\ServiceManager\ServiceManager;

/**
 * Create container
 */
$container = new ServiceManager([
    'factories' => [
        Config\Loader::class => Config\LoaderFactory::class,
        Config\Serializer::class => Config\SerializerFactory::class,
        Console\Application::class => Console\ApplicationFactory::class,
        Console\Command\JoinCommand::class => InvokableFactory::class,
        Console\Command\PruneCommand::class => InvokableFactory::class,
        Console\Command\RunCommand::class => InvokableFactory::class,
        Console\Command\ShowCommand::class => InvokableFactory::class,
        Console\Helper\ConfigLoaderHelper::class => Console\Helper\ConfigLoaderHelperFactory::class
    ]
]);

/**
 * Return container
 */
return $container;
