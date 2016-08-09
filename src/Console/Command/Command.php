<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Console\Command;

use GravityMedia\Commander\Commander;
use GravityMedia\Commander\Config;
use GravityMedia\Commander\Console\Helper\ConfigLoaderHelper;
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
     * The commander configuration.
     *
     * @var Config
     */
    private $configuration;

    /**
     * The commander.
     *
     * @var Commander
     */
    private $commander;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->addOption(
            'configuration',
            'c',
            InputOption::VALUE_OPTIONAL,
            'The commander configuration',
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
            $this->configuration = new Config();
            return;
        }

        /** @var ConfigLoaderHelper $helper */
        $helper = $this->getHelper(ConfigLoaderHelper::class);
        $this->configuration = $helper->getLoader()->load($filename);
    }

    /**
     * Get commander configuration.
     *
     * @return Config
     *
     * @throws \LogicException
     */
    public function getConfiguration()
    {
        if (null === $this->configuration) {
            throw new \LogicException('The commander configuration is not available before initialization');
        }

        return $this->configuration;
    }

    /**
     * Get operational commander.
     *
     * @return Commander
     *
     * @throws \LogicException
     */
    public function getCommander()
    {
        if (null === $this->commander) {
            $this->commander = new Commander($this->getConfiguration());
        }

        return $this->commander;
    }
}
