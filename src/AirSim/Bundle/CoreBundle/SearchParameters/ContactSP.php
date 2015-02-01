<?php
/**
 * Created by JetBrains PhpStorm.
 * User: HP
 * Date: 24.01.15
 * Time: 17:38
 * To change this template use File | Settings | File Templates.
 */

namespace AirSim\Bundle\CoreBundle\SearchParameters;


class ContactSP {

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var string
     */
    private $nameAndOrFamily;

    /**
     * @var string
     */
    private $gender;

    /**
     * @var string
     */
    private $phoneNumber;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $city;

    /**
     * @var integer
     */
    private $ageFrom;

    /**
     * @var integer
     */
    private $ageTo;

    /**
     * @var boolean
     */
    private $isFriend;

    /** Getters / Setters **/
    /**
     * @param int $ageFrom
     */
    public function setAgeFrom($ageFrom)
    {
        $this->ageFrom = $ageFrom;
    }

    /**
     * @return int
     */
    public function getAgeFrom()
    {
        return $this->ageFrom;
    }

    /**
     * @param int $ageTo
     */
    public function setAgeTo($ageTo)
    {
        $this->ageTo = $ageTo;
    }

    /**
     * @return int
     */
    public function getAgeTo()
    {
        return $this->ageTo;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
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
     * @param string $nameAndOrFamily
     */
    public function setNameAndOrFamily($nameAndOrFamily)
    {
        $this->nameAndOrFamily = $nameAndOrFamily;
    }

    /**
     * @return string
     */
    public function getNameAndOrFamily()
    {
        return $this->nameAndOrFamily;
    }

}