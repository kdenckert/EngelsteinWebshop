<h2>Upcoming Einfügen</h2>
<style>
    input[type='text']{
        width: 400px;
        height: 30px;
        font-size: 16px;
    }
    input[name="upcoming[teaser]"]{
        width: 1000px;
    }
    label{
        font-size: 20px;
        margin: 10px 0px;
    }
</style>

<form action="?page=upcoming-edit&action=insert" method="post">

    <p>
        <label for="headline">Upcoming Headline</label>
        <input type="text" name="upcoming[headline]" id="teaser"/>
    </p>
    <p>
        <label for="teaser">Upcoming  Teaser</label>
        <input type="text" name="upcoming[teaser]" id="teaser" />
    </p>
    <p>
        <label for="content">Upcoming Nachricht</label>
        <textarea name="upcoming[content]" id="content" cols="30" rows="10"></textarea>
    </p>
    <p>
        <input type="submit" name="upcoming[submit]" id="submit" value="Speichern"/>
    </p>
</form>

<h2>Alle Upcoming</h2>
<ul style="font-size: 14px;">
    <?php foreach($this->allUpcoming as $val): ?>
        <?php $date = strtotime($val['created']); $date = date("d.m.Y", $date); ?>
        <li>
            <h2><?= $date ?> - <?= $val['headline'] ?></h2>
            <!-- Inhalt kommt aus TinyMCE mit html-tags -->
            <p>Teaser: <em> <?=$val['teaser'] ?></em></p>
            <p>Content:</p> <?= $val['content']?>
            <a href="<?= $_SERVER['PHP_SELF'] ?>?page=upcoming-edit&action=delete&delete=<?= $val['id'] ?>">News löschen!</a><br /><br />
        </li>
    <?php endforeach; ?>
</ul>