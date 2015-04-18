<?php
/**
 * Created by Andrei Jakupson
 * Date: 7.04.15
 * Time: 0:09
 */

namespace AirSim\Bundle\CoreBundle\Services;

use AirSim\Bundle\CoreBundle\AirSimCoreBundle;
use AirSim\Bundle\CoreBundle\Entity\UserHighEducation;
use AirSim\Bundle\CoreBundle\Entity\UserWorkplaces;

class UserDataService {

    private static $userDataServiceInstance = null;

    // Dependencies
    private $entityManager = null;
    private $userRepository = null;
    private $highEducationsRepository = null;
    private $workPlacesRepository = null;

    private function __construct() {

        $this->entityManager = AirSimCoreBundle::getContainer()->get('doctrine')->getManager();
        $this->userRepository = $this->entityManager->getRepository('AirSimCoreBundle:User');
        $this->highEducationsRepository = $this->entityManager->getRepository('AirSimCoreBundle:UserHighEducation');
        $this->workPlacesRepository = $this->entityManager->getRepository('AirSimCoreBundle:UserWorkplaces');

    }

    public static function getInstance() {

        if(is_null(self::$userDataServiceInstance)) {
            self::$userDataServiceInstance = new self();
        }

        return self::$userDataServiceInstance;
    }

    public function saveMainInformation($userId, $userName, $userFamily, $userBirthDate, $userGender, $phoneOperator) {

        $userEntity = $this->userRepository->findOneByUserId($userId);

        $userEntity->setFirstName($userName);
        $userEntity->setLastName($userFamily);
        if(strlen($userBirthDate) > 0)
            $userEntity->setBirthdate(new \DateTime(str_replace('.', '', $userBirthDate)));
        $userEntity->setGender($userGender);
        $userEntity->setOperator($phoneOperator);

        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();

        return true;
    }

    public function saveAdditionalInformation($userId, $email, $country, $city, $address, $website) {

        $userEntity = $this->userRepository->findOneByUserId($userId);

        $userEntity->setEmail($email);
        $userEntity->setCountry($country);
        $userEntity->setCity($city);
        $userEntity->setAddress($address);
        $userEntity->setWebsite($website);

        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();

        return true;
    }

    public function addHighEducation($userId, $university, $faculty, $speciality, $degree, $startYear, $endYear) {

        $userEntity = $this->userRepository->findOneByUserId($userId);

        $highEducationEntity = new UserHighEducation();
        $highEducationEntity->setUser($userEntity);
        $highEducationEntity->setUniversity($university);
        $highEducationEntity->setFaculty($faculty);
        $highEducationEntity->setSpeciality($speciality);
        $highEducationEntity->setDegree($degree);
        $highEducationEntity->setStartDate(new \DateTime(str_replace('.', '', $startYear)));
        if(strlen($endYear) > 0) {
            $highEducationEntity->setEndDate(new \DateTime(str_replace('.', '', $endYear)));
        }

        $this->entityManager->persist($highEducationEntity);
        $this->entityManager->flush();

        $highEducationId = $highEducationEntity->getRecId();

        return $highEducationId;
    }

    public function editHighEducation($highEducationRecordId, $userId, $university, $faculty, $speciality, $degree,
        $startYear, $endYear) {

        $highEducationEntity = $this->highEducationsRepository->findOneByRecId($highEducationRecordId);

        $highEducationEntity->setUniversity($university);
        $highEducationEntity->setFaculty($faculty);
        $highEducationEntity->setSpeciality($speciality);
        $highEducationEntity->setDegree($degree);
        $highEducationEntity->setStartDate(new \DateTime(str_replace('.', '', $startYear)));
        if(strlen($endYear) > 0) {
            $highEducationEntity->setEndDate(new \DateTime(str_replace('.', '', $endYear)));
        }

        $this->entityManager->persist($highEducationEntity);
        $this->entityManager->flush();

        return true;
    }

    public function deleteHighEducation($highEducationRecordId) {

        $highEducationEntity = $this->highEducationsRepository->findOneByRecId($highEducationRecordId);

        $this->entityManager->remove($highEducationEntity);
        $this->entityManager->flush();

        return true;
    }

    public function addWorkplace($userId, $workplace, $position, $startYear, $endYear) {

        $userEntity = $this->userRepository->findOneByUserId($userId);

        $workplaceEntity = new UserWorkplaces();
        $workplaceEntity->setUser($userEntity);
        $workplaceEntity->setCompany($workplace);
        $workplaceEntity->setPosition($position);
        $workplaceEntity->setStartDate(new \DateTime(str_replace('.', '', $startYear)));
        if(strlen($endYear) > 0) {
            $workplaceEntity->setEndDate(new \DateTime(str_replace('.', '', $endYear)));
        }

        $this->entityManager->persist($workplaceEntity);
        $this->entityManager->flush();

        $workplaceId = $workplaceEntity->getRecId();

        return $workplaceId;
    }

    public function editWorkplace($workplaceRecordId, $userId, $workplace, $position, $startYear, $endYear) {

        $workplaceEntity = $this->workPlacesRepository->findOneByRecId($workplaceRecordId);

        $workplaceEntity->setCompany($workplace);
        $workplaceEntity->setPosition($position);
        $workplaceEntity->setStartDate(new \DateTime(str_replace('.', '', $startYear)));
        if(strlen($endYear) > 0) {
            $workplaceEntity->setEndDate(new \DateTime(str_replace('.', '', $endYear)));
        }

        $this->entityManager->persist($workplaceEntity);
        $this->entityManager->flush();

        return true;
    }

    public function deleteWorkplace($workplaceRecordId) {

        $workplaceEntity = $this->workPlacesRepository->findOneByRecId($workplaceRecordId);

        $this->entityManager->remove($workplaceEntity);
        $this->entityManager->flush();

        return true;
    }


}