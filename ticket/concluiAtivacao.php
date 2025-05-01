<?php

include "../_system/_functionsMain.php";
include_once '../totem/funWS/atualizacadastro.php';
include_once '../totem/funWS/TKT.php';
$cod_empresa = fnLimpaCampo(fnDecode($_GET['id']));
//busaca clientes por cpf

//habilitando o cors
header("Access-Control-Allow-Origin: *");

//busca dados da tabela
$sql = "SELECT * FROM SITE_EXTRATO WHERE COD_EMPRESA = '" . $cod_empresa . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
$qrBuscaSiteExtrato = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	//fnEscreve("entrou if");
	$cod_extrato = $qrBuscaSiteExtrato['COD_EXTRATO'];
	$des_dominio = $qrBuscaSiteExtrato['DES_DOMINIO'];
	$des_logo = $qrBuscaSiteExtrato['DES_LOGO'];
	$des_banner = $qrBuscaSiteExtrato['DES_BANNER'];
	$des_email = $qrBuscaSiteExtrato['DES_EMAIL'];
	$log_home = $qrBuscaSiteExtrato['LOG_HOME'];
	$destino_home = $qrBuscaSiteExtrato['DESTINO_HOME'];
	$log_vantagem = $qrBuscaSiteExtrato['LOG_VANTAGEM'];
	$txt_vantagem = $qrBuscaSiteExtrato['TXT_VANTAGEM'];
	$log_regula = $qrBuscaSiteExtrato['LOG_REGULA'];
	$txt_regula = $qrBuscaSiteExtrato['TXT_REGULA'];
	$log_lojas = $qrBuscaSiteExtrato['LOG_LOJAS'];
	$txt_lojas = $qrBuscaSiteExtrato['TXT_LOJAS'];
	$log_faq = $qrBuscaSiteExtrato['LOG_FAQ'];
	$txt_faq = $qrBuscaSiteExtrato['TXT_FAQ'];
	$log_premios = $qrBuscaSiteExtrato['LOG_PREMIOS'];
	$txt_premios = $qrBuscaSiteExtrato['TXT_PREMIOS'];
	$log_extrato = $qrBuscaSiteExtrato['LOG_EXTRATO'];
	$txt_extrato = $qrBuscaSiteExtrato['TXT_EXTRATO'];
	$log_contato = $qrBuscaSiteExtrato['LOG_CONTATO'];
	$log_cadastro = $qrBuscaSiteExtrato['LOG_CADASTRO'];
	$txt_contato = $qrBuscaSiteExtrato['TXT_CONTATO'];
	$cor_titulos = $qrBuscaSiteExtrato['COR_TITULOS'];
	$cor_barra = $qrBuscaSiteExtrato['COR_BARRA'];
	$cor_txtbarra = $qrBuscaSiteExtrato['COR_TXTBARRA'];
	$cor_site = $qrBuscaSiteExtrato['COR_SITE'];
	$cor_textos = $qrBuscaSiteExtrato['COR_TEXTOS'];
	$cor_rodapebg = $qrBuscaSiteExtrato['COR_RODAPEBG'];
	$cor_rodape = $qrBuscaSiteExtrato['COR_RODAPE'];
	$cor_botao = $qrBuscaSiteExtrato['COR_BOTAO'];
	$cor_botaoon = $qrBuscaSiteExtrato['COR_BOTAOON'];
	$cor_txtbotao = $qrBuscaSiteExtrato['COR_TXTBOTAO'];
	$des_vantagem = $qrBuscaSiteExtrato['DES_VANTAGEM'];
	$ico_bloco1 = $qrBuscaSiteExtrato['ICO_BLOCO1'];
	$ico_bloco2 = $qrBuscaSiteExtrato['ICO_BLOCO2'];
	$ico_bloco3 = $qrBuscaSiteExtrato['ICO_BLOCO3'];
	$tit_bloco1 = $qrBuscaSiteExtrato['TIT_BLOCO1'];
	$des_bloco1 = $qrBuscaSiteExtrato['DES_BLOCO1'];
	$tit_bloco2 = $qrBuscaSiteExtrato['TIT_BLOCO2'];
	$des_bloco2 = $qrBuscaSiteExtrato['DES_BLOCO2'];
	$tit_bloco3 = $qrBuscaSiteExtrato['TIT_BLOCO3'];
	$des_bloco3 = $qrBuscaSiteExtrato['DES_BLOCO3'];
	$des_regras = $qrBuscaSiteExtrato['DES_REGRAS'];
	$des_programa = $qrBuscaSiteExtrato['DES_PROGRAMA'];
	$tp_ordenac = $qrBuscaSiteExtrato['TP_ORDENAC'];
}

list($r_cor_backpag, $g_cor_backpag, $b_cor_backpag) = sscanf("#".$cor_site, "#%02x%02x%02x");

