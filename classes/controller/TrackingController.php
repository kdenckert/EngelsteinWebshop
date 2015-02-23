<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 29.10.2014
 * Time: 17:15
 */
require_once '../classes/model/TrackingModel.php';

class TrackingController {


    public function __construct(){
        $this->TM = new TrackingModel();
    }

    public function askForStatus($id){
       return $this->TM->getStatusForTracking($id);
    }

    public function askForSerialAndOrder($id){
        return $this->TM->getSerialnumbersByOrderid($id);
    }


}