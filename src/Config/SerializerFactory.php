<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Config;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Config serializer factory class.
 *
 * @package GravityMedia\Commander\Serializer
 */
class SerializerFactory implements FactoryInterface
{
    /**
     * Create config serializer object.
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     *
     * @return Serializer
     *
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $normalizers = [new GetSetMethodNormalizer()];
        $encoders = [new JsonEncoder()];

        return new Serializer($normalizers, $encoders);
    }
}