if($r_cor_backpag > 50){
	$r = ($r_cor_backpag-50);
}else{
	$r =($r_cor_backpag+50);
	if($r_cor_backpag < 30){
		$r = $r_cor_backpag;
	}
}
if($g_cor_backpag > 50){
	$g = ($g_cor_backpag-50);
}else{
	$g =($g_cor_backpag+50);
	if($g_cor_backpag < 30){
		$g = $g_cor_backpag;
	}
}
if($b_cor_backpag > 50){
	$b = ($b_cor_backpag-50);
}else{
	$b =($b_cor_backpag+50);
	if($b_cor_backpag < 30){
		$b = $b_cor_backpag;
	}
}

if($r_cor_backpag <= 50 && $g_cor_backpag <= 50 && $b_cor_backpag <= 50){
	$r =($r_cor_backpag+40);
	$g =($g_cor_backpag+40);
	$b =($b_cor_backpag+40);
}


//busca usuário modelo	
$sql = "SELECT * FROM  USUARIOS
		WHERE LOG_ESTATUS='S' AND
			  COD_EMPRESA = $cod_empresa AND
			  COD_TPUSUARIO=10  limit 1  ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);
				
if (isset($arrayQuery)) {
	$log_usuario = $qrBuscaUsuTeste['LOG_USUARIO'];
	$des_senhaus = fnDecode($qrBuscaUsuTeste['DES_SENHAUS']);
}

$sqlUn = "SELECT COD_UNIVEND FROM UNIDADEVENDA 
		  WHERE COD_EMPRESA = $cod_empresa 
		  AND LOG_ESTATUS = 'S' 
		  ORDER BY 1 ASC LIMIT 1";

$arrayUn = mysqli_query($connAdm->connAdm(), $sqlUn);
$qrLista = mysqli_fetch_assoc($arrayUn);

$cod_univend = fnLimpaCampoZero($_REQUEST['COD_UNIVEND']);

$idlojaKey = $cod_univend;
$idmaquinaKey = 0;
$codvendedorKey = 0;
$nomevendedorKey = 0;

$urltotem = $log_usuario.';'
			.$des_senhaus.';'
			.$idlojaKey.';'
			.$idmaquinaKey.';'
			.$cod_empresa.';'
			.$codvendedorKey.';'
			.$nomevendedorKey;

$arrayCampos = explode(";", $urltotem);

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

$nome = trim($cad_nom_cliente);
$cpf = trim($cad_num_cgcecpf);
$sexo = trim($cad_cod_sexopes);
$cartao = trim($cad_num_cartao);
$email = trim($cad_des_emailus);
$endereco = trim($cad_des_enderec);
$numero = trim($cad_num_enderec);
$bairro = trim($cad_des_bairroc);
$complemento = trim($cad_des_complem);
$cidade = trim($cad_des_cidadec);
$estado = trim($cad_cod_estadof);
$cep = trim($cad_num_cepozof);
$dt_nascimento = trim($cad_dat_nascime);
$telefone = trim($cad_num_celular);
$profissao = trim($cad_cod_profiss);
$codatendente = trim($cad_cod_atendente);
$senha = trim($cad_des_senhaus);

if(trim($k_num_cgcecpf) != ""){
	// echo "if cpf";
	$cpf = $k_num_cgcecpf;
	$cartao = $k_num_cgcecpf;
}

if(trim($k_num_cartao) != ""){
	// echo "if card";
	$cartao = $k_num_cartao;
}

if(trim($k_num_celular) != ""){
	// echo "if cel";
	$telefone = fnLimpaDoc($k_num_celular);
	$cartao = fnLimpaDoc($k_num_celular);
}

if(trim($k_cod_externo) != ""){
	// echo "if ext";
	$externo = $k_cod_externo;
	$cartao = $k_cod_externo;
}

if(trim($k_dat_nascime) != ""){
	// echo "if aniv";
	$dt_nascimento = $k_dat_nascime;
	$cartao = $cpf;
}

if(trim($k_des_emailus) != ""){
	// echo "if email";
	$email = $k_des_emailus;
	$cartao = $cpf;
}

// if(trim($cartao) == ""){
// 	$cartao = $cpf;
// }

