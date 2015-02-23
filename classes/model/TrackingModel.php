<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 24.11.2014
 * Time: 22:02
 */

require_once 'Model.php';

class TrackingModel extends Model{

    public function __construct(){
        parent::__construct();

    }

    public function getStatusForTracking($id){
        $sql = 'SELECT status, completed FROM bs_orders WHERE md5_primary = :ordersID';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
               ':ordersID' => $id
           )
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSerialnumbersByOrderid($orders_id){
        $sql = 'SELECT * FROM bs_orders WHERE md5_primary = :md5_primary';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':md5_primary' => $orders_id
           )
        );

        $res['order'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql = 'SELECT * FROM bs_serialnumbers WHERE orders_id = :orders_id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':orders_id' => $res['order'][0]['ordersID']
           )
        );

        $res['serials'] = $stmt->fetchAll(PDO::FETCH_ASSOC);


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

} 