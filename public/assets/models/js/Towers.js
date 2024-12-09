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
        type: "POST",
        url: urls.get_data_url, // json datasource
        data: function (d) {
            return  $("#towers_search_form").serialize() + '&' + $.param(d)
        }
    },
    "columns": [
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                return '<input type="checkbox" name="ids[]" class="checkboxes" value="' + row.id + '" title="" />';
            },  "width": "5%"
        },
        {"data": function(row){
            var countryHtml = '';
            if(row.tower.country_code){
                countryHtml = '(' + row.tower.country_code + ') <img src="' + assets_folder + '/global/img/flags/' + row.tower.country_code.toLowerCase() + '.png"/>';
            }
            return countryHtml;
        }, "orderable": false},
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                return row.tower.lac;
            }
        },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                return row.tower.cid;
            }
        },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                return row.tower.mcc;
            }
        },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                return row.tower.mnc;
            }
        },
        {"data": "home_power", "orderable": false},
        {"data": "work_power", "orderable": false},
        {"data": "created_at", "orderable": false},
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                var html = '<div style="width: 100%;text-align: center;">';
                    html += '<a target="_blank" class="btn btn-sm green" href="http://maps.google.com/?q='+row.tower.latitude+','+row.tower.longitude+'" ><i class="fa fa-map-o"></i>  ' + model_js_lang.map + '</a>';
                html += '</div>';
                return html;
            } ,"width": "10%"
        }
    ],
    "lengthMenu": [
        [50, 100, 150, 200],
        [50, 100, 150, 200] // change per page values here

    ],
    "pageLength": 100,
    "order": [[0, 'desc']],
});
