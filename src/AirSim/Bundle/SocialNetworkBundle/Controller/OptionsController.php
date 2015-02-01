<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class OptionsController extends Controller
{

    public function optionsAction($type)
    {
        $LOG = $this->get('logger');

        switch($type)
        {
            case 'private':
            {
                $LOG->info('optionsAction executed in OptionsController with parameter = '.$type);

                return $this->render('AirSimSocialNetworkBundle:blue/Options:private.html.twig');

            }break;
            case 'synchronization':
            {
                $LOG->info('optionsAction executed in OptionsController with parameter = '.$type);

                return $this->render('AirSimSocialNetworkBundle:blue/Options:synchronization.html.twig');

            }break;
            case 'safety':
            {
                $LOG->info('optionsAction executed in OptionsController with parameter = '.$type);

                return $this->render('AirSimSocialNetworkBundle:blue/Options:safety.html.twig');

            }break;
            case 'personal':
            {
                $LOG->info('optionsAction executed in OptionsController with parameter = '.$type);

                return $this->render('AirSimSocialNetworkBundle:blue/Options:personal.html.twig');

            }break;
            default : break;
        }
    }

    /* ***** AJAX ***** */
}
