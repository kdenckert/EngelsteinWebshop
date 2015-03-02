<?php include'inc/progressbar.inc.php'; ?>
<?php if(empty($_SESSION['items'])) : ?>
    <h2 class="light" style="text-align: center;">Warenkorb ist Leer</h2>;
<?php else: ?>
<div class="checkout clearfix">
    <section class="left">
        <h2 class="light">Meine Daten</h2>
        <p><?= ucfirst($_SESSION['gender']) ?> <?= $_SESSION['name'] ?> <?= $_SESSION['lastname'] ?><br />
            <?= ucfirst($_SESSION['street']) ?> <?= $_SESSION['nr'] ?><br />
            <?= $_SESSION['postcode'] ?> <?= ucfirst($_SESSION['city']) ?><br />
            <?= $_SESSION['email'] ?><br />
            <?= @$_SESSION['phone'] ?>
        </p>
        <?php if($_SESSION['alt_on'] == 'on') : ?>
        <h2 class="light">Alternative Versandadresse</h2>
        <p><?= ucfirst(@$_SESSION['alt_gender']) ?> <?= @$_SESSION['alt_name'] ?> <?= $_SESSION['lastname'] ?><br />
            <?= ucfirst(@$_SESSION['alt_street']) ?> <?= @$_SESSION['alt_nr'] ?><br />
            <?= @$_SESSION['alt_postcode'] ?> <?= ucfirst(@$_SESSION['alt_city']) ?><br />
        </p>
        <?php endif; ?>
        <h2 class="light">Bestellübersicht</h2>
        <table class="cart small">
            <thead>
                <tr>
                    <th>Artikel</th>
                    <th>Stückzahl</th>
                    <th>Preis</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($_SESSION['items'] as $v) : ?>
                <?php $_POST['order']['mwst'] += ($v['price'] * 0.19) * $v['count'] / 2 ?>
                <?php @$_POST['order']['total'] += $v['price'] * $v['count'] / 2 ?>
                <tr>
                    <td><?php echo $v['item']?>, <?php echo $v['style']?>, <?php echo $v['color']?></td>
                    <td><?php echo $v['count']?></td>
                    <td><?php echo number_format((($v['price'] * 0.19) + $v['price']) * $v['count'] / 2, 2); ?> &euro;</td>
                </tr>
            <?php endforeach; ?>
            <tr class="delivery">
                <td></td>
                <td>Versand</td>
                <td>0,00 &euro;</td>
            </tr>
            <tr class="taxes">
                <td></td>
                <td>Mwst 19%</td>
                <td><?php echo number_format($_POST['order']['mwst'], 2); ?>&euro;</td>
            </tr>
            <tr class="total">
                <td></td>
                <td width="170">Gesamt</td>
                <td width="170"><?php echo number_format($_POST['order']['total'] + $_POST['order']['mwst'], 2);?> &euro;</td>
            </tr>
            </tbody>
        </table>
        <h2 class="light">Bestellbedingungen</h2>
        <p>
            Bitte beachten Sie: <br/>
            Ihre Bestellung wird für Sie gefertigt und hat eine Lieferzeit von 20 Tagen.
            Die Lieferzeit beginnt NACH dem Eingang des vollständigen Zahlungsbetrag auf unser Konto
            Die Variante Klassik ist eine Spezialanfertigung für Sie, daher gilt ein eingeschränktes Widerrufsrecht.
        </p>
    </section>

    <section class="left clearfix" style="text-align: left;">
        <form action="" id="checkoutform" method="post">
            <h2 class="light">Zahlung per</h2>
            <p>Vorkasse</p>

            <h2 class="light">AGB</h2>
            <div>
                <?php include 'agb.php'; ?>
            </div>

            <h2 class="light">Widerruf</h2>
            <div>
                <?php include 'widerruf.php'; ?>
            </div>
            <br /><br />

            <input class="left" type="checkbox" name="order_ready" id="agb"/> <label style="width: 90%;" for="agb" class="left">Hiermit bestätige ich die Bestellbedinungen, die <a
                    href="agb">AGB's</a> und die <a href="widerruf">Widerrufsbelehrung</a></label>
        </form>
    </section>
</div>

<nav class="clearfix buttonbar">
    <a href="versand-und-bezahlung" class="left">Daten ändern</a>
    <input type="submit" name="submit_order" form="checkoutform" value="kostenpflichtig bestellen" class="right" />
</nav>
<?php endif; ?>
