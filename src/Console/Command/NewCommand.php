<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Console\Command;

use GravityMedia\Commander\Commander;
use GravityMedia\Commander\Entity\TaskEntity;
use GravityMedia\Commander\TaskManager;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * New command class.
 *
 * @package GravityMedia\Commander\Console\Command
 */
class NewCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('task:new')
            ->setDescription('New task')
            ->addArgument(
                'script',
                InputArgument::REQUIRED,
                'The script to run with the task'
            )
            ->addOption(
                'priority',
                null,
                InputOption::VALUE_REQUIRED,
                'The task priority',
                TaskEntity::DEFAULT_PRIORITY
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        if (null === $this->filterPriority($input)) {
            throw new RuntimeException('Value of option "priority" must be an integer');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $this->getCommanderConfig();
        $commander = new Commander($config);
        if (!$commander->isSchemaValid()) {
            $commander->updateSchema();
        }

        $entityManager = $commander->getEntityManager();
        $taskManager = new TaskManager($entityManager);

        $script = $input->getArgument('script');
        $task = $taskManager->findTask(['script' => $script, 'pid' => null]);

        $priority = $this->filterPriority($input);
        if (null === $task) {
            $taskManager->newTask($script, $priority);
            return;
        }

        if ($priority !== $task->getEntity()->getPriority()) {
            $task->updatePriority($priority);
        }
    }

    /**
     * Filter priority value.
     *
     * @param InputInterface $input
     *
     * @return null|int
     */
    protected function filterPriority(InputInterface $input)
    {
        $priority = filter_var($input->getOption('priority'), FILTER_VALIDATE_INT);
        if (false === $priority) {
            return null;
        }

        return $priority;
    }
}
