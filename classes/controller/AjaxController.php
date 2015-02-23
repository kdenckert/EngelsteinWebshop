<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 26.11.2014
 * Time: 13:12
 */


require_once '../../classes/model/AjaxModel.php';

class AjaxController {

    private $request;

    public function __construct(){
        $this->request = array_merge($_GET, $_POST);
        $this->AM = new AjaxModel();
        $this->run();
    }

    public function startChat(){

    }

    public function run(){
        if(isset($this->request['action']) && $this->request['action'] == 'logout') {
            $this->AM->disconnectByLogout($this->request['id']);
        }
        if(isset($this->request['message'])){
            $this->AM->writeMessage($this->request['message'], $this->request['name']);
        }
        if(isset($this->request['users'])){
            echo $this->AM->getAllOnlineUsers();
        }
        if(isset($this->request['read'])){
            echo $this->AM->readMessages();
        }
        if(isset($this->request['cart'])){
            $this->AM->addToCart();
            echo 'Artikel wurde zum Warenkorb hinzugefügt';
        }
        if(isset($this->request['clearcart'])){
            $this->AM->clearCart();
        }
    }

}

$AC = new AjaxController();
