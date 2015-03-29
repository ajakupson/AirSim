<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use AirSim\Bundle\CoreBundle\Services\ChatService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AirSim\Bundle\CoreBundle\Security\PrivilegeChecker;

class ChatController extends Controller
{

    public function chatAction()
    {
        $LOG = $this->get('logger');
        $LOG->info('chatAction executed in ChatController');

        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $userId = $session->get('sessionData')['userInfo']['id'];

        $privilegeChecker = PrivilegeChecker::getInstance();
        $userPrivileges = $privilegeChecker->getUserPrivileges($userId);

        $chatService = ChatService::getInstance();

        $availableChats = $chatService->getAvailableChats($userId);

        return $this->render('AirSimSocialNetworkBundle:blue/Chat:available_chats.html.twig',
            array('userPrivileges' => $userPrivileges, 'availableChats' => $availableChats));

    }

    /* ***** AJAX ***** */
}
