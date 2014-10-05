var conn = new ab.Session
(
    'ws://127.0.0.1:8080', // The host (our Ratchet WebSocket server) to connect to
    function()
    {
        // Once the connection has been established
        console.log('WebSocket connection is opened');

        var userId = $('#user_id').val();
        conn.subscribe('user_' + userId, function(topic, response)
        {
            //console.log(response.data.text);
            var event = response.eventData.event;
            switch(event)
            {
                case 'addWallRecord':
                {
                    addWallRecordSocket(response);
                }break;
                default:break;
            }

        });
    },
    function()
    {
        // When the connection is closed
        console.warn('WebSocket connection closed');
    },
    {
        // Additional parameters, we're ignoring the WAMP sub-protocol for older browsers
        'skipSubprotocolCheck': true
    }
);

var attachedImagesCounter = 0;

$(document).ready(function()
{
    uploadAttachedImages();
    deleteUploadedImage();
    addWallRecord();
});

function addWallRecord()
{
    $('#post_wall_record_button').click(function()
    {
        var wallRecordText = $('#wall_record_text').val();
        var receiverId = $('#user_id').val();
        var attachedPictures = [];

        var $images = $('#wall_record_added_images ul li');

        $.each($images, function(key, value)
        {
           if(key > 0)
           {
               attachedPictures.push($(value).attr('file-name'));
           }
        });

        if($.trim(wallRecordText).length > 0)
        {
            $.ajax
            ({
                url: './../user_add_wall_record',
                dataType: 'json',
                type: 'POST',
                data:
                {
                    receiverId: receiverId,
                    page: 'user_' + receiverId,
                    text: wallRecordText,
                    attachedPictures: attachedPictures
                },
                success: function(response)
                {
                    if(response.eventData.success)
                    {
                        $('#wall_record_text').val('');
                        var $attachedImages = $('#wall_record_added_images ul li');
                        $.each($attachedImages, function(key, value)
                        {
                           if(key != 0)
                           {
                               $(value).remove();
                           }
                        });
                        $('#wall_record_added_documents > ul').html('');
                        $('#wall_record_added_documents').hide();
                    }
                    else
                    {

                    }

                }
            });
        }
        else
        {
            // TODO: Add alert
        }
    });
}

function uploadAttachedImages()
{
    $('#wall_record_attached_images').change(function()
    {
        var receiverId = $('#user_id').val();

        var $wallRecordImagesForm = $('#wall_record_attach_images_form').ajaxForm
        ({
            url: './../user_upload_tmp_images',
            dataType: 'json',
            type: 'POST',
            data:
            {
                receiverId: receiverId
            },
            success: function(response)
            {
                var $picturesPreview = $('.wall_record_added_images ul');
                if(response.success)
                {
                    $.each(response.uploadedPictures, function(key, value)
                    {
                        attachedImagesCounter++;

                        var $picturePreviewTemplate = $picturesPreview.children(':first').clone(true).removeClass('hidden');

                        $picturePreviewTemplate.attr('file-name', value);
                        $picturePreviewTemplate.css('background', 'url("' + TMP_FILES_DIRECTORY_PATH + value + '") no-repeat center top');
                        $picturePreviewTemplate.css('background-size', 'cover');

                        var $checkbox = $picturePreviewTemplate.find('input[type="checkbox"]');
                        $checkbox.attr('id', $checkbox.attr('id') + '_' + attachedImagesCounter);

                        var $label = $picturePreviewTemplate.find('label');
                        $label.attr('for', $label.attr('for') + '_' + attachedImagesCounter);

                        $picturesPreview.append($picturePreviewTemplate);
                    });
                }
                else
                {

                }

            }
        }).submit();
    });
}

function deleteUploadedImage()
{
    $('.delete_button', '#wall_record_added_images').click(function()
    {
        var $imageContainer = $(this).closest('li');
        var imageToDelete = $imageContainer.attr('file-name');
        $.ajax
        ({
            url: './../user_delete_tmp_image',
            dataType: 'json',
            type: 'POST',
            data:
            {
                imageToDelete: imageToDelete
            },
            success: function(response)
            {
                if(response.success)
                {
                    $imageContainer.remove();
                }
                else
                {

                }

            }
        });
    });
}


/* ***** WebSocket ***** */
function addWallRecordSocket(response)
{
    var $newWallRecord = $("#wall_record_template").clone(true).removeAttr('id').removeClass('hidden');
    $newWallRecord.find('.date').html(response.eventData.recordDate);
    $newWallRecord.find('.time').html(response.eventData.recordTime);
    $newWallRecord.find('.wall_record_content h1').html('');
    var senderWebPic = null;
    if(response.senderData.senderWebPic != null)
    {
        senderWebPic = './../../public_files/user_' + response.senderData.senderId + '/albums/profile_pics/'
            + response.senderData.senderWebPic;
    }
    else
    {
        senderWebPic = APP_DEFAULT_AVATAR_MALE;
    }
    $newWallRecord.find('.wall_record_content h1').html('TODO');
    $newWallRecord.find('.wall_record_author img').attr('src', senderWebPic);
    $newWallRecord.find('.author_name').html(response.senderData.senderName + ' ' + response.senderData.senderFamily);
    $newWallRecord.find('.wall_record_text').html(response.eventData.messageText);

    var $wallRecordImagesContainer = $newWallRecord.find('.wall_record_images ul');
    var wallRecordImageTemplate = '<li><img class = "photo"/><br/><input type = "hidden" class = "photo_id"/></li>';

    $.each(response.eventData.wallRecordPictures, function(key, value)
    {
        var $wallRecordImage = $(wallRecordImageTemplate);
        var imagePath = './../../public_files/user_' + response.receiverData.receiverId + '/albums/' +
            WALL_PICTURES_ALBUM_NAME + '/' + value.name
        $wallRecordImage.find('.photo').attr('src', imagePath);
        $wallRecordImage.find('.photo_id').attr('value', value.id);

        $wallRecordImagesContainer.append($wallRecordImage);
    });

    $('.wall_records_wrapper').prepend($newWallRecord);
    console.log('DEBUG: Wall record added', response);
}