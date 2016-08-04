<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Gravitymedia\CommanderTest\Console\Helper;

use GravityMedia\Commander\Config\Loader;
use GravityMedia\Commander\Console\Helper\ConfigLoaderHelper;

/**
 * Config loader helper test class.
 *
 * @package GravityMedia\CommanderTest\Console\Helper
 *
 * @covers  GravityMedia\Commander\Console\Helper\ConfigLoaderHelper
 */
class ConfigLoaderHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that a config loader helper can be created.
     */
    public function testCreatingConfigLoaderHelper()
    {
        $loader = $this->createMock(Loader::class);

        $helper = new ConfigLoaderHelper($loader);

        $this->assertSame(ConfigLoaderHelper::class, $helper->getName());
        $this->assertSame($loader, $helper->getLoader());
    }
}
