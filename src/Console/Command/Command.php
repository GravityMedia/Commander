<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Console\Command;

use GravityMedia\Commander\Config\CommanderConfig;
use GravityMedia\Commander\Console\Helper\ConfigSerializerHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Command class
 *
 * @package GravityMedia\Commander\Console\Command
 */
class Command extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var CommanderConfig
     */
    private $commanderConfig;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->addOption('configuration', 'c', InputOption::VALUE_OPTIONAL, 'commander.json');
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getOption('configuration');
        if (file_exists($filename)) {
            /** @var Serializer $serializer */
            $serializer = $this->getHelper(ConfigSerializerHelper::class)->createSerializer();
            $this->commanderConfig = $serializer->deserialize(
                file_get_contents($filename),
                CommanderConfig::class,
                'json'
            );
            return;
        }

        $this->commanderConfig = new CommanderConfig();
    }

    /**
     * Get commander config
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
