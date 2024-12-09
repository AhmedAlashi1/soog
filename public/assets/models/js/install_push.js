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
    $('#email_tags').val('');
    $('#email_tags').tagsinput('destroy');
    $('#email_tags').tagsinput();
    $('#api_div').show();
    $('#status_div').show();
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
        {"data": "subject", "orderable": false, },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                var html = '<div style="width: 100%;text-align: center;">';
                    html += '<textarea class="form-control" >'+row.message+'</textarea>';
                html += '</div>';
                return html;
            }
        },
        {"data": function(row){
            var countryHtml = '';
            if(row.country){
                countryHtml = '<img src="' + assets_folder + '/global/img/flags/' + row.country.country_code.toLowerCase() + '.png"/>';
                return countryHtml+row.country.name_ar ;
            }else{
                return '';
            }

        }, "orderable": false},
        {"data": "city", "orderable": false, },
        {"data": "send_after", "orderable": false, },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                var html = '<div style="width: 100%;text-align: center;">';
                    html += row.messages_send;
                html += '</div>';
                return html;
            }
        },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                var html = '<div style="width: 100%;text-align: center;">';
                html += row.messages_faild;
                html += '</div>';
                return html;
            }
        },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                var inner = '<span class=""> ' + model_js_lang.activated + ' </span>';
                if (row.status == "0") {
                    inner = '<span class=""> ' + model_js_lang.disactivated + ' </span>';
                }
                return inner;
            }
        },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                var html = '<div style="width: 100%;text-align: center;">';
                html += '<a title="التفاصيل" class="btn btn-sm blue"  href="'+urls.details+'/'+row.id+'"><i class="fa fa-search"></i></a>';
                if (row.status == 1) {
                    html += '<a class="btn btn-sm green" title="الغاء تفعيل"  onclick="updateStatus(' + row.id + ', 0)"><i class="fa fa-check"> </i></a>';
                } else {
                    html += '<a class="btn btn-sm yellow" title="تفعيل"  onclick="updateStatus(' + row.id + ', 1)"><i class="fa fa-close"></i> </a>';
                }
                html += '<a title="' + js_lang.delete_record + '" class="btn btn-sm red" onclick="deleteRow(' + row.id + ')"><i class="fa fa-trash-o"></i></a>';
                html += '</div>';
                return html;
            },"width":"15%"
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
        subject: {
            required: true,
        },
        url: {
            required: true,
            url: true
        },
        message: {
            required: true
        }
    },
    messages: {// custom messages for radio buttons and checkboxes
        subject: {
            required: js_lang.field_required,
        },
        url: {
            required: js_lang.field_required,
            url: model_js_lang.field_required_url,
        },
        message: {
            required: js_lang.field_required,
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
        $.ajax({
            url: url,
            type: 'post',
            data: $(form).serialize(),
            success: function (data) {
                if (data[0] == true) {
                    $(form)[0].reset();
                    showNotify('success', model_js_lang[data[1]]);
                    $("#country_code").select2("val", "");
                    $("#cities_select").select2("val", "");
                    $("#region_select").select2("val", "");
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

function updateStatus(id, value) {
    var my_data = {};
    my_data.id = id;
    my_data.status = value;
    $.post(urls.update_status_url, my_data, function (data) {
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
                $(form1).find('input[name=total_to_notify]').val(data[2].total_to_notify);
                $(form1).find('input[name=notify_email]').val(data[2].notify_email);
//                $(form1).find('select[name=api]').val(data[2].api).select();
//                $(form1).find('select[name=status]').val(data[2].status).select();
                $('#api_div').hide();
                $('#status_div').hide();
                $(form1).find('select[name=notify_in]').val(data[2].notify_in).select();
                $(form1).find('#send_email_check').bootstrapSwitch('state', data[2].send_email == 1 ? true : false);
                $(form1).find('#multi_ip_check').bootstrapSwitch('state', data[2].multi_ip == 1 ? true : false);
                if (data[2].send_email == 1) {
                    $('#email_tags_div').show();
                } else {
                    $('#email_tags_div').hide();
                }
                $('#email_tags').tagsinput('destroy');
                $('#email_tags').tagsinput();
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
$.fn.select2.defaults.set("theme", "bootstrap");

$("#country_code").select2({
    placeholder: 'اختر بلد',
    width: null,
    allowClear: true,
    formatResult: format,
    formatSelection: format,
    escapeMarkup: function (m) {
        return m;
    }
});

function format(state) {
    if (!state.id)
        return state.text; // optgroup
    return "<img class='flag' src='" + Metronic.getGlobalImgPath() + "flags/" + state.id.toLowerCase() + ".png'/>&nbsp;&nbsp;" + state.text;
}

$("#country_code").on("change", function () {
    $.get(urls.cities_url, {"country_code": this.value}, function (data) {
        $('#cities_select').empty();
        $.each(data, function (i, v) {
            $('#cities_select').append('<option value="' + v.city + '">' + v.city + '</option>');
        });
    }, 'JSON');
});

$('#cities_select').select2({
    placeholder: "اختر مدن",
    width: null,
    allowClear: true
});


$('#region_select').select2({
    placeholder: "اختر منطقة",
    width: null,
    allowClear: true
});

$("#cities_select").on("change", function () {
    $.get(urls.region_url, {"cites": $("#cities_select").select2("val"),"country_code":$("#country_code").val()}, function (data) {
        $('#region_select').empty();
        $.each(data, function (i, v) {
            $('#region_select').append('<option value="' + v.locality + '">' + v.locality + '</option>');
        });
    }, 'JSON');
});
