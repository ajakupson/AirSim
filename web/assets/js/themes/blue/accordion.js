define(
    'blue/accordion',
    ['jquery'],

    function($)
    {

        // Default settings
        var settings =
        {
            'isTogglingEnabled': false
        };

        // Functions
        var methods =
        {
            init: function(options)
            {
                settings = $.extend
                ({

                }, options);

                showHideTabs();
            }
        };

        function showHideTabs()
        {
            $('#accordion').find('.accordion_tab').click(function()
            {
                // Expand or collapse this panel
                $(this).next().slideToggle('slow');
                if($(this).hasClass('active'))
                {
                    $(this).removeClass('active');
                }
                else
                {
                    $(this).addClass('active');
                }

                if($(this).find('input[type="button"]').hasClass('expand_button'))
                {
                    $(this).find('input[type="button"]').removeClass('expand_button').addClass('collapse_button');
                }
                else
                {
                    $(this).find('input[type="button"]').removeClass('collapse_button').addClass('expand_button');
                }



                // Hide the other panels
                if(settings.isTogglingEnabled)
                {
                    $(".accordion_content").not($(this).next()).slideUp('fast');
                }

            });
        };


        $.fn.accordion = function(method)
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
                $.error('Method with name ' + method + ' does not exist for jQuery.gallery');
            }
        };

    }
);