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
        $userCompleteData = $userService->getUserData($userId);
        $lastContacts = $userService->getUserFriends($userId, null, Constants::LAST_CONTACTS_LIMIT);

        $photosService = PhotoService::getInstance();
        $lastPhotos = $photosService->getUserPhotos($userId, null, Constants::LAST_PHOTOS_LIMIT);

        $wallService = WallService::getInstance();
        $wallRecords = $wallService->getWallRecords($userId, 0, Constants::WALL_RECORDS_LIMIT);

        $randomContacts = $userService->getUserFriends($userId, null, Constants::RANDOM_CONTACTS_LIMIT, true);

        return $this->render('AirSimSocialNetworkBundle:blue/User:user.html.twig', array('userData' => $userCompleteData,
            'lastContacts' => $lastContacts, 'lastPhotos' => $lastPhotos, 'wallRecords' => $wallRecords,
            'randomContacts' => $randomContacts));
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
        $session = $request->getSession();
        $sessionData = $session->get('sessionData');
        $photoId = $request->get('photoId');
        $userId = $session->get('sessionData')['userInfo']['id'];

        $photosService = PhotoService::getInstance();
        $photoDTO = $photosService->getPhotoDTO($photoId, null, Constants::PHOTO_COMMENTS_LIMIT, $userId);

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

    public function likeDislikeWallRecordAction()
    {
        $success = true;
        $error = '';
        $response = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();
        $sessionData = $session->get('sessionData');
        $page = $request->get('page');
        $action = $request->get('action');
        $receiverId = $request->get('receiverId');
        $wallRecordId = $request->get('wallRecordId');
        $senderId = $session->get('sessionData')['userInfo']['id'];

        $wallService = WallService::getInstance();
        $likeData = $wallService->likeDislikeWallRecord($wallRecordId, $senderId, $action);

        // TODO: Add localization
        $likedOrDisliked = $likeData['hasLiked'] ? 'liked' : 'disliked';
        $notificationInfo = 'User <span class = "author">%s %s</span> has '.$likedOrDisliked.' your wall record!';
        $notificationInfoFormatted = sprintf($notificationInfo, $sessionData['userInfo']['firstName'],
            $sessionData['userInfo']['lastName']);

        $eventData = array
        (
            'success' => $success,
            'page' => $page,
            'event' => 'likeDislikeWallRecord',
            'messageText' => '',
            'hasLiked' => $likeData['hasLiked'],
            'hasRecord' => $likeData['hasRecord'],
            'likeStatus' => $likeData['likeStatus'],
            'wallRecordId' => $wallRecordId,
            'notificationInfo' => $notificationInfoFormatted
        );

        $response = ResponseBuilder::BuildResponse(null, null, $receiverId, null, $eventData, $session->get('sessionData'));

        return new Response(json_encode($response));
    }

    public function replyToWallRecordAction()
    {
        $success = true;
        $error = '';
        $response = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();
        $sessionData = $session->get('sessionData');
        $page = $request->get('page');
        $receiverId = $request->get('receiverId');
        $wallRecordId = $request->get('wallRecordId');
        $parentReplyId = $request->get('parentReplyId');
        $replyText = $request->get('replyText');
        $senderId = $session->get('sessionData')['userInfo']['id'];

        $wallService = WallService::getInstance();
        $wallRecordReply = $wallService->replyToWallRecord($wallRecordId, $parentReplyId, $senderId, $replyText);

        // TODO: Add localization
        $notificationInfo = 'User <span class = "author">%s %s</span> has commented your wall record!';
        $notificationInfoFormatted = sprintf($notificationInfo, $sessionData['userInfo']['firstName'],
            $sessionData['userInfo']['lastName']);

        $eventData = array
        (
            'success' => $success,
            'page' => $page,
            'event' => 'replyToWallRecord',
            'messageText' => $replyText,
            'wallRecordReplyId' => $wallRecordReply->getReplyId(),
            'wallRecordId' => $wallRecordId,
            'notificationInfo' => $notificationInfoFormatted
        );

        $response = ResponseBuilder::BuildResponse(null, null, $receiverId, null, $eventData, $session->get('sessionData'));

        return new Response(json_encode($response));
    }

    public function ratePhotoAction()
    {
        $success = true;
        $error = '';
        $response = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();
        $sessionData = $session->get('sessionData');
        $page = $request->get('page');
        $receiverId = $request->get('receiverId');
        $rating = $request->get('rating');
        $photoId = $request->get('photoId');
        $senderId = $session->get('sessionData')['userInfo']['id'];

        $photoService = PhotoService::getInstance();
        $photoService->ratePicture($photoId, $senderId, $rating);
        $averageRating = $photoService->getPictureAverageRating($photoId);

        // TODO: Add localization
        $notificationInfo = 'User <span class = "author">%s %s</span> has rated your photo!';
        $notificationInfoFormatted = sprintf($notificationInfo, $sessionData['userInfo']['firstName'],
            $sessionData['userInfo']['lastName']);

        $eventData = array
        (
            'success' => $success,
            'page' => $page,
            'event' => 'ratePhoto',
            'messageText' => '',
            'photoId' => $photoId,
            'averageRating' => $averageRating,
            'notificationInfo' => $notificationInfoFormatted
        );

        $response = ResponseBuilder::BuildResponse(null, null, $receiverId, null, $eventData, $session->get('sessionData'));

        return new Response(json_encode($response));
    }

}