<?php
/**
 * Created by Andrei Jakupson
 * Date: 8.03.15
 * Time: 21:01
 */

namespace AirSim\Bundle\CoreBundle\DataTransferObjects;


class ChatInfoDTO
{
    /**
     * @var integer
     */
    private $chatId;

    /**
     * @var string
     */
    private $chatLastMessage;

    /**
     * @var string
     */
    private $messageSentTime;

    /**
     * @var boolean
     */
    private $isMessageRead;

    /**
     * @var UserWebDataDTO
     */
    private $contactData;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $dateTimeCreated;

    // Getters / setters
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
     * @param string $chatLastMessage
     */
    public function setChatLastMessage($chatLastMessage)
    {
        $this->chatLastMessage = $chatLastMessage;
    }

    /**
     * @return string
     */
    public function getChatLastMessage()
    {
        return $this->chatLastMessage;
    }

    /**
     * @param \AirSim\Bundle\CoreBundle\DataTransferObjects\UserWebDataDTO $contactData
     */
    public function setContactData($contactData)
    {
        $this->contactData = $contactData;
    }

    /**
     * @return \AirSim\Bundle\CoreBundle\DataTransferObjects\UserWebDataDTO
     */
    public function getContactData()
    {
        return $this->contactData;
    }

    /**
     * @param boolean $isMessageRead
     */
    public function setIsMessageRead($isMessageRead)
    {
        $this->isMessageRead = $isMessageRead;
    }

    /**
     * @return boolean
     */
    public function getIsMessageRead()
    {
        return $this->isMessageRead;
    }

    /**
     * @param string $messageSentTime
     */
    public function setMessageSentTime($messageSentTime)
    {
        $this->messageSentTime = $messageSentTime;
    }

    /**
     * @return string
     */
    public function getMessageSentTime()
    {
        return $this->messageSentTime;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $dateTimeCreated
     */
    public function setDateTimeCreated($dateTimeCreated)
    {
        $this->dateTimeCreated = $dateTimeCreated;
    }

    /**
     * @return string
     */
    public function getDateTimeCreated()
    {
        return $this->dateTimeCreated;
    }
}