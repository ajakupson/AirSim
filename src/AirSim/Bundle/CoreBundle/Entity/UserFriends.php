<?php

namespace AirSim\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserFriends
 */
class UserFriends
{
    /**
     * @var \DateTime
     */
    private $dateAdded;

    /**
     * @var boolean
     */
    private $isAccepted;

    /**
     * @var \DateTime
     */
    private $dateAccepted;

    /**
     * @var integer
     */
    private $groupId;

    /**
     * @var integer
     */
    private $recId;

    /**
     * @var \AirSim\Bundle\CoreBundle\Entity\User
     */
    private $user;

    /**
     * @var \AirSim\Bundle\CoreBundle\Entity\User
     */
    private $friend;


    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return UserFriends
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
     * Set isAccepted
     *
     * @param boolean $isAccepted
     * @return UserFriends
     */
    public function setIsAccepted($isAccepted)
    {
        $this->isAccepted = $isAccepted;

        return $this;
    }

    /**
     * Get isAccepted
     *
     * @return boolean 
     */
    public function getIsAccepted()
    {
        return $this->isAccepted;
    }

    /**
     * Set dateAccepted
     *
     * @param \DateTime $dateAccepted
     * @return UserFriends
     */
    public function setDateAccepted($dateAccepted)
    {
        $this->dateAccepted = $dateAccepted;

        return $this;
    }

    /**
     * Get dateAccepted
     *
     * @return \DateTime 
     */
    public function getDateAccepted()
    {
        return $this->dateAccepted;
    }

    /**
     * Set groupId
     *
     * @param integer $groupId
     * @return UserFriends
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * Get groupId
     *
     * @return integer 
     */
    public function getGroupId()
    {
        return $this->groupId;
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
     * Set user
     *
     * @param \AirSim\Bundle\CoreBundle\Entity\User $user
     * @return UserFriends
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
     * Set friend
     *
     * @param \AirSim\Bundle\CoreBundle\Entity\User $friend
     * @return UserFriends
     */
    public function setFriend(\AirSim\Bundle\CoreBundle\Entity\User $friend = null)
    {
        $this->friend = $friend;

        return $this;
    }

    /**
     * Get friend
     *
     * @return \AirSim\Bundle\CoreBundle\Entity\User 
     */
    public function getFriend()
    {
        return $this->friend;
    }
}
