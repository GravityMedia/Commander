<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Process;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * Output callback class
 *
 * @package GravityMedia\Commander\Process
 */
class OutputCallback
{
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
     * Create output callback object.
     *
     * @param OutputInterface $output
     * @param LoggerInterface $logger
     */
    public function __construct(OutputInterface $output, LoggerInterface $logger)
    {
        $this->output = $output;
        $this->logger = $logger;
    }

    /**
     * Gets called when the object is used as a callback.
     *
     * @param string $type
     * @param string $buffer
     */
    public function __invoke($type, $buffer)
    {
        $logLevel = Logger::INFO;
        if (Process::ERR === $type) {
            $logLevel = Logger::ERROR;
        }

        $this->logger->log($logLevel, $buffer);

        if (OutputInterface::VERBOSITY_QUIET === $this->output->getVerbosity()) {
            return;
        }

        if (Process::ERR === $type) {
            $buffer = sprintf('<error>%s</error>', $buffer);
        }
        $this->output->writeln($buffer);
    }
}
