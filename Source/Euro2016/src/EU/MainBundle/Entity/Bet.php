<?php

namespace EU\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Bet
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="EU\MainBundle\Entity\BetRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Bet implements JsonSerializable
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
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

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

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Bet
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

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
     * Update updatedAt to current time
     *
     * @ORM\PreUpdate
     */
     public function updateUpdatedAt()
     {
         $this->setUpdatedAt(new \Datetime());
     }

     /**
      * Set createdAt to current time
      *
      * @ORM\PrePersist
      */
      public function updateCreatedAt()
      {
          $this->setCreatedAt(new \Datetime());
          $this->updateUpdatedAt();
      }

      public function getBetScores()
      {
          return $this->score1.' - '.$this->score2;
      }

      public function updateBet(\EU\MainBundle\Entity\Bet $bet)
      {
          $this->score1 = $bet->getScore1();
          $this->score2 = $bet->getScore2();
      }

      public function getPoints()
      {
          if(!$this->getGame()->hasScore())
          {
              return 0;
          }
          else
          {
              if($this->getScore1() == $this->getGame()->getScore1() AND $this->getScore2() == $this->getGame()->getScore2())
              {
                  return 4;
              }
              elseif (($this->getScore1() - $this->getScore2()) == ($this->getGame()->getScore1() - $this->getGame()->getScore2()))
              {
                  return 2;
              }
              elseif (min(1, max(-1, $this->getScore1() - $this->getScore2())) == min(1, max(-1, $this->getGame()->getScore1() - $this->getGame()->getScore2())))
              {
                  return 1;
              }
              else
              {
                  return 0;
              }
          }
      }

      public function __toString()
      {
          return '('.$this->getBetScores().') on '.$this->game;
      }

      public function jsonSerialize()
      {
          return [
              'id'          => $this->id,
              'user'        => $this->user->jsonSerialize(),
              'game'        => $this->game->jsonSerialize(),
              'pot'         => $this->pot->jsonSerialize(),
              'score1'      => $this->score1,
              'score2'      => $this->score2,
              'createdAt'   => $this->createdAt,
              'updatedAt'   => $this->updatedAt,
              'points'      => $this->getPoints()
          ];
      }
}
