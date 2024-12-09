var users_info = {};
var dialog_is = {
    title: '',
    message: '',
    buttons: {
        success: {
            label: js_lang.save,
            className: "btn-success",
            callback: function () {
                return false;
            }
        }, danger: {
            label: js_lang.close,
            className: "btn-danger",
            callback: function () {
                bootbox.hideAll();
            }
        }
    }
};
bootbox.addLocale('ar', {
    OK: js_lang.yes,
    CANCEL: js_lang.cancel,
    CONFIRM: js_lang.CONFIRM
});
bootbox.setLocale('ar');

/**
 * active all users
 */
$("#testHlr").click(function () {
        bootbox.confirm('سوف نقوم باختيار رقم عشوائى من المستخدمين لتجربة الاتصال', function (result) {
            if (result) {
                $.post(urls.test_hlr, $("#send_data_hlr").serialize(), function (data) {
                    if (data.status == true) {
                        Command: toastr['success'](data.message);
                    } else {
                        Command: toastr['error'](data.message);
                    }
                }, 'json');
            }
        });

});

