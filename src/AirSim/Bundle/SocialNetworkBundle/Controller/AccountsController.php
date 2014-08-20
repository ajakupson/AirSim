<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccountsController extends Controller
{
    public function accountsAction($action)
    {
        switch($action)
        {
            case 'login':
            {
                return $this->render('AirSimSocialNetworkBundle:blue/Accounts:login.html.twig', array('action' => $action));

            }break;
            default : break;
        }
    }
}
