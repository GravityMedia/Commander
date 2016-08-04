<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Config;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Config loader factory class.
 *
 * @package GravityMedia\Commander\Config
 */
class LoaderFactory implements FactoryInterface
{
    /**
     * Create config loader object.
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     *
     * @return Loader
     *
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when creating a service.
     * @throws ContainerException if any other error occurs.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var Serializer $serializer */
        $serializer = $container->get(Serializer::class);

        return new Loader($serializer);
    }
}
