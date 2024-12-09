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
function init() {
    var map = new GMaps({
        div: '#gmap_marker',
        lat: 28.995350501745158,
        lng: 46.510254988281304,
    });
    var marker = map.addMarker({
        lat: 28.995350501745158,
        lng: 46.510254988281304,
        draggable: true,
        title: 'Your Location Here',

    });
    map.setZoom(6);
    google.maps.event.addListener(marker, 'dragend', function (event) {
        $("#longitude").val(this.getPosition().lng());
        $("#latitude").val(this.getPosition().lat());
    });

}

function initMap(lat,lng) {
    var map = new GMaps({
        div: '#gmap_marker',
        lat: lat,
        lng: lng,
    });
    var marker = map.addMarker({
        lat: lat,
        lng: lng,
        draggable: true,
        title: 'Your Location Here',

    });
    map.setZoom(6);
    google.maps.event.addListener(marker, 'dragend', function (event) {
        $("#longitude").val(this.getPosition().lng());
        $("#latitude").val(this.getPosition().lat());
    });

}
var form1 = $('#alerts_form');
$('#alerts_create_btn').click(function () {
    $(form1)[0].reset();
    $(form1).find('input[name=id]').val('');
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
    "rowReorder": {
        "update": false,
        //"selector": 'tr'
    },
    "ajax": {
        url: urls.get_data_url+'?type='+type, // json datasource
    },
    "columns": [
        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                return '<div style="width: 100%;text-align: center;"><span aria-hidden="true" class="icon-layers"></span></div>';
            },  "width": "1%"
        },
        {"orderable": false,"data": function (row, type, val, meta) {
                return '<img src="'+assets_folder+'/tmp/'+row.image+'" style="width: 100px;height: 100px;" alt="image" class="img-responsive">';
            }},
        {"data": function (row, type, val, meta) {
            return row.cat.title_ar
            }, "orderable": false
        },
        {"data": "title_ar", "orderable": false, },
        {"data": "price", "orderable": false, },
        {"data": "price_after", "orderable": false, },
        {"data": "quntaty", "orderable": false, },
        {"data": "end_date", "orderable": false, },
        // {"data": function (row, type, val, meta) {
        //         if (row.status == 1) {
        //             return '<a onclick="updateStatus('+ row.id +',0)"><span class="badge badge-success"><i class="icon-check"></i></span></a>';
        //         } else if (row.status == 0) {
        //             return '<a onclick="updateStatus('+ row.id +',1)"><span class="badge badge-danger"> <i class="icon-close"></i> </span></a>';
        //         } else {
        //             return '';
        //         }
        //     }, "orderable": false
        // },
        {
            "orderable": false,
            "width": "20%",
            "data": function (row, type, val, meta) {
                users_info[row.id] = row;
                var html = '<div style="width: 100%;text-align: center;">';
                //html += '<a title="'+js_lang.pics+'" class="btn btn-sm yellow" onclick="viewDetails(\'' + row.id + '\')"><i class="fa fa-edit"></i>  الصور</a>';
                //html += '<a title="' + js_lang.edit_record + '" class="btn btn-sm blue"  onclick="editRow(' + row.id + ')"><i class="fa fa-edit"></i></a>';
                html += '<a title="' + js_lang.delete_record + '" class="btn btn-sm red" onclick="deleteRow(' + row.id + ')"><i class="fa fa-trash-o"></i></a>';
                html += '</div>';
                return html;
            }
        }
    ],
    "lengthMenu": [
        [100, 200, 500, 1000],
        [100, 200, 500, 1000] // change per page values here

    ],
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
    dataTable.ajax.url(urls.get_data_url+"?ids="+obj+"&pos="+pos+"&type="+type).load();
});
var error1 = $('.alert-danger', form1);
form1.validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "",
    rules: {
        title_ar: {
            required: true
        },note_ar: {
            required: true
        },title_en: {
            required: true,
        },price: {
            required: true,
        },quntaty: {
            required: true,
        },price_after: {
            required: true,
        },end_date: {
            required: true,
        },note_en: {
            required: true,
        }
    },
    messages: {// custom messages for radio buttons and checkboxes
        'title_ar': {
            required: js_lang.field_required,
        },
        'note_ar': {
            required: js_lang.field_required,
        },
        'title_en': {
            required: js_lang.field_required,
        },
        'price': {
            required: js_lang.field_required,
        },'quntaty': {
            required: js_lang.field_required,
        },'price_after': {
            required: js_lang.field_required,
        },'end_date': {
            required: js_lang.field_required,
        },'note_en': {
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
                    $('#alerts-form-modal').modal('hide');
                    dataTable.ajax.reload(null, false);
                    showNotify('success', model_js_lang[data[1]]);
                    location.reload();

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

function updateStatus(id,type) {
    bootbox.confirm('هل انت متاكد من تغيير حالة المنتج ؟', function (result) {
        if (result) {
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
    });
}

function viewDetails(obj) {
    try {
        dialog_is.title = model_js_lang.dialog_title;
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
    bootbox.confirm('هل انت متاكد من الحذف من القائمة ؟', function (result) {
        if (result) {
            var my_data = {};
            my_data.id = id;
            my_data._token = token_code;
            $.post(urls.delete_url_image, my_data, function (data) {
                if (data[0]) {
                    dataTable.ajax.reload(null, false);
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
                $(form1).find('input[name=price]').val(data[2].price);
                $(form1).find('input[name=cat_id]').val(data[2].cat_id);
                $(form1).find('select[name=type]').val(data[2].type);
                $(form1).find('select[name=user_id]').val(data[2].user_id);
                $(form1).find('input[name=quntaty]').val(data[2].quntaty);
                $(form1).find('input[name=price_after]').val(data[2].price_after);
                $(form1).find('input[name=end_date]').val(data[2].end_date);
                $(form1).find('input[name=lng]').val(data[2].lng);
                $(form1).find('input[name=lat]').val(data[2].lat);
                $(form1).find('textarea[name=note_ar]').val(data[2].note_ar);
                $(form1).find('textarea[name=note_en]').val(data[2].note_en);
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
                location.reload();
            }, 'json');
        }
    });
}

$('#alerts-form-modal').on('shown.bs.modal', function(){
    var lat=$(form1).find('input[name=lat]').val();
    var lng=$(form1).find('input[name=lng]').val();
    if(lat && lng){
        initMap(lat,lng);
    }else{
        init();
    }

});
