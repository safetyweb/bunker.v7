<?php
//GERADOR FA TO BASE 64:  http://fa2png.io/
// normal - 30px / grande - 60px
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

include "../_system/_functionsMain.php";

require_once("../pdfComponente/autoload.inc.php");

use Dompdf\Dompdf;

$dompdf = new DOMPDF();

$dados = $_POST;
foreach ($dados as $dado => $valor) {
	if (!is_array($dado)) {
		$$dado = $valor;
	}
}


$filename = $titulo;



//echo "<pre>";
//print_r($dados);
//echo "</pre>";

$html = "<html>";


$html .= "<head>";

/*****SCRIPTS************************************************************************************/
$html .= <<<HTML
	<link href="../css/chosen-bootstrap.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="../css/fa5all.css" />
	<link href="../css/bootstrap.vertical-tabs.css" rel="stylesheet" />
	<link href="../css/bootstrap.flatly.min.css" rel="stylesheet">
	<link href="../css/bootstrap.flatly.min.AUX.css" rel="stylesheet">

	<style>
	@page{margin: 0.2in 0.5in 0.2in 0.5in;}
	.page_break { page-break-before: always; }
	html,body{font-size: 12px !important}
	.f30 {font-size: 30px !important;}
	.f26b{font-size: 26px !important;font-weight: bold !important;}
	.f21{font-size: 21px !important;}
	.f18{font-size: 18px !important;}
	.f17{font-size: 17px !important;}
	.f16{font-size: 16px !important;}
	.f15{font-size: 15px !important;}
	.f14{font-size: 14px !important;}
	.f13{font-size: 13px !important;}
	.f12{font-size: 12px !important;}
	.f11{font-size: 11px !important;}
	.f10{font-size: 10px !important;}

	small{
		font-size:16px !important;
	}
	h1, .h1, h2, .h2, h3, .h3 {
    	margin-top: 21px !important;
    	margin-bottom: 10.5px !important;
	}
	h3,.h3{
    	font-size: 26px !important;
	}
	h4, .h4 {
    	font-size: 19px !important;
	}
	h5, .h5 {
    	font-size: 15px !important;
	}
	h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
		font-family: "Lato","Helvetica Neue",Helvetica,Arial,sans-serif !important;
		font-weight: 400 !important;
		line-height: 1.1 !important;
		color: inherit !important;
	}
	h1 small, h2 small, h3 small, h4 small, h5 small, h6 small, .h1 small, .h2 small, .h3 small, .h4 small, .h5 small, .h6 small, h1 .small, h2 .small, h3 .small, h4 .small, h5 .small, h6 .small, .h1 .small, .h2 .small, .h3 .small, .h4 .small, .h5 .small, .h6 .small {
		font-weight: normal !important;
		line-height: 1 !important;
		color: #b4bcc2 !important;
	}
	.bg{
		background-color:#F8F8F8 !important;
		padding:3px !important;
	}
	.tb{
		border-spacing: 10px !important;
    	border-collapse: separate !important;
	}
	small, .small {
    	font-size: 86% !important;
	}
	hr{
		height:1px !important;
		margin:0 !important;
		padding:0 !important;
	}


	.panel{
		width: 100% !important;
		background-color: #fff !important;
		border: 1px solid #ECF0F1 !important;
		border-radius: 6px !important;
		text-align: center !important;
		display: inline-block !important;
		height: 130px !important;
		margin:10px;
	}
	.panel .panel-heading{
		background-color: #ECF0F1 !important;
		font-weight: bold !important;
		color: #2c3e50 !important;
		height:35px !important;
	}

	</style>
HTML;

$html .= "</head>";

$html .= "<body>";


/*******************************************************************************************/
$html .= "<h4 class='text-center'>$titulo</h4>";

$html .= "<table style='width:960px;'>";
$html .= "<tr>";
$html .= "<td style='width:50%;vertical-align: top;'>";

$html .= "<div style='margin:10px;background:#18BC9C;color:#FFF;width: 100%;border-radius: 10px;text-align: center;padding-top:120px;padding-bottom:80px;'>";

$html .= "<p><span style='font-size:32px !important'>R$</span> <span style='font-size:48px  !important'>$total_venda</span></p>";
$html .= "<p style='margin-top: -10px!important;' class='f14'>$total_venda_txt</p>";
$html .= "<br>";
$html .= "<p class='f14'>$cli_qtd_compras_txt</p>";
$html .= "<p class='f14'>$cli_qtd_inativo_txt</p>";
$html .= "<p class='f14'>$roi_txt</p>";

$html .= "</div>";

$html .= "</td>";
$html .= "<td style='width:50%;vertical-align: top;'>";

