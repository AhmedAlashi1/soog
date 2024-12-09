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
    bFilter: false,
    bSort: false,
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
            return  $("#limit_users_search_form").serialize() + '&' + $.param(d)
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
    "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        if (aData.mobile_number_blocked == true || aData.ip_address_blocked == true || aData.device_serial_blocked == true) {
            $(nRow).addClass('danger');
        } else {

        }
    },
    "columns": [{
            "orderable": false,
            "data": function (row, type, val, meta) {
                return '';
            }
        }, {
            "orderable": false,
            "data": function (row, type, val, meta) {
                return '<input type="checkbox" name="ids[]" class="checkboxes" value="' + row.id + '" title="" />';
            }
        },
        {"data": function (row) {
                var countryHtml = '';
                if (row.country_code) {
                    countryHtml = '(' + row.country_code + ') <img src="' + assets_folder + '/global/img/flags/' + row.country_code.toLowerCase() + '.png"/>';
                }
                return row.ip_address + countryHtml;
            }, "orderable": false},
        {"data": "google_email", "orderable": false},
        {"data": "device_serial", "orderable": false},
        {"data": "created_at", "orderable": false},
        /*{"data": function (row, type, val, meta) {
         if(row.status=='active') {
         return "<a class='label label-sm label-info' onclick='deActiveUser(" + row.id + ");'> <i class='fa fa-check'></i> " + model_js_lang.activated + "</a>";
         }else if(row.status=='inactive'){
         return "<a class='label label-sm label-danger' onclick='ActiveUser(" + row.id + ");'> <i class='fa fa-user-times'></i> " + model_js_lang.inactive + "</a>";
         }else if(row.status=='pending_activation'){
         return "<a class='label label-sm label-warning' onclick='ActiveUser(" + row.id + ");'> <i class='fa fa-user-times'></i> " + model_js_lang.pending_activation + "</a>";
         }else{
         return '';
         }
         }, "orderable": false
         },*/
        {"data": function (row, type, val, meta) {
                var html = '';
                if (row.device_serial_blocked == true) {
                    html += "<a class='btn btn-sm red' onclick='unblockSerial(" + row.id + ");'> <i class='fa fa-user-times'></i> " + model_js_lang.block_serial + "</a>";
                } else {
                    html += "<a class='btn btn-sm green' onclick='blockSerial(" + row.id + ");'> <i class='fa fa-check'></i> " + model_js_lang.block_serial + "</a>";
                }
                if (row.ip_address_blocked == true) {
                    html += "<a class='btn btn-sm red' onclick='unblockIP(" + row.id + ");'> <i class='fa fa-user-times'></i> " + model_js_lang.block_ip + "</a>";
                } else {
                    html += "<a class='btn btn-sm green' onclick='blockIP(" + row.id + ");'> <i class='fa fa-check'></i> " + model_js_lang.block_ip + "</a>";
                }

                /*if(row.mobile_number_blocked==true){
                 html +=  "<a class='btn btn-sm red' onclick='unblockNumber(" + row.id + ");'> <i class='fa fa-user-times'></i> " + model_js_lang.block_mobile + "</a>";
                 }else{
                 html += "<a class='btn btn-sm green' onclick='blockNumber(" + row.id + ");'> <i class='fa fa-check'></i> " + model_js_lang.block_mobile + "</a>";
                 }*/
                return html;
            }, "orderable": false
        },
        {"data": function (row, type, val, meta) {
                return "<a title='' class='btn btn-sm yellow' target='_blank' href='http://maps.google.com/?q=" + row.location_latitude + "," + row.location_longitude + "'><i class='fa fa-map-o'></i> " + model_js_lang.view_map + "</a>";
            }, "orderable": false
        },
        {"data": "app_id", "orderable": false},
        {"data": "app_version", "orderable": false},
        {"data": "device_model", "orderable": false, },
        {"data": "created_at", "orderable": false, },
        {"data": "city", "orderable": false, },
        {"data": "locality", "orderable": false, },
        {"data": "os", "orderable": false, },
        {"data": "lac", "orderable": false, },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                if (row.tel_roaming == 1) {
                    return '<span class="label label-sm label-info"> ' + model_js_lang.yes + ' </span>';
                } else if (row.tel_roaming == 0) {
                    return '<span class="label label-sm label-danger"> ' + model_js_lang.no + ' </span>';
                } else {
                    return '<span class="label label-sm label-warning"> ' + model_js_lang.none + ' </span>';
                }
            }
        },
        {"data": "mcc", "orderable": false, },
        {"data": "last_activity", "orderable": false, },
        {"data": "mnc", "orderable": false, },
        {"data": "sim_serial", "orderable": false, },
        {"data": "cid", "orderable": false, },
        {"data": function (row, type, val, meta) {
            if(row.home) {
                return "<a title='' class='btn btn-sm yellow' target='_blank' href='http://maps.google.com/?q=" + row.home.latitude + "," + row.home.longitude + "'><i class='fa fa-map-o'></i> " + model_js_lang.view_map + "</a>";
            }else{
                return '';
            }
        }, "orderable": false
        },
        {"data": function (row, type, val, meta) {
            if(row.work) {
                return "<a title='' class='btn btn-sm yellow' target='_blank' href='http://maps.google.com/?q=" + row.work.latitude + "," + row.work.longitude + "'><i class='fa fa-map-o'></i> " + model_js_lang.view_map + "</a>";
            }else{
                return '';
            }
        }, "orderable": false
        },
        {"data": function (row, type, val, meta) {
            return "<a class='btn btn-sm purple' target='_blank' href='"+urls.get_towers+"/"+row.id+"/limited'><i class='fa fa-map-o'></i> عرض الابراج</a>";
        }, "orderable": false
        },
        /*{
         "orderable": false,
         "data": function (row, type, val, meta) {
         users_info[row.id] = row;
         var html = '<a onclick="viewDetails(' + row.id +')" title="' + js_lang.exeptions + '" class="btn btn-sm blue" ><i class="fa fa-bars"></i>'+model_js_lang.details+'</a>';
         return html;
         }
         }*/
    ],
    "lengthMenu": [
        [10, 20, 50, 100],
        [10, 20, 50, 100] // change per page values here

    ],
    "pageLength": 20,
    "order": [[1, 'desc']]
});

