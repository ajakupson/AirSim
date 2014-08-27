<?php

namespace AirSim\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PhotoComments
 */
class PhotoComments
{
    /**
     * @var integer
     */
    private $userId;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var \DateTime
     */
    private $dateAdded;

    /**
     * @var integer
     */
    private $commentId;

    /**
     * @var \AirSim\Bundle\CoreBundle\Entity\UserPhotos
     */
    private $photo;


    /**
     * Set userId
     *
     * @param integer $userId
     * @return PhotoComments
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
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
     * Set comment
     *
     * @param string $comment
     * @return PhotoComments
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
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return PhotoComments
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
     * Get commentId
     *
     * @return integer 
     */
    public function getCommentId()
    {
        return $this->commentId;
    }

    /**
     * Set photo
     *
     * @param \AirSim\Bundle\CoreBundle\Entity\UserPhotos $photo
     * @return PhotoComments
     */
    public function setPhoto(\AirSim\Bundle\CoreBundle\Entity\UserPhotos $photo = null)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \AirSim\Bundle\CoreBundle\Entity\UserPhotos 
     */
    public function getPhoto()
    {
        return $this->photo;
    }
}
