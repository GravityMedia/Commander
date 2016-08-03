<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Config;

use GravityMedia\Commander\Config;

/**
 * Config loader class.
 *
 * @package GravityMedia\Commander\Config
 */
class Loader
{
    /**
     * The config serializer.
     *
     * @var Serializer
     */
    protected $configSerializer;

    /**
     * Create config loader object.
     *
     * @param Serializer $configSerializer
     */
    public function __construct(Serializer $configSerializer)
    {
        $this->configSerializer = $configSerializer;
    }

    /**
     * Load config.
     *
     * @param string $filename
     *
     * @return Config
     */
    public function load($filename)
    {
        $data = file_get_contents($filename);

        return $this->configSerializer->deserialize($data, Config::class, 'json');
    }
}
