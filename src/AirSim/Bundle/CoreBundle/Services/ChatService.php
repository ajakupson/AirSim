<?php
/**
 * Created by Andrei Jakupson
 * Date: 8.03.15
 * Time: 20:04
 */

namespace AirSim\Bundle\CoreBundle\Services;

use AirSim\Bundle\CoreBundle\AirSimCoreBundle;
use AirSim\Bundle\CoreBundle\DataTransferObjects\ChatInfoDTO;
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
                      (
                        SELECT chatMessage.messageId
                        FROM AirSimCoreBundle:ChatMessages AS chatMessage
                        WHERE chat.chatId = chatMessage.chatId
                        AND chatMessage.messageId =
                        (
                            SELECT MAX(lastMessage.messageId)
                            FROM AirSimCoreBundle:ChatMessages AS lastMessage
                            WHERE chat.chatId = lastMessage.chatId
                        )
                      ) AS chatLastMessageId,
                      (
                        SELECT chatMessage.messageText
                        FROM AirSimCoreBundle:ChatMessages AS chatMessage
                        WHERE chat.chatId = chatMessage.chatId
                        AND chatMessage.messageId =
                        (
                            SELECT MAX(lastMessage.messageId)
                            FROM AirSimCoreBundle:ChatMessages AS lastMessage
                            WHERE chat.chatId = lastMessage.chatId
                        )
                      ) AS chatLastMessageText,
                      (
                        SELECT chatMessage.dateTimeSent
                        FROM AirSimCoreBundle:ChatMessages AS chatMessage
                        WHERE chat.chatId = chatMessage.chatId
                        AND chatMessage.messageId =
                        (
                            SELECT MAX(lastMessage.messageId)
                            FROM AirSimCoreBundle:ChatMessages AS lastMessage
                            WHERE chat.chatId = lastMessage.chatId
                        )
                      ) AS chatLastMessageId')
            ->from('AirSimCoreBundle:Chat', 'chat')
            ->innerJoin('AirSimCoreBundle:ChatMembers', 'chatMembers', 'WITH', 'chat.chatId = chatMembers.chatId AND chatMembers.userId = :userId')
            ->setParameter('userId', $userId);

        $chats = $query->getQuery()->getResult();

        var_dump($chats); exit;

        foreach($chats as $chat)
        {
            $chatInfoDTO = new ChatInfoDTO();
            /*$chatInfoDTO->setChatId($chat->getChatId());
            $chatInfoDTO->setDescription($chat->getDescription);
            $chatInfoDTO->setDateTimeCreated($chat->getDateTimeCreated()->format('d.m.Y h:i:s'));*/

            //$availableChatsInfo[] = $chatInfoDTO;
        }

        /*$userEntity = $this->userService->getUserData($userId);

        if($userEntity != null)
        {
            $memberInChats = $userEntity->getChatsMember();
            foreach($memberInChats as $chat)
            {
                $chatEntity = $chat->getChat();
                $chatAllMembers = $chatEntity->getChatMembers();
                foreach($chatAllMembers as $chatMember)
                {
                    if($chatMember->getUser()->getUserId() != $userEntity->getUserId())
                        $chatWithData = $chatMember->getUser();
                }

                $lastMsgIndex = sizeof($chatEntity->getChatMessages()) - 1;
                $chatMessage = $chatEntity->getChatMessages()->get($lastMsgIndex);
                if(strlen($chatMessage->getMessageText()) > 94)
                {
                    $chatShortMsg = substr($chatMessage->getMessageText(), 0, 94).' ...';
                }
                else
                {
                    $chatShortMsg = $chatMessage->getMessageText();
                }

                $availableChatsInfo[] = array
                (
                    'chatId' => $chatEntity->getChatId(),
                    'chatMessage' => $chatShortMsg,
                    'messageSentTime' => $chatMessage->getDateTimeSent()->format('d.m.Y h:i:s'),
                    'messageIsRead' => $chatMessage->getIsReaded(),
                    'contactUsername' => $chatWithData->getLogin(),
                    'contactName' => $chatWithData->getFirstName(),
                    'contactFamily' => $chatWithData->getLastName(),
                    'contactWebProfilePic' => $chatWithData->getWebProfilePic()
                );
            }

            uasort($availableChatsInfo, 'AirSim\Bundle\SocialNetworkBundle\Modules\ChatModule::sortChatsByDateTime');
        }*/

        return $availableChatsInfo;

    }

    private static function sortChatsByDateTime($a, $b)
    {
        if($a['messageSentTime'] == $b['messageSentTime'])
            return 0;

        return ($a['messageSentTime'] > $b['messageSentTime']) ? -1 : 1;
    }
}