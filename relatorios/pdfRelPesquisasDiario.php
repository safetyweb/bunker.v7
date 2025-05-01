<?php
//GERADOR FA TO BASE 64:  http://fa2png.io/
// normal - 30px / grande - 60px
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

include "../_system/_functionsMain.php";

require_once("../pdfComponente/autoload.inc.php");
use Dompdf\Dompdf;

$dompdf = new DOMPDF();


$filename = (@$_REQUEST["filename"] <> ""?$_REQUEST["filename"]:"relPesquisaDiario");

//busca dados da empresa
$cod_empresa = fnDecode($_REQUEST['id']);	
$cod_pesquisa = fnDecode($_REQUEST['idP']);	

$lojasSelecionadas = (@$_REQUEST['LOJAS'] <> ""?$_REQUEST['LOJAS']:"0");
if ($lojasSelecionadas == 0){
	include("../unidadesAutorizadas.php");
}
$dat_ini = (@$_REQUEST["DAT_INI"] <> ""?fnDataSql(@$_REQUEST["DAT_INI"]):date("Y-m-d"));
$dat_fim = (@$_REQUEST["DAT_FIM"] <> ""?fnDataSql(@$_REQUEST["DAT_FIM"]):date("Y-m-d"));

$sql = "SELECT COD_EMPRESA, NOM_FANTASI, DAT_CADASTR FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
if (isset($arrayQuery)){
	$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
	$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	$dat_cadastr = $qrBuscaEmpresa['DAT_CADASTR'];
}

$sql = "SELECT * FROM PESQUISA WHERE COD_EMPRESA = $cod_empresa and COD_PESQUISA = $cod_pesquisa order by DES_PESQUISA";
$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
$qrBuscaPesquisa = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)){
	$des_pesquisa = $qrBuscaPesquisa['DES_PESQUISA'];
	$ini_pesq = $qrBuscaPesquisa['DAT_INI'];
	$fim_pesq = $qrBuscaPesquisa['DAT_FIM'];
}
$contador = 1;


$ids = implode(@$_REQUEST["COD_UNIVEND"],",");
$unidadevenda = "";
if ($ids == "" || $ids == "9999"){
	$unidadevenda = "Todas Unidades";
	$andUnidades = "";
}else{
	$sql = "select GROUP_CONCAT(NOM_FANTASI SEPARATOR ', ') NOM_FANTASI from unidadevenda where COD_EMPRESA = '".$cod_empresa."' AND LOG_ESTATUS = 'S' AND COD_UNIVEND IN ($ids) order by trim(NOM_FANTASI) ";
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
	$rs = mysqli_fetch_assoc($arrayQuery);
	$unidadevenda = $rs["NOM_FANTASI"];
	$andUnidades = "AND DPI.COD_UNIVEND IN($lojasSelecionadas)";
}

$ids = implode(@$_REQUEST["COD_GRUPOTR"],",");
$grupotrabalho = "";
if ($ids <> ""){
	$sql = "select GROUP_CONCAT(DES_GRUPOTR SEPARATOR ', ') DES_GRUPOTR from grupotrabalho where cod_empresa = $cod_empresa AND COD_GRUPOTR IN ($ids) order by DES_GRUPOTR";
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
	$rs = mysqli_fetch_assoc($arrayQuery);
	$grupotrabalho = $rs["DES_GRUPOTR"];
}

$ids = implode(@$_REQUEST["COD_TIPOREG"],",");
$regiao_grupo = "";
if ($ids <> ""){
	$sql = "select GROUP_CONCAT(des_tiporeg SEPARATOR ', ') DES_TIPOREG from regiao_grupo where cod_empresa = $cod_empresa AND COD_TIPOREG IN ($ids) order by des_tiporeg";
	$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql);
	$rs = mysqli_fetch_assoc($arrayQuery);
	$regiao_grupo = $rs["DES_TIPOREG"];
}

//print_r($_POST);

$html = "";

$html .= "<html>";
$html .= "<head>";

