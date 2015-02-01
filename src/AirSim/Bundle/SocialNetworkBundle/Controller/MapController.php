<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AirSim\Bundle\CoreBundle\Services\MapService;

class MapController extends Controller
{

    public function mapAction(){}

    /* ***** AJAX ***** */
    public function getUserMarkerAction()
    {
        $error = '';
        $success = true;
        $response = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();
        $sessionData = $session->get('sessionData');
        $userId = $session->get('sessionData')['userInfo']['id'];

        $mapService = MapService::getInstance();
        $userMarkerDTO = $mapService->getUserMarker($userId);

        $response = array('success' => $success, 'error' => $error, 'data' => array("markerData" => $userMarkerDTO->expose()));

        return new Response(json_encode($response));
    }

    public function getFriendsMarkersAction()
    {
        $error = '';
        $success = true;
        $response = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();
        $sessionData = $session->get('sessionData');
        $userId = $session->get('sessionData')['userInfo']['id'];

        $mapService = MapService::getInstance();
        $userMarkerDTOs = $mapService->getFriendsMarkers($userId);

        $response = array('success' => $success, 'error' => $error, 'data' => array("markersData" => $userMarkerDTOs));

        return new Response(json_encode($response));
    }

    public function addMarkerAction()
    {
        $error = '';
        $success = true;
        $response = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();
        $sessionData = $session->get('sessionData');
        $userId = $session->get('sessionData')['userInfo']['id'];
        $address = $request->get('address');
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');
        $comment = $request->get('comment');

        $mapService = MapService::getInstance();
        $userMarkerDTO = $mapService->setUserMarker($userId, $address, $latitude, $longitude, $comment);

        $response = array('success' => $success, 'error' => $error, 'data' => array("markerData" => $userMarkerDTO->expose()));

        return new Response(json_encode($response));
    }
}