while($qrCampos = mysqli_fetch_assoc($arrayFields)){ 

	// echo "<pre>";
	// print_r($qrCampos);
	// echo "</pre>";

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

	// echo $qrCampos[DES_CAMPOOBG];

	switch ($qrCampos[DES_CAMPOOBG]) {

		case 'NOM_CLIENTE':

			if($_POST[$qrCampos[DES_CAMPOOBG]] != ""){

				$nome = fnLimpaCampo($_POST[$qrCampos[DES_CAMPOOBG]]);

			}

		break;
		
		case 'COD_SEXOPES':

			if($_POST[$qrCampos[DES_CAMPOOBG]] != ""){

				$sexo = fnLimpaCampoZero($_POST[$qrCampos[DES_CAMPOOBG]]);

			}

		break;
		
		case 'DES_EMAILUS':

			if($_POST[$qrCampos[DES_CAMPOOBG]] != ""){

				$email = fnLimpaCampo($_POST[$qrCampos[DES_CAMPOOBG]]);

			}

		break;
		
		case 'NUM_CELULAR':

			if($_POST[$qrCampos[DES_CAMPOOBG]] != ""){

				$telefone = fnLimpaCampo(fnLimpaDoc($_POST[$qrCampos[DES_CAMPOOBG]]));

			}

		break;
		
		case 'NUM_CARTAO':

			if($_POST[$qrCampos[DES_CAMPOOBG]] != ""){

				$cartao = fnLimpaCampo(fnLimpaDoc($_POST[$qrCampos[DES_CAMPOOBG]]));

			}

		break;

		case 'NUM_CGCECPF':

			if($_POST[$qrCampos[DES_CAMPOOBG]] != ""){

				$cpf = fnLimpaCampo(fnLimpaDoc($_POST[$qrCampos[DES_CAMPOOBG]]));

			}

		break;
		
		
		case 'DAT_NASCIME':

			if($_POST[$qrCampos[DES_CAMPOOBG]] != ""){

				$dt_nascimento = fnLimpaCampo($_POST[$qrCampos[DES_CAMPOOBG]]);

			}

		break;
		
		case 'COD_PROFISS':

			if($_POST[$qrCampos[DES_CAMPOOBG]] != ""){

				$profissao = fnLimpaCampoZero($_POST[$qrCampos[DES_CAMPOOBG]]);

			}

		break;
		
		case 'COD_ATENDENTE':

			if($_POST[$qrCampos[DES_CAMPOOBG]] != ""){

				$codatendente = fnLimpaCampoZero($_POST[$qrCampos[DES_CAMPOOBG]]);

			}

		break;
		
		case 'DES_SENHAUS':

			if($_POST[$qrCampos[DES_CAMPOOBG]] != ""){

				$senha = $_POST[$qrCampos[DES_CAMPOOBG]];

			}

		break;
		
		case 'DES_ENDEREC':

			if($_POST[$qrCampos[DES_CAMPOOBG]] != ""){

				$endereco = fnLimpaCampo(fnAcentos($_POST[$qrCampos[DES_CAMPOOBG]]));

			}

		break;
		
		case 'NUM_ENDEREC':

			if($_POST[$qrCampos[DES_CAMPOOBG]] != ""){

				$numero = fnLimpaCampo($_POST[$qrCampos[DES_CAMPOOBG]]);

			}

		break;
		
		case 'NUM_CEPOZOF':

			if($_POST[$qrCampos[DES_CAMPOOBG]] != ""){

				$cep = fnLimpaCampo($_POST[$qrCampos[DES_CAMPOOBG]]);

			}

		break;
		
		case 'COD_ESTADOF':

			if($_POST[$qrCampos[DES_CAMPOOBG]] != ""){

				$estado = fnLimpaCampo($_POST[$qrCampos[DES_CAMPOOBG]]);

			}

		break;
		
		case 'NOM_CIDADEC':

			if($_POST[$qrCampos[DES_CAMPOOBG]] != ""){

				$cidade = fnLimpaCampo(fnAcentos($_POST[$qrCampos[DES_CAMPOOBG]]));

			}

		break;
		
		case 'DES_BAIRROC':

			if($_POST[$qrCampos[DES_CAMPOOBG]] != ""){

				$bairro = fnLimpaCampo(fnAcentos($_POST[$qrCampos[DES_CAMPOOBG]]));

			}

		break;
		
		case 'DES_COMPLEM':

			if($_POST[$qrCampos[DES_CAMPOOBG]] != ""){

				$complemento = fnLimpaCampo(fnAcentos($_POST[$qrCampos[DES_CAMPOOBG]]));

			}

		break;
		
		

		default:

			// $cpf = $_POST[$qrCampos[DES_CAMPOOBG]];

		break;

	}


}

// echo "$k_num_celular";

if($sexo == 0 || $sexo == ""){
	$sexo = 3;
}

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
						'complemento' =>$complemento
                   );

// $fields = rtrim(ltrim(trim($fields),','),',');
//    $values = rtrim(ltrim(trim($values),','),',');

// $sql = "INSERT INTO CLIENTES ($fields) VALUES ($values)";
// echo "<pre>";
// print_r($dadosatualiza);
// echo "</pre>";

$atualiza=atualizacadastro($dadosatualiza, $arrayCampos);

// echo "<pre>";
// print_r($atualiza);
// echo "</pre>";

