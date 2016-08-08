<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Gravitymedia\CommanderTest\Provider;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use GravityMedia\Commander\Provider\MappingDriverProvider;

/**
 * Mapping driver provider test class.
 *
 * @package GravityMedia\CommanderTest\Provider
 *
 * @covers  GravityMedia\Commander\Provider\MappingDriverProvider
 */
class MappingDriverProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that a mapping driver can be created.
     */
    public function testMappingDriverCreation()
    {
        $cache = $this->createMock(Cache::class);

        $provider = new MappingDriverProvider($cache);

        $this->assertInstanceOf(MappingDriver::class, $provider->getMappingDriver());
    }
}
