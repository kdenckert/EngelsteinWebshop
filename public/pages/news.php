<p><a href="startseite" style="font-size: 16px;">zurück zur Startseite</a></p>
<?php foreach($this->newsContent as $val) : ?>
    <?php $date = strtotime($val['created']); $date = date("d.m.Y", $date); ?>
   <h2><?= $date ?> - <?= $val['headline'] ?></h2>
    <p><em><?= $val['teaser'] ?></em></p>
   <section class="news">
           <?= $val['content'] ?>
   </section>
<?php endforeach; ?>