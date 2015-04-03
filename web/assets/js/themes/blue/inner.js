define(
    'blue/inner',
    [
        'jquery',
        'jquery.knob',
        'jquery.selectric',
//        'jquery.qtip',
        'formstone.scroller',
        'blue/html_templates',
        'blue/constants',
        'blue/common_functions',
        'blue/common',
        'blue/notification',
        'blue/dialog',
        'blue/gallery',
        'blue/map',
        'blue/attachments'
    ],
    function($)
    {
        // On document ready
        $(function()
        {
            menuItemAddClassSelected();
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
                top: '-95px',
                height: 'auto'
            });

            // Init gallery
            $('#gallery').gallery('init',
            {
                'url': './../user_get_photo_data',
                'folderPath' : './../../public_files/user_',
                'appDefaultFolderPath': './../../public_files/app_default/',
                'onShowCallback': function()
                {
                    $('#gallery').dialog('open');

                    $('#gallery_comments_scroller').scroller('destroy');
                    setTimeout(function()
                    {
                        $('#gallery_comments_scroller').scroller();
                    }, 500);

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

            // Map
            $('#map').dialog('init',
            {
                top: '-60px',
                draggable: true,
                onDialogOpen: onMapDialogOpenEvent
            });
            $('#map_link').click(function(event)
            {
                event.preventDefault();
                $('#map').dialog('open');
            });

            // Write message
            $('#write_message_dialog').dialog('init',
            {   top: '-10px',
                height: 500,
                draggable: true
            });
            $('body').on('click', '.write_message', function()
            {
                $('#write_message_dialog').dialog('open');
            });

            // Confirmation
            $('#confirmation_dialog').dialog('init',
            {   top: '-10px',
                width: 300,
                draggable: false,
                isConfirmation: true
            });

            //$('#test_div').attachments('init');

            // Formstone
            $('.formstone_scroller').scroller();

            function onMapDialogOpenEvent()
            {
                $.ajax
                ({
                    type: 'POST',
                    dataType: 'json',
                    url: './../get_friends_markers',
                    success: function(response)
                    {
                        if(response.success)
                        {
                            $.each(response.data.markersData, function(key, marker)
                            {
                                var latitude = parseFloat(marker.latitude);
                                var longitude =  parseFloat(marker.longitude);
                                var positionObject = [latitude, longitude];

                                var $bubble = $('#bubble_template').clone(true).removeAttr('id').removeClass('hidden');
                                var profilePic;
                                if(marker.userWebData.webProfilePic != null)
                                {
                                    profilePic = './../../public_files/user_' + marker.userWebData.userId + '/albums/profile_pics/'
                                        + marker.userWebData.webProfilePic;
                                }
                                else
                                {
                                    profilePic = APP_DEFAULT_AVATAR_MALE;
                                }
                                $bubble.find('.web_profile_pic img').attr('src', profilePic);
                                $bubble.find('.username').html(marker.userWebData.name + ' ' + marker.userWebData.family);
                                $bubble.find('.comment').html(marker.comment);

                                var markerOptions =
                                {
                                    text: '!',
                                    textColor: '#333333',
                                    fill: '#27a9ff',
                                    stroke: '#333333',
                                    //icon: '',
                                    anchor: {x: 12, y: 18}, //an icon 24x36 would result centered
                                    mouseover: function(event)
                                    {
                                        $('#map_container').jHERE('bubble', event.geo, {
                                            content: $bubble.get(0)
                                        });
                                    }
                                }

                                // Set marker
                                $('#map_container').jHERE('marker', positionObject, markerOptions);

                                // Center marker
                                /*$('#map_container').jHERE('originalMap', function(map, here)
                                {
                                    map.set('center', positionObject);
                                    map.update(-1, true);
                                });*/
                            });
                        }
                    }
                });

//                if(!isMapInit)
//                {
//                    setInterval(onMapDialogOpenEvent(), 60000);
//                }
//                isMapInit = true;
            }

            function onMapDialogOpenEvent_Old()
            {
                $.ajax
                ({
                    type: 'POST',
                    dataType: 'json',
                    url: './../get_user_marker',
                    success: function(response)
                    {
                        if(response.success)
                        {
                            var latitude = parseFloat(response.data.markerData.latitude);
                            var longitude =  parseFloat(response.data.markerData.longitude);
                            var positionObject = [latitude, longitude];

                            var $bubble = $('#bubble_template').clone(true).removeAttr('id').removeClass('hidden');
                            $bubble.find('.comment').html(response.data.markerData.comment);

                            var markerOptions =
                            {
                                text: '!',
                                textColor: '#333333',
                                fill: '#27a9ff',
                                stroke: '#333333',
                                //icon: '',
                                anchor: {x: 12, y: 18}, //an icon 24x36 would result centered
                                mouseover: function(event)
                                {
                                    $('#map_container').jHERE('bubble', event.geo, {
                                        content: $bubble.get(0)
                                    });
                                }
                            }

                            // Set marker
                            $('#map_container').jHERE('marker', positionObject, markerOptions);

                            // Center marker
                            $('#map_container').jHERE('originalMap', function(map, here)
                            {
                                map.set('center', positionObject);
                                map.update(-1, true);
                            });

                        }
                    }
                });

            }

            // Error Dialog
            $('#error_message_dialog').errDialogInit();
        }

        function menuItemAddClassSelected()
        {
            var pageUrlArray = window.location.href.split('/');
            var selectedMenuItem = pageUrlArray[pageUrlArray.length - 2];
            switch(selectedMenuItem)
            {
                case 'user':
                {
                    $('.home').parent().addClass('selected');
                }break;
                case 'contacts':
                {
                    $('.friends').parent().addClass('selected');
                }break;
                case 'options':
                {
                    $('.options').parent().addClass('selected');
                }; break;
                default: break;
            }
        }
    }
);