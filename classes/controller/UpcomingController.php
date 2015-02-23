<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 03.02.2015
 * Time: 14:03
 */

require_once '../classes/model/UpcomingModel.php';


class UpcomingController {

    public function __construct(){
        $this->UM = new UpcomingModel();
    }

    public function askForUpcoming($id){
        return $this->UM->getUpcoming($id);
    }

    public function askForAllUpcoming(){
        return $this->UM->getAllUpcoming();
    }

}