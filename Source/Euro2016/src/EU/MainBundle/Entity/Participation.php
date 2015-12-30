<?php

namespace EU\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="EU\MainBundle\Entity\ParticipationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Participation
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
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="paidAt", type="datetime", nullable=true)
     */
    private $paidAt;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="EU\MainBundle\Entity\Pot")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pot;


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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Participation
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
        return $this->createdAt;
    }

    /**
     * Set paidAt
     *
     * @param \DateTime $paidAt
     * @return Participation
     */
    public function setPaidAt($paidAt)
    {
        $this->paidAt = $paidAt;

        return $this;
    }

    /**
     * Get paidAt
     *
     * @return \DateTime
     */
    public function getPaidAt()
    {
        return $this->paidAt;
    }

    /**
     * Set user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return Participation
     */
    public function setUser(\Application\Sonata\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set pot
     *
     * @param \EU\MainBundle\Entity\Pot $pot
     * @return Participation
     */
    public function setPot(\EU\MainBundle\Entity\Pot $pot)
    {
        $this->pot = $pot;

        return $this;
    }

    /**
     * Get pot
     *
     * @return \EU\MainBundle\Entity\Pot
     */
    public function getPot()
    {
        return $this->pot;
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
