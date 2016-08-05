<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Prune command class.
 *
 * @package GravityMedia\Commander\Console\Command
 */
class PruneCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('prune')
            ->setDescription('Prune finished tasks');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $taskManager = $this->getCommander()->getTaskManager();

        $tasks = $taskManager->findAllTerminatedTasks();

        if (0 === count($tasks)) {
            $output->writeln('No tasks found');
            return;
        }

        foreach ($tasks as $task) {
            $task->remove();
        }

        $output->writeln(sprintf('Removed %s terminated task(s)', count($tasks)));
    }
}
