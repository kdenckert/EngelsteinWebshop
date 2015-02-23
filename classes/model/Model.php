<?php
require_once '../classes/system/System.php';
require_once '../classes/model/PDFInvoiceModel.php';


class Model extends System
{

    public $PDF;
    public $request;


    public function __construct()
    {
        $this->year = date("Y");
        $this->webshopID = "75";
        $this->randomnumber = "5775";
        $this->contractnumber = $this->year . "-" . $this->webshopID . "-" . $this->randomnumber . "-";
        $this->request = array_merge($_GET, $_POST);
        parent::__construct();
        $this->systemSetup();
    }



} 