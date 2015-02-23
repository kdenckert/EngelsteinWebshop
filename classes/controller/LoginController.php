<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 29.10.2014
 * Time: 14:15
 */
require_once '../classes/model/LoginModel.php';

class loginController {

    public function __construct($credentials){
        $this->LM = new LoginModel();
        $this->validateLogin($credentials);
    }

    public function validateLogin($credentials){
        $this->LM->checkValidUser($credentials);
    }
} 