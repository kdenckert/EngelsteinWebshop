<?php
    $width = count($this->protocolData['serials']) * 500;
?>

<a href="?page=order-history">zurück</a>
<section class="data">
    <h2>Kundendaten</h2>
    <ul>
        <li>Name: <?php echo $this->protocolData['customer'][0]['name']?></li>
        <li>Nachname: <?php echo $this->protocolData['customer'][0]['lastname']?></li>
        <li>Straße: <?php echo $this->protocolData['customer'][0]['street']?> <?php echo $this->protocolData['customer'][0]['nr']?></li>
        <li>Postleitzahl: <?php echo $this->protocolData['customer'][0]['postcode']?></li>
        <li>Stadt: <?php echo $this->protocolData['customer'][0]['city']?></li>
        <li>Email: <?php echo $this->protocolData['customer'][0]['email']?></li>
        <li><?php if(!empty($this->protocolData['customer'][0]['cellphone'])){echo 'Telefon: ' . $this->protocolData['customer'][0]['cellphone'];}?></li>
    </ul>
    <?php if(!$this->payed){
        echo '
            <section class="controls">
                <a href="index.php?page=protocol&amp;id=' . $_GET['id'] . '&amp;payed">Rechnung wurde Bezahlt!</a>
            </section>
        ';
    } ?>

</section>
<?php if($this->payed) : ?>
<section class="protocol clearfix"  style="width: <?php echo $width ?>px;">
<?php foreach($this->protocolData['serials'] as $key => $val){
    echo '<div class="floatingContainer" id="' . $val['serial_id'] . '">';
    echo '<h2>Protokoll für Seriennummer: ' . $this->protocolData['order'][0]['article'] . $val['serial_id'] . $this->protocolData['order'][0]['type'] . '</h2>';

    if($val['state'] == 0){
        echo '<div class="progress" style=""><p style="width: 9%;">0%</p></div>';
    }else{
        echo '<div class="progress" style=""><p style="  width: ' . $val['state'] / count($this->protocolSteps) * 100 . '%;">' . round(($val['state']) / count($this->protocolSteps) * 100) . ' %</p></div>';

    }
    echo '<ul>';
        // Schleife die Protokollschritte raus
        foreach($this->protocolSteps as $kstep => $vstep){
            // Schleife den Statusarray, State und Serialid aus serials tabelle
            foreach($this->status as $kstat => $vstat){
                // Wenn Statusarray Serialid und Eine Serialid aus dem Datenarray übereinstimmen dann
                if($vstat['serial_id'] == $val['serial_id']){
                    if($vstat['state'] + 1 == $kstep){
                        echo '<li><strong>' . $vstep . '<a href="index.php?page=protocol&amp;id=' . $_GET['id'] . '&amp;sn='.$val['serial_id'].'&amp;update=' . $vstat['state'] . '#' . $val['serial_id'] . '">Abhaken</a></strong>';
                    }elseif($vstat['state'] == $kstep){
                        echo '<li><strong>' . $vstep . '<a href="index.php?page=protocol&amp;id=' . $_GET['id'] . '&amp;sn='.$val['serial_id'].'&amp;downdate=' . $vstat['state'] . '#' . $val['serial_id'] . '">Zurück</a></strong>';
                    }else{
                        echo '<li>' . $vstep . '';
                    }
                    if($vstat['state'] >= $kstep) {
                        foreach($this->log as $logk => $logv){
                            if($logv['sn_id'] ==  $val['serial_id']){
                                if($kstep == $logv['state']){
                                    echo '<p style="color: blue;">Abgehakt durch: '.$logv['tag'].' am '.$this->formatDate($logv['created']).'</p>';
                                }else{
                                }
                            }
                        }

                    }

                }else{
                    continue;
                }
            }
        }
        echo '</ul>';
    echo '</div>';

}
?>

</section>
    <?php
?>
<?php endif; ?>
