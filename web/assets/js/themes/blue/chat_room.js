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
                console.log('CHAT_ROOM WebSocket connection is opened');

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
                            readMessageSocket(response);
                        }break;
                        case DELETE_MESSAGE:
                        {
                            deleteMessageSocket(response);
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

        var chatId = null;

        $(function()
        {
            chatId = $('#chat_id').val();

            sendMessage();
            readMessage();
            deleteMessage();
        });

        function sendMessage()
        {
            $('#send_chat_message').click(function()
            {
                var messageText = $('#chat_message_textarea').val();
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
                            if(response.eventData.success)
                            {
                                setTimeout(function()
                                {
                                    $('#chat_messages_container').scrollTop($('#chat_messages_container')[0].scrollHeight);
                                }, 200);

                            }
                        }
                    });
                }
                else
                {

                }
            });
        }

        function readMessage()
        {
            $('.chat_messages').on('mouseover', '.message_participant_two.unread_message', function()
            {
                var $message = $(this);
                var messageId = $message.find('.message_id').val();

                $.ajax
                ({
                    url: './../messages_read_message',
                    data:
                    {
                        chatId: chatId,
                        messageId: messageId
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(response)
                    {
                        if(response.eventData.success)
                        {
                            $message.removeClass('unread_message');
                            // TODO: update unread messages bubbles
                        }
                    }
                });
            });
        }

        function deleteMessage()
        {
            var messageId = null;
            var $messageContainer = null;

            $('body').on('click', '.delete_message_icon', function()
            {
                $messageContainer = $(this).closest('.message_participant_one');
                messageId = $messageContainer.find('.message_id').val();

                $('#confirmation_dialog').dialog('open',
                {
                    isConfirmation: true,
                    confirmationDialogTitle: 'Messages Delete Confirmation',
                    confirmationText: 'Are You sure about deleting chosen message?',
                    onConfirmation: onDeleteConfirm
                });
            });

            function onDeleteConfirm() {

                $.ajax
                ({
                    url: './../messages_delete_message',
                    data:
                    {
                        chatId: chatId,
                        messageId: messageId
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(response)
                    {
                        if(response.eventData.success) {
                            $messageContainer.html('<span class = "normal_text">Message has been deleted!</span>').parent().removeClass('unread_message');
                        }
                    }
                });
            }

        }

        /*** WebSocket ***/
        function sendMessageSocket(response)
        {
            var messagesContainer = $('#chat_messages_container');
            var userId = $('#user_id').val();

            var message;
            if(userId !== response.receiverData.receiverId) {
                message = TEMPLATE_CHAT_ROOM_MESSAGE_ONE.format(response.eventData.dateTime, response.eventData.messageText, response.eventData.messageId,
                    response.eventData.messageId, response.eventData.messageId);
            } else {
                message = TEMPLATE_CHAT_ROOM_MESSAGE_TWO.format(response.eventData.dateTime, response.eventData.messageText, response.eventData.messageId,
                    response.eventData.messageId, response.eventData.messageId);
            }

            messagesContainer.append(message);
            var height = messagesContainer[0].scrollHeight;

            $('#chat_messages_container').scroller('destroy');
            $('#chat_messages_container').scroller();
            $('#chat_messages_container').scroller('scroll', height, 500);
        }

        function readMessageSocket(response)
        {
            $.each($('.message_participant_one.unread_message'), function()
            {
                var messageId = $(this).find('.message_id').val();
                if(messageId == response.eventData.messageId)
                {
                    $(this).removeClass('unread_message');
                }
            });
        }

        function deleteMessageSocket(response)
        {
            $.each($('.message_participant_two'), function()
            {
                var messageId = $(this).find('.message_id').val();
                if(messageId === response.eventData.messageId)
                {
                    $(this).html('<span class = "normal_text">Message has been deleted!</span>').parent().removeClass('unread_message');
                }
            });
        }

    }
)