if($atualiza == "Registro inserido!" || $atualiza == "Cadastro Atualizado !"){

	if($atualiza == "Registro inserido!"){
		$atualiza = "realizado";
	}else{
		$atualiza = "atualizado";
	}

	$sqlCliente = "SELECT COD_CLIENTE FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND NUM_CGCECPF = $dadosatualiza[cpf]";
	$qrBuscaCliente =  mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCliente));
	$cod_cliente = $qrBuscaCliente['COD_CLIENTE'];

	$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa ORDER BY NUM_ORDENAC";
	// fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

	$sqlDelTermos = "DELETE FROM CLIENTES_TERMOS 
					  WHERE COD_CLIENTE = $cod_cliente
					  AND COD_EMPRESA = $cod_empresa";

	// echo($sqlDelTermos);

	mysqli_query(connTemp($cod_empresa,''),$sqlDelTermos);

	$sqlBlc = "";
	$termos = "";
	while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)){

		if (!empty($_REQUEST["TERMOS_".$qrBuscaFAQ[COD_BLOCO]])) {

			$sqlBlc = "INSERT INTO CLIENTES_TERMOS(
										COD_EMPRESA,
										COD_CLIENTE,
										COD_BLOCO,
										COD_TERMOS
									) VALUES(
										$cod_empresa,
										$cod_cliente,
										$qrBuscaFAQ[COD_BLOCO],
										'$qrBuscaFAQ[COD_TERMO]'
									)";

			// fnEscreve($sqlBlc);

			mysqli_query(connTemp($cod_empresa,''),$sqlBlc);

			$termos .= $qrBuscaFAQ[COD_TERMO].",";					


		}

	}

	$logsAtt = "";

	$termos = rtrim($termos,',');

	$sqlTer = "SELECT 
				(SELECT COUNT(COD_TIPO) FROM TERMOS_EMPRESA WHERE COD_EMPRESA = $cod_empresa AND COD_TIPO = 2 AND COD_TERMO IN($termos)) AS ACC_EMAIL,
				(SELECT COUNT(COD_TIPO) FROM TERMOS_EMPRESA WHERE COD_EMPRESA = $cod_empresa AND COD_TIPO = 3 AND COD_TERMO IN($termos)) AS ACC_SMS,
				(SELECT COUNT(COD_TIPO) FROM TERMOS_EMPRESA WHERE COD_EMPRESA = $cod_empresa AND COD_TIPO = 4 AND COD_TERMO IN($termos)) AS ACC_WHATS,
				(SELECT COUNT(COD_TIPO) FROM TERMOS_EMPRESA WHERE COD_EMPRESA = $cod_empresa AND COD_TIPO = 5 AND COD_TERMO IN($termos)) AS ACC_PUSH,
				(SELECT COUNT(COD_TIPO) FROM TERMOS_EMPRESA WHERE COD_EMPRESA = $cod_empresa AND COD_TIPO = 6 AND COD_TERMO IN($termos)) AS ACC_OFERTA,
				(SELECT COUNT(COD_TIPO) FROM TERMOS_EMPRESA WHERE COD_EMPRESA = $cod_empresa AND COD_TIPO = 7 AND COD_TERMO IN($termos)) AS ACC_TELE";

	// fnEscreve($sqlTer);

	$arrayTer = mysqli_query(connTemp($cod_empresa,''),$sqlTer);

	$qrAcc = mysqli_fetch_assoc($arrayTer);

	if($qrAcc[ACC_EMAIL] > 0){
		$logsAtt .= "LOG_EMAIL = 'S',";
	}else{
		$logsAtt .= "LOG_EMAIL = 'N',";
	}

	if($qrAcc[ACC_SMS] > 0){
		$logsAtt .= "LOG_SMS = 'S',";
	}else{
		$logsAtt .= "LOG_SMS = 'N',";
	}

	if($qrAcc[ACC_OFERTA] > 0){
		$logsAtt .= "LOG_OFERTAS = 'S',";
	}else{
		$logsAtt .= "LOG_OFERTAS = 'N',";
	}

	if($qrAcc[ACC_TELE] > 0){
		$logsAtt .= "LOG_TELEMARK = 'S',";
	}else{
		$logsAtt .= "LOG_TELEMARK = 'N',";
	}

	if($qrAcc[ACC_WHATS] > 0){
		$logsAtt .= "LOG_WHATSAPP = 'S',";
	}else{
		$logsAtt .= "LOG_WHATSAPP = 'N',";
	}

	if($qrAcc[ACC_PUSH] > 0){
		$logsAtt .= "LOG_PUSH = 'S',";
	}else{
		$logsAtt .= "LOG_PUSH = 'N',";
	}

	// if($qrAcc[ACC_FID] > 0){
	// 	$logsAtt .= "LOG_FIDELIZADO = 'S',";
	// }else{
	// 	$logsAtt .= "LOG_FIDELIZADO = 'N',";
	// }

	$logsAtt = rtrim($logsAtt,',');

	$sqlUpdCli = "UPDATE CLIENTES SET $logsAtt WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE = $cod_cliente";

	// fnEscreve($sqlUpdCli);

	mysqli_query(connTemp($cod_empresa,''),$sqlUpdCli);

	$sqlUpdate = "UPDATE GERATOKEN SET DES_CANAL = 3 WHERE NUM_CGCECPF = $cpf AND COD_EMPRESA = $cod_empresa";
	mysqli_query(connTemp($cod_empresa,''),$sqlUpdate);

}


$sql = "SELECT LOG_TERMOS FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
$qrLog = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
$log_termos = $qrLog['LOG_TERMOS'];

$sqlControle = "SELECT * FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

