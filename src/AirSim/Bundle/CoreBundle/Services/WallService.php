<?php

namespace AirSim\Bundle\CoreBundle\Services;

use AirSim\Bundle\CoreBundle\AirSimCoreBundle;
use AirSim\Bundle\CoreBundle\Entity\UserPhotos;
use AirSim\Bundle\CoreBundle\Entity\UserWallRecords;
use AirSim\Bundle\CoreBundle\Entity\WallRecordPictures;
use AirSim\Bundle\CoreBundle\Tools\Constants;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Validator\Constraints\DateTime;

class WallService
{
    private static $wallServiceInstance = null;

    // Dependencies
    private $entityManager = null;
    private $userWallRecordsRepository = null;
    private $wallRecordPicturesRepository = null;
    private $wallRecordLikesRepository = null;
    private $userRepository = null;

    private function __construct()
    {
        $this->entityManager = AirSimCoreBundle::getContainer()->get('doctrine')->getManager();
        $this->userWallRecordsRepository = $this->entityManager->getRepository('AirSimCoreBundle:UserWallRecords');
        $this->wallRecordPicturesRepository = $this->entityManager->getRepository('AirSimCoreBundle:WallRecordPictures');
        $this->wallRecordLikesRepository = $this->entityManager->getRepository('AirSimCoreBundle:WallRecordLikes');
        $this->userRepository = $this->entityManager->getRepository('AirSimCoreBundle:User');
    }

    public static function getInstance()
    {
        if(is_null(self::$wallServiceInstance))
        {
            self::$wallServiceInstance = new self();
        }
        return self::$wallServiceInstance;
    }

    public function getWallRecords($userId, $offset = null, $limit = null)
    {
        $query = $this->entityManager->createQueryBuilder();

        $query
            ->select('wallRecord,
                      wallRecordAuthor.userId,
                      wallRecordAuthor.login,
                      wallRecordAuthor.firstName,
                      wallRecordAuthor.lastName,
                      wallRecordAuthor.webProfilePic,
                      (
                        SELECT COUNT(wallRecordLikes) AS likes
                        FROM AirSimCoreBundle:WallRecordLikes AS wallRecordLikes
                        WHERE wallRecordLikes.wallRecId = wallRecord.wallRecId
                            AND wallRecordLikes.likeDislike = 1
                      ) AS totalLikes,
                      (
                        SELECT COUNT(wallRecordDislikes) AS dislikes
                        FROM AirSimCoreBundle:WallRecordLikes AS wallRecordDislikes
                        WHERE wallRecordDislikes.wallRecId = wallRecord.wallRecId
                            AND wallRecordDislikes.likeDislike = 0
                      ) AS totalDislikes')
            ->from('AirSimCoreBundle:UserWallRecords', 'wallRecord')
            ->innerJoin('AirSimCoreBundle:User', 'wallRecordAuthor', 'WITH', 'wallRecord.authorId = wallRecordAuthor.userId')
            ->where('wallRecord.toId = :userId')
            ->setParameter('userId', $userId)
            ->setMaxResults($limit)
            ->orderBy('wallRecord.dateAdded', 'DESC');

        $userWallRecords = $query->getQuery()->getResult();

        $photoService = PhotoService::getInstance();
        $counter = 0;
        foreach($userWallRecords as $userWallRecord)
        {
            $userWallRecords[$counter]['wallRecordPictures'] = $photoService->getWallRecordPictures($userWallRecord[0]->getWallRecId());
            $counter++;
        }

        return $userWallRecords;
    }

    public function addWallRecord($toId, $authorId, $text, $attachedPictures)
    {
        $user = $this->userRepository->findOneBy(array('userId' => $toId));
        $dateTime = new \DateTime();

        $wallRecordData = array();

        $wallRecord = new UserWallRecords();
        $wallRecord->setTo($user);
        $wallRecord->setAuthorId($authorId);
        $wallRecord->setRecordText($text);
        $wallRecord->setDateAdded($dateTime);

        $this->entityManager->persist($wallRecord);

        $fileService = FileService::getInstance();
        $albumService = AlbumService::getInstance();
        $userWallPicsAlbum = $albumService->getWallPicsAlbum($toId);
        $userAlbumDirectory = './../web/public_files/user_'.$toId.'/albums/wall_pics/';

        // Images
        $wallRecordData['wallRecordPictures'] = array();
        foreach($attachedPictures as $picture)
        {
            $fileService->moveFile(Constants::TMP_FILES_DIRECTORY_PATH.$picture, $userAlbumDirectory.$picture);

            $userPicture = new UserPhotos();
            $userPicture->setAlbum($userWallPicsAlbum);
            $userPicture->setPhotoName($picture);
            $userPicture->setPhotoTitle(null);
            $userPicture->setPhotoDescription(null);
            $userPicture->setDateAdded($dateTime);
            $userPicture->setIsCover(false);
            $userPicture->setLatitude(null);
            $userPicture->setLongitude(null);
            $this->entityManager->persist($userPicture);
            $this->entityManager->flush();

            $pictureData = array();
            $pictureData['id'] = $userPicture->getPhotoId();
            $pictureData['name'] = $userPicture->getPhotoName();
            $wallRecordData['wallRecordPictures'][] = $pictureData;

            $userWallPicture = new WallRecordPictures();
            $userWallPicture->setPicture($userPicture);
            $userWallPicture->setWallRec($wallRecord);
            $this->entityManager->persist($userWallPicture);
        }

        // Documents

        $this->entityManager->flush();
        $wallRecordData['wallRecord'] = $wallRecord;

        return $wallRecordData;

    }
}