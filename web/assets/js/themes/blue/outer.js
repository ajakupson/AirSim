define(
    'blue/outer',
    [
        'jquery',
        //'particles',
        'blue/dialog'
    ],
    function($)
    {
        $(window).load(function()
        {
            initParticles();
        });

    // On document ready
    $(function()
    {

        initDialogWindows();
    });

    function initDialogWindows()
    {
        // About Us Dialog
        $('#about_dialog').dialog('init',
        {
            top: '-151px',
            draggable: true
        });
        $('#about_us').click(function()
        {
            $('#about_dialog').dialog('open');
        });

        // Contact Dialog
        $('#contact_dialog').dialog('init',
        {
            top: '-151px'
        });
        $('#contact').click(function()
        {
            $('#contact_dialog').dialog('open');
        });

        // Select Language Dialog
        $('#select_language_dialog').dialog('init');
        $('#choose_lang_btn').click(function()
        {
            $('#select_language_dialog').dialog('open');
        });

        // Error Dialog
        $('#error_message_dialog').errDialogInit();
    }

    function initParticles()
    {
        particlesJS('particles-js', {
            particles: {
                color: '#fff',
                shape: 'circle', // "circle", "edge" or "triangle"
                opacity: 1,
                size: 4,
                size_random: true,
                nb: 150,
                line_linked: {
                    enable_auto: true,
                    distance: 100,
                    color: '#fff',
                    opacity: 1,
                    width: 1,
                    condensed_mode: {
                        enable: false,
                        rotateX: 600,
                        rotateY: 600
                    }
                },
                anim: {
                    enable: true,
                    speed: 1
                }
            },
            interactivity: {
                enable: false,
                mouse: {
                    distance: 250
                },
                detect_on: 'canvas', // "canvas" or "window"
                mode: 'grab',
                line_linked: {
                    opacity: .5
                },
                events: {
                    onclick: {
                        enable: false,
                        mode: 'push', // "push" or "remove" (particles)
                        nb: 4
                    }
                }
            },
            /* Retina Display Support */
            retina_detect: true
        });
    }

});