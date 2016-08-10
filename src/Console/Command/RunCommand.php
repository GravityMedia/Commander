<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Console\Command;

use GravityMedia\Commander\Commander\TaskRunner;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Run command class.
 *
 * @package GravityMedia\Commander\Console\Command
 */
class RunCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('run')
            ->setDescription('Run all joined tasks');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commander = $this->getCommander()->initialize();

        $taskManager = $commander->getTaskManager();
        $logger = $commander->getLogger();

        $taskRunner = new TaskRunner($taskManager, $output, $logger);
        $taskRunner->runAll($this->getConfiguration()->getProcessTimeout());
    }
}
