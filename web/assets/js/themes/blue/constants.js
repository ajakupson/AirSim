define
(
    'blue/constants',
    function()
    {
        // Error codes
        this.ERR_WRONG_USERNAME_OR_PASS = 'ERR_WRONG_USERNAME_OR_PASS';

        // Paths
        this.APP_DEFAULT_SETTINGS_PATH = '../../public_files/app_default/blue/';
        this.APP_DEFAULT_AVATAR_MALE = APP_DEFAULT_SETTINGS_PATH + 'no_avatar_male.png';
        this.TMP_FILES_DIRECTORY_PATH = '/AirSim/web/public_files/tmp/';

        // Default folders
        this.WALL_PICTURES_ALBUM_NAME = 'wall_pics';
        this.PROFILE_PICS_ALBUM_NAME = 'profile_pics';

        // Actions
        this.LIKE = 'like';
        this.DISLIKE = 'dislike';

        // Events
        this.SEND_MESSAGE = 'sendMessage';
        this.READ_MESSAGE = 'readMessage';
        this.DELETE_MESSAGE = 'deleteMessage';

        // Elements settings
        this.TEXTAREA_MAX_HEIGHT = 300;
    }
)