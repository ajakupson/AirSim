<?php
/**
 * Created by Andrei Jakupson
 * Date: 24.02.15
 * Time: 22:42
 */

namespace AirSim\Bundle\CoreBundle\Services;

use AirSim\Bundle\CoreBundle\AirSimCoreBundle;
use AirSim\Bundle\CoreBundle\DataTransferObjects\ContactUpdatesDTO;
use AirSim\Bundle\CoreBundle\DataTransferObjects\UserWebDataDTO;
use AirSim\Bundle\CoreBundle\Entity\UserFriends;
use AirSim\Bundle\CoreBundle\Entity\User;

class ContactsService
{
    private static $contactsServiceInstance = null;

    // Dependencies
    private $entityManager = null;
    private $userRepository = null;
    private $friendsRepository = null;

    private function __construct()
    {

        $this->entityManager = AirSimCoreBundle::getContainer()->get('doctrine')->getManager();
        $this->userRepository = $this->entityManager->getRepository('AirSimCoreBundle:User');
        $this->friendsRepository = $this->entityManager->getRepository('AirSimCoreBundle:UserFriends');

    }

    public static function getInstance()
    {
        if(is_null(self::$contactsServiceInstance))
        {
            self::$contactsServiceInstance = new self();
        }

        return self::$contactsServiceInstance;
    }

    public function sendFriendRequest($senderId, $receiverId)
    {
        $friendRequestId = null;
        $event = null;

        $dateTime = new \DateTime();

        $isInReceiverList = $this->friendsRepository->findOneBy(array('userId' => $receiverId, 'friendId' => $senderId));
        if(sizeof($isInReceiverList) == 0)
        {
            $wasAlreadySent = $this->friendsRepository->findOneBy(array('userId' => $senderId, 'friendId' => $receiverId));
            if(sizeof($wasAlreadySent) == 0)
            {
                $friendEntity = new UserFriends();

                $friendEntity->setUser($this->userRepository->findOneByUserId($senderId));
                $friendEntity->setFriend($this->userRepository->findOneByUserId($receiverId));
                $friendEntity->setDateAdded($dateTime);
                $friendEntity->setIsAccepted(0);
                $friendEntity->setGroupId(0);

                $this->entityManager->persist($friendEntity);
                $this->entityManager->flush();

                $friendRequestId = $friendEntity->getRecId();
                $event = 'contactAdd';
            }
        }
        else
        {
            // Accept for receiver
            $isInReceiverList->setIsAccepted(1);
            $isInReceiverList->setDateAccepted($dateTime);
            $this->entityManager->persist($isInReceiverList);

            // Create for sender
            $friendEntity = new UserFriends();

            $friendEntity->setUser($this->userRepository->findOneByUserId($senderId));
            $friendEntity->setFriend($this->userRepository->findOneByUserId($receiverId));
            $friendEntity->setDateAdded($dateTime);
            $friendEntity->setIsAccepted(1);
            $friendEntity->setDateAccepted($dateTime);
            $friendEntity->setGroupId(0);

            $this->entityManager->persist($friendEntity);
            $this->entityManager->flush();

            $friendRequestId = $friendEntity->getRecId();
            $event = 'contactAccept';
        }

        $response = array('friendRequestId' => $friendRequestId, 'event' => $event, 'dateTimeAdded' => $dateTime);

        return $response;
    }

    public function getUpdates($userId)
    {
        $contactUpdates = new ContactUpdatesDTO();

        $incomingRequests = $this->getIncomingFriendRequestsUpdates($userId);
        $outgoingRequests = $this->getOutgoingFriendRequestsUpdates($userId);

        $contactUpdates->setIncomingFriendRequestsUpdates($incomingRequests);
        $contactUpdates->setOutgoingFriendRequestsUpdates($outgoingRequests);

        return $contactUpdates;
    }

    public function getIncomingFriendRequestsUpdates($userId)
    {
        $query = $this->entityManager->createQueryBuilder();
        $query
            ->select('contact')
            ->from('AirSimCoreBundle:User', 'contact')
            ->innerJoin('AirSimCoreBundle:UserFriends', 'incomingRequests', 'WITH', 'incomingRequests.userId = contact.userId')
            ->where('incomingRequests.friendId = :userId')
            ->andWhere('incomingRequests.isAccepted = 0')
            ->setParameter('userId', $userId)
            ->orderBy('incomingRequests.dateAdded', 'ASC');

        $incomingRequests = $query->getQuery()->getResult();

        return $this->buildUserWebDataDTOs($incomingRequests);
    }

    public function getOutgoingFriendRequestsUpdates($userId)
    {
        $query = $this->entityManager->createQueryBuilder();
        $query
            ->select('contact')
            ->from('AirSimCoreBundle:User', 'contact')
            ->innerJoin('AirSimCoreBundle:UserFriends', 'outgoingRequests', 'WITH', 'outgoingRequests.friendId = contact.userId')
            ->where('outgoingRequests.userId = :userId')
            ->andWhere('outgoingRequests.isAccepted = 0')
            ->setParameter('userId', $userId)
            ->orderBy('outgoingRequests.dateAccepted', 'ASC');

        $outgoingRequests = $query->getQuery()->getResult();

        return $this->buildUserWebDataDTOs($outgoingRequests);
    }

    private function buildUserWebDataDTOs($data)
    {
        $userWebDataDTOs = array();

        foreach($data as $record)
        {
            $userWebData = new UserWebDataDTO();
            $userWebData->setUserId($record->getUserId());
            $userWebData->setName($record->getFirstName());
            $userWebData->setFamily($record->getLastName());
            $userWebData->setGender($record->getGender());
            $userWebData->setPhoneNumber($record->getPhoneNumber());
            $userWebData->setWebProfilePic($record->getWebProfilePic());

            $userWebDataDTOs[] = $userWebData;
        }
        return $userWebDataDTOs;
    }
}