<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Config;

use GravityMedia\Commander\Config;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

/**
 * Config loader class.
 *
 * @package GravityMedia\Commander\Config
 */
class Loader
{
    /**
     * The serializer.
     *
     * @var Serializer
     */
    protected $serializer;

    /**
     * Create config loader object.
     *
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Load config.
     *
     * @param string $filename
     *
     * @return Config
     *
     * @throws \InvalidArgumentException
     */
    public function load($filename)
    {
        $data = file_get_contents($filename);

        try {
            $config = $this->serializer->deserialize($data, Config::class, 'json');
        } catch (UnexpectedValueException $exception) {
            throw new \InvalidArgumentException('Invalid configuration file', 0, $exception);
        }

        return $config;
    }
}
