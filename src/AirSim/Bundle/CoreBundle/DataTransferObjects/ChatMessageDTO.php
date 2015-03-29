<?php
/**
 * Created by Andrei Jakupson
 * Date: 24.03.15
 * Time: 1:29
 */

namespace AirSim\Bundle\CoreBundle\DataTransferObjects;


class ChatMessageDTO
{
    /**
     * @var integer
     */
    private $messageId;

    /**
     * @var integer
     */
    private $authorId;

    /**
     * @var string
     */
    private $messageText;

    /**
     * @var string
     */
    private $messageDateTimeSent;

    /**
     * @var boolean
     */
    private $isRead;

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
     * @param boolean $isRead
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;
    }

    /**
     * @return boolean
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * @param string $messageDateTimeSent
     */
    public function setMessageDateTimeSent($messageDateTimeSent)
    {
        $this->messageDateTimeSent = $messageDateTimeSent;
    }

    /**
     * @return string
     */
    public function getMessageDateTimeSent()
    {
        return $this->messageDateTimeSent;
    }

    /**
     * @param int $messageId
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
    }

    /**
     * @return int
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * @param string $messageText
     */
    public function setMessageText($messageText)
    {
        $this->messageText = $messageText;
    }

    /**
     * @return string
     */
    public function getMessageText()
    {
        return $this->messageText;
    }
}