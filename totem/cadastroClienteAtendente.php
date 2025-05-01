<?php

$log_cadastro = 'N';

$key_des_token = fnLimpaCampo($_REQUEST['KEY_DES_TOKEN']);
if($key_des_token == "") $key_des_token = fnLimpaCampo(fnLimpaDoc($_REQUEST['DES_TOKEN']));

// echo "<h1>_".$_REQUEST['KEY_DES_TOKEN']."_</h1><br/>";
// echo "<h1>_".$_REQUEST['DES_TOKEN']."_</h1><br/>";

$k_num_cartao = fnLimpaCampo($_REQUEST['KEY_NUM_CARTAO']);
$k_num_celular = fnLimpaCampo($_REQUEST['KEY_NUM_CELULAR']);
$k_cod_externo = fnLimpaCampo($_REQUEST['KEY_COD_EXTERNO']);
$k_num_cgcecpf = fnLimpaDoc(fnLimpaCampo($_REQUEST['KEY_NUM_CGCECPF']));
$k_dat_nascime = fnLimpaCampo($_REQUEST['KEY_DAT_NASCIME']);
$k_des_emailus = fnLimpaCampo($_REQUEST['KEY_DES_EMAILUS']);

$log_novocli = fnLimpaCampo($_REQUEST['LOG_NOVOCLI']);

$cad_nom_cliente = fnLimpaCampo($_REQUEST['CAD_NOM_CLIENTE']);
$cad_num_cgcecpf = fnLimpaCampo($_REQUEST['CAD_NUM_CGCECPF']);
$cad_cod_sexopes = fnLimpaCampo($_REQUEST['CAD_COD_SEXOPES']);
$cad_num_cartao = fnLimpaCampo($_REQUEST['CAD_NUM_CARTAO']);
$cad_des_emailus = fnLimpaCampo($_REQUEST['CAD_DES_EMAILUS']);
$cad_des_enderec = fnLimpaCampo($_REQUEST['CAD_DES_ENDEREC']);
$cad_num_enderec = fnLimpaCampo($_REQUEST['CAD_NUM_ENDEREC']);
$cad_des_bairroc = fnLimpaCampo($_REQUEST['CAD_DES_BAIRROC']);
$cad_des_complem = fnLimpaCampo($_REQUEST['CAD_DES_COMPLEM']);
$cad_des_cidadec = fnLimpaCampo($_REQUEST['CAD_DES_CIDADEC']);
$cad_cod_estadof = fnLimpaCampo($_REQUEST['CAD_COD_ESTADOF']);
$cad_num_cepozof = fnLimpaCampo($_REQUEST['CAD_NUM_CEPOZOF']);
$cad_dat_nascime = fnLimpaCampo($_REQUEST['CAD_DAT_NASCIME']);
$cad_num_celular = fnLimpaCampo($_REQUEST['CAD_NUM_CELULAR']);
$cad_cod_profiss = fnLimpaCampo($_REQUEST['CAD_COD_PROFISS']);
$cad_cod_atendente = fnLimpaCampo($_REQUEST['CAD_COD_ATENDENTE']);
$cad_des_senhaus = fnLimpaCampo(fnDecode($_REQUEST['CAD_DES_SENHAUS']));

$sqlCampo = "SELECT COD_CHAVECO FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";

$arrayCampo = mysqli_query($connAdm->connAdm(),$sqlCampo);

$lastField = "";

$qrCampos = mysqli_fetch_assoc($arrayCampo);

switch ($qrCampos[COD_CHAVECO]) {

	case 2:
		$cpf = fnLimpaDoc($k_num_cartao);
		$cartao = fnLimpaDoc($k_num_cartao);
	break;

	case 3:
		$cpf = fnLimpaDoc($k_num_celular);
		$cartao = fnLimpaDoc($k_num_celular);
	break;

	case 4:
		$cpf = fnLimpaDoc($k_cod_externo);
		$cartao = fnLimpaDoc($k_cod_externo);
	break;

	case 5:
		$cpf = fnLimpaDoc($k_num_cgcecpf);
		$cartao = fnLimpaDoc($k_num_cgcecpf);
	break;

	default:
		$cpf = fnLimpaDoc($k_num_cgcecpf);
		$cartao = fnLimpaDoc($k_num_cartao);
	break;

}

if($cad_nom_cliente != "")
	$nome = trim($cad_nom_cliente);
if($cad_num_cgcecpf != "")
	$cpf = trim($cad_num_cgcecpf);
