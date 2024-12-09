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
/**
 * datatable ajax
 * @type {jQuery}
 */

var dataTable = $('#table_view').DataTable({
    "language": {
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
    //"processing": true,
    'fnDrawCallback': function ()
    {
        $('#table_view').show();
    },
    "serverSide": true,
    "ajax": {
        type: "POST",
        url: urls.get_data_url, // json datasource
        data: function (d) {
            return  $("#full_users_search_form").serialize() + '&' + $.param(d)
        }
    },
    responsive: {
        details: {
            type: 'column',
        }
    },
    dom: 'Bfrtip',
    buttons: [
        'csv', 'excel'
    ],
    columnDefs: [{
            className: 'control',
            targets: 0
        }],
    "autoWidth": false,
    "searching": false,
    "columns": [{
            "orderable": false,
            "data": function (row, type, val, meta) {
                return '';
            }
        },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                return '<input type="checkbox" name="ids[]" class="checkboxes" value="' + row.id + '" title="" />';
            }
        },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                return row.first_name+' '+row.last_name;
            }
        },
        {"data": "email", "orderable": false},
        {"data": "mobile_number", "orderable": false},
        {"data": "credit", "orderable": false},

        {"data": function (row, type, val, meta) {
                if (row.status == 'active') {
                    return "<a class='label label-sm label-info' onclick='deActiveUser(" + row.id + ");'> <i class='fa fa-check'></i> " + model_js_lang.activated + "</a>";
                } else if (row.status == 'inactive') {
                    return "<a class='label label-sm label-danger' onclick='ActiveUser(" + row.id + ");'> <i class='fa fa-user-times'></i> " + model_js_lang.inactive + "</a>";
                } else if (row.status == 'pending_activation') {
                    return "<a class='label label-sm label-warning' onclick='ActiveUser(" + row.id + ");'> <i class='fa fa-user-times'></i> " + model_js_lang.pending_activation + "</a>";
                } else {
                    return '';
                }
            }, "orderable": false
        },
        {"data": function (row, type, val, meta) {
                return row.address
        }, "orderable": false
        },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                var html = '<div style="width: 100%;text-align: center;">';
                users_info[row.id] = row;
                html += '<a  class="btn btn-sm blue"  onclick="addCredit(' + row.id + ',1)"><i class="fa fa-cogs"></i> اضافة رصيد</a>';
                html += '<a href="'+urls.address+'?user_id='+row.id+'" target="_blank" class="btn btn-sm purple" ><i class="fa fa-bars"></i> العناوين</a>';
                //html += '<a href="'+urls.orders+'/'+row.id+'" target="_blank" class="btn btn-sm purple" ><i class="fa fa-bars"></i> الطلبات</a>';
                html += '<a  class="btn btn-sm blue"  onclick="detailsRow(' + row.id + ')"><i class="fa fa-edit"></i> التفاصيل</a>';
                html += '<a title="' + js_lang.delete_record + '" class="btn btn-sm red" onclick="deleteRow(' + row.id + ')"><i class="fa fa-trash-o"></i>  ' + js_lang.delete_record + '</a>';
                html += '</div>';
                return html;
            } ,"width": "30%"
        }
    ],
    "lengthMenu": [
        [100, 200, 500, 1000],
        [100, 200, 500, 1000] // change per page values here

    ],
    "pageLength": 100,
    "order": [[2, 'desc']]
});
/**
 * deactivate users function
 * @param id
 * @returns {boolean}
 */
