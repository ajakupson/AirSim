define
(
    'blue/contacts',
    ['jquery', 'blue/tabs'],
    function($)
    {

        // On DOM ready
        $(function()
        {
            $('#contacts_tabs').tabs('init',
            {
                tabsContent: $('#contacts_tabs_content')
            });
            searchContacts();
            sendFriendRequest();
        });

        function searchContacts()
        {
            $('#search_friend').click(function()
            {
                searchContactsAjax(true);
            });

            $('#search_contact').click(function()
            {
                searchContactsAjax(false);
            });

            function searchContactsAjax(isFriend)
            {
                if(isFriend)
                {
                    $('.friends_block_wrap').empty();
                    $('#loading_friends').show();

                    var nameAndOrFamily = $('#search_friend_text').val();
                    var gender = $('input[name="gender_f"]:checked').val();
                    var phoneNumber = $('input[name="phone_number_f"]').val();
                    var country = $('#country_f').val();
                    var city = $('#city_f').val();
                    var ageFrom = $('#age_from_f').val();
                    var ageTo = $('#age_to_f').val();
                }
                else
                {
                    $('.contacts_block_wrap').empty();
                    $('#loading_contacts').show();

                    var nameAndOrFamily = $('#search_contact_text').val();
                    var gender = $('input[name="gender_c"]:checked').val();
                    var phoneNumber = $('input[name="phone_number_c"]').val();
                    var country = $('#country_c').val();
                    var city = $('#city_c').val();
                    var ageFrom = $('#age_from_c').val();
                    var ageTo = $('#age_to_c').val();
                }

                $.ajax
                ({
                    url: './../contacts_search_contacts',
                    type: 'POST',
                    dataType: 'json',
                    data:
                    {
                        nameAndOrFamily: nameAndOrFamily,
                        gender: gender,
                        phoneNumber: phoneNumber,
                        country: country,
                        city: city,
                        ageFrom: ageFrom,
                        ageTo: ageTo,
                        isFriend: isFriend
                    },
                    success: function(response)
                    {
                        isFriend ? $('#loading_friends').hide() : $('#loading_contacts').hide();

                        if(response.success)
                        {
                            if(isFriend)
                            {
                                appendFriends(response);
                            }
                            else
                            {
                                appendContacts(response);
                            }
                        }
                        else
                        {
                            console.error('response error');
                        }
                    }
                });

                function appendFriends(response)
                {
                    var $friendTemplate = $('#friend_template').clone(true).removeAttr('id').removeClass('hidden');
                    var $foundFriendsToAppend = '';

                    $.each(response.foundContacts, function(index, value)
                    {
                        var $friendRecord = $friendTemplate;
                        $friendRecord.find('.friend_id').val(value.userId);
                        $friendRecord.find('.name').html(value.name + ' ' + value.family);
                        $friendRecord.find('.phone_number').html(value.phoneNumber);

                        if(value.webProfilePic !== null)
                        {
                            $friendRecord.find('img').attr('src', './../../public_files/user_' + value.userId + '/albums/'
                                + PROFILE_PICS_ALBUM_NAME + '/' + value.webProfilePic);
                        }
                        else
                        {
                            $friendRecord.find('img').attr('src', './../../public_files/app_default/no_avatar_male.png');
                        }

                        $friendRecord.find('input[type="checkbox"]').attr('id', 'f_id_' + value.userId);
                        $friendRecord.find('label').attr('for', 'f_id_' + value.userId);


                        $foundFriendsToAppend += $friendRecord[0].outerHTML;
                    });

                    $('.friends_block_wrap').append($foundFriendsToAppend);
                }

                function appendContacts(response)
                {
                    var $contactTemplate = $('#contact_template').clone(true).removeAttr('id').removeClass('hidden');
                    var $foundContactsToAppend = '';

                    $.each(response.foundContacts, function(index, value)
                    {
                        var $contactRecord = $contactTemplate;
                        $contactRecord.find('.contact_id').val(value.userId);
                        $contactRecord.find('.name').html(value.name + ' ' + value.family);
                        $contactRecord.find('.phone_number').html(value.phoneNumber);

                        if(value.webProfilePic !== null)
                        {
                            $contactRecord.find('img').attr('src', './../../public_files/user_' + value.userId + '/albums/'
                                + PROFILE_PICS_ALBUM_NAME + '/' + value.webProfilePic);
                        }
                        else
                        {
                            $contactRecord.find('img').attr('src', './../../public_files/app_default/no_avatar_male.png');
                        }

                        $foundContactsToAppend += $contactRecord[0].outerHTML;
                    });

                    $('.contacts_block_wrap').append($foundContactsToAppend);
                }
            }
        }

        function sendFriendRequest()
        {
            $('body').on('click', '.add_to_friends', function()
            {
                var receiverId = $(this).closest('.contact').find('.contact_id').val();

                $.ajax
                ({
                    url: './../contacts_add_contacts',
                    type: 'POST',
                    dataType: 'json',
                    data:
                    {
                        receiverId: receiverId
                    },
                    success: function(response)
                    {

                    }
                });
            });

        }

        /*$('#searchFriendText').on('input', function()
        {
            var searchText = $(this).val();
            $.ajax
            ({
                url: './../../contacts_search_friend',
                type: 'POST',
                dataType: 'json',
                data:
                {
                    searchText: searchText
                },
                success: function(response)
                {
                    if(response.success)
                    {

                    }
                    else
                    {
                        console.error('response error');
                    }
                },
                error: function()
                {
                    console.error('error');
                }
            });
        });*/
    }
);