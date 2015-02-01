<?php

namespace AirSim\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserMarker
 */
class UserMarker
{
    /**
     * @var integer
     */
    private $userId;

    /**
     * @var string
     */
    private $address;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var \DateTime
     */
    private $dateMarked;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var boolean
     */
    private $isActive;

    /**
     * @var integer
     */
    private $markerId;

    /**
     * @var \AirSim\Bundle\CoreBundle\Entity\User
     */
    private $user;


    /**
     * Set address
     *
     * @param string $address
     * @return UserMarker
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
     * Set latitude
     *
     * @param float $latitude
     * @return UserMarker
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
     * @return UserMarker
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
     * Set dateMarked
     *
     * @param \DateTime $dateMarked
     * @return UserMarker
     */
    public function setDateMarked($dateMarked)
    {
        $this->dateMarked = $dateMarked;

        return $this;
    }

    /**
     * Get dateMarked
     *
     * @return \DateTime 
     */
    public function getDateMarked()
    {
        return $this->dateMarked;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return UserMarker
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return UserMarker
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Get markerId
     *
     * @return integer 
     */
    public function getMarkerId()
    {
        return $this->markerId;
    }

    /**
     * Set user
     *
     * @param \AirSim\Bundle\CoreBundle\Entity\User $user
     * @return UserMarker
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

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
