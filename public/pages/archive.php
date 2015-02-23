<h1>Archiv</h1>
<section class="orders">
    <table class="orderOverview">
        <tbody>
        <?php foreach ($this->archive['orders'] as $val) {
            // tausche status gegen text
            if($val['status'] == 1){
                $color= 'red';
            }else if($val['status'] == 2){
                $color= 'darkorange';
            }else{
                $color= 'green';

            }
            /*
            foreach($this->statusArray as $key => $vals){
                if($val['status'] == $key){
                    $val['status'] = $vals;
                    break;
                }
            }*/
            echo '
                    <tr style="border-top: thin solid #aaa;">
                        <td colspan="15"><h2 style="color: '.$color.'">' . $this->formatDate($val['ordersCreated']) . ' - ' . $val['name'] . ' ' . $val['lastname'] . ' </h2></td>
                    </tr>
                    <tr class="opener open_' . $val['ordersID'] . '">
                        <td><strong>Auftrags- / Rechnungsnummer</strong><br />' . $val['invoiceID'] . '</td>
                        <td><strong>Artikel</strong><br />' . $val['article'] . '</td>
                        <td><strong>Variante</strong><br />' . $val['type'] . '</td>
                        <td><strong>Anzahl</strong><br />' . $val['count'] . '</td>
                        <td><strong>Preis</strong><br />' . $val['price'] . ' €</td>
                    </tr>
                    <tr>
                        <td colspan="13">
                            <div class="openingdiv open_' . $val['ordersID'] . ' clearfix">
                                <div class="container clearfix">
                                    <div class="left overviewBox orderInformation">
                                        <h2>Bestellte Seriennummern:</h2>
                                        <ul>
                                        ';

            foreach($this->archive['serialnumbers'] as $kserial => $vserial){
                if($vserial['orders_id'] == $val['ordersID']){
                    if($val['count'] == 2){
                        $countSerial = 'A';
                    }elseif($val['count'] == 5){
                        $countSerial = 'B';
                    }
                    echo '<li>'.$val['article'] . $vserial['serial_id'] . $val['type'] . $countSerial . '</li>';
                    echo '<li>';
                    if($vserial['state'] == 0){
                        echo '<div class="progress" style=""><p style="width: 9%;">0%</p></div>';
                    }else{
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
                                    <div class="left overviewBox pakages">
                                       <h2>Sendungsverfolung</h2>
                                       <form action="" method="post">
                                       ';
            if($val['status'] == 4)
            {
                $moduloCount = 1;
                $entryCounter = 0;
                foreach($this->archive['serialnumbers'] as $kserial => $vserial)
                {
                    if($vserial['orders_id'] == $val['ordersID'])
                    {

                        echo '<p>' . $val['article'] . $vserial['serial_id'] . $val['type'] . $countSerial . '</p>';

                    }
                }
            }else if($val['status'] <= 3){
                echo '<p>protokoll noch nicht fertiggestellt.</p>';
            }
            else if($val['status'] >= 3){
                echo '<p>Bestellung wurde am xy verschickt</p>';
            }

            echo'
                                    </div>
                                </div>
                                <div class="toolbar ">
                                    <nav>
                                    <a href="?page=protocol&amp;id=' . $val['ordersID'] . '">Fertigungsprotokoll</a>
                                    ';
            foreach($this->archive['pdfs'] as $kpdf => $vpdf){
                if($vpdf['orderID'] == $val['ordersID']){
                    if($vpdf['type'] == 'order'){
                        echo '<a href="pdfs/'.$vpdf['url'].'">Rechnung als PDF</a>';
                    }else if($vpdf['type'] == 'invoice'){
                        echo '<a href="pdfs/'.$vpdf['url'].'">Auftragsbestätigung als PDF</a>';
                    }else if($vpdf['type'] == 'protokoll'){
                        echo '<a href="pdfs/'.$vpdf['url'].'">Protokoll als PDF</a>';
                    }
                }
            }
            echo '
                                    <a href="tracking&amp;id='.$val['md5_primary'].'">Tracking</a>
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