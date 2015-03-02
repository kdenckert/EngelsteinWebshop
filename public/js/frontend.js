$(document).ready(function(){


    $('.imageSlider').nivoSlider({
        effect: 'fade',               // Specify sets like: 'fold,fade,sliceDown'
        animSpeed: 500,                 // Slide transition speed
        pauseTime: 8000,                // How long each slide will show
        startSlide: 0,                  // Set starting Slide (0 index)
        directionNav: true,             // Next & Prev navigation
        controlNav: true,               // 1,2,3... navigation
        controlNavThumbs: false,        // Use thumbnails for Control Nav
        pauseOnHover: true,             // Stop animation while hovering
        manualAdvance: false,           // Force manual transitions
        manualCaption: true
    });

    $('.bxslider').bxSlider();

    $('i.fa.fa-plus.open').click(function(){
        $(this).parent().next().next().next().slideToggle(500);
    });

    $('.open_upcoming').click(function(){
        $(this).find('div').slideToggle(500);
    });

    $('a.lightbox').nivoLightbox();

    $('a.aw-cust').click(function(e){
        e.preventDefault();
        $('#aw-cust').fadeToggle(500);
        $('.product_bxslider').bxSlider();
    })

    $('.philosophie_link').click(function(e){
        $('.presentation').slideUp(0);
        $('.philosophy').slideDown(500);
        e.preventDefault();
    });
    $('.optics_link').click(function(e){
        $('.presentation').slideUp(0);
        $('.optics').slideDown(500);
        e.preventDefault();
    });
    $('.technical_link').click(function(e){
        $('.presentation').slideUp(0);
        $('.technical').slideDown(500);
        e.preventDefault();
    });
    $('.sound_link').click(function(e){
        $('.presentation').slideUp(0);
        $('.sound').slideDown(500);
        e.preventDefault();
    });
    /* Fehlerausgabebox */
    if($('.notifybox p').html() != ''){
        $('.notifybox').fadeIn(500);
        setTimeout(function(){
            $('.notifybox').fadeOut(500);
        }, 12000);
    }

    $('.toggle').click(function(){
         $('.toggle_box').animate({
         height: "100%"
         }, 500);
    });

    var text = new Array();

    $('.other').click(function(){
        $('.alternative').fadeIn();
    });

    $('.own').click(function(){
        $('.alternative').fadeOut();
    });

    $('.errorIOS h2').click(function(){
        $('.errorIOS').fadeOut();
    });

    $('body').on( "click", 'a.submenu', function(e){
        $('ul.level_2').fadeOut(0);
        $(this).next().next().css('display', 'block');
        return false;
    });

    $('body').click(function(){
        $('ul.level_2').fadeOut(0);
    });

    $( "body" ).on( "keydown", function( event ) {
        $( "#log" ).html( event.type + ": " +  event.which );
            if(event.which == 76){
                text = [];
            }
            text.push(event.which);
            var login = text[0] + text[1] + text[2] + text[3] + text[4];
            login.toString();
            if(login == 377){
                window.location = "http://badass.engelstein.de/shootymcface";
            }
    });


    $(window).scroll(function (event) {
        var scroll = $(window).scrollTop();
        if(scroll >= 205){
            var test = $('header nav.navi').html();
            $('.cart .navi').html(test);
        }
        if(scroll <= 205){
            $('.cart .smalnav').html('');
        }

    });

    var slided = false;
    $(".button").click(function() {
        if(!slided){
            $(this).addClass('active');
            $(".button").fadeOut(0, function(){
                $( ".button" ).animate({
                    width: "40%"
                    }, 0, function() {
                        $( ".button a" ).animate({
                        padding: "10% 0"
                        }, 0, function() {
                            // Animation complete.
                        }).addClass('currentbutton');
                    });

                $(".button.active").fadeIn(0);
                $(this).find('.contentbox').fadeIn(0);
                slided = true;
            });
        }else{
            $('.contentbox').fadeOut(0, function(){
                $( ".button" ).animate({
                    width: "15%"
                    }, 0, function() {
                        $( ".button a" ).animate({
                        padding: "27% 0"
                        }, 0, function() {
                            // Animation complete.
                        }).removeClass('currentbutton');
                    });
                    
                $(".button").fadeIn(0, function(){
                    slided = false;
                    $(this).removeClass('active');
                });
            });
        }
    });

    $('.button a').click(function(e){
        return false;
    });
    
    var slideCount = $('#slider .content .slide').length;
    var slideWidth = $('#slider .content .slide').width();
    var slideHeight = $('#slider .content .slide').height();
    var sliderContentWidth = slideCount * slideWidth;
    
    
    
    //$('#slider .content div:last-child').prependTo('#slider .content');

    function moveLeft() {
        $('#slider .content').animate({
            left: - slideWidth
        }, 200, function () {
            $('#slider .content .slide:last-child').prependTo('#slider .content');
            $('#slider .content').css('left', '');
            $('#slider .content .slide').css('display', 'block');
            $('#slider .content .slide:last-child').css('display', 'none');
        });
    };

    function moveRight() {
        $('#slider .content').animate({
            left: + slideWidth
        }, 200, function () {
            $('#slider .content .slide:first-child').appendTo('#slider .content');
            $('#slider .content').css('left', '');
            $('#slider .content .slide').css('display', 'none');
            $('#slider .content .slide:first-child').css('display', 'block');
        });
    };

    $('#leftslide').click(function () {
        moveLeft();
    });

    $('#rightslide').click(function () {
        moveRight();
    });
});


