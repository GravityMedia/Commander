<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Config\Loader;

use GravityMedia\Commander\Config\CommanderConfig;
use GravityMedia\Commander\Serializer\ConfigSerializer;

/**
 * Commander config loader class.
 *
 * @package GravityMedia\Commander\Loader
 */
class CommanderConfigLoader
{
    /**
     * The config serializer.
     *
     * @var ConfigSerializer
     */
    protected $configSerializer;

    /**
     * Create commander config loader.
     *
     * @param ConfigSerializer $configSerializer
     */
    public function __construct(ConfigSerializer $configSerializer)
    {
        $this->configSerializer = $configSerializer;
    }

    /**
     * Load commander config.
     *
     * @param string $filename
     *
     * @return CommanderConfig
     */
    public function load($filename)
    {
        return $this->configSerializer->deserialize(
            file_get_contents($filename),
            CommanderConfig::class,
            'json'
        );
    }
}
