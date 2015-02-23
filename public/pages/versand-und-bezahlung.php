<?php include'inc/progressbar.inc.php'; ?>
<?php if(empty($_SESSION['items'])) : ?>
    <h2 class="light" style="text-align: center;">Warenkorb ist Leer</h2>;
<?php else: ?>
    <?php if($_SESSION['gender'] == 'herr') {
        $herr = 'selected';
    }else if($_SESSION['gender'] == 'frau'){
        $frau = 'selected';
    }
    if($_SESSION['alt_gender'] == 'herr') {
        $alt_herr = 'selected';
    }else if($_SESSION['alt_gender'] == 'frau'){
        $alt_frau = 'selected';
    }
    ?>
<h2 class="light">Ihre Daten</h2>
<p>Geben Sie ihre Rechnungsdaten vollständig an.</p>
<table class="cart step2 mt20">
    <form action="" id="order-form" method="post">
        <thead>
            <tr class="radio">
                <th><input type="radio" name="customer_data[alternative_adress]" value="off" class="own" id="own" checked/> <label class="own" for="own">An meine Rechnungsadresse schicken</label></th>
                <th></th>
                <th><input type="radio" name="customer_data[alternative_adress]" value="on" id="other" class="other"/> <label class="other" for="other">Andere Lieferadresse angeben</label></th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td><h2 class="light">Daten eingabe</h2></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <label for="gender">Anrede</label>
                    <select name="customer_data[gender]" id="gender">
                        <option value="default">Bitte wählen</option>
                        <option value="herr" <?= $herr ?>>Herr</option>
                        <option value="frau" <?= $frau ?>>Frau</option>
                    </select>

                    <label for="name">Vorname</label>
                    <input id="name" name="customer_data[name]" value="<?= @$_SESSION['name'] ?>" type="text"/>

                    <label for="lastname">Nachname</label>
                    <input id="lastname" name="customer_data[lastname]" value="<?= @$_SESSION['lastname'] ?>" type="text"/>

                    <label for="street" class="quater">Straße <span style="margin-left: 240px;">Hausnummer</span></label>
                    <input class="quater" name="customer_data[street]" value="<?= @$_SESSION['street'] ?>" id="street" type="text"/>
                    <input class="tiny" name="customer_data[nr]" value="<?= @$_SESSION['nr'] ?>" type="text"/>

                    <label for="plz">PLZ <span style="margin-left: 60px;">Stadt</span></label>
                    <input class="tiny" name="customer_data[postcode]" value="<?= @$_SESSION['postcode'] ?>" id="plz" type="text"/>
                    <input class="quater" name="customer_data[city]" value="<?= @$_SESSION['city'] ?>" type="text"/>

                    <label for="email">E-Mail</label>
                    <input id="email" name="customer_data[email]" value="<?= @$_SESSION['email'] ?>" type="text"/>

                    <label for="phone">Telefon für evtl. Rückfragen (optional)</label>
                    <input id="phone" name="customer_data[phone]" value="<?= @$_SESSION['phone'] ?>" type="text"/>
                </td>
                <td></td>
                <td class="alternative">
                    <label for="genders">Anrede</label>
                    <select name="customer_data[alt_gender]" id="genders">
                        <option value="default">Bitte wählen</option>
                        <option value="herr" <?= $alt_herr ?>>Herr</option>
                        <option value="frau" <?= $alt_frau ?>>Frau</option>
                    </select>

                    <label for="alt_name">Vorname</label>
                    <input id="alt_name" name="customer_data[alt_name]" value="<?= @$_SESSION['alt_name'] ?>" type="text"/>

                    <label for="firstname_scnd">Nachname</label>
                    <input id="firstname_scnd" name="customer_data[alt_lastname]" value="<?= @$_SESSION['alt_lastname'] ?>" type="text"/>

                    <label for="street_scnd" class="quater">Straße <span style="margin-left: 240px;">Hausnummer</span></label>
                    <input class="quater" id="street_scnd" name="customer_data[alt_street]" value="<?= @$_SESSION['alt_street'] ?>" type="text"/>
                    <input class="tiny" name="customer_data[alt_nr]" value="<?= @$_SESSION['alt_nr'] ?>" type="text"/>

                    <label for="plz_scnd">PLZ <span style="margin-left: 75px;">Stadt</span></label>
                    <input class="tiny" name="customer_data[alt_postcode]" value="<?= @$_SESSION['alt_postcode'] ?>" id="plz_scnd" type="text"/>
                    <input class="quater" name="customer_data[alt_city]" value="<?= @$_SESSION['alt_city'] ?>" type="text"/>
                </td>
            </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="text-align: left;">
                <div style="margin-top: -120px; margin-left: 75px;">
                    <h2 class="light">Zahlungsauswahl</h2>
                    <input style="width: 10%;"type="radio" name="customer_data[prepaid]" id="prepaid"/> <label for="prepaid">Vorkasse</label>
                </div>
            </td>
        </tr>
        </tbody>
    </form>
</table>

<nav class="clearfix buttonbar">
    <a href="warenkorb" class="left">zum Warenkorb</a>
    <input type="submit" value="Weiter" name="customer_data[submit]" form="order-form" class="right"/>
</nav>
<?php endif; ?>