if($cad_cod_sexopes != "")
	$sexo = trim($cad_cod_sexopes);
if($cad_num_cartao != "")
	$cartao = trim($cad_num_cartao);
if($cad_des_emailus != "")
	$email = trim($cad_des_emailus);
if($cad_des_enderec != "")
	$endereco = trim($cad_des_enderec);
if($cad_num_enderec != "")
	$numero = trim($cad_num_enderec);
if($cad_des_bairroc != "")
	$bairro = trim($cad_des_bairroc);
if($cad_des_complem != "")
	$complemento = trim($cad_des_complem);
if($cad_des_cidadec != "")
	$cidade = trim($cad_des_cidadec);
if($cad_cod_estadof != "")
	$estado = trim($cad_cod_estadof);
if($cad_num_cepozof != "")
	$cep = trim($cad_num_cepozof);
if($cad_dat_nascime != "")
	$dt_nascimento = trim($cad_dat_nascime);
if($cad_num_celular != "")
	$telefone = trim($cad_num_celular);
if($cad_cod_profiss != "")
	$profissao = trim($cad_cod_profiss);
if($cad_cod_atendente != "")
	$codatendente = trim($cad_cod_atendente);
if($cad_des_senhaus != "")
	$senha = trim($cad_des_senhaus);

$sqlCampos = "SELECT NOM_CAMPOOBG, 
					 NOM_CAMPOOBG, 
					 DES_CAMPOOBG, 
					 MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG AS CAT_CAMPO, 
					 INTEGRA_CAMPOOBG.TIP_CAMPOOBG AS TIPO_DADO, 
					 COL_MD, 
					 COL_XS, 
					 CLASSE_INPUT, 
					 CLASSE_DIV 
				FROM MATRIZ_CAMPO_INTEGRACAO                         
				LEFT JOIN INTEGRA_CAMPOOBG ON INTEGRA_CAMPOOBG.COD_CAMPOOBG=MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG                         
				WHERE MATRIZ_CAMPO_INTEGRACAO.COD_EMPRESA = $cod_empresa
				ORDER BY MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG, MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG ASC";

$arrayFields = mysqli_query($connAdm->connAdm(),$sqlCampos);

// echo($sqlCampos);

$lastField = "";
$fields = "COD_EMPRESA, ";
$values = $cod_empresa.",";

