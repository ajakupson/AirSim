<?php

namespace AirSim\Bundle\CoreBundle\Services;

use AirSim\Bundle\CoreBundle\AirSimCoreBundle;

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
}