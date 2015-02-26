$(document).ready(function(){

    $('#sms_button').click(function(e){
        e.preventDefault();
        $('#sms_form').fadeToggle(500);
    });

	
    $('#sms_success').fadeOut(4000);
	$('#sms_fail').fadeOut(4000);

});
