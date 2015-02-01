//require.config({
//    paths : {
//        async: './../plugins/require-js-async-0.1.2'
//    }
//});

define(
    'blue/map',
    [
        'jquery',
        'here',
        'here.markers',
        'here.geocode'
    ],
    function($)
    {
        $(function()
        {
            setTimeout(function()
            {
                initNokiaMap();
            }, 10000);

            // Tooltips
            /*$('a').each(function(){
                $(this).qtip({ // Grab some elements to apply the tooltip to
                    content: {
                        text: 'My common piece of text here'
                    },
                    style: {
                        classes: 'qtip-light'
                    },
                    position: {
                        my: 'top center',
                        at: 'top center',
                        target: $(this).parent()
                    }
                });
            });*/

            addMarker();
            refreshMap();
        });

        // Private functions
        function initNokiaMap()
        {
            $('#map_container').jHERE(
            {
                enable: ['behavior', 'zoombar', 'scalebar', 'rightclick', 'contextmenu'],
                zoom: 10,
                center: {latitude: 29.976480, longitude: 31.131302},
                type: 'map', // can be map (the default), satellite, terrain, smart, pt.
                // API credentials from Nokia developer website
                appId: 'URXmsqAlIxjr41aSiojS', //appId from the Nokia developer website,
                authToken: '7w1lsMEuL9ToVILvmS8vLA'
            });

            $('#map_container').jHERE('originalMap', function(map, here)
            {
                var restrict = function(evt) {

                    var limits = {minLat: -90, minLon: -180, maxLat: 90, maxLon: 180};

                    if (map.center.latitude < limits.minLat || map.center.latitude > limits.maxLat) {

                        var latitude =  Math.max(Math.min(map.center.latitude, limits.maxLat), limits.minLat);
                        var longitude = Math.max(Math.min(map.center.longitude, limits.maxLon), limits.minLon);
                        map.setCenter(new nokia.maps.geo.Coordinate(latitude,longitude));
                        evt.cancel();
                    }
                }


                map.addListener("dragend", restrict);
                map.addListener("drag", restrict);
//                map.addListener("mapviewchange", restrict);
//                map.addListener("mapviewchangeend", restrict);
//                map.addListener("mapviewchangestart", restrict);

                //map.addObserver("zoomLevel", function(obj, key, newValue, oldValue) { if (newValue < 5) map.set("zoomLevel", 5);});
                map.set('minZoomLevel', 1.5);
            });
        }

        function addMarker()
        {
            $('#mark_location', '#map').click(function()
            {
                var address = $.trim($('input[name="location"]', '#map').val());

                if(address != null && address != '')
                {
                    var comment = $('input[name="comment"]', '#map').val();
                    var latitude = null;
                    var longitude = null;

                    $.jHERE.geocode(address, function(position)
                    {
                        latitude = position.latitude;
                        longitude = position.longitude;

                        $.ajax
                        ({
                            type: 'POST',
                            dataType: 'json',
                            url: './../map_add_marker',
                            data:
                            {
                                address: address,
                                latitude: latitude,
                                longitude: longitude,
                                comment: comment
                            },
                            success: function(response)
                            {
                                if(response.success)
                                {
                                    setMaker(response.data.markerData);
                                }
                                else
                                {

                                }

                            }
                        });
                    },
                    function()
                    {
                        // TODO: Show error
                        return false;
                    });

                }

            });
        }

        function setMaker(markerData)
        {
            var latitude = parseFloat(markerData.latitude);
            var longitude = parseFloat(markerData.longitude);
            var positionObject = [latitude, longitude];

            var $bubble = $('#bubble_template').clone(true).removeAttr('id').removeClass('hidden');
            var profilePic;
            if(markerData.userWebData.webProfilePic != null)
            {
                profilePic = './../../public_files/user_' + markerData.userWebData.userId + '/albums/profile_pics/'
                    + markerData.userWebData.webProfilePic;
            }
            else
            {
                profilePic = APP_DEFAULT_AVATAR_MALE;
            }
            $bubble.find('.web_profile_pic img').attr('src', profilePic);
            $bubble.find('.username').html(markerData.userWebData.name + ' ' + markerData.userWebData.family);
            $bubble.find('.comment').html(markerData.comment);

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

        function refreshMap()
        {
            $('#refresh_map').click(function()
            {
                $('#map_container').jHERE('originalMap', function(map, here)
                {
                    map.update(-1, true);
                });
            });
        }
    }
);