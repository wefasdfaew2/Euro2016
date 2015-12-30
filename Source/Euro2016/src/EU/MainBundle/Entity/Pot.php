<?php

namespace EU\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pot
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="EU\MainBundle\Entity\PotRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Pot
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $manager;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return Pot
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Pot
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createAt;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Pot
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set manager
     *
     * @param \Application\Sonata\UserBundle\Entity\User $manager
     * @return Pot
     */
    public function setManager(\Application\Sonata\UserBundle\Entity\User $manager)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get manager
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Set createdAt to current time
     *
     * @ORM\PrePersist
     */
     public function updateCreatedAt()
     {
         $this->setCreatedAt(new \Datetime());
     }
}
