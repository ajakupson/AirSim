<?php

namespace AirSim\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserBlockedContacts
 */
class UserBlockedContacts
{
    /**
     * @var integer
     */
    private $userId;

    /**
     * @var integer
     */
    private $blockedUserId;

    /**
     * @var \DateTime
     */
    private $dateBlocked;

    /**
     * @var integer
     */
    private $recId;

    /**
     * @var \AirSim\Bundle\CoreBundle\Entity\User
     */
    private $user;


    /**
     * Set blockedUserId
     *
     * @param integer $blockedUserId
     * @return UserBlockedContacts
     */
    public function setBlockedUserId($blockedUserId)
    {
        $this->blockedUserId = $blockedUserId;

        return $this;
    }

    /**
     * Get blockedUserId
     *
     * @return integer 
     */
    public function getBlockedUserId()
    {
        return $this->blockedUserId;
    }

    /**
     * Set dateBlocked
     *
     * @param \DateTime $dateBlocked
     * @return UserBlockedContacts
     */
    public function setDateBlocked($dateBlocked)
    {
        $this->dateBlocked = $dateBlocked;

        return $this;
    }

    /**
     * Get dateBlocked
     *
     * @return \DateTime 
     */
    public function getDateBlocked()
    {
        return $this->dateBlocked;
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
     * @return UserBlockedContacts
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
