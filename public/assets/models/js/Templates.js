var form1 = $('#templates_form');
var error1 = $('.alert-danger', form1);
form1.validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "",
    rules: {
        admins_mobiles: {
            required: true,
        },
        admins_emails: {
            required: true,
        },
        site_name: {
            required: true,
        },
    },
    messages: {// custom messages for radio buttons and checkboxes
        'admins_mobiles': {
            required: js_lang.field_required,
        },
        'admins_emails': {
            required: js_lang.field_required,
        },
        'site_name': {
            required: js_lang.field_required,
        },
    },
    invalidHandler: function (event, validator) { //display error alert on form submit
        error1.show();
    },
    highlight: function (element) { // hightlight error inputs
        $(element)
                .closest('.form-group').addClass('has-error'); // set error class to the control group
    },
    unhighlight: function (element) { // revert the change done by hightlight
        $(element)
                .closest('.form-group').removeClass('has-error'); // set error class to the control group
    },
    success: function (label) {
        label
                .closest('.form-group').removeClass('has-error'); // set success class to the control group
    },
    submitHandler: function (form) {
        error1.hide();
        $.ajax({
            url: urls.save_url,
            data: $(form).serialize(),
            type: 'post',
            dataType: 'json',
            success: function (data) {
                var failed = data['failed'].length;
                var success = data['success'].length;
                var message = '';
                if (failed > 0) {
                    message = model_js_lang.error_in_some_fields;
                    showNotify('warning', message);
                } else {
                    message = model_js_lang.saved_success;
                    showNotify('success', message);
                }
            }
        });
        return false;
    }
});