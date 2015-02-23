<?php if(isset($_GET['id'])) : ?>
<section class="protocol clearfix"  style="">
    <?php
    echo '<p class="left"><img src="" style="background: green;" width="50px" height="50px" /></p>';

    if($this->status[0]['status'] >= 2){
        echo '<p class="left"><img src="" style="background: green;" width="50px" height="50px" /></p>';
    }else{
        echo '<p class="left"><img src="" style="background: red;" width="50px" height="50px" /></p>';
    }
    foreach($this->protocolData['serials'] as $key => $val){
        echo '<div class="left" id="' . $val['serial_id'] . '">';
        echo '<h2>Seriennummer: ' . $this->protocolData['order'][0]['article'] . $val['serial_id'] . $this->protocolData['order'][0]['type'] . '</h2>';
        if($val['state'] == 0){
            echo '<div class="progress" style=""><p style="width: 7%;">5%</p></div>';
        }else{
            echo '<div class="progress" style=""><p style="  width: ' . $val['state'] / count($this->protocolSteps) * 100 . '%;">' . round(($val['state']) / count($this->protocolSteps) * 100) . ' %</p></div>';

        }
        echo '</div>';
    }
    if($this->status[0]['status'] >= 3){
        echo '<p class="left"><img src="" style="background: green;" width="50px" height="50px" /></p>';
    }else{
        echo '<p class="left"><img src="" style="background: red;" width="50px" height="50px" /></p>';
    }
    if($this->status[0]['completed'] == 1){
        echo '<p class="left"><img src="" style="background: green;" width="50px" height="50px" /></p>';
    }else{
        echo '<p class="left"><img src="" style="background: red;" width="50px" height="50px" /></p>';
    }
    ?>


</section>
<?php endif; ?>