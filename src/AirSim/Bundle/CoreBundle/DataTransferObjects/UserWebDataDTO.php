<?php

namespace AirSim\Bundle\CoreBundle\DataTransferObjects;

class UserWebDataDTO
{
    /**
     * @var integer
     */
    private $userId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $family;

    /**
     * @var string
     */
    private $webProfilePic;

    /**
     * @var string
     */
    private $phoneNumber;

    /**
     * @var string
     */
    private $gender;

    // Make object fields visible
    public function expose()
    {
        return get_object_vars($this);
    }

    // Getters / setters
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
     * @param string $family
     */
    public function setFamily($family)
    {
        $this->family = $family;
    }

    /**
     * @return string
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * @param string $webProfilePic
     */
    public function setWebProfilePic($webProfilePic)
    {
        $this->webProfilePic = $webProfilePic;
    }

    /**
     * @return string
     */
    public function getWebProfilePic()
    {
        return $this->webProfilePic;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

}