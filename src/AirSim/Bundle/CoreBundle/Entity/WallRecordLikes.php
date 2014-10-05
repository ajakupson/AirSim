<?php

namespace AirSim\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WallRecordLikes
 */
class WallRecordLikes
{
    /**
     * @var integer
     */
    private $wallRecId;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var boolean
     */
    private $likeDislike;

    /**
     * @var \DateTime
     */
    private $dateRated;

    /**
     * @var integer
     */
    private $recId;

    /**
     * @var \AirSim\Bundle\CoreBundle\Entity\UserWallRecords
     */
    private $wallRec;


    /**
     * @param int $wallRecId
     */
    public function setWallRecId($wallRecId)
    {
        $this->wallRecId = $wallRecId;
    }

    /**
     * @return int
     */
    public function getWallRecId()
    {
        return $this->wallRecId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return WallRecordLikes
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set likeDislike
     *
     * @param boolean $likeDislike
     * @return WallRecordLikes
     */
    public function setLikeDislike($likeDislike)
    {
        $this->likeDislike = $likeDislike;

        return $this;
    }

    /**
     * Get likeDislike
     *
     * @return boolean 
     */
    public function getLikeDislike()
    {
        return $this->likeDislike;
    }

    /**
     * Set dateRated
     *
     * @param \DateTime $dateRated
     * @return WallRecordLikes
     */
    public function setDateRated($dateRated)
    {
        $this->dateRated = $dateRated;

        return $this;
    }

    /**
     * Get dateRated
     *
     * @return \DateTime 
     */
    public function getDateRated()
    {
        return $this->dateRated;
    }

    /**
     * Get recId
     *
     * @return integer 
     */
    public function getRecId()
    {
        return $this->recId;
    }

    /**
     * Set wallRec
     *
     * @param \AirSim\Bundle\CoreBundle\Entity\UserWallRecords $wallRec
     * @return WallRecordLikes
     */
    public function setWallRec(\AirSim\Bundle\CoreBundle\Entity\UserWallRecords $wallRec = null)
    {
        $this->wallRec = $wallRec;

        return $this;
    }

    /**
     * Get wallRec
     *
     * @return \AirSim\Bundle\CoreBundle\Entity\UserWallRecords 
     */
    public function getWallRec()
    {
        return $this->wallRec;
    }
}
