<?php $headline = explode('-', $_GET['p']); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <link rel="shortcut icon" href="public/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="public/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="public/css/reset.css"/>
    <link rel="stylesheet" href="public/css/nivo-slider.css"/>
    <link rel="stylesheet" href="public/css/jquery.bxslider.css"/>
    <link rel="stylesheet" href="public/css/nivo-lightbox.css"/>
    <link rel="stylesheet" href="public/css/themes/default/default.css"/>
    <link rel="stylesheet" href="public/css/lbox_themes/default/default.css"/>
    <link rel="stylesheet" href="public/css/themes/dark/dark.css"/>
    <link rel="stylesheet" href="public/css/themes/bar/bar.css"/>
    <link rel="stylesheet" href="public/css/themes/light/light.css"/>

    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">    <link rel="stylesheet" href="public/css/screen.css"/>
    <title>Engelstein Webshop</title>
</head>
<body>
<section class="cart">
    <nav class="navi smalnav"></nav>
    <a href="warenkorb" class="cartlink"><i class="fa fa-shopping-cart" style="font-size: 24px; margin-right: 30px;"></i> <strong> Artikel</strong></a>
</section>
<div class="notifybox">
    <p><?php
        if(count($this->status) > 0){
            foreach($this->status as $val){
                echo '&bull; ' . $val . '<br />';
            }
        }
        ?></p>

</div>
<header>
    <figure>
        <a href="<?= $_SERVER['PHP_SELF'] ?>"><img src="public/img/logo.png" height="130" alt=""/></a>
    </figure>
    <nav class="navi">
        <ul class="level_1">
            <li>
                <a href="startseite" class="circle"><i class="fa fa-home"></i></a>
                <div class="label">Startseite</div>
            </li>
            <li><a href="" class="circle submenu"><i class="fa fa-th"></i></a>
                <div class="label">Produkte</div>
                <ul class="level_2">
                    <li><a href="produkte">Produktübersicht</a></li>
                    <li><a href="apfelweiss">Apfelweiß Klassik</a></li>
                    <li><a href="apfelweiss-student">Apfelweiß Student</a></li>
                </ul>

            <li><a href="team" class="circle"><i class="fa fa-users"></i></a>
                <div class="label">Team</div>

            <li><a href="warenkorb" class="circle"> <i class="fa fa-shopping-cart"></i></a>
                <div class="label">Warenkorb</div>
            <li><a href="" class="circle submenu"><i class="fa fa-info"></i></a>
                <div class="label">Infos</div>
                <ul class="level_2">
                    <li><a href="impressum">Impressum</a></li>
                    <li><a href="datenschutz">Datenschutz</a></li>
                    <li><a href="agb">AGBs</a></li>
                    <li><a class="last" href="widerruf">Widerrufsrecht</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <section class="slider">
        <?php if($_GET['p'] == 'startseite' || $_GET['p'] == ''): ?>
           <div class="slider-wrapper theme-default">
               <div class="ribbon"></div>
                <div class="imageSlider nivoSlider">
                    <a href="produkte">
                        <img src="public/img/apfelweis-teaser.png" width="100%" title="#feature-text-01" alt="" />

                    <a href="produkte">
                        <img src="public/img/studio.png" width="100%" title="#feature-text-02" alt="" />
                    </a>
                </div>
               <div id="feature-text-01" class="nivo-html-caption">
                   <p>Mein Text....</p>
               </div>
               <div id="feature-text-02" class="nivo-html-caption">
                   <p>...zweites Bild</p>
               </div>

           </div>
        <?php else: ?>
            <ul>
                <li><img src="public/img/<?php echo $_GET['p']; ?>_icon_dark.png" height="200" alt=""/></li>
            </ul>
            <h1><?= @ucwords(str_replace('apfelweiss', 'apfelweiß', $headline[0])) ?> <?= @ucwords(str_replace('Apfelweiss', 'Apfelweiß', $headline[1])) ?> <?= @ucwords(str_replace('Apfelweiss', 'Apfelweiß', $headline[2])) ?></h1>
        <?php endif;?>
    </section>
</header>

<div id="wrapper">
    <section class="innerWrapper">
        <? require_once 'pages/' . $this->siteContent; ?>
    </section>
</div>

<footer class="dark">
    <section class="clearfix">
        <div class="left">
            <h3>Sitemap</h3>
            <ul>
                <li><a href="produkte">Produkte</a></li>
                <li><a href="team">Team</a></li>
                <li><a href="apfelweiss">Apfelweiß</a></li>
                <li><a href="impressum">Impressum</a></li>
                <li><a href="datenschutz">Datenschutz</a></li>
                <li><a href="agb">AGB</a></li>
            </ul>
        </div>
        <div class="left">
            <h3>Produkte</h3>
            <ul>
                <li><a href="produkte">Produkte</a></li>
                <li><a href="apfelweiss">Apfelweiss</a></li>
                <li><a href="apfelweiss-student">Apfelweiss Student</a></li>
            </ul>
        </div>
        <div class="left contact">
            <h3>Schnellkontakt</h3>
            <p>
                Engelstein UG<br />
                (haftungsunbeschränkt)
                <br /><br />
                Salomonstraße 26 - 28<br />
                04103 Leipzig
                <br /><br />
                Tel.: 0341 / 99 38 38 70<br />
                E-Mail: <a href="mailto:info@engelstein.de">info@engelstein.de</a>
            </p>
        </div>
        <div class="right">
            <h3>Unsere Partner</h3>
            <ul>
                <li><a href="http://www.lydiatrudi.com/laden/neuigkeiten/" target="_blank">Lydiatrudi</a></li>
                <li><a href="http://www.tischlerei-deger.de/" target="_blank">Deger</a></li>
                <li><a href="https://leipzig.sae.edu/de/home/" target="_blank">SAE Institute Leipzig</a></li>

            </ul>
        </div>
    </section>
</footer>
<script>
    var cartcounter = <?php echo json_encode(count($_SESSION['items'])); ?>;
</script>
<script src="public/js/jquery-2.1.1.min.js"></script>
<script src="public/js/jquery.nivo.slider.js"></script>
<script src="public/js/nivo-lightbox.min.js"></script>
<script src="public/js/jquery.bxslider.min.js"></script>

<script src="public/js/frontend.js"></script>
<script src="public/js/functionality.js"></script>



</body>
</html>

