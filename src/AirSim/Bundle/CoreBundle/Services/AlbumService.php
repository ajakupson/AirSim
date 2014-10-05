<?php

namespace AirSim\Bundle\CoreBundle\Services;

use AirSim\Bundle\CoreBundle\AirSimCoreBundle;
use AirSim\Bundle\CoreBundle\Tools\Constants;

class AlbumService
{
    private static $albumServiceInstance;

    // Dependencies
    private $entityManager = null;
    private $userPhotoAlbumsRepository = null;

    public function __construct()
    {
        $this->entityManager = AirSimCoreBundle::getContainer()->get('doctrine')->getManager();
        $this->userPhotoAlbumsRepository = $this->entityManager->getRepository('AirSimCoreBundle:UserPhotoAlbums');
    }

    public static function getInstance()
    {
        if(self::$albumServiceInstance == null)
        {
            self::$albumServiceInstance = new self();
        }
        return self::$albumServiceInstance;
    }

    public function getWallPicsAlbum($userId)
    {
        return $this->userPhotoAlbumsRepository->findOneBy(array('userId' => $userId, 'albumName' => Constants::WALL_PICTURES_ALBUM_NAME));
    }
}