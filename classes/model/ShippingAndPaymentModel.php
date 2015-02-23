<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 22.01.2015
 * Time: 17:25
 */
require_once 'Model.php';

class ShippingAndPaymentModel extends Model{

    public function __construct(){
        parent::__construct();
    }

    public function addToSession($data){
        $this->session->setSession('gender', $data['gender']);
        $this->session->setSession('name', $data['name']);
        $this->session->setSession('lastname', $data['lastname']);
        $this->session->setSession('street', $data['street']);
        $this->session->setSession('nr', $data['nr']);
        $this->session->setSession('postcode', $data['postcode']);
        $this->session->setSession('city', $data['city']);
        $this->session->setSession('email', $data['email']);
        $this->session->setSession('phone', $data['phone']);
        $this->session->setSession('prepaid', $data['prepaid']);
        if($data['alternative_adress'] == 'on'){
            $this->session->setSession('alt_gender', $data['alt_gender']);
            $this->session->setSession('alt_name', $data['alt_name']);
            $this->session->setSession('alt_lastname', $data['alt_lastname']);
            $this->session->setSession('alt_street', $data['alt_street']);
            $this->session->setSession('alt_nr', $data['alt_nr']);
            $this->session->setSession('alt_postcode', $data['alt_postcode']);
            $this->session->setSession('alt_city', $data['alt_city']);
            $this->session->setSession('alt_on', $data['alternative_adress']);
        }
    }


}