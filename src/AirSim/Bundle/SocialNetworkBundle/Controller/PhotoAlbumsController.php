<?php
/**
 * Created by Andrei Jakupson
 * Date: 14.03.15
 * Time: 17:55
 */

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AirSim\Bundle\CoreBundle\Security\PrivilegeChecker;
use AirSim\Bundle\CoreBundle\Services\PhotoAlbumsService;

class PhotoAlbumsController extends Controller
{

    public function photoAlbumsAction($contactId = null)
    {
        $LOG = $this->get('logger');
        $LOG->info('photosAction executed in PhotosController');

        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $userId = $session->get('sessionData')['userInfo']['id'];

        if($contactId == null)
        {
            $contactId = $userId;
        }

        $privilegeChecker = PrivilegeChecker::getInstance();
        $userPrivileges = $privilegeChecker->getUserPrivileges($userId);

        $photoAlbumsService = PhotoAlbumsService::getInstance();

        $albumsAndPhotos = $photoAlbumsService->getAlbumsAndPhotosData($userId);

        return $this->render('AirSimSocialNetworkBundle:blue/Photos:photo_albums.html.twig',
            array('userPrivileges' => $userPrivileges, 'albumsAndPhotos' => $albumsAndPhotos));
    }
}