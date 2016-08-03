<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Console\Helper;

use GravityMedia\Commander\Config\Loader;
use Symfony\Component\Console\Helper\Helper;

/**
 * Config loader helper class.
 *
 * @package GravityMedia\Commander\Console\Helper
 */
class ConfigLoaderHelper extends Helper
{
    /**
     * The loader.
     *
     * @var Loader
     */
    protected $loader;

    /**
     * Create config loader helper object.
     *
     * @param Loader $loader
     */
    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return static::class;
    }

    /**
     * Get loader.
     *
     * @return Loader
     */
    public function getLoader()
    {
        return $this->loader;
    }
}