// fnEscreve($sqlControle);

$arrayControle = mysqli_query(connTemp($cod_empresa,''),$sqlControle);

$qrControle = mysqli_fetch_assoc($arrayControle);

$log_separa = $qrControle['LOG_SEPARA'];
$des_img_g = $qrControle['DES_IMG_G'];
$des_img = $qrControle['DES_IMG'];
$des_imgmob = $qrControle['DES_IMGMOB'];

$des_img_g = $des_img;

// fnEscreve($sql);

?>
<!DOCTYPE html>
	<html lang="pt-br">
		<head>
			<meta charset="utf-8">
			<title><?php echo $des_programa; ?> - <?php echo $nom_fantasi; ?></title>
			<meta name="viewport" content="width=device-width, initial-scale=1.0">

			<link href="css/main.css" rel="stylesheet">
			<link href="css/custom.css" rel="stylesheet">
			
			<!-- SISTEMA -->
			<link href="https://bunker.mk/css/jquery-confirm.min.css" rel="stylesheet"/>
			<link href="https://bunker.mk/css/jquery.webui-popover.min.css" rel="stylesheet" />
			<link href="https://bunker.mk/css/chosen-bootstrap.css" rel="stylesheet" />
			<link href="https://bunker.mk/css/font-awesome.min.css" rel="stylesheet" />
			<link rel="stylesheet" type="text/css" href="https://bunker.mk/css/fontawesome-pro-5.13.0-web/css/all.min.css" />
			
			<!-- complement -->
			<link href="https://bunker.mk/css/default.css" rel="stylesheet" />
			<link href="https://bunker.mk/css/checkMaster.css" rel="stylesheet" />
			
			
			<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
			<!--[if lt IE 9]>
			  <script src="js/html5shiv.js"></script>
			<![endif]-->
			
		</head>

		<style>

			body{
				width: 100vw;
				background: #<?=$cor_site?>!important;
				-ms-overflow-style: none!important;  /* Internet Explorer 10+ */
    			scrollbar-width: none!important;  /* Firefox */
    			overflow-y: visible;
			}

			body::-webkit-scrollbar { 
			    display: none!important;  /* Safari and Chrome */
			}

			#parallax {
			  height: 652px;
			  width: 100%;
			  position: fixed;
			  background: none;
			  background-size: cover;
			  z-index: -100;
			}

			.logo-img{
				height: 40px!important;
			}

			section{
				padding-top: 15px!important;
			}
			
			h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
				color: #<?php echo $cor_titulos; ?>;
			}			
			
			p, p.lead  {
				color: #<?php echo $cor_textos; ?>;
			}			
			.bottom-menu-inverse {
				background-color: #<?php echo $cor_rodapebg; ?>;
				color: #<?php echo $cor_rodape; ?>;
			}
			
			.fFooter {
				color: #<?php echo $cor_rodape; ?>;
			}	
			
			.navbar .nav > li > a{
				color: #<?php echo $cor_titulos; ?>;
			}

			.btn-primary {
				background-color: #<?php echo $cor_botao; ?>;
			}
			
			.btn-primary:hover {
				background-color: #<?php echo $cor_botaoon; ?>;
			}	

			p {
				font-size: 12px; margin: 0;
				padding: 0 0 3px 0;
			}	
			
			.f18 {
				font-size: 18px;
			}			
			
			/* modal */								
			.modal-dialog {
				width: 40%;
				max-width: 1080px;
				margin-top: 10px;
				margin-bottom: 10px;
				height: 500px;
			}
			
			.modal-content {
				height: 700px;
			}			

			iframe {
			  display: block;
			  margin: 0 auto;
			} 

			.modal-body {
				position: relative;
				padding: 20px;
				height: 700px;
			}			
			
			#contato-info{
				color: green;
				margin-top: 25px;
				text-align: center;
			}
			
			.bloco {
				padding: 30px 0;
			}	


			hr{
				width: 100%;
				border-top: 2px solid #161616;
			}
			
			hr.divisao{
				width: 100%;
				border-top: 1px dashed #cecece;
				margin: 5px 0;
			}	

			#footer {
				position: fixed;
				bottom: 0;
				width: 100%;				
			}
			
			.numero{
				font-size: 16px;
				margin-top: -40px;
			}		
			
			@media only screen and (min-width: 761px) and (max-width: 1281px) { /* 10 inch tablet enter here */
				.lead.titulo {
					margin-top: 50px;
				}
			} 			
			
			@media only screen and (max-width: 760px) {
				/* For mobile phones: */
				section#contact {
					padding: 10px 0;
				}
				
				.lead {
					margin-bottom: 10px;
				}				
				
				#footer .bottom-menu, #footer .bottom-menu-inverse {
					padding: 10px 0 0;
					height: 60px;
				}
				
				.rating > label { 
					font-size: 15px;
				}					
				
				.rating.rate10 { 
					border: none;
					float: left;
					width: 315px;
				}	
				.numero {
					margin-top: -10px;
				}				
			}			
						
			
			/*.input-hg {
				background-color: transparent !important;
				border-bottom: 2px solid #4d4d4d !important;	
				border-radius: 0;				
			}
			
			.input-hg:focus {
				border-bottom-color: #48c9b0 !important;
			}*/

			section{
				padding-top: 150px;
				background: #<?=$cor_site?>!important;
			}

			.info-section-white{
				background: #<?=$cor_site?>!important;
			}

			.WordSection1{
				background: #<?=$cor_site?>!important;
			}

			.navbar, .navbar-inner{
				background: #<?=$cor_barra?>!important;
			}

			h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
				color: #<?php echo $cor_titulos; ?>;
			}			
			
			p, p.lead  {
				color: #<?php echo $cor_textos; ?>;
			}
			.navbar .nav > li > a{
				color: #<?php echo $cor_txtbarra; ?>;
			}
			.btn-primary {
				background-color: #<?php echo $cor_botao; ?>;
			}
			.btn-primary:hover {
				background-color: #<?php echo $cor_botaoon; ?>;
			}

	.p-l-0{
		padding-left: 0;
	}