/*function viewDetails(obj){
 try{
 dialog_is.title = model_js_lang.details_user;
 dialog_is.message = '<table class="table table-bordered" style="width: 100%;">' +
 '<tr><th style="width: 20%">#</th><td style="width: 30%">'+users_info[obj].id+'</td><th style="width: 20%">fhfghfh</th><td style="width: 30%">'+users_info[obj].id+'</td></tr>';
 dialog_is.message += '<tr><th>fhfghfh</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].google_email+'</td></tr>';
 dialog_is.message += '<tr><th>fhfghh</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].email+'</td></tr>';
 dialog_is.message += '<tr><th>fghgfh</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].mobile_number+'</td></tr>';
 dialog_is.message += '<tr><th>fhfgh</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].first_name+'</td></tr>';
 dialog_is.message += '<tr><th>fhfghfgh</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].last_name+'</td></tr>';
 dialog_is.message += '<tr><th>fhfghfgh</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].type+'</td></tr>';
 dialog_is.message += '<tr><th>fhfghfgh</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].country_code+'</td></tr>';
 dialog_is.message += '<tr><th>fhfghfgh</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].status+'</td></tr>';
 dialog_is.message += '<tr><th>fhfghfgh</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].credits+'</td></tr>';
 dialog_is.message += '<tr><th>Device Serial</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].device_serial+'</td></tr>';
 dialog_is.message += '<tr><th>Device Model</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].device_model+'</td></tr>';
 dialog_is.message += '<tr><th>Ip Address</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].ip_address+'</td></tr>';
 dialog_is.message += '<tr><th>Sim Serial</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].sim_serial+'</td></tr>';
 dialog_is.message += '<tr><th>Sim Country</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].sim_country+'</td></tr>';
 dialog_is.message += '<tr><th>Network Country</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].network_country+'</td></tr>';
 dialog_is.message += '<tr><th>Network Operator</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].network_operator+'</td></tr>';
 dialog_is.message += '<tr><th>Sim Operator</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].sim_operator+'</td></tr>';
 dialog_is.message += '<tr><th>fhfghfgh</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].location_latitude+','+users_info[obj].location_longitude+'</td></tr>';
 dialog_is.message += '<tr><th>fhfghfgh</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].city+'</td></tr>';
 dialog_is.message += '<tr><th>fhfghfgh</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].resend_code_count+'</td></tr>';
 dialog_is.message += '<tr><th>fhfghfgh</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].last_code_request+'</td></tr>';
 dialog_is.message += '<tr><th>fhfghfgh</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].change_mobile_count+'</td></tr>';
 dialog_is.message += '<tr><th>Device token</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].device_token+'</td></tr>';
 dialog_is.message += '<tr><th>Imei</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].imei+'</td></tr>';
 dialog_is.message += '<tr><th>fhfghfgh</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].os+'</td></tr>';
 dialog_is.message += '<tr><th>fhfghfgh</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].created_at+'</td></tr>';
 dialog_is.message += '<tr><th>fhfghfgh</th><td>'+users_info[obj].id+'</td><th>fhfghfh</th><td>'+users_info[obj].updated_at+'</td></tr>';
 dialog_is.message +='</table>';
 delete dialog_is.buttons.success;
 dialog_is.buttons.danger.callback = function () {
 bootbox.hideAll();
 return false;
 };
 bootbox.dialog(dialog_is);
 }catch(e) {
 alert(e.message)
 }
 }*/
/**
 * deactivate users function
 * @param id
 * @returns {boolean}
 */
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
        bootbox.dialog({
            title: js_lang.box_error,
            message: js_lang.box_message
        });
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
        bootbox.dialog({
            title: js_lang.box_error,
            message: js_lang.box_message
        });
        return false;
    }
});
/**
 * block users device srial
 */
