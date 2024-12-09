var users_info = {};
var users = {};
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
        type: "post",
        url: urls.get_data_url, // json datasource
        data: function (d) {
            return  $("#api_log_search_form").serialize() + '&' + $.param(d)
        }
    },
    "searching": false,
    "columns": [
        {
            "data": function (row, type, val, meta) {
                return '<input type="checkbox" name="ids[]" class="checkboxes" value="' + row._id + '" title="" />';
            },
            "orderable": false,
        },
        {
            "data": function (row, type, val, meta) {
                if(row.user){
                    return row.user.device_model;
                }else{
                    return ''
                }

            }, "orderable": false,
        },
        {
            "data": function (row, type, val, meta) {
                if(row.user){
                    return row.user.mobile_number;
                }else{
                    return ''
                }

            }, "orderable": false,
        },
        {
            "data": function (row, type, val, meta) {
                return model_js_lang[row.service_type];
            }, "orderable": false,
        },
        {
            "data": function (row, type, val, meta) {
                return row.service_code;
            }, "orderable": false,
        },
        {
            "data": function (row, type, val, meta) {
                return row.message;
            }, "orderable": false,
        },
        {"data": "number", "orderable": false, },
        {"data": "response_date", "orderable": false, },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                users_info[row._id] = row;
                var html = '<div style="width: 100%;text-align: center;">';
                html += '<a title="الريكويست" class="btn btn-sm yellow" onclick="viewDetails(\'' + row._id + '\')"><i class="fa fa-edit"></i> تفاصيل</a>';
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

$("#log_delete_btn").click(function () {
    toastr.options.positionClass = "toast-top-center";
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
                $.post(urls.delete_url, my_data, function (data) {
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


$('.delete_date').click(function(){
    $('#daterange').val('');
    return false;
});
$('#excute_search').click(function () {
    dataTable.ajax.reload();
    return false;
});
$.fn.select2.defaults.set("theme", "bootstrap");
$(".api_select2").select2({
    placeholder: 'اختر نوع الخدمة',
    width: null,
    allowClear: true
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
            dialog_is.message += '<tr><th>تاريخ الارسال</th><td><textarea class="form-control">' + users_info[obj].response_date + '</textarea></td></tr>';
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
