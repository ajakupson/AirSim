define(
    'blue/dialog',
    ['jquery'],
    function($)
    {
        // Default setting
        var settings = {};
        var defaults =
        {
            top: '-1px',
            width: '100%',
            draggable: true,
            onDialogOpen: null,
            onDialogClose: null
        }

        // Simple dialog
        var methods =
        {
            init: function(options)
            {
                return this.each(function()
                {
                    var dialog = $(this);
                    var dialogHeader = dialog.find('.dialog_title');

                    settings = $.extend
                    (true, { height: dialog.outerHeight() }, defaults, options);

                    $(this).data("settings", settings);

                    // make dialog draggable
                    // TODO: works, but has to be improved
                    if(settings.draggable)
                    {
                        dialogHeader.on('mousedown', function(e)
                        {
                            var prevMousePosX = e.pageX;
                            var prevMousePosY = e.pageY;

                            dialog.addClass('draggable');
                            $('.draggable').children().on('mousemove', function(e)
                            {
                                dialog.addClass('no_transition');

                                var dialogPosX = dialog.offset().left;
                                var dialogPosY = dialog.offset().top;

                                var curMousePosX = e.pageX;
                                var curMousePosY = e.pageY;

                                if($('.draggable').is(':hover'))
                                {

                                    $('.draggable').offset
                                    ({
                                        left: dialogPosX + (curMousePosX - prevMousePosX),
                                        top: dialogPosY + (curMousePosY - prevMousePosY)
                                    })
                                    .on('mouseup', function()
                                    {
                                        $('.draggable').children().unbind('mousemove');
                                        dialog.removeClass('draggable');
                                        dialog.removeClass('no_transition');
                                    });
                                }

                                prevMousePosX = curMousePosX;
                                prevMousePosY = curMousePosY;
                            });
                            e.preventDefault(); // prevent element selection
                        })
                        .on('mouseup', function()
                        {
                            $('.draggable').children().unbind('mousemove');
                            dialog.removeClass('draggable');
                            dialog.removeClass('no_transition');
                        });
                    }

                    // close dialog event
                    $('.dialog_close', dialog).click(function()
                    {
                        dialog.dialog('close');
                    });

                    return dialog.css
                    ({
                        top: settings.top,
                        width: settings.width,
                        height: settings.height
                    });

                });
            },
            open: function()
            {
               if($(this).not(':visible'))
               {
                    var settings = $(this).data("settings");

                    if(settings.onDialogOpen != null)
                    {
                        settings.onDialogOpen();
                    }

                    $('body').addClass('scrolling_of');
                    var dialogBackground = $('#dialog_background');

                    var cssTop = parseInt(settings.top, 10);
                    this.css
                    ({
                        top: cssTop + $(document).scrollTop()
                    });
                    this.fadeIn();
                    this.removeClass('scale');
                    dialogBackground.fadeIn();

                    return this;
               }
            },
            close: function()
            {
                $('body').removeClass('scrolling_of');
                var dialogBackground = $('#dialog_background');

                this.hide();
                this.addClass('scale');
                dialogBackground.fadeOut();

                if(settings.onDialogClose != null)
                {
                    settings.onDialogClose();
                }

                return this;
            }
        };

        $.fn.dialog = function(method)
        {
            if(methods[method])
            {
                return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
            }
            else if(typeof method === 'object' || !method)
            {
                return methods.init.apply(this, arguments);
            }
            else
            {
                $.error('Method with name ' + method + ' does not exist for jQuery.dialog');
            }
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
            this.css
            ({
                top: $(document).scrollTop()
            });

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
    }
);