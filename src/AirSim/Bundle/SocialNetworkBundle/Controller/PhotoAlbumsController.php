<?php
/**
 * Created by Andrei Jakupson
 * Date: 14.03.15
 * Time: 17:55
 */

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use AirSim\Bundle\CoreBundle\Security\PrivilegeChecker;
use AirSim\Bundle\CoreBundle\Services\FileService;
use AirSim\Bundle\CoreBundle\Services\PhotoAlbumsService;
use AirSim\Bundle\CoreBundle\Services\PhotoService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AirSim\Bundle\CoreBundle\Tools\Constants;

class PhotoAlbumsController extends Controller {

    public function photoAlbumsAction($contactId) {
        $LOG = $this->get('logger');
        $LOG->info('photosAction executed in PhotosController');

        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $userId = $session->get('sessionData')['userInfo']['id'];

        if($contactId == null) {
            $contactId = $userId;
        }

        $privilegeChecker = PrivilegeChecker::getInstance();
        $userPrivileges = $privilegeChecker->getUserPrivileges($userId);

        $photoAlbumsService = PhotoAlbumsService::getInstance();
        $albumsAndPhotos = $photoAlbumsService->getAlbumsAndPhotosData($contactId);

        return $this->render('AirSimSocialNetworkBundle:blue/Photos:photo_albums.html.twig',
            array('userPrivileges' => $userPrivileges, 'albumsAndPhotos' => $albumsAndPhotos));
    }

    /* AJAX Calls */
    public function createAlbumAction() {
        $error = '';
        $success = true;

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();
        $userId = $session->get('sessionData')['userInfo']['id'];
        $albumName = $request->get('albumTitle');

        $photoAlbumsService = PhotoAlbumsService::getInstance();
        $newAlbum = $photoAlbumsService->createAlbum($userId, $albumName, $albumName);

        $response = array("success" => $success, "error" => $error,
            "data" => array("newAlbumId" => $newAlbum['newAlbumId'], "newAlbumName" => $newAlbum['newAlbumTitle']));
        return new Response(json_encode($response));

    }

    public function deleteAlbumAction() {

        $error = '';
        $success = true;

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();
        $userId = $session->get('sessionData')['userInfo']['id'];
        $albumId = $request->get('albumId');

        $photoAlbumsService = PhotoAlbumsService::getInstance();
        $photoAlbumsService->deleteAlbum($userId, $albumId);

        $response = array("success" => $success, "error" => $error);
        return new Response(json_encode($response));
    }

    public function savePhotosAction() {

        $LOG = $this->get('logger');
        $LOG->info('savePhotosAction executed in PhotoAlbumsController');

        $error = '';
        $success = true;
        $response = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();
        $userId = $session->get('sessionData')['userInfo']['id'];
        $albumId = $request->get('albumId');
        $albumTitle = $request->get('albumTitle');
        $attachedPictures = $request->get('attachedPictures');

        $photoService = PhotoService::getInstance();
        $uploadedPictures = $photoService->savePhotos($userId, $albumId, $albumTitle, $attachedPictures, $this);

        $response = array("success" => $success, "error" => $error, 'data' => $uploadedPictures);
        return new Response(json_encode($response));
    }

    /*public function commentPhotoAction()
    {
        $success = false;
        $error = '';

        $request = $this->getRequest();
        $photoId = $request->get('photoId');
        $comment = $request->get('comment');
        $session = $request->getSession();
        $userId = $session->get('user_id');

        $entityManager = $this->getDoctrine()->getManager();
        $commentEntity = new TabPhotoComments();
        $commentEntity->setUserId($userId);
        $commentEntity->setPhotoId($photoId);
        $commentEntity->setComment($comment);
        $now = new \DateTime();
        $commentEntity->setDateAdded($now);
        $entityManager->persist($commentEntity);
        $entityManager->flush();

        $userData = array
        (
            'userName' => $session->get('user_name'),
            'userFamily' => $session->get('user_family'),
            'userPic' => $session->get('user_pic')
        );

        $response = array("success" => $success, "error" => $error, "data" => array("userData" => $userData, "dateTimeAdded" => $now));
        return new Response(json_encode($response));
    }*/

    public function saveCroppedProfilePictureAction() {

        $success = true;
        $error = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $x_axis = $request->get('x_axis');
        $x2_axis = $request->get('x2_axis');
        $y_axis = $request->get('y_axis');
        $y2_axis = $request->get('y2_axis');
        $thumb_width = $request->get('thumb_width');
        $thumb_height = $request->get('thumb_height');
        $imageName = $request->get('imageName');

        $session = $request->getSession();
        $userId = $session->get('sessionData')['userInfo']['id'];

        $uploadDirectory = sprintf(Constants::USER_ALBUM_PICTURE_PATH, $userId, Constants::APP_DEFAULT_PROFILE_PICS_ALBUM_NAME, $imageName);

        $photoService = PhotoService::getInstance();
        $photoService->updateProfilePicture($userId, $x_axis, $x2_axis, $y_axis, $y2_axis, $thumb_width, $thumb_height,
            Constants::TMP_FILES_DIRECTORY_PATH, $uploadDirectory, $imageName);

        $userInfo = $session->get('sessionData')['userInfo'];
        $userInfo['webPic'] = $imageName;

        $session->set('sessionData', array('userInfo' => $userInfo));

        $response = array("success" => $success, "error" => $error, "data" => array("thumbnailPicture" => $imageName));
        return new Response(json_encode($response));
    }

    public function getExtension($str)
    {
        $i = strrpos($str,".");
        if (!$i) { return ""; }
        $l = strlen($str) - $i;
        $ext = substr($str,$i+1,$l);
        return $ext;
    }

    public function deleteDirectory($dirPath)
    {
        $files = glob($dirPath.'*', GLOB_MARK);
        foreach($files as $file)
        {
            /*if(is_dir($file))
                $this->deleteDirectory($file);
            else unlink($file);*/
            unlink($file);
        }
        rmdir($dirPath);
    }
}