<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 29.10.2014
 * Time: 17:16
 */
require_once 'Model.php';
require_once '../classes/model/PDFOrderModel.php';


class OrderhistoryModel extends Model{


    public function __construct(){
        parent::__construct();
    }

    public function getOrderHistory(){
        // get all Orders and Customers who ordered
        $sql = 'SELECT o.*, c.* FROM bs_orders AS o, bs_customers AS c WHERE o.customerID = c.custID AND o.completed = :completed ORDER BY o.ordersCreated DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
               ':completed' => 0
           )
        );
        $res['orders'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $sql = 'SELECT o.ordersID, s.* FROM bs_orders AS o, bs_serialnumbers as s WHERE s.orders_id = o.ordersID ORDER BY s.serial_id ASC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':completed' => 0
           )
        );
        $res['serialnumbers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $sql = 'SELECT o.contractID, p.* FROM bs_orders AS o, bs_generated_pdfs as p WHERE p.orderID = o.contractID ORDER BY o.ordersCreated DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':completed' => 0
           )
        );
        $res['pdfs'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res;

    }
    public function getLogStatus(){
        $sql = 'SELECT * FROM bs_protocol_log';
        $stmt = $this->db->prepare($sql);
        $stmt->execute( );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function deleteFromOrders($id, $ScndID){
        $sql = 'SELECT * FROM bs_generated_pdfs WHERE orderID = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                ':id' => $ScndID
            )
        );
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql = 'DELETE FROM bs_orders WHERE ordersID = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':id' => $id
           )
        );
        $sql = 'DELETE FROM bs_serialnumbers WHERE orders_id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':id' => $id
           )
        );

        @unlink($_SERVER['DOCUMENT_ROOT'] . '/public/pdfs/' . $res[0]['url']);
        @unlink($_SERVER['DOCUMENT_ROOT'] . '/public/pdfs/' . $res[1]['url']);

        $sql = 'DELETE FROM bs_generated_pdfs WHERE orderID = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':id' => $id
           )
        );

        $sql = 'DELETE FROM bs_protocol_log WHERE ordersID = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':id' => $id
           )
        );

        $sql = 'DELETE FROM bs_track_da_packages WHERE orders_id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':id' => $id
           )
        );
    }

    public function getTrackingCodes(){
        $sql = 'SELECT * FROM bs_track_da_packages ORDER BY serialnumber ASC';
        $stmt = $this->db->prepare($sql);
        if($stmt->execute()){
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($res as $k){
                $returnArray[$k['serialnumber']]['trackingCode'] = $k['tracking_code'];
                $returnArray[$k['serialnumber']]['orders_id'];
            }
            return $returnArray;
        }else{
            return false;
        }
    }

    public function setOrderToArchive($id){
        $sql = 'UPDATE bs_orders SET status = :status, completed = :completed, completedAt= :completed_at, statusModified = :statusModified WHERE ordersID = :ordersID';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':ordersID' => $id,
              ':status' => 4,
              ':completed' => 1,
              ':statusModified' => date("Y-m-d H:i:s"),
              ':completed_at' => date("Y-m-d H:i:s")
           )
        );
    }

    public function setTrackingCodes($param = array()){
        $istrue = false;
        $sql = 'INSERT INTO bs_track_da_packages
                  (orders_id, tracking_code, serialnumber, created)
                VALUES
                  (:orders_id, :tracking_code, :serialnumber, :created)
                ON DUPLICATE KEY UPDATE
                  tracking_code = :tracking_code';
        $stmt = $this->db->prepare($sql);
        foreach($param as $k => $v){
            if(is_array($v)){
                if($v['trackingID'] != ''){
                    for ($i = 0; $i <= 1; $i++){
                        if(isset($v[$i])){
                            $array = array(
                               ':orders_id' => $v['ordersID'],
                               ':tracking_code' => $v['trackingID'],
                               ':serialnumber' => $v[$i],
                               ':created' => date("Y-m-d H:i:s")
                            );
                            try {
                                $stmt->execute($array);
                                $istrue = true;
                            } catch(PDOException $e){
                                echo $e->getMessage();
                            }
                        }
                    }
                }
            }
        }
        return $istrue;
    }

    public function generateOrderPDF($data){
        $this->POM = new PDFOrderModel();
        $this->POM->AliasNbPages();
        $this->POM->getData($data['pdf_data'][$_POST['identifier']], $_POST['identifier']);
        for($i = 0; $i < $data['pdf_data'][$_POST['identifier']]['packagecount']; $i++){
            $this->POM->getPackageCount($i + 1);
            $this->POM->AddPage();
            $this->POM->SetFont('Arial','',12);
            $this->POM->TableBody($header = array('Position', 'Menge', 'Artikel', 'Serienummer'), $i);
        }
        $this->POM->Output($_SERVER['DOCUMENT_ROOT'] . '/public/pdfs/L_' . $data['pdf_data'][$_POST['identifier']]['order']['contract_id'] . '_' . $data['pdf_data'][$_POST['identifier']]['lastname'] . '_' . $data['pdf_data'][$_POST['identifier']]['name'] . '.pdf', 'F');
        $this->POM->Output();
    }



} 