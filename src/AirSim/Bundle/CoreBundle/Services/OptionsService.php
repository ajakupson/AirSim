<?php
/**
 * Created by Andrei Jakupson
 * Date: 5.04.15
 * Time: 17:10
 */

namespace AirSim\Bundle\CoreBundle\Services;

use AirSim\Bundle\CoreBundle\AirSimCoreBundle;
use AirSim\Bundle\CoreBundle\Tools\Constants;

class OptionsService {

    private static $optionsServiceInstance;

    // dependencies
    private $entityManager = null;
    private $configRepository = null;
    private $usersRepository = null;

    private function __construct()
    {
        $this->entityManager = AirSimCoreBundle::getContainer()->get('doctrine')->getManager();
        $this->configRepository = $this->entityManager->getRepository('AirSimCoreBundle:UserConfig');
        $this->usersRepository = $this->entityManager->getRepository('AirSimCoreBundle:User');
    }

    public static function getInstance()
    {
        if(is_null(self::$optionsServiceInstance))
        {
            self::$optionsServiceInstance = new self();
        }
        return self::$optionsServiceInstance;
    }

    public function changeConfigParameter($userId, $configAlias, $configValue) {

        $userEntity = $this->usersRepository->findOneByUserId($userId);
        if($userEntity == null || sizeof($userEntity) == 0) {
            return false;
        }

        $userConfig = $userEntity->getConfig();

        switch($configAlias) {
            case Constants::PRIVATE_PHONE_VISIBILITY : {
                $userConfig->setPrivPhoneVisibility($configValue);
            } break;
            case Constants::PRIVATE_ADDITIONAL_INFO_VISIBILITY : {
                $userConfig->setPrivAddInfoVisibility($configValue);
            } break;
            case Constants::PRIVATE_FRIENDS_VISIBILITY : {
                $userConfig->setPrivFriendsVisibility($configValue);
            } break;
            case Constants::PRIVATE_SEARCH_BY_PHONE_NUMBER : {
                $userConfig->setPrivSearchByPhone($configValue);
            } break;
            case Constants::PRIVATE_SENDING_MESSAGES : {
                $userConfig->setPrivWhoAllowedWrite($configValue);
            } break;
            case Constants::PRIVATE_SENDING_MESSAGES : {
                $userConfig->setPrivWhoAllowedWrite($configValue);
            } break;
            case Constants::SYNCHRONIZATION_AUTO_BETWEEN_PHONE_SITE : {
                $userConfig->setSyncAutoSync($configValue);
            } break;
            default: {
                return false;
            } break;
        }

        $this->entityManager->persist($userConfig);
        $this->entityManager->flush();

        return true;
    }
}