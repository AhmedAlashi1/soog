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
var form1 = $('#sms_gates_form');
$('#sms_gates_create_btn').click(function () {
    $(form1)[0].reset();
    $(form1).find('input[name=id]').val('');
    $('#sms-gates-form-modal').modal('show');
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
        url: urls.get_data_url, // json datasource
    },
    "searching": false,
    "columns": [
        {"data": "id", "orderable": false, },
        {"data": "title", "orderable": false, },
        {"data": "gateway", "orderable": false, },
        {"data": "username", "orderable": false, },
        {"data": "sender", "orderable": false, },
        {"data": "updated_at", "orderable": false, },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                var html = '<div style="width: 100%;text-align: center;">';
                html += '<a title="'+model_js_lang.balance+'" class="btn btn-sm purple" onclick="getBalance(' + row.id + ')"><i class="fa fa-dollar"></i></a>';
                html += '<a title="' + js_lang.edit_record + '" class="btn btn-sm blue"  onclick="editRow(' + row.id + ')"><i class="fa fa-edit"></i></a>';
                //html += '<a title="' + js_lang.delete_record + '" class="btn btn-sm red" onclick="deleteRow(' + row.id + ')"><i class="fa fa-trash-o"></i></a>';
                html += '</div>';
                return html;
            }
        }
    ],
});
var error1 = $('.alert-danger', form1);
var validator=form1.validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "",
    rules: {
        title: {
            required: true,
        },
        gateway: {
            required: true,
        },
        username: {
            required: true,
        },
        password: {
            required: function (element) {
                return $("input[name=id]").val() == "";
            },
        },
        sender: {
            required: true,
        },
    },
    messages: {// custom messages for radio buttons and checkboxes
        'title': {
            required: js_lang.field_required,
        },
        'gateway': {
            required: js_lang.field_required,
        },
        'username': {
            required: js_lang.field_required,
        },
        'password': {
            required: js_lang.field_required,
        },
        'sender': {
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
        if ($(form).find('input[name=id]').val() == '') {
            var url = urls.save_url;
        } else {
            var url = urls.update_url;
        }
        $.ajax({
            url: url,
            type: 'post',
            data: $(form).serialize(),
            success: function (data) {
                if (data[0] == true) {
                    $(form)[0].reset();
                    $('#sms-gates-form-modal').modal('hide');
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
                $(form1).find('input[name=title]').val(data[2].title);
                $(form1).find('select[name=gateway]').val(data[2].gateway).select();
                $(form1).find('input[name=sender]').val(data[2].sender);
                $(form1).find('input[name=username]').val(data[2].username);
                $(form1).find('input[name=password]').val('');
                $('#sms-gates-form-modal').modal('show');
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
function updateSort(id, value) {
    var my_data = {};
    my_data.id = id;
    my_data.value = value;
    $.post(urls.update_sort_url, my_data, function (data) {
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
function getBalance(id) {
    var my_data = {};
    my_data.id = id;
    $.post(urls.get_balance_url, my_data, function (data) {
        if (data[0]) {
            showNotify('success', data[1]);
        } else {
            if (js_lang[data[1]]) {
                showNotify('error', data[1]);
            } else {
                showNotify('error', data[1]);
            }
        }
        dataTable.ajax.reload();
    }, 'json');
}

$('#sms-gates-form-modal').on('hidden.bs.modal', function () {
    error1.hide();
    validator.resetForm();
})