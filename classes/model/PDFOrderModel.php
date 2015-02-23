<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 05.11.2014
 * Time: 09:48
 */

require_once '../classes/system/fpdf.php';

class PDFOrderModel extends FPDF{

    private $headerY;
    private $data = array();
    private $pc;
    private $orderNumber;

    public function getData($data, $orderNumber){
        $this->data = $data;
        $this->orderNumber = $orderNumber;
    }

    public function getPackageCount($pc){
        $this->pc = $pc;
    }

    public function Header(){
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(80);
        // Title
        //$this->Cell(30,10,ES,1,0,'C');
        $this->Image($_SERVER['DOCUMENT_ROOT'] . '/public/img/logo.png', 45, 15, 110);
        // Line break
        $this->Ln(40);
        $this->SetFont('Arial', '', 12);
        //$this->Cell(45, 10, 'Firma des Kunden', 0, 0, '', false, '');
        $this->Ln(12);
        if($this->data['alt_data'] == 1){
            $this->MultiCell(80, 5, ucfirst(@$this->data['alt_gender']) . ' ' . utf8_decode($this->data['alt_name']) . ' ' . utf8_decode($this->data['alt_lastname']) . "\n" . ucfirst(utf8_decode($this->data['alt_street'])) . ucfirst(utf8_decode($this->data['alt_nr'])) . "\n" . utf8_decode($this->data['alt_postcode']) . ' ' . ucfirst(utf8_decode($this->data['alt_city'])) . "\n" .  "\n" . "Paket " . $this->pc . " / " . $this->data['packagecount'], 0, '', false);
        }else{
            $this->MultiCell(80, 5, ucfirst(@$this->data['gender']) . ' ' . utf8_decode($this->data['name']) . ' ' . utf8_decode($this->data['lastname']) . "\n" . ucfirst(utf8_decode($this->data['street'])) . ucfirst(utf8_decode($this->data['nr'])) . "\n" . utf8_decode($this->data['postcode']) . ' ' . ucfirst(utf8_decode($this->data['city'])) . "\n" .  "\n" . "Paket " . $this->pc . " / " . $this->data['packagecount'], 0, '', false);
        }
        $this->SetXY(140, 52);
        $this->MultiCell(80, 5, utf8_decode('Engelstein UG'."\n".'(Haftungsunbeschränkt)'."\n".''."\n".'Salomonstraße 26 - 28'."\n".'04103 Leipzig'."\n".''."\n".'Telefon: 0341 / 99 38 38 70'."\n".'E-Mail: info@engelstein.de'), 0, '', false);
        $this->SetX(10);
        $this->ln(10);
        $this->SetFontSize(16);
        $this->Cell(45, 10, utf8_decode('Lieferschein'), 0, 0, '', false, '');
        $this->ln(11);
        $this->SetFontSize(10);
        $this->MultiCell(0, 5, utf8_decode('Herzlichen Glückwunsch zu Ihrem neuen Engelstein-Produkt. Anbei erhalten Sie den Lieferschein für Ihre '. $this->pc . '. Bestellung mit der folgenden Bestellnummer: ' . $this->orderNumber), 0, "J");
        $this->headerY = $this->GetY();
    }

    public function TableBody($header, $c){
        $this->SetY($this->headerY);
        $this->ln(10);
        // Column widths
        $w = array(20, 20, 115, 37);
        // Header
        $this->SetTextColor(255,255,255);
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],7,$header[$i],1,0,'L', '#000');
        $this->Ln();
        // Data
        $this->SetTextColor(0,0,0);
        $count = 0;
        foreach($this->data['order']['package_' . $c] as $row)
        {
            $this->Cell($w[0],12,$count,'B');
            $this->Cell($w[1],12,'1','B');
            $this->Cell($w[2],12,utf8_decode(@$this->data['order']['article']) . ', ' . utf8_decode(@$this->data['order']['type']) . ', ' . utf8_decode(@$this->data['order']['color']),'B');
            $this->Cell($w[3],12,$this->data['order']['package_' . $c][$count],'B',0,'C');
            $this->Ln();
            $count++;
        }
        // Closing line
        $this->Cell(array_sum($w),0,'','T');
        $this->Ln(12);
        $this->SetFontSize(10);
        $this->MultiCell(0, 5, utf8_decode('Sofern Sie mehr als zwei Lausprecher bestellt haben, erfolgt der Versand in mehreren Kartons. Heute erhalten Sie die '. $this->pc . '. Lieferung. Sollten Sie mehr als eine Lieferung erwarten, haben Sie etwas Geduld. Den Fortschritt Ihrer Bestellung können Sie weiterhin in der Statusverfolgung einsehen.
'), 0, "L");
        $this->Ln(12);
        $this->Cell(0,10,utf8_decode('Mit freundlichen Grüßen'),0,0,'L');
        $this->Ln(6);
        $this->Cell(0,10,utf8_decode('Ihr Engelstein-Team'),0,0,'L');


    }

    public function Footer(){
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','',10);
        // Page number


        $this->Cell(0,10,'Seite '.$this->PageNo().' von {nb}',0,0,'C');
    }

}