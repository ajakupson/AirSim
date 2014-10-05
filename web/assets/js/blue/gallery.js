(function($){

    var $commentTemplate =
        $('<div class = "photo_comment">' +
            '<div class = "comment_author_pic"><img/></div>' +
            '<div class = "comment_data">' +
                '<div class = "comment_author"></div>' +
                '<div class = "comment_date"></div>' +
                '<article class = "comment_text"></article>' +
        '</div><p class = "clear"></p></div>');


    // Default settings
    var settings =
    {
        'folderPath': '',
        'appDefaultFolderPath': '',
        'url': '',
        'onShowCallback': function(){}
    };

    var methods =
    {
        init: function(options)
        {
            settings = $.extend
            ({

            }, options);

            switchPhoto();
        },
        show: function(params)
        {
            getPhotoData(params.photoId);
        }
    };

    function getPhotoData(photoId)
    {
        $.ajax
        ({
            type: 'POST',
            dataType: 'json',
            url: settings.url,
            data:
            {
                photoId: photoId
            },
            success: function(response)
            {
                var $photoContainer = $('.photo_container');
                var $img = $photoContainer.find('.photo_to_show');

                var imagePath = settings.folderPath + response.data.photoData.userId + '/albums/' + response.data.photoData.albumName + '/' +
                    response.data.photoData.photoName;
                var prevPhotoId = response.data.photoData.previousPhotoId;
                var nextPhotoId = response.data.photoData.nextPhotoId;

                if(prevPhotoId.length == 0)
                    $('.previous_photo').hide();
                else
                {
                    $('#previous_photo_id').val(prevPhotoId);
                    $('.previous_photo').show();
                }

                if(nextPhotoId.length == 0)
                    $('.next_photo').hide();
                else
                {
                    $('#next_photo_id').val(nextPhotoId);
                    $('.next_photo').show();
                }

                $img.attr('src', imagePath);

                /* Info */
                var albumTitle = response.data.photoData.albumTitle;
                var albumId = response.data.photoData.albumId;
                var photoTitle = response.data.photoData.photoTitle;
                var photoDescription = response.data.photoData.photoDescription;
                var dateAdded = response.data.photoData.photoDateAdded;
                var photoLocation = response.data.photoData.photoAddress;
                var undefined = '..........';

                $('#album_title').html((albumTitle != null) ? albumTitle : undefined);
                $('#photo_title').html(photoTitle != null ? photoTitle : undefined);
                $('#photo_description').html(photoDescription);
                $('#photo_date_added').html(dateAdded != null ? dateAdded : undefined);
                $('#photo_location').html(photoLocation != null ? photoLocation : undefined);
                $('#photo_id').val(photoId);

                var $commentsHolder = $('.photo_comments_holder');
                $commentsHolder.html('');
                var ownerId = $('#userId').val();

                var $comment = null;
                $.each(response.data.photoData.photoComments, function(key, value)
                {
                    $comment = $commentTemplate.clone();
                    var img = null;
                    if(value.authorWebProfilePic != null)
                    {
                        img = settings.folderPath + response.data.photoData.userId + '/albums/profile_pics/' + value.authorWebProfilePic;
                    }
                    else
                    {
                        img = settings.appDefaultFolderPath + 'no_avatar_male.png';
                    }

                    $comment.find('img').attr('src', img);
                    $comment.find('.comment_author').html(value.authorName + ' ' + value.authorFamily);
                    $comment.find('.comment_date').html(value.commentDateAdded);
                    $comment.find('.comment_text').html(value.commentText);

                    $commentsHolder.prepend($comment);

                });

                settings.onShowCallback();

            }
        });
    }

    function switchPhoto()
    {
        $('.previous_photo, .next_photo').click(function()
        {
            var previousOrNextPhotoId = $(this).find('input[type="hidden"]').val();
            getPhotoData(previousOrNextPhotoId);
        });
    }

    $.fn.gallery = function(method)
    {
        if(methods[method])
        {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if(typeof method === 'object' || !method)
        {
            return methods.init.apply(this, arguments);
        }
        else
        {
            $.error('Method with name ' + method + ' does not exist for jQuery.gallery');
        }
    };

}(jQuery));