var $ = jQuery;
var dhWindow;
$(document).ready(function()
{
    $('.FlowupLabels').FlowupLabels({
        /*
        These are all the default values
        You may exclude any/all of these options
        if you won't be changing them
        */
        // Handles the possibility of having input boxes prefilled on page load
        feature_onLoadInit: true, 

        // Class when focusing an input
        class_focused:      'focused',
        // Class when an input has text entered
        class_populated:    'populated' 
    });

    $('#btnSaveSettings').click(function()
    {
        var data = 
        {
            public_key: $('input[name=public_key]').val(),
            secret_key: $('input[name=secret_key]').val(),
            endpoint: $('input[name=endpoint]').val(),
            auth_token: $('input[name=auth_token]').val(),
        };

        window.location.href = "?page=dh-settings&action=save&public_key=" + data.public_key + "&secret_key=" + data.secret_key + "&endpoint=" + data.endpoint+"&auth_token="+data.auth_token;
    });

    $('#btnLoginWithDancehub').click(function()
    {
        if($(this).text() != 'Logout')
        {
            var data = 
            {
                public_key: $('input[name=public_key]').val(),
                secret_key: $('input[name=secret_key]').val(),
                endpoint: $('input[name=endpoint]').val(),
                auth_token: $('input[name=auth_token]').val(),
            };

            if(data.public_key.length <= 0 || data.secret_key.length <= 0 || data.endpoint.length <= 0)
            {
                alert('You must enter the values for all parameters.');
            }
            else
            {
                var authCode = btoa(data.public_key.trim()+":"+data.secret_key.trim());
                dhWindow = window.open(data.endpoint + "login?authorization="+authCode, "dhWindow", "width=800,height=600");
                
            }
        }
        else
        {
            $(this).text('Login with Dancehub');
            $('input[name=auth_token]').val('');
        }

    });

    window.addEventListener("message", receiveMessage, false);

    function receiveMessage(event)
    {
        console.log(event.data);
        if(dhWindow)
            dhWindow.close();
        
        if(event.data.status == 'success')
        {
            $('input[name=auth_token]').val(event.data.token);
            $('#btnLoginWithDancehub').text('Logout');
        }

    }
});