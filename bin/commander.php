<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

/**
 * Check PHP version
 */
if (version_compare('5.6.0', PHP_VERSION, '>')) {
    fwrite(STDERR, 'Commander requires PHP 5.6; using the latest version of PHP is highly recommended.' . PHP_EOL);

    exit(1);
}

/**
 * Check PDO_SQLITE extension
 */
if (!extension_loaded('pdo_sqlite')) {
    fwrite(
        STDERR,
        'Commander requires the PDO SQLite driver; ' .
        'please enable the PDO_SQLITE extension in your PHP configuration file: ' . php_ini_loaded_file() . PHP_EOL
    );

    exit(1);
}

/**
 * Set correct timezone
 */
if (!ini_get('date.timezone')) {
    ini_set('date.timezone', 'UTC');
}

/**
 * Initialize loader
 */
foreach ([__DIR__ . '/../vendor/autoload.php', __DIR__ . '/../../../autoload.php'] as $file) {
    if (file_exists($file)) {
        $loader = require $file;
        break;
    }
}

/**
 * Register loader in annotation registry
 */
if (isset($loader)) {
    \Doctrine\Common\Annotations\AnnotationRegistry::registerLoader([$loader, 'loadClass']);
}

/**
 * Unset global autoload variables
 */
unset($file, $loader);

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
