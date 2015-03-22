<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use AirSim\Bundle\CoreBundle\Services\ChatService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AirSim\Bundle\CoreBundle\Security\PrivilegeChecker;

class ChatController extends Controller
{

    public function chatAction($page)
    {
        $LOG = $this->get('logger');

        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $userId = $session->get('sessionData')['userInfo']['id'];

        $privilegeChecker = PrivilegeChecker::getInstance();
        $userPrivileges = $privilegeChecker->getUserPrivileges($userId);

        $chatService = ChatService::getInstance();

        //$availableChats = $chatService->getAvailableChats($userId);
        $availableChats = array();


        switch($page)
        {
            case 'available_chats':
            {
                $LOG->info('chatAction executed in ChatController with parameter = '.$page);

                return $this->render('AirSimSocialNetworkBundle:blue/Chat:available_chats.html.twig',
                    array('userPrivileges' => $userPrivileges, 'availableChats' => $availableChats));

            }break;
            case 'chat_room':
            {
                $LOG->info('chatAction executed in ChatController with parameter = '.$page);

                return $this->render('AirSimSocialNetworkBundle:blue/Chat:chat.html.twig',
                    array('userPrivileges' => $userPrivileges));

            }break;
            default : break;
        }


    }

    /* ***** AJAX ***** */
}
