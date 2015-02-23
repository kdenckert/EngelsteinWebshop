<?php
/**
 * Created by PhpStorm.
 * User: MaDBook
 * Date: 29.10.2014
 * Time: 17:16
 * Statuscodes: 1 - Entgegengenommen; 2 - Bezahlt; 3 - Fertiggestellt; 4 - Versandt
 */
require_once 'Model.php';
require_once '../classes/model/PDFInvoiceModel.php';


class OrderModel extends Model{

    // Configuration
    private $lastcontract;

    public function __construct(){
        parent::__construct();
    }

    public function saveOrder($item, $data){
        $sql = 'INSERT INTO bs_contract_id (nullvalue) VALUES (:nullvalue)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
               ':nullvalue' => NULL
           )
        );

        $sql = 'SELECT * FROM bs_contract_id ORDER BY contract_id DESC LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $this->lastcontract = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Insert Userdata in Table Customers UPDATE wenn Eintrag existiert
        $sql = '
                INSERT INTO bs_customers (gender, name, lastname, email, city, street, nr, postcode, cellphone)
                VALUE (:gender, :name, :lastname, :email, :city, :street, :nr, :postcode, :cellphone)
                ON DUPLICATE KEY UPDATE gender = :gender, name = :name, lastname = :lastname, email = :email, city = :city, street = :street, nr = :nr, postcode = :postcode, cellphone = :cellphone';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
                ':gender' => $data['gender'],
                ':name' => $data['name'],
                ':lastname' => $data['lastname'],
                ':email' => $data['email'],
                ':city' => $data['city'],
                ':street' => $data['street'],
                ':nr' => $data['nr'],
                ':postcode' => $data['postcode'],
                ':cellphone' => $data['cellphone'],
           )
        );


        // Hole Kundenid nachdem sie angelegt wurde.
        $sql = 'SELECT custID FROM bs_customers WHERE email = :email;';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':email' => $data['email']
           )
        );
        $custID = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Insert Orderdata in table orders
        if($data['alt_on'] == 'on'){
            $sqla = 'INSERT INTO bs_orders (article, count, type, status, customerID, price, color, statusModified, contractID, alt_data, alt_gender, alt_name, alt_lastname, alt_street, alt_nr, alt_postcode, alt_city)
               VALUE (:article, :count, :type, :status, :customerID, :price, :color, :statusModified, :contractID, :alt_data, :alt_gender, :alt_name, :alt_lastname, :alt_street, :alt_nr, :alt_postcode, :alt_city)';
        }else{
            $sqla = 'INSERT INTO bs_orders (article, count, type, status, customerID, price, color, statusModified, contractID, alt_data)
               VALUE (:article, :count, :type, :status, :customerID, :price, :color, :statusModified, :contractID, :alt_data)';
        }
        $stmta = $this->db->prepare($sqla);

        $sqlb = 'SELECT * FROM bs_serialnumbers ORDER BY serial_id DESC LIMIT 1';
        $stmtb = $this->db->prepare($sqlb);

        $sqlc = 'SELECT * FROM bs_orders ORDER BY ordersID DESC LIMIT 1';
        $stmtc = $this->db->prepare($sqlc);

        foreach($item as $k => $v){
            if($data['alt_on'] == 'on'){
                $stmta->execute(
                    array(
                        ':contractID' => $this->lastcontract[0]['contract_id'],
                        ':article' => $v['item'],
                        ':count' => $v['count'],
                        ':type' => $v['style'],
                        ':status' => 1,
                        ':customerID' => $custID[0]['custID'],
                        ':price' => $v['price'],
                        ':color' => $v['color'],
                        ':statusModified' => date("Y-m-d H:i:s"),
                        'alt_data' => 1,
                        'alt_gender' => $data['alt_gender'],
                        'alt_name' => $data['alt_name'],
                        'alt_lastname' => $data['alt_lastname'],
                        'alt_street' => $data['alt_street'],
                        'alt_nr' => $data['alt_nr'],
                        'alt_postcode' => $data['alt_postcode'],
                        'alt_city' => $data['alt_city'],
                    )
                );
              }else{
                $stmta->execute(
                    array(
                        ':contractID' => $this->lastcontract[0]['contract_id'],
                        ':article' => $v['item'],
                        ':count' => $v['count'],
                        ':type' => $v['style'],
                        ':status' => 1,
                        ':customerID' => $custID[0]['custID'],
                        ':price' => $v['price'],
                        ':color' => $v['color'],
                        ':statusModified' => date("Y-m-d H:i:s"),
                        'alt_data' => 0
                    )
                );
            }


            // get last Serialnumber
            $stmtb->execute();
            $lastserial = $stmtb->fetchAll(PDO::FETCH_ASSOC);

            // Get current Orders ID
            $stmtc->execute();
            $lastorderid = $stmtc->fetchAll(PDO::FETCH_ASSOC);

            $sqld = 'UPDATE bs_orders set md5_primary = :md5_primary WHERE ordersID = :ordersID';
            $stmtd = $this->db->prepare($sqld);
            $stmtd->execute(
               array(
                  'ordersID' => $lastorderid[0]['ordersID'],
                  'md5_primary' => md5("badass" . "§!$%!__.&/ESforPresidenkackastinkebärt!" . $custID[0]['custID'])
               )
            );

            // Insert as much serialnumbers as Ordercount
            $sql = 'INSERT INTO bs_serialnumbers
               (serial_id, orders_id)
               VALUES
               (:serial_id, :orders_id)';
            $stmt = $this->db->prepare($sql);
            $count = 1;

            for ($i = 0; $i < $v['count']; $i++) {
                $stmt->execute(
                   array(
                      ':serial_id' => $lastserial[0]['serial_id'] + $count,
                      ':orders_id' => $lastorderid[0]['ordersID']
                   )
                );
                $count++;
            }
        }
        // Insert OrderPDF into Database
        $sql = 'INSERT INTO bs_generated_pdfs
               (type, url, orderID)
               VALUES
               (:type, :url, :orderID)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
           array(
              ':type' => 'order',
              ':url' => 'O_' . $this->contractnumber . $this->lastcontract[0]['contract_id'] . '_' . $data['lastname'] . '_' . $data['name'] . '.pdf',
              ':orderID' => $this->lastcontract[0]['contract_id']
           )
        );
        $stmt->execute(
           array(
              ':type' => 'invoice',
              ':url' => 'I_' . $this->contractnumber . $this->lastcontract[0]['contract_id'] . '_' . $data['lastname'] . '_' . $data['name'] . '.pdf',
              ':orderID' => $this->lastcontract[0]['contract_id']
           )
        );

        $pdf = $this->generateInvoicePDF($item, $data, $this->lastcontract[0]['contract_id']);
        $this->sendConfirmationMail($data, $item, $this->contractnumber.$this->lastcontract[0]['contract_id'], $pdf, 'I_' . $this->contractnumber . $this->lastcontract[0]['contract_id'] . '_' . $data['lastname'] . '_' . $data['name'] . '.pdf');

    }


    public function generateInvoicePDF($item, $data, $invoiceCount){
        $this->PIM = new PDFInvoiceModel();
        $this->PIM->getData($item, $data, $this->contractnumber.$invoiceCount);
        $this->PIM->AliasNbPages();
        $this->PIM->AddPage();
        $this->PIM->SetFont('Arial','',12);
        $this->PIM->TableBody();
        $this->PIM->Output($_SERVER['DOCUMENT_ROOT'] . '/public/pdfs/I_' . $this->contractnumber . $invoiceCount . '_' . $data['lastname'] . '_' . $data['name'] . '.pdf', 'F');
        return $this->PIM->Output($_SERVER['DOCUMENT_ROOT'] . '/public/pdfs/I_' . $this->contractnumber . $invoiceCount . '_' . $data['lastname'] . '_' . $data['name'] . '.pdf', 'S');

        //$this->PIM->Output();
    }

    public function sendConfirmationMail($customer, $items, $contractnr, $pdf, $file_name){
        $mailtext = '
            <html>
            <head>
                <meta charset="utf-8" />
                <title>Bestellbestätigung</title>
            </head>

            <body>

            <h2>Auftragsbestätigung</h2>

            <p>Sehr geehrter '.ucfirst($customer['gender']).'  '.$customer['lastname'].',<br /><br />
            herzlichen Glückwunsch, dass Sie sich für unsere Produkte entschieden haben.
            Mit dieser eMail gehen wir nun einen gemeinsamen Vertrag ein.
            Alles Nähere können Sie noch einmal in den angehängten Dokumenten nachlesen.
            Sobald wir in den nächsten 14 Tagen Ihre Zahlung erhalten haben, werden wir mit unserer Hände Arbeit Ihr Abhörkunstwerk nach Ihren Wünschen erschaffen.
            Bitte beachten Sie nochmals, dass diese Handarbeit ihre Zeit dauert.
            Hier können Sie uns und dem Fortschritt ihrer Lautsprecher virtuell auf die Finger schauen.
            Im folgenden Teil erhalten Sie eine detaillierte Auflistung aller Vertragsbestandteile,
            bitte prüfen Sie alle Konditionen genau nach.
            </p>
            <p>
                Auftrags-/Rechnungsnummer: '.$contractnr.' <br />
                Datum: '.date("d-m-Y H:i:s").'
            </p>

            <table border="1">
              <tr>
                <td>Posten</td>
                <td>Modell</td>
                <td>Ausstattung</td>
                <td>SN</td>
                <td>Anzahl</td>
                <td>Einzelpreis</td>
              </tr>
              ';
            $counter = 1;
            $result = 0;
            foreach($items as $val){
                $mailtext .= '<tr>
                        <td>'.$counter.'</td>
                        <td>'.$val['item'].'</td>
                        <td>'.$val['style'].', '.$val['color'].'</td>
                        <td>asdasd</td>
                        <td>'.$val['count'].'</td>
                        <td>'.number_format(($val['price'] * ($val['count'] / 2) / $val['count']), 2).' €</td>
                   </tr>';
                $counter++;
                $result += $val['price'] * ($val['count'] / 2);

            }
            $mailtext .= '
            <tr>
                <td></td>
                <td>MwSt.</td>
                <td></td>
                <td></td>
                <td>19%</td>
                <td>'.number_format($result * 0.19, 2).' €</td>
              </tr>
              <tr>
                <td></td>
                <td>Versand</td>
                <td></td>
                <td></td>
                <td></td>
                <td>Kostenlos</td>
              </tr>
              <tr>
                <td></td>
                <td>Gesamtpreis</td>
                <td></td>
                <td></td>
                <td></td>
                <td>'.number_format($result, 2).' €</td>
              </tr>
            </table>

            <p>Bitte überweisen Sie uns den Gesamtbetrag binnen 14 Tagen auf<br /><br />
                Kontoname<br />
                IBAN<br />
                Nochmal Wert<br />
                Betreff: Auftrags-/Rechnungsnummer<br /><br />
            </p>
            <h3>Ihre Rechnungs / Versandadresse</h3>
            <p>' . ucfirst($customer['gender']) . ' ' . ucfirst($customer['lastname']) . '<br />
                ' . ucfirst($customer['street']) . ' ' . ucfirst($customer['nr']) . '<br />
                ' . $customer['postcode'] . ' ' . ucfirst($customer['city']) . '<br />
                Tel.: ' . $customer['phone'] . '<br />
                eMail: ' . $customer['email'] . '<br />
            </p>';
            if($customer['alt_data'] == 1){
                $mailtext .='
                <h3>Alternative Versandadresse</h3>
                <p>' . ucfirst($customer['alt_gender']) . ' ' . ucfirst($customer['alt_lastname']) . '<br />
                    ' . ucfirst($customer['alt_street']) . ' ' . ucfirst($customer['alt_nr']) . '<br />
                    ' . ucfirst($customer['alt_postcode']) . ' ' . ucfirst($customer['alt_city']) . '<br />
                </p>';
            }
            $mailtext .='
            <h3>Vertragspartner und Kontakt:</h3>
            <p>Engelstein UG (haftungsbeschränkt)<br />
                Geschäftsführer: Ronny Stein<br />
                Entwicklungsingenieur: Thomas Engelmann<br />
                Salomonstr. 26-28<br />
                04103 Leipzig<br />
                Tel.: 0341/99 38 38 70<br />
                eMail: info@engelstein.de<br />
            </p>
            <p>
                Versand:<br />
                -	Versicherter DHL Standard-Versand<br /><br />
                Sobald Ihre Lieblinge versandfertig sind erhalten Sie eine weitere Mail mit der Sendungsverfolgung und ihren Rechnungsunterlagen.<br />
                Mit freundlichen Grüßen<br />
                Ihr Engelstein Team.
            </p>
            <hr/>
            <h2>Allgemeine Geschäftsbedingungen der Firma Engelstein UG (haftungsbeschränkt)</h2>
            <p>
                §1 Geltung gegenüber Unternehmern und Begriffsdefinitionen <br/>
                (1) Die nachfolgenden Allgemeinen Geschäftsbedingungen gelten für alle Lieferungen zwischen uns und einem Verbraucher in ihrer zum Zeitpunkt der Bestellung gültigen Fassung, die Ihnen während des Bestellvorgangs und mit der Bestellbestätigung mitgeteilt werden.
            </p>
            <p>
                Verbraucher ist jede natürliche Person, die ein Rechtsgeschäft zu Zwecken abschließt, die überwiegend weder ihrer gewerblichen noch ihrer selbständigen beruflichen Tätigkeit zugerechnet werden können (§ 13 BGB).
                §2 Zustandekommen eines Vertrages, Speicherung des Vertragstextes <br/>
                (1) Die folgenden Regelungen über den Vertragsabschluss gelten für Bestellungen über unseren Internetshop http://www.engelstein.de.
            </p>
            <p>
                (2) Im Falle des Vertragsschlusses kommt der Vertrag mit
            </p>
            <p>
                Engelstein UG (haftungsbeschränkt)<br/>
                Ronny Stein <br/>
                Salomonstr. 26-28 <br/>
                D-04103 Leipzig <br/>
                Registergericht Leipzig, HBR: 29377 <br/>
            </p>
            <p>
                zustande.
            </p>
            <p>
                (3) Die Präsentation der Waren in unserem Internetshop stellt kein rechtlich bindendes Vertragsangebot unsererseits dar, sondern sind nur eine unverbindliche Aufforderungen an den Verbraucher, Waren zu bestellen. Mit der Bestellung der gewünschten Ware gibt der Verbraucher ein für ihn verbindliches Angebot auf Abschluss eines Kaufvertrages ab.
            <br/> <br/>
                (4) Bei Eingang einer Bestellung in unserem Internetshop gelten folgende Regelungen: Der Verbraucher gibt ein bindendes Vertragsangebot ab, indem er die in unserem Internetshop vorgesehene Bestellprozedur erfolgreich durchläuft.
            </p>
            <p>
                Die Bestellung erfolgt in folgenden Schritten: <br/>
                1) Auswahl der gewünschten Ware <br/>
                2) Eröffnung des verbindlichen Angebots mit dem Klicken auf "Bestellen" <br/>
                3) Eingabe der persönlichen Daten <br/>
                4) Eingabe der Rechnungs- und/oder Versandadresse <br/>
                5) Eingabe der Zahlungsart <br/>
                6) Überprüfung der Daten und Bestätigung der AGBs und der Widerrufsbelehrung <br/>
                7) Bestätigung des verbindlichen Vertrages durch klicken auf "kostenpflichtig bestellen" <br/>
                <br/>
                Der Verbraucher kann vor dem verbindlichen Absenden der Bestellung durch den Button „Prüfen der Angaben“ wieder zu der Internetseite gelangen, auf der die Angaben des Kunden erfasst werden und Eingabefehler berichtigen bzw. durch Schließen des Internetbrowsers den Bestellvorgang abbrechen. Wir bestätigen den Eingang der Bestellung unmittelbar durch eine automatisch generierte E-Mail („Bestellbestätigung“). Mit dieser nehmen wir Ihr Angebot an.
            </p>
            <p>
                (5) Speicherung des Vertragstextes bei Bestellungen über unseren Internetshop: Wir senden Ihnen die Bestelldaten und unsere AGB per E-Mail zu. Die AGB können Sie jederzeit auch unter http://www.engelstein.de/agb.html einsehen. Ihre Bestelldaten sind aus Sicherheitsgründen nicht mehr über das Internet zugänglich.
                §3 Preise, Versandkosten, Zahlung, Fälligkeit <br/><br/>
                (1) Die angegebenen Preise enthalten die gesetzliche Umsatzsteuer und sonstige Preisbestandteile. Hinzu kommen etwaige Versandkosten.
                    <br/><br/>
                (2) Der Verbraucher hat die Möglichkeit der Zahlung per Vorkasse .
                    <br/><br/>
                (3) Hat der Verbraucher die Zahlung per Vorkasse gewählt, so verpflichtet er sich, den Kaufpreis unverzüglich nach Vertragsschluss zu zahlen.
                §4 Lieferung<br/><br/>
                (1) Sofern wir dies in der Produktbeschreibung nicht deutlich anders angegeben haben, werden alle von uns angebotenen Artikel nach Kundenauftrag gefertigt. Die Lieferung erfolgt hier spätesten innerhalb von 20 Werktagen. Dabei beginnt die Frist für die Lieferung im Falle der Zahlung per Vorkasse am Tag nach Zahlungsauftrag an die mit der Überweisung beauftragte Bank und bei allen anderen Zahlungsarten am Tag nach Vertragsschluss zu laufen. Fällt das Fristende auf einen Samstag, Sonntag oder gesetzlichen Feiertag am Lieferort, so endet die Frist am nächsten Werktag.
                    <br/><br/>
                (2) Die Gefahr des zufälligen Untergangs und der zufälligen Verschlechterung der verkauften Sache geht auch beim Versendungskauf erst mit der Übergabe der Sache an den Käufer auf diesen über.
                §5 Eigentumsvorbehalt<br/><br/>
                Wir behalten uns das Eigentum an der Ware bis zur vollständigen Bezahlung des Kaufpreises vor.
            </p>
            <h2>§6 Widerrufsrecht des Kunden als Verbraucher:</h2>
            <p>
                Widerrufsrecht für Verbraucher
                <br/><br/>
                Verbrauchern steht ein Widerrufsrecht nach folgender Maßgabe zu, wobei Verbraucher jede natürliche Person ist, die ein Rechtsgeschäft zu Zwecken abschließt, die überwiegend weder ihrer gewerblichen noch ihrer selbständigen beruflichen Tätigkeit zugerechnet werden können:
                Widerrufsbelehrung
                <br/><br/>
                1. Widerrufsrecht
                Sie haben das Recht, binnen vierzehn Tagen ohne Angabe von Gründen diesen Vertrag zu widerrufen. Die Widerrufsfrist beträgt vierzehn Tage ab dem Tag an dem Sie oder
                ein von Ihnen benannter Dritter, der nicht der Beförderer ist, die Ware in Besitz
                genommen haben bzw. hat. Sollte Ihre Bestellung in Teilen geliefert werden, gilt der Tag des Erhalts der letzten Teillieferung. Um Ihr Widerrufsrecht auszuüben, müssen Sie uns Der Firma: Engelstein UG (haftungsbeschränkt) • Salomonstr. 26-28, 04103 Leipzig (Mail: info@engelstein.de) mittels einer eindeutigen Erklärung (z.B. ein mit der Post versandter Brief oder E-Mail) über Ihren Entschluss, diesen Vertrag zu widerrufen, informieren. Sie können dafür das beigefügte Muster-Widerrufsformular (s. Anhang 1) verwenden, das jedoch nicht vorgeschrieben ist. Zur Wahrung der Widerrufsfrist reicht es aus, dass Sie die Mitteilung über die Ausübung des Widerrufsrechts vor Ablauf der Widerrufsfrist absenden.
                <br/><br/>
                2. Folgen des Widerrufs
                Wenn Sie diesen Vertrag widerrufen, haben wir Ihnen alle Zahlungen, die wir von Ihnen erhalten haben, einschließlich der Lieferkosten (mit Ausnahme der zusätzlichen Kosten, die sich daraus ergeben, dass Sie eine andere Art der Lieferung als die von uns angebotene, günstigste Standardlieferung gewählt haben), unverzüglich und spätestens binnen vierzehn Tagen ab dem Tag zurückzuzahlen, an dem die Mitteilung über Ihren Widerruf dieses Vertrags bei uns eingegangen ist. Für diese Rückzahlung verwenden wir dasselbe Zahlungsmittel, das Sie bei der ursprünglichen Transaktion eingesetzt haben, es sei denn, mit Ihnen wurde ausdrücklich etwas anderes vereinbart; in keinem Fall werden Ihnen wegen dieser Rückzahlung Entgelte berechnet. Bitte geben Sie bei der Zahlungsart Überweisungen im Rahmen Ihrer Widerrufserklärung Ihre vollständigen Kontodaten ggf. erneut an. Wir können die Rückzahlung verweigern, bis wir die Waren wieder zurückerhalten haben oder bis Sie den Nachweis erbracht haben, dass Sie die Waren zurückgesandt haben, je nachdem, welches der frühere Zeitpunkt ist. Sie haben die Waren unverzüglich und in jedem Fall spätestens binnen vierzehn Tagen ab dem Tag, an dem Sie uns über den Widerruf dieses Vertrags unterrichten, an  uns: Engelstein UG (haftungsbeschränkt) • Salomonstr. 26-28, 04103 Leipzig zurückzusenden oder zu übergeben. Die Frist ist gewahrt, wenn Sie die Waren vor Ablauf der Frist von vierzehn Tagen absenden. Sie tragen die unmittelbaren Kosten der Rücksendung der Waren. Verwenden Sie die original Versandverpackung. Sie müssen für einen etwaigen Wertverlust der Waren nur aufkommen, wenn dieser Wertverlust auf einen zur Prüfung der Beschaffenheit, Eigenschaften und Funktionsweise der Waren nicht notwendigen Umgang mit ihnen zurückzuführen ist.
                <br/><br/>
                3. Ausschluss des Widerrufsrechts
                Das Widerrufsrecht besteht nicht bei Verträgen zur Lieferung von Waren, die nach Kundenspezifikation angefertigt werden oder eindeutig auf die persönlichen Bedürfnisse zugeschnitten sind. Dies gilt insbesondere für die Auswahl besonderer Lackierungen der Gehäuse. Da diese Produkte nicht vorgefertigt werden können, sondern auf Kundenwunsch hergestellt werden. Die vom Widerrufsrecht ausgeschlossenen Produkte, die speziell nach Kundenspezifikationen gefertigt werden, sind in der Shopauswahl mit der Bezeichnung „individuell*“ gekennzeichnet. Bitte kontaktieren Sie uns bei einem Widerrufswunsch trotzdem. Wir werden uns je nach Fall um eine Kulanzregelung bemühen.
                <br /><a href="http://badass.engelstein.de/public/assets/Widerrufsformular.pdf" target="_blank">Zum Widerrufsformular</a>
            </p>
            </body>
            </html>
        ';


