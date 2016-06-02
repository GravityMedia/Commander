<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Console;

/**
 * Application class
 *
 * @package GravityMedia\Commander\Console
 */
class Application extends \Symfony\Component\Console\Application
{
    /**
     * The name of the application
     */
    const NAME = 'Commander';

    /**
     * The version of the application
     */
    const VERSION = '@git-version@';

    /**
     * Create application object
     */
    public function __construct()
    {
        parent::__construct(static::NAME, static::VERSION);
    }
}
