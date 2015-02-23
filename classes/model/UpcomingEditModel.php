<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 03.02.2015
 * Time: 14:04
 */

require_once 'Model.php';


class UpcomingEditModel extends Model{

    public function __construct(){
        parent::__construct();
    }

    public function insertData($data){
        $sql = 'INSERT INTO bs_upcoming (headline, teaser, content, created)
                VALUES (:headline, :teaser, :content, :created)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
                ':headline' => $data['headline'],
                ':teaser' => $data['teaser'],
                ':content' => $data['content'],
                ':created' => date('Y-m-d H:i:s', time())
           )
        );
    }

    public function getAllUpcoming(){
        $sql = 'SELECT * FROM bs_upcoming ORDER BY created DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUpcoming($id){
        $sql = 'DELETE FROM bs_upcoming WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':id' => $id,
           )
        );
    }


}