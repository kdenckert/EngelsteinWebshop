<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 05.11.2014
 * Time: 09:48
 */

require_once '../classes/system/fpdf.php';

class PDFInvoiceModel extends FPDF{

    private $headerY;
    private $data = array();
    private $items = array();
    private $contractnumber;

    public function getData($items, $data, $contractnumber){
        $this->data = $data;
        $this->items = $items;
        $this->contractnumber = $contractnumber;
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
        $this->Cell(45, 10, 'Firma des Kunden', 0, 0, '', false, '');
        $this->Ln(12);
        $this->MultiCell(80, 5, ucfirst($this->data['gender']) . ' ' . utf8_decode($this->data['name']) . ' ' . utf8_decode($this->data['lastname']) . "\n" . ucfirst(utf8_decode($this->data['street'])) . ' ' . utf8_decode($this->data['nr']) . "\n" . utf8_decode($this->data['postcode']) . ' ' . ucfirst(utf8_decode($this->data['city'])), 0, '', false);
        $this->SetXY(140, 52);
        $this->MultiCell(80, 5, utf8_decode('Engelstein UG'."\n".'(Haftungsunbeschränkt)'."\n".''."\n".'Salomonstraße 26 - 28'."\n".'04103 Leipzig'."\n".''."\n".'Telefon: 0341 / 99 38 38 70'."\n".'E-Mail: info@engelstein.de'), 0, '', false);
        $this->SetX(10);
        $this->ln(10);
        $this->SetFontSize(16);
        $this->Cell(45, 10, utf8_decode('Rechnung'), 0, 0, '', false, '');
        $this->ln(8);
        $this->SetFontSize(10);
        $this->Cell(45, 10, utf8_decode('Rechnungsdatum: 01.07.14 | Rechnungsnummer: ' . $this->contractnumber . ' | Fälligkeitsdatum: zwei Wochen nach Erhalt der Rechnung'), 0, 0, '', false, '');
        $this->headerY = $this->GetY();
    }

    public function TableBody($header = array('Position', 'Menge', 'Artikel / Leistung', 'Einzelpreis', 'Gesamtpreis')){
        $this->SetY($this->headerY);
        $this->ln(10);

        // Column widths
        $w = array(20, 20, 95, 30, 30);
        // Header
        $this->SetTextColor(255,255,255);
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],7,$header[$i],1,0,'L', '#000');
        $this->Ln();
        // Data
        $this->SetTextColor(0,0,0);
        $count = 1;
        $result = 0;
        foreach($this->items as $row)
        {
            $this->Cell($w[0],12,$count,'B');
            $this->Cell($w[1],12,$row['count'],'B');
            $this->Cell($w[2],12,utf8_decode(@$row['item']) . ', ' . utf8_decode(@$row['style']) . ', ' . utf8_decode(@$row['color']),'B');
            $this->Cell($w[3],12,number_format(($row['price'] * ($row['count'] / 2) / $row['count']), 2) . ' ' . chr(128),'B',0,'C');
            $this->Cell($w[4],12,number_format($row['price'] * ($row['count'] / 2), 2). ' ' . chr(128),'B',0,'C');
            $this->Ln();
            $count++;
            $result += $row['price'] * ($row['count'] / 2);
        }
        // Closing line
        $this->Cell(array_sum($w),0,'','T');
        $this->headerY = $this->GetY();
        $this->ln(15);
        $this->SetXY(145, $this->headerY + 10);
        $this->Cell(45, 10, 'Gesamt:                 ' . number_format($result, 2) . ' '.chr(128), 0, 0, '', false, '');
        $this->ln(8);
        $this->SetX(145);
        $this->Cell(45, 10, 'MwSt: 19%             ' . number_format($result * 0.19, 2) . ' '.chr(128), 0, 0, '', false, '');


    }

    public function Footer(){
        $this->SetY(-60);
        // Arial italic 8
        $this->SetFont('Arial','',12);
        // Page number
        $this->SetFont('Arial', 'b', 12);
        $this->Cell(45, 10, 'Zahlungskonditionen:', 0, 0, '', false, '');
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 10, 'Sofort ohne Abzug '."\r", 0, 0, '', false, '');
        $this->Ln(6);
        $this->Cell(60, 10, utf8_decode('Wir bedanken uns für ihren Auftrag und Ihr Vertrauen in unsere Arbeit.'), 0, 0, '', false, '');
        $this->Ln(10);
        $this->SetX(10);
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(60, 5, utf8_decode('Engelstein UG (haftungsbeschränkt)'."\n".'Salomonstraße 26 - 28'."\n".'04103 Leipzig'), 0, '', false);
        $this->SetXY(85, -43);
        $this->MultiCell(60, 5, utf8_decode('Geschäftsführung: Ronny Stein.'."\n".'Entwicklung: Thomas Engelmann'."\n".'Organisation: Stephan Graupner'), 0, 'C', false);
        $this->SetXY(150, -43);
        $this->MultiCell(50, 5, utf8_decode('Registergericht Leipzig.'."\n".'HBR: 29 377'."\n".'Ust.IdNr: DE 2894 09890'), 0, 'R', false);
        $this->Ln(5);
        $this->SetTextColor(255,255,255);
        $this->Cell(0, 6, '                                   IBAN: DE77 8604 0000 0371 1454 00                BIC: COBADEFF 860', 0, 0, '', '#000', '');
        $this->SetXY(165, -15);
        $this->SetTextColor(0,0,0);
        $this->Cell(0,10,'Seite '.$this->PageNo().' von {nb}',0,0,'C');
    }

}