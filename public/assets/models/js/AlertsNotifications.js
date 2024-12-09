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
    "autoWidth": false,
    "pagingType": "bootstrap_full_number",
    "processing": false,
    "serverSide": true,
    "ajax": {
        type: "post",
        url: urls.get_data_url, // json datasource
        data: function (d) {
            return  $("#alerts_notifications_search_form").serialize() + '&' + $.param(d)
        }
    },
    "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        if (aData.seen == 0) {
            $(nRow).addClass('danger');
        }
    },
    "columns": [
        {"data": "id", "orderable": false, },
        {
            "data": function (row, type, val, meta) {
                return '<input type="checkbox" name="ids[]" class="checkboxes" value="' + row.id + '" title="" />';
            },
            "orderable": false,
        },
        {
            "data": function (row, type, val, meta) {
                return typeof api_types[row.api] != 'undefined' ? api_types[row.api] : row.api;
            }, "orderable": false,
        },
        {
            "data": function (row, type, val, meta) {
                return typeof status_codes[row.status] != 'undefined' ? '(' + row.status + ') ' + status_codes[row.status].split('_').join(' ').capitalize() : 'unkown';
            }, "orderable": false,
        },
        {"data": "total_logs", "orderable": false, },
        {"data": "ip_address", "orderable": false, },
        {"data": function (row) {
                var inner = '<span class="label label-sm label-success"> ' + model_js_lang.yes + ' </span>';
                if (row.seen == "0") {
                    inner = '<span class="label label-sm label-danger"> ' + model_js_lang.no + ' </span>';
                }
                return inner;
            }, "orderable": false, },
        {"data": "created_at", "orderable": false, },
    ],
});
$("#alerts_notifications_delete_btn").click(function () {
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
$("#alerts_notifications_seen_btn").click(function () {
    toastr.options.positionClass = "toast-top-center";
    if ($('input[name="ids[]"]:checked').length) {
        var ids = [];
        $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
            ids.push($(y).val());
        });
        var my_data = {};
        my_data.ids = ids;
        my_data._token = token_code;
        $.post(urls.update_status_url, my_data, function (data) {
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
    } else {
        Command: toastr['error'](js_lang.box_message);
        return false;
    }
});
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
$.fn.select2.defaults.set("theme", "bootstrap");
$(".api_select2").select2({
    placeholder: 'اختر api',
    width: null
});
$(".status_select2").select2({
    placeholder: 'اختر نتيجة',
    width: null
});

$('#excute_search').click(function () {
    dataTable.ajax.reload();
    return false;
});