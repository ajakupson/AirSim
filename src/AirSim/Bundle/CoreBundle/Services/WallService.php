<?php

namespace AirSim\Bundle\CoreBundle\Services;

use AirSim\Bundle\CoreBundle\AirSimCoreBundle;
use AirSim\Bundle\CoreBundle\DataTransferObjects\WallRecordReplyDTO;
use AirSim\Bundle\CoreBundle\Entity\UserPhotos;
use AirSim\Bundle\CoreBundle\Entity\UserWallRecords;
use AirSim\Bundle\CoreBundle\Entity\WallRecordLikes;
use AirSim\Bundle\CoreBundle\Entity\WallRecordPictures;
use AirSim\Bundle\CoreBundle\Entity\WallRecordReplies;
use AirSim\Bundle\CoreBundle\Tools\Constants;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\ORM\Tools\Pagination\Paginator;

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
        $this->wallRecordCommentsRepository = $this->entityManager->getRepository('AirSimCoreBundle:WallRecordReplies');
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
                            AND wallRecordDislikes.likeDislike = -1
                      ) AS totalDislikes')
            ->from('AirSimCoreBundle:UserWallRecords', 'wallRecord')
            ->innerJoin('AirSimCoreBundle:User', 'wallRecordAuthor', 'WITH', 'wallRecord.authorId = wallRecordAuthor.userId')
            ->where('wallRecord.toId = :userId')
            ->setParameter('userId', $userId)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy('wallRecord.dateAdded', 'DESC');

        $userWallRecords = $query->getQuery()->getResult();

        $photoService = PhotoService::getInstance();
        $counter = 0;
        foreach($userWallRecords as $userWallRecord)
        {
            $userWallRecords[$counter]['wallRecordPictures'] = $photoService->getWallRecordPictures($userWallRecord[0]->getWallRecId());
            $userWallRecords[$counter]['wallRecordComments'] = $this->getWallRecordComments($userWallRecord[0]->getWallRecId(), 0, Constants::WALL_RECORD_COMMENTS_LIMIT);
            $counter++;
        }

        return $userWallRecords;
    }

    public function getWallRecordComments($wallRecordId, $offset = null, $limit = null)
    {
        $query = $this->entityManager->createQueryBuilder();

        $query
            ->select('wallRecordComment,
                      commentAuthor.userId,
                      commentAuthor.login,
                      commentAuthor.firstName,
                      commentAuthor.lastName,
                      commentAuthor.webProfilePic')
            ->from('AirSimCoreBundle:wallRecordReplies', 'wallRecordComment')
            ->innerJoin('AirSimCoreBundle:User', 'commentAuthor', 'WITH', 'wallRecordComment.authorId = commentAuthor.userId')
            ->where('wallRecordComment.wallRecordId = :wallRecordId')
            ->setParameter('wallRecordId', $wallRecordId)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy('wallRecordComment.dateAdded', 'ASC');

        $wallRecordComments = $query->getQuery()->getResult();

        return $wallRecordComments;
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

        // TODO: Documents

        // TODO: Audios

        // TODO: Videos



        $this->entityManager->flush();
        $wallRecordData['wallRecord'] = $wallRecord;

        return $wallRecordData;

    }

    public function likeDislikeWallRecord($wallRecordId, $userId, $action)
    {
        $userLike = $this->wallRecordLikesRepository->findOneBy(array('wallRecId' => $wallRecordId, 'userId' => $userId));

        $hasLiked = true;
        $hasRecord = false;
        $likeStatus = null;
        $dateTime = new \DateTime();

        if(sizeof($userLike) != 0)
        {
            $hasRecord = true;
            if($userLike->getLikeDislike() == -1 && $action == Constants::LIKE)
            {
                $hasLiked = true;
                $likeStatus = -1;
                $userLike->setLikeDislike(0);
            }
            else if($userLike->getLikeDislike() == 0 && $action == Constants::LIKE)
            {
                $hasLiked = true;
                $likeStatus = 0;
                $userLike->setLikeDislike(1);
            }
            else if($userLike->getLikeDislike() == 1 && $action == Constants::DISLIKE)
            {
                $hasLiked = false;
                $likeStatus = 1;
                $userLike->setLikeDislike(0);
            }
            else if($userLike->getLikeDislike() == 0 && $action == Constants::DISLIKE)
            {
                $hasLiked = false;
                $likeStatus = 0;
                $userLike->setLikeDislike(-1);
            }
            $userLike->setDateRated($dateTime);
            $this->entityManager->persist($userLike);
        }
        else
        {
            $wallRecordLike = new WallRecordLikes();
            $wallRecordLike->setWallRecId($wallRecordId);
            $wallRecordLike->setUserId($userId);
            $wallRecordLike->setDateRated($dateTime);

            if($action == Constants::LIKE)
            {
                $hasLiked = true;
                $wallRecordLike->setLikeDislike(1);
            }
            else if($action == Constants::DISLIKE)
            {
                $hasLiked = false;
                $wallRecordLike->setLikeDislike(-1);
            }

            $this->entityManager->persist($wallRecordLike);
        }

        $this->entityManager->flush();

        $responseData = array
        (
            'hasLiked' => $hasLiked,
            'hasRecord' => $hasRecord,
            'likeStatus' => $likeStatus
        );

        return $responseData;
    }

    public function replyToWallRecord($wallRecordId, $parentReplyId, $authorId, $replyText)
    {
        $relatedWallRecord = $this->userWallRecordsRepository->findOneByWallRecId($wallRecordId);

        $wallRecordReply = new WallRecordReplies();
        $wallRecordReply->setWallRecord($relatedWallRecord);
        $wallRecordReply->setParentReplyId($parentReplyId);
        $wallRecordReply->setAuthorId($authorId);
        $wallRecordReply->setReplyText($replyText);
        $wallRecordReply->setDateAdded(new \DateTime());

        $this->entityManager->persist($wallRecordReply);
        $this->entityManager->flush();

//        $wallRecordReplyDTO = new WallRecordReplyDTO();
//        $wallRecordReplyDTO->setReplyId($wallRecordReply->getRepyId());
//        $wallRecordReplyDTO->setWallRecordId($$wallRecordId);
//        $wallRecordReplyDTO->setParentReplyId($parentReplyId);
//        $wallRecordReplyDTO->setAuthorId($authorId);
//        $wallRecordReplyDTO->setDateAdded($wallRecordReply->getDateAdded()->format('d/m/Y'));
//        $wallRecordReplyDTO->setReplyText($replyText);

        return $wallRecordReply;
    }
}