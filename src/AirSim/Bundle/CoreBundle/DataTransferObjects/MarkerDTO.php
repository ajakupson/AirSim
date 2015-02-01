<?php
namespace AirSim\Bundle\CoreBundle\DataTransferObjects;

class MarkerDTO
{
    /**
     * @var string
     */
    private $address;

    /**
     * @var double
     */
    private $latitude;

    /**
     * @var double
     */
    private $longitude;

    /**
     * @var string
     */
    private $dateAdded;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var UserWebDataDTO[]
     */
    private $userWebData;

    public function expose()
    {
        return get_object_vars($this);
    }

    // Getters / setters
    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $dateAdded
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * @return string
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param \AirSim\Bundle\CoreBundle\DataTransferObjects\UserWebDataDTO[] $userWebData
     */
    public function setUserWebData($userWebData)
    {
        $this->userWebData = $userWebData;
    }

    /**
     * @return \AirSim\Bundle\CoreBundle\DataTransferObjects\UserWebDataDTO[]
     */
    public function getUserWebData()
    {
        return $this->userWebData;
    }
}