@media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
	

	.p-r-0{
		padding-right: 0;
		padding-left: 0;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 0;
		padding-right: 0;
	}

	.p-0{
		padding: 0;
	}

	.nav-tabs li{
		width:100%;
	}

	.nav-tabs li:last-child{
		margin-bottom:5px;
	}

    
}
 
/* (320x480) Smartphone, Portrait */
@media only screen and (device-width: 320px) and (orientation: portrait) {
    
	.p-r-0{
		padding-right: 0;
		padding-left: 0;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 0;
		padding-right: 0;
	}

	.p-0{
		padding: 0;
	}


}
 
/* (320x480) Smartphone, Landscape */
@media only screen and (device-width: 480px) and (orientation: landscape) {
    
	.p-r-0{
		padding-right: 0;
		padding-left: 0;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 0;
		padding-right: 0;
	}

	.p-0{
		padding: 0;
	}

}
 
/* (1024x768) iPad 1 & 2, Landscape */
@media only screen and (min-device-width: 768px) and (max-device-width: 1367px) and (orientation: landscape) {
    
	.p-r-0{
		padding-right: 0;
	}

	.p-l-0{
		padding-left: 0;
	}

}

/* (1280x800) Tablets, Portrait */
@media only screen and (max-width: 800px) and (orientation : portrait) {
	 
	.p-r-0{
		padding-right: 0;
		padding-left: 0;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 0;
		padding-right: 0;
	}

	.p-0{
		padding: 0;
	}

	
}

/* (768x1024) iPad 1 & 2, Portrait */
@media only screen and (max-width: 768px) and (orientation : portrait) {
    
	.p-r-0{
		padding-right: 0;
		padding-left: 0;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 0;
		padding-right: 0;
	}

	.p-0{
		padding: 0;
	}

	
}
 
/* (2048x1536) iPad 3 and Desktops*/
@media only screen and (min-device-width: 1536px) and (max-device-width: 2048px) {

	.p-r-0{
		padding-right: 0;
	}

	.p-l-0{
		padding-left: 0;
	}
		 
}

@media only screen and (min-device-width: 1100px) and (orientation : portrait) {
    
	.p-r-0{
		padding-right: 0;
		padding-left: 0;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 0;
		padding-right: 0;
	}

	.p-0{
		padding: 0;
	}

	
}

@media (max-height: 824px) and (max-width: 416px){
	
	.p-r-0{
		padding-right: 0;
		padding-left: 0;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 0;
		padding-right: 0;
	}

	.p-0{
		padding: 0;
	}

}	

/* (320x480) iPhone (Original, 3G, 3GS) */
@media (max-device-width: 737px) and (max-height: 416px) {
	

	.p-r-0{
		padding-right: 0;
		padding-left: 0;
		margin-bottom: 10px;
	}

	.p-l-0{
		padding-left: 0;
		padding-right: 0;
	}

	.p-0{
		padding: 0;
	}

}

#corpoForm{
		width: 100vw!important;
	}

	#caixaForm{
		overflow: auto;
	}

	#caixaImg, #caixaForm{
		height: 100vh;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_img; ?>') no-repeat center center; 
		-webkit-background-size: 100% 100%;
		  -moz-background-size: 100% 100%;
		  -o-background-size: 100% 100%;
		  background-size: 100% 100%;
	}

	input::-webkit-input-placeholder {
		font-size: 22px;
		line-height: 3;
	}

	/* (320x480) iPhone (Original, 3G, 3GS) */
@media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
	body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	  overflow: auto!important;
	}

	#corpoForm{
		width: unset!important;
	}

	#caixaImg, #caixaForm{
		height: unset;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		height: 360px;
	}
    
}
 
/* (320x480) Smartphone, Portrait */
@media only screen and (device-width: 320px) and (orientation: portrait) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	  overflow: auto!important;
	}

#corpoForm{
		width: unset!important;
	}

	#caixaImg, #caixaForm{
		height: unset;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		height: 360px;
	}

}
 
