<?php

namespace TaskBundle\Entity;

use DateTime;
use AuthBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @ORM\Table(name="tasks")
 * @ORM\Entity(repositoryClass="TaskBundle\Repository\TaskRepository")
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
     * @ORM\Column(name="remindAt", type="datetime", nullable=true)
     */
    private $remindAt = null;

    /**
     * @var boolean
     *
     * @ORM\Column(name="reminded", type="boolean")
     */
    private $reminded = false;

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
     * @ORM\ManyToOne(targetEntity="\AuthBundle\Entity\User", inversedBy="tasks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @var \AuthBundle\Entity\User
     */
    private $user;


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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param $title string
     * @return $this
     */
    public function setTitle($title)
    {
        if (is_string($title)) {
            $this->title = $title;
        }

        return $this;
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
     * Is completed
     *
     * @return bool
     */
    public function isCompleted()
    {
        return $this->completed;
    }

    /**
     * Set completed
     *
     * @param boolean
     *
     * @return Task
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * Get remindAt
     *
     * @return DateTime
     */
    public function getRemindAt()
    {
        return $this->remindAt;
    }

    /**
     * Set remindAt
     *
     * @param DateTime $remindAt
     * @return $this
     */
    public function setRemindAt(DateTime $remindAt = null)
    {
        $this->remindAt = $remindAt;

        return $this;
    }

    /**
     * Is reminded
     *
     * @return bool
     */
    public function isReminded()
    {
        return $this->reminded;
    }

    public function setReminded($reminded) {
        $this->reminded = $reminded;
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
        return $this->getCurrentDateTime()->getTimestamp() > $this->deadline->getTimestamp();
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
    public function setTimestampsOnPersist()
    {
        $this->createdAt = $this->getCurrentDateTime();
        $this->updatedAt = $this->getCurrentDateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function setUpdatedAtOnUpdate()
    {
        $this->updatedAt = $this->getCurrentDateTime();
    }

    private function getCurrentDateTime()
    {
        return new DateTime();
    }

    /**
     * Get completed
     *
     * @return boolean
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Task
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return user
     */
    public function getUser()
    {
        return $this->user;
    }
}
