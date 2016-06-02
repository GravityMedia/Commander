#!/usr/bin/env php
<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

/**
 * Set correct timezone
 */
if (!ini_get('date.timezone')) {
    ini_set('date.timezone', 'UTC');
}

/**
 * Initialize autoloader
 */
foreach (array(__DIR__ . '/../..', __DIR__ . '/../vendor') as $dir) {
    if (file_exists($dir . '/autoload.php')) {
        require $dir . '/autoload.php';
        break;
    }
}

unset($dir);

/**
 * Import classes
 */
use GravityMedia\Commander\Console\Application;

/**
 * Run application
 */
$application = new Application();
exit($application->run());
