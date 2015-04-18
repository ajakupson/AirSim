<?php

namespace AirSim\Bundle\CoreBundle\Services;

use AirSim\Bundle\CoreBundle\AirSimCoreBundle;
use AirSim\Bundle\CoreBundle\DataTransferObjects\CommentDTO;
use AirSim\Bundle\CoreBundle\DataTransferObjects\PhotoDTO;
use AirSim\Bundle\CoreBundle\Entity\PhotoRatings;
use AirSim\Bundle\CoreBundle\Tools\Constants;
use AirSim\Bundle\CoreBundle\Services\FileService;
use AirSim\Bundle\CoreBundle\Entity\UserPhotos;

class PhotoService {
    private static $photoServiceInstance;

    // dependencies
    private $entityManager = null;
    private $photosRepository = null;
    private $photoRatingsRepository = null;
    private $photoAlbumsService = null;
    private $fileService;

    private function __construct() {
        $this->entityManager = AirSimCoreBundle::getContainer()->get('doctrine')->getManager();
        $this->photosRepository = $this->entityManager->getRepository('AirSimCoreBundle:UserPhotos');
        $this->photoRatingsRepository = $this->entityManager->getRepository('AirSimCoreBundle:PhotoRatings');
        $this->photoAlbumsService = PhotoAlbumsService::getInstance();
        $this->fileService = FileService::getInstance();
    }

