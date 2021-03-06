<?php

namespace EU\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Participation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="EU\MainBundle\Entity\ParticipationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Participation implements JsonSerializable
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
     * @ORM\Column(name="acceptedAt", type="datetime", nullable=true)
     */
    private $acceptedAt;

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

     public function isPaid()
     {
         return $this->paidAt !== null;
     }

     public function jsonSerialize()
     {
         return [
             'id'           => $this->id,
             'createdAt'    => $this->createdAt,
             'acceptedAt'   => $this->acceptedAt,
             'paidAt'       => $this->paidAt,
             'user'         => $this->user->jsonSerialize(),
             'pot'          => $this->pot->jsonSerialize()
         ];
     }

    /**
     * Set acceptedAt
     *
     * @param \DateTime $acceptedAt
     * @return Participation
     */
    public function setAcceptedAt($acceptedAt)
    {
        $this->acceptedAt = $acceptedAt;

        return $this;
    }

    /**
     * Get acceptedAt
     *
     * @return \DateTime
     */
    public function getAcceptedAt()
    {
        return $this->acceptedAt;
    }
}
