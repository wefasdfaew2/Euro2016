<?php

namespace EU\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Game
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="EU\MainBundle\Entity\GameRepository")
 */
class Game implements JsonSerializable
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
     * @ORM\Column(name="score1", type="integer", nullable=true)
     */
    private $score1;

    /**
     * @var integer
     *
     * @ORM\Column(name="score2", type="integer", nullable=true)
     */
    private $score2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startTime", type="datetime")
     */
    private $startTime;

    /**
     * @var string
     *
     * @ORM\Column(name="pool", type="string", length=255, nullable=true)
     */
    private $pool;

    /**
     * @ORM\ManyToOne(targetEntity="EU\MainBundle\Entity\Team")
     * @ORM\JoinColumn(nullable=false)
     */
    private $team1;

    /**
     * @ORM\ManyToOne(targetEntity="EU\MainBundle\Entity\Team")
     * @ORM\JoinColumn(nullable=false)
     */
    private $team2;

    /**
     * @ORM\OneToMany(targetEntity="EU\MainBundle\Entity\Bet", mappedBy="game")
     */
     private $bets;


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
     * @return Game
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
     * @return Game
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
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return Game
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set team1
     *
     * @param \EU\MainBundle\Entity\Team $team1
     * @return Game
     */
    public function setTeam1(\EU\MainBundle\Entity\Team $team1)
    {
        $this->team1 = $team1;

        return $this;
    }

    /**
     * Get team1
     *
     * @return \EU\MainBundle\Entity\Team
     */
    public function getTeam1()
    {
        return $this->team1;
    }

    /**
     * Set team2
     *
     * @param \EU\MainBundle\Entity\Team $team2
     * @return Game
     */
    public function setTeam2(\EU\MainBundle\Entity\Team $team2)
    {
        $this->team2 = $team2;

        return $this;
    }

    /**
     * Get team2
     *
     * @return \EU\MainBundle\Entity\Team
     */
    public function getTeam2()
    {
        return $this->team2;
    }

    public function getStartTimeFormatted()
    {
        return date_format($this->startTime, 'M j');
    }

    public function __toString()
    {
        if($this->id === null)
        {
            return 'New game';
        }
        else
        {
            return $this->team1->getShortName().' - '.$this->team2->getShortName();
        }
    }

    public function getStats()
    {
        $bets = $this->getBets();
        $s = sizeof($bets);
        $stats = array(
                    '0' => array(1 => 0, 2 => 0),
                    '1' => array(1 => 0, 2 => 0),
                    '2' => array(1 => 0, 2 => 0),
                    '3' => array(1 => 0, 2 => 0),
                    '3+' => array(1 => 0, 2 => 0)
        );
        foreach ($bets as $b)
        {
            if($b->getScore1() < 4)
            {
                $stats[$b->getScore1()][1] += (1 / $s);
            }
            else {
                $stats['3+'][1] += (1 / $s);
            }
            if($b->getScore2() < 4)
            {
                $stats[$b->getScore2()][2] += (1 / $s);
            }
            else {
                $stats['3+'][2] += (1 / $s);
            }
        }
        /*
        foreach ($stats as $goals => $values)
        {
            foreach ($values as $team => $value)
            {
                $stats[$goals][$team] = $value/$s;
            }
        }
        */
        return $stats;
    }

    public function jsonSerialize()
    {
        return [
            'id'          => $this->id,
            'team1'       => $this->team1->jsonSerialize(),
            'team2'       => $this->team2->jsonSerialize(),
            'score1'      => $this->score1,
            'score2'      => $this->score2,
            'startTime'   => $this->startTime,
            'hasStarted'  => $this->hasStarted(),
            'pool'        => $this->pool,
            'stats'       => $this->getStats()
        ];
    }

    public function hasStarted()
    {
        $now = new \DateTime();
        return $now > $this->getStartTime();
    }

    public function hasScore()
    {
        return !is_null($this->score1) AND !is_null($this->score2);
    }

    /**
     * Set pool
     *
     * @param string $pool
     * @return Game
     */
    public function setPool($pool)
    {
        $this->pool = $pool;

        return $this;
    }

    /**
     * Get pool
     *
     * @return string
     */
    public function getPool()
    {
        return $this->pool;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add bets
     *
     * @param \EU\MainBundle\Entity\Bet $bets
     * @return Game
     */
    public function addBet(\EU\MainBundle\Entity\Bet $bet)
    {
        $this->bets[] = $bet;
        $bet->setGame($this);
        return $this;
    }

    /**
     * Remove bets
     *
     * @param \EU\MainBundle\Entity\Bet $bets
     */
    public function removeBet(\EU\MainBundle\Entity\Bet $bets)
    {
        $this->bets->removeElement($bets);
    }

    /**
     * Get bets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBets()
    {
        return $this->bets;
    }
}
