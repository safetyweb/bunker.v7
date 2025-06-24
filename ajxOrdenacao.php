<?php include "_system/_functionsMain.php";

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$buscaAjx1 = fnLimpacampo($_REQUEST['ajx1']);
$buscaAjx2 = fnLimpacampo($_REQUEST['ajx2']);

//inicialização
$tabela = "";
$campo = "";

//tabela do update
switch ($buscaAjx2) {
	case 1: //segmento
		$tabela = "BLOCOTEMPLATE";
		$campo = "COD_BLTEMPL";
		break;
	case 2: //segmento itens
		$tabela = "SEGMARKAITEM";
		$campo = "COD_SEGITEM";
		break;
	case 3: //segmento itens
		$tabela = "TIPOCAMPANHA";
		$campo = "COD_TPCAMPA";
		break;
	case 4: //blocos template
		$tabela = "BLOCOTEMPLATE";
		$campo = "COD_BLTEMPL";
		break;
	case 5: //modulos marka
		$tabela = "MODULOSMARKA";
		$campo = "COD_MODULMK";
		break;
	case 6: // grupos modulos marka
		$tabela = "GRUPOMODULOSMARKA";
		$campo = "COD_GRUPOMODMK";
		break;
	case 7: // Segmento marka
		$tabela = "SEGMENTOMARKA";
		$campo = "COD_SEGMENT";
		break;
	case 8: // Fases da venda - integração
		$tabela = "INTEGRA_VENDAMTZ";
		$campo = "COD_FASEINT";
		break;
	case 9: // Fases da venda - integração
		$tabela = "INTEGRA_ACAOMTZ";
		$campo = "COD_ACAOINT";
		break;
	case 10: // campos obrigatórios
		$tabela = "INTEGRA_CAMPOOBG";
		$campo = "COD_CAMPOOBG";
		break;
	case 11: //variaveis
		$tabela = "VARIAVEIS";
		$campo = "COD_BANCOVAR";
		break;
	case 12: //Ocorrência dos filtros
		$tabela = "TIPO_FILTRO";
		$campo = "COD_TPFILTRO";
		break;
	case 13: //Ocorrência dos filtros
		$tabela = "CANAL_COMUNICACAO";
		$campo = "COD_CANALCOM";
		break;
	case 14: //Ocorrência dos filtros
		$tabela = "COMUNICACAO_FAIXAS";
		$campo = "COD_COMFAIXA";
		break;
	case 15: //Ocorrência dos filtros
		$tabela = "CATEGORIA_TUTORIAL";
		$campo = "COD_CATEGOR";
		break;
	case 16: //Ocorrência dos filtros
		$tabela = "SUBCATEGORIA_TUTORIAL";
		$campo = "COD_SUBCATEGOR";
		break;
	case 17: //Ocorrência dos filtros
		$tabela = "BLOCO_COMUNICACAO";
		$campo = "COD_BLTEMPL";
		break;
	case 18:
		$tabela = "SISTEMA_VERSAO";
		$campo = "COD_VERSAO";
		break;
	case 19:
		$tabela = "MODULOSMARKA_AREA";
		$campo = "COD_AREABLOCK";
		break;
	case 20:
		$tabela = "SAC_CHAMADOS";
		$campo = "COD_CHAMADO";
		break;
	case 21: //tour
		$tabela = "TOUR";
		$campo = "COD_TOUR";
		break;
	case 22: //categorias de tickets
		$tabela = "CATEGORIA_BLOCOTEMPLATE";
		$campo = "COD_CATEGORIA";
		break;
}

$campoId = explode(",", $buscaAjx1);
//$campoId = explode(",", "3,2,4");
$categories = "";
$montaUpdate = "";
$contaLoop = 1;
foreach ($campoId as $ordem) {
	if ($ordem <> "" && $ordem <> "undefined") {
		if ($buscaAjx2 == 20) {
			$e = explode(":", $ordem);
			$campoId = $e[0];
			$cod_usu = $e[1];
			if ($campoId <> "" && $campoId <> "undefined") {
				$montaUpdate .= "update " . $tabela . " set NUM_ORDENAC = " . $contaLoop . ", COD_USUARIO_ORDENAC = 0" . $cod_usu . " where " . $campo . " = " . $campoId . "; " . PHP_EOL;
			}
		} else {
			$campoId = trim($ordem);
			$montaUpdate .= "update " . $tabela . " set NUM_ORDENAC = " . $contaLoop . " where " . $campo . " = " . $campoId . "; " . PHP_EOL;
		}
		$contaLoop++;
	}
}

//fnEscreve("aee....");
fnEscreve($montaUpdate);
//fnEscreve($buscaAjx1);
//fnEscreve($buscaAjx2);
//fnMostraForm();

//update da ordenação
$sql2 = $montaUpdate;
if ($buscaAjx2 == 20) {
	$arrayQuery2 = mysqli_multi_query($connAdmSAC->connAdm(), $sql2) or die(mysqli_error());
} else {
	$arrayQuery2 = mysqli_multi_query($connAdm->connAdm(), $sql2) or die(mysqli_error());
}
//$qrOrdena = mysqli_fetch_assoc($arrayQuery2);
//fnEscreve($sql2);		
