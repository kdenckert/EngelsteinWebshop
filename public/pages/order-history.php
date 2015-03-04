<h1>Bestellübersicht</h1>
<h2 style="margin-top: 15px;">Legende</h2>
<p style="margin: 15px 0px;">
    <span
       style="display: inline-block; margin: 0px 10px; border-radius: 10px; height: 20px; width: 20px; background: red;"></span>Bestellung
    ist eingegangen
    <span
       style="display: inline-block; margin: 0px 10px; border-radius: 10px; height: 20px; width: 20px; background: darkorange;"></span>Protokoll
    wird bearbeitet
    <span
       style="display: inline-block; margin: 0px 10px; border-radius: 10px; height: 20px; width: 20px; background: green;"></span>Bereit
    zum verschicken
</p>
<section class="orders">
    <table class="orderOverview">
        <tbody>
        <?php foreach ($this->orderHistory['orders'] as $val) {
            // tausche status gegen text
            if ($val['status'] == 1) {
                $color = 'red';
            } else {
                if ($val['status'] == 2) {
                    $color = 'darkorange';
                } else {
                    $color = 'green';

                }
            }
            echo '
                    <tr style="border-top: thin solid #aaa;">
                        <td colspan="15"><h2 style="color: ' . $color . '">' . $val['ordersCreated'] . ' - ' . $val['name'] . ' ' . $val['lastname'] . ' </h2></td>
                    </tr>
                    <tr class="opener open_' . $val['ordersID'] . '">
                        <td><strong>Auftrags- / Rechnungsnummer</strong><br />' . $this->test . $val['contractID'] . '

                        </td>
                        <td><strong>Status</strong><br />
                            ';
            if ($val['status'] == 1) {
                echo '<div style="border-radius: 10px; height: 20px; width: 20px; background: red;"></div>';
            } else {
                if ($val['status'] == 2) {
                    echo '<div style="border-radius: 10px; height: 20px; width: 20px; background: darkorange;"></div>';
                } else {
                    if ($val['status'] == 3) {
                        echo '<div style="border-radius: 10px; height: 20px; width: 20px; background: green;"></div>';
                    }
                }
            }
            echo '
                        </td>

                        <td><strong>Artikel</strong><br />' . $val['article'] . '</td>
                        <td><strong>Variante</strong><br />' . $this->replaceStyleWithCode($val['type']) . '</td>
                        <td><strong>Farbe</strong><br />' . @$val['color'] . '</td>
                        <td><strong>Anzahl</strong><br />' . $val['count'] . '</td>
                        <td><strong>Preis</strong><br />' . $val['price'] * $val['count'] / 2 . ' €</td>
                        <td><strong>Löschen</strong><br /><a class="deleteOrder" href="?page=order-history&amp;delete=' . $val['ordersID'] . '"&amp;deleteScnd=' . $val['contractID'] . '">jetzt löschen</a></td>
                    </tr>
                    <tr>
                        <td colspan="13">
                            <div class="openingdiv open_' . $val['ordersID'] . ' clearfix">
                                <div class="container clearfix">
                                    <div class="left overviewBox orderInformation">
                                        <h2>Bestellte Seriennummern:</h2>
                                        <ul>
                                        ';

            foreach ($this->orderHistory['serialnumbers'] as $kserial => $vserial) {
                if ($vserial['orders_id'] == $val['ordersID']) {
                    if ($val['count'] == 2) {
                        $countSerial = 'A';
                    } elseif ($val['count'] == 5) {
                        $countSerial = 'B';
                    } elseif ($val['count'] / ($val['count'] / 2) == 2) {
                        $countSerial = 'A';
                    }
                    echo '<li>' . $val['article'] . $vserial['serial_id'] . $this->replaceStyleWithCode($val['type']) . $countSerial . '</li>';
                    echo '<li>';
                    if ($vserial['state'] == 0) {
                        echo '<div class="progress" style=""><p style="width: 9%;">0%</p></div>';
                    } else {
                        echo '<div class="progress" style=""><p style="  width: ' . $vserial['state'] / count($this->statusArray) * 100 . '%;">' . round(($vserial['state']) / count($this->statusArray) * 100) . ' %</p></div>';

                    }
                    echo '</li>';
                }
            }

            echo '
                                        </ul>
                                    </div>
                                    <div class="left overviewBox orderInformation">
                                        <h2>Adresse</h2>
                                        <p>
                                            Straße: ' . $val['street'] . ' ' . $val['nr'] . '<br />
                                            Postleitzahl: ' . $val['postcode'] . '<br />
                                            Stadt: ' . $val['city'] . '<br />
                                            Email: ' . $val['email'] . '<br />
                                            Telefon: ' . $val['cellphone'] . '<br />

                                        </p>
                                    </div>
                                    <div class="left overviewBox orderInformation">
                                    ';
                                    if($val['alt_data'] == 1){
                                        echo '<h2>Alternative Adresse</h2>
                                        <p>
                                            Name: ' . $val['alt_gender'] . ' ' . $val['alt_name'] . ' ' . $val['alt_lastname'] . '<br />
                                            Straße: ' . $val['alt_street'] . ' ' . $val['alt_nr'] . '<br />
                                            Postleitzahl: ' . $val['alt_postcode'] . '<br />
                                            Stadt: ' . $val['alt_city'] . '<br />
                                        </p>

                                        ';

                                    }

                                    echo '
                                    </div>
                                    <div class="left overviewBox pakages">
                                       <h2>Sendungsverfolung</h2>
                                       ';
            if ($val['status'] == 3) {
                echo '<form action="" method="post">';

                $moduloCount = 1;
                $entryCounter = 0;
                foreach ($this->orderHistory['serialnumbers'] as $kserial => $vserial) {
                    if ($vserial['orders_id'] == $val['ordersID']) {
                        if (!empty($this->trackingCodes[$vserial['serial_id']]['trackingCode'])) {
                            $locked = 'disabled="disabled"';
                        } else {
                            $locked = '';
                        }

                        echo '
                                                            <div>
                                                                <input type="hidden" form="pdf_data_' . $this->test . $val['contractID'] . '" name="pdf_data[' . $this->test . $val['contractID'] . '][order][package_' . $entryCounter . '][]"  value="' . $val['article'] . $vserial['serial_id'] . $this->replaceStyleWithCode($val['type']) . $countSerial . '"/>
                                                                <label for="' . $vserial['serial_id'] . '">' . $val['article'] . $vserial['serial_id'] . $this->replaceStyleWithCode($val['type']) . $countSerial . ' Paket: ' . ($entryCounter + 1) . '</label>
                                                                <input type="hidden" name="trackOrder[' . $entryCounter . '][]" value="' . $vserial['serial_id'] . '" />
                                                                <input type="hidden" name="trackOrder[' . $entryCounter . '][ordersID]" value="' . $val['ordersID'] . '" />
                                                        ';
                        if (($moduloCount % 2) == 0) {
                            echo '<p style="margin: 10px 0px;"><input type="text" id="' . $vserial['serial_id'] . '" ' . @$locked . ' name="trackOrder[' . $entryCounter . '][trackingID]" placeholder="Sendungsverfolgung" value="' . @$this->trackingCodes[$vserial['serial_id']]['trackingCode'] . '" /><a href="#edit" class="editTrackingcode">edit</a></p>';
                            $entryCounter += 1;
                        } else {
                            if ($moduloCount == $val['count'] && $val['count'] >= 2) {
                                echo '<p style="margin: 10px 0px;"><input type="text" id="' . $vserial['serial_id'] . '" name="trackOrder[' . $entryCounter . '][trackingID]" ' . @$locked . ' placeholder="Sendungsverfolgung" value="' . @$this->trackingCodes[$vserial['serial_id']]['trackingCode'] . '"/><a href="#edit" class="editTrackingcode">edit</a></p>';
                            }
                        }
                        echo '</div>
                        ';
                        $moduloCount++;
                    }
                }
                echo '
                                                <p>
                                                    <input type="submit" value="Eintragen" name="trackOrder[submit]" />
                                                </p>
                                                </form>';
            } else {
                if ($val['staus'] <= 3) {
                    echo '<p>protokoll noch nicht fertiggestellt.</p>';
                } else {
                    if ($val['staus'] >= 3) {
                        echo '<p>Bestellung wurde am xy verschickt</p>';
                    }
                }
            }

            echo '
                                    </div>
                                </div>

                                <div class="toolbar ">


                                    <nav>
                                        <a href="?page=protocol&amp;id=' . $val['ordersID'] . '">Fertigungsprotokoll</a>
                                        <form id="pdf_data_' . $this->test . $val['contractID'] . '" method="post">
                                            <!-- Alternative Shipping Data -->
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][alt_data]"  value="' . $val['alt_data'] . '"/>
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][alt_gender]"  value="' . $val['alt_gender'] . '"/>
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][alt_name]"  value="' . $val['alt_name'] . '"/>
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][alt_lastname]"  value="' . $val['alt_lastname'] . '"/>
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][alt_city]"  value="' . $val['alt_city'] . '"/>
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][alt_street]"  value="' . $val['alt_street'] . '"/>
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][alt_nr]"  value="' . $val['alt_nr'] . '"/>
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][alt_postcode]"  value="' . $val['alt_postcode'] . '"/>
                                            <!-- Regular Shipping Data -->
                                            <input name="identifier" value="' . $this->test . $val['contractID'] . '" type="hidden"/>
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][order][article]"  value="' . $val['article'] . '"/>
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][order][type]"  value="' . $val['type'] . '"/>
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][order][color]"  value="' . $val['color'] . '"/>
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][order][price]"  value="' . $val['price'] . '"/>
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][order][count]"  value="' . $val['count'] . '"/>
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][gender]"  value="' . $val['gender'] . '"/>
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][name]"  value="' . $val['name'] . '"/>
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][lastname]"  value="' . $val['lastname'] . '"/>
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][street]"  value="' . $val['street'] . ' ' . $val['nr'] . '"/>
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][postcode]"  value="' . $val['postcode'] . '"/>
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][city]"  value="' . $val['city'] . '"/>
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][email]"  value="' . $val['email'] . '"/>
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][cellphone]"  value="' . $val['cellphone'] . '"/>
                                            <input type="hidden" name="pdf_data[' . $this->test . $val['contractID'] . '][packagecount]"  value="' . $entryCounter . '"/>
                                            <input type="submit" name="lsubmit" class="linkbutton" value="Lieferschein generieren"/>
                                        </form>
                                    ';
                                    foreach ($this->orderHistory['pdfs'] as $kpdf => $vpdf) {
                                        if ($vpdf['orderID'] == $val['contractID']) {
                                                if ($vpdf['type'] == 'invoice') {
                                                    echo '<a href="pdfs/' . $vpdf['url'] . '">Rechnung als PDF</a>';
                                                    break;
                                                } else {
                                                    if ($vpdf['type'] == 'protokoll') {
                                                        echo '<a href="pdfs/' . $vpdf['url'] . '">Protokoll als PDF</a>';
                                                        break;
                                                    }
                                                }
                                        }
                                    }
                                    echo '
                                    <a href="?page=order-history&amp;archive=' . $val['ordersID'] . '" style="background: green;" class="archiveOrder">Bestellung abschließen</a>
                                    <a href="http://engelstein.de/tracking&id=' . $val['md5_primary'] . '">Tracking</a>

                                    </nav>
                                </div>
                            </div>
                        </td>
                    </tr>
                ';
        }
        ?>
        </tbody>
    </table>
</section>