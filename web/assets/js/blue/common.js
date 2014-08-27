$(document).ready(function()
{
    // Setup AJAX error handling
    $.ajaxSetup({
        error: function(XMLHttpRequest, status, error)
        {
            $('#error_message_dialog').errDialogOpen(XMLHttpRequest, status, error);
        }
    });
});