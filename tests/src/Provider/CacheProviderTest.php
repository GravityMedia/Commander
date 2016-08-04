<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Gravitymedia\CommanderTest\Provider;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\FilesystemCache;
use GravityMedia\Commander\Config;
use GravityMedia\Commander\Provider\CacheProvider;

/**
 * Cache provider test class.
 *
 * @package GravityMedia\CommanderTest\Provider
 *
 * @covers  GravityMedia\Commander\Provider\CacheProvider
 */
class CacheProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that an array cache can be created.
     *
     * @dataProvider provideCacheDirectories()
     *
     * @param string $directory
     * @param string $class
     */
    public function testArrayCacheProvider($directory, $class)
    {
        $config = $this->createMock(Config::class);
        $config
            ->expects($this->once())
            ->method('getCacheDirectory')
            ->will($this->returnValue($directory));

        $provider = new CacheProvider($config);

        $this->assertInstanceOf($class, $provider->getCache());
    }

    /**
     * Provide cache directories and expected caches.
     *
     * @return array
     */
    public function provideCacheDirectories()
    {
        return [
            [null, ArrayCache::class],
            [sys_get_temp_dir(), FilesystemCache::class]
        ];
    }
}
