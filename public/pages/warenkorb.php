<?php include'inc/progressbar.inc.php'; ?>
<?php if(empty($_SESSION['items'])) : ?>
    <h2 class="light" style="text-align: center;">Warenkorb ist Leer</h2>;
<?php else: ?>
<table class="cart">
    <form action="" method="post">
        <thead>
            <tr>
                <th>Artikel</th>
                <th>Anzahl</th>
                <th class="delete">Löschen</th>
                <th>Preis</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($_SESSION['items'] as $k => $v) : ?>
            <?php $_POST['order']['total'] += $v['price'] * $v['count'] / 2 ?>
            <tr>
                <td>Artikel: <?= $v['item'] ?>, Stil: <?= ucfirst($v['style'])?>, Farbe: <?= ucfirst($v['color']) ?></td>
                <td><?= $v['count'] ?></td>
                <td class="delete"><a href="/warenkorb&amp;delete=<?= $k ?>"><i class="fa fa-ban"></i></a></td>
                <td><?= number_format($v['price'] * $v['count'] / 2, 2) ?> &euro;</td>
            </tr>
        <?php endforeach;?>
        <tr class="taxes"><td></td><td></td><td></td><td></td></tr>
        <tr class="delivery">
            <td></td>
            <td></td>
            <td>Versand</td>
            <td>0,00 &euro;</td>
        </tr>
        <tr class="taxes">
            <td></td>
            <td></td>
            <td>Mwst 19%</td>
            <td><?= number_format($_POST['order']['total'] * 0.19, 2) ?>&euro;</td>
        </tr>
        <tr class="total">
            <td></td>
            <td></td>
            <td>Gesamt</td>
            <td><?= number_format($_POST['order']['total'], 2) ?> &euro;</td>
        </tr>
        </tbody>
    </form>
</table>

<nav class="clearfix buttonbar">
    <a href="produkte" class="left">zur Produktübersicht</a>
    <a href="versand-und-bezahlung" class="right">Daten überprüfen</a>
</nav>
<?php endif; ?>