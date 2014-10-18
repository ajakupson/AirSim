<?php

namespace AirSim\Bundle\CoreBundle\DataTransferObjects;


class WallRecordReplyDTO
{
    /**
     * @var integer
     */
    private $replyId;

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
     * @var string
     */
    private $dateAdded;

    public function expose()
    {
        return get_object_vars($this);
    }


    // Getters / setters
    /**
     * @param int $authorId
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;
    }

    /**
     * @return int
     */
    public function getAuthorId()
    {
        return $this->authorId;
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
     * @param int $parentReplyId
     */
    public function setParentReplyId($parentReplyId)
    {
        $this->parentReplyId = $parentReplyId;
    }

    /**
     * @return int
     */
    public function getParentReplyId()
    {
        return $this->parentReplyId;
    }

    /**
     * @param int $replyId
     */
    public function setReplyId($replyId)
    {
        $this->replyId = $replyId;
    }

    /**
     * @return int
     */
    public function getReplyId()
    {
        return $this->replyId;
    }

    /**
     * @param string $replyText
     */
    public function setReplyText($replyText)
    {
        $this->replyText = $replyText;
    }

    /**
     * @return string
     */
    public function getReplyText()
    {
        return $this->replyText;
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