(function($){

    var dialogBackground;

    // Simple dialog
    $.fn.dialogInit = function(options)
    {

        var dialog = this;
        var dialogHeader = this.find('.dialog_title');
        dialogBackground = $('#dialog_background');

        var settings = $.extend
        ({
            top: '-1px',
            width: '100%',
            height: dialog.outerHeight(),
            draggable: false
        }, options);

        // make dialog draggable
        // TODO: works, but has to be improved
        if(settings.draggable)
        {
            $(dialogHeader).on('mousedown', function(e)
            {
                var prevMousePosX = e.pageX;
                var prevMousePosY = e.pageY;

                $(dialog).addClass('draggable');
                $('.draggable').parents().on('mousemove', function(e)
                {
                    var dialogPosX = dialog.offset().left;
                    var dialogPosY = dialog.offset().top;

                    var curMousePosX = e.pageX;
                    var curMousePosY = e.pageY;

                    $('.draggable').offset
                    ({
                        left: dialogPosX + (curMousePosX - prevMousePosX),
                        top: dialogPosY + (curMousePosY - prevMousePosY)
                    })
                    .on('mouseup', function()
                    {
                        $(dialog).removeClass('draggable');
                    });

                    prevMousePosX = curMousePosX;
                    prevMousePosY = curMousePosY;
                });
                e.preventDefault(); // prevent element selection
            })
            .on('mouseup', function()
            {
                $(dialog).removeClass('draggable');
            });
        }

        // close dialog event
        $('.dialog_close', dialog).click(function()
        {
           dialog.dialogClose();
        });

        return this.css
        ({
            top: settings.top,
            width: settings.width,
            height: settings.height
        });

    };

    $.fn.dialogOpen = function()
    {
        this.fadeIn();
        this.removeClass('scale');
        dialogBackground.fadeIn();
    };

    $.fn.dialogClose = function()
    {
        this.hide();
        this.addClass('scale');
        dialogBackground.fadeOut();
    };

    // TODO : improve
    // Error dialog
    $.fn.errDialogInit = function(options)
    {
        var dialog = this;

        var settings = $.extend
        ({
        }, options);

        // close error dialog event
        $('.error_message_dialog_close', dialog).click(function()
        {
            dialog.errDialogClose();
        });

    };

    $.fn.errDialogOpen = function(XMLHttpRequest, status, error)
    {
        this.find('#error_message_dialog_title_text').html(status + ': ' + error);
        this.find('#error_message_dialog_content').html(XMLHttpRequest.responseText);
        this.fadeIn();
        dialogBackground.fadeIn();
    };

    $.fn.errDialogClose = function()
    {
        this.find('#error_message_dialog_content').html('');
        this.fadeOut();
        dialogBackground.fadeOut();
    }


}(jQuery));