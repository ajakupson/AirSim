<?php

namespace AirSim\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChatMembers
 */
class ChatMembers
{
    /**
     * @var integer
     */
    private $recId;

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
     * Get recId
     *
     * @return integer 
     */
    public function getRecId()
    {
        return $this->recId;
    }

    /**
     * Set user
     *
     * @param \AirSim\Bundle\CoreBundle\Entity\User $user
     * @return ChatMembers
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
     * @return ChatMembers
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
