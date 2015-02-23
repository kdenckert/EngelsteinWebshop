<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 03.02.2015
 * Time: 14:04
 */

require_once 'Model.php';


class UpcomingModel extends Model{

    public function __construct(){
        parent::__construct();
    }

    public function getUpcoming($id){
        $sql = 'SELECT * FROM bs_upcoming WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
               ':id' => $id
           )
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUpcoming(){
        $sql = 'SELECT * FROM bs_upcoming ORDER BY created DESC LIMIT 6';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}