<?php

namespace EU\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bet
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="EU\MainBundle\Entity\BetRepository")
 */
class Bet
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
     * @ORM\Column(name="score1", type="integer")
     */
    private $score1;

    /**
     * @var integer
     *
     * @ORM\Column(name="score2", type="integer")
     */
    private $score2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="EU\MainBundle\Entity\Game")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

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
     * Set score1
     *
     * @param integer $score1
     * @return Bet
     */
    public function setScore1($score1)
    {
        $this->score1 = $score1;

        return $this;
    }

    /**
     * Get score1
     *
     * @return integer
     */
    public function getScore1()
    {
        return $this->score1;
    }

    /**
     * Set score2
     *
     * @param integer $score2
     * @return Bet
     */
    public function setScore2($score2)
    {
        $this->score2 = $score2;

        return $this;
    }

    /**
     * Get score2
     *
     * @return integer
     */
    public function getScore2()
    {
        return $this->score2;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Bet
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
     * Set game
     *
     * @param \EU\MainBundle\Entity\Game $game
     * @return Bet
     */
    public function setGame(\EU\MainBundle\Entity\Game $game)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return \EU\MainBundle\Entity\Game 
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return Bet
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
     * @return Bet
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
}
