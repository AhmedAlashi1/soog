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

var form1 = $('#admins_form');
$('#admins_create_btn').click(function () {
    $(form1)[0].reset();
    $(form1).find('input[name=id]').val('');
    $('#admins-form-modal').modal('show');
});


/**
 * datatable ajax
 * @type {jQuery}
 */
var dataTable = $('#table_view').DataTable({
    "language": {
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
    //"processing": true,
    'fnDrawCallback': function ()
    {
        $('#table_view').show();
    },
    "serverSide": true,
    "ajax": {
        type: "POST",
        url: urls.get_data_url, // json datasource
        data: function (d) {
            return  $("#full_users_search_form").serialize() + '&' + $.param(d)
        }
    },
    responsive: {
        details: {
            type: 'column',
        }
    },
    columnDefs: [{
            className: 'control',
            targets: 0
        }],
    "autoWidth": false,
    "searching": false,
    "columns": [{
            "orderable": false,
            "data": function (row, type, val, meta) {
                return '';
            }
        },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                return '<input type="checkbox" name="ids[]" class="checkboxes" value="' + row.id + '" title="" />';
            }
        },
        {"data": function (row) {

                return row.mobile_number;
        }, "orderable": false},
        {"data": "full_name", "orderable": false},
        {"data": "email", "orderable": false},

        {"data": function (row, type, val, meta) {
                if (row.status == 'active') {
                    return "<a class='label label-sm label-info' onclick='deActiveUser(" + row.id + ");'> <i class='fa fa-check'></i> " + model_js_lang.activated + "</a>";
                } else if (row.status == 'inactive') {
                    return "<a class='label label-sm label-danger' onclick='ActiveUser(" + row.id + ");'> <i class='fa fa-user-times'></i> " + model_js_lang.inactive + "</a>";
                } else if (row.status == 'pending_activation') {
                    return "<a class='label label-sm label-warning' onclick='ActiveUser(" + row.id + ");'> <i class='fa fa-user-times'></i> " + model_js_lang.pending_activation + "</a>";
                } else {
                    return '';
                }
            }, "orderable": false
        },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {

                return row.username
            }
        },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                var html = '<div style="width: 100%;text-align: center;">';
                html += '<a href="'+urls.orders+'/'+row.id+'" target="_blank" class="btn btn-sm purple" ><i class="fa fa-bars"></i> الطلبات</a>';
                html += '<a title="' + js_lang.edit_record + '" class="btn btn-sm blue"  onclick="editRow(' + row.id + ')"><i class="fa fa-edit"></i> ' + js_lang.edit_record + '</a>';
                html += '<a title="' + js_lang.delete_record + '" class="btn btn-sm red" onclick="deleteRow(' + row.id + ')"><i class="fa fa-trash-o"></i>  ' + js_lang.delete_record + '</a>';
                html += '</div>';
                return html;
            } ,"width": "30%"
        }

    ],
    "lengthMenu": [
        [100, 200, 500, 1000],
        [100, 200, 500, 1000] // change per page values here

    ],
    "pageLength": 100,
    "order": [[2, 'desc']]
});
/**
 * deactivate users function
 * @param id
 * @returns {boolean}
 */
function ResetActiveUser(id) {
    bootbox.confirm(model_js_lang.reset_confirm, function (result) {
        if (result) {
            var my_data = {};
            my_data.ids = [id];
            my_data._token = token_code;
            $.post(urls.reset_mobile_url, my_data, function (data) {
                if (data.status == true) {
                    Command: toastr['success'](data.message);
                } else {
                    Command: toastr['error'](data.message);
                }
                dataTable.ajax.reload();
            }, 'json');
        }
    });
    return false;
}
/**
 *
 * @param id
 * @returns {boolean}
 * @constructor
 */
