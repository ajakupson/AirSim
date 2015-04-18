<?php
/**
 * Created by Andrei Jakupson
 * Date: 14.03.15
 * Time: 18:00
 */

namespace AirSim\Bundle\CoreBundle\Services;

use AirSim\Bundle\CoreBundle\AirSimCoreBundle;
use AirSim\Bundle\CoreBundle\Entity\UserPhotoAlbums;
use AirSim\Bundle\CoreBundle\Tools\Constants;
use AirSim\Bundle\CoreBundle\Tools\Functions;
use AirSim\Bundle\CoreBundle\Services\FileService;

class PhotoAlbumsService
{
    private static $photoAlbumsServiceInstance = null;

    // Dependencies
    private $entityManager = null;
    private $userPhotoAlbumsRepository = null;
    private $photosRepository = null;
    private $userRepository = null;
    private $fileService = null;

    private function __construct() {
        $this->entityManager = AirSimCoreBundle::getContainer()->get('doctrine')->getManager();
        $this->userPhotoAlbumsRepository = $this->entityManager->getRepository('AirSimCoreBundle:UserPhotoAlbums');
        $this->userPhotosRepository = $this->entityManager->getRepository('AirSimCoreBundle:UserPhotos');
        $this->userRepository = $this->entityManager->getRepository('AirSimCoreBundle:User');
        $this->fileService = FileService::getInstance();
    }

    public static function getInstance() {

        if(is_null(self::$photoAlbumsServiceInstance)) {
            self::$photoAlbumsServiceInstance = new self();
        }

        return self::$photoAlbumsServiceInstance;
    }

    public function getAlbumsAndPhotosData($userId) {
        $userAlbumsAndPhotos = array();

        $albums = $this->getUserAlbums($userId);
        foreach($albums as $album) {

            $albumId = $album->getAlbumId();
            $photos = $this->getAlbumPhotos($albumId);

            $userAlbumsAndPhotos[] = array(
                'album' => $album,
                'photos' => $photos
            );
        }

        return $userAlbumsAndPhotos;

    }

    public function getUserAlbums($userId) {
        $albumEntities = $this->userPhotoAlbumsRepository->findBy(array('userId' => $userId));

        return $albumEntities;
    }

    public function getAlbumPhotos($albumId) {
        $photoEntities = $this->userPhotosRepository->findBy(array('albumId' => $albumId), array('dateAdded' => 'DESC'), 7, 0);

        return $photoEntities;
    }

    public function createAlbum($userId, $albumName, $albumDescription) {

        $userEntity = $this->userRepository->findOneByUserId($userId);

        $albumToLatinName = Functions::CyrillicToLatin($albumName);
        $albumTitle = preg_replace('/\s+/', '_', $albumToLatinName);

        $albumEntity = new UserPhotoAlbums();
        $albumEntity->setUser($userEntity);
        $albumEntity->setAlbumName($albumTitle);
        $albumEntity->setAlbumTitle($albumName);
        $albumEntity->setAlbumDescription($albumDescription);

        $dir = Constants::USER_FILES_DIRECTORY_PATH.$userId.'/albums/'.$albumTitle;
        mkdir($dir);

        $this->entityManager->persist($albumEntity);
        $this->entityManager->flush();

        $newAlbumId = $albumEntity->getAlbumId();

        return array(
            'newAlbumId' => $newAlbumId,
            'newAlbumTitle' => $albumTitle);

    }

    public function deleteAlbum($userId, $albumId) {

        $albumEntity = $this->userPhotoAlbumsRepository->findOneByAlbumId($albumId);

        $dir = Constants::USER_FILES_DIRECTORY_PATH.$userId.'/albums/'.$albumEntity->getAlbumTitle().'/';
        $this->fileService->deleteFile($dir);

        $this->entityManager->remove($albumEntity);
        $this->entityManager->flush();
    }

    public function getAlbumById($albumId) {
        return $this->userPhotoAlbumsRepository->findOneByAlbumId($albumId);
    }

    /**
     * @param null $userRepository
     */
    public function setUserRepository($userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return null
     */
    public function getUserRepository()
    {
        return $this->userRepository;
    }



}