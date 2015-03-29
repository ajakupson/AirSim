define
(
    'blue/common_functions',
    function()
    {

        this.showElement = function (el)
        {
            $(el).show(250);
        },
        this.hideElement = function (el)
        {
            $(el).hide(250);
        },

        this.String.format = function()
        {
            var s = arguments[0];
            for (var i = 0; i < arguments.length - 1; i++) {
                var reg = new RegExp("\\{" + i + "\\}", "gm");
                s = s.replace(reg, arguments[i + 1]);
            }

            return s;
        },

        this.GetUrlParameter = function(sParam)
        {
            var sPageURL = window.location.search.substring(1);
            var sURLVariables = sPageURL.split('&');
            for(var i = 0; i < sURLVariables.length; i++)
            {
                var sParameterName = sURLVariables[i].split('=');
                if(sParameterName[0] == sParam)
                {
                    return sParameterName[1];
                }

            }
        }
});