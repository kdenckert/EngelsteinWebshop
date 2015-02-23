<?php

require_once '../classes/view/View.php';
require_once '../classes/system/System.php';
require_once '../classes/controller/LoginController.php';
require_once '../classes/controller/OrderController.php';
require_once '../classes/controller/TrackingController.php';
require_once '../classes/controller/OrderhistoryController.php';
require_once '../classes/controller/ProtocolController.php';
require_once '../classes/controller/ArchiveController.php';
require_once '../classes/controller/ShippingAndPaymentController.php';
require_once '../classes/controller/NewsController.php';
require_once '../classes/controller/NewsEditController.php';
require_once '../classes/controller/UpcomingController.php';
require_once '../classes/controller/UpcomingEditController.php';






class Controller extends System
{

    public $page;
    public $year;
    public $webshopID;
    public $randomnumber;
    public $contractnumber;

    public function __construct()
    {
        $this->request = array_merge($_GET, $_POST);
        $this->validatePages();
        $this->view = new View();
        $this->arrStatus = array();
        parent::__construct();


    }

    public function run()
    {
        $this->year = date("Y");
        $this->webshopID = "75";
        $this->randomnumber = "5775";
        $this->contractnumber = $this->year . "-" . $this->webshopID . "-" . $this->randomnumber . "-";
        $this->view->test = $this->contractnumber;

        // Backend Switch-Case nur wenn User eingeloggt ist.
        if(isset($this->request['page']))
        {
            $this->view->setTemplate("backend");
            switch ($this->request['page'])
            {

                case 'order-history':
                    $this->OHC = new OrderhistoryController();
                    $this->view->orderHistory = $this->OHC->askForOrderhistory();
                    $this->view->statusArray = $this->defineProtocolSteps();
                    $this->view->trackingCodes = $this->OHC->handleTrackingData();
                    if(isset($this->request['delete'])){
                        $this->OHC->askfordeletion($this->request['delete'], $this->request['deleteScnd']);
                        header('Location: index.php?page=order-history');
                        exit();
                    }
                    if(isset($this->request['trackOrder'])){
                        $this->OHC->handleTrackingData($this->request['trackOrder']);
                        header('Location: index.php?page=order-history');
                        exit();
                    }
                    if(isset($this->request['lsubmit'])){
                        $this->OHC->OHM->generateOrderPDF($this->request);
                    }
                    if(isset($this->request['archive'])){
                        $this->OHC->moveToArchive($this->request['archive']);
                        header('Location: index.php?page=order-history');
                        exit();
                    }
                    break;

                case 'protocol':
                    $this->PC = new ProtocolController();
                    $this->view->status = $this->PC->askForStatus($this->request['id']);
                    $this->view->protocolSteps = $this->defineProtocolSteps();
                    $this->view->protocolData = $this->PC->askForSerialAndOrder($this->request['id']);
                    $this->view->log = $this->PC->askForLog($this->request['id']);
                    $this->view->payed = $this->PC->PM->checkForPayment($this->request['id']);
                    if(isset($this->request['update']) || isset($this->request['downdate'])){
                        if(isset($this->request['downdate'])){
                            $this->PC->askForStatusDowndate($this->request['downdate'], $this->request['sn'], $this->request['id']);
                        }else{
                            $this->PC->askForStatusUpdate($this->request['update'], $this->request['sn'], $this->request['id']);
                        }
                        $this->PC->PM->checkStateForEachSn($this->request['id']);
                        header('Location: index.php?page=protocol&id=' . $this->request['id']);
                        exit();
                    }
                    if(isset($this->request['payed'])){
                        $this->PC->PM->setStateToPayed($this->request['id']);
                        header('Location: index.php?page=protocol&id=' . $this->request['id']);
                        exit();
                    }
                    break;

                case 'archive':
                    $this->AC = new ArchiveController();
                    $this->view->archive = $this->AC->askForArchive();
                    $this->view->statusArray = $this->defineProtocolSteps();
                    break;

                case 'upcoming-edit':
                    $this->UEC = new UpcomingEditController($this->request);
                    $this->view->allUpcoming = $this->UEC->askForAllUpcoming();

                    break;

                case 'news-edit':
                    $this->NEC = new NewsEditController($this->request);
                    $this->view->allNews = $this->NEC->askForAllNews();
                    break;


            }

            $this->view->siteContent = $this->request['page'] . '.php';
        }

        // Frontend Switchcase für alle User.
        // getparameter P=
        elseif($this->page)
        {
            $this->view->setTemplate("default");
            switch ($this->page)
            {
                // Load different Models and Controllers
                case 'shootymcface':
                    if(isset($_SESSION['loginStatus'])){
                        header('Location: public/index.php?page=order-history');
                        exit();
                    }else{
                        if(isset($this->request['login'])){
                            $this->LC = new LoginController($this->request['login']);
                        }
                    }
                    break;

                case 'tracking':
                    $this->TC = new TrackingController();
                    $this->view->protocolSteps = $this->defineProtocolSteps();
                    $this->view->protocolData = $this->TC->askForSerialAndOrder($this->request['id']);
                    $this->view->status = $this->TC->askForStatus($this->request['id']);
                    break;
                case 'warenkorb':
                    if(isset($this->request['delete'])){
                        $this->session->unsetSession('items', $this->request['delete']);
                        header('Location: warenkorb');
                        exit();
                    }
                    break;
                case 'versand-und-bezahlung':
                    if(isset($this->request['customer_data']['submit'])){
                        $this->SAP = new ShippingAndPaymentController();
                        $this->arrStatus['error'] = $this->SAP->validateForm($this->request['customer_data']);
                        if($this->arrStatus['error'] === 'true'){
                            header('Location: abschluss-der-bestellung');
                            exit();
                        }

                    }
                    break;
                case 'abschluss-der-bestellung':
                    $this->view->orderData = $this->request;
                    $_SESSION['userdata'] = $this->request;
                    if(isset($this->request['submit_order'])){
                        if($this->request['order_ready'] == 'on'){
                            header("Location: /vielen-dank");
                            exit();
                        }else{
                            $this->arrStatus['error'][] = 'Bitte bestätigen sie die Allgemeinen Geschäftsbedingungen';
                        }
                    }
                    break;
                case 'vielen-dank':
                    $this->OC = new OrderController();
                    $this->arrStatus['error'] = $this->OC->transferOrderDataToModel($_SESSION);
                    $this->session->unsetSession();
                    break;

                case 'news':
                    $this->NC = new NewsController();
                    $this->view->newsContent = $this->NC->askForNews($this->request['id']);
                    break;

                case 'ausblick':
                    $this->UC = new UpcomingController();
                    $this->view->upcomingContent = $this->UC->askForUpcoming($this->request['id']);
                    break;

                case 'startseite':
                    $this->NC = new NewsController();
                    $this->view->newsContent = $this->NC->askForAllNews();

                    $this->UC = new UpcomingController();
                    $this->view->upcomingContent = $this->UC->askForAllUpcoming();
                    break;
            }
            $this->view->siteContent = $this->page . '.php';
            if(!empty($this->arrStatus['error'])){
                $this->view->status = $this->arrStatus['error'];
            }else{
                $this->view->status = array();
            }
        }


        try {

            return $this->view->parse();
        } catch (InvalidArgumentException $e) {
            echo $e->getMessage();
        }
    }

}