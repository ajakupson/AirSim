<?php

namespace AirSim\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserWallRecords
 */
class UserWallRecords
{
    /**
     * @var integer
     */
    private $authorId;

    /**
     * @var string
     */
    private $recordText;

    /**
     * @var \DateTime
     */
    private $dateAdded;

    /**
     * @var integer
     */
    private $wallRecId;

    /**
     * @var \AirSim\Bundle\CoreBundle\Entity\User
     */
    private $to;


    /**
     * Set authorId
     *
     * @param integer $authorId
     * @return UserWallRecords
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;

        return $this;
    }

    /**
     * Get authorId
     *
     * @return integer 
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * Set recordText
     *
     * @param string $recordText
     * @return UserWallRecords
     */
    public function setRecordText($recordText)
    {
        $this->recordText = $recordText;

        return $this;
    }

    /**
     * Get recordText
     *
     * @return string 
     */
    public function getRecordText()
    {
        return $this->recordText;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return UserWallRecords
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime 
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Get wallRecId
     *
     * @return integer 
     */
    public function getWallRecId()
    {
        return $this->wallRecId;
    }

    /**
     * Set to
     *
     * @param \AirSim\Bundle\CoreBundle\Entity\User $to
     * @return UserWallRecords
     */
    public function setTo(\AirSim\Bundle\CoreBundle\Entity\User $to = null)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get to
     *
     * @return \AirSim\Bundle\CoreBundle\Entity\User 
     */
    public function getTo()
    {
        return $this->to;
    }
}
