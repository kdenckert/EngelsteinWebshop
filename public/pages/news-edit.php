<h2>News Einfügen</h2>
<style>
    input[type='text']{
        width: 400px;
        height: 30px;
        font-size: 16px;
    }
    input[name="news[teaser]"]{
        width: 1000px;
    }
    label{
        font-size: 20px;
        margin: 10px 0px;
    }
</style>

<form action="?page=news-edit&action=insert" method="post">

    <p>
        <label for="headline">News Headline</label>
        <input type="text" name="news[headline]" id="teaser"/>
    </p>
    <p>
        <label for="teaser">News Teaser</label>
        <input type="text" name="news[teaser]" id="teaser" />
    </p>
    <p>
        <label for="content">News Nachricht</label>
        <textarea name="news[content]" id="content" cols="30" rows="10"></textarea>
    </p>
    <p>
        <input type="submit" name="news[submit]" id="submit" value="Speichern"/>
    </p>
</form>

<h2>Alle News</h2>
<ul style="font-size: 14px;">
<?php foreach($this->allNews as $val): ?>
    <?php $date = strtotime($val['created']); $date = date("d.m.Y", $date); ?>
    <li>
        <h2><?= $date ?> - <?= $val['headline'] ?></h2>
        <!-- Inhalt kommt aus TinyMCE mit html-tags -->
        <p>Teaser: <em> <?=$val['teaser'] ?></em></p>
        <p>Content:</p> <?= $val['content']?>
        <a href="<?= $_SERVER['PHP_SELF'] ?>?page=news-edit&action=delete&delete=<?= $val['id'] ?>">News löschen!</a><br /><br />
    </li>
<?php endforeach; ?>
</ul>