<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

/**
 * Initialize loader
 */
$loader = require __DIR__ . '/../vendor/autoload.php';

/**
 * Register loader in annotation registry
 */
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader([$loader, 'loadClass']);

/**
 * Unset global variables
 */
unset($loader);
