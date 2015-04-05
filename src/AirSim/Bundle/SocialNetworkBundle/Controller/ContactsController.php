<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use AirSim\Bundle\CoreBundle\DataTransferObjects\UserWebDataDTO;
use AirSim\Bundle\CoreBundle\SearchParameters\ContactSP;
use AirSim\Bundle\CoreBundle\Services\ContactsService;
use AirSim\Bundle\CoreBundle\Tools\Functions;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AirSim\Bundle\CoreBundle\Tools\Constants;
use AirSim\Bundle\CoreBundle\Services\UserService;
use AirSim\Bundle\CoreBundle\Security\PrivilegeChecker;
use AirSim\Bundle\SocialNetworkBundle\Utils\ResponseBuilder;

class ContactsController extends Controller
{

    public function contactsAction($type)
    {
        $LOG = $this->get('logger');

        $userService = UserService::getInstance();
        $contactsService = ContactsService::getInstance();

        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $userId = $session->get('sessionData')['userInfo']['id'];

        $privilegeChecker = PrivilegeChecker::getInstance();
        $userPrivileges = $privilegeChecker->getUserPrivileges($userId);

        $userFriends = $userService->getUserFriends($userId, 0, Constants::CONTACTS_LIST_LIMIT, false);
        $userUpdates = $contactsService->getUpdates($userId);

        return $this->render('AirSimSocialNetworkBundle:blue/Contacts:contacts.html.twig',
            array('userFriends' => $userFriends, 'userPrivileges' => $userPrivileges, 'userUpdates' => $userUpdates));
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
        $contactSearchParams->setIsFriend($request->get('isFriend') === 'true' ? true : false);
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

    public function sendFriendRequestAction()
    {
        $LOG = $this->get('logger');
        $LOG->info('sendFriendRequestAction executed in ContactsController');

        $error = '';
        $success = true;
        $response = '';

        $contactsService = ContactsService::getInstance();
        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        $senderId = $session->get('sessionData')['userInfo']['id'];
        $receiverId = $request->get('receiverId');

        $requestData = $contactsService->sendFriendRequest($senderId, $receiverId);

        // TODO: Add localization
        $notificationInfo = 'User <span class = "author">%s %s</span> has added You to a contact list!';
        $notificationInfoFormatted = sprintf($notificationInfo, $session->get('sessionData')['userInfo']['firstName'],
            $session->get('sessionData')['userInfo']['lastName']);

        $eventData = array
        (
            'success' => $success,
            'page' => 'updates'.$receiverId,
            'event' => $requestData['event'],
            'messageText' => '',
            'friendRequestId' => $requestData['friendRequestId'],
            'dateTime' => $requestData['dateTimeAdded']->format('d.m.Y h:i:s'),
            'notificationInfo' => $notificationInfoFormatted
        );

        $response = ResponseBuilder::BuildResponse(null, null, $receiverId, null, $eventData, $session->get('sessionData'));

        return new Response(json_encode($response));
    }
}
