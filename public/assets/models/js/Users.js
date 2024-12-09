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
var form1 = $('#users_form');
$('#users_create_btn').click(function () {
    $(form1)[0].reset();
    $(form1).find('input[name=id]').val('');
    $('#users-form-modal').modal('show');
});
var dataTable = $('#table_view').DataTable({
    "language": {
        "sProcessing": js_lang.loading,
        "sLengthMenu": js_lang.sLengthMenu + " _MENU_",
        "sZeroRecords": js_lang.sZeroRecords,
        "sInfo": js_lang.show + " _START_ " + js_lang.to + " _END_ " + js_lang.from_source + " _TOTAL_ " + js_lang.entry,
        "sInfoEmpty": js_lang.sInfoEmpty,
        "sInfoFiltered": "(" + js_lang.result_out + " _MAX_ " + js_lang.entry + ")",
        "sInfoPostFix": "",
        "sSearch": js_lang.search,
        "sUrl": "",
        "oPaginate": {
            "sFirst": js_lang.sFirst,
            "sPrevious": js_lang.sPrevious,
            "sNext": js_lang.sNext,
            "sLast": js_lang.sLast
        }
    },
    "pagingType": "bootstrap_full_number",
    "processing": true,
    "serverSide": true,
    "ajax": {
        type: "POST",
        url :urls.get_data_url ,// json datasource
        data: function ( d ) {
            return  $("#users_search_form").serialize() + '&' + $.param(d)
        }
    },
    "columns": [
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                if (row.id == user_id) {
                    return '';
                }
                return '<input type="checkbox" name="ids[]" class="checkboxes" value="' + row.id + '" title="" />';
            }
        },
        {"data": 'id', "orderable": false, },
        {"data": "name", "orderable": false, },
        {"data": "email", "orderable": false, },
        {"data": "user_name", "orderable": false, },
        {"data": "balance", "orderable": false, },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                var inner = '<span class="label label-sm label-success"> ' + model_js_lang.activated + ' </span>';
                if (row.is_active == "0") {
                    inner = '<span class="label label-sm label-danger"> ' + model_js_lang.disactivated + ' </span>';
                }
                return inner;
            }
        },
        {"data": "created_at", "orderable": false, },
        {"data": "updated_at", "orderable": false, },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                var html = '<div style="width: 100%;text-align: center;">';
                html += '<a title="' + js_lang.edit_record + '" class="btn btn-sm blue"  onclick="editRow(' + row.id + ')"><i class="fa fa-edit"></i> ' + js_lang.edit_record + '</a>';
                if (row.id != user_id) {
                    html += '<a title="' + js_lang.delete_record + '" class="btn btn-sm red" onclick="deleteRow(' + row.id + ')"><i class="fa fa-trash-o"></i>  ' + js_lang.delete_record + '</a>';
                }
                html += '</div>';
                return html;
            }
        }
    ],
    "lengthMenu": [
        [5, 10, 15, 20],
        [5, 10, 15, 20] // change per page values here

    ],
    "pageLength": 10,
    "order": [[1, 'desc']],
});
$.validator.addMethod(
        "regex",
        function (value, element, regexp) {
            var check = false;
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        }, "Please check your input.");
var error1 = $('.alert-danger', form1);
form1.validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "",
    rules: {
        name: {
            required: true,
            minlength: 4
        },
        email: {
            required: true,
            email: true
        },
        user_name: {
            required: true,
            regex: /^[a-z][a-z0-9_.-]{5,25}$/
        },
        password: {
            required: function (element) {
                if ($('input[name=id]').val() == '') {
                    return true;
                } else {
                    return false;
                }
            },
            regex: /(?=^.{8,}$)(?=.*\d)(?=.*[!@#$%^&*(){}]+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/
        },
        repassword: {
            required: function (element) {
                if ($('input[name=id]').val() == '') {
                    return true;
                } else {
                    return false;
                }
            },
            equalTo: "#password"
        },
        balance: {
            range: [0.000, 9999999.999]
        },
    },
    messages: {// custom messages for radio buttons and checkboxes
        'name': {
            required: js_lang.field_required,
            minlength: model_js_lang.name_minlength
        },
        'email': {
            required: js_lang.field_required,
            email: model_js_lang.enter_valid_email
        },
        'user_name': {
            required: js_lang.field_required,
            regex: model_js_lang.user_username_invalid
        },
        'password': {
            required: js_lang.field_required,
            regex: model_js_lang.password_valid_help
        },
        'repassword': {
            required: js_lang.field_required,
            equalTo: model_js_lang.repassword_equals_password
        },
        balance: {
            range: model_js_lang.balance_between
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
        if ($(form).find('input[name=id]').val() == '') {
            var url = urls.save_url;
        } else {
            var url = urls.update_url;
        }
        $.ajax({
            url: url,
            data: $(form).serialize(),
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data[0] == true) {
                    $(form)[0].reset();
                    $('#users-form-modal').modal('hide');
                    dataTable.ajax.reload();
                    showNotify('success', js_lang.save_success);
                } else {
                    var message = '<ul>';
                    if ($.isArray(data[1])) {
                        $.each(data[1], function (key, err) {
                            message += '<li>' + model_js_lang[err] + '</li>';
                        });
                    } else {
                        message = '<li>' + model_js_lang[data[1]] + '</li>';
                    }
                    message += '</ul>';
                    showNotify('error', message);
                }
            }
        });
        return false;
    }
});
function editRow(id)
{
    var form_data = {};
    form_data.id = id;
    form_data._token = token_code;
    $.ajax({
        url: urls.get_row_url,
        data: form_data,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            if (data[0] == true) {
                $(form1).find('input[name=id]').val(data[2].id);
                $(form1).find('input[name=name]').val(data[2].name);
                $(form1).find('input[name=balance]').val(data[2].balance);
                $(form1).find('input[name=user_name]').val(data[2].user_name);
                $(form1).find('input[name=email]').val(data[2].email);
                $('#users-form-modal').modal('show');
            } else {
                showNotify('error', data.message);
            }
        }
    });
}
function deleteRow(id) {
    bootbox.confirm(js_lang.conferm_delete, function (result) {
        if (result) {
            var my_data = {};
            my_data.id = id;
            my_data._token = token_code;
            my_data._method = 'DELETE';
            $.post(urls.delete_url, my_data, function (data) {
                if (data[0]) {
                    showNotify('success', model_js_lang.deleted_success);
                } else {
                    if (js_lang[data[1]]) {
                        showNotify('error', js_lang[data[1]]);
                    } else {
                        showNotify('error', model_js_lang[data[1]]);
                    }
                }
                dataTable.ajax.reload();
            }, 'json');
        }
    });
}

