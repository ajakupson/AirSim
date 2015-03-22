define
(
    'blue/photo_albums',
    ['jquery', 'blue/tabs'],
    function($)
    {
        // On DOM ready
        $(function()
        {
            $('#albums_tabs').tabs('init',
            {
                tabsContent: $('#albums_tabs_content')
            });
        });
    }
);