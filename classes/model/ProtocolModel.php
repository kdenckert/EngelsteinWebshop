<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 29.10.2014
 * Time: 17:16
 */
require_once 'Model.php';

class ProtocolModel extends Model{

    public function __construct(){
        parent::__construct();
    }

    public function setStateToPayed($id){
        $sql = 'UPDATE bs_orders set status = :status, statusModified = :statusModified, payedAt = :payed_at WHERE ordersID = :ordersID';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':payed_at' => date("Y-m-d H:i:s"),
              ':ordersID' => $id,
              ':status' => 2,
              ':statusModified' => date("Y-m-d H:i:s")
           )
        );
    }

    public function checkStateForEachSn($id){
        $count = count($this->defineProtocolSteps());
        $sql = 'SELECT state FROM bs_serialnumbers WHERE orders_id = :ordersID';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':ordersID' => $id,
           )
        );
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $innerCounter = 0;
        foreach($res as $k => $v){
            if($v['state'] == $count){
                $innerCounter++;
            }
        }
        if($innerCounter == count($res)){
            $sql = 'UPDATE bs_orders set status = :status, statusModified = :statusModified WHERE ordersID = :ordersID';
            $stmt = $this->db->prepare($sql);
            $stmt->execute(
               array(
                  ':ordersID' => $id,
                  ':status' => 3,
                  ':statusModified' => date("Y-m-d H:i:s")
               )
            );
        }else{
            $sql = 'UPDATE bs_orders set status = :status, statusModified = :statusModified WHERE ordersID = :ordersID';
            $stmt = $this->db->prepare($sql);
            $stmt->execute(
               array(
                  ':ordersID' => $id,
                  ':status' => 2,
                  ':statusModified' => date("Y-m-d H:i:s")
               )
            );
            return false;
        }

    }

    public function checkForPayment($id){
        $sql = 'SELECT status FROM bs_orders WHERE ordersID = :ordersID';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':ordersID' => $id,
           )
        );
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($res[0]['status'] >= 2){
            return true;
        }else{
            return false;
        }
    }

    public function getSerialnumbersByOrderid($orders_id){
        $sql = 'SELECT * FROM bs_serialnumbers WHERE orders_id = :orders_id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':orders_id' => $orders_id
           )
        );

        $res['serials'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $sql = 'SELECT * FROM bs_orders WHERE ordersID = :orders_id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':orders_id' => $orders_id
           )
        );

        $res['order'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql = 'SELECT * FROM bs_customers WHERE custID = :customer_id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':customer_id' => $res['order'][0]['customerID']
           )
        );
        $res['customer'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getLogStatus($id){
        $sql = 'SELECT state, serial_id FROM bs_serialnumbers WHERE orders_id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
               ':id' => $id
           )
        );
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getProtocolLog($id){
        $sql = 'SELECT * FROM bs_protocol_log WHERE ordersID = :ordersID';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':ordersID' => $id
           )
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function updateStatus($update, $sn, $id){
        $sql = 'INSERT INTO bs_protocol_log
                (state, ordersID, tag, sn_id)
                VALUES
                (:state, :ordersID, :tag, :sn_id)
                ';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':state' => $update + 1,
              ':ordersID' => $id,
              ':tag' => $_SESSION['tag'],
              ':sn_id' => $sn
           )
        );

        $sql = 'UPDATE bs_serialnumbers
            SET
            state = :state, state_modified = :state_modified
            WHERE
            serial_id = :serial_id';

        $stmt = $this->db->prepare($sql);
        $array = array(
           ':serial_id' => $sn,
           ':state' => $update + 1,
           ':state_modified' => date("Y-m-d H:i:s")
        );
        if($stmt->execute($array)){

        }else{

        }

    }
    public function downdateStatus($update, $sn, $id){
        $sql = 'DELETE FROM bs_protocol_log WHERE state = :state AND sn_id = :sn_id';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':state' => $update,
              ':sn_id' => $sn
           )
        );

        $sql = 'UPDATE bs_serialnumbers
            SET
            state = :state, state_modified = :state_modified
            WHERE
            serial_id = :serial_id';
        $stmt = $this->db->prepare($sql);
        $array = array(
           ':serial_id' => $sn,
           ':state' => $update - 1,
           ':state_modified' => date("Y-m-d H:i:s")
        );
        if($stmt->execute($array)){

        }else{

        }

    }

} 