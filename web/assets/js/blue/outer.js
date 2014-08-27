$(document).ready(function()
{
    initDialogWindows();
});

function initDialogWindows()
{
    // About Us Dialog
    $('#about_dialog').dialogInit();
    $('#about_us').click(function()
    {
        $('#about_dialog').dialogOpen();
    });

    // Contact Dialog
    $('#contact_dialog').dialogInit();
    $('#contact').click(function()
    {
        $('#contact_dialog').dialogOpen();
    });

    // Select Language Dialog
    $('#select_language_dialog').dialogInit();
    $('#choose_lang_btn').click(function()
    {
        $('#select_language_dialog').dialogOpen();
    });

    // Error Dialog
    $('#error_message_dialog').errDialogInit();
}