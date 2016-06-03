<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Console\Command;

use Doctrine\ORM\Tools\SchemaTool;
use GravityMedia\Commander\Console\Helper\EntityManagerHelper;
use GravityMedia\Commander\Entity\TaskEntity;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Append command class
 *
 * @package GravityMedia\Commander\Console\Command
 */
class AppendCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('append')
            ->setDescription('Append task')
            ->addArgument('commandline', InputArgument::REQUIRED, 'The commandline to append with the task');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entity = new TaskEntity();
        $entity->setCommandline($input->getArgument('commandline'));

        $config = $this->getCommanderConfig();

        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $this->getHelper(EntityManagerHelper::class)->createEntityManager($config);
        $classes = $entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool = new SchemaTool($entityManager);
        $sql = $schemaTool->getUpdateSchemaSql($classes);

        $output->writeln($sql);

        //$entityManager->persist($entity);
        //$entityManager->flush();
    }
}