    public static function getInstance() {
        if(is_null(self::$photoServiceInstance)) {
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

    public function getPhotoData($photoId, $userId)
    {
        $query = $this->entityManager->createQueryBuilder();
        $query
            ->select('photo,
                      photoAlbums.albumName,
                      photoAlbums.albumTitle,
                      user.userId,
                      AVG(averageRating.rating) AS averageRatio,
                      userRating.rating AS userRated')
            ->from('AirSimCoreBundle:UserPhotos', 'photo')
            ->innerJoin('AirSimCoreBundle:UserPhotoAlbums', 'photoAlbums', 'WITH', 'photo.albumId = photoAlbums.albumId')
            ->innerJoin('AirSimCoreBundle:User', 'user', 'WITH', 'photoAlbums.userId = user.userId')
            ->leftJoin('AirSimCoreBundle:PhotoRatings', 'averageRating', 'WITH', 'averageRating.photoId = photo.photoId')
            ->leftJoin('AirSimCoreBundle:PhotoRatings', 'userRating', 'WITH', 'userRating.photoId = photo.photoId AND userRating.userId = :userId')
            ->where('photo.photoId = :photoId')
            ->setParameter('photoId', $photoId)
            ->setParameter('userId', $userId);
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

    public function getPhotoDTO($photoId, $offset = null, $limit = null, $userId)
    {
        $photoData = $this->getPhotoData($photoId, $userId);
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
        $photoDTO->setAverageRating($photoData['averageRatio']);
        $photoDTO->setUserRated($photoData['userRated']);

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

    public function ratePicture($pictureId, $senderId, $rating)
    {
        $photoRating = $this->photoRatingsRepository->findOneBy(array('photoId' => $pictureId, 'userId' => $senderId));

        if($photoRating == null)
        {
            $photoToRate = $this->photosRepository->findOneByPhotoId($pictureId);

            $photoRating = new PhotoRatings();
            $photoRating->setPhoto($photoToRate);
            $photoRating->setUserId($senderId);
            $photoRating->setRating($rating);
            $photoRating->setDateRated(new \DateTime());
        }
        else
        {
            $photoRating->setRating($rating);
        }

        $this->entityManager->persist($photoRating);
        $this->entityManager->flush();

        return $photoRating;
    }

    public function getPictureAverageRating($pictureId)
    {
        $query = $this->entityManager->createQueryBuilder();
        $query
            ->select('AVG(photoRating.rating) AS pictureAverageRating')
            ->from('AirSimCoreBundle:PhotoRatings', 'photoRating')
            ->where('photoRating.photoId = :photoId')
            ->setParameter('photoId', $pictureId);
        $averageRating = $query->getQuery()->getResult();

        return $averageRating[0]['pictureAverageRating'];
    }

    public function savePhotos($userId, $albumId, $albumTitle, $attachedPictures, $diContainer) {

        $userAlbumDirectory = Constants::USER_FILES_DIRECTORY_PATH.$userId.'/albums/'.$albumTitle.'/';
        $albumEntity = $this->photoAlbumsService->getAlbumById($albumId);
        $dateTime = new \DateTime();
        $uploadedPictures = array();
        global $kernel;

        foreach($attachedPictures as $picture) {

            $this->fileService->moveFile(Constants::TMP_FILES_DIRECTORY_PATH.$picture, $userAlbumDirectory.$picture);

            $imagePath = sprintf(Constants::IMAGINE_FILE_PATH, $userId, $albumTitle, $picture);
            $cachedImagePath = sprintf(Constants::IMAGINE_CACHE_PATH, Constants::IMAGE_FILTER_LAST_PHOTO, $userId, $albumTitle, $picture);
            $this->LiipWriteThumbnailImage($imagePath, $cachedImagePath, Constants::IMAGE_FILTER_LAST_PHOTO, $diContainer);

            $userPicture = new UserPhotos();
            $userPicture->setAlbum($albumEntity);
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
            $pictureData['name'] = $picture;
            $pictureData['userId'] = $userId;

            $uploadedPictures[] = $pictureData;
        }

        $this->entityManager->flush();

        return $uploadedPictures;
    }

    /**
     * Write a thumbnail image using the LiipImagineBundle
     *
     * @param string $fullSizeImgWebPath path where full size upload is stored e.g. uploads/attachments
     * @param string $thumbAbsPath full absolute path to attachment directory e.g. /var/www/projectName/images/thumbs/
     * @param string $filter filter defined in config e.g. my_thumb
     * @param Object $diContainer Dependency Injection Object, if calling from controller just pass $this
     */
    public function LiipWriteThumbnailImage($fullSizeImgWebPath, $thumbAbsPath, $filter, $diContainer) {

        $container = $diContainer;                                       // the DI container, if keeping this function in controller just use $container = $this
        $dataManager = $container->get('liip_imagine.data.manager');     // the data manager service
        $filterManager = $container->get('liip_imagine.filter.manager'); // the filter manager service
        $image = $dataManager->find($filter, $fullSizeImgWebPath);       // find the image and determine its type
        $response = $filterManager->applyFilter($image, $filter);

        $thumb = $response->getContent();                                // get the image from the response

        $f = fopen($thumbAbsPath, 'w');                                  // create thumbnail file
        fwrite($f, $thumb);                                              // write the thumbnail
        fclose($f);                                                      // close the file
    }

    public function cropAndSaveImage($xAxis, $x2Axis, $yAxis, $y2Axis, $thumbWidth, $thumbHeight, $fromDirectory, $toDirectory,
        $imageName) {

        $tWidth = Constants::MAX_PROFILE_PIC_WIDTH; // Maximum thumbnail width
        $tHeight = Constants::MAX_PROFILE_PIC_HEIGHT; // Maximum thumbnail height

        $ratio = ($tWidth / $thumbWidth);
        $newW = ceil($thumbWidth * $ratio);
        $newH = ceil($thumbHeight * $ratio);
        $newImg = imagecreatetruecolor($newW ,$newH);
        $imageSrc = imagecreatefromjpeg($fromDirectory.$imageName);
        imagecopyresampled($newImg, $imageSrc, 0, 0, $xAxis, $yAxis, $newW, $newH, $thumbWidth, $thumbHeight);
        imagejpeg($newImg, $toDirectory, 90);
    }

    public function updateProfilePicture($userId, $xAxis, $x2Axis, $yAxis, $y2Axis, $thumbWidth, $thumbHeight, $fromDirectory, $toDirectory,
        $imageName) {

        $this->cropAndSaveImage($xAxis, $x2Axis, $yAxis, $y2Axis, $thumbWidth, $thumbHeight, $fromDirectory, $toDirectory,
            $imageName);

        $userEntity = $this->photoAlbumsService->getUserRepository()->findOneByUserId($userId);
        /*if(sizeof($userEntity) != 0) {
            $userEntity->setWebProfilePic($imageName);
            $this->entityManager->persist($userEntity);
            $this->entityManager->flush();
        }*/
    }
}