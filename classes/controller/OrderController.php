<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 29.10.2014
 * Time: 17:15
 */
require_once '../classes/model/OrderModel.php';

class OrderController {

    public $arrStatus = array();

    public function __construct(){
        $this->OM = new OrderModel();
    }

    public function transferOrderDataToModel($data){
        $this->OM->saveOrder($_SESSION['items'], $data);
        $array[]= 'Bestellung wurde entgegengenommen. Bitte schauen Sie in ihr Email Postfach.';
        return $array;
    }



} 