define(
    'blue/user',
    [
        'jquery',
        'jquery.form',
        'autobahn',
        'blue/common',
        'blue/accordion'

   ],
    function($)
    {

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
                    var event = response.eventData.event;
                    switch(event)
                    {
                        case 'addWallRecord':
                        {
                            addWallRecordSocket(response);
                        }break;
                        case 'likeDislikeWallRecord':
                        {
                            likeDislikeWallRecordSocket(response);
                        }break;
                        case 'replyToWallRecord':
                        {
                            replyToWallRecordSocket(response);
                        }break;
                        case 'ratePhoto':
                        {
                            ratePhotoSocket(response);
                        }
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
        // On DOM ready
        $(function()
        {
            initElements();
            $('#accordion').accordion('init');

            uploadAttachedImages();
            deleteUploadedImage();
            addWallRecord();
            likeWallRecord();
            dislikeWallRecord();
            replyToWallRecord();
            makeWallRecordEditable();
            cancelWallRecordEditing();

        });

        function initElements()
        {
            // Init tooltips
//            $('.edit_user_info_btn').qtip
//            ({
//                content:
//                {
////                    title: 'Edit information',
//                    text: 'Click to navigate to page for editing information.'
////                    button: true
//                },
////                hide:
////                {
////                    event: false
////                },
//                style:
//                {
//                    classes: 'qtip-light'
//                },
//                position:
//                {
//                    my: 'bottom left',
//                    at: 'top right',
//                    target: $('.edit_user_info_btn')
//                }
//            });

            // Init photo ratings
            $('.rate', '.photo_rating').knob
            ({
                'width': 75,
                'min': 0,
                'max': 10,
                'thickness': .3,
                'fgColor': '#66CC66',
                'release': ratePhoto
            });

            $('.rating', '.photo_rating').knob
            ({
                'width': 125,
                'min': 0,
                'max': 10,
                'step': .1,
                'readOnly': true,
                'thickness': .3,
                'fgColor': '#27a9ff',
                'skin': 'tron'
            });
        }

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

        function likeWallRecord()
        {
            $('.like_button').click(function()
            {
                var receiverId = $('#user_id').val();
                var wallRecordId = $(this).closest('.wall_record').find('.wall_record_id').val();

                $.ajax
                ({
                    url: './../user_like_dislike_wall_record',
                    type: 'POST',
                    dataType: 'json',
                    data:
                    {
                        wallRecordId: wallRecordId,
                        receiverId: receiverId,
                        page: 'user_' + receiverId,
                        action: LIKE
                    },
                    success: function(response)
                    {
                        if(response.eventData.success)
                        {

                        }
                        else
                        {}

                    }
                });
            });
        }

        function dislikeWallRecord()
        {
            $('.dislike_button').click(function()
            {
                var receiverId = $('#user_id').val();
                var wallRecordId = $(this).closest('.wall_record').find('.wall_record_id').val();

                $.ajax
                ({
                    url: './../user_like_dislike_wall_record',
                    type: 'POST',
                    dataType: 'json',
                    data:
                    {
                        wallRecordId: wallRecordId,
                        receiverId: receiverId,
                        page: 'user_' + receiverId,
                        action: DISLIKE
                    },
                    success: function(response)
                    {
                        if(response.eventData.success)
                        {

                        }
                        else
                        {}

                    }
                });
            });
        }

        function replyToWallRecord()
        {
            $('.wall_rec_reply_button').click(function()
            {
                var receiverId = $('#user_id').val();
                var $wallRecord = $(this).closest('.wall_record');
                var wallRecordId = $wallRecord.find('.wall_record_id').val();
                var parentReplyId = $wallRecord.find('.parent_reply_id').val();
                var replyText = $wallRecord.find('.wall_record_reply_text').val();

                if(replyText.length > 0)
                {
                    $.ajax
                    ({
                        url: './../user_reply_to_wall_record',
                        type: 'POST',
                        dataType: 'json',
                        data:
                        {
                            wallRecordId: wallRecordId,
                            parentReplyId: parentReplyId,
                            replyText: replyText,
                            receiverId: receiverId,
                            page: 'user_' + receiverId

                        },
                        success: function(response)
                        {
                            if(response.eventData.success)
                            {
                                $wallRecord.find('.wall_record_reply_text').val('');
                            }
                            else
                            {}

                        }
                    });
                }
            });
        }

        function ratePhoto(value)
        {
            var photoId = $('#gallery #photo_id').val();
            var receiverId = $('#user_id').val();

            if($('#gallery .photo_rating').is(':hover'))
            {
                $.ajax
                ({
                    url: './../user_rate_photo',
                    type: 'POST',
                    dataType: 'json',
                    data:
                    {
                        photoId: photoId,
                        receiverId: receiverId,
                        rating: value,
                        page: 'user_' + receiverId
                    },
                    success: function()
                    {

                    }
                });
            }
        }

        function makeWallRecordEditable()
        {
            $('.edit_button.wall_record_edit.record_edit').click(function()
            {
                var deleteImageButton = '<input type = "button" class = "delete_button wall_record_delete delete_img"/>';
                var cancelButton = '<input type = "button" class = "cancel_wall_record_edit" value = "Cancel"/>';
                var saveButton = '<input type = "button" class = "save_edited_wall_rec" value = "Save changes"/>';
                var wallRecordEditButtonsContainer = '<div class = "edit_buttons">' +
                    cancelButton + saveButton +'</div>';

                var $wallRecord = $(this).closest('.wall_record').addClass('editable');
                $wallRecord.find('.wall_record_date_time').addClass('editable');

                // Hilde some elements
                $('label', $wallRecord).hide();
                $('.edit_button.wall_record_edit', $wallRecord).hide();
                $('.wall_record_author', $wallRecord).hide();
                $('.wall_record_buttons', $wallRecord).hide();
                $('textarea', $wallRecord).hide();
                $('.wall_rec_reply_button', $wallRecord).hide();
                $('.wall_record_replies_wrapper', $wallRecord).hide();
                $wallRecord.find('.wall_record_content').css({'padding-bottom': 0});

                $wallRecord.find('.title_wrap h1').attr('contenteditable', true);
                $wallRecord.find('.wall_record_text').attr('contenteditable', true);
                if(($wallRecord.find('.wall_record_images .photo').parent().find('.delete_img')).length == 0)
                {
                    $wallRecord.find('.wall_record_images .photo').parent().append(deleteImageButton);
                }
                if($('.edit_buttons', $wallRecord).length == 0)
                {
                    $(wallRecordEditButtonsContainer).insertAfter($('.wall_record_buttons', $wallRecord));
                }

                $('#editable_content_background').fadeIn();
            });
        }

        function cancelWallRecordEditing()
        {
            // TODO: make AJAX call and get all data of wall record by it's id
            $(document).on('click', '.cancel_wall_record_edit', function()
            {
                var $wallRecord = $(this).closest('.wall_record').removeClass('editable');
                $wallRecord.find('.wall_record_date_time').removeClass('editable');

                // Show previously hidden elements
                $('label', $wallRecord).show();
                $('.edit_button.wall_record_edit', $wallRecord).show();
                $('.wall_record_author', $wallRecord).show();
                $('.wall_record_buttons', $wallRecord).show();
                $('textarea', $wallRecord).show();
                $('.wall_rec_reply_button', $wallRecord).show();
                $('.wall_record_replies_wrapper', $wallRecord).show();

                $wallRecord.find('.title_wrap h1').removeAttr('contenteditable');
                $wallRecord.find('.wall_record_text').removeAttr('contenteditable');
                $wallRecord.find('.wall_record_content').removeAttr('style');

                // Remove dynamically added elements
                $('.delete_img', $wallRecord).remove();
                $('.edit_buttons', $wallRecord).remove();

                $('#editable_content_background').fadeOut();
            });
        }


        /* ***** WebSocket ***** */
        function addWallRecordSocket(response)
        {
            var $newWallRecord = $("#wall_record_template").clone(true).removeAttr('id').removeClass('hidden');
            $newWallRecord.find('.wall_record_id').val(response.eventData.newWallRecordId);
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

        function likeDislikeWallRecordSocket(response)
        {
            var $wallRecord = $('input.wall_record_id[value="' + response.eventData.wallRecordId + '"]').closest('.wall_record');
            var likes = $wallRecord.find('.likes').html();
            var dislikes = Math.abs($wallRecord.find('.dislikes').html());
            if(response.eventData.hasLiked && !response.eventData.hasRecord)
            {
                likes++;
            }
            else if(response.eventData.hasLiked && response.eventData.hasRecord
                && response.eventData.likeStatus == 0)
            {
                likes++;
            }
            else if(response.eventData.hasLiked && response.eventData.hasRecord
                && response.eventData.likeStatus == -1)
            {
                dislikes--;
            }
            else if(!response.eventData.hasLiked && !response.eventData.hasRecord)
            {
                dislikes++;
            }
            else if(!response.eventData.hasLiked && response.eventData.hasRecord
                && response.eventData.likeStatus == 0)
            {
                dislikes++;
            }
            else if(!response.eventData.hasLiked && response.eventData.hasRecord
                && response.eventData.likeStatus == 1)
            {
                likes--;
            }
            $wallRecord.find('.likes').html(likes);
            var formattedDislikes = dislikes > 0 ? '-' + dislikes : dislikes;
            $wallRecord.find('.dislikes').html(formattedDislikes);

        }

        function replyToWallRecordSocket(response)
        {
            var $wallRecord = $('input.wall_record_id[value="' + response.eventData.wallRecordId + '"]').closest('.wall_record');
            var $wallRecordRepliesContainer = $wallRecord.find('.wall_record_replies_wrapper');
            var $wallRecordReply = $("#wall_record_reply_template").clone(true).removeAttr('id').removeClass('hidden');
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

            $wallRecordReply.find('.wall_record_reply_author').find('img').attr('src', senderWebPic);
            $wallRecordReply.find('.author_name').html(response.senderData.senderName + ' ' + response.senderData.senderFamily);
            $wallRecordReply.find('.wall_record_text_reply').html(response.eventData.messageText);

            $wallRecordRepliesContainer.append($wallRecordReply);
        }

        function ratePhotoSocket(response)
        {
            if($('#gallery').is(':visible'))
            {
                if($('#gallery #photo_id').val() == response.eventData.photoId)
                {
                    $('#gallery .rating').val(response.eventData.averageRating).trigger('change');
                }
            }
        }

    }
);