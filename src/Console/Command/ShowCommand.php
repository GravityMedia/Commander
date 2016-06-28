<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Console\Command;

use GravityMedia\Commander\Commander;
use GravityMedia\Commander\TaskManager;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Show command class.
 *
 * @package GravityMedia\Commander\Console\Command
 */
class ShowCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('tasks:show')
            ->setDescription('Show tasks');
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

        $tasks = $taskManager->getTasks();
        if (0 === count($tasks)) {
            $output->writeln('No tasks found');
            return;
        }

        $table = new Table($output);
        $table->setHeaders(['ID', 'Script', 'Priority', 'PID', 'Exit Code', 'Created At', 'Updated At']);

        foreach ($tasks as $task) {
            $table->addRow([
                $task->getEntity()->getId(),
                $task->getEntity()->getScript(),
                $task->getEntity()->getPriority(),
                $task->getEntity()->getPid(),
                $task->getEntity()->getExitCode(),
                $task->getEntity()->getCreatedAt()->format('r'),
                $task->getEntity()->getUpdatedAt()->format('r')
            ]);
        }

        $table->render();
    }
}
