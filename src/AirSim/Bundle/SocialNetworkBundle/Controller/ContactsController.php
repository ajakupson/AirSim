<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use AirSim\Bundle\CoreBundle\DataTransferObjects\UserWebDataDTO;
use AirSim\Bundle\CoreBundle\SearchParameters\ContactSP;
use AirSim\Bundle\CoreBundle\Tools\Functions;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AirSim\Bundle\CoreBundle\Tools\Constants;
use AirSim\Bundle\CoreBundle\Services\UserService;

class ContactsController extends Controller
{

    public function contactsAction($type)
    {
        $LOG = $this->get('logger');

        $userService = UserService::getInstance();

        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $userId = $session->get('sessionData')['userInfo']['id'];

        /*switch($type)
        {
            case 'friends':
            {
                $LOG->info('contactsAction executed in ContactsController with parameter = '.$type);

                $userFriends = $userService->getUserFriends($userId, 0, Constants::CONTACTS_LIST_LIMIT, false);
                //var_dump($userFriends); exit;

                return $this->render('AirSimSocialNetworkBundle:blue/Contacts:friends.html.twig',
                    array('userFriends' => $userFriends));

            }break;
            case 'add':
            {
                $LOG->info('contactsAction executed in ContactsController with parameter = '.$type);

                return $this->render('AirSimSocialNetworkBundle:blue/Contacts:add.html.twig');

            }break;
            case 'updates':
            {
                $LOG->info('contactsAction executed in ContactsController with parameter = '.$type);

                return $this->render('AirSimSocialNetworkBundle:blue/Contacts:updates.html.twig');

            }break;
            default : break;

        }*/

        $userFriends = $userService->getUserFriends($userId, 0, Constants::CONTACTS_LIST_LIMIT, false);

        return $this->render('AirSimSocialNetworkBundle:blue/Contacts:contacts.html.twig',
            array('userFriends' => $userFriends));
    }

    /* ***** AJAX ***** */
    public function searchContactsAction()
    {
        $LOG = $this->get('logger');
        $LOG->info('searchContactAction executed in ContactsController');

        $success = true;
        $error = '';
        $response = '';

        // Preparation
        $userService = UserService::getInstance();
        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();
        $sessionData = $session->get('sessionData');
        $userId = $session->get('sessionData')['userInfo']['id'];

        $contactSearchParams = new ContactSP();
        $contactSearchParams->setNameAndOrFamily(Functions::setStringLike($request->get('nameAndOrFamily')));
        $contactSearchParams->setGender($request->get('gender'));
        $contactSearchParams->setPhoneNumber(Functions::setStringLike($request->get('phoneNumber')));
        $contactSearchParams->setCountry(Functions::setStringLike($request->get('country')));
        $contactSearchParams->setCity(Functions::setStringLike($request->get('city')));
        $contactSearchParams->setAgeFrom($request->get('ageFrom'));
        $contactSearchParams->setAgeTo($request->get('ageTo'));
        $contactSearchParams->setIsFriend((boolean)$request->get('isFriend'));
        $contactSearchParams->setUserId($userId);

        $foundContacts = $userService->searchContacts($contactSearchParams, Constants::CONTACTS_LIST_LIMIT, 0);



        $response = array
        (
            'success' => $success,
            'error' => $error,
            'foundContacts' => $foundContacts
        );

        return new Response(json_encode($response));
    }
}
