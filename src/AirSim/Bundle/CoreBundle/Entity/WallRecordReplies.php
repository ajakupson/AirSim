<?php

namespace AirSim\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WallRecordReplies
 */
class WallRecordReplies
{
    /**
     * @var integer
     */
    private $wallRecordId;

    /**
     * @var integer
     */
    private $parentReplyId;

    /**
     * @var integer
     */
    private $authorId;

    /**
     * @var string
     */
    private $replyText;

    /**
     * @var \DateTime
     */
    private $dateAdded;

    /**
     * @var integer
     */
    private $replyId;

    /**
     * @var \AirSim\Bundle\CoreBundle\Entity\UserWallRecords
     */
    private $wallRecord;


    /**
     * Set parentReplyId
     *
     * @param integer $parentReplyId
     * @return WallRecordReplies
     */
    public function setParentReplyId($parentReplyId)
    {
        $this->parentReplyId = $parentReplyId;

        return $this;
    }

    /**
     * Get parentReplyId
     *
     * @return integer 
     */
    public function getParentReplyId()
    {
        return $this->parentReplyId;
    }

    /**
     * Set authorId
     *
     * @param integer $authorId
     * @return WallRecordReplies
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;

        return $this;
    }

    /**
     * Get authorId
     *
     * @return integer 
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * Set replyText
     *
     * @param string $replyText
     * @return WallRecordReplies
     */
    public function setReplyText($replyText)
    {
        $this->replyText = $replyText;

        return $this;
    }

    /**
     * Get replyText
     *
     * @return string 
     */
    public function getReplyText()
    {
        return $this->replyText;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return WallRecordReplies
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
     * Get replyId
     *
     * @return integer 
     */
    public function getReplyId()
    {
        return $this->replyId;
    }

    /**
     * Set wallRecord
     *
     * @param \AirSim\Bundle\CoreBundle\Entity\UserWallRecords $wallRecord
     * @return WallRecordReplies
     */
    public function setWallRecord(\AirSim\Bundle\CoreBundle\Entity\UserWallRecords $wallRecord = null)
    {
        $this->wallRecord = $wallRecord;

        return $this;
    }

    /**
     * Get wallRecord
     *
     * @return \AirSim\Bundle\CoreBundle\Entity\UserWallRecords 
     */
    public function getWallRecord()
    {
        return $this->wallRecord;
    }

    /**
     * @param int $wallRecordId
     */
    public function setWallRecordId($wallRecordId)
    {
        $this->wallRecordId = $wallRecordId;
    }

    /**
     * @return int
     */
    public function getWallRecordId()
    {
        return $this->wallRecordId;
    }
}
