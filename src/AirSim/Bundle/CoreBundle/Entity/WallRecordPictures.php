<?php

namespace AirSim\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WallRecordPictures
 */
class WallRecordPictures
{
    /**
     * @var integer
     */
    private $recId;

    /**
     * @var \AirSim\Bundle\CoreBundle\Entity\UserWallRecords
     */
    private $wallRec;

    /**
     * @var integer
     */
    private $wallRecId;

    /**
     * @var \AirSim\Bundle\CoreBundle\Entity\UserPhotos
     */
    private $picture;

    /**
     * @var integer
     */
    private $pictureId;


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
     * @return WallRecordPictures
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

    /**
     * Set picture
     *
     * @param \AirSim\Bundle\CoreBundle\Entity\UserPhotos $picture
     * @return WallRecordPictures
     */
    public function setPicture(\AirSim\Bundle\CoreBundle\Entity\UserPhotos $picture = null)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return \AirSim\Bundle\CoreBundle\Entity\UserPhotos 
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param int $pictureId
     */
    public function setPictureId($pictureId)
    {
        $this->pictureId = $pictureId;
    }

    /**
     * @return int
     */
    public function getPictureId()
    {
        return $this->pictureId;
    }

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




}
