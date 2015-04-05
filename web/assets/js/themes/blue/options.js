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

            changeConfigParameter();
        });

        function changeConfigParameter() {

            $('.config_select').change(function() {

                var configAlias = $(this).data('alias');
                var configValue = $(this).val();
                var $ajaxWait = $('.changing_config');

                $.ajax({

                    url: './../options_config_change',
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        configAlias: configAlias,
                        configValue: configValue
                    },
                    onLoading: $ajaxWait.show(),
                    success: function(response) {

                        if(response.success) {

                            $ajaxWait.fadeOut();
                        }
                        else {

                        }

                    }
                })
            });
        }
    }
);