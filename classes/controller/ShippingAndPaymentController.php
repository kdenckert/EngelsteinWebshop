<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 22.01.2015
 * Time: 16:53
 */
require_once '../classes/model/ShippingAndPaymentModel.php';

class ShippingAndPaymentController extends System{

    public function __construct(){
        $this->SAM = new ShippingAndPaymentModel();
    }

    public function validateForm($data){
        if($data['gender'] == 'default'){
            $array[] = 'Bitte wählen Sie eine Anrede';
        }
        if(empty($data['name'])){
            $array[] = 'Bitte geben Sie ihren Namen an.';
        }
        if(empty($data['lastname'])){
            $array[] = 'Bitte geben Sie ihren Nachnamen an.';
        }
        if(empty($data['street'])){
            $array[] = 'Bitte geben Sie ihre Straße an.';
        }
        if(empty($data['nr'])){
            $array[] = 'Bitte geben Sie ihre Hausnummer an.';
        }
        if(empty($data['postcode'])){
            $array[] = 'Bitte geben Sie ihre Postleitzahl an.';
        }else{
            if(!preg_match('/^([0]{1}[1-9]{1}|[1-9]{1}[0-9]{1})[0-9]{3}$/', $data['postcode'])){
                $array[] = 'Die angegebene Postleitzahl ist ungültig';
            }
        }
        if(empty($data['city'])){
            $array[] = 'Bitte geben Sie ihre Stadt an.';
        }
        if(empty($data['email'])){
            $array[] = 'Bitte geben Sie ihre Email an.';
        }else{
            if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
                $array[] = 'Die angegebene Email ist ungültig';
            }
        }
        if($data['prepaid'] != 'on'){
            $array[] = 'Bitte Zahlungsart wählen';
        }
        if($data['alternative_adress'] == 'on'){
            if(empty($data['alt_name'])){
                $array[] = 'Bitte geben Sie ihren alternativen Versandnamen an.';
            }
            if(empty($data['alt_lastname'])){
                $array[] = 'Bitte geben Sie ihren alternativen Nachnamen an.';
            }
            if(empty($data['alt_street'])){
                $array[] = 'Bitte geben Sie ihre alternativen Straße an.';
            }
            if(empty($data['alt_nr'])){
                $array[] = 'Bitte geben Sie ihre alternativen Hausnummer an.';
            }
            if(empty($data['alt_postcode'])){
                $array[] = 'Bitte geben Sie ihre alternativen Postleitzahl an.';
            }else{
                if(!preg_match('/^([0]{1}[1-9]{1}|[1-9]{1}[0-9]{1})[0-9]{3}$/', $data['postcode'])){
                    $array[] = 'Die angegebene Postleitzahl ist ungültig';
                }
            }
            if(empty($data['city'])){
                $array[] = 'Bitte geben Sie ihre alternativen Stadt an.';
            }
        }
        if(empty($array)){
            $this->SAM->addToSession($data);
            return 'true';
        }else{
            return $array;
        }
    }


}