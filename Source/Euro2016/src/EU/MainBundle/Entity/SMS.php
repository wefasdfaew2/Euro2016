<?php

namespace EU\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Sonata\UserBundle\Entity\User as User;
use EU\MainBundle\Entity\Game as Game;


/**
 * SMS
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="EU\MainBundle\Entity\SMSRepository")
 */
class SMS
{

    const API_KEY = 'cYDP/WqOgp2s/Coxs/cuzg==';
    const API_URL = 'http://api.smsway.eu/manage.php?';

    public function __construct(User $pUser, Game $pGame)
    {
        $this->user = $pUser;
        $this->game = $pGame;
        $this->text = 'Dear '.$this->user->getFirstname().', it looks like you forgot to register a bet on '.$this->game;
    }

    public function send()
    {
        if($this->user->getPhone() != '')
        {
            $this->sentAt = new \Datetime();
            $url = self::API_URL.'apiKey='.urlencode(self::API_KEY).'&content='.urlencode($this->text).'&num='.$this->user->getPhone().'&msgid='.$this->id;
            $response = file($url);
            return $response == 'sended';
        }
        else
        {
            return false;
        }
    }


    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sentAt", type="datetime", nullable=true)
     */
    private $sentAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="receivedAt", type="datetime", nullable=true)
     */
    private $receivedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="cost", type="string", length=255, nullable=true)
     */
    private $cost;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="EU\MainBundle\Entity\Game")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

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
     * Set text
     *
     * @param string $text
     * @return SMS
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set sent
     *
     * @param \DateTime $sent
     * @return SMS
     */
    public function setSent($sent)
    {
        $this->sent = $sent;

        return $this;
    }

    /**
     * Get sent
     *
     * @return \DateTime
     */
    public function getSent()
    {
        return $this->sent;
    }

    /**
     * Set receivedAt
     *
     * @param \DateTime $receivedAt
     * @return SMS
     */
    public function setReceivedAt($receivedAt)
    {
        $this->receivedAt = $receivedAt;

        return $this;
    }

    /**
     * Get receivedAt
     *
     * @return \DateTime
     */
    public function getReceivedAt()
    {
        return $this->receivedAt;
    }

    /**
     * Set cost
     *
     * @param string $cost
     * @return SMS
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return string
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set sentAt
     *
     * @param \DateTime $sentAt
     * @return SMS
     */
    public function setSentAt($sentAt)
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    /**
     * Get sentAt
     *
     * @return \DateTime
     */
    public function getSentAt()
    {
        return $this->sentAt;
    }

    /**
     * Set user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return SMS
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
     * Set game
     *
     * @param \EU\MainBundle\Entity\Game $game
     * @return SMS
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
     * Set status
     *
     * @param string $status
     * @return SMS
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
