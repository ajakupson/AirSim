<?php

namespace AirSim\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserPhotos
 */
class UserPhotos
{
    /**
     * @var string
     */
    private $photoName;

    /**
     * @var string
     */
    private $photoTitle;

    /**
     * @var string
     */
    private $photoDescription;

    /**
     * @var \DateTime
     */
    private $dateAdded;

    /**
     * @var boolean
     */
    private $isCover;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var string
     */
    private $address;

    /**
     * @var integer
     */
    private $photoId;

    /**
     * @var \AirSim\Bundle\CoreBundle\Entity\UserPhotoAlbums
     */
    private $album;


    /**
     * Set photoName
     *
     * @param string $photoName
     * @return UserPhotos
     */
    public function setPhotoName($photoName)
    {
        $this->photoName = $photoName;

        return $this;
    }

    /**
     * Get photoName
     *
     * @return string 
     */
    public function getPhotoName()
    {
        return $this->photoName;
    }

    /**
     * Set photoTitle
     *
     * @param string $photoTitle
     * @return UserPhotos
     */
    public function setPhotoTitle($photoTitle)
    {
        $this->photoTitle = $photoTitle;

        return $this;
    }

    /**
     * Get photoTitle
     *
     * @return string 
     */
    public function getPhotoTitle()
    {
        return $this->photoTitle;
    }

    /**
     * Set photoDescription
     *
     * @param string $photoDescription
     * @return UserPhotos
     */
    public function setPhotoDescription($photoDescription)
    {
        $this->photoDescription = $photoDescription;

        return $this;
    }

    /**
     * Get photoDescription
     *
     * @return string 
     */
    public function getPhotoDescription()
    {
        return $this->photoDescription;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return UserPhotos
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
     * Set isCover
     *
     * @param boolean $isCover
     * @return UserPhotos
     */
    public function setIsCover($isCover)
    {
        $this->isCover = $isCover;

        return $this;
    }

    /**
     * Get isCover
     *
     * @return boolean 
     */
    public function getIsCover()
    {
        return $this->isCover;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return UserPhotos
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return UserPhotos
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return UserPhotos
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Get photoId
     *
     * @return integer 
     */
    public function getPhotoId()
    {
        return $this->photoId;
    }

    /**
     * Set album
     *
     * @param \AirSim\Bundle\CoreBundle\Entity\UserPhotoAlbums $album
     * @return UserPhotos
     */
    public function setAlbum(\AirSim\Bundle\CoreBundle\Entity\UserPhotoAlbums $album = null)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * Get album
     *
     * @return \AirSim\Bundle\CoreBundle\Entity\UserPhotoAlbums 
     */
    public function getAlbum()
    {
        return $this->album;
    }
}
