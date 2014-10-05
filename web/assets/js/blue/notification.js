var notificationConn = new ab.Session
(
    'ws://127.0.0.1:8080', // The host (our Ratchet WebSocket server) to connect to
    function()
    {
        // Once the connection has been established
        console.log('WebSocket connection is opened');

        var loggedInUserId = $('#logged_in_user_id').val();
        notificationConn.subscribe('logged_in_user_' + loggedInUserId, function(topic, response)
        {
            var event = response.eventData.event;
//            switch(event)
//            {
//                case 'sendMessage':
//                {
//                    unreadMessagesPlus();
//
//                    // If in Chat Room
//                    chatRoom(response);
//                }break;
//                default:break;
//            }
            showNotification(response);
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

function showNotification(response)
{
    var $notificationBox = $('#notification_box_template').clone(true).removeAttr('id').removeClass('hidden');
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

    $notificationBox.find('.sender_pic').attr('src', senderWebPic);
    $notificationBox.find('.notification_info').html(response.eventData.notificationInfo);

    var messageTextToShow = null;
    if(response.eventData.messageText.length > 100)
    {
        messageTextToShow = response.eventData.messageText.substr(0, 96) + ' ...';
    }
    else
    {
        messageTextToShow = response.eventData.messageText;
    }
    $notificationBox.find('.notification_message').html(messageTextToShow);



    $('.notifications_wrapper ul').prepend($notificationBox.fadeIn(500));
    $('#notification_sound').get(0).play();

}