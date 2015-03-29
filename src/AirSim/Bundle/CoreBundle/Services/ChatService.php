<?php
/**
 * Created by Andrei Jakupson
 * Date: 8.03.15
 * Time: 20:04
 */

namespace AirSim\Bundle\CoreBundle\Services;

use AirSim\Bundle\CoreBundle\AirSimCoreBundle;
use AirSim\Bundle\CoreBundle\DataTransferObjects\ChatInfoDTO;
use AirSim\Bundle\CoreBundle\DataTransferObjects\UserWebDataDTO;
use AirSim\Bundle\CoreBundle\Services\UserService;

class ChatService
{
    private static $chatServiceInstance = null;

    // Dependencies
    private $entityManager = null;
    private $chatRepository = null;
    private $chatMembersRepository = null;
    private $chatMessagesRepository = null;
    private $userService = null;

    private function __construct()
    {

        $this->entityManager = AirSimCoreBundle::getContainer()->get('doctrine')->getManager();
        $this->chatRepository = $this->entityManager->getRepository('AirSimCoreBundle:Chat');
        $this->chatMembersRepository = $this->entityManager->getRepository('AirSimCoreBundle:ChatMembers');
        $this->chatMessagesRepository = $this->entityManager->getRepository('AirSimCoreBundle:ChatMessages');
        $this->userService = UserService::getInstance();

    }

    public static function getInstance()
    {
        if(is_null(self::$chatServiceInstance))
        {
            self::$chatServiceInstance = new self();
        }

        return self::$chatServiceInstance;
    }

    public function getAvailableChats($userId)
    {
        $availableChatsInfo = array();

        $query = $this->entityManager->createQueryBuilder();
        $query
            ->select('chat,
                      chatLastMessage.messageId,
                      chatLastMessage.messageText,
                      chatLastMessage.dateTimeSent,
                      contact.userId,
                      contact.firstName,
                      contact.lastName,
                      contact.webProfilePic')
            ->from('AirSimCoreBundle:Chat', 'chat')
            ->innerJoin('AirSimCoreBundle:ChatMembers', 'chatMembers', 'WITH', 'chat.chatId = chatMembers.chatId AND chatMembers.userId = :userId')
            ->leftJoin('AirSimCoreBundle:ChatMessages', 'chatLastMessage', 'WITH', 'chat.chatId = chatLastMessage.chatId AND chatLastMessage.messageId =
                        (
                            SELECT MAX(lastMessage.messageId)
                            FROM AirSimCoreBundle:ChatMessages AS lastMessage
                            WHERE chat.chatId = lastMessage.chatId
                        )')
            ->leftJoin('AirSimCoreBundle:ChatMembers', 'chatContactMembers', 'WITH', 'chat.chatId = chatContactMembers.chatId')
            ->leftJoin('AirSimCoreBundle:User', 'contact', 'WITH', 'chatContactMembers.userId = contact.userId AND chatContactMembers.userId != :userId')
            ->setParameter('userId', $userId);

        $chats = $query->getQuery()->getResult();

        foreach($chats as $chat)
        {
            $chatInfoDTO = new ChatInfoDTO();
            $chatInfoDTO->setChatId($chat[0]->getChatId());
            $chatInfoDTO->setDescription($chat[0]->getDescription());
            $chatInfoDTO->setDateTimeCreated($chat[0]->getDateTimeCreated()->format('d.m.Y h:i:s'));

            $chatShortMessage = null;
            if(strlen($chat['messageText']) > 94)
            {
                $chatShortMessage = substr($chat['messageText'], 0, 94).' ...';
            }
            else
            {
                $chatShortMessage = $chat['messageText'];
            }

            $chatInfoDTO->setChatLastMessage($chatShortMessage);
            $chatInfoDTO->setMessageSentTime($chat['dateTimeSent']->format('d.m.Y h:i:s'));

            $contactInfoDTO = new UserWebDataDTO();
            $contactInfoDTO->setUserId($chat['userId']);
            $contactInfoDTO->setName($chat['firstName']);
            $contactInfoDTO->setFamily($chat['lastName']);
            $contactInfoDTO->setWebProfilePic($chat['webProfilePic']);

            $chatInfoDTO->setContactData($contactInfoDTO);

            $availableChatsInfo[] = $chatInfoDTO;
        }

        uasort($availableChatsInfo, 'AirSim\Bundle\CoreBundle\Services\ChatService::sortChatsByDateTime');

        return $availableChatsInfo;

    }

    public function getChatParticipantInfo($chatId, $userId)
    {
        $query = $this->entityManager->createQueryBuilder();
        $query
            ->select('chatMember')
            ->from('AirSimCoreBundle:ChatMembers', 'chat')
            ->innerJoin('AirSimCoreBundle:User', 'chatMember', 'WITH', 'chat.userId = chatMember.userId')
            ->where('chat.chatId = :chatId')
            ->andWhere('chat.userId != :userId')
            ->setParameter('chatId', $chatId)
            ->setParameter('userId', $userId);
        $participantInfo = $query->getQuery()->setMaxResults(1)->getResult()[0];

        $chatParticipantInfoDTO = new UserWebDataDTO();
        $chatParticipantInfoDTO->setUserId($participantInfo->getUserId());
        $chatParticipantInfoDTO->setName($participantInfo->getFirstName());
        $chatParticipantInfoDTO->setFamily($participantInfo->getLastName());
        $chatParticipantInfoDTO->setWebProfilePic($participantInfo->getWebProfilePic());

        return $chatParticipantInfoDTO;
    }

    private static function sortChatsByDateTime($a, $b)
    {
        if($a->getMessageSentTime() == $b->getMessageSentTime())
            return 0;

        return ($a->getMessageSentTime() > $b->getMessageSentTime()) ? -1 : 1;
    }
}