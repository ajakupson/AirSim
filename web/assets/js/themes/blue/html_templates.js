define
(
    'blue/html_templates',
    function()
    {
        // Chat room
        this.TEMPLATE_CHAT_ROOM_MESSAGE_ONE = '<div class="message_participant_one unread_message">' +
            '<span class = "message_date_time_one">{0}</span>' +
            '<span class = "message_text">{1}</span>' +
            '<input type = "button" class = "delete_message_icon" value = ""/><br/>' +
            '<input type = "checkbox" class = "message_select" id = "m_{2}" unchecked/>' +
            '<label for = "m_{3}"><span></span></label>' +
            '<input type = "hidden" class = "message_id" value = "{4}"/>' +
            '<div class = "clear"></div></div>';

        this.TEMPLATE_CHAT_ROOM_MESSAGE_TWO = '<div class="message_participant_two unread_message">' +
            '<span class = "message_date_time_two">{0}</span>' +
            '<span class = "message_text">{1}</span>' +
            '<input type = "hidden" class = "message_id" value = "{4}"/>' +
            '<div class = "clear"></div></div>';
    }
);