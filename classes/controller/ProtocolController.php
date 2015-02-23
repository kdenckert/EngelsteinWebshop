<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 29.10.2014
 * Time: 17:15
 */
require_once '../classes/model/ProtocolModel.php';

class ProtocolController {

    public $arrStatus = array();

    public function __construct(){
        $this->PM = new ProtocolModel();
    }

    public function askForStatus($id){
        return $this->PM->getLogStatus($id);
    }

    public function askForLog($id){
        return $this->PM->getProtocolLog($id);
    }

    public function askForSerialAndOrder($id){
        return $this->PM->getSerialnumbersByOrderid($id);
    }

    public function askForStatusUpdate($update, $sn, $id){
        $this->PM->updateStatus($update, $sn, $id);
    }
    public function askForStatusDowndate($update, $sn, $id){
        $this->PM->downdateStatus($update, $sn, $id);
    }
    // ####

}