<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Provider;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\SchemaValidator;

/**
 * Schema tool provider class.
 *
 * @package GravityMedia\Commander\Provider
 */
class SchemaToolProvider
{
    /**
     * The entity manager.
     *
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * The schema tool.
     *
     * @var SchemaTool
     */
    protected $schemaTool;

    /**
     * The schema validator.
     *
     * @var SchemaValidator
     */
    protected $schemaValidator;

    /**
     * Create schema tool provider object.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Get schema tool.
     *
     * @return SchemaTool
     */
    public function getSchemaTool()
    {
        if (null === $this->schemaTool) {
            $this->schemaTool = new SchemaTool($this->entityManager);
        }

        return $this->schemaTool;
    }

    /**
     * Get schema validator.
     *
     * @return SchemaValidator
     */
    public function getSchemaValidator()
    {
        if (null === $this->schemaValidator) {
            $this->schemaValidator = new SchemaValidator($this->entityManager);
        }

        return $this->schemaValidator;
    }
}
