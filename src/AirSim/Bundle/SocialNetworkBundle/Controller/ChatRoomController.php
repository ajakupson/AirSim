<?php
/**
 * Created by Andrei Jakupson
 * Date: 24.03.15
 * Time: 1:10
 */

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use AirSim\Bundle\CoreBundle\Tools\Constants;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AirSim\Bundle\CoreBundle\Security\PrivilegeChecker;
use AirSim\Bundle\CoreBundle\Services\ChatMessagesService;
use AirSim\Bundle\CoreBundle\Services\ChatService;
use AirSim\Bundle\SocialNetworkBundle\Utils\ResponseBuilder;

class ChatRoomController extends Controller
{
    public function chatRoomAction($chatId)
    {
        $LOG = $this->get('logger');
        $LOG->info('chatRoomAction executed in ChatRoomController.');

        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $userId = $session->get('sessionData')['userInfo']['id'];

        $privilegeChecker = PrivilegeChecker::getInstance();
        $userPrivileges = $privilegeChecker->getUserPrivileges($userId);

        $chatService = ChatService::getInstance();
        $chatMessagesService = ChatMessagesService::getInstance();

        $chatMessages = $chatMessagesService->getChatMessages($chatId);
        $availableChats = $chatService->getAvailableChats($userId);

        if(sizeof($chatMessages) > 0)
        {
            $participantInfo = $chatService->getChatParticipantInfo($chatId, $userId);

            return $this->render('AirSimSocialNetworkBundle:blue/Chat:chat.html.twig',
                array('userPrivileges' => $userPrivileges, 'participantInfo' => $participantInfo, 'chatId' => $chatId,
                    'chatMessages' => $chatMessages, 'availableChats' => $availableChats));
        }

        return $this->render('AirSimSocialNetworkBundle:blue/Errors:not_found.html.twig',
            array('userPrivileges' => $userPrivileges));
    }

    /* AJAX calls */
    public function sendMessageAction()
    {
        $LOG = $this->get('logger');
        $LOG->info('sendMessageAction executed in ChatRoomController');

        $error = '';
        $success = true;
        $response = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        $chatId = $request->get('chatId');
        $receiverId = $request->get('receiverId');
        $messageText = $request->get('messageText');
        $userId = $session->get('userSessionData')['userInfo']['id'];

        $chatMessagesService = ChatMessagesService::getInstance();
        $newChatMessage = $chatMessagesService->addChatMessage($chatId, $userId, $messageText);

        // TODO: Add localization
        $notificationInfo = 'User <span class = "author">%s %s</span> has sent You a message.';
        $notificationInfoFormatted = sprintf($notificationInfo, $session->get('sessionData')['userInfo']['firstName'],
            $session->get('sessionData')['userInfo']['lastName']);

        $eventData = array
        (
            'success' => $success,
            'page' => 'chat_'.$chatId,
            'event' => Constants::SEND_MESSAGE,
            'messageText' => $messageText,
            'dateTime' => $newChatMessage->getMessageDateTimeSent(),
            'notificationInfo' => $notificationInfoFormatted
        );

        $response = ResponseBuilder::BuildResponse(null, null, $receiverId, null, $eventData, $session->get('sessionData'));

        return new Response(json_encode($response));
    }
}