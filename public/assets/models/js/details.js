var users_info = {};
var status_info = {};
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

var form1 = $('#admins_form');
$('#admins_create_btn').click(function () {
    $(form1)[0].reset();
    $(form1).find('input[name=id]').val('');
    $('#admins-form-modal').modal('show');
});


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
        url: urls.get_data_url_details, // json datasource
        data: function (d) {
            return  $("#full_users_search_form").serialize() + '&' + $.param(d)
        }
    },
    responsive: {
        details: {
            type: 'column',
        }
    },
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
        {"data": "id", "orderable": false},
        {"data": function (row) {
            if(row.user){
                return row.user.full_name;
            }else{
                return '';
            }

        }, "orderable": false},

        {"data": function (row) {
            if(row.driver){
                return row.driver.full_name;
            }else{
                return '';
            }

        }, "orderable": false},

        {"data": function (row, type, val, meta) {
                if (row.status == 'new') {
                    return "<span class='label label-sm label-default'> <i class='fa fa-angellist'></i> جديد</span>";
                } else if (row.status == 'accept') {
                    return "<span class='label label-sm label-primary'> <i class='fa fa-houzz'></i> تم القبول</span>";
                } else if (row.status == 'in_car') {
                    return "<a class='label label-sm label-success' onclick='ActiveUser(" + row.id + ");'> <i class='fa fa-lastfm'></i> فى السيارة</a>";
                }else if (row.status == 'in_landery') {
                    return "<a class='label label-sm label-info' onclick='deActiveUser(" + row.id + ");'> <i class='fa fa-google-wallet'></i> فى المغسلة</a>";
                }else if (row.status == 'car_finish') {
                    return "<span class='label label-sm label-warning' > <i class='fa fa-ioxhost'></i> فى الطريق</span>";
                }else if (row.status == 'complete') {
                    return "<span class='label label-sm label-danger' > <i class='fa fa-skyatlas'></i> منتهى</span>";
                } else {
                    return '';
                }
            }, "orderable": false
        },
        {"data": "total_cost", "orderable": false},
        {"data": function (row, type, val, meta) {
            return "<a title='' class='btn btn-sm green' target='_blank' href='http://maps.google.com/?q=" + row.lat + "," + row.lng + "'><i class='fa fa-map-o'></i> الخريطة</a>";
        }, "orderable": false
        },
        {"data": "orders_count", "orderable": false},
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                var html = '<div style="width: 100%;text-align: center;">';
                users_info[row.id] = row;
                html += '<a title="' + js_lang.edit_record + '" class="btn btn-sm blue"  onclick="editRow(' + row.id + ')"><i class="fa fa-edit"></i> التفاصيل</a>';
                status_info[row.id] = row;
                html += '<a title="' + js_lang.delete_record + '" class="btn btn-sm red" onclick="statusRow(' + row.id + ')"><i class="fa fa-trash-o"></i> الحالات</a>';
                html += '</div>';
                return html;
            } ,"width": "20%"
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
function ResetActiveUser(id) {
    bootbox.confirm(model_js_lang.reset_confirm, function (result) {
        if (result) {
            var my_data = {};
            my_data.ids = [id];
            my_data._token = token_code;
            $.post(urls.reset_mobile_url, my_data, function (data) {
                if (data.status == true) {
                    Command: toastr['success'](data.message);
                } else {
                    Command: toastr['error'](data.message);
                }
                dataTable.ajax.reload();
            }, 'json');
        }
    });
    return false;
}
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
                dataTable.ajax.reload();
            }, 'json');
        }
    });
    return false;
}

