<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 29.10.2014
 * Time: 13:11
 */
require_once 'Session.php';

class System {

    public $price = array('student' => 3000, 'matt' => 3500, 'hochglanz' => 4000);
    public $stereo = 2;
    public $PIM;
    public $POM;

    public $db;
    public $arrStatus = array();
    public $session;


    public function __construct(){
        $this->session = new Session();
    }

    public function systemSetup($switch = 'live'){
        if($switch = 'live'){
            define('DB_HOST', 'mysql5.guitarstix.de');
            define('DB_NAME', 'db424797');
            define('DB_USER', 'db424797');
            define('DB_PASS', 'Engelsteinbadass2014');
        }else if($switch = 'local'){
            define('DB_HOST', 'localhost');
            define('DB_NAME', 'badass_engelstein');
            define('DB_USER', 'root');
            define('DB_PASS', '');
        }

        $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . '', DB_USER, DB_PASS, array(
           PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
           PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ));
    }

    public function validatePages(){
        $whitelist = array(
           'shootymcface', 'manage', 'archive',
           'apfelweiss', 'impressum', 'abschluss-der-bestellung',
           'apfelweiss-student', 'versand-und-bezahlung',
           'order', 'order-history', 'apfelweiss',
           'team', 'warenkorb', 'tracking', 'produkte',
           'agb', 'widerruf', 'vielen-dank', 'generateOrderConfirmation', 'datenschutz',
            'ausblick', 'news'
        );

        if(isset($this->request['p'])){
            if(in_array($this->request['p'], $whitelist)){
                $this->page = $this->request['p'];
            }else{
                $this->page = 'startseite';
            }
        }else{
            $this->page = 'startseite';
        }
    }


    public function defineProtocolSteps(){
        return array(
            1 => 'Gehäuse mit Reflexrohr bestücken',
            2 => 'Dämmringe für Tief-/Mitteltöner einkleben',
            3 => 'Schalter in Terminal einpasen',
            4 => 'Terminal-Input mit Platine verbinden',
            5 => 'Schalter mit Platine verbinden',
            6 => 'Kabel für Hochtöner und Tief-/Mitteltöner anlöten',
            7 => 'Platine und Terminal in das Gehäuse führen',
            8 => 'Terminal und Platine verschrauben',
            9 => 'Gehäuse mit Dämmwolle befüllen',
            10 => 'Hoch- und Tief-/Mitteltöner anbringen',
            11 => 'Optische Prüfung',
            12=> 'Impedanzverlauf prüfen.',
            13 => 'Frequenzgang prüfen'
        );

    }

    public function formatDate($date = '2014-11-05 16:34:24'){
        $dateExplode = explode(' ', $date);
        $justDate = $dateExplode[0];
        $explodeJustDate = explode('-', $justDate);
        $justTime = $dateExplode[1];
        $formattedDate = array();
        $formattedDate['date'] = $explodeJustDate[2] . '.' . $explodeJustDate[1] . '.' . $explodeJustDate[0];
        $formattedDate['time'] = $justTime;
        return $formattedDate['date'] . ' - ' . $formattedDate['time'];
    }

}