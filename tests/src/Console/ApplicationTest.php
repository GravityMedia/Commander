<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Gravitymedia\CommanderTest\Console;

use GravityMedia\Commander\Console\Application;

/**
 * Application test class.
 *
 * @package GravityMedia\CommanderTest\Console
 *
 * @covers GravityMedia\Commander\Console\Application
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the application has the correct name and version.
     */
    public function testApplicationNameAndVersion()
    {
        $application = new Application();

        $this->assertSame(Application::NAME, $application->getName());
        $this->assertSame(Application::VERSION, $application->getVersion());
    }
}
