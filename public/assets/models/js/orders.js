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
    'fnDrawCallback': function () {
        $('#table_view').show();
    },
    "serverSide": true,
    "ajax": {
        type: "POST",
        url: urls.get_data_url, // json datasource
        data: function (d) {
            return $("#full_users_search_form").serialize() + '&' + $.param(d)
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
    "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        $('#print_total').text(aData["total"]);
        if (aData["comment"]) {
            $(nRow).addClass('disactivated-user');
        }
    },
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
        {
            "data": function (row) {
                if (row.user) {
                    return row.user.first_name;
                } else {
                    return '';
                }

            }, "orderable": false
        },

        {
            "data": function (row, type, val, meta) {
                if (row.status == 'new') {
                    return "<a class='label label-sm label-default' > <i class='fa fa-angellist'></i> تم قبول الطلب</a>";
                } else if (row.status == 'pay_pending') {
                    return "<a class='label label-sm label-primary' > <i class='fa fa-houzz'></i> جاري تجهيز الطلب</a>";
                } else if (row.status == 'shipping') {
                    return "<a class='label label-sm label-success' > <i class='fa fa-lastfm'></i> تم الغاء الطلب</a>";
                } else if (row.status == 'shipping_complete') {
                    return "<a class='label label-sm label-info'> <i class='fa fa-google-wallet'></i> تم الشحن</a>";
                } else if (row.status == 'complete') {
                    return "<span class='label label-sm label-danger' > <i class='fa fa-skyatlas'></i> تم تاكيد الاستلام</span>";
                } else {
                    return '';
                }
            }, "orderable": false
        },
        {"data": "total_cost", "orderable": false},
        {
            "data": function (row) {
                if (row.payment) {
                    var html = '<div style="width: 100%;text-align: center;">';
                    html += row.payment.title_ar;
                    html += '<br>';
                    if (row.payment.id == 4 || row.payment.id == 3) {
                        if (row.payment_status == 1) {
                            html += '<div class="font-blue">(تم الدفع)</div>';
                        } else if (row.payment_status == 0) {
                            html += '<div class="font-red">(فشل الدفع)</div>';
                        } else {
                            html += '<div class="font-green">(معلق للدفع)</div>';
                        }
                    }
                    html += '</div>';
                    return html
                } else {
                    return '';
                }

            }
        },

        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                var html = '<div style="width: 100%;text-align: center;">';
                users_info[row.id] = row;
                html += '<a title="ملاحظات الاداره" class="btn btn-sm green"  onclick="moveOrder(' + row.id + ')"><i class="fa fa-user"></i> ملاحظات</a>';
                html += '<a title="' + js_lang.edit_record + '" class="btn btn-sm blue"  onclick="detailsShow(' + row.id + ')"><i class="fa fa-edit"></i> التفاصيل</a>';
                html += '<a title="' + js_lang.edit_record + '" class="btn btn-sm purple"  onclick="editRow(' + row.id + ')"><i class="fa fa-edit"></i> المنتجات</a>';
                status_info[row.id] = row;
                html += '<a title="الحالات" class="btn btn-sm green" onclick="statusRow(' + row.id + ')"><i class="fa fa-bars"></i> </a>';
                html += '<a title="' + js_lang.delete_record + '" class="btn btn-sm red" onclick="deleteRow(' + row.id + ')"><i class="fa fa-trash-o"></i> </a>';
                html += '</div>';
                return html;
            }, "width": "40%"
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
                dataTable.ajax.reload(null, false);
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
                dataTable.ajax.reload(null, false);
            }, 'json');
        }
    });
    return false;
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

function deActiveUser(id) {
    bootbox.confirm('هل تريد تغيير وضع الطلب من تم الشحن الى تم تاكيد الاستلام ؟', function (result) {
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
                dataTable.ajax.reload(null, false);
            }, 'json');
        }
    });
    return false;
}

