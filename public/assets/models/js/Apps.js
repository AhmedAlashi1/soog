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
var form1 = $('#apps_form');
$('#apps_create_btn').click(function () {
    $(form1)[0].reset();
    $(form1).find('input[name=id]').val('');
    $('#apps-form-modal').modal('show');
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
    "autoWidth": false,
    "pagingType": "bootstrap_full_number",
    "processing": false,
    "serverSide": true,
    "ajax": {
        url :urls.get_data_url ,// json datasource
    },
    "columns": [
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                return '<input type="checkbox" name="ids[]" class="checkboxes" value="' + row.id + '" title="" />';
            }
        },
        {"data": "name", "orderable": false, },
        {"data": "app_id", "orderable": false, },
        {"data": "app_version", "orderable": false, },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                var inner = '<span class="badge badge-success"><i class="icon-check"></i></span>';
                if (row.check_version == "0") {
                    inner = '<span class="badge badge-danger"><i class="icon-close"></i></span>';
                }
                return inner;
            }
        },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                var inner = '<span class="badge badge-success"><i class="icon-check"></i></span>';
                if (row.status == "0") {
                    inner = '<span class="badge badge-danger"> <i class="icon-close"></i> </span>';
                }
                return inner;
            }
        },
        {"data": "created_at", "orderable": false, },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                var html = '<div style="width: 100%;text-align: center;">';
                    html += '<a title="' + js_lang.edit_record + '" class="btn btn-sm blue"  onclick="editRow(' + row.id + ')"><i class="fa fa-edit"></i> ' + js_lang.edit_record + '</a>';
                    html += '<a title="' + js_lang.delete_record + '" class="btn btn-sm red" onclick="deleteRow(' + row.id + ')"><i class="fa fa-trash-o"></i>  ' + js_lang.delete_record + '</a>';
                html += '</div>';
                return html;
            }
        }
    ],
    "lengthMenu": [
        [50, 100, 150, 200],
        [50, 100, 150, 200] // change per page values here

    ],
    "pageLength": 100,
    "order": [[0, 'desc']],
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
        app_id: {
            required: true,
        },
        app_version: {
            required: true,
        },
        db_prefix: {
            required: true,
        },
//        database_name: {
//            required: true,
//        },
//        username: {
//            required: true,
//        },
//        password: {
//            required: true,
//        }

    },
    messages: {// custom messages for radio buttons and checkboxes
        'name': {
            required: js_lang.field_required,
            minlength: model_js_lang.name_minlength
        },
        'app_id': {
            required: js_lang.field_required,
        },
        'app_version': {
            required: js_lang.field_required,
        },
        'db_prefix': {
            required: js_lang.field_required,
        },
//        'database_name': {
//            required: js_lang.field_required,
//        },
//        'username': {
//            required: js_lang.field_required,
//        },
//        'password': {
//            required: js_lang.field_required,
//        }
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
                    $('#apps-form-modal').modal('hide');
                    dataTable.ajax.reload();
                    showNotify('success', model_js_lang[data[1]]);
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
                $(form1).find('input[name=app_id]').val(data[2].app_id);
                $(form1).find('input[name=app_version]').val(data[2].app_version);
                $(form1).find('input[name=name]').val(data[2].name);
                $(form1).find('input[name=db_prefix]').val(data[2].db_prefix);
//                $(form1).find('input[name=database_name]').val(data[2].database_name);
//                $(form1).find('input[name=username]').val(data[2].username);
//                $(form1).find('input[name=password]').val(data[2].password);
                $(form1).find('input[name=app_key]').val(data[2].app_key);
                $(form1).find('input[name=secret_key]').val(data[2].secret_key);
                if(data[2].status==1){
                    $('.status_check').bootstrapSwitch('state', true);
                }else{
                    $('.status_check').bootstrapSwitch('state', false);
                }
                if(data[2].check_version==1){
                    $('.version_check').bootstrapSwitch('state', true);
                }else{
                    $('.version_check').bootstrapSwitch('state', false);
                }
                $('#apps-form-modal').modal('show');
            } else {
                showNotify('error', model_js_lang[data[1]]);
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
            $.post(urls.delete_url, my_data, function (data) {
                if (data[0]) {
                    showNotify('success', model_js_lang[data[1]]);
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

$("#apps_delete_btn").click(function () {
  toastr.options.positionClass = "toast-top-full-width";
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
                $.post(urls.delete_multi_url, my_data, function (data) {
                    dataTable.ajax.reload();
                    bootbox.hideAll();
                    if (data[0]) {
                        Command: toastr['success'](model_js_lang[data[1]]);
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
        Command: toastr['error'](js_lang.box_message);
        return false;
    }
});
$("#apps_activate_btn").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
        bootbox.confirm(js_lang.conferm_active, function (result) {
            if (result) {
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
                        showNotify(notify_status, model_js_lang[data[1]]);
                    } else {
                        showNotify(notify_status, model_js_lang[data[1]]);
                    }
                }, 'json');
            }
        });
    } else {
        Command: toastr['error'](js_lang.box_message);
        return false;
    }
});
$("#apps_disactivate_btn").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
        bootbox.confirm(js_lang.conferm_deactive, function (result) {
            if (result) {
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
                        showNotify(notify_status, model_js_lang[data[1]]);
                    } else {
                        showNotify(notify_status, model_js_lang[data[1]]);
                    }
                }, 'json');
            }
        });
    } else {
        Command: toastr['error'](js_lang.box_message);
        return false;
    }
});
