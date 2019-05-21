

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