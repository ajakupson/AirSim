<?php
/**
 * Created by Andrei Jakupson
 * Date: 14.03.15
 * Time: 18:00
 */

namespace AirSim\Bundle\CoreBundle\Services;

use AirSim\Bundle\CoreBundle\AirSimCoreBundle;

class PhotoAlbumsService
{
    private static $photoAlbumsServiceInstance = null;

    // Dependencies
    private $entityManager = null;
    private $userPhotoAlbumsRepository = null;
    private $photosRepository = null;

    private function __construct()
    {
        $this->entityManager = AirSimCoreBundle::getContainer()->get('doctrine')->getManager();
        $this->userPhotoAlbumsRepository = $this->entityManager->getRepository('AirSimCoreBundle:UserPhotoAlbums');
        $this->userPhotosRepository = $this->entityManager->getRepository('AirSimCoreBundle:UserPhotos');
    }

    public static function getInstance()
    {
        if(is_null(self::$photoAlbumsServiceInstance))
        {
            self::$photoAlbumsServiceInstance = new self();
        }

        return self::$photoAlbumsServiceInstance;
    }

    public function getAlbumsAndPhotosData($userId)
    {
        $userAlbumsAndPhotos = array();

        $albums = $this->getUserAlbums($userId);
        foreach($albums as $album)
        {
            $albumId = $album->getAlbumId();
            $photos = $this->getAlbumPhotos($albumId);

            $userAlbumsAndPhotos[] = array
            (
                'album' => $album,
                'photos' => $photos
            );
        }

        return $userAlbumsAndPhotos;

    }

    public function getUserAlbums($userId)
    {
        $albumEntities = $this->userPhotoAlbumsRepository->findBy(array('userId' => $userId));

        return $albumEntities;
    }

    public function getAlbumPhotos($albumId)
    {
        $photoEntities = $this->userPhotosRepository->findBy(array('albumId' => $albumId), array('dateAdded' => 'DESC'), 7, 0);

        return $photoEntities;
    }

}