<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Console\Command;

use Doctrine\ORM\EntityManagerInterface;
use GravityMedia\Commander\Console\Helper\EntityManagerHelper;
use GravityMedia\Commander\Entity\TaskEntity;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Add command class
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
            ->setName('add')
            ->setDescription('Add task')
            ->addArgument('commandline', InputArgument::REQUIRED, 'The commandline which represents the task');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $this->getCommanderConfig();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getHelper(EntityManagerHelper::class)->createEntityManager($config);
        $this->updateSchema($entityManager);

        $entity = new TaskEntity();
        $entity->setCommandline($input->getArgument('commandline'));

        $entityManager->persist($entity);
        $entityManager->flush();
    }
}
