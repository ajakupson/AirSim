<?php

namespace AirSim\Bundle\CoreBundle\DataTransferObjects;


class PhotoDTO
{
    /**
     * @var integer
     */
    private $userId;

    /**
     * @var integer
     */
    private $albumId;

    /**
     * @var string
     */
    private $albumName;

    /**
     * @var integer
     */
    private $photoId;

    /**
     * @var string
     */
    private $photoName;

    /**
     * @var string
     */
    private $photoTitle;

    /**
     * @var string
     */
    private $photoDescription;

    /**
     * @var string
     */
    private $photoDateAdded;

    /**
     * @var bool
     */
    private $isPhotoCover;

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
    private $address;

    /**
     * @var integer
     */
    private $previousPhotoId;

    /**
     * @var integer
     */
    private $nextPhotoId;

    /**
     * @var float
     */
    private $averageRating;

    /**
     * @var integer
     */
    private $userRated;

    /**
     * @var CommentDTO[]
     */
    private $photoComments;

    public function expose()
    {
        return get_object_vars($this);
    }


    // Getters / Setters
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
     * @param int $albumId
     */
    public function setAlbumId($albumId)
    {
        $this->albumId = $albumId;
    }

    /**
     * @return int
     */
    public function getAlbumId()
    {
        return $this->albumId;
    }

    /**
     * @param string $albumName
     */
    public function setAlbumName($albumName)
    {
        $this->albumName = $albumName;
    }

    /**
     * @return string
     */
    public function getAlbumName()
    {
        return $this->albumName;
    }

    /**
     * @param boolean $isPhotoCover
     */
    public function setIsPhotoCover($isPhotoCover)
    {
        $this->isPhotoCover = $isPhotoCover;
    }

    /**
     * @return boolean
     */
    public function getIsPhotoCover()
    {
        return $this->isPhotoCover;
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
     * @param int $nextPhotoId
     */
    public function setNextPhotoId($nextPhotoId)
    {
        $this->nextPhotoId = $nextPhotoId;
    }

    /**
     * @return int
     */
    public function getNextPhotoId()
    {
        return $this->nextPhotoId;
    }

    /**
     * @param \AirSim\Bundle\CoreBundle\DataTransferObjects\CommentDTO[] $photoComments
     */
    public function setPhotoComments($photoComments)
    {
        $this->photoComments = $photoComments;
    }

    /**
     * @return \AirSim\Bundle\CoreBundle\DataTransferObjects\CommentDTO[]
     */
    public function getPhotoComments()
    {
        return $this->photoComments;
    }

    /**
     * @param string $photoDateAdded
     */
    public function setPhotoDateAdded($photoDateAdded)
    {
        $this->photoDateAdded = $photoDateAdded;
    }

    /**
     * @return string
     */
    public function getPhotoDateAdded()
    {
        return $this->photoDateAdded;
    }

    /**
     * @param string $photoDescription
     */
    public function setPhotoDescription($photoDescription)
    {
        $this->photoDescription = $photoDescription;
    }

    /**
     * @return string
     */
    public function getPhotoDescription()
    {
        return $this->photoDescription;
    }

    /**
     * @param int $photoId
     */
    public function setPhotoId($photoId)
    {
        $this->photoId = $photoId;
    }

    /**
     * @return int
     */
    public function getPhotoId()
    {
        return $this->photoId;
    }

    /**
     * @param string $photoName
     */
    public function setPhotoName($photoName)
    {
        $this->photoName = $photoName;
    }

    /**
     * @return string
     */
    public function getPhotoName()
    {
        return $this->photoName;
    }

    /**
     * @param string $photoTitle
     */
    public function setPhotoTitle($photoTitle)
    {
        $this->photoTitle = $photoTitle;
    }

    /**
     * @return string
     */
    public function getPhotoTitle()
    {
        return $this->photoTitle;
    }

    /**
     * @param int $previousPhotoId
     */
    public function setPreviousPhotoId($previousPhotoId)
    {
        $this->previousPhotoId = $previousPhotoId;
    }

    /**
     * @return int
     */
    public function getPreviousPhotoId()
    {
        return $this->previousPhotoId;
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
     * @param mixed $averageRating
     */
    public function setAverageRating($averageRating)
    {
        $this->averageRating = $averageRating;
    }

    /**
     * @return mixed
     */
    public function getAverageRating()
    {
        return $this->averageRating;
    }

    /**
     * @param int $userRated
     */
    public function setUserRated($userRated)
    {
        $this->userRated = $userRated;
    }

    /**
     * @return int
     */
    public function getUserRated()
    {
        return $this->userRated;
    }








}