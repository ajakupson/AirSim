<?php
/**
 * Created by Andrei Jakupson
 * Date: 5.04.15
 * Time: 15:42
 */

namespace AirSim\Bundle\CoreBundle\Security;


class PrivilegesIds {

    // Search by phone
    const SEARCH_BY_PHONE_YES = 1;
    const SEARCH_BY_PHONE_NO = 0;

    // Phone visibility
    const PHONE_VISIBILITY_ALL = 0;
    const PHONE_VISIBILITY_FRIENDS_ONLY = 1;

    // Additional info visibility
    const ADDITIONAL_INFO_VISIBILITY_ALL = 0;
    const ADDITIONAL_INFO_VISIBILITY_FRIENDS_ONLY = 1;

    // Contacts visibility
    const CONTACTS_VISIBILITY_ALL = 0;
    const CONTACTS_VISIBILITY_FRIENDS_ONLY  = 1;
    const CONTACTS_VISIBILITY_NONE = 2;

    // Can write messages
    const WRITE_MESSAGES_ALL = 0;
    const WRITE_MESSAGES_FRIENDS_ONLY  = 1;
    const WRITE_MESSAGES_NONE = 2;

    // Synchronization
    const SYNCHRONIZATION_PHONE = 0;
    const SYNCHRONIZATION_NAME_FAMILY = 1;
    const SYNCHRONIZATION_PHONE_ADDITIONAL_INFO = 2;
    const SYNCHRONIZATION_PHONE_ADDITIONAL_INFO_NAME_FAMILY = 3;
}