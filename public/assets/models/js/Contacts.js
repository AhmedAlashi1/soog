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
    "processing": false,
    "serverSide": true,
    "deferLoading": 0,
    "ajax": {
        type: "POST",
        url: urls.get_data_url, // json datasource
        data: function (d) {
            return  $("#search_form").serialize() + '&' + $.param(d)
        },
        'error': function (xhr, status, error) {
            showNotify('error', error);
        },
        "dataSrc": function (json) {
            if (typeof json.status !== 'undefined') {
                if (json.status == false) {
                    showNotify('error', json.message);
                    return false;
                }
            }
            return json.data;
        }
    },
    "columns": [
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                return '<input type="checkbox" name="ids[]" class="checkboxes" value="' + row.id + '" title="" />';
            }
        },
        {"data": "name", "orderable": false, },
        {"data": "mobile", "orderable": false, },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                var inner = '<div style="width: 100%;text-align: center;">';
                if (row.gender == "m") {
                    inner = '<span class=""> ذكر </span>';
                } else if (row.gender == "f") {
                    inner = '<span class=""> أنثى </span>';
                }
                inner = '</div>';
                return inner;
            }
        },
        {"data": function (row, type, val, meta) {
                return '(' + row.country + ') <img src="' + assets_folder + '/global/img/flags/' + row.country.toLowerCase() + '.png"/>';
            }, "orderable": false
        },
        {"data": "region", "orderable": false, },
        {"data": "insert_date", "orderable": false, },
    ],
    "lengthMenu": [
        [50, 100, 150, 200],
        [50, 100, 150, 200] // change per page values here

    ],
    "pageLength": 100,
    "order": [[0, 'desc']],
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
            $('#defaultrange input').val(start.format('YYYY-MM-DD') + ' / ' + end.format('YYYY-MM-DD'));
        }
);

$('#excute_search').click(function () {
    var country = $('#country').val();
    if (country == 0 || country == '') {
        showNotify('error', 'لابد من اختيار الدولة لكى تتمكن من البحث');
        return false;
    }

    dataTable.ajax.reload();
    return false;
});
function format(state) {
    if (!state.id)
        return state.text; // optgroup
    return "<img class='flag' src='" + App.getGlobalImgPath() + "flags/" + state.id.toLowerCase() + ".png'/>&nbsp;&nbsp;" + state.text;
}
$.fn.select2.defaults.set("theme", "bootstrap");
$("#country").select2({
    placeholder: 'اختر دولة',
    width: null,
    allowClear: true,
    formatResult: format,
    formatSelection: format,
    escapeMarkup: function (m) {
        return m;
    }
});

