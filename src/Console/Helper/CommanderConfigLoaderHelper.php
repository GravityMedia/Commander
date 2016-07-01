<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Console\Helper;

use GravityMedia\Commander\Config\Loader\CommanderConfigLoader;
use Symfony\Component\Console\Helper\Helper;

/**
 * Commander config loader helper class.
 *
 * @package GravityMedia\Commander\Console\Helper
 */
class CommanderConfigLoaderHelper extends Helper
{
    /**
     * The commander config loader.
     *
     * @var CommanderConfigLoader
     */
    protected $commanderConfigLoader;

    /**
     * Create commander config loader helper object.
     *
     * @param CommanderConfigLoader $commanderConfigLoader
     */
    public function __construct(CommanderConfigLoader $commanderConfigLoader)
    {
        $this->commanderConfigLoader = $commanderConfigLoader;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return static::class;
    }

    /**
     * Get commander config loader.
     *
     * @return CommanderConfigLoader
     */
    public function getCommanderConfigLoader()
    {
        return $this->commanderConfigLoader;
    }
}
