<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 03.02.2015
 * Time: 14:03
 */

require_once '../classes/model/NewsEditModel.php';


class NewsEditController {

    public function __construct($data){
        $this->NEM = new NewsEditModel();
        $this->run($data);
    }

    public function run($data){
        switch($data['action']){
            case 'insert':
                $this->NEM->insertData($data['news']);
                break;
            case 'delete':
                $this->NEM->deleteNews($data['delete']);
                header('location: ?page=news-edit');
                exit();
                break;
        }
    }

    public function askForAllNews(){
        return $this->NEM->getAllNews();
    }

}