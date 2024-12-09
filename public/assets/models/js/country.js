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
var form1 = $('#property_form');
$('#admins_create_btn').click(function () {
    $(form1)[0].reset();
    $(form1).find('input[name=id]').val('');
    $('#admins-form-modal').modal('show');
});

var form2 = $('#city_form');

var form3 = $('#zon_form');

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
            },  "width": "5%"
        },
        {"data": "title_ar", "orderable": false},
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
            "data": function (row, type, val, meta) {
                users_info[row.id] = row;
                var html = '<div style="width: 100%;text-align: center;">';
                html += '<a title="اضافة منطقة" class="btn btn-sm green" onclick="addCity(\'' + row.id + '\')"><i class="fa fa-plus"></i> اضافة منطقة</a>';
                html += '<a title="المناطق" class="btn btn-sm yellow" onclick="viewDetails(\'' + row.id + '\')"><i class="fa fa-bars"></i> المناطق</a>';
                html += '<a title="' + js_lang.edit_record + '" class="btn btn-sm blue"  onclick="editRow(' + row.id + ')"><i class="fa fa-edit"></i> ' + js_lang.edit_record + '</a>';
                html += '<a title="' + js_lang.delete_record + '" class="btn btn-sm red" onclick="deleteRow(' + row.id + ')"><i class="fa fa-trash-o"></i>  ' + js_lang.delete_record + '</a>';
                html += '</div>';
                return html;
            } ,"width": "40%"
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
        title_ar: {
            required: true
        },title_en: {
            required: true
        }
    },
    messages: {// custom messages for radio buttons and checkboxes
        'title': {
            required: js_lang.field_required,
        },'title_en': {
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
                    dataTable.ajax.reload(null, false);
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

function updateStatus(id, type) {

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
        dataTable.ajax.reload(null, false);
    }, 'json');
}
function updateStatusCity(id, type,country) {

    var my_data = {};
    my_data.id = id;
    if (type == 0) {
        my_data.status = 0;
    } else {
        my_data.status = 1;
    }
    $.post(urls.update_status_city, my_data, function (data) {
        if (data[0]) {
            showNotify('success', model_js_lang[data[1]]);
        } else {
            if (js_lang[data[1]]) {
                showNotify('error', js_lang[data[1]]);
            } else {
                showNotify('error', model_js_lang[data[1]]);
            }
        }
        dataTable2.ajax.url(urls.get_data_url_city+'?country='+country).load();
    }, 'json');
}