function deActiveUser(id) {
    bootbox.confirm('هل تريد تغيير وضع الطلب من فى المغسلة الى السائق لتوصيلة للعميل ؟', function (result) {
        if (result) {
            var my_data = {};
            my_data.ids = id;
            my_data._token = token_code;
            $.post(urls.deactivate_url, my_data, function (data) {
                if (data.status == true) {
                    Command: toastr['success'](data.message);
                } else {
                    Command: toastr['error'](data.message);
                }
                dataTable.ajax.reload();
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
    bootbox.confirm('هل تريد تغيير وضع الطلب من فى السيارة الى فى المغسلة ؟', function (result) {
        if (result) {
            var my_data = {};
            my_data.ids = id;
            my_data._token = token_code;
            $.post(urls.activate_url, my_data, function (data) {
                if (data.status == true) {
                    Command: toastr['success'](data.message);
                } else {
                    Command: toastr['error'](data.message);
                }
                dataTable.ajax.reload();
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
                    dataTable.ajax.reload();
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
                    dataTable.ajax.reload();
                }, 'json');
            }
        });
    } else {
        Command: toastr['error'](js_lang.box_message);
        return false;
    }
});
/**
 * block users device srial
 */

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
    dataTable.ajax.reload();
    return false;
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
        full_name: {
            required: true,
            minlength: 4
        },
        email: {
            required: true,
            email: true
        },
        car: {
            required: true,
        },
        car_number: {
            required: true,
        },
        mobile_number: {
            required: true,
        },

        user_name: {
            required: true,
            regex: /^[a-z][a-z0-9_.-]{4,25}$/
        },
        password: {
            required: function (element) {
                if ($('input[name=id]').val() == '') {
                    return true;
                } else {
                    return false;
                }
            },
            regex: /(?=^.{6,}$)(?=.*\d)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/
        },
        repassword: {
            required: function (element) {
                if ($('input[name=id]').val() == '') {
                    return true;
                } else {
                    return false;
                }
            },
            equalTo: "#password"
        }
    },
    messages: {// custom messages for radio buttons and checkboxes
        'full_name': {
            required: js_lang.field_required,
            minlength: model_js_lang.name_minlength
        },
        'email': {
            required: js_lang.field_required,
            email: model_js_lang.enter_valid_email
        },
        'car': {
            required: js_lang.field_required,
        }
        ,
        'car_number': {
            required: js_lang.field_required,
        },
        'mobile_number': {
            required: js_lang.field_required,
        },
        'user_name': {
            required: js_lang.field_required,
            regex: model_js_lang.user_username_invalid
        },
        'password': {
            required: js_lang.field_required,
            regex: model_js_lang.password_valid_help
        },
        'repassword': {
            required: js_lang.field_required,
            equalTo: model_js_lang.repassword_equals_password
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
            data: $(form).serialize(),
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data[0] == true) {
                    $(form)[0].reset();
                    $('#admins-form-modal').modal('hide');
                    dataTable.ajax.reload();
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

function editRow(obj)
{
    try {
        dialog_is.title = 'عرض تفاصيل الطلب';
        dialog_is.message = '<table class="table table-bordered" style="width: 100%;">';
        dialog_is.message += '<tr>';
        dialog_is.message += '<th>المنتج</th>';
        dialog_is.message += '<th>العدد</th>';
        dialog_is.message += '<th>السعر</th>';
        dialog_is.message += '</tr>';
        $.each(users_info[obj].orders, function (key, val) {
            dialog_is.message += '<tr>';
            dialog_is.message += '<td>'+val.clothe.title+'</td>';
            dialog_is.message += '<td>'+val.number+'</td>';
            dialog_is.message += '<td>'+val.price+'</td>';
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

function statusRow(obj)
{
    try {
        dialog_is.title = 'عرض مراحل قبول ومتابعة الطلب';
        dialog_is.message = '<table class="table table-bordered" style="width: 100%;">';
        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">الحالة</th>';
        dialog_is.message += '<th class="text-info">تاريخ التنفيذ</th>';
        dialog_is.message += '<th class="text-info">مدة التنفيذ</th>';
        dialog_is.message += '</tr>';
        $.each(users_info[obj].orderStatus, function (key, val) {
            var mes='';
            dialog_is.message += '<tr>';
            if (val.status == "new") {
                mes = 'جديد';
            }else if(val.status == "accept"){
                mes = 'تم قبولة';
            }else if(val.status == "in_car"){
                mes = 'فى السيارة';
            }else if(val.status == "in_landery"){
                mes = 'فى المغسلة';
            }else if(val.status == "car_finish"){
                mes = 'فى الطريق';
            }else if(val.status == "complete"){
                mes = 'منتهى';
            }
            dialog_is.message += '<td class="text-danger">'+mes+'</td>';
            dialog_is.message += '<td>'+val.request_time+'</td>';
            dialog_is.message += '<td>'+val.duration+'</td>';
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