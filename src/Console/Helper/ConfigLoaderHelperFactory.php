<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Console\Helper;

use GravityMedia\Commander\Config\Loader;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Config loader helper factory class.
 *
 * @package GravityMedia\Commander\Serializer
 */
class ConfigLoaderHelperFactory implements FactoryInterface
{
    /**
     * Create config loader helper object.
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     *
     * @return ConfigLoaderHelper
     *
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when creating a service.
     * @throws ContainerException if any other error occurs.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var Loader $commanderConfigLoader */
        $commanderConfigLoader = $container->get(Loader::class);

        return new ConfigLoaderHelper($commanderConfigLoader);
    }
}
