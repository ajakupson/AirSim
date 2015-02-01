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
        });

        function searchContacts()
        {
            $('#search_friend').click(function()
            {
                searchContactsAjax(true);
            });

            function searchContactsAjax(isFriend)
            {
                $('.friends_block_wrap').empty();
                $('#loading_contacts').show();

                var nameAndOrFamily = $('#search_friend_text').val();
                var gender = $('input[name="gender"]:checked').val();
                var phoneNumber = $('input[name="phone_number"]').val();
                var country = $('#country').val();
                var city = $('#city').val();
                var ageFrom = $('#age_from').val();
                var ageTo = $('#age_to').val();

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
                        $('#loading_contacts').hide();

                        if(response.success)
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
                        else
                        {
                            console.error('response error');
                        }
                    }
                });
            }
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