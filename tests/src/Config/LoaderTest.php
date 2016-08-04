<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Gravitymedia\CommanderTest\Config;

use GravityMedia\Commander\Config;
use GravityMedia\Commander\Config\Loader;
use GravityMedia\Commander\Config\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

/**
 * Loader test class.
 *
 * @package GravityMedia\CommanderTest\Config
 *
 * @covers  GravityMedia\Commander\Config\Loader
 * @uses    GravityMedia\Commander\Config
 */
class LoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that a valid config can be loaded.
     */
    public function testLoadingValidConfig()
    {
        $normalizers = [new GetSetMethodNormalizer()];

        $encoders = [new JsonEncoder()];

        $serializer = new Serializer($normalizers, $encoders);

        $loader = new Loader($serializer);

        $this->assertInstanceOf(Config::class, $loader->load(__DIR__ . '/../../resources/commander.json'));
    }

    /**
     * Test that loading an invalid config throws an exception.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testLoadingInvalidConfigThrowsException()
    {
        $normalizers = [new GetSetMethodNormalizer()];

        $encoders = [new JsonEncoder()];

        $serializer = new Serializer($normalizers, $encoders);

        $loader = new Loader($serializer);

        $loader->load(__DIR__ . '/../../resources/invalid-commander.json');
    }
}
