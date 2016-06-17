<?php
/**
 * This file is part of the Commander project.
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Commander\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Target entity class.
 *
 * @package GravityMedia\Commander\Doctrine\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="task")
 **/
class TaskEntity
{
    /**
     * The default priority.
     */
    const DEFAULT_PRIORITY = 1;

    /**
     * The ID.
     *
     * @var string
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     **/
    private $id;

    /**
     * The script.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     **/
    private $script;

    /**
     * The priority.
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $priority;

    /**
     * The PID.
     *
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     **/
    private $pid;

    /**
     * The exit code.
     *
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $exitCode;

    /**
     * The timestamp when the task was created.
     *
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * The timestamp when the task was updated.
     *
     * @var \DateTime $updatedAt
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * Create task entity.
     */
    public function __construct()
    {
        $this->priority = static::DEFAULT_PRIORITY;
    }

    /**
     * Get ID.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get script.
     *
     * @return string
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * Set script.
     *
     * @param string $script
     *
     * @return $this
     */
    public function setScript($script)
    {
        $this->script = $script;
        return $this;
    }

    /**
     * Get priority.
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set priority.
     *
     * @param int $priority
     *
     * @return $this
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * Get PID.
     *
     * @return int
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * Set PID.
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
     * Get exit code.
     *
     * @return int
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }

    /**
     * Set exit code.
     *
     * @param int $exitCode
     *
     * @return $this
     */
    public function setExitCode($exitCode)
    {
        $this->exitCode = $exitCode;
        return $this;
    }

    /**
     * Get timestamp when the task was created.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set timestamp when the task was created.
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

    /**
     * Get timestamp when the task was updated.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set timestamp when the task was updated.
     *
     * @param \DateTime $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
