<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 29.10.2014
 * Time: 14:15
 */

require_once 'Model.php';

class LoginModel extends Model{

    public function __construct(){
        parent::__construct();

    }

    public function checkValidUser($credentials){
        $sql = 'SELECT * FROM bs_users WHERE username = :username AND password = :password';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':username' => $credentials['username'],
              ':password' => md5($credentials['password'])
           )
        );
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql = 'UPDATE bs_users SET is_online = :is_online WHERE username = :username AND password = :password';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':username' => $credentials['username'],
              ':password' => md5($credentials['password']),
              ':is_online' => 1
           )
        );

        if(!empty($res)){
            // Set logged in User Credentials for global Use
            $this->session->setSession('loginStatus', 'admin');
            $this->session->setSession('name', $res[0]['name']);
            $this->session->setSession('ID', $res[0]['ID']);
            $this->session->setSession('signature', $res[0]['signature']);
            $this->session->setSession('tag', $res[0]['tag']);
            // Redirect after Login
            header('location: public/index.php?page=order-history');
            exit;
        }
    }
}