$("#app_users_block_serial").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
        bootbox.confirm(model_js_lang.block_serial_confirm_all, function (result) {
            if (result) {
                var ids = [];
                $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
                    ids.push($(y).val());
                });
                var my_data = {};
                my_data.usertype = 2;
                my_data.ids = ids;
                my_data._token = token_code;
                $.post(urls.block_serial_url, my_data, function (data) {
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
        bootbox.dialog({
            title: js_lang.box_error,
            message: js_lang.box_message
        });
        return false;
    }
});
/**
 * block users mobile
 */
$("#app_users_block_mobile").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
        bootbox.confirm(model_js_lang.block_mobile_confirm_all, function (result) {
            if (result) {
                var ids = [];
                $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
                    ids.push($(y).val());
                });
                var my_data = {};
                my_data.ids = ids;
                my_data.usertype = 2;
                my_data._token = token_code;
                $.post(urls.block_mobile_url, my_data, function (data) {
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
        bootbox.dialog({
            title: js_lang.box_error,
            message: js_lang.box_message
        });
        return false;
    }
});
/**
 * app users block ip
 */
$("#app_users_block_ip").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
        bootbox.confirm(model_js_lang.block_ip_confirm_all, function (result) {
            if (result) {
                var ids = [];
                $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
                    ids.push($(y).val());
                });
                var my_data = {};
                my_data.ids = ids;
                my_data.usertype = 2;
                my_data._token = token_code;
                $.post(urls.block_ip_url, my_data, function (data) {
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
        bootbox.dialog({
            title: js_lang.box_error,
            message: js_lang.box_message
        });
        return false;
    }
});
/**
 * app users un block device serial
 */
$("#app_users_unblock_serial").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
        bootbox.confirm(model_js_lang.unblock_serial_confirm_all, function (result) {
            if (result) {
                var ids = [];
                $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
                    ids.push($(y).val());
                });
                var my_data = {};
                my_data.ids = ids;
                my_data.usertype = 2;
                my_data._token = token_code;
                $.post(urls.unblock_serial_url, my_data, function (data) {
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
        bootbox.dialog({
            title: js_lang.box_error,
            message: js_lang.box_message
        });
        return false;
    }
});
/**
 * app uses mobile unblock
 */
$("#app_users_unblock_mobile").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
        bootbox.confirm(model_js_lang.unblock_mobile_confirm_all, function (result) {
            if (result) {
                var ids = [];
                $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
                    ids.push($(y).val());
                });
                var my_data = {};
                my_data.ids = ids;
                my_data._token = token_code;
                $.post(urls.unblock_mobile_url, my_data, function (data) {
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
        bootbox.dialog({
            title: js_lang.box_error,
            message: js_lang.box_message
        });
        return false;
    }
});
/**
 * app users unblock ip
 */
$("#app_users_unblock_ip").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
        bootbox.confirm(model_js_lang.unblock_ip_confirm_all, function (result) {
            if (result) {
                var ids = [];
                $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
                    ids.push($(y).val());
                });
                var my_data = {};
                my_data.ids = ids;
                my_data._token = token_code;
                my_data.usertype = 2;
                $.post(urls.unblock_ip_url, my_data, function (data) {
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
        bootbox.dialog({
            title: js_lang.box_error,
            message: js_lang.box_message
        });
        return false;
    }
});
/**
 *
 * @param id
 * @returns {boolean}
 */
function unblockSerial(id) {
    bootbox.confirm(model_js_lang.unblock_serial_confirm, function (result) {
        if (result) {
            var my_data = {};
            my_data.ids = [id];
            my_data._token = token_code;
            my_data.usertype = 2;
            $.post(urls.unblock_serial_url, my_data, function (data) {
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
 */
function blockSerial(id) {
    bootbox.confirm(model_js_lang.block_serial_confirm, function (result) {
        if (result) {
            var my_data = {};
            my_data.ids = [id];
            my_data._token = token_code;
            my_data.usertype = 2;
            $.post(urls.block_serial_url, my_data, function (data) {
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
 */
function unblockIP(id) {
    bootbox.confirm(model_js_lang.unblock_ip_confirm, function (result) {
        if (result) {
            var my_data = {};
            my_data.ids = [id];
            my_data._token = token_code;
            my_data.usertype = 2;
            $.post(urls.unblock_ip_url, my_data, function (data) {
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
 */
function blockIP(id) {
    bootbox.confirm(model_js_lang.block_ip_confirm, function (result) {
        if (result) {
            var my_data = {};
            my_data.ids = [id];
            my_data._token = token_code;
            my_data.usertype = 2;
            $.post(urls.block_ip_url, my_data, function (data) {
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
 */
function unblockNumber(id) {
    bootbox.confirm(model_js_lang.unblock_mobile_confirm, function (result) {
        if (result) {
            var my_data = {};
            my_data.ids = [id];
            my_data._token = token_code;
            $.post(urls.unblock_mobile_url, my_data, function (data) {
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
 */
function blockNumber(id) {
    bootbox.confirm(model_js_lang.block_mobile_confirm, function (result) {
        if (result) {
            var my_data = {};
            my_data.ids = [id];
            my_data._token = token_code;
            $.post(urls.block_mobile_url, my_data, function (data) {
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
 * for filtration
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
