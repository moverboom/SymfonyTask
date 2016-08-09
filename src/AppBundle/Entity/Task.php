<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @ORM\Table(name="tasks")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaskRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Task
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string")
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="completed", type="boolean")
     */
    private $completed = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deadline", type="datetime")
     */
    private $deadline;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param $title string
     */
    public function setTitle($title) {
        if(is_string($title)) {
            $this->title = $title;
        }
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Task
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get completed
     *
     * @return bool
     */
    public function isCompleted() {
        return $this->completed;
    }

    /**
     * Set completed
     *
     * @param boolean
     *
     * @return Task
     */
    public function setCompleted($completed) {
        $this->completed = $completed;

        return $this;
    }

    /**
     * Set deadline
     *
     * @param \DateTime $deadline
     *
     * @return Task
     */
    public function setDeadline(\DateTime $deadline)
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Get deadline
     *
     * @return \DateTime
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * Returns true if Task is expired
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->getCurrentDateTime()->diff($this->deadline)->d < 0;
        //return $this->getCurrentDateTime();
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt Creation datetime
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt Update datetime
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAtOnPersist()
    {
        $this->createdAt = $this->getCurrentDateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function setUpdatedAtOnUpdate()
    {
        $this->updatedAt = $this->getCurrentDateTime();
    }

    public function getCurrentDateTime() {
        //return DateTime::createFromFormat('Y-m-d H:i:s', strval(time()));
        return new DateTime();
    }
}
