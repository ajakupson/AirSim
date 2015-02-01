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
        }
});