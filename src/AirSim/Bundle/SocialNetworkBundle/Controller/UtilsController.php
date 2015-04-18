<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AirSim\Bundle\CoreBundle\Tools\Constants;
use AirSim\Bundle\CoreBundle\Services\FileService;

class UtilsController extends Controller
{

    public function utilsAction(){}

    /* ***** AJAX ***** */
    public function uploadTmpImagesAction() {

        $success = true;
        $error = '';
        $imagesValidFormats = explode(', ', Constants::IMAGES_VALID_FORMATS);

        $request = $this->get('request_stack')->getCurrentRequest();
        $receiverId = $request->get('receiverId');

        $picturesToBeUploaded = $request->files->get('attached_images');
        $uploadedPictures = array();
        if(sizeof($picturesToBeUploaded) > 0)
        {
            $fileService = FileService::getInstance();
            $uploadedPictures = $fileService->uploadFiles($picturesToBeUploaded, $imagesValidFormats, Constants::TMP_FILES_DIRECTORY_PATH,
                $receiverId);
        }

        $response = array(
            'success' => $success,
            'uploadedPictures' => $uploadedPictures
        );

        return new Response(json_encode($response));
    }

    public function deleteTmpImageAction() {

        $success = true;
        $error = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $pictureToBeDeleted = $request->get('imageToDelete');

        $fileService = FileService::getInstance();
        $fileService->deleteFile(Constants::TMP_FILES_DIRECTORY_PATH.$pictureToBeDeleted);

        $response = array(
            'success' => $success
        );

        return new Response(json_encode($response));
    }
}