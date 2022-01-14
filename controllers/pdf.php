<?php 
require "../ticker_pdf.php";
$html=ob_get_clean();
//Obtiene la libreria
require_once '../librerias/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
//Inicia dompdf
$pdf=new Dompdf();
//$html=ob_get_clean();
$pdf->set_option('isHtml5ParserEnabled',true);
$pdf->set_option('isRemoteEnabled',true);
$pdf->loadHtml($html);
$pdf->set_paper('A4','portrait'); //Vertical - portrait; Horizontal - landscape;
$pdf->render();
$pdf->stream("COMPRA-GOV-".time(),array('Attachment'=>1));
?>