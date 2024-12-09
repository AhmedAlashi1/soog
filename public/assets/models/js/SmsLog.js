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
        type: "post",
        url :urls.get_data_url, // json datasource
        data: function (d) {
            return  $("#api_log_search_form").serialize() + '&' + $.param(d)
        }
    },
    "searching": false,
    "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
        if (aData["status"] == "failed") {
            $(nRow).addClass('disactivated-user');
        }
        else {
            $(nRow).addClass('activated-user');
        }
    },
    "columns": [
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                return '<input type="checkbox" name="ids[]" class="checkboxes" value="' + row.id + '" title="" />';
            }
        },
        {"data": function (row) {
            if(!row.user || 0 === row.user.country_code.length){
                return row.numbers;
            }
            return '<img src="' + assets_folder + '/global/img/flags/' + row.user.country_code.toLowerCase() + '.png"/>' + row.numbers;
        }, "orderable": false},
        {"data": "sender", "orderable": false, },
        {"data": "gateway", "orderable": false, },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                var inner = '<textarea class="form-control" dir="ltr">'+row.message+'</textarea>';
                return inner;
            }
        },

        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                var inner = '<span class="badge badge-success"><i class="icon-check"></i></span>';
                if (row.status == "failed") {
                    inner = '<span class="badge badge-danger"> <i class="icon-close"></i> </span>';
                }
                return inner;
            }
        },
        {"data": "gate_message", "orderable": false, },
        {"data": "created_at", "orderable": false, },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                users_info[row.id] = row;
                var html = '<div style="width: 100%;text-align: center;">';
                    html += '<a title="' + js_lang.delete_record + '" class="btn btn-sm red" onclick="deleteRow(' + row.id + ')"><i class="fa fa-trash-o"></i>  ' + js_lang.delete_record + '</a> <br>';
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

$('#excute_search').click(function () {
    dataTable.ajax.reload();
    return false;
});

function viewDetails(obj) {
    try {
        dialog_is.title = 'تفاصيل المستخدم';
        dialog_is.message = '<table class="table table-bordered" style="width: 100%;">';
        dialog_is.message += '<tr><th>اسم المستخدم</th><td><textarea class="form-control">' + users_info[obj].user.full_name + '</textarea></td></tr>';
        dialog_is.message += '<tr><th>رقم الجوال</th><td><textarea class="form-control">' + users_info[obj].user.mobile_number + '</textarea></td></tr>';
        dialog_is.message += '<tr><th>الرقم التسلسلى للجهاز</th><td><textarea class="form-control">' + users_info[obj].user.device_serial + '</textarea></td></tr>';
        dialog_is.message += '<tr><th>المدينة</th><td><textarea class="form-control">' + users_info[obj].user.city + '</textarea></td></tr>';
        dialog_is.message += '<tr><th>المنطقة</th><td><textarea class="form-control">' + users_info[obj].user.locality + '</textarea></td></tr>';
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