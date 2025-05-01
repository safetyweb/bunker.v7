<?php 

	include '../_system/_functionsMain.php';
	include_once '../totem/funWS/buscaConsumidor.php';
	include_once '../totem/funWS/buscaConsumidorCNPJ.php';
	// include_once '../totem/funWS/saldo.php';

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$tipo = fnLimpaCampo($_GET["TIPO"]);
	$urltotem = fnDecode($_POST["URL_TOTEM"]);
	// $cod_token = fnDecode($_POST["COD_TOKEN"]);
	$pref = fnLimpaCampo($_POST["PREF"]);
	$casasDec = fnLimpaCampo($_POST["CASAS_DEC"]);

	$arrayCampos = explode(";", $urltotem);

	if($tipo == "TKN"){

		$des_token = fnLimpaCampo($_POST["DES_TOKEN"]);

		$sqlCli = "SELECT COD_CLIENTE, LOG_FIDELIZADO FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND DES_TOKEN = '$des_token'";

		$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);

		if(mysqli_num_rows($arrayCli) == 0){

			$sqlverificatoken="SELECT * FROM geratoken 
								WHERE COD_EMPRESA = $cod_empresa 
								AND DES_TOKEN = '$des_token'";

			// echo($sqlverificatoken);

			$qrCliToken = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlverificatoken));

			$nom_cliente = fnLimpaCampo($_POST['NOM_CLIENTE']);
			$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CELULAR']));
			$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CGCECPF']));
			
			include_once '../totem/funWS/GeraToken.php';

			$dadosenvio = array(
									'tipoGeracao'=>'1',
									'token'=>"$des_token",
									'celular'=>$qrCliToken['NUM_CELULAR'],		
									'cpf'=>$qrCliToken['NUM_CGCECPF']
								);

			$retornoEnvio = ValidaToken($dadosenvio, $arrayCampos);

			// echo '<pre>';
		 //    print_r($dadosenvio);
		 //    print_r($retornoEnvio);
		 //    echo '</pre>';
		 //    exit();

			$cod_envio = $retornoEnvio[body][envelope][body][validatokenresponse][retornatoken][coderro];

			if($cod_envio == 39){

				$sqlBusca = "SELECT COD_CLIENTE FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa  AND NUM_CGCECPF = '$qrCliToken[NUM_CGCECPF]'";
				$arrayBusca = mysqli_query(connTemp($cod_empresa,''),$sqlBusca);

				if(mysqli_num_rows($arrayBusca) > 0){

					$qrBusca = mysqli_fetch_assoc($arrayBusca);

					$sqlPreUpdt = "UPDATE CLIENTES 
									SET DES_TOKEN = '$des_token' 
									WHERE COD_EMPRESA = $cod_empresa 
									AND COD_CLIENTE = $qrBusca[COD_CLIENTE]";

					mysqli_query(connTemp($cod_empresa,''),$sqlPreUpdt);

					$sqlCli = "SELECT COD_CLIENTE, LOG_FIDELIZADO FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND DES_TOKEN = '$des_token'";

					$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);

				}else{

					$sqlPreCad = "INSERT INTO CLIENTES(
												COD_EMPRESA,
												NOM_CLIENTE,
												NUM_CGCECPF,
												NUM_CARTAO,
												NUM_CELULAR,
												DES_TOKEN,
												LOG_FIDELIDADE,
												LOG_EMAIL,
												LOG_SMS,
												LOG_TELEMARK,
												LOG_WHATSAPP,
												LOG_OFERTAS,
												COD_USUCADA
											  ) VALUES(
											  	$cod_empresa,
											  	'$qrCliToken[NOM_CLIENTE]',
											  	'$qrCliToken[NUM_CGCECPF]',
											  	'$qrCliToken[NUM_CGCECPF]',
											  	'$qrCliToken[NUM_CELULAR]',
											  	'$des_token',
											  	'N',
											  	'N',
											  	'N',
											  	'N',
											  	'N',
											  	'N',
											  	9999
											  )";

					mysqli_query(connTemp($cod_empresa,''),$sqlPreCad);

					$sqlCli = "SELECT COD_CLIENTE, LOG_FIDELIZADO FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND DES_TOKEN = '$des_token'";

					$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);

				}


			}


		}
		
	}else{

		$k_num_cartao = fnLimpaCampo($_REQUEST['KEY_NUM_CARTAO']);
		$k_num_celular = fnLimpaCampo(fnLimpaDoc($_REQUEST['KEY_NUM_CELULAR']));
		$k_cod_externo = fnLimpaCampo($_REQUEST['KEY_COD_EXTERNO']);
		$k_num_cgcecpf = fnLimpaDoc(fnLimpaCampo($_REQUEST['KEY_NUM_CGCECPF']));
		$k_dat_nascime = fnLimpaCampo($_REQUEST['KEY_DAT_NASCIME']);
		$k_des_emailus = fnLimpaCampo($_REQUEST['KEY_DES_EMAILUS']);

		$whereSql = "";

		if($k_num_cartao != ""){
			$whereSql .= "OR NUM_CARTAO = '$k_num_cartao' ";
		}

		if($k_num_celular != ""){
			$whereSql .= "OR NUM_CELULAR = '$k_num_celular' ";
		}

		if($k_cod_externo != ""){
			$whereSql .= "OR COD_EXTERNO = '$k_cod_externo' ";
		}

		if($k_num_cgcecpf != "" && $k_num_cgcecpf != "00000000000"){
			$whereSql .= "OR NUM_CGCECPF = '$k_num_cgcecpf' ";
		}

		if($k_dat_nascime != ""){
			$whereSql .= "OR DAT_NASCIME = '$k_dat_nascime' ";
		}

		if($k_des_emailus != ""){
			$whereSql .= "OR DES_EMAILUS = '$k_des_emailus' ";
		}

		$whereSql = trim(ltrim($whereSql, "OR"));

		$sqlCli = "SELECT COD_CLIENTE, LOG_FIDELIZADO FROM CLIENTES 
			       WHERE COD_EMPRESA = $cod_empresa
			       AND ($whereSql)
			       ORDER BY 1 LIMIT 1";

			       // echo($sqlCli);
			       // exit();
		$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);

	}


	if(mysqli_num_rows($arrayCli) > 0){

		$sqlSite = "SELECT COD_DOMINIO, DES_DOMINIO FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
		$arraySite = mysqli_query(connTemp($cod_empresa,''),$sqlSite);
		$qrSite = mysqli_fetch_assoc($arraySite);

		$cod_dominio = $qrSite[COD_DOMINIO];
		$des_dominio = $qrSite[DES_DOMINIO];

		if($cod_dominio == 2){
			$extensaoDominio = ".fidelidade.mk";
		}else{
			$extensaoDominio = ".mais.cash";
		}

		$qrCli = mysqli_fetch_assoc($arrayCli);

		// echo "https://".$des_dominio.$extensaoDominio."/active.do?idC=".fnEncode($qrCli[COD_CLIENTE]))."&pop=true&ida=false";

		echo fnEncode($qrCli[COD_CLIENTE]);
		
		// header("Location:https://".$des_dominio.$extensaoDominio."/active.do?idC=".fnEncode($qrCli[COD_CLIENTE]))."&pop=true&ida=false";

	}else{

		// echo $tipo;


		if($tipo == "TKN"){

		}else{

			echo 0;

		}


	}

?>