function ResetActiveEmailUser(id) {
    bootbox.confirm(model_js_lang.reset_confirm_email, function (result) {
        if (result) {
            var my_data = {};
            my_data.ids = [id];
            my_data._token = token_code;
            $.post(urls.reset_email_url, my_data, function (data) {
                if (data.status == true) {
                    Command: toastr['success'](data.message);
                } else {
                    Command: toastr['error'](data.message);
                }
                dataTable.ajax.reload();
            }, 'json');
        }
    });
    return false;
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
function deActiveUser(id) {
    bootbox.confirm(model_js_lang.daactivated_confirm, function (result) {
        if (result) {
            var my_data = {};
            my_data.ids = [id];
            my_data._token = token_code;
            $.post(urls.deactivate_url, my_data, function (data) {
                if (data.status == true) {
                    Command: toastr['success'](data.message);
                } else {
                    Command: toastr['error'](data.message);
                }
                dataTable.ajax.reload();
            }, 'json');
        }
    });
    return false;
}
/**
 * Active user function
 * @param id
 * @returns {boolean}
 * @constructor
 */
function ActiveUser(id) {
    bootbox.confirm(model_js_lang.activated_confirm, function (result) {
        if (result) {
            var my_data = {};
            my_data.ids = [id];
            my_data._token = token_code;
            $.post(urls.activate_url, my_data, function (data) {
                if (data.status == true) {
                    Command: toastr['success'](data.message);
                } else {
                    Command: toastr['error'](data.message);
                }
                dataTable.ajax.reload();
            }, 'json');
        }
    });
    return false;
}
/**
 * active all users
 */
$("#app_users_activate_btn").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
        bootbox.confirm(model_js_lang.activated_confirm_all, function (result) {
            if (result) {
                var ids = [];
                $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
                    ids.push($(y).val());
                });
                var my_data = {};
                my_data.ids = ids;
                my_data._token = token_code;
                $.post(urls.activate_url, my_data, function (data) {
                    if (data.status == true) {
                        Command: toastr['success'](data.message);
                    } else {
                        Command: toastr['error'](data.message);
                    }
                    dataTable.ajax.reload();
                }, 'json');
            }
        });
    } else {
        Command: toastr['error'](js_lang.box_message);
        return false;
    }
});
/**
 * deactive all users
 */
$("#app_users_deactivate_btn").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
        bootbox.confirm(model_js_lang.deactivated_confirm_all, function (result) {
            if (result) {
                var ids = [];
                $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
                    ids.push($(y).val());
                });
                var my_data = {};
                my_data.ids = ids;
                my_data._token = token_code;
                $.post(urls.deactivate_url, my_data, function (data) {
                    if (data.status == true) {
                        Command: toastr['success'](data.message);
                    } else {
                        Command: toastr['error'](data.message);
                    }
                    dataTable.ajax.reload();
                }, 'json');
            }
        });
    } else {
        Command: toastr['error'](js_lang.box_message);
        return false;
    }
});
/**
 * block users device srial
 */

// date pickers
$('#defaultrange').daterangepicker({
    opens: (App.isRTL() ? 'left' : 'right'),
    format: 'YYYY-MM-DD',
    separator: ' to ',
    startDate: moment().subtract('days', 29),
    endDate: moment(),
    ranges: {
        'اليوم': [moment(), moment()],
        'أمس': [moment().subtract('days', 1), moment().subtract('days', 1)],
        'أخر 7 أيام': [moment().subtract('days', 6), moment()],
        'أخر 30 يوم': [moment().subtract('days', 29), moment()],
        'هذا الشهر': [moment().startOf('month'), moment().endOf('month')],
        'الشهر الماضى': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
    },
    minDate: '01/01/2012',
    maxDate: '12/31/2018',
},
        function (start, end) {
            $('#defaultrange input').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
        }
);

$('.delete_date').click(function () {
    $('#daterange').val('');
    return false;
});
/**
 * for filtration
 */
$('#excute_search').click(function () {
    dataTable.ajax.reload();
    return false;
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
        full_name: {
            required: true,
            minlength: 4
        },
        email: {
            required: true,
            email: true
        },
        mobile_number: {
            required: true,
        },

        username: {
            required: true,
            regex: /^[a-z][a-z0-9_.-]{4,25}$/
        },
        password: {
            required: function (element) {
                if ($('input[name=id]').val() == '') {
                    return true;
                } else {
                    return false;
                }
            },
            regex: /(?=^.{6,}$)(?=.*\d)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/
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
        }
    },
    messages: {// custom messages for radio buttons and checkboxes
        'full_name': {
            required: js_lang.field_required,
            minlength: model_js_lang.name_minlength
        },
        'email': {
            required: js_lang.field_required,
            email: model_js_lang.enter_valid_email
        },
        'mobile_number': {
            required: js_lang.field_required,
        },
        'username': {
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
        }
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
        var formData = new FormData(form1[0]);
        $.ajax({
            url: url,
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function (data) {
                if (data[0] == true) {
                    $(form)[0].reset();
                    $('#admins-form-modal').modal('hide');
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
                $(form1).find('input[name=full_name]').val(data[2].full_name);
                $(form1).find('input[name=mobile_number]').val(data[2].mobile_number);
                $(form1).find('input[name=username]').val(data[2].username);
                $(form1).find('input[name=email]').val(data[2].email);
                $(form1).find('select[name=cat_id]').val(data[2].cat_id);
                $('#admins-form-modal').modal('show');
            } else {
                showNotify('error', data.message);
            }
        }
    });
}