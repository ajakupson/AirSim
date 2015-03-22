define
(
    'blue/options',
    ['jquery', 'blue/tabs'],
    function($)
    {
        // On DOM ready
        $(function()
        {
            $('#options_tabs').tabs('init',
            {
                tabsContent: $('#options_tabs_content')
            });
        });
    }
);