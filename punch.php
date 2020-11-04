<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thinh Hoang
 * Date: 07/11/2016
 * Time: 21:11
 */

error_reporting(0);

require_once "tcpdf/tcpdf.php";
// require_once "../../tcpdf/tcpdf_barcodes_1d.php";

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('LifePlanner');
$pdf->SetTitle('LifePlanner Punch Card');
$pdf->SetSubject('LifePlanner Punch Card');
$pdf->SetKeywords('');
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
$pdf->SetFont('freesans');

// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

// set header and footer fonts
// $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
// $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(15, 15, 15);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->setCellHeightRatio(1.25);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('freesans', '', 12);

// add a page
$pdf->AddPage();

// ----------------------------------------------------------
// Read XLSX file

include "simplexlsx.php";

$html = '
<div style="text-align: center; font-weight: bold">THE LIFEPLANNER PROGRAM<br>
PUNCH CARD REGISTRATION ACKNOWLEDGEMENT SHEET</div>
<div></div>
<table>
<tr><td>Name: <span style="font-weight: bold">HOANG DINH THINH</span></td><td>Student ID: <span style="font-weight: bold">41303880</span></td></tr>
<tr><td>Printing code: <span style="font-weight: bold">'.strtoupper($printCode).'</span></td><td>Date of printing: <span style="font-weight: bold">'.date("d/m/Y H:i:s").'</span></td></tr>
</table>
<div></div>
<table cellspacing="0" cellpadding="1" border="1">
<tr style="font-weight: bold"><td width="20%">Start</td><td width="20%">Duration</td><td width="20%">Paid</td><td width="40%">Task name</td></tr>
';

$todayEarned = 0;
$rowNum = 0;

$db = new mysqli("localhost","root","","lifeplanner") or die ("Can not connect to database!");
$db->set_charset("utf8");

if ( $xlsx = SimpleXLSX::parse('today.xlsx') ) {
	foreach( $xlsx->rows() as $r ) {
        if ($r[0] != 'Total' && $rowNum>0) {
            // Register into the database
            $exist = $db->query("SELECT time FROM punchcards WHERE time='".$r[2]."'");
            if ($exist->num_rows == 0)
            {
                $db->query("INSERT INTO punchcards (time, duration, paid, taskname) VALUES ('$r[2]','$r[4]','$r[5]','$r[6]')");
                $html.= '<tr><td>'.$r[2].'</td><td>'.$r[4].'</td><td>'.number_format((float)$r[5], 2, '.', '').'</td><td>'.$r[6].'</td></tr>';
                $todayEarned += $r[5];            
            }

        }
        $rowNum++;
	}	
} else {
	echo SimpleXLSX::parseError();
}

$result = $db->query("SELECT SUM(paid) FROM punchcards WHERE MONTH(time) = MONTH(CURRENT_DATE()) AND YEAR(time) = YEAR(CURRENT_DATE())");
$totalOfMonthRow = $result->fetch_row();

$html .= '</table><div></div><div>Total amount earned: '.number_format((float)$todayEarned, 2, '.', '').'</div>';
$html .= '<div>Total this month: '.number_format((float)$totalOfMonthRow[0], 2, '.', '').'</div>';

// output the HTML content
$pdf->writeHTML($html, true, false, false, false, '');

$pdf->lastPage();

$pdf->setPage(1);
$pdf->setXY(15,15);
// $pdf->write1DBarcode($printCode, 'C128', '','','40','10');

$pdf->Output('punch.pdf', 'I');

// echo $html;

?>