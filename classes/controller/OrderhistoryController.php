<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 29.10.2014
 * Time: 17:15
 */
require_once '../classes/model/OrderhistoryModel.php';

class OrderhistoryController{

    public function __construct(){
        $this->OHM = new OrderhistoryModel();
    }

    public function askForOrderhistory(){
        return $orderhistory = $this->OHM->getOrderHistory();
    }

    public function askForDeletion($id, $ScndID){
        $this->OHM->deleteFromOrders($id, $ScndID);
    }

    public function moveToArchive($param){
        $this->OHM->setOrderToArchive($param);
    }

    public function askForLogstatus(){
        $this->OHM->getLogStatus();
    }

    public function handleTrackingData($param = null){
        if(!is_null($param)){
            if($this->OHM->setTrackingCodes($param)){
                $this->OHM->arrStatus['error'] = 'Daten erfolgreich gespeichert!';
            }else{
                $this->OHM->arrStatus['error'] = 'Entweder es wurden keine Daten Eingegeben oder es liegt ein Fehler vor.';
            }
        }else{
            if($this->OHM->getTrackingCodes()){
                return $this->OHM->getTrackingCodes();
            }else{
                $this->OHM->arrStatus['error'] = 'Fehler bei der Abfrage der Trackingcodes';
                return '';
            }
        }
    }





} 