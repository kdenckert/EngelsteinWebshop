<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 03.02.2015
 * Time: 14:03
 */

require_once '../classes/model/NewsModel.php';


class NewsController {

    public function __construct(){
        $this->NM = new NewsModel();
    }

    public function askForNews($id){
        return $this->NM->getNews($id);
    }

    public function askForAllNews(){
        return $this->NM->getAllNews();
    }

}