/* (320x480) Smartphone, Landscape */
@media only screen and (device-width: 480px) and (orientation: landscape) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}
		
}
 
/* (1024x768) iPad 1 & 2, Landscape */
@media only screen and (min-device-width: 768px) and (max-device-width: 1367px) and (orientation: landscape) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

	

	.navbar img{
		margin-top: 0;
	}

	#caixaImg{
		 padding: 0;
	}
		 
}

/* (1280x800) Tablets, Portrait */
@media only screen and (max-width: 800px) and (orientation : portrait) {
	 body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat bottom fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: 103%;
	}

	.navbar img{
		margin-top: -10px;
	}

	#corpoForm{
		width: unset!important;
	}

	#caixaImg, #caixaForm{
		height: unset;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		height: 360px;
	}
	
}

/* (768x1024) iPad 1 & 2, Portrait */
@media only screen and (max-width: 768px) and (orientation : portrait) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	  overflow: auto!important;
	}

	

	.navbar img{
		margin-top: 0;
	}

#corpoForm{
		width: unset!important;
	}

	#caixaImg, #caixaForm{
		height: unset;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		height: 360px;
	}
		 
}
 
/* (2048x1536) iPad 3 and Desktops*/
@media only screen and (min-device-width: 1536px) and (max-device-width: 2048px) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

	

	.navbar img{
		margin-top: 0;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_img_g; ?>') no-repeat center center;
		 padding: 0;
	}
		 
}

@media only screen and (min-device-width: 1100px) and (orientation : portrait) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	  overflow: auto!important;
	}

	

	.navbar img{
		margin-top: 0;
	}

	#corpoForm{
		width: unset!important;
	}

	#caixaImg, #caixaForm{
		height: unset;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		height: 360px;
	}
		 
}

@media (max-height: 824px) and (max-width: 416px){
	body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	  overflow: auto!important;
	}

	#corpoForm{
		width: unset!important;
	}

	#caixaImg, #caixaForm{
		height: unset;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		height: 360px;
	}
}	

/* (320x480) iPhone (Original, 3G, 3GS) */
@media (max-device-width: 737px) and (max-height: 416px) {
	body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

	#caixaImg{
		 padding: 0;
	}

		
}

	.input-sm, .chosen-single{
		font-size: 20px!important;
	}

</style>

<!-- Scrollspy set in the body -->
		<body id="home" data-spy="scroll" data-target=".main-nav" data-offset="73">

		<div id="parallax"></div>
		
		<!--/////////////////////////////////////// NAVIGATION BAR ////////////////////////////////////////-->

		<section id="header">

			<nav class="navbar navbar-fixed-top" role="navigation">

				<div class="navbar-inner">
					<div class="container">

						<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target="#navigation"></button>

					   <!-- Logo goes here - replace the image with yours -->
						<a href="." class="navbar-brand"><img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_logo; ?>" class="logo-img img-responsive" alt="<?php echo $des_programa; ?> - <?php echo $nom_fantasi; ?>" title="Booom! Logo"></a>

						<div class="collapse navbar-collapse main-nav" id="navigation">

							<ul class="nav pull-right">
								<!-- Menu -->
								<li class='active'><a href='#home'>Home</a></li>
								<?php
								if ($log_contato == "S"){echo "<li><a href='#contact'>$txt_contato</a></li>";}
								?>	
							</ul>

						</div><!-- /nav-collapse -->
					</div><!-- /container -->
				</div><!-- /navbar-inner -->
			</nav>

		</section>

		<!--/////////////////////////////////////// CONTACT SECTION ////////////////////////////////////////-->
		
		<section id="contact">
				
				<div class="row">
					
					<div class="col-md-12 col-xs-12">

						<div class="row" id="corpoForm">

							<form data-toggle="validator" role="form2" method="post" id="formulario" autocomplete="off">

								<div class="col-md-6 col-xs-12" id="caixaImg">
									<!-- <img src="http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?=$des_img?>" class="img-responsive" style="margin-left: auto; margin-right: auto;"> -->
								</div>

								<div class="col-md-6 col-xs-12 text-center" id="caixaForm" style="background-color: #FFF;">

									<div class="push20"></div>
									<div class="push50"></div>
									
									<h3>Cadastro <?=$atualiza?></h3>

									<a href="javascript:void(0)" class="btn btn-info btn-block" onclick=' 
																						parent.$("#popModal").modal("toggle"); 
																						parent.$("#senha").focus();
																						parent.$("html,body").animate({scrollTop: (parent.$("#extrato").position().top - 120)},"slow");'>Fazer login</a>

								</div>

								
								<input type="hidden" name="opcao" id="opcao" value="">
								<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
								<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
								
							</form>
							
						</div><!-- /container -->

					</div>

				</div>

		</section>

		<input type="hidden" name="CASAS_DEC" id="CASAS_DEC" value="<?=$casasDec?>">
		<input type="hidden" name="URL_TOTEM" id="URL_TOTEM" value="<?=fnEncode($urltotem)?>">
		<input type="hidden" name="COD_TOKEN" id="COD_TOKEN" value="<?=fnEncode($cod_token)?>">
		<input type="hidden" name="PREF" id="PREF" value="<?=$pref?>">
		

		<div style="height: 80px; clear:both;"></div>

		<!--//////////////////////////////////////// FOOTER SECTION ////////////////////////////////////////-->
		<section id="footer">
			<div class="bottom-menu-inverse">

				<div class="container">

					<div class="row">
						<div class="col-md-6">
							<p class="fFooter"><?php echo $nom_fantasi; ?> - &copy; Todos os direitos reservados. <br/> 
							Solução: &nbsp; <a href="https://marka.mk" class="fFooter" target="_blank">Marka Soluções em Fidelização</a>.</p>
						</div>

						<div class="col-md-6 social">
							<ul class="bottom-icons">
								<?php								
								
									$sql = "select * 
											from rede_sociais RS
											inner join $connAdm->DB.tipo_redes_sociais TRD on TRD.COD_REDES = RS.COD_REDES
											WHERE 
											RS.COD_EMPRESA = $cod_empresa 
											ORDER BY TRD.NOM_REDES ";
											
									//fnEscreve($sql);
									$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
																						
									$count=0;
									while ($qrBuscaRedesSociais = mysqli_fetch_assoc($arrayQuery))
									  {														  
										$count++;
										?>	
										
										<li>
										  <a href="<?php echo $qrBuscaRedesSociais['DES_REDESOC']; ?>" target="_blank" ><i class="fa  fa-lg <?php echo $qrBuscaRedesSociais['DES_ICONE']; ?>"></i></a>
										</li>										
											
									  <?php	
									  }	
										  
								?>							

							  </ul>                      
						</div>
					</div>
				
				</div><!-- /row -->
			</div><!-- /container -->

		</section>
		
		<!-- modal -->									
		<div class="modal fade" id="popModal" tabindex='-1'>
			<div class="modal-dialog" style="">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"></h4>
					</div>
					<div class="modal-body">
						<iframe frameborder="0" style="width: 100%; height: 600px !important"></iframe>
					</div>		
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->		
		
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
		<script src="js/jquery.ui.touch-punch.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.isotope.min.js"></script>
		<script src="js/bootstrap-select.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/jquery.mask.min.js"></script>
		<script src="js/iframeResizer.min.js"></script>		
		<script src="js/jquery-confirm.min.js"></script>

