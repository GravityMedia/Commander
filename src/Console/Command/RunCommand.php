<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Console\Command;

use GravityMedia\Commander\Commander;
use GravityMedia\Commander\TaskManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

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
            ->setName('tasks:run')
            ->setDescription('Run tasks');
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

        foreach ($taskManager->getTasks(['pid' => null]) as $task) {
            $process = new Process($task->getEntity()->getScript());
            $process->setTimeout($config->getCommandTimeout());
            $process->start();

            $task->applyPid($process->getPid());

            $process->wait(function ($type, $buffer) use ($output) {
                if (Process::ERR === $type) {
                    $buffer = sprintf('<error>%s</error>', $buffer);
                }

                $output->writeln($buffer);
            });

            $task->applyExitCode($process->getExitCode());
        }
    }
}
