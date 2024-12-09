$.validator.addMethod(
        "regex",
        function (value, element, regexp) {
            var check = false;
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        }, "Please check your input.");
var form1 = $('#reset_password_form');
var error1 = $('.alert-danger', form1);
form1.validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "",
    rules: {
        password: {
            required: function (element) {
                return true;
            },
            regex: /(?=^.{8,}$)(?=.*\d)(?=.*[!@#$%^&*(){}]+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/
        },
        verify_password: {
            required: function (element) {
                return true;
            },
            equalTo: "#password"
        },
    },
    messages: {// custom messages for radio buttons and checkboxes
        'password': {
            required: js_lang.field_required,
            regex: model_js_lang.password_valid_help
        },
        'verify_password': {
            required: js_lang.field_required,
            equalTo: model_js_lang.repassword_equals_password
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
        form.submit();
        return false;
    }
});