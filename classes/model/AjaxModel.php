<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 26.11.2014
 * Time: 13:11
 */

require_once   '../../classes/system/System.php';


class AjaxModel extends System{

    public function __construct(){
        parent::__construct();
        $this->systemSetup();
    }

    public function disconnectByLogout($id){
        $sql = 'UPDATE bs_users SET is_online = :is_online WHERE ID = :ID';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':ID' => $id,
              ':is_online' => 0
           )
        );

    }

    public function getAllOnlineUsers(){
        $sql = 'SELECT * FROM bs_users WHERE is_online = :is_online';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':is_online' => 1
           )
        );
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($res);
    }

    public function writeMessage($message, $name){
        $sql = 'INSERT INTO bs_chats (user_name, message, created) VALUES (:user_name, :message, :created)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':message' => $message,
               ':user_name' => $name,
               ':created' => date("Y-m-d H:i:s")
           )
        );
    }

    public function readMessages(){
        $sql = 'SELECT * FROM bs_chats';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($res);
    }

    // add to Cart

    public function addToCart(){
        if(!empty($_SESSION['items'])){
            foreach($_SESSION['items'] as $k => $v){
                if($_GET['color'] == $v['color'] && $_GET['style'] == $v['style']){
                    $_SESSION['items'][$k]['count'] += $_GET['count'];
                    $this->true = false;
                    break;
                } else if($_GET['color'] != $v['color'] || $_GET['style'] != $v['style']){
                    $this->true = true;
                }
            }
            if($this->true){
                $this->session->addToCart(array('item' => $_GET['item'], 'price' => $this->price[$_GET['style']], 'count' => $_GET['count'], 'style' => $_GET['style'], 'color' => $_GET['color']));
            }
        }else{
            $this->session->addToCart(array('item' => $_GET['item'], 'price' => $this->price[$_GET['style']], 'count' => $_GET['count'], 'style' => $_GET['style'], 'color' => $_GET['color']));
        }
    }

    public function clearCart(){
        $this->session->unsetSession('items');
    }

} 