<?php

$sms_uri = $_SERVER['REQUEST_URI'];

if(isset($_POST['sms_submit'])){
    if(!empty($_POST['sms_text'])){
		
        $to = 'sms@engelstein.de';
        $subject = 'Feedback: '.$_SERVER['HTTP_HOST'].' | '.date('d.m.Y');
        $msg = "Feedback gesendet:\n";
        $msg .= "\n";
        $msg .= "Nachricht: ".$_POST['sms_text']."\n";
        $msg .= "\n";
        $msg .= "Von Seite: ".$_SERVER['REQUEST_URI']."\n";

        if(mail($to, $subject, $msg)){
			$sendMail = true;
		}else{
			$sendMail = false;
		}

    }else{
		$sendMail = false;
	}
}

?>
<div id="sms_field">
    <link rel="stylesheet" type="text/css" href="public/sms_button/css/style.css">
    <script src="public/sms_button/js/script.js"></script>

    <a id="sms_button" href=""><img width="70" src="public/sms_button/img/feedback.png"></a>
    <?php if(@$sendMail) : ?>
         <p id="sms_success">SMS versandt</p>
	<?php elseif(@$sendMail === false) : ?>
		<p id="sms_fail">Es gibt nichts zu versenden...</p>
    <?php endif; ?>
    <form id="sms_form" action="<?=$sms_uri ?>" method="post">
        <textarea maxlength="160" name="sms_text" id="sms_text" rows="9" cols="25" placeholder="Das hier abgegebene Feedback erreicht uns direkt. Unsere Reaktion könnt ihr unter Infos auf der Feedback-Seite finden."></textarea><br/>
        <input type="submit" value="Abschicken!" name="sms_submit">
    </form>
</div>
