<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 29.10.2014
 * Time: 17:16
 */
require_once 'Model.php';

class ArchiveModel extends Model{


    public function __construct(){
        parent::__construct();
    }

    public function getArchive(){
        // get all Orders and Customers who ordered
        $sql = 'SELECT o.*, c.* FROM bs_orders AS o, bs_customers AS c WHERE o.customerID = c.custID AND o.completed = :completed ORDER BY o.ordersCreated DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':completed' => 1
           )
        );
        $res['orders'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $sql = 'SELECT o.ordersID, s.* FROM bs_orders AS o, bs_serialnumbers as s WHERE s.orders_id = o.ordersID ORDER BY o.ordersCreated DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $res['serialnumbers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql = 'SELECT o.ordersID, p.* FROM bs_orders AS o, bs_generated_pdfs as p WHERE p.orderID = o.ordersID ORDER BY o.ordersCreated DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $res['pdfs'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }




} 