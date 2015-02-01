<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ChatController extends Controller
{

    public function chatAction($page)
    {
        $LOG = $this->get('logger');

        switch($page)
        {
            case 'available_chats':
            {
                $LOG->info('chatAction executed in ChatController with parameter = '.$page);

                return $this->render('AirSimSocialNetworkBundle:blue/Chat:available_chats.html.twig');

            }break;
            case 'chat_room':
            {
                $LOG->info('chatAction executed in ChatController with parameter = '.$page);

                return $this->render('AirSimSocialNetworkBundle:blue/Chat:chat.html.twig');

            }break;
            default : break;
        }
    }

    /* ***** AJAX ***** */
}
