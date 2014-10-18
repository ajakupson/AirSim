<?php

namespace AirSim\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PhotoRatings
 */
class PhotoRatings
{
    /**
     * @var integer
     */
    private $photoId;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var integer
     */
    private $rating;

    /**
     * @var \DateTime
     */
    private $dateRated;

    /**
     * @var integer
     */
    private $recId;

    /**
     * @var \AirSim\Bundle\CoreBundle\Entity\UserPhotos
     */
    private $photo;


    /**
     * Set userId
     *
     * @param integer $userId
     * @return PhotoRatings
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
     * Set rating
     *
     * @param integer $rating
     * @return PhotoRatings
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set dateRated
     *
     * @param \DateTime $dateRated
     * @return PhotoRatings
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
     * Set photo
     *
     * @param \AirSim\Bundle\CoreBundle\Entity\UserPhotos $photo
     * @return PhotoRatings
     */
    public function setPhoto(\AirSim\Bundle\CoreBundle\Entity\UserPhotos $photo = null)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \AirSim\Bundle\CoreBundle\Entity\UserPhotos 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param int $photoId
     */
    public function setPhotoId($photoId)
    {
        $this->photoId = $photoId;
    }

    /**
     * @return int
     */
    public function getPhotoId()
    {
        return $this->photoId;
    }
}
