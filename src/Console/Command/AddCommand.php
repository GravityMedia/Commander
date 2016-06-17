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
 * Add command class.
 *
 * @package GravityMedia\Commander\Console\Command
 */
class AddCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('task:add')
            ->setDescription('Add task')
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

        if (false === filter_var($input->getOption('priority'), FILTER_VALIDATE_INT)) {
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
        $task = $taskManager->getTask(['script' => $script]);

        if (null === $task) {
            $taskManager->addTask($script);
            return;
        }

        // ToDo: Update task
    }
}
