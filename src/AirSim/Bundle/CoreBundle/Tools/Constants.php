<?php
namespace AirSim\Bundle\CoreBundle\Tools;


class Constants {
    // Error Codes
    const ERR_WRONG_USERNAME_OR_PASS = 'ERR_WRONG_USERNAME_OR_PASS';

    // SQL Limits
    const LAST_CONTACTS_LIMIT           = 7;
    const RANDOM_CONTACTS_LIMIT         = 12;
    const CONTACTS_LIST_LIMIT           = 30;
    const LAST_PHOTOS_LIMIT             = 12;
    const PHOTO_COMMENTS_LIMIT          = 15;
    const WALL_RECORDS_LIMIT            = 10;
    const WALL_RECORD_COMMENTS_LIMIT    = 10;
    const CHAT_MESSAGES_LIMIT           = 15;

    const SQL_OFFSET_TEST = 1;

    // Valid formats
    const IMAGES_VALID_FORMATS = 'jpg, jpeg, png, gif, bmp';

    // Paths
    const TMP_FILES_DIRECTORY_PATH = './../web/public_files/tmp/';
    const USER_FILES_DIRECTORY_PATH = './../web/public_files/user_';
    const USER_ALBUM_PICTURE_PATH = './../web/public_files/user_%d/albums/%s/%s';
    const IMAGINE_FILE_PATH = 'public_files/user_%d/albums/%s/%s';
    const IMAGINE_CACHE_PATH = 'media/cache/%s/public_files/user_%d/albums/%s/%s';

    const APP_DEFAULT_PROFILE_PICS_ALBUM_NAME = 'profile_pics';

    // LiipImagineBundle filter names
    const IMAGE_FILTER_LAST_PHOTO = 'last_photo';

    const MAX_PROFILE_PIC_WIDTH = 240;
    const MAX_PROFILE_PIC_HEIGHT = 240;

    // Default folders
    const WALL_PICTURES_ALBUM_NAME = 'wall_pics';

    // Like types
    const WALL_RECORD_LIKE = 'WALL_RECORD_LIKE';

    // Actions
    const LIKE      = 'like';
    const DISLIKE   = 'dislike';

    // Events
    const SEND_MESSAGE      = 'sendMessage';
    const READ_MESSAGE      = 'readMessage';
    const DELETE_MESSAGE    = 'deleteMessage';

    // Config aliases
    const PRIVATE_PHONE_VISIBILITY                  = 'PRIVATE_PHONE_VISIBILITY';
    const PRIVATE_ADDITIONAL_INFO_VISIBILITY        = 'PRIVATE_ADDITIONAL_INFO_VISIBILITY';
    const PRIVATE_FRIENDS_VISIBILITY                = 'PRIVATE_FRIENDS_VISIBILITY';
    const PRIVATE_SEARCH_BY_PHONE_NUMBER            = 'PRIVATE_SEARCH_BY_PHONE_NUMBER';
    const PRIVATE_SENDING_MESSAGES                  = 'PRIVATE_SENDING_MESSAGES';
    const SYNCHRONIZATION_AUTO_BETWEEN_PHONE_SITE   = 'SYNCHRONIZATION_AUTO_BETWEEN_PHONE_SITE';
}