function addCredit(id) {
    $(form1)[0].reset();
    $(form1).find('input[name=id]').val(id);
    $('#alerts-form-modal').modal('show');
}
var form1 = $('#alerts_form');
var error1 = $('.alert-danger', form1);
form1.validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "",
    rules: {
        credit: {
            required: true,
        }
    },
    messages: {
        'credit': {
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
            var url = urls.credit_url;
        $.ajax({
            url: url,
            type: 'post',
            data: $(form).serialize(),
            success: function (data) {
                if (data[0] == true) {
                    $(form)[0].reset();
                    $('#alerts-form-modal').modal('hide');
                    dataTable.ajax.reload(null, false);
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
/**
 *
 * @param id
 * @returns {boolean}
 * @constructor
 */
function ResetActiveEmailUser(id) {
    bootbox.confirm(model_js_lang.reset_confirm_email, function (result) {
        if (result) {
            var my_data = {};
            my_data.ids = [id];
            my_data._token = token_code;
            $.post(urls.reset_email_url, my_data, function (data) {
                if (data.status == true) {
                    Command: toastr['success'](data.message);
                } else {
                    Command: toastr['error'](data.message);
                }
                dataTable.ajax.reload(null, false);
            }, 'json');
        }
    });
    return false;
}

function deActiveUser(id) {
    bootbox.confirm(model_js_lang.daactivated_confirm, function (result) {
        if (result) {
            var my_data = {};
            my_data.ids = [id];
            my_data._token = token_code;
            $.post(urls.deactivate_url, my_data, function (data) {
                if (data.status == true) {
                    Command: toastr['success'](data.message);
                } else {
                    Command: toastr['error'](data.message);
                }
                dataTable.ajax.reload(null, false);
            }, 'json');
        }
    });
    return false;
}
/**
 * Active user function
 * @param id
 * @returns {boolean}
 * @constructor
 */
function ActiveUser(id) {
    bootbox.confirm(model_js_lang.activated_confirm, function (result) {
        if (result) {
            var my_data = {};
            my_data.ids = [id];
            my_data._token = token_code;
            $.post(urls.activate_url, my_data, function (data) {
                if (data.status == true) {
                    Command: toastr['success'](data.message);
                } else {
                    Command: toastr['error'](data.message);
                }
                dataTable.ajax.reload(null, false);
            }, 'json');
        }
    });
    return false;
}
/**
 * active all users
 */
$("#app_users_activate_btn").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
        bootbox.confirm(model_js_lang.activated_confirm_all, function (result) {
            if (result) {
                var ids = [];
                $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
                    ids.push($(y).val());
                });
                var my_data = {};
                my_data.ids = ids;
                my_data._token = token_code;
                $.post(urls.activate_url, my_data, function (data) {
                    if (data.status == true) {
                        Command: toastr['success'](data.message);
                    } else {
                        Command: toastr['error'](data.message);
                    }
                    dataTable.ajax.reload(null, false);
                }, 'json');
            }
        });
    } else {
        Command: toastr['error'](js_lang.box_message);
        return false;
    }
});
/**
 * deactive all users
 */
$("#app_users_deactivate_btn").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
        bootbox.confirm(model_js_lang.deactivated_confirm_all, function (result) {
            if (result) {
                var ids = [];
                $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
                    ids.push($(y).val());
                });
                var my_data = {};
                my_data.ids = ids;
                my_data._token = token_code;
                $.post(urls.deactivate_url, my_data, function (data) {
                    if (data.status == true) {
                        Command: toastr['success'](data.message);
                    } else {
                        Command: toastr['error'](data.message);
                    }
                    dataTable.ajax.reload(null, false);
                }, 'json');
            }
        });
    } else {
        Command: toastr['error'](js_lang.box_message);
        return false;
    }
});
$("#app_users_credit_btn").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
                var ids = [];
                $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
                    ids.push($(y).val());
                });
                const my_data = ids.join();
                $(form1)[0].reset();
                $(form1).find('input[name=id]').val(my_data);
                $('#alerts-form-modal').modal('show');

    } else {
        Command: toastr['error'](js_lang.box_message);
        return false;
    }
});

$("#apps_delete_btn").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
        bootbox.confirm('هل انت متاكد من حذف المحدد ؟', function (result) {
            if (result) {
                var ids = [];
                $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
                    ids.push($(y).val());
                });
                var my_data = {};
                my_data.ids = ids;
                my_data._token = token_code;
                $.post(urls.delete_url, my_data, function (data) {
                    if (data.status == true) {
                        Command: toastr['success'](data.message);
                    } else {
                        Command: toastr['error'](data.message);
                    }
                    dataTable.ajax.reload(null, false);
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

$('.delete_date').click(function () {
    $('#daterange').val('');
    return false;
});
/**
 * for filtration
 */
$('#excute_search').click(function () {
    dataTable.ajax.reload(null, false);
    return false;
});
function statusRow(obj)
{
    try {
        dialog_is.title = 'عرض ارشيف شحنات العميل';
        dialog_is.message = '<table class="table table-bordered" style="width: 100%;">';
        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">القيمة</th>';
        dialog_is.message += '<th class="text-info">تاريخ التنفيذ</th>';
        dialog_is.message += '<th class="text-info">ملاحظات</th>';
        dialog_is.message += '</tr>';
        $.each(users_info[obj].charges, function (key, val) {
            dialog_is.message += '<td>'+val.credit+'</td>';
            dialog_is.message += '<td>'+val.created_at+'</td>';
            dialog_is.message += '<td>'+val.comment+'</td>';
            dialog_is.message += '</tr>';
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
function detailsRow(obj)
{
    try {
        dialog_is.title = 'عرض بيانات العميل التفصيلية';
        dialog_is.message = '<table class="table table-bordered" style="width: 100%;">';

        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">#</th>';
        dialog_is.message += '<th>'+users_info[obj].id+'</th>';
        dialog_is.message += '</tr>';

        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">العنوان</th>';
        dialog_is.message += '<th>'+users_info[obj].address+'</th>';
        dialog_is.message += '</tr>';

        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">البريد الالكترونى</th>';
        dialog_is.message += '<th>'+users_info[obj].email+'</th>';
        dialog_is.message += '</tr>';

        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">رقم الجوال</th>';
        dialog_is.message += '<th>'+users_info[obj].mobile_number+'</th>';
        dialog_is.message += '</tr>';


        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">الصورة الشخصية</th>';
        dialog_is.message += '<th><img src="'+assets_folder+'/tmp/'+users_info[obj].avatar+'" width="100" height="100" /></th>';
        dialog_is.message += '</tr>';

        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">الاسم بالكامل</th>';
        dialog_is.message += '<th>'+users_info[obj].first_name+' '+users_info[obj].last_name+'</th>';
        dialog_is.message += '</tr>';

        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">عدد الطلبات</th>';
        dialog_is.message += '<th>'+users_info[obj].charges_count+'</th>';
        dialog_is.message += '</tr>';

        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">اجمالى الفواتير</th>';
        dialog_is.message += '<th>'+users_info[obj].discount_total+'</th>';
        dialog_is.message += '</tr>';

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