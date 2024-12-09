var users_info = {};
var form1 = $('#users_form');
$('#create_btn').click(function () {
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
        {"data": "name", "orderable": true},
        {"data": "created_at", "orderable": true},
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                users_info[row.id] = row;
                var html = '<div style="width: 100%;text-align: center;">';
                //if (permissions.edit) {
                    html += '<a title="' + js_lang.edit_record + '" class="btn btn-sm blue" onclick="editRow(' + row.id + ')"><i class="fa fa-edit"></i> ' + js_lang.edit_record + '</a>';
                //}
                //if (permissions.delete) {
                    //html += '<a title="' + js_lang.delete_record + '" class="btn btn-sm red" onclick="deleteRow(' + row.id + ')"><i class="fa fa-trash-o"></i> ' + js_lang.delete_record + '</a>';
                //}
                html += '</div>';
                return html;
            } ,"width": "30%"
        }
    ]
});
/**
 *
 * @param id
 */
function deleteRow(id) {
    bootbox.confirm(js_lang.confirm_delete, function (result) {
        if (result) {
            var my_data = {};
            my_data.id = id;
            //my_data._token = token_code;
            $.post(urls.delete_url, my_data, function (data) {
                if (data[0]) {
                    showNotify('success', js_lang[data[1]]);
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

/**
 *
 * @param id
 */
function editRow(id)
{
    var data = users_info[id];
    $(form1).find('input[name=id]').val(data.id);
    $(form1).find('input[name=name]').val(data.name);
    $('.permission_check').bootstrapSwitch('state', false);
    if (data.permissions.length > 0) {
        $.each(data.permissions, function (i, v) {
            $('.permission_' + v.id).bootstrapSwitch('state', true);
        });
    }
    $('#admins-form-modal').modal('show');
}
/**
 *
 * @param id
 * @param type
 */

$("#data_delete_btn").click(function () {
    if ($('input[name="ids[]"]:checked').length) {
        bootbox.confirm(js_lang.confirm_delete, function (result) {
            if (result) {
                var ids = [];
                $.each($('table#table_view tbody input[type="checkbox"]:checked'), function (x, y) {
                    ids.push($(y).val());
                });
                var my_data = {};
                my_data.ids = ids;
                $.post(urls.delete_multi_url, my_data, function (data) {
                    dataTable.ajax.reload();
                    bootbox.hideAll();
                    if (data[0]) {
                        showNotify('success', js_lang[data[1]]);
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
var error1 = $('.alert-danger', form1);
form1.validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "",
    rules: {
        name: {
            required: true,
        }
    },
    messages: {// custom messages for radio buttons and checkboxes
        'name': {
            required: js_lang.required
        }
    },
    invalidHandler: function (event, validator) { //display error alert on form submit
        error1.show();
        $('#admins-form-modal').animate({scrollTop:0}, 600);
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
        $('#add_admins_btn').button('loading');
        $.ajax({
            url: url,
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            error: function (response) {
                $('#add_admins_btn').button('reset');
                try {
                    if(response.status == 422) {
                        var message = '<ul>';
                        $.each(response.responseJSON.errors, function (key, err) {
                            $.each(err, function (k, val) {
                                message += '<li>' + val + '</li>';
                            });
                        });
                        message += '</ul>';
                        showNotify('error', message);
                    } else {
                        showNotify('error', js_lang.connection_error);
                    }
                } catch(error) {
                    showNotify('error', js_lang.connection_error);
                }
            },
            success: function (data) {
                $('#add_admins_btn').button('reset');
                if (data[0] == true) {
                    $(form)[0].reset();
                    $('#admins-form-modal').modal('hide');
                    showNotify('success', model_js_lang[data[1]]);
                    dataTable.ajax.reload();
                } else {
                    $('#add_admins_btn').button('reset');
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

$("#check_all_permissions").on('switchChange.bootstrapSwitch', function (event, state) {
    $('.permission_row').bootstrapSwitch('state', state);
    $('.permission_check').bootstrapSwitch('state', state);
});

$('.permission_row').on('switchChange.bootstrapSwitch', function (event, state) {
    $(this).closest('tr').find('.permission_check').bootstrapSwitch('state', state);
});
