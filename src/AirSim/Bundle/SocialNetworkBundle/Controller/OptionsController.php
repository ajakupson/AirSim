<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use AirSim\Bundle\CoreBundle\Services\OptionsService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AirSim\Bundle\CoreBundle\Security\PrivilegeChecker;

class OptionsController extends Controller
{

    public function optionsAction($type)
    {
        $LOG = $this->get('logger');
        $LOG->info('optionsAction executed in OptionsController');

        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $userId = $session->get('sessionData')['userInfo']['id'];

        $privilegeChecker = PrivilegeChecker::getInstance();
        $userPrivileges = $privilegeChecker->getUserPrivileges($userId);

        return $this->render('AirSimSocialNetworkBundle:blue/Options:options.html.twig',
            array('userPrivileges' => $userPrivileges));
    }

    /* ***** AJAX ***** */
    public function configChangeAction()
    {
        $success = false;
        $error = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        $userId = $session->get('sessionData')['userInfo']['id'];
        $configAlias = $request->get('configAlias');
        $valueToBeSet = $request->get('configValue');

        $optionsService = OptionsService::getInstance();
        $success = $optionsService->changeConfigParameter($userId, $configAlias, $valueToBeSet);

        $response = array('success' => $success, 'error' => $error);
        return new Response(json_encode($response));
    }
}
