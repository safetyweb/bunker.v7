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
foreach($dados as $dado => $valor){
	if (!is_array($dado)){
		$$dado = $valor;
	}
}

//busca dados da empresa
$cod_empresa = fnDecode($_GET['id']);	
$cod_pesquisa = fnDecode($_GET['idP']);	
$sql = "SELECT COD_EMPRESA, NOM_FANTASI, DAT_CADASTR FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)){
	$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
	$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	$dat_cadastr = $qrBuscaEmpresa['DAT_CADASTR'];
}

$lojasSelecionadas = (@$_REQUEST['LOJAS'] <> ""?$_REQUEST['LOJAS']:"0");
if ($lojasSelecionadas == 0){
	include("../unidadesAutorizadas.php");
}
$dat_ini = (@$_REQUEST["DAT_INI"] <> ""?fnDataSql(@$_REQUEST["DAT_INI"]):date("Y-m-d"));
$dat_fim = (@$_REQUEST["DAT_FIM"] <> ""?fnDataSql(@$_REQUEST["DAT_FIM"]):date("Y-m-d"));

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
			.text-warning {color: #ffc107!important;}
			.text-success {color: #28a745!important;}
			.text-center {text-align:center;}
			.text-muted {color: #b4bcc2;}

			.push{clear:both;}
			.push5{clear:both;height:5px;}
			.push10{clear:both;height:5px;}

			.skill-name {
				text-transform: uppercase;
				margin-left: 0;
				padding-left: 0;
				padding-top: 1px;
				float: left;
				color:#FFF;
				font-family: 'Raleway', sans-serif;
				font-size: 0.7em;
				text-shadow: -0.5px 0 1.4px #000!important;
			}
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


	$html .= "<center><img src='{$myGauge}'></center>";
	$html .= "<div class='push5'></div>";

	$html .= "<table>";
	$html .= "<tr>";
	$html .= "<td style='width:30%'>&nbsp;</td>";
	$html .= "<td style='text-align:center;'>";
	$html .= fnValor(round($pct_detratoresGeral),0)."%";
	$html .= "<br>";
	$html .= "<img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAIAAAC0Ujn1AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAJwSURBVEhLtZbPi9pAFMf3D9uTJw/LHsRelIIKC4WCUIoeqpcWBPFQelgKycKCJ6EglM1NwaOwB1mWUFgUISBofsd0gjFlIM0koyYzSSy6/fI95b33mUkm82Yu3HRBZzPn9R+s0mmIN2XfDZlh1SEPdAfipHgloqEpGHe1Ve5ydZXsQk0ZTu2EEeLQEPx+aIvp0LDzDXUGaD6FtgS1mSWLjzsj3U+3GIEVRVtTtZqhyv7V4nc+TA+h4VKrn871nRGZA32PdtZMkUo9wUXlyQmIGA1fuiKZdKoLt2sbMQM00JtUxhkW+8sdetF/tSkHLnRN6KNBr0zGkDNSb7qB7nbOSfQ/Xu+burdVBb0Vu0Jv1F8IrWl1IuC7ylnojZCsn9VotKwKOORKnBQJYYs9wUPzChVAvukDXE2/FpoUVgJ61RlfJMa8D8JMgAnAIxvzQUpfjQWwFxMlaYt95FLQ5/k/o+2xTDx9FSO0O1ULVOB8M7yHdowOFSCcL0odVmGw5U/lI0fE1aU0AmjL2KPPRODgSlt90v4E/1lYEJhpx0VD14ONbk/kuCSxNcCHBwTgeaAGs+5yxlwLjkWojOUSWYXcHGz2nQ/03pHhOmf59WAYN7tKW5v5zdOayORSFdUXFMFo1+aV6PjS0NuMjnlPDXlw0JodoxV9/m0SnAY7tJfyzEb7X1Z8e/SQzIqV68iT0u1613oOaE/WqH1Wd83VNNSosSJoT6fT840w1xOJ9rSd9aU8VZnuDz3TxOV7xaCRoGbcET06wflq0gUqAe0LOsv1Ayu9jy5U4Ny19IXVHoWkW5mnNHRYWxPYe1tHLpJIrvsXsNbRLzFLn1wAAAAASUVORK5CYII='>";
	$html .= "<br>";
	$html .= "Detratores";
	$html .= "</td>";
	$html .= "<td style='text-align:center;'>";
	$html .= fnValor(round($pct_neutrosGeral),0)."%";
	$html .= "<br>";
	$html .= "<img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAIAAAC0Ujn1AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAALUSURBVEhLrZZNSBRhGMdn3nF2Zk3cRW2lS2KC3rQkgz7WNNhLKBEpdejjEHRZ6LBWl2gPHrpEl3APRR0iCcENio0ShDrsxdUOsZKkiSZR6uLajmiz8907s89+jM74tfu7vP/n4eXPO8+8M89DappG2KMsx6W5CZWb1pIzxj4XWX2CdDVSR9rpWgYZe+ywsZYSQmxA+hEiVEhYgHxkvd9x2kvTkNiEhbU0FRJjQW0b00KQF7U+Y1s8W5/AbK1y6ZEb8mIUwt1TM8R0+2izfUGkLvCRy/vxxaxcEd6EJfOD5k4tCCMXpN8Tht4v7pfMxa7c2WGVxm8X64tJ3RVjC7mjG9apUfFb2AiLJKFNBYUVCHRr4cvD3d6HnXmvjEUVQyFCjCq/4oYuEcthMaWvSJkeVS2PXDvEXEs6L71FDkjkcQTKuv+UX5+j632QMTGoznJ4QfLScCZhJlB23kc7CMrtZY8HIJcFHb3DehhEu5iOPlRwe3Noi3FcE6SuJiBhQiBkUJosgMqiCWlQdqwt6NbaOoRmQvLHsJDkpPlw+msIclm0yXv8fEJOxvnIA+tiphP4YyE3nleV7HbkuU/fDFiVqkQgkgVVchDhBFVKKhtxNRB10AtxCak+jNsDqc0NrH8KQipPG9nUR1XtVKzViDz9AnSeZtTxubxB/6n+5F+3Kjyks+ivmAG9DTP/Xp1URQgAup++6sd9U/9fK5NBfnzT5fUQlb3IXQuRHalBdW0GNOAhj40daHVhlWkFCX74rLJm+VnukYqnTG9Pphtk7rXH2fmILP6Kox6qE3z1CNaaLra9vzj3NnTqidMDASZvRjX49++OR4Yz78qbTC/ePCzgtv43mv5wS03vpe6snzrX7zwEUY7N1joqJ8QeS9+3HZ0yIC9Z32c3QFlZZ5A4cSoizw6qqa2dvo5wd1F1PWUtzXZTGcbeugBlgyvYxaKKHQZJHYL4D8BaF9vSAoRMAAAAAElFTkSuQmCC'>";
	$html .= "<br>";
	$html .= "Neutros";
	$html .= "</td>";
	$html .= "<td style='text-align:center;'>";
	$html .= fnValor(round($pct_promotoresGeral),0)."%";
	$html .= "<br>";
	$html .= "<img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAIAAAC0Ujn1AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAANGSURBVEhLrZZJTBNRGMeHKXYBOy1pHQlLaVJBlkaLhURMm2gmxkjSxFDCATXRcMF4qHqSU9OL8WL0gvagN6IJmkqiKScSow0kLC6Ala2htMgyUhlatEyh1FleS4dOC0J/l/m+f1/+897X9+Z7ObFYDMrABj7u9wyFiOk/K9PbtICIlPVieblSYyhERbnMmDSks47ic/3PvFP2LZDzguVr2k8aDQUCkHPhs15128cHbBGQ7YkB0XfW6tCUFey2Ds70ts0tuEC2bwSKLu0VTAkyFhg8GXxTPVcP4EsRDVz71u1YARnLzqxJb2+zZ2GYTQ5GjvzFKXNjfO7xWf8evHdIX4oY0TEx4GM2EgVrTfRNjDmY6JDgpNv2I8DGjLV/5GGYybKBc6nfRdIBZR1xLXrHGTVL4G9nCeoBQ+RkX4iVuAjQLt31RaP5tUwIlB2EFpXJe+HG5Ak1BhQOLwOeIG29vMhbZUuZCVMIIaHccEZvAVqco7q75agIFiBl2B0Z0DhsLIySEIyHCBwIHMho/DhuRpnSJbEV2a3shvAREOwL85YDss87HfOB4KrX8eWrHWhxNr52jHrxUGD8e49tDWhcIr/CUM7Q0HMT/8+H4r6mjXPQswuMClM3QHaA5eI8EGYTabkMghEZagA5B0xe8UC8x4KoMVbeIbBCVUDt6+OlWGrBBRqr3niz4VJXflr31sLLXXpje1V1IxB20MrUWvrIQOpGpQRoCaKeJ6N+Ekaxs80fi4uauO9W5aKdlS2Paoqgbdwx4XYCOYHAfExDPZjvdXjM1j+4e/PS6214WluN0L5RMvR3ec0fFJWqpHmImGmG6x775w+2TTpMBpXoPp3TI4lWgLu7Ly6GUo8lCktvlTS0lpUiSYUh1/yu2UF7gODrR9LO0y1NTDdIdJmA09XTlv78orCwginL8lZkmlF4aSo0ddagbJzUdsMe+xDPAvdPncz4pq5CBDL6b4wj0bTXn7ceAdn/YpBzfCmSZs2yTbhG3t0ORng/h2kQthdj1soikMVJsWYI/hx4POPOfHViMUg0lir+CxS/NUM0uDT23ud5tU4MpwxRCeSNCrW5RKdNcyujyGCdRCQcJHeWIJJIM18kaSDoHxKhO598rnL7AAAAAElFTkSuQmCC'>";
	$html .= "<br>";
	$html .= "Promotores";
	$html .= "</td>";
	$html .= "<td style='width:30%'>&nbsp;</td>";
	$html .= "</tr>";
	$html .= "</table>";


	$html .= "<div class='push10'></div>";
	$html .= "<center><img src='{$lineChart}' style='width:100%'></center>";



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






	$sql = "SELECT UV.NOM_FANTASI, 
								       Count(1)                                       AS TOTAL_VOTOS, 
								       Ifnull(Count(CASE 
								                      WHEN DPI.cod_npstipo = 1 THEN DPI.cod_npstipo 
								                    END), 0)                          AS DETRATORES, 
								       Ifnull(Count(CASE 
								                      WHEN DPI.cod_npstipo = 2 THEN DPI.cod_npstipo 
								                    END), 0)                          AS NEUTROS, 
								       Ifnull(Count(CASE 
								                      WHEN DPI.cod_npstipo = 3 THEN DPI.cod_npstipo 
								                    END), 0)                          AS PROMOTORES, 
								       ( ( Ifnull(Count(CASE 
								                          WHEN DPI.cod_npstipo = 1 THEN DPI.cod_npstipo 
								                        END), 0) / Count(1) ) * 100 ) AS PERC_DETRATORES, 
								       ( ( Ifnull(Count(CASE 
								                          WHEN DPI.cod_npstipo = 2 THEN DPI.cod_npstipo 
								                        END), 0) / Count(1) ) * 100 ) AS PERC_NEUTROS, 
								       ( ( Ifnull(Count(CASE 
								                          WHEN DPI.cod_npstipo = 3 THEN DPI.cod_npstipo 
								                        END), 0) / Count(1) ) * 100 ) AS PERC_PROMOTORES,
								          
								      ( ( ( Ifnull(Count(CASE 
								                          WHEN DPI.cod_npstipo = 3 THEN DPI.cod_npstipo 
								                        END), 0) / Count(1) ) * 100 ) - 
								       ( ( Ifnull(Count(CASE 
								                          WHEN DPI.cod_npstipo = 1 THEN DPI.cod_npstipo 
								                        END), 0) / Count(1) ) * 100 )) AS NPS
								FROM   dados_pesquisa_itens DPI
								INNER JOIN dados_pesquisa DP ON DP.cod_registro = DPI.cod_registro
								INNER JOIN webtools.unidadevenda UV ON UV.cod_univend = DPI.cod_univend
								WHERE  DPI.cod_pergunta IN (SELECT cod_registr 
								                            FROM   modelopesquisa 
								                            WHERE  cod_template = $cod_pesquisa 
								                                   AND cod_blpesqu = 5 
								                                   AND cod_exclusa IS NULL) 
								       AND DP.cod_registro = DPI.cod_registro 
								       AND DP.dt_horainicial BETWEEN '$dat_ini 00:00:00' AND 
								                                     '$dat_fim 23:59:59' 
								       $andUnidades 
								       AND DPI.COD_EMPRESA = $cod_empresa
								GROUP  BY DPI.COD_UNIVEND
								ORDER BY NPS DESC";
	// fnEscreve($sql);
			
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

	if(mysqli_num_rows($arrayQuery) > 0){
		$html .= "<h4>Ranking por Loja</h4>";

		while($row = mysqli_fetch_assoc($arrayQuery)){
			$html .= "<table>";
			$html .= "<tr>";
			$html .= "<td style='width:200px;'>".$row['NOM_FANTASI']."</td>";
			$html .= "<td>";
			$html .= "<div class='progress' style='height:15px;'>";
			$html .= "<div class='progress-bar active' style='background-color: #207DBB;height:15px;width:".fnvalorSql(fnValor($row["NPS"],0))."%;'></div>";
			$html .= "<div style='possition:absolute;margin-top:-35px;' class='skill-name'>".fnValor($row['NPS'],0)."</div>";
			$html .= "</div>";
			$html .= "<td style='width:100px;'>".$row['TOTAL_VOTOS']." respostas</td>";
			$html .= "</td>";
			$html .= "</tr>";
			$html .= "</table>";
		}
		$html .= "<table>";
		$html .= "<tr>";
		$html .= "<td style='width:200px;'>&nbsp;</td>";
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
		$html .= "<td style='width:100px;'>&nbsp;</td>";
		$html .= "</tr>";
		$html .= "</table>";
	}

/************************************************************************************************************/

$html .= "</html>";


$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portait');

$dompdf->render();
$font = $dompdf->getFontMetrics()->get_font("helvetica", "");
//$dompdf->getCanvas()->page_text(35, 810, ("Emissão: ").date("d/m/Y H:i:s").str_repeat(" ", 160).("Página")." {PAGE_NUM} de {PAGE_COUNT}", $font, 8, array(0,0,0));

$dompdf->stream($filename.".pdf", array("Attachment" => false));
