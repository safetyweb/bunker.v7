<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_POST['COD_EMPRESA']));
	$nom_arq = fnLimpaCampo($_POST['NOM_ARQ']);
	$campo = fnLimpaCampo(ltrim($_POST['CAMPO'],"arqUpload_"));

	$sqlControle = "SELECT * FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

	// fnEscreve($sqlControle);

	$arrayControle = mysqli_query(connTemp($cod_empresa,''),$sqlControle);

	// try {mysqli_query(connTemp($cod_empresa,''),$sqlControle);} 
 //                            catch (mysqli_sql_exception $e) {fnEscreve($e);}
 //                            fnEscreve($e);

	$qrCont = mysqli_fetch_assoc($arrayControle);

	if(mysqli_num_rows($arrayControle) == 0){

		$sqlIns = "INSERT INTO CONTROLE_TERMO(
							      COD_EMPRESA,
							      TXT_ACEITE,
								  TXT_COMUNICA,
								  LOG_SEPARA,
								  COD_USUCADA
							   ) VALUES(
							   	  $cod_empresa,
							   	  'Estou ciente e de acordo com os termos, e desejo me cadastrar:',
							   	  'Comunicação',
							   	  'N',
							   	  $_SESSION[SYS_COD_USUARIO]
							   ); ";

		$sqlIns .= "INSERT INTO TERMOS_EMPRESA 
					(COD_EMPRESA, COD_TIPO, NOM_TERMO, ABV_TERMO, LOG_ATIVO, DES_TERMO, COD_USUCADA) 
					VALUES 
					($cod_empresa, 1, 'Termos de Uso', 'Termos de Uso', 'S', '', $_SESSION[SYS_COD_USUARIO]),
					($cod_empresa, 1, 'Política de Privacidade', 'Política de Privacidade', 'S', '', $_SESSION[SYS_COD_USUARIO]),
					($cod_empresa, 1, 'Regulamento de Uso do Programa', 'Regulamento', 'S', '', $_SESSION[SYS_COD_USUARIO]),
					($cod_empresa, 2, 'Autorização de email', 'email', 'S', '', $_SESSION[SYS_COD_USUARIO]),
					($cod_empresa, 3, 'Autorização de SMS', 'SMS', 'S', '', $_SESSION[SYS_COD_USUARIO]),
					($cod_empresa, 4, 'Autorização de WhatsApp', 'WhatsApp', 'S', '', $_SESSION[SYS_COD_USUARIO]),
					($cod_empresa, 5, 'Autorização de Push', 'Push', 'S', '', $_SESSION[SYS_COD_USUARIO]),
					($cod_empresa, 6, 'Ofertas personalizadas', 'Ofertas', 'S', '', $_SESSION[SYS_COD_USUARIO]),
					($cod_empresa, 7, 'Autorização de Telemarketing', 'Telemarketing', 'S', '', $_SESSION[SYS_COD_USUARIO]); ";

		// fnEscreve($sqlIns);

		mysqli_multi_query(connTemp($cod_empresa,''),$sqlIns);

	}

	$sql = "UPDATE CONTROLE_TERMO
			SET $campo = '$nom_arq'
			WHERE COD_EMPRESA = $cod_empresa";
	//fnEscreve($sql);
	mysqli_query(connTemp($cod_empresa,''),$sql);

?>