form2.validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "",
    rules: {
        title_ar: {
            required: true,
            minlength: 2
        },title_en: {
            required: true,
            minlength: 2
        }
    },
    messages: {// custom messages for radio buttons and checkboxes
        'title': {
            required: js_lang.field_required,
            minlength: model_js_lang.name_minlength
        },'title_en': {
            required: js_lang.field_required,
            minlength: model_js_lang.name_minlength
        },'delivery_cost': {
            required: js_lang.field_required,
        },'order_limit': {
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
            var url = urls.save_url_city;
        } else {
            var url = urls.update_url_city;
        }
        var formData = new FormData(form2[0]);
        $.ajax({
            url: url,
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function (data) {
                if (data[0] == true) {
                    $(form)[0].reset();
                    $('#city-form-modal').modal('hide');
                    dataTable2.ajax.reload(null, false);
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

form3.validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "",
    rules: {
        name: {
            required: true,
            minlength: 2
        }
    },
    messages: {// custom messages for radio buttons and checkboxes
        'name': {
            required: js_lang.field_required,
            minlength: model_js_lang.name_minlength
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
            var url = urls.save_url_zon;
        } else {
            var url = urls.update_url_zon;
        }
        var formData = new FormData(form3[0]);
        $.ajax({
            url: url,
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function (data) {
                if (data[0] == true) {
                    $(form)[0].reset();
                    $(form3).find('input[name=id]').val('');
                    $('#zon-form-modal').modal('hide');
                    dataTable3.ajax.reload(null, false);
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
                dataTable.ajax.reload(null, false);
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
                    dataTable.ajax.reload(null, false);
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
        showNotify('error', js_lang.box_message);
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
                $(form1).find('input[name=title_ar]').val(data[2].title_ar);
                $(form1).find('input[name=title_en]').val(data[2].title_en);
                $(form1).find('input[name=title_tr]').val(data[2].title_tr);
                $('#admins-form-modal').modal('show');
            } else {
                showNotify('error', data.message);
            }
        }
    });
}

/**
 *
 * @param id
 */
function addCity(id)
{
    $(form2).find('input[name=id]').val('');
    $(form2).find('input[name=governorat_id]').val(id);
    $('#city-form-modal').modal('show');

}
function viewDetails(obj) {
    try {
        dataTable2.ajax.url(urls.get_data_url_city+'?country='+obj).load();
        $('#city-data-modal').modal('show');
    } catch (e) {
        alert(e.message)
    }
}
var dataTable2 = $('#table_view_city').DataTable({
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
        url :urls.get_data_url_city ,// json datasource
    },
    "columns": [
        {"data": "id", "orderable": false},
        {"data": "title_ar", "orderable": false},
        {"data": "delivery_cost", "orderable": false},
        {"data": "order_limit", "orderable": false},
        // {
        //     "orderable": false,
        //     "data": function (row, type, val, meta) {
        //         var html = '<div style="width: 100%;text-align: center;">';
        //         if(row.far_zone == 1){
        //             html +='نعم';
        //         }
        //         html += '</div>';
        //         return html;
        //     } ,"width": "30%"
        // },
        {
            "data": function (row, type, val, meta) {
                if (row.status == 1) {
                    return '<a onclick="updateStatusCity(' + row.id + ',0,'+row.governorat_id+')"><span class="badge badge-success"><i class="icon-check"></i></span></a>';
                } else if (row.status == 0) {
                    return '<a onclick="updateStatusCity(' + row.id + ',1,'+row.governorat_id+')"><span class="badge badge-danger"> <i class="icon-close"></i> </span></a>';
                } else {
                    return '';
                }
            }, "orderable": false
        },
        {"data": "created_at", "orderable": false},
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                users_info[row.id] = row;
                var html = '<div style="width: 100%;text-align: center;">';
                html += '<a title="' + js_lang.edit_record + '" class="btn btn-sm blue"  onclick="editRowCity(' + row.id + ')"><i class="fa fa-edit"></i> ' + js_lang.edit_record + '</a>';
                html += '<a title="' + js_lang.delete_record + '" class="btn btn-sm red" onclick="deleteRowCity(' + row.id + ',' + row.governorat_id + ')"><i class="fa fa-trash-o"></i>  ' + js_lang.delete_record + '</a>';
                html += '</div>';
                return html;
            } ,"width": "30%"
        }
    ],
    "lengthMenu": [
        [50, 100, 150, 200],
        [50, 100, 150, 200] // change per page values here

    ],
    "pageLength": 100,
    "order": [[0, 'desc']],
});
function addZon(id)
{
    $(form3).find('input[name=city_id]').val(id);
    $('#zon-form-modal').modal('show');

}


function editRowCity(id)
{
    var form_data = {};
    form_data.id = id;
    form_data._token = token_code;
    $.ajax({
        url: urls.get_row_url_city,
        data: form_data,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            $('#city-data-modal').modal('hide');
            if (data[0] == true) {
                $(form2).find('input[name=id]').val(data[2].id);
                $(form2).find('input[name=governorat_id]').val(data[2].governorat_id);
                $(form2).find('input[name=title_ar]').val(data[2].title_ar);
                $(form2).find('input[name=title_en]').val(data[2].title_en);
                $(form2).find('input[name=delivery_cost]').val(data[2].delivery_cost);
                $(form2).find('input[name=order_limit]').val(data[2].order_limit);
                // if (data[2].far_zone == 1) {
                //     $('.rooms_check').bootstrapSwitch('state', true);
                // }else{
                //     $('.rooms_check').bootstrapSwitch('state', false);
                // }
                $('#city-form-modal').modal('show');
            } else {
                showNotify('error', data.message);
            }
        }
    });
}

function deleteRowCity(id,country) {
    bootbox.confirm(js_lang.conferm_delete, function (result) {
        if (result) {
            var my_data = {};
            my_data.id = id;
            my_data._token = token_code;
            $.post(urls.delete_url_city, my_data, function (data) {
                if (data[0]) {
                    showNotify('success', model_js_lang[data[1]]);
                } else {
                    if (js_lang[data[1]]) {
                        showNotify('error', js_lang[data[1]]);
                    } else {
                        showNotify('error', model_js_lang[data[1]]);
                    }
                }
                dataTable2.ajax.url(urls.get_data_url_city+'?country='+country).load();
            }, 'json');
        }
    });
}

function editRowZon(id)
{
    var form_data = {};
    form_data.id = id;
    form_data._token = token_code;
    $.ajax({
        url: urls.get_row_url_zon,
        data: form_data,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            $('#city-data-modal').modal('hide');
            $('#zon-data-modal').modal('hide');
            if (data[0] == true) {
                $(form3).find('input[name=id]').val(data[2].id);
                $(form3).find('input[name=city_id]').val(data[2].city_id);
                $(form3).find('input[name=name]').val(data[2].name);
                $('#zon-form-modal').modal('show');
            } else {
                showNotify('error', data.message);
            }
        }
    });
}

function deleteRowZon(id,country) {
    bootbox.confirm(js_lang.conferm_delete, function (result) {
        if (result) {
            var my_data = {};
            my_data.id = id;
            my_data._token = token_code;
            $.post(urls.delete_url_zon, my_data, function (data) {
                if (data[0]) {
                    showNotify('success', model_js_lang[data[1]]);
                } else {
                    if (js_lang[data[1]]) {
                        showNotify('error', js_lang[data[1]]);
                    } else {
                        showNotify('error', model_js_lang[data[1]]);
                    }
                }
                dataTable3.ajax.url(urls.get_data_url_zon+'?city='+country).load();
            }, 'json');
        }
    });
}