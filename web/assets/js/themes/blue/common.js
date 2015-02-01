define
(
    'blue/common',
    [
        'jquery',
        'jquery.autosize',
    ],
    function($)
    {
        $(window).load(function()
        {
            $('#page_cover').fadeOut('slow');
        });

        $(function()
        {
            $('select').selectric();

            // Resize font when size of the window is changed - possible solution
            /*$(window).resize(function()
             {
             $('body').css('font-size', ($(window).width() * 0.0085) + 'px');
             });*/

            $('textarea').attr('maxlength', 10000).autosize();

            // Setup AJAX error handling
            $.ajaxSetup
            ({
                global: true,
                error: function(XMLHttpRequest, status, error)
                {
                    $('#error_message_dialog').errDialogOpen(XMLHttpRequest, status, error);
                }
            });
        });
    }
);