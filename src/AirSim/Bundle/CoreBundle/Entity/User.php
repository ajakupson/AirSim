<?php

namespace AirSim\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 */
class User
{
    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $phoneNumber;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $gender;

    /**
     * @var \DateTime
     */
    private $birthdate;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $website;

    /**
     * @var string
     */
    private $webProfilePic;

    /**
     * @var string
     */
    private $phoneProfilePic;

    /**
     * @var string
     */
    private $profileCover;

    /**
     * @var \DateTime
     */
    private $loggedInTime;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var \AirSim\Bundle\CoreBundle\Entity\UserConfig
     */
    private $config;

    /**
     * @var Collection
     */
    private $friends;


    public function __construct()
    {
        $this->friends = new ArrayCollection();
    }


    /* ***** Getters / Setters ***** */
    /**
     * Set login
     *
     * @param string $login
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     * @return User
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string 
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set operator
     *
     * @param string $operator
     * @return User
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return string 
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     * @return User
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime 
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return User
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
     * Set website
     *
     * @param string $website
     * @return User
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set webProfilePic
     *
     * @param string $webProfilePic
     * @return User
     */
    public function setWebProfilePic($webProfilePic)
    {
        $this->webProfilePic = $webProfilePic;

        return $this;
    }

    /**
     * Get webProfilePic
     *
     * @return string 
     */
    public function getWebProfilePic()
    {
        return $this->webProfilePic;
    }

    /**
     * Set phoneProfilePic
     *
     * @param string $phoneProfilePic
     * @return User
     */
    public function setPhoneProfilePic($phoneProfilePic)
    {
        $this->phoneProfilePic = $phoneProfilePic;

        return $this;
    }

    /**
     * Get phoneProfilePic
     *
     * @return string 
     */
    public function getPhoneProfilePic()
    {
        return $this->phoneProfilePic;
    }

    /**
     * Set profileCover
     *
     * @param string $profileCover
     * @return User
     */
    public function setProfileCover($profileCover)
    {
        $this->profileCover = $profileCover;

        return $this;
    }

    /**
     * Get profileCover
     *
     * @return string 
     */
    public function getProfileCover()
    {
        return $this->profileCover;
    }

    /**
     * Set loggedInTime
     *
     * @param \DateTime $loggedInTime
     * @return User
     */
    public function setLoggedInTime($loggedInTime)
    {
        $this->loggedInTime = $loggedInTime;

        return $this;
    }

    /**
     * Get loggedInTime
     *
     * @return \DateTime 
     */
    public function getLoggedInTime()
    {
        return $this->loggedInTime;
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
     * Set config
     *
     * @param \AirSim\Bundle\CoreBundle\Entity\UserConfig $config
     * @return User
     */
    public function setConfig(\AirSim\Bundle\CoreBundle\Entity\UserConfig $config = null)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get config
     *
     * @return \AirSim\Bundle\CoreBundle\Entity\UserConfig 
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $friends
     */
    public function setFriends($friends)
    {
        $this->friends = $friends;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFriends()
    {
        return $this->friends;
    }

}
