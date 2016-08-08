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
foreach ([__DIR__ . '/../vendor/autoload.php', __DIR__ . '/../../../autoload.php'] as $file) {
    if (file_exists($file)) {
        require $file;
        break;
    }
}

/**
 * Unset global autoload file variable
 */
unset($file);

/**
 * Initialize container
 *
 * @var \Interop\Container\ContainerInterface $container
 */
$container = require __DIR__ . '/../config/container.config.php';

/**
 * Run application
 */
exit($container->get(\GravityMedia\Commander\Console\Application::class)->run());
