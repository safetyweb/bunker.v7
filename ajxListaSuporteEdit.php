<?php 

	include '_system/_functionsMain.php'; 	

	//echo fnDebug('true');	

	$cod_chamado = fnLimpaCampoZero($_POST['pk']);
	$campo = fnLimpaCampo($_POST['name']);
	$valor = fnLimpaCampo($_POST['value']);

	if (strpos($valor, ',') !== false) {
	    $valor = fnValorSql($valor);
	}
	if($valor == ''){
		$sql = "UPDATE SAC_CHAMADOS SET $campo = NULL WHERE COD_CHAMADO = $cod_chamado";
	}else{
		if($campo == 'DAT_ENTREGA' || $campo == 'DAT_INICIO'){
			$sql = "UPDATE SAC_CHAMADOS SET $campo = '".fndatasql($valor)."' WHERE COD_CHAMADO = $cod_chamado";
		}else{
			$sql = "UPDATE SAC_CHAMADOS SET $campo = '$valor' WHERE COD_CHAMADO = $cod_chamado";
		}
	}

	fnEscreve($sql);
	fnTestesql($connAdmSAC->connAdm(),$sql);

	$sqlSac = "SELECT COD_CHAMADO,
					COD_STATUS, 
					COD_EMPRESA,
					DAT_ENTREGA,
					DES_PREVISAO,
					COD_USUARIO_ORDENAC,
					DAT_INICIO
			   FROM SAC_CHAMADOS 
			   WHERE COD_CHAMADO = $cod_chamado";
	$qrSac = mysqli_fetch_assoc(mysqli_query($connAdmSAC->connAdm(),$sqlSac));

	echo "<input class='COD_CHAMADO' value='".$qrSac["COD_CHAMADO"]."'>";
	// echo "<textarea class='DAT_ENTREGA'>".fnFormataDataEntregaSAC($qrSac)."</textarea>";


	if($campo == "DAT_PROXINT"){

		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
		$cod_status = $qrSac['COD_STATUS'];
		$cod_empresa = $qrSac['COD_EMPRESA'];
		$tp_comentario = 1;
		
		if($valor == ''){
			$des_comentario = "Alterou que a data da <i>próxima interação</i> está para <i>ser definida</i> <br/>";
		}else{
			$des_comentario = "Alterou a data prevista da <i>próxima interação</i> para <i>".fnDataShort($valor)."</i> <br/>";
		}

		$sqlComent = "INSERT INTO SAC_COMENTARIO(
							COD_CHAMADO,
							DES_COMENTARIO,
							TP_COMENTARIO,
							COD_EMPRESA,
							COD_USUARIO,
							DAT_CADASTRO,
							COD_COR,
							COD_STATUS
							) VALUES(
							'$cod_chamado',
							'$des_comentario',
							'$tp_comentario',
							'$cod_empresa',
							$cod_usucada,
							NOW(),
							2,
							'$cod_status'
							)";

		mysqli_query($connAdmSAC->connAdm(),$sqlComent);

		$tipo_email = "Comentado";
		$novo_chamado = "Atualização - ";
		$cod_chamado_sql = $cod_chamado;

		/////////////////--Envio do Email--/////////////////
		/**/       include 'envioEmailSac.php';		    /**/
		////////////////////////////////////////////////////

	}
						
?>