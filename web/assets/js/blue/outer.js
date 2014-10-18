$(document).ready(function()
{
    initDialogWindows();
});

function initDialogWindows()
{
    // About Us Dialog
    $('#about_dialog').dialog('init',
    {
        draggable: true
    });
    $('#about_us').click(function()
    {
        $('#about_dialog').dialog('open');
    });

    // Contact Dialog
    $('#contact_dialog').dialog('init');
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