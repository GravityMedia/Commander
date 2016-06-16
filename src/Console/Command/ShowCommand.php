<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Console\Command;

use Doctrine\ORM\EntityManagerInterface;
use GravityMedia\Commander\Console\Helper\EntityManagerHelper;
use GravityMedia\Commander\TaskManager;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Show command class
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

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getHelper(EntityManagerHelper::class)->createEntityManager($config);
        $taskManager = new TaskManager($entityManager);
        $taskManager->updateSchema();

        $table = new Table($output);
        $table->setHeaders(['ID', 'Commandline', 'PID', 'Created At']);

        foreach ($taskManager->getTasks() as $task) {
            $table->addRow([
                $task->getId(),
                $task->getCommandline(),
                $task->getPid(),
                $task->getCreatedAt()->format('r')
            ]);
        }

        $table->render();
    }
}
