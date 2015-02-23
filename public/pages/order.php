<?php if(isset($this->orderStatus)) :  ?>
<ul>
<?php foreach($this->orderStatus['orderForm'] as $k => $v){
    echo '<li>' . $v . '</li>';
}?>
</ul>
<?php endif; ?>
<h2>Diese Seite wird rausfliege und ist nur temporär da</h2>
<form action="" method="post">
    <fieldset>
        <legend>Artikel</legend>
        <select name="order[type]">
            <option value="M">Matt</option>
            <option value="R">Rough</option>
        </select>

        <select name="order[count]">
            <option value="2">Paar</option>
            <option value="5">Surround</option>
        </select>
    </fieldset>
    <fieldset>
        <legend>Kundendaten</legend>
        <p>
            <input type="text" name="order[name]" id="" value="Bert" placeholder="Name"/>
        </p>

        <p>
            <input type="text" name="order[lastname]" value="Bums" id="" placeholder="Nachname"/>
        </p>

        <p>
            <input type="text" name="order[street]" value="Knechtenweg" id="" placeholder="Straße"/>
        </p>

        <p>
            <input type="text" name="order[nr]" id="" value="12" placeholder="Hausnummer"/>
        </p>

        <p>
            <input type="text" name="order[postcode]" value="17823" id="" placeholder="Postleitzahl"/>
        </p>

        <p>
            <input type="text" name="order[city]" value="Polen" id="" placeholder="Stadt"/>
        </p>

        <p>
            <input type="text" name="order[email]" id="" value="BB@googlemail.com" placeholder="E-Mail"/>
        </p>

        <p>
            <input type="text" name="order[cellphone]" id="" value="01711225741" placeholder="Handy"/>
        </p>
    </fieldset>
    <p>
        <input type="submit" name="order[submit]" id="" value="Bestellen"/>
    </p>

</form>