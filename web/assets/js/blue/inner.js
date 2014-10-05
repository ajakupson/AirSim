

$(document).ready(function()
{
    initDialogWindows();

    // Remove shown notification
    $('.notification_close').click(function()
    {
       $(this).closest('.notification_box').remove();
    });
});

function initDialogWindows()
{
    // Init gallery modal
    $('#gallery').dialogInit
    ({
        'height': 'auto'
    });

    // Init gallery
    $('#gallery').gallery('init',
    {
        'url': './../user_get_photo_data',
        'folderPath' : './../../public_files/user_',
        'appDefaultFolderPath': './../../public_files/app_default/',
        'onShowCallback' : function()
        {
            $('#gallery').dialogOpen();
        }
    });

    $('.photo').click(function()
    {
        var photoId = $(this).parent().find('.photo_id').val();
        $('#gallery').gallery('show',
        {
            'photoId': photoId
        });
    });

    // Error Dialog
    $('#error_message_dialog').errDialogInit();
}