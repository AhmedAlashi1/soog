<!-- Back-to-top -->
<a href="#top" id="back-to-top"><i class="las la-angle-double-up"></i></a>
<!-- JQuery min js -->
<script src="{{URL::asset('assets/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap Bundle js -->
<script src="{{URL::asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Ionicons js -->
<script src="{{URL::asset('assets/plugins/ionicons/ionicons.js')}}"></script>
<!-- Moment js -->
<script src="{{URL::asset('assets/plugins/moment/moment.js')}}"></script>

<!-- Rating js-->
<script src="{{URL::asset('assets/plugins/rating/jquery.rating-stars.js')}}"></script>
<script src="{{URL::asset('assets/plugins/rating/jquery.barrating.js')}}"></script>

<!--Internal  Perfect-scrollbar js -->
<script src="{{URL::asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/perfect-scrollbar/p-scroll.js')}}"></script>
<!--Internal Sparkline js -->
<script src="{{URL::asset('assets/plugins/jquery-sparkline/jquery.sparkline.min.js')}}"></script>
<!-- Custom Scroll bar Js-->
<script src="{{URL::asset('assets/plugins/mscrollbar/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<!-- right-sidebar js -->
<script src="{{URL::asset('assets/plugins/sidebar/sidebar-rtl.js')}}"></script>
<script src="{{URL::asset('assets/plugins/sidebar/sidebar-custom.js')}}"></script>
<!-- Eva-icons js -->
<script src="{{URL::asset('assets/js/eva-icons.min.js')}}"></script>
<!-- Sticky js -->
<script src="{{URL::asset('assets/js/sticky.js')}}"></script>
<!-- custom js -->
<script src="{{URL::asset('assets/js/custom.js')}}"></script><!-- Left-menu js-->
<script src="{{URL::asset('assets/plugins/side-menu/sidemenu.js')}}"></script>
<script src="{{ asset('assets/js/t.js')}}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
    var notificationOptions = {
        icon : "{{asset('assets/img/1.png')}}",
    };
    // Let's check if the browser supports notifications
    if (!("Notification" in window)) {
        console.log("This browser does not support desktop notification");
    }
    else if (Notification.permission === "granted") {
        // var notification = new Notification("Hi there!");
        console.log('Chrome Notification Works');
    }
    // Otherwise, we need to ask the user for permission
    else if (Notification.permission !== "denied") {
        Notification.requestPermission().then(function (permission) {
            // If the user accepts, let's create a notification
            if (permission === "granted") {
                var notification = new Notification("Hi there!",notificationOptions);
                //console.log('Chrome Notification Works');
            }
        });
    }
    {{-- var audio = new Audio("https://img.pikbest.com/houzi/audio/original/2020/10/16/93df8dfc1500e6f11905b609eb07c236.mp3");--}}
     var audio = new Audio("https://admin.halaw-kakaw.com/assets/voice/rna.mp3");
     {{--var audio = new Audio({{url('http://admin.halaw-kakaw.com/assets/voice/rna.mp3')}});--}}
    var pusher = new Pusher('{{env('PUSHER_KEY')}}', {
        cluster: 'mt1'
    });
    // console.log(pusher);
    var channel = pusher.subscribe('orders');
    channel.bind('notifications', function(data) {
        toastr.success(data.message);
        audio.play();
        let currentOptions = notificationOptions;
        currentOptions.body = 'The Cart '+ '# '+ data.id + ' Has been submitted.';
        new Notification("New Cart Submitted!",currentOptions);
        toastr.success(data.id + 'طلب جديد رقم الطلب هو ');
         $('.toast').css('background-color' , 'black');
    });

    @foreach (App\Models\Order::where('status' ,'new')->get()->take(5) as $cart)
        toastr.options.timeOut = 5000;
        toastr.options.closeButton = true;
        toastr.options.positionClass = "toast-top-left";
    // toastr.options.onclick = function () {
    //     var ids = [];
    //     ids.push({{$cart->id}});
    //     var my_data = {};
    //     my_data.ids = ids;
    //     $.post('https://halaw-kakaw.com/admin/orders/activateAll2', my_data, function (data) {
    //         if (data.status == true) {
    //             window.open('https://halaw-kakaw.com/admin/orders/print/16951', "popupWindow", "width=600,height=600,scrollbars=yes");
    //             try {
    //                 dataTable.ajax.reload(null, false);
    //             } catch (e) {

    //             }
    //         }

    //     }, 'json');
    // }
    toastr.success(' الطلب رقم  # {{ $cart->id }} يحتاج الى الموافقه');
    $('.toast').css('background-color' , 'black');
    @endforeach
</script>
@yield('js')

