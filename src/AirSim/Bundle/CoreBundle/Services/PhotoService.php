<?php

namespace AirSim\Bundle\CoreBundle\Services;

use AirSim\Bundle\CoreBundle\AirSimCoreBundle;
use AirSim\Bundle\CoreBundle\DataTransferObjects\PhotoDTO;
use AirSim\Bundle\CoreBundle\DataTransferObjects\CommentDTO;

class PhotoService
{
    private static $photoServiceInstance;

    // dependencies
    private $entityManager = null;
    private $photosRepository = null;

    private function __construct()
    {
        $this->entityManager = AirSimCoreBundle::getContainer()->get('doctrine')->getManager();
        $this->photosRepository = $this->entityManager->getRepository('AirSimCoreBundle:UserPhotos');
    }

    public static function getInstance()
    {
        if(is_null(self::$photoServiceInstance))
        {
            self::$photoServiceInstance = new self();
        }
        return self::$photoServiceInstance;
    }

    public function getUserPhotos($userId, $offset = null, $limit = null)
    {
        $query = $this->entityManager->createQueryBuilder();
        $query
            ->select(array('photos', 'photoAlbums.albumName'))
            ->from('AirSimCoreBundle:UserPhotos', 'photos')
            ->innerJoin('AirSimCoreBundle:UserPhotoAlbums', 'photoAlbums', 'WITH', 'photos.albumId = photoAlbums.albumId')
            ->where('photoAlbums.userId = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('photos.dateAdded', 'DESC')
            ->setMaxResults($limit);

        $photos = $query->getQuery()->getResult();

        return $photos;
    }

    public function getPhotoData($photoId)
    {
        $query = $this->entityManager->createQueryBuilder();
        $query
            ->select('photo, photoAlbums.albumName, photoAlbums.albumTitle, user.userId')
            ->from('AirSimCoreBundle:UserPhotos', 'photo')
            ->innerJoin('AirSimCoreBundle:UserPhotoAlbums', 'photoAlbums', 'WITH', 'photo.albumId = photoAlbums.albumId')
            ->innerJoin('AirSimCoreBundle:User', 'user', 'WITH', 'photoAlbums.userId = user.userId')
            ->andWhere('photo.photoId = :photoId')
            ->setParameter('photoId', $photoId);
        $photoData = $query->getQuery()->getResult();

        return $photoData[0];
    }

    public function getPhotoComments($photoId, $offset = null, $limit = null)
    {
        $query = $this->entityManager->createQueryBuilder();
        $query
            ->select('comment.commentId', 'comment.comment', 'comment.dateAdded', 'author.userId', 'author.firstName',
                'author.lastName', 'author.login', 'author.webProfilePic')
            ->from('AirSimCoreBundle:PhotoComments', 'comment')
            ->innerJoin('AirSimCoreBundle:User', 'author', 'WITH', 'comment.userId = author.userId')
            ->where('comment.photoId = :photoId')
            ->setParameter('photoId', $photoId);
        $comments = $query->getQuery()->getResult();

        return $comments;
    }

    public function getAlbumPreviousPhoto($albumId, $photoId)
    {
        $query = $this->entityManager->createQueryBuilder();
        $query
            ->select('photo.photoId')
            ->from('AirSimCoreBundle:UserPhotos', 'photo')
            ->where('photo.albumId = :albumId')
            ->andWhere('photo.photoId < :photoId')
            ->setParameter('albumId', $albumId)
            ->setParameter('photoId', $photoId)
            ->orderBy('photo.photoId', 'DESC')
            ->setMaxResults(1);
        $previousPhotoId = $query->getQuery()->getResult();
        if($previousPhotoId != null)
            $previousPhotoId = $previousPhotoId[0]['photoId'];

        return $previousPhotoId;
    }

    public function getAlbumNextPhoto($albumId, $photoId)
    {
        $query = $this->entityManager->createQueryBuilder();
        $query
            ->select('photo.photoId')
            ->from('AirSimCoreBundle:UserPhotos', 'photo')
            ->where('photo.albumId = :albumId')
            ->andWhere('photo.photoId > :photoId')
            ->setParameter('albumId', $albumId)
            ->setParameter('photoId', $photoId)
            ->orderBy('photo.photoId', 'ASC')
            ->setMaxResults(1);
        $nextPhotoId = $query->getQuery()->getResult();
        if($nextPhotoId != null)
            $nextPhotoId = $nextPhotoId[0]['photoId'];

        return $nextPhotoId;
    }

    public function getPhotoDTO($photoId, $offset = null, $limit = null)
    {
        $photoData = $this->getPhotoData($photoId);
        $albumId = $photoData[0]->getAlbumId();
        $photoComments = $this->getPhotoComments($photoId, $offset, $limit);
        $previousPhotoId = $this->getAlbumPreviousPhoto($albumId, $photoId);
        $nextPhotoId = $this->getAlbumNextPhoto($albumId, $photoId);

        $photoDTO = new PhotoDTO();
        $photoDTO->setUserId($photoData['userId']);
        $photoDTO->setAlbumId($albumId);
        $photoDTO->setAlbumName($photoData['albumName']);
        $photoDTO->setPhotoId($photoData['albumTitle']);
        $photoDTO->setPhotoName($photoData[0]->getPhotoName());
        $photoDTO->setPhotoTitle($photoData[0]->getPhotoTitle());
        $photoDTO->setPhotoDescription($photoData[0]->getPhotoDescription());
        $photoDTO->setPhotoDateAdded($photoData[0]->getDateAdded()->format('d.m.Y'));
        $photoDTO->setIsPhotoCover($photoData[0]->getIsCover());
        $photoDTO->setLatitude($photoData[0]->getLatitude());
        $photoDTO->setLongitude($photoData[0]->getLongitude());
        $photoDTO->setAddress($photoData[0]->getAddress());
        $photoDTO->setPreviousPhotoId($previousPhotoId);
        $photoDTO->setNextPhotoId($nextPhotoId);

        $commentDTOs = array();
        foreach($photoComments as $comment)
        {
            $commentDTO = new CommentDTO();
            $commentDTO->setCommentId($comment['commentId']);
            $commentDTO->setCommentText($comment['comment']);
            $commentDTO->setCommentDateAdded($comment['dateAdded']->format('d.m.Y'));
            $commentDTO->setAuthorId($comment['userId']);
            $commentDTO->setAuthorName($comment['firstName']);
            $commentDTO->setAuthorFamily($comment['lastName']);
            $commentDTO->setAuthorLogin($comment['login']);
            $commentDTO->setAuthorWebProfilePic($comment['webProfilePic']);

            $commentDTOs[] = $commentDTO->expose();
        }
        $photoDTO->setPhotoComments($commentDTOs);

        return $photoDTO;
    }

    public function getWallRecordPictures($wallRecordId)
    {
        $query = $this->entityManager->createQueryBuilder();
        $query
            ->select('pictures')
            ->from('AirSimCoreBundle:UserPhotos', 'pictures')
            ->innerJoin('AirSimCoreBundle:WallRecordPictures', 'wallRecordPictures', 'WITH', 'wallRecordPictures.pictureId = pictures.photoId')
            ->where('wallRecordPictures.wallRecId = :wallRecId')
            ->setParameter('wallRecId', $wallRecordId)
            ->orderBy('pictures.dateAdded', 'DESC');

        $pictures = $query->getQuery()->getResult();

        return $pictures;
    }
}