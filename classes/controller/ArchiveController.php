<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 29.10.2014
 * Time: 17:15
 */
require_once '../classes/model/ArchiveModel.php';

class ArchiveController {

    public function __construct(){
        $this->AM = new ArchiveModel();
    }

    public function askForArchive(){
        return $archive = $this->AM->getArchive();
    }





} 