<?php

namespace AirSim\Bundle\CoreBundle\Services;

use AirSim\Bundle\CoreBundle\AirSimCoreBundle;
use AirSim\Bundle\CoreBundle\DataTransferObjects\UserWebDataDTO;
use AirSim\Bundle\CoreBundle\SearchParameters\ContactSP;

// Singleton
class UserService
{
    private static $userServiceInstance = null;

    // Dependencies
    private $entityManager = null;
    private $userRepository = null;
    private $friendsRepository = null;

    private function __construct()
    {

        $this->entityManager = AirSimCoreBundle::getContainer()->get('doctrine')->getManager();
        // config
        $config = $this->entityManager->getConfiguration();
        $config->addCustomNumericFunction('FLOOR', 'AirSim\Bundle\CoreBundle\FunctionsToDQL\MySQL\MySqlFloor');

        $this->userRepository = $this->entityManager->getRepository('AirSimCoreBundle:User');
        $this->friendsRepository = $this->entityManager->getRepository('AirSimCoreBundle:UserFriends');

    }

    public static function getInstance()
    {
        if(is_null(self::$userServiceInstance))
        {
            self::$userServiceInstance = new self();
        }

        return self::$userServiceInstance;
    }

    public function getUserData($userId)
    {
        $userCompleteData =  $this->userRepository->findOneByUserId($userId);

        return $userCompleteData;
    }

    public function getUserIdByUsername($username)
    {
        $user = $this->userRepository->findOneBy(array('login' => $username));

        return sizeof($user) == 1 ? $user->getUserId() : null;
    }

    public function getUserFriends($userId, $offset = null, $limit = null, $random = false)
    {
        $count = 0;
        if($random)
        {
            $query = $this->entityManager->createQueryBuilder();
            $query
                ->select('COUNT(friend)')
                ->from('AirSimCoreBundle:User', 'friend')
                ->innerJoin('AirSimCoreBundle:UserFriends', 'userFriends', 'WITH', 'userFriends.friendId = friend.userId')
                ->where('userFriends.userId = :userId')
                ->andWhere('userFriends.isAccepted = 1')
                ->setParameter('userId', $userId)
                ->setMaxResults($limit)
                ->orderBy('userFriends.dateAccepted', 'DESC');

            $count = $query->getQuery()->getResult()[0][1];
        }

        $query = $this->entityManager->createQueryBuilder();
        $query
            ->select('friend')
            ->from('AirSimCoreBundle:User', 'friend')
            ->innerJoin('AirSimCoreBundle:UserFriends', 'userFriends', 'WITH', 'userFriends.friendId = friend.userId')
            ->where('userFriends.userId = :userId')
            ->andWhere('userFriends.isAccepted = 1')
            ->setParameter('userId', $userId)
            ->setMaxResults($limit)
            ->orderBy('userFriends.dateAccepted', 'DESC');

        if($random)
        {
            $randomNumber = 0;
            $difference = $count - $limit;
            if($difference >= 0)
            {
                $randomNumber = rand(0, $difference);
            }
            $query
                ->setFirstResult($randomNumber)
                ->setMaxResults($limit);
        }

        $userFriends = $query->getQuery()->getResult();

        return $userFriends;
    }

    public function searchContacts($searchParams, $limit = null, $offset = null)
    {
        $currentDate = new \DateTime();
        $query = $this->entityManager->createQueryBuilder();
        $query
            ->select('contact', 'FLOOR(DATE_DIFF(CURRENT_DATE(), contact.birthdate) / 365) AS age')
            ->from('AirSimCoreBundle:User', 'contact');

        if($searchParams->getIsFriend())
        {
            $query->innerJoin('AirSimCoreBundle:UserFriends', 'userFriends', 'WITH', 'userFriends.friendId = contact.userId')
                  ->where('userFriends.userId = :userId')
                  ->setParameter('userId', $searchParams->getUserId())
                  ->andWhere('userFriends.isAccepted = 1');
        }
        if($searchParams->getNameAndOrFamily() != null)
        {
            $query->andWhere('UPPER(contact.firstName) LIKE :nameAndOrFamily OR UPPER(contact.lastName) LIKE :nameAndOrFamily')
                  ->setParameter('nameAndOrFamily', $searchParams->getNameAndOrFamily());
        }
        if($searchParams->getGender() != null && trim($searchParams->getGender()) != "")
        {
            $query->andWhere('contact.gender = :gender')
                  ->setParameter('gender', $searchParams->getGender());
        }
        if($searchParams->getPhoneNumber() != null)
        {
            $query->andWhere('contact.phoneNumber LIKE :phoneNumber')
                ->setParameter('phoneNumber', $searchParams->getPhoneNumber());
        }
        if($searchParams->getCountry() != null)
        {
            $query->andWhere('contact.country LIKE :country')
                ->setParameter('country', $searchParams->getCountry());
        }
        if($searchParams->getCity() != null)
        {
            $query->andWhere('contact.city LIKE :city')
                ->setParameter('city', $searchParams->getCity());
        }
        if($searchParams->getAgeFrom() != null && trim($searchParams->getAgeFrom()) != "")
        {
            $query->andWhere('FLOOR(DATE_DIFF(CURRENT_DATE(), contact.birthdate) / 365) >= :ageFrom')
                ->setParameter('ageFrom', $searchParams->getAgeFrom());
        }
        if($searchParams->getAgeTo() != null && trim($searchParams->getAgeTo()) != "")
        {
            $query->andWhere('FLOOR(DATE_DIFF(CURRENT_DATE(), contact.birthdate) / 365) <= :ageTo')
                ->setParameter('ageTo', $searchParams->getAgeTo());
        }
        if(!$searchParams->getIsFriend())
        {
            $query->andWhere('contact.userId NOT IN
            (
                SELECT friend.friendId FROM AirSimCoreBundle:UserFriends AS friend
                WHERE friend.userId = :userId AND friend.isAccepted = 1
            ) AND contact.userId != :userId')->setParameter('userId', $searchParams->getUserId());
        }

        if($offset != null)
        {
            $query->setFirstResult($offset);
        }
        if($limit != null)
        {
            $query->setMaxResults($limit);
        }

        $foundContacts = $query->getQuery()->getResult();


        $foundContactsDTOs = array();
        foreach($foundContacts as $contact)
        {
            $contactDTO = new UserWebDataDTO();
            $contactDTO->setUserId($contact[0]->getUserId());
            $contactDTO->setName($contact[0]->getFirstName());
            $contactDTO->setFamily($contact[0]->getLastName());
            $contactDTO->setGender($contact[0]->getGender());
            $contactDTO->setWebProfilePic($contact[0]->getWebProfilePic());
            $contactDTO->setPhoneNumber($contact[0]->getPhoneNumber());

            $foundContactsDTOs[] = $contactDTO->expose();
        }

        return $foundContactsDTOs;
    }
}