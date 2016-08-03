<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Console;

use GravityMedia\Commander\Console\Command;
use GravityMedia\Commander\Console\Helper;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Application factory class.
 *
 * @package GravityMedia\Commander\Console
 */
class ApplicationFactory implements FactoryInterface
{
    /**
     * Create application object.
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     *
     * @return Application
     *
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when creating a service.
     * @throws ContainerException if any other error occurs.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $application = new Application();
        $application->add($container->get(Command\NewCommand::class));
        $application->add($container->get(Command\PruneCommand::class));
        $application->add($container->get(Command\RunCommand::class));
        $application->add($container->get(Command\ShowCommand::class));

        $helperSet = $application->getHelperSet();
        $helperSet->set($container->get(Helper\ConfigLoaderHelper::class));

        return $application;
    }
}
