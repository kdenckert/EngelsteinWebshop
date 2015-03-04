$(document).ready(function(){
if (navigator.userAgent.match(/(iPad|iPhone|iPod touch);.*CPU.*OS 7_\d/i)){
   $('.errorIOS').fadeIn();
}
/**
 * Created by MaD on 14.12.2014.
 */
$('.cartlink strong').html(cartcounter + ' Artikel');
var _COLOR = '';
var _PRICE = '';
var _STYLE = '';
var _COUNT = '2';
var _CUSTOM;

Number.prototype.formatMoney = function(c, d, t){
    var n = this,
        c = isNaN(c = Math.abs(c)) ? 2 : c,
        d = d == undefined ? "." : d,
        t = t == undefined ? "," : t,
        s = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

function calculatePrice(style){
    var price;
    switch(style){
        case 'matt':
            price = 2941.18;
            price = price + (price * 0.19);
            price = Math.round(price);
            break;
        case 'hochglanz':
            price = 3361.34;
            price = price + (price * 0.19);
            price = Math.round(price);
            break;
    }



    return price;
}

// select Variation of AWKlassic
$('.choose_color').click(function(){
        $('.color img').removeClass('active');
        $(this).addClass('active');
        _COLOR = $(this).next().find('p').attr('class')
        $('li.final_color strong').addClass(_COLOR).html(_COLOR);
});


$('.choose_style').click(function(){
    $('.style img').removeClass('active');
    $(this).addClass('active');
    _PRICE = calculatePrice(_STYLE)
    _STYLE = $(this).next().find('p').attr('class');
    if(_STYLE == 'student'){

    }
    $('li.final_style strong').addClass(_STYLE).html(_STYLE);
    $('li.final_price strong').addClass(_STYLE).html(calculatePrice(_STYLE) + ' €');
});


// add to cart
$('.addtocart').click(function(e){
    if($(this).hasClass('no-customization')){
        _COLOR = 'nicht wählbar';
        _STYLE = 'student';
        _CUSTOM = false;
    }
    if(_COLOR == ''){
        $('.notifybox p').html('Farbe wurde nicht gewählt.');
        $('.notifybox').fadeIn(500);
        setTimeout(function(){
            $('.notifybox').fadeOut(500);
        }, 3000);
    }else if(_STYLE == ''){
        $('.notifybox p').html('Stil wurde nicht gewählt.');
        $('.notifybox').fadeIn(500);
        setTimeout(function(){
            $('.notifybox').fadeOut(500);
        }, 3000);
    }
    if(_COLOR != '' && _STYLE != ''){
        var item = '';
        item = $(this).attr('href');
        item = item.split('&');
        cartcounter++;
        $('.cartlink strong').html(cartcounter + ' Artikel');
        $.ajax({
            url: "classes/controller/AjaxController.php?cart&" + item[1] + "&color=" + _COLOR + "&style=" + _STYLE + '&count=' + _COUNT,
            type: "GET",
            success: function (e) {
                $('.notifybox p').html(e);
                $('.notifybox').fadeIn(500);
                setTimeout(function(){
                    $('.notifybox').fadeOut(500);
                }, 3000);
                _COLOR = ''; _STYLE = ''; _PRICE = '';
                $('.style img').removeClass('active'); $('.color img').removeClass('active');
                if(_CUSTOM !== false){
                    $('li.final_price strong').html(''); $('li.final_color strong').html(''); $('li.final_style strong').html('');
                }
            }
        });
    }
    return false;
})

// clear cart
$('.clearcart').click(function(e){
    $.ajax({
        url: "../classes/controller/AjaxController.php?clearcart=true",
        type: "GET",
        success: function (e) {
        }
    });
    window.href('http://engelstein.de/warenkorb');

    return false;
})

// read Cart
});