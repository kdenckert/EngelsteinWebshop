
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Engelstein Backend</title>
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/reset.css"/>
    <link rel="stylesheet" href="tinymce/skins/lightgray/skin.min.css"/>
    <link rel="stylesheet" href="tinymce/skins/lightgray/content.min.css"/>
    <link rel="stylesheet" href="css/backend.css"/>
</head>
<body>
<section class="wrapper">
<?php




if($_SESSION['loginStatus'] == 'admin'){
    echo'
            <h2>Hallo '.$_SESSION['name'].'</h2>

            <nav>
                <a href="?page=order-history">Bestellübersicht</a>
                <a href="?page=archive">Archiv</a>
                <a href="?page=news-edit">News</a>
                <a href="?page=upcoming-edit">Upcoming</a>
                <!-- <a href="">all Serials</a>
                <a href="">all Customers</a> -->
                <a href="?action=logout" class="logout">Logout</a>
            </nav>
        ';
}
?>

    <?php
    require_once 'pages/' . $this->siteContent;
    ?>
</section>
<section class="chat">
    <div class="chatBox">

    </div>

    <form id="chatform">
        <div class="contentInput">
            <input type="text" value="">
            <input type="submit" />
        </div>
    </form>

    <ul class="userlist">

    </ul>
</section>
<script>
    var id = <?php echo json_encode($_SESSION['ID']); ?>;
    var name = <?php echo json_encode($_SESSION['name']); ?>;
</script>
<script src="js/jquery-2.1.1.min.js"></script>
<script src="tinymce/tinymce.min.js"></script>
<script src="js/backend.js"></script>

</body>
</html>

