$(document).ready(function () {
    // On form submit, stop the regular login process
    $('form').on('submit', function(e)
    {
        e.preventDefault();
        $('.btn-darkblue, .btn-pink')
            .html('<i class="fas fa-cog fa-spin"></i>&nbsp;<i class="fas fa-cog fa-spin"></i>&nbsp;<i class="fas fa-cog fa-spin"></i>')
            .addClass('disabled');
        var username = '';
        var password = '';
        var login_type = '';
        var csrf_token = $('[name="_csrf_token"]').val();


        switch ($(this).attr('name')) {
            case 'client': username = $('#inputEmail').val();
                           password = $('#inputPassword').val();
                           login_type = 'client'
                           break;

            case 'cm': username = $('#cmEmail').val();
                       password = $('#cmPassword').val();
                       login_type = 'cm';
                       break;

            case 'user': username = $('[name="_username"]').val();
                         password = $('[name="_password"]').val();
                         login_type = 'user';
                         break;
        }
        var url = $(this).attr('action'); //Get the action parameter

        $.ajax(url, {
           method: 'POST',
           data: JSON.stringify({ '_csrf_token':csrf_token,'_username': username, '_password': password, 'login_type': login_type}),
           // data: $(this).serialize(),
           // data: {'_username': username, '_password': password,'_csrf_token':csrf_token},
           contentType: "application/json",
           success: function (data) {
               if(data.success == true)
               {
                   location.href = data.url;
               }
               else
               {
                   $('.error').show();
                   $('.error').html(data.message);
                   $('.btn-darkblue, .btn-pink').html('Connexion').removeClass('disabled');

               }
           }
        });
    });

    // Check if the input is valid JSON
    function IsJsonString(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }
});