<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Process entity class
 *
 * @package GravityMedia\Commander\Doctrine\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="process")
 **/
class ProcessEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     **/
    protected $id;

    /**
     * @ORM\Column(type="string")
     **/
    protected $commandline;

    /**
     * @ORM\Column(type="integer", nullable=true)
     **/
    protected $pid;

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
     * @param string $pid
     *
     * @return $this
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
    }
}
