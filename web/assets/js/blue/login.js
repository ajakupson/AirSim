$(document).ready(function()
{
    $('.slider').sliderInit();
    logIn();
});

function logIn()
{
    $('#log_in_btn').click(function()
    {
        var username = $('input[name = "login_input"]').val();
        var password = $('input[name = "pass_input"]').val();
        var errorCounter = 0;

        errorCounter += validateLength(username, 5, '#is_required_username', '#min_length_username');
        errorCounter += validateLength(password, 8, '#is_required_password', '#min_length_pass');

        if(errorCounter > 0)
            return false;
        else
        {
            hideElement('#wrong_username_or_pass');
            // TODO: Ajax call
        }
    });
}