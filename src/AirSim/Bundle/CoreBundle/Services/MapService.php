<?php

namespace AirSim\Bundle\CoreBundle\Services;

use AirSim\Bundle\CoreBundle\AirSimCoreBundle;
use AirSim\Bundle\CoreBundle\DataTransferObjects\MarkerDTO;
use AirSim\Bundle\CoreBundle\DataTransferObjects\UserWebDataDTO;
use AirSim\Bundle\CoreBundle\Entity\UserMarker;

class MapService
{
    private static $mapServiceInstance;

    // Dependencies
    private $entityManager = null;
    private $userMarkerRepository = null;
    private $userRepository;

    private function __construct()
    {
        $this->entityManager = AirSimCoreBundle::getContainer()->get('doctrine')->getManager();
        $this->userMarkerRepository = $this->entityManager->getRepository('AirSimCoreBundle:UserMarker');
        $this->userRepository = $this->entityManager->getRepository('AirSimCoreBundle:User');
    }

    public static function getInstance()
    {
        if(is_null(self::$mapServiceInstance))
        {
            self::$mapServiceInstance = new self();
        }
        return self::$mapServiceInstance;
    }

    public function setUserMarker($userId, $address, $latitude, $longitude, $comment)
    {
        $userMarker = $this->userMarkerRepository->findOneBy(array('userId' => $userId));
        if($userMarker == null)
        {
            $userMarker = new UserMarker();
            $user = $this->userRepository->findOneByUserId($userId);
            $userMarker->setUser($user);
        }

        $currentDateTime = new \DateTime();

        $userMarker->setLatitude($latitude);
        $userMarker->setLongitude($longitude);
        $userMarker->setAddress($address);
        $userMarker->setComment($comment);
        $userMarker->setDateMarked($currentDateTime);
        $userMarker->setIsActive(true);

        $this->entityManager->persist($userMarker);
        $this->entityManager->flush();

        // Build userMarkerDTO
        $userMarkerDTO = $this->buildUserMarkerDTO($latitude, $longitude, $address, $comment, $currentDateTime, $userId);

        return $userMarkerDTO;
    }

    public function getUserMarker($userId)
    {
        // TODO: change
        $userMarker = $this->userMarkerRepository->findOneBy(array('userId' => $userId, 'isActive' => 1));

        $userMarkerDTO = new MarkerDTO();
        if(sizeof($userMarker) == 1)
        {
            $userMarkerDTO->setAddress($userMarker->getAddress());
            $userMarkerDTO->setLatitude($userMarker->getLatitude());
            $userMarkerDTO->setLongitude($userMarker->getLongitude());
            $userMarkerDTO->setComment($userMarker->getComment());
            $userMarkerDTO->setDateAdded($userMarker->getDateMarked()->format('m.d.Y h:i'));
        }

        return $userMarkerDTO;

    }

    public function getFriendsMarkers($userId)
    {
        $query = $this->entityManager->createQueryBuilder();
        $query
            ->select('friendMarker,
                      friend.userId,
                      friend.firstName,
                      friend.lastName,
                      friend.gender,
                      friend.webProfilePic')
            ->from('AirSimCoreBundle:UserMarker', 'friendMarker')
            ->innerJoin('AirSimCoreBundle:User', 'friend', 'WITH', 'friend.userId = friendMarker.userId')
            ->innerJoin('AirSimCoreBundle:UserFriends', 'userFriend', 'WITH', 'userFriend.friendId = friend.userId')
            ->where('userFriend.userId = :userId')
            ->setParameter('userId', $userId);

        $friendsMarkers = $query->getQuery()->getResult();

        $markersDTOs = array();
        foreach($friendsMarkers as $marker)
        {
            $markersDTOs[] = $this->buildByParametersUserMarkerDTO($marker[0]->getLatitude(), $marker[0]->getLongitude(),
                '', $marker[0]->getComment(), $marker[0]->getDateMarked()->format('d.m.Y h:i'),
                $marker['userId'], $marker['firstName'], $marker['lastName'], $marker['gender'], $marker['webProfilePic']);
        }

        return $markersDTOs;
    }

    /* ********* Private functions ********** */
    private function buildUserMarkerDTO($latitude, $longitude, $address, $comment, $currentDateTime, $userId)
    {
        $user = $this->userRepository->findOneByUserId($userId);

        $userMarkerDTO = new MarkerDTO();
        $userMarkerDTO->setAddress($address);
        $userMarkerDTO->setLatitude($latitude);
        $userMarkerDTO->setLongitude($longitude);
        $userMarkerDTO->setComment($comment);
        $userMarkerDTO->setDateAdded($currentDateTime->format('m.d.Y h:i'));

        if($user != null)
        {
            $userWebDataDTO = new UserWebDataDTO();
            $userWebDataDTO->setUserId($user->getUserId());
            $userWebDataDTO->setName($user->getFirstName());
            $userWebDataDTO->setFamily($user->getLastName());
            $userWebDataDTO->setGender($user->getGender());
            $userWebDataDTO->setWebProfilePic($user->getWebProfilePic());

            $userMarkerDTO->setUserWebData($userWebDataDTO->expose());
        }

        return $userMarkerDTO;
    }

    private function buildByParametersUserMarkerDTO($latitude, $longitude, $address, $comment, $currentDateTime, $userId, $firstName,
        $lastName, $gender, $webProfilePic)
    {

        $userMarkerDTO = new MarkerDTO();
        $userMarkerDTO->setAddress($address);
        $userMarkerDTO->setLatitude($latitude);
        $userMarkerDTO->setLongitude($longitude);
        $userMarkerDTO->setComment($comment);
        $userMarkerDTO->setDateAdded($currentDateTime);

        $userWebDataDTO = new UserWebDataDTO();
        $userWebDataDTO->setUserId($userId);
        $userWebDataDTO->setName($firstName);
        $userWebDataDTO->setFamily($lastName);
        $userWebDataDTO->setGender($gender);
        $userWebDataDTO->setWebProfilePic($webProfilePic);

        $userMarkerDTO->setUserWebData($userWebDataDTO->expose());

        return $userMarkerDTO->expose();
    }

}