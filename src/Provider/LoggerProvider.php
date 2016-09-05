<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Provider;

use GravityMedia\Commander\Commander;
use GravityMedia\Commander\Config;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * Logger provider class.
 *
 * @package GravityMedia\Commander\Provider
 */
class LoggerProvider
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * The handlers.
     *
     * @var HandlerInterface[]
     */
    protected $handlers;

    /**
     * The logger.
     *
     * @var null|LoggerInterface
     */
    protected $logger;

    /**
     * Create commander object.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Get handlers.
     *
     * @return HandlerInterface[]
     */
    public function getHandlers()
    {
        if (null === $this->handlers) {
            $handlers = [];

            $path = $this->config->getLogFilePath();
            if (null !== $path) {
                $handlers[] = new StreamHandler($path);
            }

            $this->handlers = $handlers;
        }

        return $this->handlers;
    }

    /**
     * Get logger.
     *
     * @return null|LoggerInterface
     */
    public function getLogger()
    {
        if (null === $this->logger) {
            $logger = new Logger(Commander::LOGGER_NAME);

            foreach ($this->getHandlers() as $handler) {
                $logger->pushHandler($handler);
            }

            if (0 === count($logger->getHandlers())) {
                return null;
            }

            $this->logger = $logger;
        }

        return $this->logger;
    }
}
