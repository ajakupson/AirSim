

$(document).ready(function()
{
    initElementsAndDialogWindows();

    // Remove shown notification
    $('.notification_close').click(function()
    {
       $(this).closest('.notification_box').remove();
    });
});

function initElementsAndDialogWindows()
{
    // Init gallery modal
    $('#gallery').dialog('init',
    {
        top: '-80px',
        height: 'auto'
    });

    // Init gallery
    $('#gallery').gallery('init',
    {
        'url': './../user_get_photo_data',
        'folderPath' : './../../public_files/user_',
        'appDefaultFolderPath': './../../public_files/app_default/',
        'onShowCallback' : function()
        {
            $('#gallery').dialog('open');
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