<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
	$cod_persona = fnLimpaCampoZero($_POST['COD_PERSONA_TKT']);
	$cod_template = fnLimpaCampoZero($_POST['COD_TEMPLATE']);
	$qtd_emailok = fnLimpaCampoZero($_POST['QTD_EMAILOK']);
	$qtd_emailnok = fnLimpaCampoZero($_POST['QTD_EMAILNOK']);
	$qtd_cliente = fnLimpaCampoZero($_POST['QTD_CLIENTE']);
	$des_template = addslashes(htmlentities($_POST['DES_TEMPLATE']));
	$des_assunto = fnLimpaCampo($_POST['DES_ASSUNTO']);
	$des_remet = fnLimpaCampo($_POST['DES_REMET']);
	$des_emailex = fnLimpaCampo($_POST['DES_EMAILEX']);

	$sql = "select * from VARIAVEIS order by NUM_ORDENAC ";
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());

	while ($qrListaVariaveis = mysqli_fetch_assoc($arrayQuery))
	  {

		if (strlen(strstr($des_template,$qrListaVariaveis['KEY_BANCOVAR']))>0){ 
			//fnEscreve($qrListaVariaveis['NOM_BANCOVAR']);
			$cod_bancovar = $cod_bancovar.$qrListaVariaveis['COD_BANCOVAR'].",";
		} 
	  
	  }
	  
	$cod_bancovar = substr($cod_bancovar,0,-1);

	$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

	$sql = "";

	if($cod_template == 0){

		$sql .= "INSERT INTO TEMPLATE_EMAIL(
							COD_EMPRESA,
							LOG_ATIVO,
							NOM_TEMPLATE,
							ABV_TEMPLATE,
							DES_TEMPLATE,
							COD_USUCADA
			   	  		)VALUES( 
							$cod_empresa,
							'S',
							'TEMPLATE ENVIO - ".date("d/m/Y H:i:s")." (Automática)',
							'ENV',
							'Template gerada automaticamente via envio, em ".date("d/m/Y H:i:s")."',
							$cod_usucada
						); ";

		$cod_template = "(SELECT MAX(COD_TEMPLATE) FROM TEMPLATE_EMAIL WHERE COD_EMPRESA = $cod_empresa AND COD_USUCADA = $cod_usucada)";

		$sql .= "INSERT INTO MODELO_EMAIL(
								COD_EMPRESA,
								COD_TEMPLATE,
								DES_ASSUNTO,
								DES_REMET,
								DES_TEMPLATE,
								COD_BANCOVAR,
								COD_USUCADA
								) VALUES(
								$cod_empresa,
								$cod_template,
								'$des_assunto',
								'$des_remet',
								'$des_template',
								'$cod_bancovar',
								$cod_usucada
							); ";

	}else{
		
		$sql .= "UPDATE MODELO_EMAIL SET 
							DES_ASSUNTO = '$des_assunto', 
							DES_REMET = '$des_remet', 
							DES_TEMPLATE = '$des_template', 
							COD_BANCOVAR = '$cod_bancovar' 
					WHERE COD_TEMPLATE = $cod_template; ";

	}


	$sql .= "INSERT INTO CONTROLE_ENVIO_SIMPLES(
						 COD_EMPRESA,
						 COD_PERSONA,
						 QTD_EMAILOK,
						 QTD_EMAILNOK,
						 QTD_CLIENTE,
						 COD_USUCADA 
						) VALUES(
						 $cod_empresa,
						 $cod_persona,
						 '$qtd_emailok',
						 '$qtd_emailnok',
						 '$qtd_cliente',
						 $cod_usucada
						 ); ";

	mysqli_multi_query(connTemp($cod_empresa,''),$sql);

	$sql = "SELECT MAX(COD_CONTROLE) AS COD_CONTROLE FROM CONTROLE_ENVIO_SIMPLES WHERE COD_USUCADA = $cod_usucada AND COD_EMPRESA = $cod_empresa";
	$qrCodCon = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

	if($cod_template == "(SELECT MAX(COD_TEMPLATE) FROM TEMPLATE_EMAIL WHERE COD_EMPRESA = $cod_empresa AND COD_USUCADA = $cod_usucada)"){

		$sql = "SELECT MAX(COD_MODELO) AS COD_MODELO FROM MODELO_EMAIL WHERE COD_EMPRESA = $cod_empresa AND COD_USUCADA = $cod_usucada";
		

	}else{

		$sql = "SELECT COD_MODELO FROM MODELO_EMAIL WHERE COD_EMPRESA = $cod_empresa AND COD_TEMPLATE = $cod_template";

	}

	$qrCodMod = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

	$cod_modelo = $qrCodMod['COD_MODELO'];

	// inserção dos clientes e emails extras
	$insertCli = "";

	if($des_emailex != ""){
		$emails_extras = explode(";", $des_emailex);

		foreach ($emails_extras as $email) {

			$insertCli .= "(
							$cod_empresa,
							'".$qrCodCon['COD_CONTROLE']."',
							'99999',
							'0',
							'".$cod_modelo."',
							'Administrativo',
							'".$email."',
							'3',
							'".date('d/m/Y')."',
							'99999',
							$cod_usucada
						  ),";

		}

	}

	$sqlCli = "SELECT DISTINCT CL.COD_CLIENTE, CL.NOM_CLIENTE, CL.DES_EMAILUS, 
				CL.NUM_CGCECPF, CL.COD_UNIVEND, CL.COD_SEXOPES, CL.DAT_NASCIME 
				FROM PERSONACLASSIFICA PC 
			    LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = PC.COD_CLIENTE
			   WHERE PC.COD_PERSONA = $cod_persona";

	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlCli);

	while($qrCli = mysqli_fetch_assoc($arrayQuery)){

		if($qrCli['DES_EMAILUS'] != ''){

			$insertCli .= "(
							$cod_empresa,
							'".$qrCodCon['COD_CONTROLE']."',
							'".$qrCli['COD_CLIENTE']."',
							'".$qrCli['COD_UNIVEND']."',
							'".$cod_modelo."',
							'".$qrCli['NOM_CLIENTE']."',
							'".$qrCli['DES_EMAILUS']."',
							'".$qrCli['COD_SEXOPES']."',
							'".$qrCli['DAT_NASCIME']."',
							'".$qrCli['NUM_CGCECPF']."',
							$cod_usucada
						  ),";

		}

	}

	$insertCli = rtrim($insertCli, ',');

	if($insertCli != ''){

		$sql = "INSERT INTO ENVIO_SIMPLES_EMAIL(
								COD_EMPRESA,
								COD_CONTROLE,
								COD_CLIENTE,
								COD_UNIVEND,
								COD_MODELO,
								NOM_CLIENTE,
								DES_EMAILUS,
								COD_SEXOPES,
								DAT_NASCIMEN,
								NUM_CGCECPF,
								COD_USUCADA
							)VALUES $insertCli;";
		fnEscreve($sql);
		fnTestesql(connTemp($cod_empresa,''),$sql);
	}


?>