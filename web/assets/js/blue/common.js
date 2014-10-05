$(document).ready(function()
{
    // Resize font when size of the window is changed - possible solution
    /*$(window).resize(function()
    {
        $('body').css('font-size', ($(window).width() * 0.0085) + 'px');
    });*/

    // Setup AJAX error handling
    $.ajaxSetup({
        error: function(XMLHttpRequest, status, error)
        {
            $('#error_message_dialog').errDialogOpen(XMLHttpRequest, status, error);
        }
    });
});