<?php

namespace AirSim\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserConfig
 */
class UserConfig
{
    /**
     * @var integer
     */
    private $privPhoneVisibility;

    /**
     * @var integer
     */
    private $privSearchByPhone;

    /**
     * @var integer
     */
    private $privAddInfoVisibility;

    /**
     * @var integer
     */
    private $privFriendsVisibility;

    /**
     * @var integer
     */
    private $privWhoAllowedWrite;

    /**
     * @var integer
     */
    private $syncAutoSync;

    /**
     * @var integer
     */
    private $configId;


    /**
     * Set privPhoneVisibility
     *
     * @param integer $privPhoneVisibility
     * @return UserConfig
     */
    public function setPrivPhoneVisibility($privPhoneVisibility)
    {
        $this->privPhoneVisibility = $privPhoneVisibility;

        return $this;
    }

    /**
     * Get privPhoneVisibility
     *
     * @return integer 
     */
    public function getPrivPhoneVisibility()
    {
        return $this->privPhoneVisibility;
    }

    /**
     * Set privSearchByPhone
     *
     * @param integer $privSearchByPhone
     * @return UserConfig
     */
    public function setPrivSearchByPhone($privSearchByPhone)
    {
        $this->privSearchByPhone = $privSearchByPhone;

        return $this;
    }

    /**
     * Get privSearchByPhone
     *
     * @return integer 
     */
    public function getPrivSearchByPhone()
    {
        return $this->privSearchByPhone;
    }

    /**
     * Set privAddInfoVisibility
     *
     * @param integer $privAddInfoVisibility
     * @return UserConfig
     */
    public function setPrivAddInfoVisibility($privAddInfoVisibility)
    {
        $this->privAddInfoVisibility = $privAddInfoVisibility;

        return $this;
    }

    /**
     * Get privAddInfoVisibility
     *
     * @return integer 
     */
    public function getPrivAddInfoVisibility()
    {
        return $this->privAddInfoVisibility;
    }

    /**
     * Set privFriendsVisibility
     *
     * @param integer $privFriendsVisibility
     * @return UserConfig
     */
    public function setPrivFriendsVisibility($privFriendsVisibility)
    {
        $this->privFriendsVisibility = $privFriendsVisibility;

        return $this;
    }

    /**
     * Get privFriendsVisibility
     *
     * @return integer 
     */
    public function getPrivFriendsVisibility()
    {
        return $this->privFriendsVisibility;
    }

    /**
     * Set privWhoAllowedWrite
     *
     * @param integer $privWhoAllowedWrite
     * @return UserConfig
     */
    public function setPrivWhoAllowedWrite($privWhoAllowedWrite)
    {
        $this->privWhoAllowedWrite = $privWhoAllowedWrite;

        return $this;
    }

    /**
     * Get privWhoAllowedWrite
     *
     * @return integer 
     */
    public function getPrivWhoAllowedWrite()
    {
        return $this->privWhoAllowedWrite;
    }

    /**
     * Set syncAutoSync
     *
     * @param integer $syncAutoSync
     * @return UserConfig
     */
    public function setSyncAutoSync($syncAutoSync)
    {
        $this->syncAutoSync = $syncAutoSync;

        return $this;
    }

    /**
     * Get syncAutoSync
     *
     * @return integer 
     */
    public function getSyncAutoSync()
    {
        return $this->syncAutoSync;
    }

    /**
     * Get configId
     *
     * @return integer 
     */
    public function getConfigId()
    {
        return $this->configId;
    }
}
