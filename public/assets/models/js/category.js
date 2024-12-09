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
    "rowReorder": {
        "update": false,
        //"selector": 'tr'
    },
    "ajax": {
        url :urls.get_data_url+'?parent_id='+parent_id ,// json datasource
    },
    "columns": [
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                return '<div style="width: 100%;text-align: center;"><span aria-hidden="true" class="icon-layers"></span></div>';
            },  "width": "1%"
        },
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                return '<input type="checkbox" name="ids[]" class="checkboxes" value="' + row.id + '" title="" />';
            },  "width": "5%"
        },
        {"data": "parent", "orderable": false},
        {"data": function (row, type, val, meta) {
                return '<a href="'+urls.index+'?parent_id='+row.id+'">'+row.title_ar+'</a>';

            }, "orderable": false
        },
        {"orderable": false,"data": function (row, type, val, meta) {
                return '<img src="'+assets_folder+'/tmp/'+row.image+'" style="width: 100px;height: 100px;" alt="image" class="img-responsive">';
            }},
        {"data": function (row, type, val, meta) {
                if (row.status == 1) {
                    return '<a onclick="updateStatus('+ row.id +',0)"><span class="badge badge-success"><i class="icon-check"></i></span></a>';
                } else if (row.status == 0) {
                    return '<a onclick="updateStatus('+ row.id +',1)"><span class="badge badge-danger"> <i class="icon-close"></i> </span></a>';
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
                html += '<a href="'+urls.orders+'?cat_id='+row.id+'" target="_blank" class="btn btn-sm purple" ><i class="fa fa-bars"></i> المنتجات</a>';
                html += '<a title="' + js_lang.edit_record + '" class="btn btn-sm blue"  onclick="editRow(' + row.id + ')"><i class="fa fa-edit"></i> ' + js_lang.edit_record + '</a>';
                html += '<a title="' + js_lang.delete_record + '" class="btn btn-sm red" onclick="deleteRow(' + row.id + ')"><i class="fa fa-trash-o"></i>  ' + js_lang.delete_record + '</a>';
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
dataTable.on('row-reorder', function ( e, diff, edit ) {
    var obj = []
    var pos = []
    for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
        var rowData = dataTable.row( diff[i].node ).data();
        obj.push(rowData.id)
        pos.push(diff[i].newPosition)
    }
    var params = jQuery.param(obj)
    dataTable.ajax.url(urls.get_data_url+"?ids="+obj+"&pos="+pos).load();
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
        title: {
            required: true,
            minlength: 4
        },delivery: {
            required: true
        },
    },
    messages: {// custom messages for radio buttons and checkboxes
        'title': {
            required: js_lang.field_required,
            minlength: model_js_lang.name_minlength
        },'delivery': {
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
                    $('#parent_id').html('<option value="0">قسم رئيسي</option>'+data[2]);
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
                $('#main_cat_id').show();
                $('#parent_id').hide();
                $(form1).find('input[name=id]').val(data[2].id);
                $(form1).find('input[name=title_ar]').val(data[2].title_ar);
                $(form1).find('input[name=title_en]').val(data[2].title_en);
                $(form1).find('select[name=parent_id2]').val(data[2].parent_id);
                $('#admins-form-modal').modal('show');
                $('#more_images').remove();
                if (data[2].home == 1) {
                    $('.special_check').bootstrapSwitch('state', true);
                }else{
                    $('.special_check').bootstrapSwitch('state', false);
                }
            } else {
                showNotify('error', data.message);
            }
        }
    });
}

/**
 *
 * @param obj
 */
function viewDetails(obj) {
    try {
        dialog_is.title = 'عرض صور العقار';
        dialog_is.message = '<table class="table table-bordered" style="width: 100%;">';
        $.each(users_info[obj].images, function (key, val) {
            dialog_is.message += '<tr><th><a title="' + js_lang.delete_record + '" id="delet_'+val.id +'" class="btn btn-sm red" onclick="deleteRowImage(' + val.id + ')"><i class="fa fa-trash-o"></i>  ' + js_lang.delete_record + '</a></th><td><div class="profile-userpic"><img src="'+assets_folder+'/tmp/'+val.image+'" width="100" height="100" alt=""></td></tr>';
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

function deleteRowImage(id) {
    bootbox.confirm(js_lang.conferm_delete, function (result) {
        if (result) {
            var my_data = {};
            my_data.id = id;
            my_data._token = token_code;
            $.post(urls.delete_url_image, my_data, function (data) {
                if (data[0]) {
                    showNotify('success', model_js_lang[data[1]]);
                } else {
                    if (js_lang[data[1]]) {
                        showNotify('error', js_lang[data[1]]);
                    } else {
                        showNotify('error', model_js_lang[data[1]]);
                    }
                }
                $('#delet_'+id).parent().parent().remove();
            }, 'json');
        }
    });
}

function updateStatus(id,type) {

            var my_data = {};
            my_data.id = id;
            if(type==0){
                my_data.status=0;
            }else{
                my_data.status=1;
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