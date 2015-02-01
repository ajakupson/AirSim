define(
    'blue/tabs',
    ['jquery'],

    function($)
    {

        // Default settings
        var settings = {};
        var defaults =
        {
            tabsContent: null
        };

        // Functions
        var methods =
        {
            init: function(options)
            {
                return this.each(function()
                {
                    var $tabs_nav = $(this);
                    var $tabs_content = $(options.tabsContent);

                    settings = $.extend(true, {}, defaults, options);

                    $(this).data("settings", settings);

                    switchTabs($tabs_nav, $tabs_content);

                });
            }
        };

        function switchTabs($tabs_nav, $tabs_content)
        {
            $('li', $tabs_nav).click(function(event)
            {
                $('li', $tabs_nav).removeClass('active');
                $(this).addClass('active');

                $('div.tab', $tabs_content).addClass('hidden')
                $('#' + $(this).attr('tab')).removeClass('hidden');
                //$tabsContent.addClass('hidden');
                //$(this).next().removeClass('hidden');
            });
        }

        $.fn.tabs = function(method)
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
                $.error('Method with name ' + method + ' does not exist for jQuery.tabs');
            }
        };
    }
);