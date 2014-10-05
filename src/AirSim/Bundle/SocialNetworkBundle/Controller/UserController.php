<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use AirSim\Bundle\CoreBundle\Services\FileService;
use AirSim\Bundle\CoreBundle\Services\WallService;
use AirSim\Bundle\SocialNetworkBundle\Utils\ResponseBuilder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;

use AirSim\Bundle\CoreBundle\Services\PhotoService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AirSim\Bundle\CoreBundle\Services\UserService;
use AirSim\Bundle\CoreBundle\Tools\Constants;

class UserController extends Controller
{
    public function userAction($username)
    {
        $userService = UserService::getInstance();
        $userId = $userService->getUserIdByUsername($username);
        $lastContacts = $userService->getUserFriends($userId, null, Constants::LAST_CONTACTS_LIMIT);

        $photosService = PhotoService::getInstance();
        $lastPhotos = $photosService->getUserPhotos($userId, null, Constants::LAST_PHOTOS_LIMIT);

        $wallService = WallService::getInstance();
        $wallRecords = null;
        $wallRecords = $wallService->getWallRecords($userId, null, Constants::WALL_RECORDS_LIMIT);
//        var_dump($wallRecords); exit;

        $randomContacts = $userService->getUserFriends($userId, null, Constants::RANDOM_CONTACTS_LIMIT, true);

        return $this->render('AirSimSocialNetworkBundle:blue/User:user.html.twig', array('lastContacts' => $lastContacts,
            'lastPhotos' => $lastPhotos, 'wallRecords' => $wallRecords, 'randomContacts' => $randomContacts));
    }

    /* ***** AJAX Calls ***** */
    public function getPhotoDataAction()
    {
        $LOG = $this->get('logger');
        $LOG->info('getPhotoDataAction executed in UserController');

        $error = '';
        $success = true;
        $response = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $photoId = $request->get('photoId');

        $photosService = PhotoService::getInstance();
        $photoDTO = $photosService->getPhotoDTO($photoId, null, Constants::PHOTO_COMMENTS_LIMIT);

        $response = array('success' => $success, 'error' => $error, 'data' => array("photoData" => $photoDTO->expose()));

        return new Response(json_encode($response));
    }

    public function addWallRecordAction()
    {
        $LOG = $this->get('logger');
        $LOG->info('addWallRecord executed in UserController');

        $error = '';
        $success = true;
        $response = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();
        $sessionData = $session->get('sessionData');
        $page = $request->get('page');
        $receiverId = $request->get('receiverId');
        $authorId = $session->get('sessionData')['userInfo']['id'];
        $text = $request->get('text');
        $attachedPictures = $request->get('attachedPictures');

        $wallService = WallService::getInstance();
        $addedWallRecord = $wallService->addWallRecord($receiverId, $authorId, $text, $attachedPictures);

        $photoService = PhotoService::getInstance();

        // TODO: Add localization
        $notificationInfo = 'User <span class = "author">%s %s</span> has sent you a message!';
        $notificationInfoFormatted = sprintf($notificationInfo, $sessionData['userInfo']['firstName'],
            $sessionData['userInfo']['lastName']);

        $eventData = array
        (
            'success' => $success,
            'page' => $page,
            'event' => 'addWallRecord',
            'messageText' => $text,
            'recordDate' => $addedWallRecord['wallRecord']->getDateAdded()->format('d/m/Y'),
            'recordTime' => $addedWallRecord['wallRecord']->getDateAdded()->format('H:i'),
            'newWallRecordId' => $addedWallRecord['wallRecord']->getWallRecId(),
            'wallRecordPictures' => $addedWallRecord['wallRecordPictures'],
            'notificationInfo' => $notificationInfoFormatted
        );

        $response = ResponseBuilder::BuildResponse(null, null, $receiverId, null, $eventData, $session->get('sessionData'));

        return new Response(json_encode($response));

    }

    public function uploadTmpImagesAction()
    {
        $success = true;
        $error = '';
        $imagesValidFormats = explode(', ', Constants::IMAGES_VALID_FORMATS);

        $request = $this->get('request_stack')->getCurrentRequest();
        $receiverId = $request->get('receiverId');

        $picturesToBeUploaded = $request->files->get('wall_record_attached_images');
        $uploadedPictures = array();
        if(sizeof($picturesToBeUploaded) > 0)
        {
            $fileService = FileService::getInstance();
            $uploadedPictures = $fileService->uploadFiles($picturesToBeUploaded, $imagesValidFormats, Constants::TMP_FILES_DIRECTORY_PATH,
                $receiverId);
        }

        $response = array
        (
            'success' => $success,
            'uploadedPictures' => $uploadedPictures
        );

        return new Response(json_encode($response));
    }

    public function deleteTmpImageAction()
    {
        $success = true;
        $error = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $pictureToBeDeleted = $request->get('imageToDelete');

        $fileService = FileService::getInstance();
        $fileService->deleteFile(Constants::TMP_FILES_DIRECTORY_PATH.$pictureToBeDeleted);

        $response = array
        (
            'success' => $success
        );

        return new Response(json_encode($response));
    }
}