$html .= "<div style='margin:10px;'>";
$html .= "<img style='border-radius:10px;' src='data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAAA8AAD/4QMsaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA2LjAtYzAwMiA3OS4xNjQ0NjAsIDIwMjAvMDUvMTItMTY6MDQ6MTcgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCAyMS4yIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpFNUE2RkFFMzVDMjcxMUVCQjZGRkIwNTIxNzA5MzIzMCIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpFNUE2RkFFNDVDMjcxMUVCQjZGRkIwNTIxNzA5MzIzMCI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOkU1QTZGQUUxNUMyNzExRUJCNkZGQjA1MjE3MDkzMjMwIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkU1QTZGQUUyNUMyNzExRUJCNkZGQjA1MjE3MDkzMjMwIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+/+4ADkFkb2JlAGTAAAAAAf/bAIQABgQEBAUEBgUFBgkGBQYJCwgG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'>";
$html .= "<p class='f14' style='padding-top:5px;'><b>DETALHES DA CAMPANHA</b></p>";
$html .= "<hr>";

$html .= "<table style='width:100%'>";
$html .= "<tr>";
$html .= "<td style='width:50%;vertical-align: top;'>";
$html .= $camp_col1;
$html .= "</td>";
$html .= "<td style='width:50%;vertical-align: top;'>";
$html .= $camp_col2;
$html .= "</td>";
$html .= "</tr>";
$html .= "</table>";

$html .= "</div>";

$html .= "</td>";

$html .= "</tr>";
$html .= "</table>";

$html .= "<div class='page_break'></div>";

$html .= "<table style='width:1040px;'>";
$html .= "<tr>";
$html .= "<td style='width:25%;vertical-align: top;text-align:center;' rowspan=2>";

$html .= "<div class='panel'>";
$html .= "<div class='panel-heading'>Fat. Grupo Ação</div>";
$html .= "<div class='panel-body'>";
$html .= "<span class='f18'>R$ $vl_grupo_acao</span>";
$html .= "</div>";
$html .= "</div>";

$html .= "</td>";
$html .= "<td style='width:25%;vertical-align: top;text-align:center;'>";

$html .= "<div class='panel'>";
$html .= "<div class='panel-heading'>Fat. Grupo Controle</div>";
$html .= "<div class='panel-body'>";
$html .= "<span class='f18'>R$ $vl_grupo_controle</span>";
$html .= "</div>";
$html .= "</div>";

$html .= "</td>";
$html .= "<td style='width:25%;vertical-align: top;text-align:center;'>";

$html .= "<div class='panel'>";
$html .= "<div class='panel-heading'>Incremento<br>Margem Bruta</div>";
$html .= "<div class='panel-body'>";
$html .= "<span class='f18'>R$ $vl_incremento</span>";
$html .= "<br><span class='f14 text-muted'>$pc_incremento%</span>";
$html .= "</div>";
$html .= "</div>";

$html .= "</td>";
$html .= "<td style='width:25%;vertical-align: top;text-align:center;'>";

$html .= "<div class='panel'>";
$html .= "<div class='panel-heading'>Investimento Total</div>";
$html .= "<div class='panel-body'>";
$html .= "<span class='f18'>R$ $vl_invest</span>";
$html .= "<br><span class='f14 text-muted'>$pc_invest%</span>";
$html .= "</div>";
$html .= "</div>";

$html .= "</td>";

$html .= "</tr>";

$html .= "<tr>";
$html .= "<td style='width:25%;vertical-align: top;text-align:center;'>";

$html .= "<div class='panel'>";
$html .= "<div class='panel-heading' style='background:#FFF !important;'>Resultado</div>";
$html .= "<div class='panel-body'>";
$html .= "<span class='f18'>R$ $vl_resultado</span>";
$html .= "<br><span class='f14 text-muted'>$pc_resultado%</span>";
$html .= "</div>";
$html .= "</div>";

$html .= "</td>";
$html .= "<td style='width:25%;vertical-align: top;text-align:center;'>";

$html .= "<div class='panel'>";
$html .= "<div class='panel-heading'>ROI Comparativo</div>";
$html .= "<div class='panel-body'>";
$html .= "<span class='f18'>R$ $vl_roi</span>";
$html .= "</div>";
$html .= "</div>";

$html .= "</td>";
$html .= "</tr>";
$html .= "</table>";



$html .= "<div class='page_break'></div>";

$html .= "<table style='width:1040px;'>";
$html .= "<tr>";
$html .= "<td style='width:100%;vertical-align: top;text-align:center;'>";

$html .= "<div class='panel' style='height:50px !important;'>";
$html .= "<div class='panel-heading' style='font-size:19px !important;'>Análise de Grupos</div>";
$html .= "</div>";

$html .= "<div>";
$html .= $txt_grupo_acao;
$html .= "</div>";

$html .= "<div class='panel' style='height:100px !important;width:200px !important;margin:0 auto !important;'>";
$html .= "<div class='panel-body'>";
$html .= $txt_taxa_engajamento;
$html .= "</div>";
$html .= "</div>";


