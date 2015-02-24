<?php

class Session
{

    var $model;
    var $db;

    public function __construct()
    {
        /* CUSTOM DB CONFIG*/
        define('DB_HOST', 'mysql5.guitarstix.de');
        define('DB_NAME', 'db424797');
        define('DB_USER', 'db424797');
        define('DB_PASS', 'esdatabase2014');
        /* END */
        if(!isset($_SESSION))
        {
            session_start();
            session_name('USER');
        }

        $this->request = array_merge($_GET, $_POST);
        $this->checkSession();
    }

    public function checkSession()
    {
        if (!isset($_SESSION['lastdone']))
        {
            $_SESSION['lastdone'] = time();
        }
        elseif (isset($this->request['action']) && $this->request['action'] == 'logout' || $_SESSION['lastdone'] + 3600 < time())
        {
            /* CUSTOM */
            $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . '', DB_USER, DB_PASS, array(
               PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
               PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ));

            $sql = 'UPDATE bs_users SET is_online = :is_online WHERE ID = :ID';
            $stmt = $this->db->prepare($sql);
            $stmt->execute(
               array(
                  ':ID' => $_SESSION['ID'],
                  ':is_online' => 0
               )
            );
            /* EEND*/
            unset($_COOKIE['LOGGED_IN']);
            $_SESSION = array();
            session_destroy();
            header('location: http://badass.engelstein.de/startseite');
            exit();
        }
        else
        {
            $_SESSION['lastdone'] = time();
        }
    }

    public function setSession($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function addToCart($value){
        $_SESSION['items'][] = $value;
    }

    public function unsetSession($key = '', $scndKey = '', $thirdkey = '')
    {
        if($key != '' && $scndKey == '' && $thirdkey == ''){
            unset($_SESSION[$key]);
        }else if($scndKey != '' && $key != '' && $thirdkey == ''){
            unset($_SESSION[$key][$scndKey]);
        }else if($scndKey != '' && $thirdkey != '' && $key != ''){
            unset($_SESSION[$key][$scndKey][$thirdkey]);
        }else if($key == '' && $scndKey == '' && $thirdkey == ''){
            unset($_SESSION);
            session_destroy();
        }
    }

}