$("#users_delete_btn").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
        bootbox.confirm(js_lang.conferm_delete, function (result) {
            if (result) {
                var ids = [];
                $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
                    ids.push($(y).val());
                });
                var my_data = {};
                my_data.ids = ids;
                my_data._token = token_code;
                my_data._method = 'DELETE';
                $.post(urls.delete_multi_url, my_data, function (data) {
                    dataTable.ajax.reload();
                    bootbox.hideAll();
                    if (data[0]) {
                        Command: toastr['success'](js_lang[data[1]]);
                    } else {
                        if (js_lang[data[1]]) {
                            showNotify('error', js_lang[data[1]]);
                        } else {
                            showNotify('error', model_js_lang[data[1]]);
                        }
                    }
                }, 'json');
            }
        });
    } else {
        bootbox.dialog({
            title: js_lang.box_error,
            message: js_lang.box_message
        });
        return false;
    }
});
$("#users_activate_btn").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
        var ids = [];
        $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
            ids.push($(y).val());
        });
        var my_data = {};
        my_data.ids = ids;
        my_data.status = 'activate';
        my_data._token = token_code;
        $.post(urls.update_status_url, my_data, function (data) {
            bootbox.hideAll();
            var notify_status = 'danger';
            if (data[0]) {
                dataTable.ajax.reload();
                notify_status = 'success';
            }
            if (js_lang[data[1]]) {
                showNotify(notify_status, js_lang[data[1]]);
            } else {
                showNotify(notify_status, model_js_lang[data[1]]);
            }
        }, 'json');
    } else {
        bootbox.dialog({
            title: js_lang.box_error,
            message: js_lang.box_message
        });
        return false;
    }
});
$("#users_disactivate_btn").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
        var ids = [];
        $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
            ids.push($(y).val());
        });
        var my_data = {};
        my_data.ids = ids;
        my_data.status = 'disactivate';
        my_data._token = token_code;
        $.post(urls.update_status_url, my_data, function (data) {
            bootbox.hideAll();
            var notify_status = 'danger';
            if (data[0]) {
                dataTable.ajax.reload();
                notify_status = 'success';
            }
            if (js_lang[data[1]]) {
                showNotify(notify_status, js_lang[data[1]]);
            } else {
                showNotify(notify_status, model_js_lang[data[1]]);
            }
        }, 'json');
    } else {
        bootbox.dialog({
            title: js_lang.box_error,
            message: js_lang.box_message
        });
        return false;
    }
});

$('#excute_search').click(function(){
    dataTable.ajax.reload();
    return false;
});