$html .= "<div style='margin:5px 0 5px 0;'>";
$html .= "<img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAAKCAYAAABWiWWfAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAACiSURBVDhPY/z/8fz/qjNnGeb/ZyAbiLHrMuy2MWNgYvj6geEyBQaBwKufLxlu/WQAGibpwDBVgpdBDCpBOuBl6NVyYbBhBxkGBHLaYQybyTIQZJAvQ5QkJ5gHNgwESDcQ1SAQgBsGAsQbiGkQCKAYBgKEDcRuEAhgGAYCuA3EbRAIYDUMBDANxG8QGPwnAB5eXf8/ZP+a/0uffYOK4AL//wMAlHNvwCs2JCYAAAAASUVORK5CYII='>";
$html .= "</div>";



$html .= "<div class='panel' style='height:410px !important;width:200px !important;margin:0 auto !important;'>";
$html .= "<div class='panel-body'>";
$html .= $txt_info_tr;
$html .= "</div>";
$html .= "</div>";

$html .= "<div class='page_break'></div>";

$html .= "<div>";
$html .= "<p class='f14'><b>RESULTADOS DO CASHBACK</b></p>";
$html .= "</div>";

$html .= "<div class='col-xs-12 text-center'>";
$html .= "<p class='f18'><b>Grupo Ação</b></p>";
$html .= "</div>";

$html .= "<div style='height:30px;'></div>";

$html .= "<table style='width:1040px;'>";
$html .= "<tr>";
$html .= "<td style='width:50%;vertical-align: top;text-align:center;'>";

$html .= "<div class='panel' style='height:220px !important;>";
$html .= "<div class='panel-body'>";
$html .= $txt_grupo_acao_1;
$html .= "</div>";
$html .= "</div>";

$html .= "</td>";
$html .= "<td style='width:50%;vertical-align: top;text-align:center;'>";

$html .= "<div class='panel' style='height:220px !important;>";
$html .= "<div class='panel-body'>";
$html .= $txt_grupo_acao_2;
$html .= "</div>";
$html .= "</div>";

$html .= "</td>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<td style='width:50%;vertical-align: top;text-align:center;'>";

$html .= "<div class='panel' style='height:280px !important;>";
$html .= "<div class='panel-body'>";
$html .= $txt_grupo_acao_3;
$html .= "</div>";
$html .= "</div>";

$html .= "</td>";
$html .= "<td style='width:50%;vertical-align: top;text-align:center;'>";

$html .= "<div class='panel' style='height:280px !important;>";
$html .= "<div class='panel-body'>";
$html .= $txt_grupo_acao_4;
$html .= "</div>";
$html .= "</div>";

$html .= "</td>";
$html .= "</tr>";
$html .= "</table>";

$html .= "<div class='page_break'></div>";

$html .= "<table style='width:1040px;'>";
$html .= "<tr>";
$html .= "<td style='width:50%;vertical-align: top;text-align:center;'>";

$html .= "<div class='panel' style='height:160px !important;>";
$html .= "<div class='panel-body'>";
$html .= $txt_grupo_acao_5;
$html .= "</div>";
$html .= "</div>";

$html .= "</td>";
$html .= "<td style='width:50%;vertical-align: top;text-align:center;'>";

$html .= "<div class='panel' style='height:160px !important;>";
$html .= "<div class='panel-body'>";
$html .= $txt_grupo_acao_6;
$html .= "</div>";
$html .= "</div>";

$html .= "</td>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<td style='width:50%;vertical-align: top;text-align:center;'>";

$html .= "<div class='panel' style='height:270px !important;>";
$html .= "<div class='panel-body'>";
$html .= $txt_grupo_acao_7;
$html .= "</div>";
$html .= "</div>";

$html .= "</td>";
$html .= "<td style='width:50%;vertical-align: top;text-align:center;'>";

$html .= "<div class='panel' style='height:270px !important;>";
$html .= "<div class='panel-body'>";
$html .= $txt_grupo_acao_8;
$html .= "</div>";
$html .= "</div>";

$html .= "</td>";
$html .= "</tr>";
$html .= "</table>";

$html .= "<div class='page_break'></div>";

$html .= "<div class='panel' style='height:100px !important;background:#2C3E50 !important;margin-top:20px;width:200px !important;margin:0 auto !important;'>";
$html .= "<div class='panel-body' style='color:#FFF !important;'>";
$html .= $txt_fat_grupo_acao;
$html .= "</div>";
$html .= "</div>";




$html .= "</td>";
$html .= "</tr>";
$html .= "</table>";


$html .= "</body>";
$html .= "</html>";

//echo $html;exit;

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');

$dompdf->render();
$font = $dompdf->getFontMetrics()->get_font("helvetica", "");
//$dompdf->getCanvas()->page_text(35, 810, utf8_encode("Emiss�o: ").date("d/m/Y H:i:s").str_repeat(" ", 160).utf8_encode("P�gina")." {PAGE_NUM} de {PAGE_COUNT}", $font, 8, array(0,0,0));

//if (@$_GET["baixa"] == "S"){
//	$pdf = $dompdf->output();
//	file_put_contents("arquivos/".$filename.".pdf", $pdf);
//}else{
$dompdf->stream($filename . ".pdf", array("Attachment" => false));
//}
