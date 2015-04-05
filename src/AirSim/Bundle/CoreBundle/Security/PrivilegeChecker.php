<?php
/**
 * Created by Andrei Jakupson
 * Date: 3.02.15
 * Time: 1:05
 */

namespace AirSim\Bundle\CoreBundle\Security;

use AirSim\Bundle\CoreBundle\AirSimCoreBundle;
use AirSim\Bundle\CoreBundle\Security\PrivilegesIds;


class PrivilegeChecker
{
    private static $privilegeCheckerInstance;

    // Dependencies
    private $entityManager = null;
    private $userBlockedContactsRepository = null;
    private $userConfigRepository = null;
    private $userRepository = null;
    private $friendsRepository = null;

    public function __construct()
    {
        $this->entityManager = AirSimCoreBundle::getContainer()->get('doctrine')->getManager();
        $this->userBlockedContactsRepository = $this->entityManager->getRepository('AirSimCoreBundle:UserBlockedContacts');
        $this->userConfigRepository = $this->entityManager->getRepository('AirSimCoreBundle:UserConfig');
        $this->userRepository = $this->entityManager->getRepository('AirSimCoreBundle:User');
        $this->friendsRepository = $this->entityManager->getRepository('AirSimCoreBundle:UserFriends');
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

        if($userId != null) {
            $userPrivileges->setIsLoggedIn(true);
        }

        if($contactId == null) {
            return $userPrivileges;
        } else {
            $userPrivileges->setContactId($contactId);
        }

        if($userBlockedContactsRecord != null && $userId != $contactId) {
            $userPrivileges->setIsBlocked(true);
        } else {
            $userPrivileges->setIsBlocked(false);

            // Get contact config
            $contactConfig = null;
            $contactEntity = $this->userRepository->findOneByUserId($contactId);
            if($contactEntity != null || sizeof($contactEntity) == 1) {
                $contactConfig = $contactEntity->getConfig();
            }

            $friendEntity = $this->friendsRepository->findOneBy(array('userId' => $contactId, 'friendId' => $userId,
                'isAccepted' => 1));
            if($friendEntity != null || sizeof($friendEntity) == 1) {
                $userPrivileges->setIsFriend(true);
            }

            // For not blocked contact - check all privileges
            // See additional info privilege
            switch($contactConfig->getPrivAddInfoVisibility()) {
                case PrivilegesIds::ADDITIONAL_INFO_VISIBILITY_ALL: {
                    $userPrivileges->setSeeContactsPrivilege(true);
                } break;
                case PrivilegesIds::ADDITIONAL_INFO_VISIBILITY_FRIENDS_ONLY: {
                    if($userPrivileges->getIsFriend()) {
                        $userPrivileges->setSeeContactsPrivilege(true);
                    }
                }
                default: break;
            }

            // See contacts privilege
            switch($contactConfig->getPrivFriendsVisibility()) {
                case PrivilegesIds::CONTACTS_VISIBILITY_ALL: {
                    $userPrivileges->setSeeContactsPrivilege(true);
                } break;
                case PrivilegesIds::CONTACTS_VISIBILITY_FRIENDS_ONLY: {
                    if($userPrivileges->getIsFriend()) {
                        $userPrivileges->setSeeContactsPrivilege(true);
                    }
                }
                default: break;
            }

            // Write message privilege
            switch($contactConfig->getPrivWhoAllowedWrite()) {
                case PrivilegesIds::WRITE_MESSAGES_ALL: {
                    $userPrivileges->setSendMessagePrivilege(true);
                } break;
                case PrivilegesIds::WRITE_MESSAGES_FRIENDS_ONLY: {
                    if($userPrivileges->getIsFriend()) {
                        $userPrivileges->setSendMessagePrivilege(true);
                    }
                }
                default: break;
            }
        }

        return $userPrivileges;
    }
}