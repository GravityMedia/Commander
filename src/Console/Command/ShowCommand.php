<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Console\Command;

use GravityMedia\Commander\Commander\Task;
use Monolog\Formatter\NormalizerFormatter;
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
            ->setDescription('Show all tasks');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tasks = $this->getCommander()->getTaskManager()->findAllTasks();

        if (0 === count($tasks)) {
            $output->writeln('No tasks found');
            return;
        }

        $this->renderTable($tasks, $output);
    }

    /**
     * Render table.
     *
     * @param Task[]          $tasks
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function renderTable(array $tasks, OutputInterface $output)
    {
        $table = new Table($output);
        $table->setHeaders(['ID', 'Priority', 'Commandline', 'PID', 'Exit Code', 'Created At', 'Updated At']);

        foreach ($tasks as $task) {
            $table->addRow([
                $task->getEntity()->getId(),
                $task->getEntity()->getPriority(),
                $task->getEntity()->getCommandline(),
                $task->getEntity()->getPid(),
                $task->getEntity()->getExitCode(),
                $task->getEntity()->getCreatedAt()->format(NormalizerFormatter::SIMPLE_DATE),
                $task->getEntity()->getUpdatedAt()->format(NormalizerFormatter::SIMPLE_DATE)
            ]);
        }

        $table->render();
    }
}