$html .= "<style>
			body {
				font-family: \"Lato\",\"Helvetica Neue\",Helvetica,Arial,sans-serif;
				font-size: 12px;
				line-height: 1.42857143;
				color: #000000;
				background-color: #ffffff;
			}
			table{width:100%;}
			table td{padding:5px;}
			h4, .h4 {
				font-size: 19px;
				margin-bottom: 10.5px;
				font-weight: 400;
				line-height: 1.1;
				color: inherit;
			}
			.label_table {
				font-size: 13px;
				margin:0;
				padding:0;
				padding-top:10px;
			}
			.input_table {
				font-size: 17px;
				font-weight: bold;
				margin:0;
				padding:0;
			}
			.progress{
				border-radius: 3px;
				height: 21px;
				white-space: nowrap;
				word-spacing: nowrap;
				width:100%;
				background:#ECF0F1;
			}
			.progress .progress-bar, .progress .progress-bar.progress-bar-default {
				background-color: #3498DB ;
				border-radius: 3px;
			}

			.progress-meter{
				height:15px;
				border-collapse: collapse;
			}
			.progress-meter td{
				color: rgb(160, 160, 160);
			}
			.progress-meter tr:first-child td{
				border-width: 1px;
				border-style: solid;
				border-color: rgb(160, 160, 160);
				border-top:0;
			}

			.text-danger {color: #dc3545!important;}
			.text-warning {color: #f39c12!important;}
			.text-success {color: #28a745!important;}
			.text-center {text-align:center;}
			.text-muted {color: #b4bcc2;}

			.push{clear:both;}
			.push5{clear:both;height:5px;}
			.push10{clear:both;height:5px;}
		</style>";
$html .= "</head>";

/************************************************************************************************************/

	$html .= "<table>";
	$html .= "<tr>";
	$html .= "<td class='label_table'>Empresa:</td>";
	$html .= "<td class='label_table'>Pesquisa:</td>";
	$html .= "<td class='label_table'>Validade:</td>";
	$html .= "</tr>";
	$html .= "<tr>";
	$html .= "<td class='input_table'>$nom_empresa</td>";
	$html .= "<td class='input_table'>$des_pesquisa</td>";
	$html .= "<td class='input_table'>".fnFormatDate($dat_ini)." a ".fnFormatDate($dat_fim)."</td>";
	$html .= "</tr>";

	$html .= "<tr>";
	$html .= "<td class='label_table'>Unidade de Atendimento:</td>";
	$html .= "<td class='label_table'>Grupo de Lojas:</td>";
	$html .= "<td class='label_table'>Região:</td>";
	$html .= "</tr>";
	$html .= "<tr>";
	$html .= "<td class='input_table'>$unidadevenda</td>";
	$html .= "<td class='input_table'>$grupotrabalho</td>";
	$html .= "<td class='input_table'>$regiao_grupo</td>";
	
	$html .= "</table>";


	$sql = "SELECT DPI.* FROM DADOS_PESQUISA_ITENS DPI, DADOS_PESQUISA DP
			WHERE DPI.COD_PERGUNTA IN (
										SELECT COD_REGISTR FROM MODELOPESQUISA 
										WHERE COD_TEMPLATE = $cod_pesquisa 
										AND COD_BLPESQU = 5 
										AND COD_EXCLUSA IS NULL
									)
			AND DP.COD_REGISTRO = DPI.COD_REGISTRO
			$andUnidades
			AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' ";

			$med_ponderada = 0;
			$total_clientes = 0;
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			
			$total = array();
			
			$cont = 0;
			while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
				if($qrBusca['resposta_numero'] == 0){
					$total[0]++;
				}else if($qrBusca['resposta_numero'] == 1){
					$total[1]++;
				}else if($qrBusca['resposta_numero'] == 2){
					$total[2]++;
				}else if($qrBusca['resposta_numero'] == 3){
					$total[3]++;
				}else if($qrBusca['resposta_numero'] == 4){
					$total[4]++;
				}else if($qrBusca['resposta_numero'] == 5){
					$total[5]++;
				}else if($qrBusca['resposta_numero'] == 6){
					$total[6]++;
				}else if($qrBusca['resposta_numero'] == 7){
					$total[7]++;
				}else if($qrBusca['resposta_numero'] == 8){
					$total[8]++;
				}else if($qrBusca['resposta_numero'] == 9){
					$total[9]++;
				}else if($qrBusca['resposta_numero'] == 10){
					$total[10]++;
				}
				$cont++;
			}

			for ($i = 10; $i >= 0; $i--) {
				$pcRand	= $total[$i];
				$med_ponderada += $pcRand * $i;
				$total_clientes += $pcRand;

				if($pcRand == ''){
					$pcRand = 0;
				}

			}

			$med_ponderada = $med_ponderada/$total_clientes;

	$sql = "SELECT DES_PERGUNTA,
				(SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS DPI, DADOS_PESQUISA DP
				 WHERE DPI.COD_PERGUNTA = (SELECT COD_REGISTR FROM MODELOPESQUISA 
									   WHERE COD_TEMPLATE = $cod_pesquisa 
									   AND LOG_PRINCIPAL = 'S'
									   AND COD_EXCLUSA IS NULL) 
				 AND DPI.COD_NPSTIPO = 3
				 $andUnidades
				 AND DP.COD_REGISTRO = DPI.COD_REGISTRO
				 AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					) AS TOTAL_PROMOTORES,

				(SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS DPI, DADOS_PESQUISA DP
				 WHERE DPI.COD_PERGUNTA = (SELECT COD_REGISTR FROM MODELOPESQUISA 
									   WHERE COD_TEMPLATE = $cod_pesquisa 
									   AND LOG_PRINCIPAL = 'S'
									   AND COD_EXCLUSA IS NULL) 
				 AND DPI.COD_NPSTIPO = 2
				 $andUnidades
				 AND DP.COD_REGISTRO = DPI.COD_REGISTRO
				 AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					) AS TOTAL_NEUTROS,

				 (SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS DPI, DADOS_PESQUISA DP
				 WHERE DPI.COD_PERGUNTA = (SELECT COD_REGISTR FROM MODELOPESQUISA 
									   WHERE COD_TEMPLATE = $cod_pesquisa 
									   AND LOG_PRINCIPAL = 'S'
									   AND COD_EXCLUSA IS NULL) 
				 AND DPI.COD_NPSTIPO = 1
				 $andUnidades
				 AND DP.COD_REGISTRO = DPI.COD_REGISTRO
				 AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					) AS TOTAL_DETRATORES
					FROM MODELOPESQUISA 
				   WHERE COD_TEMPLATE = $cod_pesquisa 
				   AND LOG_PRINCIPAL = 'S'
				   AND COD_EXCLUSA IS NULL
				";
	// fnEscreve($sql);
	$arrayCount = mysqli_query(connTemp($cod_empresa,''),$sql);
	$qrBusca = mysqli_fetch_assoc($arrayCount);
	$TOTAL_PROMOTORES = $qrBusca['TOTAL_PROMOTORES'];
	$TOTAL_NEUTROS = $qrBusca['TOTAL_NEUTROS'];
	$TOTAL_DETRATORES = $qrBusca['TOTAL_DETRATORES'];

	$total_avalia = $TOTAL_PROMOTORES + $TOTAL_NEUTROS + $TOTAL_DETRATORES;
	// Media ponderada = (Ax0 + Bx1 + Cx2 + …. + Jx10) / qtde total de clientes com respostas

	// Clientes totais com respostas = A + B + C + .... + J
	if($med_ponderada < 6) {
		$corMed = "text-danger";
		$texto = "Detratores";
		$icone = "data:image/png;base64,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";
	}else if($med_ponderada >= 6 && $med_ponderada < 9){
		$corMed = "text-warning";
		$texto = "Neutros";
		$icone = "data:image/png;base64,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";
	}else{
		$corMed = "text-success";
		$texto = "Promotores";
		$icone = "data:image/png;base64,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";
	}

	$pct_detratoresGeral = ($TOTAL_DETRATORES/$total_clientes)*100;
	$pct_neutrosGeral = ($TOTAL_NEUTROS/$total_clientes)*100;
	$pct_promotoresGeral = ($TOTAL_PROMOTORES/$total_clientes)*100;

	$nps = $pct_promotoresGeral - $pct_detratoresGeral;

	if($nps < 60) {
		$corNps = "text-danger";
	}else if($nps >= 60 && $nps < 90){
		$corNps = "text-warning";
	}else{
		$corNps = "text-success";
	}

	if(mysqli_num_rows($arrayCount) == 1){
		$html .= "<h4>".$contador.". ".$qrBusca['DES_PERGUNTA']."</h4>";

		$html .= "<table>";
		$html .= "<tr>";
		$html .= "<td>";

			$html .= "<table>";
			
			$html .= "<tr>";
			$html .= "<td style='width:20px;'>";
			$html .= "<img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAPCAIAAAC0tAIdAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAE+SURBVChThZE/T4NQFMX5GP0UjG5ujo7d3Bwdu3Vzc2VjIZqQvKFJCYmDCQlWyotopYSkSFLQGkGJJTUixhAaIU/+FoI0npzl3vN7NzfvYqhS5D5L5A3oCnTm4fFMNf2oCDOV9KdOjKkO3+LezA4LKKdXk95lE6p7XzbyBxiKHbBlat19w0tp3xjif7IWj3gtRhh/vWkNmBUKny728lIUrZ9Im56VKUVYCCPKIqHBMvAfa/S3B+8q+vD+vU7/45QmR83uNh/pHgYnZS1cyQuuW4txKGlztixpsEx+0OaLRXn65MELPwxG5UiFA6bjrx0G5hHVGYtWdp0A3pYtntqF52Cuw4XKKOxBdTKafMmvkyiwwWZGi+m+4aZYQSeKPVkZ7DQ5ChdY5vWrYCo619rVTOlUSfcmZ1P5reJSIfQL7yq3spUPe0sAAAAASUVORK5CYII='>";
			$html .= "</td>";
			$html .= "<td style='width:110px;'>Promotores</td>";
			$html .= "<td>";
			$html .= "<div class='progress' style='height:15px;'>";
			$html .= "<div class='progress-bar active' style='background-color: #15BC9C;height:15px;width:".fnvalorSql(fnValor($pct_promotoresGeral,0))."%;'></div>";
			$html .= "</div>";
			$html .= "</td>";
			$html .= "<td style='width:30px;text-align:right;'>".fnvalorSql(fnValor($TOTAL_PROMOTORES,0))."</td>";
			$html .= "<td style='width:50px;text-align:right;'>".fnvalorSql(fnValor($pct_promotoresGeral,2))."%</td>";
			$html .= "</tr>";

			$html .= "<tr>";
			$html .= "<td style='width:20px;'>";
			$html .= "<img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAPCAIAAAC0tAIdAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAFbSURBVChTY/z//z8DDPy+teb3tc3/vn4E8+SZ5H1Y9FzZ+MA8MICq/vf+9I+dcf++voKIIgF5RvUVHDZqzGAOSPW/V1u+b43//w8sgBVI7+D0MAVqYPz/98H3FUZ/v0PFcQAxRsMT3Eb8TL8vTCWkFAhe/b+y5Oc/BqY/D+ZCRRh8mH3fcloVQXk8E9ni3rLK6UG5v6f8vcfA9B8SABDw++O/v1AmCPz6+P83lAky/vUrxq9zhPB4Dxkwal1nYmCFcggDZgbG75s1/ryEBLM8A4ccmIEEft9k+AuRtWX22MDCppnz52UdlG8YwghmwcH/R71/n4JVc4YwS4Ni58ePzQZ/XmHGIjIwZXLawaXIwMTAwM7hspGZTwwqjgWYMhrO5FAEsWCp6t/HH3vz/jzaAmIjA45sZptidnl+oKlAgJIG//169fv6ob+vXoI4nPLMCrasMlB1IMDAAACCC4XacTT8IAAAAABJRU5ErkJggg=='>";
			$html .= "</td>";
			$html .= "<td style='width:110px;'>Neutros</td>";
			$html .= "<td>";
			$html .= "<div class='progress' style='height:15px;'>";
			$html .= "<div class='progress-bar active' style='background-color: #F39C12;height:15px;width:".fnvalorSql(fnValor($pct_neutrosGeral,0))."%;'></div>";
			$html .= "</div>";
			$html .= "</td>";
			$html .= "<td style='width:30px;text-align:right;'>".fnvalorSql(fnValor($TOTAL_NEUTROS,0))."</td>";
			$html .= "<td style='width:50px;text-align:right;'>".fnvalorSql(fnValor($pct_neutrosGeral,2))."%</td>";
			$html .= "</tr>";

			$html .= "<tr>";
			$html .= "<td style='width:20px;'>";
			$html .= "<img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAPCAIAAAC0tAIdAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAGASURBVChTY/z//z8DDHy7sOXb5t1/H38G86SYXL05XSx5BME8MICq/vvm3Pvm0j/3fkFEkQAPU+IM/iB5djAHpPrv891v85r+/QALYAOMXtMFMnWAGpgY/j780IpPKRD835b/cf8XIIPp+6GFvx9CBPGAX//mrPv0l4Hpx8HdUBEGS7ZZhwUbIqA89UKeDYe5fCWh3E8Lf11lYPp7GcoFgv+/vvxD9uePd/++Q5kg4x++Y3ztY/MHyicAmNI2MjHyQTmEATMDE7MxG5QDAjxM7uUcEzcKLF3FVVrILMMDFQYBVWZtISZej3gol82SbfpGPg/2P3NLPpZ0/Hgsydm5msNLHior78MuD4qdnx+6PX8c+sXAF8Tq9fb3ioNQaSAQCWK1e/573XGgWtaeJcLqkJj/+vBtU9Lva5jRDgHyTEXdwo6SzPB0wvD33Yf5jT82ngNLIwExH7bKbH4VHqBSIICpBoO/v55/PXH2943nICFBBVZjSy4lHlaIHBAwMAAA7leXyxK9IksAAAAASUVORK5CYII='>";
			$html .= "</td>";
			$html .= "<td style='width:110px;'>Detratores</td>";
			$html .= "<td>";
			$html .= "<div class='progress' style='height:15px;'>";
			$html .= "<div class='progress-bar active' style='background-color: #E74C3C;height:15px;width:".fnvalorSql(fnValor($pct_detratoresGeral,0))."%;'></div>";
			$html .= "</div>";
			$html .= "</td>";
			$html .= "<td style='width:30px;text-align:right;'>".fnvalorSql(fnValor($TOTAL_DETRATORES,0))."</td>";
			$html .= "<td style='width:50px;text-align:right;'>".fnvalorSql(fnValor($pct_detratoresGeral,2))."%</td>";
			$html .= "</tr>";
			
			$html .= "<tr>";
			$html .= "<td style='width:20px;'></td>";
			$html .= "<td style='width:110px;'></td>";
			$html .= "<td>";
				$html .= "<table class='progress-meter'>";
				$html .= "<tr>";
				$html .= "<td style='width: 25%;'></td>";
				$html .= "<td style='width: 25%;'></td>";
				$html .= "<td style='width: 30%;'></td>";
				$html .= "<td style='width: 20%;'></td>";
				$html .= "</tr>";
				$html .= "<tr>";
				$html .= "<td style='text-align:left;'>0</td>";
				$html .= "<td style='text-align:left;'>25</td>";
				$html .= "<td style='text-align:right;'>75</td>";
				$html .= "<td style='text-align:right;'>100</td>";
				$html .= "</tr>";
				$html .= "</table>";
			$html .= "</td>";
			$html .= "<td style='width:30px;'></td>";
			$html .= "<td style='width:50px;'></td>";

			$html .= "</table>";


		$html .= "</td>";
		$html .= "<td style='width:100px;'>";

		$html .= "<div style='background:#ECF0F1; border-radius: 15px;'>";
		$html .= "<div class='text-center ".$corMed."'>";
		$html .= "<div class='push5'></div>";
		$html .= "<img src='".$icone."'>";
		$html .= "<div class='push5'></div>";
		$html .= "<b>".fnValor($med_ponderada,2)."</b>";
		$html .= "<div class='push'></div>";
		$html .= "<b>Média Final</b>";
		$html .= "<div class='push5'></div>";
		$html .= "</div>";
		$html .= "</div>";

		$html .= "</td>";

		$html .= "<td style='width:100px;'>";

		$html .= "<div style='background:#ECF0F1; border-radius: 15px;'>";
		$html .= "<div class='text-center ".$corNps."'>";
		$html .= "<div class='push10'></div>";
		$html .= "<b style='font-size: 42px;'>".fnValor($nps,0)."</b>";
		$html .= "<div class='push'></div>";
		$html .= "<b>Média NPS</b>";
		$html .= "<div class='push5'></div>";
		$html .= "</div>";
		$html .= "</div>";

		$html .= "</td>";

		$html .= "</tr>";
		$html .= "</table>";
	}



/****/


	$sql = "SELECT MP.DES_PERGUNTA,
			(SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS DPI, DADOS_PESQUISA DP
			 WHERE DPI.COD_PERGUNTA = MP.COD_REGISTR 
			 AND DPI.COD_NPSTIPO = 3
			 $andUnidades
			 AND DP.COD_REGISTRO = DPI.COD_REGISTRO
			 AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
				) AS TOTAL_PROMOTORES,

			(SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS DPI, DADOS_PESQUISA DP
			 WHERE DPI.COD_PERGUNTA = MP.COD_REGISTR
			 AND DPI.COD_NPSTIPO = 2
			 $andUnidades
			 AND DP.COD_REGISTRO = DPI.COD_REGISTRO
			 AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
				) AS TOTAL_NEUTROS,

			 (SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS DPI, DADOS_PESQUISA DP
			 WHERE DPI.COD_PERGUNTA = MP.COD_REGISTR 
			 AND DPI.COD_NPSTIPO = 1
			 $andUnidades
			 AND DP.COD_REGISTRO = DPI.COD_REGISTRO
			 AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
				) AS TOTAL_DETRATORES
				FROM MODELOPESQUISA MP 
			   WHERE MP.COD_TEMPLATE = $cod_pesquisa 
			   AND MP.LOG_PRINCIPAL = 'N'
			   AND TIP_BLOCO = 'squares'
			   AND MP.COD_EXCLUSA IS NULL
			";
	// fnEscreve($sql);
	$arrayCountAvalia = mysqli_query(connTemp($cod_empresa,''),$sql);

	while($qrBuscaAvalia = mysqli_fetch_assoc($arrayCountAvalia)){
		$contador++;

		$TOTAL_PROMOTORESav = $qrBuscaAvalia['TOTAL_PROMOTORES'];
		$TOTAL_NEUTROSav = $qrBuscaAvalia['TOTAL_NEUTROS'];
		$TOTAL_DETRATORESav = $qrBuscaAvalia['TOTAL_DETRATORES'];

		$pct_detratoresAvalia = ($TOTAL_DETRATORESav/$total_clientes)*100;
		$pct_neutrosAvalia = ($TOTAL_NEUTROSav/$total_clientes)*100;
		$pct_promotoresAvalia = ($TOTAL_PROMOTORESav/$total_clientes)*100;

		$nps = $pct_promotoresAvalia - $pct_detratoresAvalia;
		
		$html .= "<h4>".$contador.". ".$qrBuscaAvalia['DES_PERGUNTA']."</h4>";

		$html .= "<table>";
		$html .= "<tr>";
		$html .= "<td>";

			$html .= "<table>";
			
			$html .= "<tr>";
			$html .= "<td style='width:20px;'>";
			$html .= "<img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAPCAIAAAC0tAIdAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAE+SURBVChThZE/T4NQFMX5GP0UjG5ujo7d3Bwdu3Vzc2VjIZqQvKFJCYmDCQlWyotopYSkSFLQGkGJJTUixhAaIU/+FoI0npzl3vN7NzfvYqhS5D5L5A3oCnTm4fFMNf2oCDOV9KdOjKkO3+LezA4LKKdXk95lE6p7XzbyBxiKHbBlat19w0tp3xjif7IWj3gtRhh/vWkNmBUKny728lIUrZ9Im56VKUVYCCPKIqHBMvAfa/S3B+8q+vD+vU7/45QmR83uNh/pHgYnZS1cyQuuW4txKGlztixpsEx+0OaLRXn65MELPwxG5UiFA6bjrx0G5hHVGYtWdp0A3pYtntqF52Cuw4XKKOxBdTKafMmvkyiwwWZGi+m+4aZYQSeKPVkZ7DQ5ChdY5vWrYCo619rVTOlUSfcmZ1P5reJSIfQL7yq3spUPe0sAAAAASUVORK5CYII='>";
			$html .= "</td>";
			$html .= "<td style='width:110px;'>Promotores</td>";
			$html .= "<td>";
			$html .= "<div class='progress' style='height:15px;'>";
			$html .= "<div class='progress-bar active' style='background-color: #15BC9C;height:15px;width:".fnvalorSql(fnValor($pct_promotoresAvalia,0))."%;'></div>";
			$html .= "</div>";
			$html .= "</td>";
			$html .= "<td style='width:30px;text-align:right;'>".fnvalorSql(fnValor($TOTAL_PROMOTORESav,0))."</td>";
			$html .= "<td style='width:50px;text-align:right;'>".fnvalorSql(fnValor($pct_promotoresAvalia,2))."%</td>";
			$html .= "</tr>";

			$html .= "<tr>";
			$html .= "<td style='width:20px;'>";
			$html .= "<img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAPCAIAAAC0tAIdAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAFbSURBVChTY/z//z8DDPy+teb3tc3/vn4E8+SZ5H1Y9FzZ+MA8MICq/vf+9I+dcf++voKIIgF5RvUVHDZqzGAOSPW/V1u+b43//w8sgBVI7+D0MAVqYPz/98H3FUZ/v0PFcQAxRsMT3Eb8TL8vTCWkFAhe/b+y5Oc/BqY/D+ZCRRh8mH3fcloVQXk8E9ni3rLK6UG5v6f8vcfA9B8SABDw++O/v1AmCPz6+P83lAky/vUrxq9zhPB4Dxkwal1nYmCFcggDZgbG75s1/ryEBLM8A4ccmIEEft9k+AuRtWX22MDCppnz52UdlG8YwghmwcH/R71/n4JVc4YwS4Ni58ePzQZ/XmHGIjIwZXLawaXIwMTAwM7hspGZTwwqjgWYMhrO5FAEsWCp6t/HH3vz/jzaAmIjA45sZptidnl+oKlAgJIG//169fv6ob+vXoI4nPLMCrasMlB1IMDAAACCC4XacTT8IAAAAABJRU5ErkJggg=='>";
			$html .= "</td>";
			$html .= "<td style='width:110px;'>Neutros</td>";
			$html .= "<td>";
			$html .= "<div class='progress' style='height:15px;'>";
			$html .= "<div class='progress-bar active' style='background-color: #F39C12;height:15px;width:".fnvalorSql(fnValor($pct_neutrosAvalia,0))."%;'></div>";
			$html .= "</div>";
			$html .= "</td>";
			$html .= "<td style='width:30px;text-align:right;'>".fnvalorSql(fnValor($TOTAL_NEUTROSav,0))."</td>";
			$html .= "<td style='width:50px;text-align:right;'>".fnvalorSql(fnValor($pct_neutrosAvalia,2))."%</td>";
			$html .= "</tr>";

			$html .= "<tr>";
			$html .= "<td style='width:20px;'>";
			$html .= "<img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAPCAIAAAC0tAIdAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAGASURBVChTY/z//z8DDHy7sOXb5t1/H38G86SYXL05XSx5BME8MICq/vvm3Pvm0j/3fkFEkQAPU+IM/iB5djAHpPrv891v85r+/QALYAOMXtMFMnWAGpgY/j780IpPKRD835b/cf8XIIPp+6GFvx9CBPGAX//mrPv0l4Hpx8HdUBEGS7ZZhwUbIqA89UKeDYe5fCWh3E8Lf11lYPp7GcoFgv+/vvxD9uePd/++Q5kg4x++Y3ztY/MHyicAmNI2MjHyQTmEATMDE7MxG5QDAjxM7uUcEzcKLF3FVVrILMMDFQYBVWZtISZej3gol82SbfpGPg/2P3NLPpZ0/Hgsydm5msNLHior78MuD4qdnx+6PX8c+sXAF8Tq9fb3ioNQaSAQCWK1e/573XGgWtaeJcLqkJj/+vBtU9Lva5jRDgHyTEXdwo6SzPB0wvD33Yf5jT82ngNLIwExH7bKbH4VHqBSIICpBoO/v55/PXH2943nICFBBVZjSy4lHlaIHBAwMAAA7leXyxK9IksAAAAASUVORK5CYII='>";
			$html .= "</td>";
			$html .= "<td style='width:110px;'>Detratores</td>";
			$html .= "<td>";
			$html .= "<div class='progress' style='height:15px;'>";
			$html .= "<div class='progress-bar active' style='background-color: #E74C3C;height:15px;width:".fnvalorSql(fnValor($pct_detratoresAvalia,0))."%;'></div>";
			$html .= "</div>";
			$html .= "</td>";
			$html .= "<td style='width:30px;text-align:right;'>".fnvalorSql(fnValor($TOTAL_DETRATORESav,0))."</td>";
			$html .= "<td style='width:50px;text-align:right;'>".fnvalorSql(fnValor($pct_detratoresAvalia,2))."%</td>";
			$html .= "</tr>";
			
			$html .= "<tr>";
			$html .= "<td style='width:20px;'></td>";
			$html .= "<td style='width:110px;'></td>";
			$html .= "<td>";
				$html .= "<table class='progress-meter'>";
				$html .= "<tr>";
				$html .= "<td style='width: 25%;'></td>";
				$html .= "<td style='width: 25%;'></td>";
				$html .= "<td style='width: 30%;'></td>";
				$html .= "<td style='width: 20%;'></td>";
				$html .= "</tr>";
				$html .= "<tr>";
				$html .= "<td style='text-align:left;'>0</td>";
				$html .= "<td style='text-align:left;'>25</td>";
				$html .= "<td style='text-align:right;'>75</td>";
				$html .= "<td style='text-align:right;'>100</td>";
				$html .= "</tr>";
				$html .= "</table>";
			$html .= "</td>";
			$html .= "<td style='width:30px;'></td>";
			$html .= "<td style='width:50px;'></td>";

			$html .= "</table>";


		$html .= "</td>";
		$html .= "</tr>";
		$html .= "</table>";
	}

/****/

	$sql = "SELECT * FROM MODELOPESQUISA 
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_TEMPLATE = $cod_pesquisa 
			AND COD_BLPESQU = 2
			AND DAT_EXCLUSA IS NULL";

	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

	// fnEscreve($sql);
	while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {

		$contador++;

		$html .= "<h4>".$contador.". ".$qrBusca['DES_PERGUNTA']."</h4>";

		$texto = true;
		if ($qrBusca["DES_TIPO_RESPOSTA"] == "R" || $qrBusca["DES_TIPO_RESPOSTA"] == "C" ||
			$qrBusca["DES_TIPO_RESPOSTA"] == "RB" || $qrBusca["DES_TIPO_RESPOSTA"] == "CB" ||
			$qrBusca["DES_TIPO_RESPOSTA"] == "A"){
			$texto = false;
		}
		$sql2 = "SELECT DPI.*, DP.DT_HORAINICIAL 
				FROM DADOS_PESQUISA_ITENS DPI, DADOS_PESQUISA DP
				WHERE COD_PERGUNTA = $qrBusca[COD_REGISTR]
				AND DPI.COD_REGISTRO = DP.COD_REGISTRO
				$andUnidades
				AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
				ORDER BY DP.DT_HORAINICIAL DESC";
		$arrayQuery2 = mysqli_query(connTemp($cod_empresa,''),$sql2);
		
		$rsp_opc = array();
		$qtd_opc = array();
		$qtd_rsp = 0;
		while ($qrBusca2 = mysqli_fetch_assoc($arrayQuery2)) {

			if(trim($qrBusca2['resposta_texto']) != ""){

				$r = json_decode($qrBusca2['resposta_texto'],true);
				if (is_array($r)){
					$resp = "";
					foreach($r as $rk => $rv){
						if (is_array($rv)){
							$resp .= "&nbsp;<span style='border:1px solid ".(@$rv["opcao"] == "N"?"#dc3545":(@$rv["opcao"] == "S"?"#28a745":"#EEE")).";padding:1px 5px;'>";
							$resp .= $rv["texto"];
							$resp .= "</span>";
							$o = str_replace(" ","",$rv["texto"]);
							@$rsp_opc[$o][$rv["opcao"]]++;
							@$qtd_opc[$rv["opcao"]]++;
						}else{
							$resp .= "&nbsp;<span style='border:1px solid #EEE;padding:1px 5px;'>$rv</span>";
							$o = str_replace(" ","",$rv);
							@$rsp_opc[$o]["S"]++;
							@$qtd_opc["S"]++;
						}
					}
				}else{
					$resp = $qrBusca2['resposta_texto'];
				}

				$qtd_rsp++;
				
				if ($texto){
					$html .= "<i class='text-muted'>".$resp." - <small>".fnDataFull($qrBusca2['DT_HORAINICIAL'])."</small></i>";
					$html .= "<div class='push'></div>";	
				}
			}
		}
	
		if (!$texto){
			
			$opcoes = json_decode($qrBusca['DES_OPCOES'],true);

			$avaliacao = ($qrBusca["DES_TIPO_RESPOSTA"] == "A");

			$html .= "<table>";
			foreach($opcoes as $opkey => $opcao){
				$o = str_replace(" ","",$opcao);
				
				if ($avaliacao){
					$qtd_s = @$rsp_opc[$o]["S"];
					$qtd_n = @$rsp_opc[$o]["N"];
					$pct_s = (@$rsp_opc[$o]["S"]/(@$qtd_s+@$qtd_n))*100;
					$pct_n = (@$rsp_opc[$o]["N"]/(@$qtd_s+@$qtd_n))*100;
				}else{
					$qtd_s = @$rsp_opc[$o]["S"];
					$pct_s = (@$rsp_opc[$o]["S"]/@$qtd_opc["S"])*100;
				}

				$html .= "<tr>";

				$html .= "<td style='width:110px;'>$opcao</td>";

				if ($avaliacao){
					$html .= "<td style='width:50px;text-align:right;'>".fnvalorSql(fnValor($pct_n,2))."%</td>";
					$html .= "<td style='width:50px;text-align:right;'>".fnvalorSql(fnValor($qtd_n,0))."</td>";
					$html .= "<td style='width:15px;text-align:center;'>";
					$html .= "<img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAPCAIAAAC0tAIdAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAGZSURBVChTY/z////PT1/+MzAwsPFwcDB8v3f829qtf04d/P8DKAQGHEYsFfWCxkLMDAyM3w92fOjeApWAAEF7Zl97VnU1NgVhpk/Pv2/r+7lZmH1Ri6AgA8vfj2+hihgYmKLncfnKc3KzAY2BAj5VjuTU15vz/1z9xWDDxgQVBQMmDVUeZKUQwKzGYsb2d89xoNNQVOMAPGx6Ogz3Hv4mTjUDh7Yl4/srv18Rp5rh16//DG/+f4eoVi/kmdsCZP3//usvWBYNMKvbs0re/nvpHVj129s/j5wDBvnfNs+3m59javj79dk/YMgxMzD+eXbs/ZTZf18zMMr6sKqe+Ln0GUvzPBEDNgaG22+jk35/gmpgEIngnJLNAIxLJPDzw7To50Glb5+BOJ/XZjz3qX5z7Oz7k7e+/gGJoKn+///Ps7dlTs9rdn0Dsp9tfumTB9EJARhhwizJ4+vDcOHKT2BkgIKCHehcOMASgmzaOkwMW35NnP26b+p/ESNWMag4EIDSIJSJBL5ua/qy4QGjrBtbZoSACFSQgYEBAHyEzJcadqLNAAAAAElFTkSuQmCC'>";
					$html .= "</td>";
				}

				$html .= "<td>";
				$html .= "<div class='progress' style='height:15px;'>";
				if ($avaliacao){
					$html .= "<div class='progress-bar active' style='float:left;background-color: #E74C3C;height:15px;width:".fnvalorSql(fnValor($pct_n,0))."%;'></div>";
					$html .= "<div class='progress-bar active' style='float:right;background-color: #15BC9C;height:15px;width:".fnvalorSql(fnValor($pct_s,0))."%;'></div>";
				}else{
					$html .= "<div class='progress-bar active' style='background-color: #15BC9C;height:15px;width:".fnvalorSql(fnValor($pct_s,0))."%;'></div>";
				}
				
				$html .= "</div>";
				$html .= "</td>";

				if ($avaliacao){
					$html .= "<td style='width:15px;text-align:center;'>";
					$html .= "<img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAPCAIAAAC0tAIdAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAGLSURBVChTY/z//z8DBvj5YHvI3WdnGBicBW3nGqmxQ4UZmKA0Cni29tGzH3y6myXEPr4/3H/3F1QYu+rPz6/8ZnAWMTPRtkpkY7j94zNUHJvq72duXpvPIGYjBWT//PGPgeH/X4gEEKCrfnR1c/JHhgplFxugYx9fn/+H4cqbncknti+79xYoC/Tlt0f3Luz98PknkPf389ZPH+QkfKdqiwF5Vy4ua/wAMfjvoz9/TSQDGP+/PZZ8/to2sBgQeAk7TDNQhgcCHNy+uNjukwqqS/ht52JTCvTMyx+/GBiZsYYgBvj35vYPBi8BBeJU//zw6A+DOBsncaq/f3nEwKkjwAtVLcfMLAdhYQO3H93ZxsArxw8Nb7Fem4Sp/AwM367Nv3rhyltEVIPBr5c/fzHwqwNjgPH/u5PZ5y6fYWZj//vrIxObGsOvI/8YxJg4vXlE5JjBiv98mP/5W5RyVL4CGygNfnp+7dJ3oDCXmpyCGMvfTy9u7nlx9+jHD4/AihmYeL1kHBIVBRgYGAD4mp3dmyckawAAAABJRU5ErkJggg=='>";
					$html .= "</td>";
				}

				$html .= "</td>";
				$html .= "<td style='width:30px;text-align:right;'>".fnvalorSql(fnValor($qtd_s,0))."</td>";
				$html .= "<td style='width:50px;text-align:right;'>".fnvalorSql(fnValor($pct_s,2))."%</td>";
				$html .= "</tr>";

				$html .= "</tr>";

			}

			if (!$avaliacao){
				$html .= "<tr>";
				$html .= "<td></td>";
				$html .= "<td>";

				$html .= "<table class='progress-meter'>";
				$html .= "<tr>";
				$html .= "<td style='width: 25%;'></td>";
				$html .= "<td style='width: 25%;'></td>";
				$html .= "<td style='width: 30%;'></td>";
				$html .= "<td style='width: 20%;'></td>";
				$html .= "</tr>";
				$html .= "<tr>";
				$html .= "<td style='text-align:left;'>0</td>";
				$html .= "<td style='text-align:left;'>25</td>";
				$html .= "<td style='text-align:right;'>75</td>";
				$html .= "<td style='text-align:right;'>100</td>";
				$html .= "</tr>";
				$html .= "</table>";
				
				$html .= "</td>";
				$html .= "</tr>";
			}

			$html .= "</table>";

		}
		$html .= "<div style='text-align:right;'><i>$qtd_rsp resposta(s)</i></div>";
		$html .= "<div class='push5'></div>";	
	}

/************************************************************************************************************/

$html .= "</html>";
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portait');

$dompdf->render();
$font = $dompdf->getFontMetrics()->get_font("helvetica", "");
//$dompdf->getCanvas()->page_text(35, 810, ("Emissão: ").date("d/m/Y H:i:s").str_repeat(" ", 160).("Página")." {PAGE_NUM} de {PAGE_COUNT}", $font, 8, array(0,0,0));

if (@$_GET["save"] == true){
	$dir = __DIR__."/pdf/";
	$filename = $filename.".pdf";
    if (file_put_contents($dir.$filename,$dompdf->output())) {
		echo "Salvo";
    }else{
		echo "Erro ao salvar";
    }

}else{
	$dompdf->stream($filename.".pdf", array("Attachment" => false));
}
