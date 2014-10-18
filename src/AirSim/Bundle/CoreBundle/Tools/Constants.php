<?php
namespace AirSim\Bundle\CoreBundle\Tools;


class Constants
{
    // Error Codes
    const ERR_WRONG_USERNAME_OR_PASS = 'ERR_WRONG_USERNAME_OR_PASS';

    // SQL Limits
    const LAST_CONTACTS_LIMIT = 7;
    const RANDOM_CONTACTS_LIMIT = 12;
    const LAST_PHOTOS_LIMIT = 12;
    const PHOTO_COMMENTS_LIMIT = 15;
    const WALL_RECORDS_LIMIT = 10;
    const WALL_RECORD_COMMENTS_LIMIT = 10;

    // Valid formats
    const IMAGES_VALID_FORMATS = 'jpg, jpeg, png, gif, bmp';

    // Paths
    const TMP_FILES_DIRECTORY_PATH = './../web/public_files/tmp/';

    // Default folders
    const WALL_PICTURES_ALBUM_NAME = 'wall_pics';

    // Like types
    const WALL_RECORD_LIKE =  'WALL_RECORD_LIKE';

    // Actions
    const LIKE = 'like';
    const DISLIKE = 'dislike';

    // Events
}