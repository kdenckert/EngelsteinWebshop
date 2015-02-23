<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 03.02.2015
 * Time: 14:03
 */

require_once '../classes/model/UpcomingEditModel.php';


class UpcomingEditController {

    public function __construct($data){
        $this->UEM = new UpcomingEditModel();
        $this->run($data);
    }

    public function run($data){
        switch($data['action']){
            case 'insert':
                $this->UEM->insertData($data['upcoming']);
                break;
            case 'delete':
                $this->UEM->deleteUpcoming($data['delete']);
                header('location: ?page=upcoming-edit');
                exit();
            break;
        }
    }

    public function askForAllUpcoming(){
        return $this->UEM->getAllUpcoming();
    }
}