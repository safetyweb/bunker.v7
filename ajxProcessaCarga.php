<?php 

	header('Content-Type: application/json');
	include '_system/_functionsMain.php';

	// $itens_por_pagina = $_GET['itens_por_pagina'];	
	// $pagina = $_GET['idPage'];
	$opcao = fnLimpaCampo($_GET['opcao']);
	$objLojas = 'LOJAS_'.$opcao;
	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$lojasSelecionadas = fnLimpaCampo($_REQUEST[$objLojas]);
	$sql = "";

	//array das unidades de venda
	if (isset($_POST['COD_UNIVEND'])){
		$Arr_COD_UNIVEND = $_POST['COD_UNIVEND'];
		//print_r($Arr_COD_MULTEMP);			 
	 
	   for ($i=0;$i<count($Arr_COD_UNIVEND);$i++) 
	   { 
		$cod_univend = $cod_univend.$Arr_COD_UNIVEND[$i].",";
	   } 
	   
	   $cod_univend = rtrim($cod_univend,',');
		
	}else{$cod_univend = "0";}

	if($cod_univend == "9999"){
		$cod_univend = $lojasSelecionadas;
	}

	$retorno = array();

	switch ($opcao) {

		case 'Analytics':

			$dt_filtro = fnLimpaCampo($_REQUEST['DT_FILTRO']);
			$dat_ini = fnmesanosql("01/".$_REQUEST['DAT_INI_ANALYTICS']);

			if($dt_filtro == 0){
				$dt_filtro = $dat_ini;
			}else{
				$sqlDelete = "DELETE FROM TB_FECHAMENTO_CLIENTE WHERE COD_EMPRESA = $cod_empresa AND MESANO = '$dt_filtro'; ";
				mysqli_query(connTemp($cod_empresa,''),$sqlDelete);
			}

			$sql = "CALL SP_CARGA_ANALYTICS ( '$dt_filtro' , $cod_empresa); ";
			$retorno["sql"] = $sql;
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

		break;
		
		default:

			$cod_filtro = fnLimpaCampoZero($_REQUEST['COD_FILTRO']);
			$dat_ini = $_REQUEST['DAT_INI_FUNIL'];
			list($mes,$ano) = explode("/",$dat_ini);
/*
			if($cod_filtro != 0){

				$sqlBusca = "SELECT DT_FILTRO FROM FILTRO_FREQUENCIA WHERE COD_FILTRO = $cod_filtro AND COD_EMPRESA = $cod_empresa";
				$qrDat = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlBusca));

				$dt_filtro = fnMesAnoSql($qrDat['DT_FILTRO']);

				$sqlDelete = "DELETE FROM FREQUENCIA_RESULTADO WHERE COD_FILTRO = $cod_filtro AND COD_EMPRESA = $cod_empresa; 
							  DELETE FROM FILTRO_FREQUENCIA WHERE COD_FILTRO = $cod_filtro AND COD_EMPRESA = $cod_empresa;";
				mysqli_multi_query(connTemp($cod_empresa,''),$sqlDelete);
			}else{

				$dt_filtro = $dat_ini;

			}

			$sql = "CALL SP_RELAT_LUCRO_FREQUENCIA_UNIVEND ( '$cod_univend' , $cod_empresa , 0 , '$dt_filtro' ); ";
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			//fnEscreve($sql);
			*/
			$sql = "CALL SP_REFAZ_FECHAMENTO_CLIENTE ('".$ano."-".$mes."'); ";
			$retorno["sql"] = $sql;
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die("Erro ao executar rotina.");
			$rs = mysqli_fetch_assoc($arrayQuery);
			$retorno["proc"] = $rs;
		break;

	}

	// fnEscreve($arrayQuery);

	// if(strstr($arrayQuery, 'exception')){
	// 	$opcao= 'erro';
	// }

	$retorno["link"] = "https://adm.bunker.mk/action.do?mod=".fnEncode(1541)."&id=".fnEncode($cod_empresa)."&pop=true&msg=".$opcao;
	echo json_encode($retorno);

?>