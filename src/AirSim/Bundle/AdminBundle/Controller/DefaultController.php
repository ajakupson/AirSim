<?php

namespace AirSim\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AirSimAdminBundle:Default:index.html.twig', array('name' => $name));
    }
}
