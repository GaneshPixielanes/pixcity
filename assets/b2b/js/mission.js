

var $form = $("form[name='mission']");

$form.validate({
    rules: {
        "mission[client]": { required: true},
        "mission[referencePack]": { required: true},
        "mission[title]": { required: true, maxlength: 50, minlength: 5},
        "mission[description]": { required: true, maxlength: 100, minlength: 5},
        "mission[description]": { required: true, maxlength: 100, minlength: 5},
        "mission[bannerImage]": { required: true},
        "mission[briefFiles]": { required: true},
        "mission[missionBasePrice]": { required: true, number: true},

    }
});

$(document).ready(function()
{
    $('#terminate-mission').on('click', function(e){

        e.preventDefault();

        if(confirm('Are you sure about terminating this mission?'))
        {
            updateStatus($(this).attr('data-id'),'terminate');
        }
    });

    $('#cancel-mission').on('click', function (e)
    {
        e.preventDefault();

        if(confirm('Are you sure about cancelling this mission?'))
        {
            updateStatus($(this).attr('data-id'),'cancel');
        }
    });

    function updateStatus(id, status)
    {
        $.ajax($('#api-box').attr('data-url'),{
            type: 'POST',
            data: {
                'id': id,
                'status': status,
                '_token':$('#api-box').attr('data-csrf-token')
            },
            success:function(result)
            {
                if(result.success == true)
                {
                    alert('Status has been updated');
                }
                else
                {
                    alert('Sorry! Status couldn\'t be updated');
                }
            }
        });
    }
});