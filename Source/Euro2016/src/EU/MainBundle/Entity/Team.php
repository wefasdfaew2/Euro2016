<?php

namespace EU\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Team
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="EU\MainBundle\Entity\TeamRepository")
 */
class Team implements JsonSerializable
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
     * @var string
     *
     * @ORM\Column(name="longName", type="string", length=255)
     */
    private $longName;

    /**
     * @var string
     *
     * @ORM\Column(name="shortName", type="string", length=3)
     */
    private $shortName;


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
     * Set longName
     *
     * @param string $longName
     * @return Team
     */
    public function setLongName($longName)
    {
        $this->longName = $longName;

        return $this;
    }

    /**
     * Get longName
     *
     * @return string
     */
    public function getLongName()
    {
        return $this->longName;
    }

    /**
     * Set shortName
     *
     * @param string $shortName
     * @return Team
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * Get shortName
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    public function jsonSerialize()
    {
        return [
            'id'          => $this->id,
            'longName'    => $this->longName,
            'shortName'   => $this->shortName
        ];
    }
}
