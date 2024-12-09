String.prototype.capitalize = function () {
    return this.charAt(0).toUpperCase() + this.slice(1);
}
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ajaxStart(function () {
    App.startPageLoading();
});
$(document).ajaxStop(function () {
    App.stopPageLoading();
    Layout.fixContentHeight(); // fix content height
    App.initAjax(); // initialize core stuff
});
jQuery('#table_view #table_checkall').change(function () {
    var set = $(this).attr("data-set");
    if ($(this).is(":checked")) {
        $(set).prop("checked", true);
        $(set).parent().addClass("checked");
    } else {
        $(set).prop("checked", false);
        $(set).parent().removeClass("checked");
    }
});
function showNotify(type, message) {
    $(".toast").hide();
    toastr.options.newestOnTop = false;
    toastr.options.preventDuplicates = true;
    toastr.options.positionClass = "toast-top-full-width";
    switch (type) {
        case "error":
            toastr.error(message);
            break;
        case "success":
            toastr.success(message);
            break;
        case "info":
            toastr.info(message);
            break;
    }
}