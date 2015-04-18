<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use AirSim\Bundle\CoreBundle\Services\OptionsService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AirSim\Bundle\CoreBundle\Security\PrivilegeChecker;
use AirSim\Bundle\CoreBundle\Services\UserService;
use AirSim\Bundle\CoreBundle\Services\UserDataService;

class OptionsController extends Controller {

    public function optionsAction($type) {
        $LOG = $this->get('logger');
        $LOG->info('optionsAction executed in OptionsController');

        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $userId = $session->get('sessionData')['userInfo']['id'];

        $privilegeChecker = PrivilegeChecker::getInstance();
        $userPrivileges = $privilegeChecker->getUserPrivileges($userId);

        $userService = UserService::getInstance();
        $userData = $userService->getUserData($userId);
        $userConfig = $userData->getConfig();

        $userHighEducation = $userData->getHighEducations();
        $userWorkplaces = $userData->getWorkplaces();

        return $this->render('AirSimSocialNetworkBundle:blue/Options:options.html.twig',
            array('userPrivileges' => $userPrivileges, 'userData' => $userData, 'userConfig' => $userConfig,
                'userHighEducation' => $userHighEducation, 'userWorkplaces' => $userWorkplaces));
    }

    /* ***** AJAX ***** */
    public function configChangeAction() {
        $success = true;
        $error = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        $userId = $session->get('sessionData')['userInfo']['id'];
        $configAlias = $request->get('configAlias');
        $valueToBeSet = $request->get('configValue');

        $optionsService = OptionsService::getInstance();
        $success = $optionsService->changeConfigParameter($userId, $configAlias, $valueToBeSet);

        $response = array('success' => $success, 'error' => $error);
        return new Response(json_encode($response));
    }

    public function updateMainInformationAction() {
        $success = true;
        $error = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        $userId = $session->get('sessionData')['userInfo']['id'];
        $userName = $request->get('user_name');
        $userFamily = $request->get('user_family');
        $userBirthDate = $request->get('user_birthDate');
        $userGender = $request->get('user_gender');
        $phoneOperator = $request->get('phone_operator');

        $userDataService = UserDataService::getInstance();
        $success = $userDataService->saveMainInformation($userId, $userName, $userFamily, $userBirthDate, $userGender,
            $phoneOperator);

        $response = array('success' => $success, 'error' => $error);
        return new Response(json_encode($response));
    }

    public function updateAdditionalInformationAction() {
        $success = true;
        $error = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        $userId = $session->get('sessionData')['userInfo']['id'];
        $email = $request->get('email');
        $country = $request->get('country');
        $city = $request->get('city');
        $address = $request->get('address');
        $website = $request->get('website');

        $userDataService = UserDataService::getInstance();
        $success = $userDataService->saveAdditionalInformation($userId, $email, $country, $city, $address, $website);

        $response = array('success' => $success, 'error' => $error);
        return new Response(json_encode($response));
    }

    public function addHighEducationAction() {
        $success = true;
        $error = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        $userId = $session->get('sessionData')['userInfo']['id'];
        $university = $request->get('university');
        $faculty = $request->get('faculty');
        $speciality = $request->get('speciality');
        $degree = $request->get('degree');
        $startYear = $request->get('start_year');
        $endYear = $request->get('end_year');

        $userDataService = UserDataService::getInstance();
        $highEducationId = $userDataService->addHighEducation($userId, $university, $faculty, $speciality, $degree,
            $startYear, $endYear);

        $response = array('success' => $success, 'error' => $error, 'data' => array('highEducationId' => $highEducationId));
        return new Response(json_encode($response));
    }

    public function editHighEducationAction()
    {
        $success = true;
        $error = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        $highEducationRecordId = $request->get('high_education_record_id');
        $userId = $session->get('sessionData')['userInfo']['id'];
        $university = $request->get('university');
        $faculty = $request->get('faculty');
        $speciality = $request->get('speciality');
        $degree = $request->get('degree');
        $startYear = $request->get('start_year');
        $endYear = $request->get('end_year');

        $userDataService = UserDataService::getInstance();
        $success = $userDataService->editHighEducation($highEducationRecordId, $userId, $university, $faculty, $speciality, $degree,
            $startYear, $endYear);

        $response = array('success' => $success, 'error' => $error);
        return new Response(json_encode($response));
    }

    public function deleteHighEducationAction()
    {
        $success = true;
        $error = '';

        $request = $this->get('request_stack')->getCurrentRequest();

        $highEducationRecordId = $request->get('highEducationRecordId');

        $userDataService = UserDataService::getInstance();
        $success = $userDataService->deleteHighEducation($highEducationRecordId);

        $response = array('success' => $success, 'error' => $error);
        return new Response(json_encode($response));
    }

    public function addWorkplaceAction()
    {
        $success = true;
        $error = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        $userId = $session->get('sessionData')['userInfo']['id'];
        $workplace = $request->get('workplace');
        $position = $request->get('position');
        $startYear = $request->get('start_year');
        $endYear = $request->get('end_year');

        $userDataService = UserDataService::getInstance();
        $workplaceId = $userDataService->addWorkplace($userId, $workplace, $position, $startYear, $endYear);

        $response = array('success' => $success, 'error' => $error, 'data' => array('workplaceId' => $workplaceId));
        return new Response(json_encode($response));
    }

    public function editWorkplaceAction()
    {
        $success = true;
        $error = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        $workplaceRecordId = $request->get('workplace_record_id');
        $userId = $session->get('sessionData')['userInfo']['id'];
        $workplace = $request->get('workplace');
        $position = $request->get('position');
        $startYear = $request->get('start_year');
        $endYear = $request->get('end_year');

        $userDataService = UserDataService::getInstance();
        $success = $userDataService->editWorkplace($workplaceRecordId, $userId, $workplace, $position, $startYear, $endYear);

        $response = array('success' => $success, 'error' => $error);
        return new Response(json_encode($response));
    }

    public function deleteWorkplaceAction()
    {
        $success = true;
        $error = '';

        $request = $this->get('request_stack')->getCurrentRequest();

        $workplaceRecordId = $request->get('workplaceRecordId');

        $userDataService = UserDataService::getInstance();
        $success = $userDataService->deleteWorkplace($workplaceRecordId);

        $response = array('success' => $success, 'error' => $error);
        return new Response(json_encode($response));
    }
}
