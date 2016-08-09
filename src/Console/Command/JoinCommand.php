<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Join command class.
 *
 * @package GravityMedia\Commander\Console\Command
 */
class JoinCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('join')
            ->setDescription('Create new task or update existing task')
            ->addArgument(
                'commandline',
                InputArgument::REQUIRED,
                'The commandline to run with the task'
            )
            ->addOption(
                'priority',
                null,
                InputOption::VALUE_REQUIRED,
                'The task priority'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commander = $this->getCommander()->initialize();
        $taskManager = $commander->getTaskManager();

        $commandline = $input->getArgument('commandline');
        $task = $taskManager->findNextTask(['commandline' => $commandline]);

        $priority = filter_var($input->getOption('priority'), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

        if (null === $task) {
            $task = $taskManager->newTask($commandline, $priority);
            $output->writeln(sprintf('Created new task %s', $task->getEntity()->getId()));
            return;
        }

        if (null !== $priority && $priority !== $task->getEntity()->getPriority()) {
            $task->prioritize($priority);
            $output->writeln(sprintf('Updated the priority of task %s', $task->getEntity()->getId()));
            return;
        }

        $output->writeln(sprintf('Task %s already present, ignoring', $task->getEntity()->getId()));
    }
}
