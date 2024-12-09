$(document).ready(function(){

    const Dir = document.querySelector("html").getAttribute("dir");
    let IsRtl = true;
    if(Dir == "rtl") {
        IsRtl = true;
    }else if(Dir == "ltr"){
        IsRtl = false;
    }
    else {
        IsRtl = false;
    }
    $("#SliderTop").owlCarousel({
        nav:false,
        items:1,
        rtl:IsRtl,
        loop:true,
        autoplay:true,
        autoplayTimeout:5000,
        autoplayHoverPause:true
    })
    $(".SliderProducts").owlCarousel({
        nav:false,
        dots:false,
        rtl:IsRtl,
        margin:10,
        stagePadding: 5,
        loop:true,
        autoplay:true,
        autoplayTimeout:5000,
        autoplayHoverPause:true,
        responsive:{
            0:{
                items:1
            },425:{
                items:2
            },
            550:{
                items:2
            },768:{
                items:3
            },
            1000:{
                items:4
            }
        }
    })
    $("#SliderClassfication").owlCarousel({
        nav:false,
        dots:false,
        rtl:IsRtl,
        margin:10,
        responsive:{
            0:{
                items:2
            },425:{
                items:3
            },
            550:{
                items:4
            },768:{
                items:6
            },
            1000:{
                items:7
            }
        }
    })
    $("#SliderImgProduct").owlCarousel({
        nav:false,
        items:1,
        rtl:IsRtl
    })

    $("#Commercialads").owlCarousel({
        nav:false,
        dots:false,
        rtl:IsRtl,
        margin:15,
        stagePadding: 5,
        loop:true,
        autoplay:true,
        autoplayTimeout:5000,
        autoplayHoverPause:true,
        responsive:{
            0:{
                items:1
            },425:{
                items:1
            },
            550:{
                items:2
            },768:{
                items:2
            },
            1000:{
                items:3
            }
        }
    })

    var ModeBtn = document.getElementById("ChangeMode");
    var ModeMobileBtn = document.getElementById("ChangeModeMobile");

    var IsDarkMode = false;
    ModeBtn.onclick = function() {
        ChangeMode();

    }
    ModeMobileBtn.onclick = function() {
        ChangeMode();

    }
    function ChangeMode() {
        IsDarkMode = !IsDarkMode;
        //var currentTheme = document.getAttribute("data-theme");
        var targetTheme = "light";

        if(IsDarkMode) {
            targetTheme = "dark";
            ModeBtn.innerHTML = '<i class="far fa-sun"></i>';
            ModeMobileBtn.innerHTML = '<i class="far fa-sun"></i>';
        }else {
            ModeBtn.innerHTML = '<i class="far fa-moon"></i>';
            ModeMobileBtn.innerHTML = '<i class="far fa-moon"></i>';
        }
        document.documentElement.setAttribute('data-theme', targetTheme)
        localStorage.setItem('theme', targetTheme);


    }

    var NavMobile = document.getElementById("NavWeb");
    var NavbarToggler = document.getElementById("navbar-toggler");
    var CloseNavBar = document.getElementById("CloseNavBar");
    var IsShow = false;
    CloseNavBar.onclick = function() {
        ToggleNavBar();
    }
    NavbarToggler.onclick = function() {
        ToggleNavBar();
    }
    function ToggleNavBar() {
        IsShow = !IsShow;
        if(IsShow){
            NavMobile.classList.remove("Close");
            NavMobile.classList.add("Open");
        }
        else {
            NavMobile.classList.add("Close");
            NavMobile.classList.remove("Open");
        }
    }


    const currentlocate = location.href; // Return the Current page..
    const menuItem = document.querySelectorAll('.NavBottom .nav-link');
    const menulength = menuItem.length;
    for(let i=0;i<menulength;i++){
        if(menuItem[i].href === currentlocate){
            menuItem[i].classList.add("active")
        }
    }


})
