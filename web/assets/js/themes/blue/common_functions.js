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

        this.String.prototype.format = function()
        {
            var s = this;
            for(var i = 0; i < arguments.length; i++) {
                var reg = new RegExp("\\{" + i + "\\}", "gm");
                s = s.replace(reg, arguments[i]);
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