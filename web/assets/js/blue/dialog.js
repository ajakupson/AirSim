(function($){

    var dialogBackground;

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
//            animation: 'grow'
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

}(jQuery));