while($qrCampos = mysqli_fetch_assoc($arrayFields)){ 

	$colMd = $qrCampos[COL_MD];
	$colXs = $qrCampos[COL_XS];
	$required = "";
	$dataError = "";

	if($lastField == ""){
		$lastField = $qrCampos[NOM_CAMPOOBG];
	}else if($lastField == $qrCampos[NOM_CAMPOOBG]){
		continue;
	}else{
		$lastField = $qrCampos[NOM_CAMPOOBG];
	}

	switch ($qrCampos[DES_CAMPOOBG]) {

		case 'NOM_CLIENTE':

			if($_REQUEST[$qrCampos[DES_CAMPOOBG]] != ""){

				$nome = fnLimpaCampo($_REQUEST[$qrCampos[DES_CAMPOOBG]]);
				
			}

		break;
		
		case 'COD_SEXOPES':

			if($_REQUEST[$qrCampos[DES_CAMPOOBG]] != ""){

				$sexo = fnLimpaCampoZero($_REQUEST[$qrCampos[DES_CAMPOOBG]]);

			}else{
				$sexo = 3;
			}

		break;
		
		case 'DES_EMAILUS':

			if($_REQUEST[$qrCampos[DES_CAMPOOBG]] != ""){

				$email = fnLimpaCampo($_REQUEST[$qrCampos[DES_CAMPOOBG]]);

			}

		break;
		
		case 'NUM_CELULAR':

			if($_REQUEST[$qrCampos[DES_CAMPOOBG]] != ""){

				$telefone = fnLimpaCampo(fnLimpaDoc($_REQUEST[$qrCampos[DES_CAMPOOBG]]));

			}

		break;
		
		case 'NUM_CARTAO':

			if($_REQUEST[$qrCampos[DES_CAMPOOBG]] != ""){

				$cartao = fnLimpaCampo(fnLimpaDoc($_REQUEST[$qrCampos[DES_CAMPOOBG]]));

			}

		break;

		case 'NUM_CGCECPF':

			if($_REQUEST[$qrCampos[DES_CAMPOOBG]] != ""){

				$cpf = fnLimpaCampo(fnLimpaDoc($_REQUEST[$qrCampos[DES_CAMPOOBG]]));

			}

		break;
		
		
		case 'DAT_NASCIME':

			if($_REQUEST[$qrCampos[DES_CAMPOOBG]] != ""){

				$dt_nascimento = fnLimpaCampo($_REQUEST[$qrCampos[DES_CAMPOOBG]]);

			}

		break;
		
		case 'COD_PROFISS':

			if($_REQUEST[$qrCampos[DES_CAMPOOBG]] != ""){

				$profissao = fnLimpaCampoZero($_REQUEST[$qrCampos[DES_CAMPOOBG]]);

			}

		break;
		
		case 'COD_ATENDENTE':

			if($_REQUEST[$qrCampos[DES_CAMPOOBG]] != ""){

				$codatendente = fnLimpaCampoZero($_REQUEST[$qrCampos[DES_CAMPOOBG]]);

			}

		break;
		
		case 'DES_ENDEREC':

			if($_REQUEST[$qrCampos[DES_CAMPOOBG]] != ""){

				$endereco = fnLimpaCampo(fnAcentos($_REQUEST[$qrCampos[DES_CAMPOOBG]]));

			}

		break;
		
		case 'NUM_ENDEREC':

			if($_REQUEST[$qrCampos[DES_CAMPOOBG]] != ""){

				$numero = fnLimpaCampo($_REQUEST[$qrCampos[DES_CAMPOOBG]]);

			}

		break;
		
		case 'NUM_CEPOZOF':

			if($_REQUEST[$qrCampos[DES_CAMPOOBG]] != ""){

				$cep = fnLimpaCampo($_REQUEST[$qrCampos[DES_CAMPOOBG]]);

			}

		break;
		
		case 'COD_ESTADOF':

			if($_REQUEST[$qrCampos[DES_CAMPOOBG]] != ""){

				$estado = fnLimpaCampo($_REQUEST[$qrCampos[DES_CAMPOOBG]]);

			}

		break;
		
		case 'NOM_CIDADEC':

			if($_REQUEST[$qrCampos[DES_CAMPOOBG]] != ""){

				$cidade = fnLimpaCampo(fnAcentos($_REQUEST[$qrCampos[DES_CAMPOOBG]]));

			}

		break;
		
		case 'DES_BAIRROC':

			if($_REQUEST[$qrCampos[DES_CAMPOOBG]] != ""){

				$bairro = fnLimpaCampo(fnAcentos($_REQUEST[$qrCampos[DES_CAMPOOBG]]));

			}

		break;
		
		case 'DES_COMPLEM':

			if($_REQUEST[$qrCampos[DES_CAMPOOBG]] != ""){

				$complemento = fnLimpaCampo(fnAcentos($_REQUEST[$qrCampos[DES_CAMPOOBG]]));

			}

		break;
		
		

		default:

			// $cpf = $_REQUEST[$qrCampos[DES_CAMPOOBG]];

		break;

	}


}

if($_REQUEST[DES_SENHAUS] != ""){

	$senha = trim($_REQUEST[DES_SENHAUS]);

}

if($_REQUEST[COD_UNIVEND] != "" && $_REQUEST[COD_UNIVEND] != 0){

	$arrayCampos[2] = $_REQUEST[COD_UNIVEND];

}

if($sexo == 0 || $sexo == ""){
	$sexo = 3;
}

if(trim($cartao) == "" || trim($cartao) == "0"){
	$cartao = $cpf;
}

if(!isset($canal)){
	$canal = 3;
}

$sqlControle = "SELECT LOG_LGPD FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

// fnEscreve($sqlControle);

$arrayControle = mysqli_query(connTemp($cod_empresa,''),$sqlControle);

$qrControle = mysqli_fetch_assoc($arrayControle);

$log_lgpd = $qrControle['LOG_LGPD'];

if($log_lgpd == 'S'){

	$adesao = "CT";

}else{

	$adesao = "ST";

}

if($_REQUEST[COD_ATENDENTE] != "" && $_REQUEST[COD_ATENDENTE] != 0){
	$codatendente = $_REQUEST[COD_ATENDENTE];
}else if($codatendente == 0 || $codatendente == ""){
	$codatendente = $_SESSION["USU_COD_USUARIO"];
}


