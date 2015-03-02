$(document).ready(function(){

    $('#sms_button').click(function(e){
        e.preventDefault();
       // $(this).css('padding-left', '128px');
        $('#sms_form').fadeToggle(500, function(){});

    });

	
    $('#sms_success').fadeOut(4000);
	$('#sms_fail').fadeOut(4000);

});
