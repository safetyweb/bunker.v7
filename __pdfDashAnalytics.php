<?php
//include "../_system/_functionsMain.php";

error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);


require_once("pdfComponente/autoload.inc.php");
use Dompdf\Dompdf;
$dompdf = new DOMPDF();


$filename = "rel";

$html = "";

ob_start();
include "action.php";
$html = ob_get_clean();

//$html .= $_SESSION["dashAnalytics"];

echo $html;exit;

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');


$dompdf->render();
$font = $dompdf->getFontMetrics()->get_font("helvetica", "");
$dompdf->getCanvas()->page_text(35, 810, utf8_encode("Emissão: ").date("d/m/Y H:i:s").str_repeat(" ", 160).utf8_encode("Página")." {PAGE_NUM} de {PAGE_COUNT}", $font, 8, array(0,0,0));

//if (@$_GET["baixa"] == "S"){
//	$pdf = $dompdf->output();
//	file_put_contents("arquivos/".$filename.".pdf", $pdf);
//}else{
	$dompdf->stream($filename.".pdf", array("Attachment" => false));
//}
