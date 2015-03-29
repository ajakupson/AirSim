<?php

namespace AirSim\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChatMessages
 */
class ChatMessages
{
    /**
     * @var string
     */
    private $messageText;

    /**
     * @var \DateTime
     */
    private $dateTimeSent;

    /**
     * @var boolean
     */
    private $isReaded;

    /**
     * @var integer
     */
    private $messageId;

    /**
     * @var \AirSim\Bundle\CoreBundle\Entity\User
     */
    private $user;

    /**
     * @var \AirSim\Bundle\CoreBundle\Entity\Chat
     */
    private $chat;

    /**
     * @var integer
     */
    private $chatId;

    /**
     * @var integer
     */
    private $userId;


    /**
     * Set messageText
     *
     * @param string $messageText
     * @return ChatMessages
     */
    public function setMessageText($messageText)
    {
        $this->messageText = $messageText;

        return $this;
    }

    /**
     * Get messageText
     *
     * @return string 
     */
    public function getMessageText()
    {
        return $this->messageText;
    }

    /**
     * Set dateTimeSent
     *
     * @param \DateTime $dateTimeSent
     * @return ChatMessages
     */
    public function setDateTimeSent($dateTimeSent)
    {
        $this->dateTimeSent = $dateTimeSent;

        return $this;
    }

    /**
     * Get dateTimeSent
     *
     * @return \DateTime 
     */
    public function getDateTimeSent()
    {
        return $this->dateTimeSent;
    }

    /**
     * Set isReaded
     *
     * @param boolean $isReaded
     * @return ChatMessages
     */
    public function setIsReaded($isReaded)
    {
        $this->isReaded = $isReaded;

        return $this;
    }

    /**
     * Get isReaded
     *
     * @return boolean 
     */
    public function getIsReaded()
    {
        return $this->isReaded;
    }

    /**
     * Get messageId
     *
     * @return integer 
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * Set user
     *
     * @param \AirSim\Bundle\CoreBundle\Entity\User $user
     * @return ChatMessages
     */
    public function setUser(\AirSim\Bundle\CoreBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AirSim\Bundle\CoreBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set chat
     *
     * @param \AirSim\Bundle\CoreBundle\Entity\Chat $chat
     * @return ChatMessages
     */
    public function setChat(\AirSim\Bundle\CoreBundle\Entity\Chat $chat = null)
    {
        $this->chat = $chat;

        return $this;
    }

    /**
     * Get chat
     *
     * @return \AirSim\Bundle\CoreBundle\Entity\Chat 
     */
    public function getChat()
    {
        return $this->chat;
    }

    /**
     * @param int $chatId
     */
    public function setChatId($chatId)
    {
        $this->chatId = $chatId;
    }

    /**
     * @return int
     */
    public function getChatId()
    {
        return $this->chatId;
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

}