// email stuff (change data below)
        $to = $customer['email'];
        $from = "info@engelstein.de";
        $subject = "Bestätigung ihrer Bestellung auf Engelstein.de";
        $antwortan  = "info@engelstein.de";


// a random hash will be necessary to send mixed content
        $separator = md5(time());

// carriage return type (we use a PHP end of line constant)
        $eol = PHP_EOL;

// attachment name
        $filename = $file_name;

// encode data (puts attachment in proper format)
        $attachment = chunk_split(base64_encode($pdf));

// main header
        $headers  = "From: ".$from.$eol;
        $headers .= "Reply-To: $antwortan\r\n";
        $headers .= "MIME-Version: 1.0".$eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"";

// no more headers after this, we start the body! //

        $body = "--".$separator.$eol;
        $body .= "Content-Transfer-Encoding: 7bit".$eol.$eol;
        $body .= "This is a MIME encoded message.".$eol;

// message
        $body .= "--".$separator.$eol;
        $body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
        $body .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
        $body .= $mailtext.$eol;

// attachment
        $body .= "--".$separator.$eol;
        $body .= "Content-Type: application/octet-stream; name=\"".$filename."\"".$eol;
        $body .= "Content-Transfer-Encoding: base64".$eol;
        $body .= "Content-Disposition: attachment".$eol.$eol;
        $body .= $attachment.$eol;
        $body .= "--".$separator."--";

// send message
        mail($to, $subject, $body, $headers);
    }

} 