<script type="text/javascript">

	$(function(){
	
		$('input, textarea').placeholder();	

		$('.data').mask('00/00/0000');

		var SPMaskBehavior = function (val) {
		  return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
		},
		spOptions = {
		  onKeyPress: function(val, e, field, options) {
			  field.mask(SPMaskBehavior.apply({}, arguments), options);
			}
		};			
		
		$('.sp_celphones').mask(SPMaskBehavior, spOptions);
		
		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		$(".campo1,.campo2,.campo3,.campo4").keydown(function(){

			var campo1 = $(".campo1").val(),
				campo2 = $(".campo2").val(),
				campo3 = $(".campo3").val(),
				campo4 = $(".campo4").val();

				if(campo1 != "" || campo2 != "" || campo3 != "" || campo4 != ""){

					$(".campo1,.campo2,.campo3,.campo4").prop("required", false);
					$(".control-label").removeClass("required");

				}else{

					$(".campo1,.campo2,.campo3,.campo4").prop("required", true);
					$(".control-label").addClass("required");

				}

			// $('#formulario').validator();

		});

	});

	if($('.cpfcnpj').val() != undefined){
		mascaraCpfCnpj($('.cpfcnpj'));
	}
	
	function mascaraCpfCnpj(cpfCnpj){
		var optionsCpfCnpj = {
			onKeyPress: function (cpf, ev, el, op) {
				var masks = ['000.000.000-000', '00.000.000/0000-00'],
					mask = (cpf.length >= 15) ? masks[1] : masks[0];
				cpfCnpj.mask(mask, op);
			}
		}	

		var masks = ['000.000.000-000', '00.000.000/0000-00'];
		mask = (cpfCnpj.val().length >= 14) ? masks[1] : masks[0];
			
		cpfCnpj.mask(mask, optionsCpfCnpj);		
	}

	$('.validaCPF').click(function(e){

		var campo1 = $(".campo1").val(),
			campo2 = $(".campo2").val(),
			campo3 = $(".campo3").val(),
			campo4 = $(".campo4").val();

			if(campo1 != "" || campo2 != "" || campo3 != "" || campo4 != ""){

				if(campo1 != ""){

					if(!valida_cpf_cnpj($('.cpfcnpj').val())){

						e.preventDefault();
						$.alert({
							title: 'Atenção!',
							content: 'CPF/CNPJ digitado é inválido!',
						});	

					}

				}

			}else{

				e.preventDefault();
				$.alert({
					title: 'Atenção!',
					content: 'Pelo menos um dado deve ser informado!',
				});

			}

	});

</script>