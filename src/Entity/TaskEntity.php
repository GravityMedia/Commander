<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Target entity class
 *
 * @package GravityMedia\Commander\Doctrine\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="task")
 **/
class TaskEntity
{
    /**
     * The ID
     *
     * @var string
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     **/
    private $id;

    /**
     * The commandline
     *
     * @var string
     *
     * @ORM\Column(type="string")
     **/
    private $commandline;

    /**
     * The PID
     *
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     **/
    private $pid;

    /**
     * The created at timestamp
     *
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Get ID
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get commandline
     *
     * @return string
     */
    public function getCommandline()
    {
        return $this->commandline;
    }

    /**
     * Set commandline
     *
     * @param string $commandline
     *
     * @return $this
     */
    public function setCommandline($commandline)
    {
        $this->commandline = $commandline;
        return $this;
    }

    /**
     * Get PID
     *
     * @return int
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * Set PID
     *
     * @param int $pid
     *
     * @return $this
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
        return $this;
    }

    /**
     * Get created at timestamp
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set created at timestamp
     *
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
