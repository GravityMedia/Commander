<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Console\Command;

use GravityMedia\Commander\Commander;
use GravityMedia\Commander\Process\OutputCallback;
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
        $outputCallback = new OutputCallback($output, $commander->getLogger());

        while (null !== $task = $taskManager->findNextTask(['pid' => null])) {
            $process = new Process($task->getEntity()->getScript());
            $process->setTimeout($config->getCommandTimeout());
            $process->start();

            $task->updatePid($process->getPid());
            $process->wait($outputCallback);
            $task->updateExitCode($process->getExitCode());
        }
    }
}
