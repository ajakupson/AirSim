<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function userAction($username)
    {
        return $this->render('AirSimSocialNetworkBundle:blue/User:user.html.twig');
    }
}