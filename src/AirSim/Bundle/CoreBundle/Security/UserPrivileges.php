<?php
/**
 * Created by Andrei Jakupson
 * Date: 3.02.15
 * Time: 0:55
 */

namespace AirSim\Bundle\CoreBundle\Security;


class UserPrivileges
{
    /**
     * @var bool
     */
    private $isLoggedIn = false;

    /**
     * @var bool
     */
    private $isBlocked = true;

    /**
     * @var bool
     */
    private $isFriend = false;

    /**
     * @var bool
     */
    private $seeProfilePrivilege = false;

    /**
     * @var bool
     */
    private $seePhotosPrivilege = false;

    /**
     * @var bool
     */
    private $seeAdditionalInfoPrivilege = false;

    /**
     * @var bool
     */
    private $seeContactsPrivilege = false;

    /**
     * @var bool
     */
    private $addContactPrivilege = false;

    /**
     * @var bool
     */
    private $sendMessagePrivilege = false;

    // Additional flags

    /**
     * @var integer
     */
    private $userId = null;

    /**
     * @var integer
     */
    private $contactId = null;

    /**
     * @var bool
     */
    private $isInFavorites = false;

    // Getters / setters
    /**
     * @param boolean $addContactPrivilege
     */
    public function setAddContactPrivilege($addContactPrivilege)
    {
        $this->addContactPrivilege = $addContactPrivilege;
    }

    /**
     * @return boolean
     */
    public function getAddContactPrivilege()
    {
        return $this->addContactPrivilege;
    }

    /**
     * @param boolean $seeAdditionalInfoPrivilege
     */
    public function setSeeAdditionalInfoPrivilege($seeAdditionalInfoPrivilege)
    {
        $this->seeAdditionalInfoPrivilege = $seeAdditionalInfoPrivilege;
    }

    /**
     * @return boolean
     */
    public function getSeeAdditionalInfoPrivilege()
    {
        return $this->seeAdditionalInfoPrivilege;
    }

    /**
     * @param boolean $seeContactsPrivilege
     */
    public function setSeeContactsPrivilege($seeContactsPrivilege)
    {
        $this->seeContactsPrivilege = $seeContactsPrivilege;
    }

    /**
     * @return boolean
     */
    public function getSeeContactsPrivilege()
    {
        return $this->seeContactsPrivilege;
    }

    /**
     * @param boolean $seePhotosPrivilege
     */
    public function setSeePhotosPrivilege($seePhotosPrivilege)
    {
        $this->seePhotosPrivilege = $seePhotosPrivilege;
    }

    /**
     * @return boolean
     */
    public function getSeePhotosPrivilege()
    {
        return $this->seePhotosPrivilege;
    }

    /**
     * @param boolean $seeProfilePrivilege
     */
    public function setSeeProfilePrivilege($seeProfilePrivilege)
    {
        $this->seeProfilePrivilege = $seeProfilePrivilege;
    }

    /**
     * @return boolean
     */
    public function getSeeProfilePrivilege()
    {
        return $this->seeProfilePrivilege;
    }

    /**
     * @param boolean $sendMessagePrivilege
     */
    public function setSendMessagePrivilege($sendMessagePrivilege)
    {
        $this->sendMessagePrivilege = $sendMessagePrivilege;
    }

    /**
     * @return boolean
     */
    public function getSendMessagePrivilege()
    {
        return $this->sendMessagePrivilege;
    }

    /**
     * @param boolean $isBlocked
     */
    public function setIsBlocked($isBlocked)
    {
        $this->isBlocked = $isBlocked;
    }

    /**
     * @return boolean
     */
    public function getIsBlocked()
    {
        return $this->isBlocked;
    }

    /**
     * @param boolean $isLoggedIn
     */
    public function setIsLoggedIn($isLoggedIn)
    {
        $this->isLoggedIn = $isLoggedIn;
    }

    /**
     * @return boolean
     */
    public function getIsLoggedIn()
    {
        return $this->isLoggedIn;
    }

    /**
     * @param boolean $isFriend
     */
    public function setIsFriend($isFriend)
    {
        $this->isFriend = $isFriend;
    }

    /**
     * @return boolean
     */
    public function getIsFriend()
    {
        return $this->isFriend;
    }

    /**
     * @param boolean $isInFavorites
     */
    public function setIsInFavorites($isInFavorites)
    {
        $this->isInFavorites = $isInFavorites;
    }

    /**
     * @return boolean
     */
    public function getIsInFavorites()
    {
        return $this->isInFavorites;
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

    /**
     * @param int $contactId
     */
    public function setContactId($contactId)
    {
        $this->contactId = $contactId;
    }

    /**
     * @return int
     */
    public function getContactId()
    {
        return $this->contactId;
    }
}