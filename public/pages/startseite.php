<div class="errorIOS" style=" display: none; background: rgba(0,0,0, .8); z-index: 100000; height: 100%; width: 100%; color: white; position:absolute; top: 0; left: 0; text-align: center; font-size: 40px; font-family: arial; padding-top: 30%;">Aufgrunf eines Bugs in Safari-Mobile (IOS) empfehlen wir die Benutzung eines anderen Browsers. Es kann zu Fehlfunktionen bei der Warenkorbfunktion kommen. <h2 style="margin-top: 40px; font-size: 60px;">Weiter zu Seite</h2></div>
<section class="upcoming_wrapper clearfix">
    <article>
        <h2>Upcoming</h2>
        <?php foreach($this->upcomingContent as $val) : ?>
            <?php $date = strtotime($val['created']); $date = date("d.m.Y", $date); ?>
            <div class="open_upcoming clearfix">
                <h3><?= ucfirst($val['headline']) ?></h3>
                <p class="left"><em><?= $val['teaser'] ?></em></p>
                <div style="display: none;">
                    <?= $val['content'] ?>
                </div>
                <!-- <a href="ausblick&amp;id=<?= $val['id'] ?>">weiterelesen...</a> -->
            </div>
        <?php endforeach; ?>
    </article>
    <aside>
        <h2>News</h2>
        <ul>
            <?php foreach($this->newsContent as $val) : ?>
               <?php $date = strtotime($val['created']); $date = date("d.m.Y", $date); ?>
            <li>
                <h3><?= $val['headline'] ?></h3>
                <h4><?= $date ?> - von Ronny Stein</h4>
                <!-- Inhalt kommt aus TinyMCE mit html-tags -->
                <p><em> <?=$val['teaser'] ?></em></p>
                <a href="news&amp;id=<?= $val['id'] ?>">Mehr erfahren....</a>
            </li>
            <?php endforeach; ?>
        </ul>
    </aside>
</section>
<section class="productBox startseite clearfix">
    <h2>Referenzen</h2>
    <ul class="bxslider">
        <li>
            <p class="message">"They look so sexy i cant wait to hear them."</p>
            <p class="quote">Ola Ersfjord - Schweden</p>
        </li>
    </ul>

</section>