// exit();
//dados atualiza cadastro
$dadosatualiza=Array('nome'=>$nome,
                        'sexo'=>$sexo,
                        'email'=>$email,
                        'telefone'=>$telefone,
                        'cpf'=>$cpf,
                        'cartao'=>$cartao,
                        'nome'=>$nome,
                        'dt_nascimento'=>$dt_nascimento,
                        'profissao'=>$profissao,
						'codatendente'=>$codatendente,
						'senha'=>$senha,
						'endereco' =>$endereco,
						'numero' =>$numero,
						'cep' =>$cep,
						'estado' =>$estado,
						'cidade' =>$cidade,
						'bairro' =>$bairro,
						'complemento' =>$complemento,
						'canal' =>$canal,
						'tokencadastro'=>$key_des_token,
						'adesao'=>$adesao
                   );


// if($cpf == "39648555885"){
// 	echo "<pre>";
// 	print_r($dadosatualiza);
// 	echo("_".$_POST[COD_ATENDENTE]."_");
// 	echo "</pre>";
// 	exit();
// }
$atualiza=atualizacadastro($dadosatualiza, $arrayCampos);


if($atualiza == "Registro inserido!" || $atualiza == "Cadastro Atualizado !"){

	if($atualiza == "Registro inserido!"){
		$atualiza = "realizado";
	}else{
		$atualiza = "atualizado";
	}

	$sqlCliente = "SELECT COD_CLIENTE, COD_USUCADA, DES_TOKEN FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND NUM_CGCECPF = $dadosatualiza[cpf]";
	$qrBuscaCliente =  mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCliente));

	$cod_cliente = $qrBuscaCliente['COD_CLIENTE'];
	$cod_usucada_cli = $qrBuscaCliente['COD_USUCADA'];
	$tokenCad = $qrBuscaCliente['DES_TOKEN'];

	if($cod_usucada_cli == 9999){

		$sqlCanal = "SELECT 1 FROM LOG_CANAL WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE = $cod_cliente";
		$arrayCanal =  mysqli_query(connTemp($cod_empresa,''),$sqlCanal);

		if(mysqli_num_rows($arrayCanal) == 0){

			if(!isset($tipoAtiv)){
				$tipoAtiv = 2;
			}

			$atualizastaatustoken = "UPDATE GERATOKEN 
										SET LOG_USADO='2', 
										COD_CLIENTE = $cod_cliente, 
										DAT_USADO = NOW() 
									 WHERE DES_TOKEN='$tokenCad' 
									 AND TIP_TOKEN='1' 
									 AND COD_EMPRESA = $cod_empresa";

			mysqli_query(connTemp($cod_empresa,''),$atualizastaatustoken);

			$sqlUniv = "SELECT COD_UNIVEND FROM GERATOKEN 
						WHERE DES_TOKEN='$tokenCad'
						AND COD_CLIENTE = $cod_cliente
						AND COD_EMPRESA = $cod_empresa";

			$arrayUniv = mysqli_query(connTemp($cod_empresa,''),$sqlUniv);

			$qrUniv = mysqli_fetch_assoc($arrayUniv);

			$sqlInsCanal = "INSERT INTO LOG_CANAL(
											COD_EMPRESA,
											COD_UNIVEND,
											COD_CLIENTE,
											COD_CANAL,
											COD_TIPO
										) VALUES(
											$cod_empresa,
											$qrUniv[COD_UNIVEND],
											$cod_cliente,
											$canal,
											$tipoAtiv
										)";

            mysqli_query(connTemp($cod_empresa,''),$sqlInsCanal);

            $attUnivCli = "UPDATE CLIENTES 
										SET COD_UNIVEND = $qrUniv[COD_UNIVEND],
										DAT_CADASTRO = NOW()
									 WHERE COD_CLIENTE = $cod_cliente 
									 AND COD_EMPRESA = $cod_empresa";

			mysqli_query(connTemp($cod_empresa,''),$attUnivCli);

		}

	}

	// $sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa ORDER BY NUM_ORDENAC";

	// $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

	// $sqlDelTermos = "DELETE FROM CLIENTES_TERMOS 
	// 				  WHERE COD_CLIENTE = $cod_cliente
	// 				  AND COD_EMPRESA = $cod_empresa";

	// mysqli_query(connTemp($cod_empresa,''),$sqlDelTermos);

	// $sqlBlc = "";
	// $termos = "";
	// while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)){

	// 	if (!empty($_REQUEST["TERMOS_".$qrBuscaFAQ[COD_BLOCO]])) {

	// 		$sqlBlc = "INSERT INTO CLIENTES_TERMOS(
	// 									COD_EMPRESA,
	// 									COD_CLIENTE,
	// 									COD_BLOCO,
	// 									COD_TERMOS
	// 								) VALUES(
	// 									$cod_empresa,
	// 									$cod_cliente,
	// 									$qrBuscaFAQ[COD_BLOCO],
	// 									'$qrBuscaFAQ[COD_TERMO]'
	// 								)";

	// 		// fnEscreve($sqlBlc);

	// 		mysqli_query(connTemp($cod_empresa,''),$sqlBlc);

	// 		$termos .= $qrBuscaFAQ[COD_TERMO].",";					


	// 	}

	// }

	// $logsAtt = "";

	// $termos = rtrim($termos,',');

	// $sqlTer = "SELECT 
	// 			(SELECT COUNT(COD_TIPO) FROM TERMOS_EMPRESA WHERE COD_EMPRESA = $cod_empresa AND COD_TIPO = 2 AND COD_TERMO IN($termos)) AS ACC_EMAIL,
	// 			(SELECT COUNT(COD_TIPO) FROM TERMOS_EMPRESA WHERE COD_EMPRESA = $cod_empresa AND COD_TIPO = 3 AND COD_TERMO IN($termos)) AS ACC_SMS,
	// 			(SELECT COUNT(COD_TIPO) FROM TERMOS_EMPRESA WHERE COD_EMPRESA = $cod_empresa AND COD_TIPO = 4 AND COD_TERMO IN($termos)) AS ACC_WHATS,
	// 			(SELECT COUNT(COD_TIPO) FROM TERMOS_EMPRESA WHERE COD_EMPRESA = $cod_empresa AND COD_TIPO = 5 AND COD_TERMO IN($termos)) AS ACC_PUSH,
	// 			(SELECT COUNT(COD_TIPO) FROM TERMOS_EMPRESA WHERE COD_EMPRESA = $cod_empresa AND COD_TIPO = 6 AND COD_TERMO IN($termos)) AS ACC_OFERTA,
	// 			(SELECT COUNT(COD_TIPO) FROM TERMOS_EMPRESA WHERE COD_EMPRESA = $cod_empresa AND COD_TIPO = 7 AND COD_TERMO IN($termos)) AS ACC_TELE";

	// $arrayTer = mysqli_query(connTemp($cod_empresa,''),$sqlTer);

	// $qrAcc = mysqli_fetch_assoc($arrayTer);

	// if($qrAcc[ACC_EMAIL] > 0){
	// 	$logsAtt .= "LOG_EMAIL = 'S',";
	// }else{
	// 	$logsAtt .= "LOG_EMAIL = 'N',";
	// }

	// if($qrAcc[ACC_SMS] > 0){
	// 	$logsAtt .= "LOG_SMS = 'S',";
	// }else{
	// 	$logsAtt .= "LOG_SMS = 'N',";
	// }

	// if($qrAcc[ACC_OFERTA] > 0){
	// 	$logsAtt .= "LOG_OFERTAS = 'S',";
	// }else{
	// 	$logsAtt .= "LOG_OFERTAS = 'N',";
	// }

	// if($qrAcc[ACC_TELE] > 0){
	// 	$logsAtt .= "LOG_TELEMARK = 'S',";
	// }else{
	// 	$logsAtt .= "LOG_TELEMARK = 'N',";
	// }

	// if($qrAcc[ACC_WHATS] > 0){
	// 	$logsAtt .= "LOG_WHATSAPP = 'S',";
	// }else{
	// 	$logsAtt .= "LOG_WHATSAPP = 'N',";
	// }

	// if($qrAcc[ACC_PUSH] > 0){
	// 	$logsAtt .= "LOG_PUSH = 'S',";
	// }else{
	// 	$logsAtt .= "LOG_PUSH = 'N',";
	// }

	// $logsAtt .= "LOG_TERMO = 'S',";

	// $logsAtt = rtrim($logsAtt,',');

	// $sqlUpdCli = "UPDATE CLIENTES SET $logsAtt WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE = $cod_cliente";

	// if($log_lgpd == 'S'){
	// 	mysqli_query(connTemp($cod_empresa,''),$sqlUpdCli);
	// }

	if($atualiza == "realizado" || $ativacao == 1){

		if(!isset($tipoAtiv)){
			$tipoAtiv = 2;
		}

		$sqlUpdtCanal = "UPDATE LOG_CANAL SET COD_TIPO = $tipoAtiv, DAT_ATIV = NOW() WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE = $cod_cliente";
		mysqli_query(connTemp($cod_empresa,''),$sqlUpdtCanal);
	}


	$log_cadastro = 'S';

}

?>