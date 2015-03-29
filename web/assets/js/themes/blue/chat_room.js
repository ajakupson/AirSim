define(
    'blue/chat_room',
    [
        'jquery',
        'autobahn',
        'formstone.scroller'
    ],
    function($)
    {
        $('#chat_messages_container').scroller();

        var conn = new ab.Session
        (
            'ws://80.66.252.45:8080', // The host (our Ratchet WebSocket server) to connect to
            function()
            {
                // Once the connection has been established
                console.log('WebSocket connection is opened');

                var chatId = $('#chat_id').val();
                conn.subscribe('chat_' + chatId, function(topic, response)
                {
                    var event = response.eventData.event;
                    switch(event)
                    {
                        case SEND_MESSAGE:
                        {
                            sendMessageSocket(response);
                        }break;
                        case READ_MESSAGE:
                        {
                            //readMessageSocket(response);
                        }break;
                        case DELETE_MESSAGE:
                        {
                            //deleteMessageSocket(response);
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

        $(function()
        {
            sendMessage();
        });

        function sendMessage()
        {
            $('#send_chat_message').click(function()
            {
                var messageText = $('#chat_message_textarea').val();
                var chatId = $('#chat_id').val();
                var receiverId = $('#participant_id').val();

                if(messageText.length > 0)
                {
                    $.ajax
                    ({
                        url: './../messages_send_message',
                        type: 'POST',
                        dataType: 'json',
                        data:
                        {
                            chatId: chatId,
                            messageText: messageText,
                            receiverId: receiverId
                        },
                        success: function(response)
                        {
                            if(response.success)
                            {
                                setTimeout(function()
                                {
                                    $('#chat_messages_container').scrollTop($('#chat_messages_container')[0].scrollHeight);
                                }, 1000);

                            }
                        }
                    });
                }
                else
                {

                }
            });
        }

        /*** WebSocket ***/
        function sendMessageSocket(response)
        {
            var messagesContainer = $('#chat_messages_container');
            var userId = $('#user_id').val();

            var message = '<div class = "message_participant' + (userId == response.receiverData.receiverId ? '_two' : '_one') + ' unread_message">';
            message += '<span class = "message_date_time' + (userId == response.receiverData.receiverId ? '_two' : '_one') + '">' + response.eventData.dateTime + '</span>';
            message += '<span class = "message_text">' + response.eventData.messageText + '</span>';
            if(userId != response.receiverData.receiverId)
            {
                message += '<input type = "button" class = "delete_message_icon" value = ""/><br/>';
                message += '<input type = "checkbox" class = "message_select" id = "m_' + response.eventData.messageId + '" unchecked/>';
                message += '<label for = "m_' + response.eventData.messageId + '"><span></span></label>';
            }
            message += '<input type = "hidden" class = "message_id" value = "' + response.eventData.messageId + '"/>';
            message += '<div class = "clear"></div>'
            message += '</div>';

            messagesContainer.append(message);
            var height = messagesContainer[0].scrollHeight;

            //$('#chat_messages_container').scroller('reset');
            //$('#chat_messages_container').scroller('scroll', height, 500);

            $('#chat_messages_container').scroller('destroy');
            $('#chat_messages_container').scroller();
            $('#chat_messages_container').scroller('scroll', height, 500);

            //messagesContainer.scrollTop(height);
        }

    }
)