<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Provider;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\FilesystemCache;
use GravityMedia\Commander\Config;

/**
 * Cache provider class.
 *
 * @package GravityMedia\Commander\Provider
 */
class CacheProvider
{
    /**
     * The config.
     *
     * @var Config
     */
    protected $config;

    /**
     * The cache.
     *
     * @var Cache
     */
    protected $cache;

    /**
     * Create cache provider object.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Get cache.
     *
     * @return Cache
     */
    public function getCache()
    {
        if (null === $this->cache) {
            $directory = $this->config->getCacheDirectory();
            if (null === $directory) {
                $this->cache = new ArrayCache();

                return $this->cache;
            }

            $this->cache = new FilesystemCache($directory);
        }

        return $this->cache;
    }
}
