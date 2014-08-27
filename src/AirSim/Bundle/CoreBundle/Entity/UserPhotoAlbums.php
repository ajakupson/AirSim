<?php

namespace AirSim\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserPhotoAlbums
 */
class UserPhotoAlbums
{
    /**
     * @var string
     */
    private $albumName;

    /**
     * @var string
     */
    private $albumTitle;

    /**
     * @var string
     */
    private $albumDescription;

    /**
     * @var integer
     */
    private $albumId;

    /**
     * @var \AirSim\Bundle\CoreBundle\Entity\User
     */
    private $user;


    /**
     * Set albumName
     *
     * @param string $albumName
     * @return UserPhotoAlbums
     */
    public function setAlbumName($albumName)
    {
        $this->albumName = $albumName;

        return $this;
    }

    /**
     * Get albumName
     *
     * @return string 
     */
    public function getAlbumName()
    {
        return $this->albumName;
    }

    /**
     * Set albumTitle
     *
     * @param string $albumTitle
     * @return UserPhotoAlbums
     */
    public function setAlbumTitle($albumTitle)
    {
        $this->albumTitle = $albumTitle;

        return $this;
    }

    /**
     * Get albumTitle
     *
     * @return string 
     */
    public function getAlbumTitle()
    {
        return $this->albumTitle;
    }

    /**
     * Set albumDescription
     *
     * @param string $albumDescription
     * @return UserPhotoAlbums
     */
    public function setAlbumDescription($albumDescription)
    {
        $this->albumDescription = $albumDescription;

        return $this;
    }

    /**
     * Get albumDescription
     *
     * @return string 
     */
    public function getAlbumDescription()
    {
        return $this->albumDescription;
    }

    /**
     * Get albumId
     *
     * @return integer 
     */
    public function getAlbumId()
    {
        return $this->albumId;
    }

    /**
     * Set user
     *
     * @param \AirSim\Bundle\CoreBundle\Entity\User $user
     * @return UserPhotoAlbums
     */
    public function setUser(\AirSim\Bundle\CoreBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AirSim\Bundle\CoreBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
