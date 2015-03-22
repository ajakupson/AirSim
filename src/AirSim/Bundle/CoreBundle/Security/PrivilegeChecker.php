<?php
/**
 * Created by Andrei Jakupson
 * Date: 3.02.15
 * Time: 1:05
 */

namespace AirSim\Bundle\CoreBundle\Security;

use AirSim\Bundle\CoreBundle\AirSimCoreBundle;


class PrivilegeChecker
{
    private static $privilegeCheckerInstance;

    // Dependencies
    private $entityManager = null;
    private $userBlockedContactsRepository = null;

    public function __construct()
    {
        $this->entityManager = AirSimCoreBundle::getContainer()->get('doctrine')->getManager();
        $this->userBlockedContactsRepository = $this->entityManager->getRepository('AirSimCoreBundle:UserBlockedContacts');
    }

    public static function getInstance()
    {
        if(self::$privilegeCheckerInstance == null)
        {
            self::$privilegeCheckerInstance = new self();
        }
        return self::$privilegeCheckerInstance;
    }

    public function getUserPrivileges($userId, $contactId = null)
    {
        $userPrivileges = new UserPrivileges();

        $userBlockedContactsRecord = $this->userBlockedContactsRepository->findOneBy(array('userId' => $contactId, 'blockedUserId' => $userId));

        $userPrivileges->setUserId($userId);
        $userPrivileges->setContactId($contactId);
        if($userId != null)
        {
            $userPrivileges->setIsLoggedIn(true);
        }
        if($userBlockedContactsRecord != null && $userId != $contactId)
        {
            $userPrivileges->setIsBlocked(true);
        }
        else
        {
            $userPrivileges->setIsBlocked(false);

            // For not blocked contact - check all privileges

        }

        return $userPrivileges;
    }
}