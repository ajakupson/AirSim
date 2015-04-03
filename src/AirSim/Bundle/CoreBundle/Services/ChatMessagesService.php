<?php
/**
 * Created by Andrei Jakupson
 * Date: 24.03.15
 * Time: 1:19
 */

namespace AirSim\Bundle\CoreBundle\Services;

use AirSim\Bundle\CoreBundle\AirSimCoreBundle;
use AirSim\Bundle\CoreBundle\DataTransferObjects\ChatMessageDTO;
use AirSim\Bundle\CoreBundle\Entity\ChatMessages;

class ChatMessagesService
{
    private static $chatMessagesServiceInstance = null;

    // Dependencies
    private $entityManager = null;
    private $chatRepository = null;
    private $chatMessagesRepository = null;
    private $userRepository = null;

    private function __construct()
    {
        $this->entityManager = AirSimCoreBundle::getContainer()->get('doctrine')->getManager();
        $this->chatMessagesRepository = $this->entityManager->getRepository('AirSimCoreBundle:ChatMessages');
        $this->chatRepository = $this->entityManager->getRepository('AirSimCoreBundle:Chat');
        $this->userRepository = $this->entityManager->getRepository('AirSimCoreBundle:User');
    }

    public static function getInstance()
    {
        if(is_null(self::$chatMessagesServiceInstance))
        {
            self::$chatMessagesServiceInstance = new self();
        }

        return self::$chatMessagesServiceInstance;
    }

    public function getChatMessages($chatId, $limit = 15, $offset = 0)
    {
        $query = $this->entityManager->createQueryBuilder();
        $query
            ->select('messages')
            ->from('AirSimCoreBundle:ChatMessages', 'messages')
            ->innerJoin('AirSimCoreBundle:ChatMembers', 'members', 'WITH', 'members.userId = messages.userId')
            ->where('messages.chatId = :chatId')
            ->setParameter('chatId', $chatId)
            ->setFirstResult($offset)
            ->orderBy('messages.dateTimeSent', 'ASC');

        $messages = $query->getQuery()->getResult();
        $messages = array_slice($messages, 0, $limit);

        $messagesDTO = array();
        foreach($messages as $message)
        {
            $messageDTO = new ChatMessageDTO();
            $messageDTO->setMessageId($message->getMessageId());
            $messageDTO->setAuthorId($message->getUserId());
            $messageDTO->setMessageText($message->getMessageText());
            $messageDTO->setMessageDateTimeSent($message->getDateTimeSent()->format('d.m.Y h:i:s'));
            $messageDTO->setIsRead($message->getIsReaded());

            $messagesDTO[] = $messageDTO;
        }

        return $messagesDTO;
    }

    public function addChatMessage($chatId, $authorId, $messageText)
    {
        $dateTime = new \DateTime();

        $userEntity = $this->userRepository->findOneByUserId($authorId);
        $chatEntity = $this->chatRepository->findOneByChatId($chatId);

        $chatMessage = new ChatMessages();
        $chatMessage->setChat($chatEntity);
        $chatMessage->setUser($userEntity);
        $chatMessage->setMessageText($messageText);
        $chatMessage->setDateTimeSent($dateTime);
        $chatMessage->setIsReaded(false);

        $this->entityManager->persist($chatMessage);
        $this->entityManager->flush();

        $messageDTO = new ChatMessageDTO();
        $messageDTO->setMessageId($chatMessage->getMessageId());
        $messageDTO->setAuthorId($chatMessage->getUserId());
        $messageDTO->setMessageText($chatMessage->getMessageText());
        $messageDTO->setMessageDateTimeSent($chatMessage->getDateTimeSent()->format('d.m.Y h:i:s'));
        $messageDTO->setIsRead($chatMessage->getIsReaded());

        return $messageDTO;
    }

    public function readChatMessage($messageId) {

        $messageEntity = $this->chatMessagesRepository->findOneByMessageId($messageId);
        $messageEntity->setIsReaded(true);
        $this->entityManager->persist($messageEntity);
        $this->entityManager->flush();
    }

    public function deleteChatMessage($messageId) {

        $messageEntity = $this->chatMessagesRepository->findOneByMessageId($messageId);
        $this->entityManager->remove($messageEntity);
        $this->entityManager->flush();
    }
}