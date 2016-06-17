<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Console\Command;

use GravityMedia\Commander\Config\CommanderConfig;
use GravityMedia\Commander\Console\Helper\CommanderConfigLoaderHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command class.
 *
 * @package GravityMedia\Commander\Console\Command
 */
class Command extends \Symfony\Component\Console\Command\Command
{
    /**
     * The commander config.
     *
     * @var CommanderConfig
     */
    private $commanderConfig;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->addOption(
            'configuration',
            'c',
            InputOption::VALUE_OPTIONAL,
            'The commander config',
            'commander.json'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getOption('configuration');
        if (!file_exists($filename)) {
            $this->commanderConfig = new CommanderConfig();
            return;
        }

        /** @var CommanderConfigLoaderHelper $helper */
        $helper = $this->getHelper(CommanderConfigLoaderHelper::class);
        $loader = $helper->getCommanderConfigLoader();
        $this->commanderConfig = $loader->load($filename);
    }

    /**
     * Get commander config.
     *
     * @return CommanderConfig
     *
     * @throws \LogicException
     */
    public function getCommanderConfig()
    {
        if (null === $this->commanderConfig) {
            throw new \LogicException('The commander config is available after initialization');
        }

        return $this->commanderConfig;
    }
}
