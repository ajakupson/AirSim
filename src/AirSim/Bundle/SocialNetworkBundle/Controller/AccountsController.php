<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use AirSim\Bundle\CoreBundle\Tools\Constants;
use AirSim\Bundle\CoreBundle\Tools\SerializerDeserializer;

class AccountsController extends Controller
{

    public function accountsAction($action)
    {
        $LOG = $this->get('logger');

        switch($action)
        {
            case 'login':
            {
                $LOG->info('accountsAction executed in AccountsController with parameter = '.$action);

                return $this->render('AirSimSocialNetworkBundle:blue/Accounts:login.html.twig');

            }break;
            default : break;
        }
    }

    /* ***** AJAX ***** */
    public function loginCheckAction()
    {
        $LOG = $this->get('logger');
        $LOG->info('loginCheckAction executed in AccountsController');

        $error = '';
        $success = true;
        $response = '';

        $entityManager = $this->getDoctrine()->getManager();
        $usersRepo = $entityManager->getRepository('AirSimCoreBundle:User');

        $request = $this->get('request_stack')->getCurrentRequest();
        $username = $request->get('username');
        $password = $request->get('password');

        $LOG->info('Username: '.$username.', password: '.$password);

        $user = $usersRepo->findOneBy(array('login' => $username, 'password' => md5($password)));
        if(sizeof($user) == 0)
        {
            $error = Constants::ERR_WRONG_USERNAME_OR_PASS;
            $success = false;
            $response = array('success' => $success, 'error' => $error);

            $LOG->info('Entered invalid username or password');
        }
        else
        {
            $session = $request->getSession();
            $userInfo = array
            (
                'id' => $user->getUserId(),
                'username' => $username,
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'webPic' => $user->getWebProfilePic()
            );
            $session->set('sessionData', array('userInfo' => $userInfo));

            $baseUrl = $this->container->get('router')->getContext()->getBaseUrl();
            $userPage = $baseUrl.'/user/'.$username;
            $response = array('success' => $success, 'error' => $error,
                'data' => array('userPage' => $userPage));
        }

        return new Response(json_encode($response));
    }
}
