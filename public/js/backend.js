function logoutChat(){
    $.ajax({
        url: "../classes/controller/AjaxController.php?action=logout&id=" + id,
        type: "GET",
        success: function (e) {
            console.log(e);
        }
    });
}

tinymce.init({
    selector: "textarea",
    mode : "exact",
    element_format : "html",
    verify_html : false,
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});

function checkChat(){
    $.ajax({
        url: "../classes/controller/AjaxController.php?users",
        type: "GET",
        success: function (e, r, a) {
            var names = $.parseJSON(e);
            $('.chat .userlist li').remove();
            for(var i = 0; i < names.length; i++){
                if($('.chat ul li.' + names[i]['name']).length === 0){
                    $('.chat .userlist').append('<li class="'+names[i]['name']+'">'+names[i]['name']+'</li>');
                }
            }
            readChat();
        }
    });

}

function readChat(){
    $.ajax({
        url: "../classes/controller/AjaxController.php?read=true",
        type: "GET",
        success: function (e) {
            var messages = $.parseJSON(e);
            for(var i = 0; i < messages.length; i++){
                if($('.chatBox p.' + messages[i]['entry_id']).length === 0){
                    $('.chatBox').append('<p class="'+messages[i]['entry_id']+'">'+messages[i]['user_name']+' : '+messages[i]['message']+'</p>');
                }
            }
            $('.chatBox').animate({"scrollTop": $('.chatBox')[0].scrollHeight}, "fast");
        }
    });
}

function writeChat(text){
    $.ajax({
        url: "../classes/controller/AjaxController.php?message=" + text + "&name=" + name,
        type: "GET",
        success: function (e) {
            readChat();
        }
    });
}

$(document).ready(function () {

    $('#chatform').submit(function(e){
        writeChat($('.contentInput input[type="text"]').val());
        $('.contentInput input[type="text"]').val('');

        return false;
    });

    setInterval(readChat, 2000);
    setInterval(checkChat, 2000);


    $('.logout').click(function(e){
        logoutChat();
    });

    setTimeout("logoutChat", 3600);

    $('tr').click(function(){
        if($(this).hasClass('opener') === true){
            var opener_id = $(this).attr('class');
            var opener = opener_id.split(' ');
            $('div.' + opener[1]).slideToggle(200);
        }
    });

    $('.editTrackingcode').click(function(){
        $(this).prev('input').removeAttr('disabled');
    });
    $('.deleteOrder').click(function(){
        var del = window.confirm('Wirklich löschen?');
        if(del){
            return true;
        }else{
            return false;
        }
    });
    $('.archiveOrder').click(function(){
        var del = window.confirm('Wollen Sie die Bestellung wirklich archivieren?');
        if(del){
            return true;
        }else{
            return false;
        }
    });
});



