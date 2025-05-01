<?php 

	include '../_system/_functionsMain.php';
	include_once '../totem/funWS/GeraToken.php';

	$cod_empresa = fnLimpaCampoZero(fnDecode($_POST['COD_EMPRESA']));
	$cod_token = fnLimpaCampoZero($_POST['COD_TOKEN']);
	// fnEscreve('chegou');

	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, QTD_CHARTKN, TIP_TOKEN, TIP_RETORNO, NUM_DECIMAIS_B  FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($arrayQuery)){
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$qtd_chartkn = $qrBuscaEmpresa['QTD_CHARTKN'];
		$tip_token = $qrBuscaEmpresa['TIP_TOKEN'];


		if($qrBuscaEmpresa['TIP_RETORNO'] == 1){
			$casasDec = 0;
		}else{
			$casasDec = $qrBuscaEmpresa['NUM_DECIMAIS_B'];
			$pref = "R$ ";
		}

		// echo($casasDec);
	}

	// $cod_token
	$sqlTkn = "SELECT * FROM GERATOKEN 
			   WHERE COD_EMPRESA = $cod_empresa 
			   AND COD_TOKEN = $cod_token";

	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlTkn);
	$qrCli = mysqli_fetch_assoc($arrayQuery);

	$num_celular = fnLimpaCampo(fnLimpaDoc($qrCli['NUM_CELULAR']));
	$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($qrCli['NUM_CGCECPF']));
	$nom_cliente = fnLimpaCampo($qrCli['NOM_CLIENTE']);

	if($num_cgcecpf == "00000000000" || $num_cgcecpf == ""){
		$num_cgcecpf = $num_celular;
	}

	$sql = "SELECT * FROM  USUARIOS
			WHERE LOG_ESTATUS='S' AND
				  COD_EMPRESA = $cod_empresa AND
				  COD_TPUSUARIO = 10  limit 1  ";
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

	$idlojaKey = $qrLista['COD_UNIVEND'];
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

	$dadosenvio = array(
						 'tipoGeracao'=>'1',
						 'nome'=>"$nom_cliente",
						 'cpf'=>"$num_cgcecpf",
						 'celular'=>"$num_celular",
						 'email'=>''
						);

	$retornoEnvio = GeraToken($dadosenvio, $arrayCampos);

	// echo '<pre>';
	// echo '_'.$_POST['NUM_CGCECPF'];
	// echo '_'.$_POST['CAD_NUM_CGCECPF'];
	// echo '_'.$_POST['KEY_NUM_CGCECPF'];
    // print_r($dadosenvio);
    // print_r($retornoEnvio);
    // echo '</pre>';
    // exit();

	$cod_envio = $retornoEnvio[body][envelope][body][geratokenresponse][retornatoken][coderro];

	echo $cod_envio;

?>