define
(
    'blue/slider',
    ['jquery'],

function($){

        var slider;
        var totalSlides = 0;
        var currentSlide = 1;
        var sliderWidth;

        $.fn.sliderInit = function(options)
        {
            slider = this;
            $(window).resize(function()
            {
                sliderWidth = this.outerWidth();
            });
            // TODO: add some customization
            var settings = $.extend
            ({
                width: '100%'
            }, options);

            sliderWidth = $(this).outerWidth();
            totalSlides = $('#slider > li').length;

            if(totalSlides == 1)
            {
                $('.arrow_left').hide();
                $('.arrow_right').hide();
            }
            $('#slider > li').each(function(index)
            {
                if(index > 0)
                {
                    $(this).hide();
                }
            });

            // Next Slide
            $('.arrow_right').click(function()
            {
                currentSlide++;
                if(currentSlide == totalSlides)
                {
                    $('.arrow_right').hide();
                }
                else
                {
                    if($('.arrow_left').is(":hidden"))
                    {
                        $('.arrow_left').show();
                    }
                }

                $('#slide' + currentSlide).css('left', sliderWidth + 'px').show();
                $('#slide' + (currentSlide)).animate({left: 0}, 500);
                $('#slide' + (currentSlide - 1)).animate({left: -sliderWidth + 'px'}, 500);

            });

            // Previous Slide
            $('.arrow_left').click(function()
            {
                currentSlide--;
                if(currentSlide == 1)
                {
                    $('.arrow_left').hide();
                }
                else
                {
                    if($('.arrow_right').is(":hidden"))
                    {
                        $('.arrow_right').show();
                    }
                }

                $('#slide' + (currentSlide)).animate({left: 0}, 500);
                $('#slide' + (currentSlide + 1)).animate({left: sliderWidth + 'px'}, 500);

            });

            return this.css
            ({
                width: settings.width
            });
        };

    }
);