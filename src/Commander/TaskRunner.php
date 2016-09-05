<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Commander;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * Task runner class.
 *
 * @package GravityMedia\Commander\Commander
 */
class TaskRunner
{
    /**
     * The task manager.
     *
     * @var TaskManager
     */
    protected $taskManager;

    /**
     * The output.
     *
     * @var OutputInterface
     */
    protected $output;

    /**
     * The logger.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Create task runner object.
     *
     * @param TaskManager     $taskManager
     * @param OutputInterface $output
     * @param LoggerInterface $logger
     */
    public function __construct(TaskManager $taskManager, OutputInterface $output, LoggerInterface $logger)
    {
        $this->taskManager = $taskManager;
        $this->output = $output;
        $this->logger = $logger;
    }

    /**
     * Gets called when the object is used as a callback.
     *
     * @param string $type
     * @param string $buffer
     *
     * @return void
     */
    public function __invoke($type, $buffer)
    {
        $this->logMessage($type, $buffer);
        $this->renderOutput($type, $buffer);
    }

    /**
     * Log message.
     *
     * @param string $type
     * @param string $message
     *
     * @return void
     */
    protected function logMessage($type, $message)
    {
        $logLevel = Logger::INFO;
        if (Process::ERR === $type) {
            $logLevel = Logger::ERROR;
        }

        $this->logger->log($logLevel, $message);
    }

    /**
     * Render output.
     *
     * @param string $type
     * @param string $message
     *
     * @return void
     */
    protected function renderOutput($type, $message)
    {
        if ($this->output->isQuiet()) {
            return;
        }

        if (Process::ERR === $type) {
            $message = sprintf('<error>%s</error>', $message);
        }

        $this->output->writeln($message);
    }

    /**
     * Run all tasks from the task manager.
     *
     * @param int $timeout
     *
     * @return void
     */
    public function runAll($timeout)
    {
        while (null !== $task = $this->taskManager->findNextTask()) {
            $this->run($task, $timeout);
        }
    }

    /**
     * Run single task.
     *
     * @param Task $task
     * @param int  $timeout
     *
     * @return void
     */
    public function run($task, $timeout)
    {
        $process = new Process($task->getEntity()->getCommandline());
        $process->setTimeout($timeout);
        $process->start($this);

        $task->defer($process->getPid(), function () use ($process) {
            $process->wait($this);

            return $process->getExitCode();
        });
    }
}
