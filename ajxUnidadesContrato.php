<?php 

	include '_system/_functionsMain.php'; 	

	//echo fnDebug('true');	

	$cod_univend = fnLimpaCampoZero(fnDecode($_POST['COD_UNIVEND']));
	$cod_empresa = fnLimpaCampoZero(fnDecode($_POST['COD_EMPRESA']));
	$cod_contrat = fnLimpaCampoZero(fnDecode($_POST['COD_CONTRAT']));
	if (empty($_POST['LOG_UNIVEND'])) {$log_unidade='N';}else{$log_unidade=$_POST['LOG_UNIVEND'];}
	$sql = "";

	if($log_unidade == "S"){

		$sql .= "DELETE FROM CONTRATO_UNIDADE WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVEND = $cod_univend AND COD_CONTRAT = $cod_contrat; ";

		$sql .= "INSERT INTO CONTRATO_UNIDADE(
							COD_EMPRESA, 
							COD_UNIVEND,
							COD_CONTRAT
							) VALUES(
							$cod_empresa, 
							$cod_univend,
							$cod_contrat
							); ";
	}else{
		$sql .= "DELETE FROM CONTRATO_UNIDADE WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVEND = $cod_univend AND COD_CONTRAT = $cod_contrat; ";
	}

	$sql .= "UPDATE EMPRESA_CONTRATO SET 
					QTD_LOJA = (SELECT COUNT(COD_UNICONT) FROM CONTRATO_UNIDADE WHERE COD_CONTRAT = $cod_contrat) 
			WHERE COD_CONTRATO = $cod_contrat; ";

	// fnEscreve($sql);
	mysqli_multi_query($connAdm->connAdm(),$sql);

	sleep(1);

	$sql = "SELECT QTD_LOJA, TIP_CONTRATO, VL_CONTRATO, VL_PARCERIA FROM EMPRESA_CONTRATO WHERE COD_CONTRATO = $cod_contrat";
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
	
	$qrListaEmpresas = mysqli_fetch_assoc($arrayQuery);

	$totLiquido = 0;  

	if($qrListaEmpresas['TIP_CONTRATO'] =='U'){
		$tipContrato = 'Unidade';
		$multiplicador = $qrListaEmpresas['QTD_LOJA'];
		
	}else if($qrListaEmpresas['TIP_CONTRATO'] =='C'){
      	$tipContrato = 'Contrato';
		$multiplicador = 1;
    }else{
    	$tipContrato = null;
		$multiplicador = 1;
    }

    $vl_bruto = $qrListaEmpresas['VL_CONTRATO']*$multiplicador;
    $vl_desconto = ($vl_bruto*0.18) + ($vl_bruto*($qrListaEmpresas['VL_PARCERIA']/100));
    $vl_liquido = $vl_bruto-$vl_desconto;

    $totLiquido+=$vl_liquido;

    echo fnValor($totLiquido,2);
						
?>