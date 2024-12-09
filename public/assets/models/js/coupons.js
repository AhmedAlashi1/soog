// $.fn.modal.Constructor.prototype.enforceFocus = function() {};
// $.fn.select2.defaults.set("theme", "bootstrap");
// $(".api_select2").select2({
//     placeholder: 'اختر عميل',
//     width: null,
//     dropdownParent:$('#myModal')
// });

$('input[type=radio][name=type]').change(function () {
    if (this.value == 2) {
        $('#show_users').show();
        $('#show_dis').hide();
    } else {
        $('#show_users').hide();
        $('#show_dis').show();
    }
});
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


var form1 = $('#alerts_form');
$('#alerts_create_btn').click(function () {
    $(form1)[0].reset();
    $(form1).find('input[name=id]').val('');
    $('#alerts-form-modal').modal('show');
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
    "columns": [
        {"data": "code", "orderable": false,},
        {"data": "discount", "orderable": false,},
        {"data": "percent", "orderable": false,},
        {"data": "code_limit", "orderable": false,},
        {"data": "code_max", "orderable": false,},
        {"data": "count_number", "orderable": false,},
        {"data": "use_number", "orderable": false,},
        {"data": "end_offer", "orderable": false,},
        {
            "data": function (row, type, val, meta) {
                if (row.status == 1) {
                    return '<a onclick="updateStatus(' + row.id + ',0)"><span class="badge badge-success"><i class="icon-check"></i></span></a>';
                } else if (row.status == 0) {
                    return '<a onclick="updateStatus(' + row.id + ',1)"><span class="badge badge-danger"> <i class="icon-close"></i> </span></a>';
                } else {
                    return '';
                }
            }, "orderable": false
        },
        {
            "orderable": false,
            "width": "20%",
            "data": function (row, type, val, meta) {
                users_info[row.id] = row;
                var html = '<div style="width: 100%;text-align: center;">';
                    html += '<a title="' + js_lang.edit_record + '" class="btn btn-sm blue"  onclick="editRow(' + row.id + ')"><i class="fa fa-edit"></i></a>';
                    html += '<a title="' + js_lang.delete_record + '" class="btn btn-sm red" onclick="deleteRow(' + row.id + ')"><i class="fa fa-trash-o"></i></a>';
                html += '</div>';
                return html;
            }
        }
    ],
});
var error1 = $('.alert-danger', form1);
form1.validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "",
    rules: {
        code: {
            required: true
        }, count_number: {
            required: true
        }, code_limit: {
            required: true
        },
    },
    messages: {// custom messages for radio buttons and checkboxes
        'code': {
            required: js_lang.field_required,
        }, 'count_number': {
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
                    $('#alerts-form-modal').modal('hide');
                    dataTable.ajax.reload();
                    showNotify('success', model_js_lang[data[1]]);
                    $('#ip_tags').tagsinput('destroy');
                    $('#ip_tags').tagsinput();
                    $('#domain_tags').tagsinput('destroy');
                    $('#domain_tags').tagsinput();

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

function updateStatus(id, type) {
    // bootbox.confirm('هل انت متاكد من تغيير حالة المنتج ؟', function (result) {
    //     if (result) {
            var my_data = {};
            my_data.id = id;
            if (type == 0) {
                my_data.status = 0;
            } else {
                my_data.status = 1;
            }
            $.post(urls.update_status, my_data, function (data) {
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
    //     }
    // });
}

function viewDetails(obj) {
    try {
        dialog_is.title = model_js_lang.dialog_title;
        dialog_is.message = '<table class="table table-bordered" style="width: 100%;">';
        $.each(users_info[obj].images, function (key, val) {
            dialog_is.message += '<tr><th><a title="' + js_lang.delete_record + '" id="delet_' + val.id + '" class="btn btn-sm red" onclick="deleteRowImage(' + val.id + ')"><i class="fa fa-trash-o"></i>  ' + js_lang.delete_record + '</a></th><td><div class="profile-userpic"><img src="' + assets_folder + '/tmp/' + val.image + '" width="100" height="100" alt=""></td></tr>';
        });
        dialog_is.message += '</table>';
        delete dialog_is.buttons.success;
        dialog_is.buttons.danger.callback = function () {
            bootbox.hideAll();
            return false;
        };
        bootbox.dialog(dialog_is);
    } catch (e) {
        alert(e.message)
    }
}

function deleteRowImage(id) {
    bootbox.confirm('هل انت متاكد من الحذف ؟', function (result) {
        if (result) {
            var my_data = {};
            my_data.id = id;
            my_data._token = token_code;
            $.post(urls.delete_url_image, my_data, function (data) {
                if (data[0]) {
                    dataTable.ajax.reload();
                    showNotify('success', model_js_lang[data[1]]);
                } else {
                    if (js_lang[data[1]]) {
                        showNotify('error', js_lang[data[1]]);
                    } else {
                        showNotify('error', model_js_lang[data[1]]);
                    }
                }
                $('#delet_' + id).parent().parent().remove();
            }, 'json');
        }
    });
}

function editRow(id) {
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
                $(form1).find('input[name=code]').val(data[2].code);
                $(form1).find('input[name=count_number]').val(data[2].count_number);
                $(form1).find('input[name=code_limit]').val(data[2].code_limit);
                $(form1).find('input[name=code_max]').val(data[2].code_max);
                $(form1).find('input[name=discount]').val(data[2].discount);
                $(form1).find('input[name=end_at]').val(data[2].end_at);
                $('#alerts-form-modal').modal('show');
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

