<?php

namespace AirSim\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function adminAction()
    {
        return $this->render('AirSimAdminBundle:Default:index.html.twig');
    }
}