function deActiveUser2(id) {
    bootbox.confirm('هل تريد تغيير وضع الطلب من فى معلق الى معلق للدفع ؟', function (result) {
        if (result) {
            var my_data = {};
            my_data.ids = id;
            my_data._token = token_code;
            $.post(urls.deactivate_url2, my_data, function (data) {
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

function deActiveUser3(id) {
    bootbox.confirm('هل تريد تغيير وضع الطلب من فى معلق للدفع الى معلق للشحن ؟', function (result) {
        if (result) {
            var my_data = {};
            my_data.ids = id;
            my_data._token = token_code;
            $.post(urls.deactivate_url3, my_data, function (data) {
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
    bootbox.confirm('هل تريد تغيير وضع الطلب من فى معلق للشحن الى تم الشحن ؟', function (result) {
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
        bootbox.confirm('هل انت متاكد من  تحويل حالة الطلبات الى تم قبول الطلبات ؟', function (result) {
            if (result) {
                var ids = [];
                $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
                    ids.push($(y).val());
                });
                var my_data = {};
                my_data.ids = ids;
                my_data._token = token_code;
                $.post(urls.activate_url_all, my_data, function (data) {
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
$("#app_users_activate_btn2").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
        bootbox.confirm('هل انت متاكد من تحويل حالة الطلبات الى تم تجهيز الطلبات ؟', function (result) {
            if (result) {
                var ids = [];
                $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
                    ids.push($(y).val());
                });
                var my_data = {};
                my_data.ids = ids;
                my_data._token = token_code;
                $.post(urls.activate_url_all2, my_data, function (data) {
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
$("#app_users_activate_btn3").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
        bootbox.confirm('هل انت متاكد من تحويل  حالة الطلبات الى تم الغاء الطلبات   ؟', function (result) {
            if (result) {
                var ids = [];
                $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
                    ids.push($(y).val());
                });
                var my_data = {};
                my_data.ids = ids;
                my_data._token = token_code;
                $.post(urls.activate_url_all3, my_data, function (data) {
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
$("#app_users_activate_btn4").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
        bootbox.confirm('هل انت متاكد من تحويل حالة الطلبات الى تم الشحن ؟', function (result) {
            if (result) {
                var ids = [];
                $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
                    ids.push($(y).val());
                });
                var my_data = {};
                my_data.ids = ids;
                my_data._token = token_code;
                $.post(urls.activate_url_all4, my_data, function (data) {
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
$("#app_users_activate_btn5").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
        bootbox.confirm('هل انت متاكد من تحويل حالة الطلبات الى تم تاكيد الاستلام ؟', function (result) {
            if (result) {
                var ids = [];
                $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
                    ids.push($(y).val());
                });
                var my_data = {};
                my_data.ids = ids;
                my_data._token = token_code;
                $.post(urls.activate_url_all5, my_data, function (data) {
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
/**
 * block users device srial
 */
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
        maxDate: moment(),
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
        driver_id: {
            required: true
        },
        comment: {
            required: true
        }
    },
    messages: {// custom messages for radio buttons and checkboxes
        'driver_id': {
            required: js_lang.field_required
        },
        'comment': {
            required: js_lang.field_required
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
        var url = urls.move_url;
        $.ajax({
            url: url,
            data: $(form).serialize(),
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data[0] == true) {
                    $(form)[0].reset();
                    $('#alerts-form-modal').modal('hide');
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

function editRow(obj) {
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
            dialog_is.message += '<td>' + val.clothe.title_ar + '</td>';
            dialog_is.message += '<td>' + val.number + '</td>';
            dialog_is.message += '<td>' + val.price + '</td>';
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


function detailsShow(obj) {
    try {
        dialog_is.title = 'عرض تفاصيل الطلب';
        dialog_is.message = '<table class="table table-bordered" style="width: 100%;">';
        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">عدد طلبات العميل</th>';
        dialog_is.message += '<th>' + users_info[obj].charges_count + '</th>';
        dialog_is.message += '</tr>';

        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">اجمالى فواتير العميل</th>';
        dialog_is.message += '<th>' + users_info[obj].discount_total + '</th>';
        dialog_is.message += '</tr>';
        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">رقم الطلب </th>';
        dialog_is.message += '<th>' + users_info[obj].id + '</th>';
        dialog_is.message += '</tr>';

        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">عدد القطع</th>';
        dialog_is.message += '<th>' + users_info[obj].orders_count + '</th>';
        dialog_is.message += '</tr>';

        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">تاريخ الطلب </th>';
        dialog_is.message += '<th>' + users_info[obj].created_at + '</th>';
        dialog_is.message += '</tr>';
        if (users_info[obj].payment) {
            dialog_is.message += '<tr>';
            dialog_is.message += '<th class="text-info">طريقة الدفع </th>';
            dialog_is.message += '<th>' + users_info[obj].payment.title_ar + '</th>';
            dialog_is.message += '</tr>';
        }
        if (users_info[obj].address.city_data) {
            dialog_is.message += '<tr>';
            dialog_is.message += '<th class="text-info">المحافظة </th>';
            dialog_is.message += '<th>' + users_info[obj].address.city_data.title_ar + '</th>';
            dialog_is.message += '</tr>';
        }
        if (users_info[obj].address.region_data) {
            dialog_is.message += '<tr>';
            dialog_is.message += '<th class="text-info">المنطقة </th>';
            dialog_is.message += '<th>' + users_info[obj].address.region_data.title_ar + '</th>';
            dialog_is.message += '</tr>';
        }
        if (users_info[obj].deliveryTypeTitle) {
            dialog_is.message += '<tr>';
            dialog_is.message += '<th class="text-info">وقت التوصيل </th>';
            dialog_is.message += '<th>'+ users_info[obj].deliveryTypeTitle.title_ar+'</th>';
            dialog_is.message += '</tr>';
        }
        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">يوم التوصيل </th>';
        dialog_is.message += '<th>' + users_info[obj].delivery_date + '</th>';
        dialog_is.message += '</tr>';
        // if (users_info[obj].time) {
        //     dialog_is.message += '<tr>';
        //     dialog_is.message += '<th class="text-info">وقت التوصيل </th>';
        //     dialog_is.message += '<th>' + users_info[obj].time.title_ar + '</th>';
        //     dialog_is.message += '</tr>';
        // }

        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">وقت التوصيل </th>';
        dialog_is.message += '<th>' + users_info[obj].time_id + '</th>';
        dialog_is.message += '</tr>';
        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">رقم العميل </th>';
        dialog_is.message += '<th>' + users_info[obj].user.id + '</th>';
        dialog_is.message += '</tr>';

        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">الاسم </th>';
        dialog_is.message += '<th>' + users_info[obj].user.first_name + '</th>';
        dialog_is.message += '</tr>';

        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">رقم الجوال </th>';
        dialog_is.message += '<th>' + users_info[obj].user.mobile_number + '</th>';
        dialog_is.message += '</tr>';

        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">الاجمالى   </th>';
        dialog_is.message += '<th>' + users_info[obj].total_cost + '</th>';
        dialog_is.message += '</tr>';

        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">نوع الجهاز   </th>';
        if (users_info[obj].user_agent) {
            dialog_is.message += '<th>' + users_info[obj].user_agent + '</th>';
        } else {
            dialog_is.message += '<th> android</th>';
        }
        dialog_is.message += '</tr>';

        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info"> ملاحظات  </th>';
        dialog_is.message += '<th>' + users_info[obj].notes + '</th>';
        dialog_is.message += '</tr>';

        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">طباعة </th>';
        dialog_is.message += '<th><a  class="btn btn-sm purple" target="_blank" href="' + urls.print + '/' + users_info[obj].id + '"><i class="fa fa-print"></i> طباعة</a></th>';
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

function statusRow(obj) {
    try {
        dialog_is.title = 'عرض مراحل قبول ومتابعة الطلب';

        dialog_is.message = '<table class="table table-bordered" style="width: 100%;">';
        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">#</th>';
        dialog_is.message += '<th>' + users_info[obj].id + '</th>';
        dialog_is.message += '</tr>';

        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">التاريخ</th>';
        dialog_is.message += '<th>' + users_info[obj].created_at + '</th>';
        dialog_is.message += '</tr>';
        dialog_is.message += '</table>';

        dialog_is.message = '<table class="table table-bordered" style="width: 100%;">';
        dialog_is.message += '<tr>';
        dialog_is.message += '<th class="text-info">الحالة</th>';
        dialog_is.message += '<th class="text-info">تاريخ التنفيذ</th>';
        dialog_is.message += '<th class="text-info">مدة التنفيذ</th>';
        dialog_is.message += '</tr>';
        $.each(users_info[obj].orderStatus, function (key, val) {
            var mes = '';
            dialog_is.message += '<tr>';
            if (val.status == "new") {
                mes = 'معلق';
            } else if (val.status == "pay_pending") {
                mes = 'معلق للدفع';
            } else if (val.status == "shipping") {
                mes = 'معلق للشحن';
            } else if (val.status == "shipping_complete") {
                mes = 'تم الشحن';
            } else if (val.status == "complete") {
                mes = 'تم تاكيد الاستلام';
            }
            dialog_is.message += '<td class="text-danger">' + mes + '</td>';
            dialog_is.message += '<td>' + val.request_time + '</td>';
            dialog_is.message += '<td>' + val.duration + '</td>';
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

function moveOrder(id) {
    $(form1).find('input[name=order_id]').val(id);
    $(form1).find('textarea[name=comment]').val(users_info[id].comment);

    $('#alerts-form-modal').modal('show');
}

$('#export_search').click(function () {
    bootbox.confirm('هل انت متاكد من تحميل النتائج ؟', function (result) {
        if (result) {
            var my_data = $("#full_users_search_form").serialize();
            $.post(urls.export_excel, my_data, function (data) {
                if (data.status == true) {
                    window.open(storge_path + '/' + data.file);
                } else {
                    Command: toastr['error'](data.message);
                }
            }, 'json');
        }
    });
    return false;
});

