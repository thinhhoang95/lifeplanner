<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thinh Hoang
 * Date: 07/11/2016
 * Time: 21:11
 */

require_once "../../tcpdf/tcpdf.php";
require_once "../../tcpdf/tcpdf_barcodes_1d.php";

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('LifePlanner');
$pdf->SetTitle('LifePlanner Workplan');
$pdf->SetSubject('LifePlanner Workplan');
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

$printCode=substr(md5(date('d/m/Y H:i:s')),strlen(date('d/m/Y H:i:s')-5),5);

$html = '
<div style="text-align: center; font-weight: bold">THE LIFEPLANNER PROGRAM<br>
WORKPLAN REGISTRATION SHEET</div>
<div></div>
<table>
<tr><td>Name: <span style="font-weight: bold">HOANG DINH THINH</span></td><td>Student ID: <span style="font-weight: bold">41303880</span></td></tr>
<tr><td>Printing code: <span style="font-weight: bold">'.strtoupper($printCode).'</span></td><td>Date of printing: <span style="font-weight: bold">'.date("d/m/Y H:i:s").'</span></td></tr>
</table>
<div></div>
<table cellspacing="0" cellpadding="1" border="1">
<tr style="font-weight: bold"><td width="50%">Subject</td><td width="10%">Units</td><td width="40%">Completion date</td></tr>
';

include_once "../../bin/class/datatier/workplaner.php";
$workplaner = new \datatier\workplaner();
$listOfPlans = $workplaner->lastWeekWorkplanBuild();
foreach ($listOfPlans as $item) {
    $html.= '<tr>';
    $html.= '<td id="work_name" style="font-style: italic; font-weight: bold">' . $item->work_name . '</td><td id="time_units">' . $item->time_units . '</td><td id="reg_date">' . $item->completion_date . '</td>';
    $html.= "</tr>";
    $html.= "<tr>";
    $html.= '<td colspan="3">'.str_replace(PHP_EOL,'<br>',$item->mission).'</td>';
    $html.= "</tr>";
}

$html.='</table>';
$html.='<p></p>';
/*$html.='
<table cellspacing="0" cellpadding="1" border="1">
<tr style="font-weight: bold"><td width="50%">Subject</td><td width="25%">Remaining units of week</td><td width="25%">Total units of week</td></tr>
';
$listOfStats = $workplaner->statsLastWeekBuild();
foreach ($listOfStats as $item) {
    $html.= '<tr>';
    $html.= '<td style="font-style: italic; font-weight: bold">' . $item["work_name"] . '</td><td>' . $item["remaining_units_in_week"] . '</td><td>' . $item["units_per_week"] . '</td>';
    $html.= "</tr>";
}
$html.='</table>';*/

$html.='
<div></div>
<div align="right">Signature of participant</div>
';

// output the HTML content
$pdf->writeHTML($html, true, false, false, false, '');

$pdf->lastPage();


$pdf->setPage(1);
$pdf->setXY(15,15);
$pdf->write1DBarcode($printCode, 'C128', '','','40','10');

$pdf->Output('workplan.pdf', 'I');

// echo $html;

?>