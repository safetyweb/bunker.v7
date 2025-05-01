<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

//inicialização das variáveis

$teste_benefic = true;
@$cod_multemp = "0";
@$countFiltros = "";
@$check_ativo = 'checked';
@$check_troca = 'checked';
@$check_funciona = '';
@$check_mail = 'checked';
@$check_sms = 'checked';
@$check_telemark = 'checked';
@$check_beneficio = '';

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;
		$cod_univend_pref = 0;

		$cod_usuario = fnLimpacampoZero($_REQUEST['COD_USUARIO']);
		$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
		$nom_usuario = fnLimpacampo($_REQUEST['NOM_USUARIO']);
		$log_usuario = fnLimpacampo($_REQUEST['LOG_USUARIO']);
		$des_emailus = fnLimpacampo($_REQUEST['DES_EMAILUS']);
		if (empty($_REQUEST['LOG_ESTATUS'])) {
			$log_estatus = 'N';
		} else {
			$log_estatus = $_REQUEST['LOG_ESTATUS'];
		}
		if (empty($_REQUEST['LOG_TROCAPROD'])) {
			$log_trocaprod = 'N';
		} else {
			$log_trocaprod = $_REQUEST['LOG_TROCAPROD'];
		}
		if (empty($_REQUEST['LOG_RECEBE'])) {
			$log_recebe = 'N';
		} else {
			$log_recebe = $_REQUEST['LOG_RECEBE'];
		}

		$num_rgpesso = fnLimpacampo($_REQUEST['NUM_RGPESSO']);
		$dat_nascime = fnLimpacampo($_REQUEST['DAT_NASCIME']);
		$cod_estaciv = fnLimpaCampoZero($_REQUEST['COD_ESTACIV']);
		$cod_sexopes = fnLimpacampoZero($_REQUEST['COD_SEXOPES']);
		$num_tentati = fnLimpacampoZero($_REQUEST['NUM_TENTATI']);
		$num_telefon = fnLimpacampo($_REQUEST['NUM_TELEFON']);
		$num_celular = fnLimpacampo($_REQUEST['NUM_CELULAR']);
		$num_comercial = fnLimpacampo($_REQUEST['NUM_COMERCIAL']);
		$cod_externo = fnLimpacampo($_REQUEST['COD_EXTERNO']);
		$num_cartao = fnLimpacampoZero($_REQUEST['NUM_CARTAO']);
		$num_cgcecpf = fnLimpacampo($_REQUEST['NUM_CGCECPF']);
		if ($num_cartao == 0 || $num_cartao == "") {
			$num_cartao = fnLimpacampoZero(fnLimpaDoc($_REQUEST['NUM_CGCECPF']));
		}
		$des_enderec = fnLimpacampo($_REQUEST['DES_ENDEREC']);
		$num_enderec = fnLimpacampo($_REQUEST['NUM_ENDEREC']);
		$des_complem = fnLimpacampo($_REQUEST['DES_COMPLEM']);
		$des_bairroc = fnLimpacampo($_REQUEST['DES_BAIRROC']);
		$num_cepozof = fnLimpacampo($_REQUEST['NUM_CEPOZOF']);
		$nom_cidadec = fnLimpacampo($_REQUEST['NOM_CIDADEC']);
		$cod_estadof = fnLimpacampo($_REQUEST['COD_ESTADOF']);
		$cod_tpcliente = fnLimpacampoZero($_REQUEST['COD_TPCLIENTE']);
		$count_filtros = fnLimpacampo($_REQUEST['COUNT_FILTROS']);
		$log_ofertas = "N";
		$log_sus = fnLimpacampo($_REQUEST['LOG_SUS']);
		$num_cadunico = fnLimpacampoZero($_REQUEST['NUM_CADUNICO']);
		$cod_grauesc = fnLimpacampoZero($_REQUEST['COD_GRAUESC']);
		$des_localtrab = fnLimpacampo($_REQUEST['DES_LOCALTRAB']);

		//array dos sistemas da empresas
		if (isset($_POST['COD_PERFILS'])) {
			$Arr_COD_PERFILS = $_POST['COD_PERFILS'];
			//print_r($Arr_COD_SISTEMAS);			 

			for ($i = 0; $i < count($Arr_COD_PERFILS); $i++) {
				$cod_perfils = $cod_perfils . $Arr_COD_PERFILS[$i] . ",";
			}

			$cod_perfils = substr($cod_perfils, 0, -1);
		} else {
			$cod_perfils = "0";
		}


		//array das empresas multiacesso
		if (isset($_POST['COD_MULTEMP'])) {
			$Arr_COD_MULTEMP = $_POST['COD_MULTEMP'];
			//print_r($Arr_COD_MULTEMP);			 

			for ($i = 0; $i < count($Arr_COD_MULTEMP); $i++) {
				$cod_multemp = $cod_multemp . $Arr_COD_MULTEMP[$i] . ",";
			}

			$cod_multemp = substr($cod_multemp, 0, -1);
		} else {
			$cod_multemp = "0";
		}


		//fnEscreve($cod_perfils);

		$des_apelido = fnLimpacampo($_REQUEST['DES_APELIDO']);
		$cod_profiss = fnLimpacampo($_REQUEST['COD_PROFISS']);
		$cod_univend = fnLimpacampoZero($_REQUEST['COD_UNIVEND']);
		$des_contato = fnLimpacampo($_REQUEST['DES_CONTATO']);
		if (empty($_REQUEST['LOG_EMAIL'])) {
			$log_email = 'N';
		} else {
			$log_email = $_REQUEST['LOG_EMAIL'];
		}
		if (empty($_REQUEST['LOG_SMS'])) {
			$log_sms = 'N';
		} else {
			$log_sms = $_REQUEST['LOG_SMS'];
		}
		if (empty($_REQUEST['LOG_TELEMARK'])) {
			$log_telemark = 'N';
		} else {
			$log_telemark = $_REQUEST['LOG_TELEMARK'];
		}
		if (empty($_REQUEST['LOG_FUNCIONA'])) {
			$log_funciona = 'N';
		} else {
			$log_funciona = $_REQUEST['LOG_FUNCIONA'];
		}
		$nom_pai = fnLimpacampo($_REQUEST['NOM_PAI']);
		$nom_mae = fnLimpacampo($_REQUEST['NOM_MAE']);
		$cod_chaveco = fnLimpacampo($_REQUEST['COD_CHAVECO']);
		$key_externo = fnLimpacampo($_REQUEST['KEY_EXTERNO']);
		$tip_cliente = fnLimpacampo($_REQUEST['TIP_CLIENTE']);
		$des_coment = fnLimpacampo($_REQUEST['DES_COMENT']);

		$des_zona = fnLimpacampo($_REQUEST['DES_ZONA']);
		$des_auxfiltro = fnLimpacampo($_REQUEST['DES_AUXFILTRO']);
		$des_regadm = fnLimpacampo($_REQUEST['DES_REGADM']);
		$des_pref = fnLimpacampo($_REQUEST['DES_PREF']);
		$des_subpref = fnLimpacampo($_REQUEST['DES_SUBPREF']);
		$des_igreja = fnLimpacampo($_REQUEST['DES_IGREJA']);
		$des_local = fnLimpacampo($_REQUEST['DES_LOCAL']);
		$cod_grupoent = fnLimpacampoZero($_REQUEST['COD_GRUPOENT']);
		$cod_estado = fnLimpacampoZero($_REQUEST['COD_ESTADO']);
		$cod_municipio = fnLimpacampoZero($_REQUEST['COD_MUNICIPIO']);
		// fnEscreve($num_cartao);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = 1;

		if ($cod_usuario == 0 && $opcao != "") {
			$opcao = 'CAD';
		} else {
			$opcao = $opcao;
		}

		if (isset($_POST['COD_BENEFICIOS'])) {
			$Arr_COD_BENEFIC = $_POST['COD_BENEFICIOS'];
			//print_r($Arr_COD_USUARIOS_ATE);			 

			for ($i = 0; $i < count($Arr_COD_BENEFIC); $i++) {
				$cod_benefic = $cod_benefic . $Arr_COD_BENEFIC[$i] . ",";
			}

			$cod_benefic = substr($cod_benefic, 0, -1);
		} else {
			$cod_benefic = "0";
		}

		//echo $cod_benefic;

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					//verifica 
					/*switch ($cod_chaveco) {

						case 1: //cpf
							$num_cartao = fnLimpaDoc($num_cgcecpf);
							break;
						case 2: //cartao pre cadastrado
							//$num_cartao = "active";
							$num_cartao = $num_cartao;
							break;
						case 3: //telefone
							$num_cartao =  fnLimpaDoc($num_celular);
							break;
						case 4: //código externo
							$num_cartao = $num_cartao;
							break;
						case 5: //cartao + cpf
							$num_cartao = $num_cartao;
							break;
						case 6: //CPF/CNPJ/NASC/CEL/EMAIL
							$num_cartao = "0";
							break;
					}*/

					if (strlen(fnLimpaDoc($num_cgcecpf)) == '11') {
						$tip_cliente = "F";
					}

					//echo $cod_univend;
					//echo "<br>";
					//echo $num_cartao;

					//RICARDO APOS AQUI - VAI TER TODAS AS CRÍTICIAS SE FOR TIPO COM CARTAO  
					//$cod_chaveco = 2 ou 5

					$sql1 = "CALL SP_ALTERA_CLIENTES(
							'" . $cod_usuario . "',
							'" . $cod_empresa . "',
							'" . $nom_usuario . "',
							'" . $log_usuario . "',
							'" . $des_emailus . "',
							'" . $_SESSION["SYS_COD_USUARIO"] . "',    
							'" . fnLimpaDoc($num_cgcecpf) . "',
							'" . $log_estatus . "',
							'" . $log_trocaprod . "',
							'" . $num_rgpesso . "',
							'" . $dat_nascime . "',
							'" . $cod_estaciv . "',
							'" . $cod_sexopes . "',
							'" . $num_telefon . "',
							'" . $num_celular . "',
							'" . $num_comercial . "',
							'" . $cod_externo . "',
							'" . $num_cartao . "',
							'" . $num_tentati . "',
							'" . $des_enderec . "',
							'" . $num_enderec . "',
							'" . $des_complem . "',
							'" . $des_bairroc . "',
							'" . $num_cepozof . "',
							'" . $nom_cidadec . "',
							'" . $cod_estadof . "',
							'" . $des_apelido . "',
							'" . $cod_profiss . "',
							" . $cod_univend . ",
							" . $cod_univend_pref . ",
							'" . $tip_cliente . "',
							'" . $des_contato . "',
							'" . $log_email . "',
							'" . $log_sms . "',
							'" . $log_telemark . "',
							'N',
							'N',
							'S',
							'" . $nom_pai . "',
							'" . $nom_mae . "',
							'" . $cod_chaveco . "',
							'" . $cod_multemp . "',
							'" . $key_externo . "',
							'" . $cod_tpcliente . "',
							'" . $log_funciona . "',
							'N',
							'" . $log_ofertas . "',
							'" . $des_coment . "',
							'" . $opcao . "'    
						);";

					// fnEscreve($sql1);

					if ($cod_chaveco == 6) {
						$semCPF	= "S";
					} else {
						$semCPF	= "N";
					}

					//if($num_cgcecpf != "" && $num_cgcecpf != 0){
					if ($num_cgcecpf != "" || ($num_cgcecpf != 0 && $semCPF = "N")) {

						$execCliente = mysqli_query(connTemp($cod_empresa, ''), $sql1);
						$qrGravaCliente = mysqli_fetch_assoc($execCliente);
						$cod_clienteRetorno = $qrGravaCliente['COD_CLIENTE'];
						$mensagem = $qrGravaCliente['MENSAGEM'];
						$msgTipo = 'alert-success';

						if ($cod_usuario == 0 && $cod_clienteRetorno != 0) {

							$cod_indicad = fnLimpaCampoZero($_REQUEST['COD_INDICA']);

							if ($cod_indicad != 0) {

								$sql5 = "UPDATE CLIENTES SET 
													COD_INDICAD = $cod_indicad, 
													DAT_INDICAD = NOW() 
										 WHERE COD_CLIENTE = $cod_clienteRetorno";

								mysqli_query(connTemp($cod_empresa, ''), $sql5) or die(mysqli_error());
							}
						}

						if ($count_filtros != "") {

							$sql = "";
							$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

							for ($i = 0; $i < $count_filtros; $i++) {

								$cod_filtro = fnLimpacampoZero($_REQUEST["COD_FILTRO_$i"]);
								$cod_tpfiltro = fnLimpacampoZero($_REQUEST["COD_TPFILTRO_$i"]);

								// if($cod_filtro != 0){
								$sql .= "INSERT INTO CLIENTE_FILTROS(
														COD_EMPRESA,
														COD_TPFILTRO,
														COD_FILTRO,
														COD_CLIENTE,
														COD_USUCADA
														)VALUES(
														$cod_empresa,
														$cod_tpfiltro,
														$cod_filtro,
														$cod_clienteRetorno,
														$cod_usucada
														);";
								// }

							}

							//fnEscreve($sql);
							if ($sql != "") {
								mysqli_multi_query(connTemp($cod_empresa, ''), $sql);
							}
						}

						$sql .= "INSERT INTO DADOS_APOIADOR(
															COD_CLIENTE,
															COD_EMPRESA,
															DES_ZONA,
															DES_AUXFILTRO,
															DES_REGADM,
															DES_PREF,
															DES_SUBPREF,
															COD_GRUPOENT,
															LOG_SUS,
															NUM_CADUNICO,
															COD_GRAUESC,
															DES_LOCALTRAB,
															LOG_RECEBE,
															COD_BENEFICIO,
															COD_USUCADA
															) VALUES(
															$cod_usuario,
															$cod_empresa,
															'$des_zona',
															'$des_auxfiltro',
															'$des_regadm',
															'$des_pref',
															'$des_subpref',									
															'$cod_grupoent',
															'$log_sus',
															'$num_cadunico',
															$cod_grauesc,
															'$des_localtrab',
															'$log_recebe',
															'$cod_benefic',
															$cod_usucada
															);";
						mysqli_query(connTemp($cod_empresa, ''), $sql);
					} else {

						$cod_clienteRetorno = 0;
						$mensagem = "Apoiador avulso não pode ser alterado!";
						$msgTipo = 'alert-danger';
					}

					//fnEscreve($cod_clienteRetorno);
					//fnEscreve($mensagem);
					if ($mensagem == "Este apoiador já existe!") {

						$msgRetorno = $mensagem;
						$msgTipo = 'alert-danger';
					} else if ($mensagem == "Novo apoiador cadastrado com <strong> sucesso! </strong>") {
						$cod_empresa = fnEncode($cod_empresa);
						$cod_cliente = fnEncode($cod_clienteRetorno);
?>
						<script>
							window.location.replace("action.php?mod=fw6GN2ElSag¢&id=<?= $cod_empresa ?>&idC=<?= $cod_cliente ?>");
						</script>
<?php
					} else {

						$msgRetorno = $mensagem;
					}

					$cod_usuario = $cod_clienteRetorno;

					break;



				case 'ALT':
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					$msgTipo = 'alert-success';

					$sql2 = "CALL SP_ALTERA_CLIENTES(
							'" . $cod_usuario . "',
							'" . $cod_empresa . "',
							'" . $nom_usuario . "',
							'" . $log_usuario . "',
							'" . $des_emailus . "',
							'" . $_SESSION["SYS_COD_USUARIO"] . "',    
							'" . fnLimpaDoc($num_cgcecpf) . "',
							'" . $log_estatus . "',
							'" . $log_trocaprod . "',
							'" . $num_rgpesso . "',
							'" . $dat_nascime . "',
							'" . $cod_estaciv . "',
							'" . $cod_sexopes . "',
							'" . $num_telefon . "',
							'" . $num_celular . "',
							'" . $num_comercial . "',
							'" . $cod_externo . "',
							'" . $num_cartao . "',
							'" . $num_tentati . "',
							'" . $des_enderec . "',
							'" . $num_enderec . "',
							'" . $des_complem . "',
							'" . $des_bairroc . "',
							'" . $num_cepozof . "',
							'" . $nom_cidadec . "',
							'" . $cod_estadof . "',
							'" . $des_apelido . "',
							'" . $cod_profiss . "',
							" . $cod_univend . ",
							" . $cod_univend_pref . ",
							'" . $tip_cliente . "',
							'" . $des_contato . "',
							'" . $log_email . "',
							'" . $log_sms . "',
							'" . $log_telemark . "',
							'N',
							'N',
							'S',
							'" . $nom_pai . "',
							'" . $nom_mae . "',
							'" . $cod_chaveco . "',
							'" . $cod_multemp . "',
							'" . $key_externo . "',
							'" . $cod_tpcliente . "',
							'" . $log_funciona . "',
							'N',
							'" . $log_ofertas . "',
							'" . $des_coment . "',
							'" . $opcao . "'   
								
						);";

					//echo($sql2);
					fnEscreve($sql2);
					//if($num_cgcecpf != "" && $num_cgcecpf != 0){
					if ($num_cgcecpf != "" || ($num_cgcecpf != 0 && $semCPF = "N")) {
						mysqli_query(conntemp($cod_empresa, ''), $sql2);

						if ($count_filtros != "") {

							$sql = "DELETE FROM CLIENTE_FILTROS WHERE COD_CLIENTE = $cod_usuario;";
							$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

							for ($i = 0; $i < $count_filtros; $i++) {

								$cod_filtro = fnLimpacampoZero($_REQUEST["COD_FILTRO_$i"]);
								$cod_tpfiltro = fnLimpacampoZero($_REQUEST["COD_TPFILTRO_$i"]);

								// if($cod_filtro != 0){
								$sql .= "INSERT INTO CLIENTE_FILTROS(
														COD_EMPRESA,
														COD_TPFILTRO,
														COD_FILTRO,
														COD_CLIENTE,
														COD_USUCADA
														)VALUES(
														$cod_empresa,
														$cod_tpfiltro,
														$cod_filtro,
														$cod_usuario,
														$cod_usucada
														);";
								// }

							}

							//fnEscreve($sql);
							if ($sql != "") {
								mysqli_multi_query(connTemp($cod_empresa, ''), $sql);
							}
						}

						$sql = "";

						$sql .= "DELETE FROM DADOS_APOIADOR WHERE COD_CLIENTE = $cod_usuario;";

						$sql .= "INSERT INTO DADOS_APOIADOR(
											COD_CLIENTE,
											COD_EMPRESA,
											DES_ZONA,
											DES_AUXFILTRO,
											DES_REGADM,
											DES_PREF,
											DES_SUBPREF,
											COD_GRUPOENT,
											LOG_SUS,
											NUM_CADUNICO,
											COD_GRAUESC,
											DES_LOCALTRAB,
											LOG_RECEBE,
											COD_BENEFICIO,
											COD_USUCADA
											) VALUES(
											$cod_usuario,
											$cod_empresa,
											'$des_zona',
											'$des_auxfiltro',
											'$des_regadm',
											'$des_pref',
											'$des_subpref',									
											'$cod_grupoent',
											'$log_sus',
											'$num_cadunico',
											$cod_grauesc,
											'$des_localtrab',
											'$log_recebe',
											'$cod_benefic',
											$cod_usucada
											);";

						//echo $sql;
						mysqli_multi_query(connTemp($cod_empresa, ''), $sql);
					} else {
						$msgRetorno = "Apoiador avulso não pode ser alterado!";
						$msgTipo = 'alert-danger';
					}

					break;

				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					$msgTipo = 'alert-success';

					break;
			}
		}

		$sql = "UPDATE CLIENTES SET COD_ESTADO = $cod_estado, COD_MUNICIPIO = $cod_municipio WHERE COD_CLIENTE = $cod_usuario";
		mysqli_query(connTemp($cod_empresa, ''), $sql);

		$newDate = explode('/', $dat_nascime);
		$dia = $newDate[0];
		$mes   = $newDate[1];
		$ano  = $newDate[2];

		$sql = "UPDATE CLIENTES SET DIA = $dia, MES = $mes, ANO = $ano WHERE NUM_CGCECPF = " . fnLimpaDoc($num_cgcecpf);
		//fnEscreve($sql);
		mysqli_query(connTemp($cod_empresa, ''), $sql);

		if ($num_cepozof != '') {

			$sqlCoord = "SELECT LATITUDE, LONGITUDE FROM CEPBR_GEO
						WHERE CEP = " . fnLimpaDoc($num_cepozof);

			// fnEscreve($sqlCoord);

			$qrCoord = mysqli_fetch_assoc(mysqli_query($DADOS_CEP->connUser(), $sqlCoord));

			if (isset($qrCoord)) {

				$sqlAtt = "UPDATE CLIENTES SET LAT = $qrCoord[LATITUDE], LNG = $qrCoord[LONGITUDE] WHERE COD_CLIENTE = $cod_usuario";
				// fnEscreve($sqlAtt);
				mysqli_query(connTemp($cod_empresa, ''), $sqlAtt);
			}
		}
	}
}

//fnEscreve2($cod_cliente);	
//fnEscreve2("chegou aqui");	

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {

	$cod_empresa = fnDecode($_GET['id']);
	if (empty($cod_clienteRetorno)) {
		//fnEscreve("if");
		if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))) {
			//fnEscreve("if1");
			$cod_cliente = fnDecode($_GET['idC']);
			//fnEscreve($cod_cliente);		
		} else {
			//fnEscreve("if2");
			$cod_cliente = 0;
		}
	} else {
		//fnEscreve("else");
		$cod_cliente = $cod_clienteRetorno;
	}

	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_CHAVECO, LOG_CATEGORIA, LOG_AUTOCAD
			  FROM empresas WHERE COD_EMPRESA=$cod_empresa";

	//fnEscreve($sql);		
	$qrBuscaEmpresa = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), trim($sql)));
	$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
	$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	$cod_chaveco = $qrBuscaEmpresa['COD_CHAVECO'];
	$log_categoria = $qrBuscaEmpresa['LOG_CATEGORIA'];
	$log_autocad = $qrBuscaEmpresa['LOG_AUTOCAD'];

	include "labelLibrary.php";

	//categoria de clientes		
	$sql2 = "SELECT B.NOM_FAIXACAT,(SELECT SUM(T.VAL_EMENDA) FROM emenda T
			WHERE T.COD_EMPRESA= A.COD_EMPRESA
			AND T.COD_MUNICIPIO= A.COD_MUNICIPIO) AS VAL_EMENDA,A.* 
				FROM clientes A
				left join categoria_cliente B ON B.COD_CATEGORIA=A.COD_CATEGORIA
				WHERE A.COD_CLIENTE = $cod_cliente and 
				A.COD_EMPRESA = $cod_empresa";

	$arrCliente = mysqli_query(connTemp($cod_empresa, ''), $sql2);

	$qrBuscaCliente = mysqli_fetch_assoc($arrCliente);
	//fnEscreve($sql2);	
	//fnEscreve($cod_cliente);	

	if (isset($qrBuscaCliente)) {

		if ($cod_cliente != 0) {
			$cod_usuario = $qrBuscaCliente['COD_CLIENTE'];
			$cod_externo = $qrBuscaCliente['COD_EXTERNO'];
			$nom_usuario = $qrBuscaCliente['NOM_CLIENTE'];
			$num_cartao =  $qrBuscaCliente['NUM_CARTAO'];
			$num_cgcecpf = $qrBuscaCliente['NUM_CGCECPF'];
			$num_rgpesso = $qrBuscaCliente['NUM_RGPESSO'];
			$dat_nascime = $qrBuscaCliente['DAT_NASCIME'];
			$cod_estaciv = $qrBuscaCliente['COD_ESTACIV'];
			$cod_sexopes = $qrBuscaCliente['COD_SEXOPES'];
			$des_emailus = $qrBuscaCliente['DES_EMAILUS'];
			$num_telefon = $qrBuscaCliente['NUM_TELEFON'];
			$num_celular = $qrBuscaCliente['NUM_CELULAR'];
			$num_comercial = $qrBuscaCliente['NUM_COMERCI'];
			$des_enderec = $qrBuscaCliente['DES_ENDEREC'];
			$num_enderec = $qrBuscaCliente['NUM_ENDEREC'];
			$des_complem = $qrBuscaCliente['DES_COMPLEM'];
			$des_bairroc = $qrBuscaCliente['DES_BAIRROC'];
			$num_cepozof = $qrBuscaCliente['NUM_CEPOZOF'];
			$nom_cidadec = $qrBuscaCliente['NOM_CIDADEC'];
			$cod_estadof = $qrBuscaCliente['COD_ESTADOF'];
			$val_emenda = $qrBuscaCliente['VAL_EMENDA'];
			$dat_cadastr = fnFormatDateTime($qrBuscaCliente['DAT_CADASTR']);
			$log_usuario = $qrBuscaCliente['LOG_USUARIO'];
			if ($qrBuscaCliente['LOG_ESTATUS'] == 'S') {
				$check_ativo = 'checked';
			} else {
				$check_ativo = '';
			}
			if ($qrBuscaCliente['LOG_TROCAPROD'] == 'S') {
				$check_troca = 'checked';
			} else {
				$check_troca = '';
			}
			$num_tentati = $qrBuscaCliente['NUM_TENTATI'];
			$des_apelido = $qrBuscaCliente['DES_APELIDO'];
			$cod_profiss = $qrBuscaCliente['COD_PROFISS'];
			$cod_univend = $qrBuscaCliente['COD_UNIVEND'];
			$cod_tpcliente = $qrBuscaCliente['COD_TPCLIENTE'];
			$tip_cliente = $qrBuscaCliente['TIP_CLIENTE'];
			$des_contato = $qrBuscaCliente['DES_CONTATO'];
			if ($qrBuscaCliente['LOG_FUNCIONA'] == 'S') {
				$check_funciona = 'checked';
			} else {
				$check_funciona = '';
			}
			if ($qrBuscaCliente['LOG_EMAIL'] == 'S') {
				$check_mail = 'checked';
			} else {
				$check_mail = '';
			}
			if ($qrBuscaCliente['LOG_SMS'] == 'S') {
				$check_sms = 'checked';
			} else {
				$check_sms = '';
			}
			if ($qrBuscaCliente['LOG_TELEMARK'] == 'S') {
				$check_telemark = 'checked';
			} else {
				$check_telemark = '';
			}
			$nom_pai = $qrBuscaCliente['NOM_PAI'];
			$nom_mae = $qrBuscaCliente['NOM_MAE'];
			$cod_entidad = $qrBuscaCliente['COD_ENTIDAD'];
			$cod_multemp = $qrBuscaCliente['COD_MULTEMP'];
			if (empty($cod_multemp)) {
				$cod_multemp = "0";
			}
			$key_externo = $qrBuscaCliente['KEY_EXTERNO'];
			$cod_categoria = $qrBuscaCliente['COD_CATEGORIA'];
			$nom_faixacat = $qrBuscaCliente['NOM_FAIXACAT'];
			$cod_indicad = $qrBuscaCliente['COD_INDICAD'];
			$dat_indicad = fnDatasHORT($qrBuscaCliente['DAT_INDICAD']);
			$des_coment = $qrBuscaCliente['DES_COMENT'];
			$cod_usucada = $qrBuscaCliente['COD_USUCADA'];
			$cod_estado = $qrBuscaCliente['COD_ESTADO'];
			$cod_municipio = $qrBuscaCliente['COD_MUNICIPIO'];
			$dat_admissao = fnFormatDateTime($qrBuscaCliente['DAT_ADMISSAO']);
			$latitude = $qrBuscaCliente['LAT'];
			$longitude = $qrBuscaCliente['LNG'];
		} else {
			@$cod_usuario = 0;
			@$nom_usuario = '';
			@$cod_externo = '';
			@$num_cartao = '';
			@$num_cgcecpf = '';
			@$num_rgpesso = '';
			@$dat_nascime = '';
			@$cod_estaciv = 0;
			@$cod_sexopes = 0;
			@$des_emailus = '';
			@$num_telefon = '';
			@$num_celular = '';
			@$num_comercial = '';
			@$des_enderec = '';
			@$num_enderec = '';
			@$des_complem = '';
			@$des_bairroc = '';
			@$num_cepozof = '';
			@$nom_cidadec = '';
			@$cod_estadof = 0;
			@$dat_cadastr = '';
			@$log_usuario = '';
			@$des_senhaus = '';
			@$num_tentati = '';
			@$des_apelido = '';
			@$cod_profiss = '';
			@$cod_univend = '';
			@$des_contato = '';
			@$log_email = '';
			@$log_sms = '';
			@$log_telemark = '';
			@$nom_pai = '';
			@$nom_mae = '';
			@$check_ativo = 'checked';
			@$check_troca = 'checked';
			@$check_funciona = '';
			@$check_mail = 'checked';
			@$check_sms = 'checked';
			@$check_telemark = 'checked';
			@$cod_entidad = 0;
			@$cod_multemp = "0";
			@$key_externo = "";
			@$cod_tpcliente = "";
			@$cod_tpcliente = "";
			@$check_funciona = '';
			@$cod_indicad = 0;
			@$dat_indicad = '';
			@$cod_usucada = '';
			@$cod_estado = 0;
			@$cod_municipio = 0;
		}
	}
} else {
	@$cod_empresa = 0;
	@$nom_empresa = '';
	@$cod_externo = '';
	@$cod_usuario = 0;
	@$nom_usuario = '';
	@$num_cartao = '';
	@$num_cgcecpf = '';
	@$num_rgpesso = '';
	@$dat_nascime = '';
	@$cod_estaciv = 0;
	@$cod_sexopes = 0;
	@$des_emailus = '';
	@$num_telefon = '';
	@$num_celular = '';
	@$num_comercial = '';
	@$des_enderec = '';
	@$num_enderec = '';
	@$des_complem = '';
	@$des_bairroc = '';
	@$num_cepozof = '';
	@$nom_cidadec = '';
	@$cod_estadof = 0;
	@$dat_cadastr = '';
	@$log_usuario = '';
	@$des_senhaus = '';
	@$num_tentati = '';
	@$des_apelido = '';
	@$cod_profiss = '';
	@$cod_univend = '';
	@$des_contato = '';
	@$log_email = '';
	@$log_sms = '';
	@$log_telemark = '';
	@$nom_pai = '';
	@$nom_mae = '';
	@$cod_chaveco = 0;
	@$cod_entidad = 0;
	@$cod_multemp = "0";
	@$key_externo = "";
	@$cod_tpcliente = "";
	@$check_ativo = 'checked';
	@$check_troca = 'checked';
	@$check_mail = 'checked';
	@$check_sms = 'checked';
	@$check_telemark = 'checked';
	@$check_funciona = '';
	@$cod_indicad = 0;
	@$dat_indicad = '';
	@$cod_usucada = '';
	@$cod_estado = 0;
	@$cod_municipio = 0;
}

if ($cod_indicad != 0) {
	$sql = "SELECT NOM_CLIENTE FROM CLIENTES WHERE COD_CLIENTE = $cod_indicad";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

	$qrIndicad = mysqli_fetch_assoc($arrayQuery);
	$nom_indicad = $qrIndicad['NOM_CLIENTE'];
}

$sql = "SELECT * FROM DADOS_APOIADOR WHERE COD_CLIENTE = $cod_cliente";
// fnEscreve($sql);
$qrEleit = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

if (isset($qrEleit) && $cod_cliente != 0) {
	$des_zona = $qrEleit['DES_ZONA'];
	$des_auxfiltro = $qrEleit['DES_AUXFILTRO'];
	$des_regadm = $qrEleit['DES_REGADM'];
	$des_pref = $qrEleit['DES_PREF'];
	$des_subpref = $qrEleit['DES_SUBPREF'];
	$des_igreja = $qrEleit['DES_IGREJA'];
	$des_local = $qrEleit['DES_LOCAL'];
	$cod_grupoent = $qrEleit['COD_GRUPOENT'];
	$cod_grauesc = $qrEleit['COD_GRAUESC'];
	$log_sus = $qrEleit['LOG_SUS'];
	$num_cadunico = $qrEleit['NUM_CADUNICO'];
	$des_localtrab = $qrEleit['DES_LOCALTRAB'];
	$log_recebe = $qrEleit['LOG_RECEBE'];
	$des_beneficio = $qrEleit['DES_BENEFICIO'];
	$cod_benefic = $qrEleit['COD_BENEFICIO'];
} else {
	$des_zona = "";
	$des_auxfiltro = "";
	$des_regadm = "";
	$des_pref = "";
	$des_subpref = "";
	$des_igreja = "";
	$des_local = "";
	$cod_grupoent = 0;
	$cod_grauesc = 0;
	$log_sus = "";
	$num_cadunico = "";
	$des_localtrab = "";
	$log_recebe = "";
	$des_beneficio = "";
	$cod_benefic = "";
}

// fnEscreve(FNdECODE("gdKgip5aBK4¢"));
// fnEscreve($des_local);
/*Criticas chave de cadastro
	1 - CPF/CNPJ
	2 - CARTÃO PRE CADASTRADO
	3 - TELEFONE
	4 - CODIGO EXTERNO 
	5 - CPF/CNPJ+CARTAO 
	6 - CPF/CNPJ/NASC/CEL/EMAIL 
	*/

switch ($cod_chaveco) {
	case 6: //CPF/CNPJ/NASC/CEL/EMAIL
		$obgDAT_NASCIME = "";
		break;
	default:
		$obgDAT_NASCIME = "required";
}

if ($_SESSION['SYS_COD_USUARIO'] == 33103) {
	$endObriga = "";
} else {
	$endObriga = "required";
}

$sqlInd = "SELECT COD_PERFILS FROM USUARIOS WHERE COD_USUARIO = $_SESSION[SYS_COD_USUARIO]";
$qrUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), trim($sqlInd)));
// fnEscreve($cod_empresa);

if ($qrUsu['COD_PERFILS'] == 1154) {
	$master = "N";
} else {
	$master = "S";
}

$sqlEleicao = "SELECT
					(SELECT COUNT(*) FROM CLIENTES E WHERE  E.COD_MUNICIPIO=B.COD_MUNICIPIO) AS QTD_CADASTRO,
					(SELECT  SUM(QT_VOTOS_NOMINAIS) FROM ELEICOES F WHERE  F.CD_MUNICIPIO=B.COD_MUNICIPIO_E AND ANO_ELEICAO=2018 AND NR_CANDIDATO=31031) AS QTD_VOTOS
					FROM MUNICIPIOS B WHERE B.COD_MUNICIPIO = $cod_municipio";
$qrEleicao = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), trim($sqlEleicao)));

$tot_votos = $qrEleicao['QTD_VOTOS'];
$tot_apoiadores = $qrEleicao['QTD_CADASTRO'];

// $mod = fnLimpaCampo($_GET['mod']);

// fnEscreve($mod);

$grupoTrabalho = "";

switch ($cod_empresa) {

	case 136:
		$acesso = "none";
		break;
	case 311:
		$acesso = "none";
		break;
	case 224:
		$acesso = "none";
		break;
	default:
		$acesso = "inline";
}

if ($log_recebe == "S") {
	$checkRecebe = "checked";
	$disabledRecebe = "";
} else {
	$check_recebe = "";
	$disabledRecebe = "disabled";
}



?>

<style>
	.alert .alert-link {
		text-decoration: none;
	}

	.alert:hover .alert-link:hover {
		text-decoration: underline;
	}

	.foto {
		margin-left: auto !important;
		margin-right: auto !important;
		border: 1px solid #dce4ec;
	}

	#btn-foto {
		width: 100% !important;
	}

	input[type=number]::-webkit-inner-spin-button,
	input[type=number]::-webkit-outer-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}

	.field-icon {
		float: right;
		margin-left: -40px;
		margin-top: -25px;
		position: relative;
		z-index: 5;
	}

	.addprof {
		font-weight: bolder;
		font-size: 11px;
		color: #000;
		font-family: 'FontAwesome', '';
	}
</style>

<?php if ($popUp != "true") {  ?>
	<div class="push30"></div>
<?php } ?>

<div class="row">

	<div class="col-md12 margin-bottom-30">

		<!-- Portlet -->
		<?php if ($popUp != "true") {  ?>
			<div class="portlet portlet-bordered">
			<?php } else { ?>
				<div class="portlet" style="padding: 0 20px 20px 20px;">
				<?php } ?>

				<?php if ($popUp != "true") {  ?>
					<div class="portlet-title">
						<div class="caption">
							<i class="fal fa-terminal"></i>
							<span class="text-primary"><?php echo $abaNome; ?> / <?php echo $nom_empresa; ?></span>
						</div>

						<?php
						switch ($_SESSION["SYS_COD_SISTEMA"]) {
							case 16: //gerenciador social
								$formBack = "1424";
								break;
							default;
								$formBack = "1015";
								break;
						}
						include "atalhosPortlet.php";
						?>

					</div>
				<?php } ?>

				<div class="portlet-body">

					<?php if ($msgRetorno <> '') { ?>
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php } ?>

					<?php
					//verifica se tem bloqueio
					$sql4 = "SELECT COUNT(*) as TEM_BLOQUEIO
											FROM CLIENTES A, VENDAS B
											LEFT JOIN $connAdm->DB.unidadevenda d ON d.cod_univend = b.cod_univend 
											WHERE A.COD_CLIENTE=B.COD_CLIENTE AND 
											B.COD_STATUSCRED=3 AND 
                                            B.cod_avulso!=1 AND
											A.COD_EMPRESA = $cod_empresa and
											A.COD_CLIENTE = $cod_cliente ";

					//fnEscreve($sql4);
					$qrBuscaBloqueio = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql4));
					//fnEscreve($sql4);

					$tem_bloqueio = $qrBuscaBloqueio['TEM_BLOQUEIO'];

					if ($tem_bloqueio > 0) { ?>

						<div class="alert alert-warning alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
							Apoiador possui vendas bloqueadas. <br />
							<a href="action.do?mod=<?php echo fnEncode(1099); ?>&id=<?php echo fnEncode($cod_empresa); ?>" target="_blank" class="alert-link">&rsaquo; Acessar tela de desbloqueio</a>
						</div>
					<?php } ?>

					<?php
					//menu superior - cliente
					$abaEmpresa = 1020;
					$abaCli = 1423;
					if ($popUp != "true") {
						switch ($_SESSION["SYS_COD_SISTEMA"]) {
							case 14: //rede duque
								include "abasClienteDuque.php";
								break;
							default;
								include "abasClienteConfig.php";
								break;
						}
					}

					?>

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="action.php?mod=fw6GN2ElSag¢&id=<?= fnEncode($cod_empresa) ?>&idC=<?= fnEncode($cod_cliente) ?>">

							<fieldset>
								<legend>Dados Gerais</legend>

								<!-- bloco dados básicos -->
								<div class="col-xs-10">

									<div class="row">

										<?php
										if ($_SESSION["SYS_COD_SISTEMA"] == 14) {

											$sql3 = "select NOM_ENTIDAD from ENTIDADE where COD_ENTIDAD = $cod_entidad";
											$qrBuscaEntidade = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql3));
											//fnEscreve($sql3);	
											$nom_entidad = $qrBuscaEntidade['NOM_ENTIDAD'];
										?>

											<div class="col-xs-3">
												<div class="form-group">
													<label for="inputName" class="control-label">Empresa Associada</label>
													<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_ENTIDAD" id="NOM_ENTIDAD" value="<?php echo $nom_entidad; ?>" maxlength="50" data-error="Campo obrigatório">
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="push10"></div>

										<?php
										}
										?>

										<div class="col-xs-2 hidden-print">
											<div class="form-group">
												<label for="inputName" class="control-label required hidden-print">Ativo</label><br />
												<label class="switch">
													<input type="checkbox" name="LOG_ESTATUS" id="LOG_ESTATUS" class="switch" value="S" <?php echo $check_ativo; ?> />
													<span></span>
												</label>
												<div class="help-block with-errors"></div>
											</div>

										</div>

										<!--Apoiador é Funcionário / Permite Troca de Produtos -->
										<input type="hidden" name="LOG_FUNCIONA" id="LOG_FUNCIONA" value="N" />
										<input type="hidden" name="LOG_TROCAPROD" id="LOG_TROCAPROD" value="N" />

										<?php if ($log_categoria == "S") { ?>
											<div class="col-xs-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Categoria do Apoiador</label>
													<div class="push5"></div>
													<span class="label label-pill label-info f14"><i class="fa fa-bookmark"></i> &nbsp; <?php echo $nom_faixacat; ?></span>
												</div>
											</div>
										<?php } ?>

										<div class="push10"></div>

										<div class="col-xs-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">Código</label>
												<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_USUARIO" id="COD_USUARIO" value="<?php echo $cod_usuario; ?>">
											</div>
										</div>

										<div class="col-xs-3">
											<div class="form-group">
												<label for="inputName" class="control-label required">Empresa</label>
												<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa; ?>">
												<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
											</div>
										</div>

										<div class="col-xs-4">
											<label for="inputName" class="control-label required"><?= $labelNome; ?></label>
											<div class="input-group">
												<span class="input-group-btn">
													<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Busca Clientes"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
												</span>
												<input type="text" name="NOM_USUARIO" id="NOM_USUARIO" value="<?php echo $nom_usuario; ?>" maxlength="50" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório" required>
											</div>
											<div class="help-block with-errors"></div>
										</div>

										<?php
										switch ($_SESSION["SYS_COD_SISTEMA"]) {
											case 14: //rede duque
												$cartaoObg = "";
												break;
											default;
												$cartaoObg = "required";
												break;
										}
										?>

										<!-- <div class="col-xs-2" hidden="">
														<div class="form-group">
															<label for="inputName" class="control-label <?php echo $cartaoObg; ?> ">Número do Cartão</label>
															<?php if ($cod_cliente == 0) { ?>
															
																<?php if ($log_autocad == "N") {
																	if ($cod_chaveco == 2 || $cod_chaveco == 5) {
																?>
																		<input type="text" class="form-control input-sm" name="NUM_CARTAO" id="NUM_CARTAO" value="" maxlength="50" data-error="Campo obrigatório" required>
																	<?php } else { ?>

																<input type="text" class="form-control input-sm leitura" name="NUM_CARTAO_VAZIO" id="NUM_CARTAO_VAZIO" value="" maxlength="50" readonly="readonly" data-error="Campo obrigatório" >
																<input type="hidden" class="form-control input-sm leitura" name="NUM_CARTAO" id="NUM_CARTAO" value="">

																<?php
																	}
																} else { ?>

																<input type="text" class="form-control input-sm" name="NUM_CARTAO" id="NUM_CARTAO" value="" maxlength="50" data-error="Campo obrigatório" required>

																<?php } ?>
															
															<?php } else { ?>

                                                            <input type="text" class="form-control input-sm leitura" name="NUM_CARTAO" id="NUM_CARTAO" value="<?php echo $num_cartao; ?>" maxlength="50" readonly="readonly" data-error="Campo obrigatório" <?php echo $cartaoObg; ?> >
                                                            
															<?php } ?>
															<div class="help-block with-errors"></div>
														</div>
													</div> -->

										<input type="hidden" name="NUM_CARTAO" id="NUM_CARTAO" value="">

									</div>

									<div class="row">

										<div class="col-xs-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Apelido</label>
												<input type="text" class="form-control input-sm" name="DES_APELIDO" id="DES_APELIDO" value="<?php echo $des_apelido; ?>" maxlength="18" data-error="Campo obrigatório">
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-xs-2">
											<div class="form-group">
												<label for="inputName" class="control-label">CNPJ/CPF</label>
												<input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?php echo fnCompletaDoc($num_cgcecpf, 'F'); ?>" maxlength="18" data-error="Campo obrigatório">
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-xs-2">
											<div class="form-group">
												<label for="inputName" class="control-label">RG</label>
												<input type="text" class="form-control input-sm" name="NUM_RGPESSO" id="NUM_RGPESSO" value="<?php echo $num_rgpesso; ?>" maxlength="15" data-error="Campo obrigatório">
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-xs-2">
											<div class="form-group">
												<label for="inputName" class="control-label required lbl_req">Data de Nascimento</label>
												<input type="text" class="form-control input-sm data" name="DAT_NASCIME" value="<?php echo $dat_nascime; ?>" id="DAT_NASCIME" maxlength="10" required>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-xs-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Estado Civil</label>
												<select data-placeholder="Selecione um estado civil" name="COD_ESTACIV" id="COD_ESTACIV" class="chosen-select-deselect">
													<option value=""></option>
													<?php
													$sql = "select COD_ESTACIV, DES_ESTACIV from estadocivil order by des_estaciv; ";
													$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

													while ($qrListaEstCivil = mysqli_fetch_assoc($arrayQuery)) {
														echo "
																<option value='" . $qrListaEstCivil['COD_ESTACIV'] . "'>" . $qrListaEstCivil['DES_ESTACIV'] . "</option> 
															";
													}
													?>
												</select>
												<script>
													$("#formulario #COD_ESTACIV").val("<?php echo $cod_estaciv; ?>").trigger("chosen:updated");
												</script>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-xs-2">
											<div class="form-group">
												<label for="inputName" class="control-label required lbl_req">Sexo</label>
												<select data-placeholder="Selecione o sexo" name="COD_SEXOPES" id="COD_SEXOPES" class="chosen-select-deselect requiredChk" required>
													<option value=""></option>
													<?php
													$sql = "select COD_SEXOPES, DES_SEXOPES from sexo order by des_sexopes ";
													$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

													while ($qrListaSexo = mysqli_fetch_assoc($arrayQuery)) {
														echo "
																<option value='" . $qrListaSexo['COD_SEXOPES'] . "'>" . $qrListaSexo['DES_SEXOPES'] . "</option> 
															";
													}
													?>
												</select>
												<script>
													$("#formulario #COD_SEXOPES").val("<?php echo $cod_sexopes; ?>").trigger("chosen:updated");
												</script>
												<div class="help-block with-errors"></div>
											</div>
										</div>
									</div>

									<div class="row">

										<!--escolaridade-->

										<div class="col-xs-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Escolaridade </label>
												<select data-placeholder="Selecione a escolaridade" name="COD_GRAUESC" id="COD_GRAUESC" class="chosen-select-deselect">
													<option value=""></option>
													<option value="1">Educação infantil</option>
													<option value="2">Fundamental</option>
													<option value="3">Médio</option>
													<option value="4">Superior (Graduação)</option>
													<option value="5">Pós-graduação</option>
													<option value="6">Mestrado</option>
													<option value="7">Doutorado</option>
													<option value="8">Escola</option>
												</select>
												<script>
													$("#formulario #COD_GRAUESC").val("<?= $cod_grauesc; ?>").trigger("chosen:updated");
												</script>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-xs-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Tipo de Pessoa </label>
												<select data-placeholder="Selecione o tipo da pessoa" name="TIP_CLIENTE" id="TIP_CLIENTE" class="chosen-select-deselect">
													<option value="F">Física</option>
													<option value="J">Jurídica</option>
												</select>
												<script>
													$("#formulario #TIP_CLIENTE").val("<?php echo $tip_cliente; ?>").trigger("chosen:updated");
												</script>
												<div class="help-block with-errors"></div>
											</div>
										</div>
										<?php

										if ($master == 'N' && $cod_indicad == 0 && $cod_cliente != 0) {

										?>

											<!-- <div class="col-xs-4">
												<label for="inputName" class="control-label <?php echo $obriga_indic ?> ">Nome do Indicador</label>
												<div class="input-group">
													<span class="input-group-btn">
														<a type="button" name="btnBuscaInd" id="btnBuscaInd" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?php echo fnEncode($cod_cliente) ?>&pop=true&op=IND" data-title="Busca Indicador"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
													</span>
													<input type="text" name="NOM_INDICA" id="NOM_INDICA" value="" maxlength="50" readonly="" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório" <?php echo $obriga_indic ?>>
													<input type="hidden" name="COD_INDICA" id="COD_INDICA" value="">
												</div>
												<div class="help-block with-errors"></div>
											</div>

											<div class="col-xs-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Data da Indicação</label>
													<input type="text" class="form-control input-sm leitura" name="DAT_INDICA" id="DAT_INDICA" value="<?= $dat_indicad ?>" maxlength="50" readonly="readonly">
													<div class="help-block with-errors"></div>
												</div>
											</div> -->

										<?php } else if ($master == 'S' && $cod_indicad != 0 && $cod_cliente != 0) { ?>
											<!-- <div class="col-xs-4">
												<label for="inputName" class="control-label <?php echo $obriga_indic ?>">Nome do Indicador</label>
												<div class="input-group">
													<span class="input-group-btn">
														<a type="button" name="btnBuscaInd" id="btnBuscaInd" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?php echo fnEncode($cod_cliente) ?>&pop=true&op=IND" data-title="Busca Indicador"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
													</span>
													<input type="text" name="NOM_INDICA" id="NOM_INDICA" value="<?php echo $nom_indicad; ?>" maxlength="50" readonly="" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório" <?php echo $obriga_indic ?>>
													<input type="hidden" name="COD_INDICA" id="COD_INDICA" value="<?php echo $cod_indicad; ?>">
												</div>
												<div class="help-block with-errors"></div>
											</div>

											<div class="col-xs-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Data da Indicação</label>
													<input type="text" class="form-control input-sm leitura" name="DAT_INDICA" id="DAT_INDICA" value="<?= $dat_indicad ?>" maxlength="50" readonly="readonly">
													<div class="help-block with-errors"></div>
												</div>
											</div> -->

										<?php } else if ($cod_cliente == 0) { ?>

											<!-- <div class="col-xs-4">
												<label for="inputName" class="control-label <?php echo $obriga_indic ?>">Nome do Indicador</label>
												<div class="input-group">
													<span class="input-group-btn">
														<a type="button" name="btnBuscaInd" id="btnBuscaInd" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?php echo fnEncode(0) ?>&pop=true&op=IND" data-title="Busca Indicador"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
													</span>
													<input type="text" name="NOM_INDICA" id="NOM_INDICA" value="" maxlength="50" readonly="" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório" <?php echo $obriga_indic ?>>
													<input type="hidden" name="COD_INDICA" id="COD_INDICA" value="">
												</div>
												<div class="help-block with-errors"></div>
											</div>

											<div class="col-xs-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Data da Indicação</label>
													<input type="text" class="form-control input-sm leitura" name="DAT_INDICA" id="DAT_INDICA" value="<?= $dat_indicad ?>" maxlength="50" readonly="readonly">
													<div class="help-block with-errors"></div>
												</div>
											</div> -->

										<?php } else { ?>
											<!-- <div class="col-xs-4">
												<label for="inputName" class="control-label">Nome do Indicador</label>
												<div class="input-group">
													<span class="input-group-btn">
														<input type="text" name="NOM_INDICA" id="NOM_INDICA" value="<?php echo $nom_indicad; ?>" maxlength="50" class="form-control input-sm leitura" readonly style="border-radius:0 3px 3px 0;">
														<input type="hidden" name="COD_INDICA" id="COD_INDICA" value="<?php echo $cod_indicad; ?>">
												</div>
												<div class="help-block with-errors"></div>
											</div>

											<div class="col-xs-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Data da Indicação</label>
													<input type="text" class="form-control input-sm leitura" name="DAT_INDICA" id="DAT_INDICA" value="<?= $dat_indicad; ?>" maxlength="50" readonly="readonly">
													<div class="help-block with-errors"></div>
												</div>
											</div> -->
										<?php } ?>

										<div class="col-xs-3" hidden="">
											<div class="form-group">
												<label for="inputName" class="control-label">Tipo do Cliente </label>
												<select data-placeholder="Selecione o tipo do cliente" name="COD_TPCLIENTE" id="COD_TPCLIENTE" class="chosen-select-deselect">
													<option value=""></option>
													<?php
													$sql = "select * from tipo_cliente where COD_EMPRESA = $cod_empresa order by DES_TIPOCLI ";
													$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error());

													while ($qrListaTipoCli = mysqli_fetch_assoc($arrayQuery)) {
														echo "
																<option value='" . $qrListaTipoCli['COD_TIPOCLI'] . "'>" . $qrListaTipoCli['DES_TIPOCLI'] . "</option> 
															";
													}
													?>
												</select>
												<script>
													$("#formulario #COD_TPCLIENTE").val("<?php echo $cod_tpcliente; ?>").trigger("chosen:updated");
												</script>
												<div class="help-block with-errors"></div>
											</div>
										</div>




									</div>

									<div class="row">

										<?php
										//gabinete damaris		
										if ($cod_empresa == 136) {
										?>

											<div class="col-xs-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Apoiadores no Município</label>
													<b><input type="text" class="form-control input-sm text-center" style="background-color: #FFEB3B; color: #303F9F;" value="<?= fnValor($tot_apoiadores, 0) ?>" readonly="readonly"></b>
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="col-xs-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Votos no Município</label>
													<b><input type="text" class="form-control input-sm text-center" style="background-color: #FFEB3B; color: #303F9F;" value="<?= fnValor($tot_votos, 0) ?>" readonly="readonly"></b>
													<div class="help-block with-errors"></div>
												</div>
											</div>
											<div class="col-xs-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Valor Emenda</label>
													<b><input type="text" class="form-control input-sm text-center" style="background-color: #FFEB3B; color: #303F9F;" value="<?= fnValor($val_emenda, 0) ?>" readonly="readonly"></b>
													<div class="help-block with-errors"></div>
												</div>
											</div>


										<?php
										} else {
										?>
											<input type="hidden" name="TOT_APOIADORES" id="TOT_APOIADORES" value="<?= fnValor($tot_apoiadores, 0) ?>">
											<input type="hidden" name="TOT_VOTOS" id="TOT_VOTOS" value="<?= fnValor($tot_votos, 0) ?>">
											<input type="hidden" name="VAL_EMENDA" id="VAL_EMENDA" value="<?= fnValor($val_emenda, 0) ?>">

										<?php
										}
										?>

										<?php
										if ($tip_cliente == 'F') {
											$tipoPessoa = "Física";
										} else {
											$tipoPessoa = "Jurídica";
										}
										?>

										<div class="col-xs-3">
											<div class="form-group">
												<label for="inputName" class="control-label">Nome do Pai</label>
												<input type="text" class="form-control input-sm" name="NOM_PAI" id="NOM_PAI" value="<?= $nom_pai ?>" maxlength="60" data-error="Campo obrigatório">
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-xs-3">
											<div class="form-group">
												<label for="inputName" class="control-label">Nome da Mãe</label>
												<input type="text" class="form-control input-sm" name="NOM_MAE" id="NOM_MAE" value="<?= $nom_mae ?>" maxlength="60" data-error="Campo obrigatório">
												<div class="help-block with-errors"></div>
											</div>
										</div>
										<?php if ($cod_empresa == 311) {
										?>
											<div class="col-xs-3">
												<div class="form-group">
													<label for="inputName" class="control-label">Cartão SUS</label>
													<input type="text" class="form-control input-sm" name="NUM_CARTAO" id="NUM_CARTAO" value="<?= $num_cartao ?>" maxlength="20" data-error="Campo obrigatório">
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="col-xs-3">
												<div class="form-group">
													<label for="inputName" class="control-label">Login SUS</label>
													<input type="password" class="form-control input-sm" name="LOG_SUS" id="LOG_SUS" value="<?= $log_sus ?>" maxlength="20" data-error="Campo obrigatório">
													<span toggle="#LOG_SUS" class="fa fa-fw fa-eye field-icon toggle-password"></span>
													<div class="help-block with-errors"></div>
												</div>
											</div>
											<div class="push10"></div>
											<div class="col-xs-3">
												<div class="form-group">
													<label for="inputName" class="control-label">NIS.Cad-único</label>
													<input type="number" class="form-control input-sm" name="NUM_CADUNICO" id="NUM_CADUNICO" value="<?= $num_cadunico ?>" maxlenght="20" data-error="Campo obrigatório">

													<div class="help-block with-errors"></div>
												</div>
											</div>
										<?php
										}
										?>

										<div class="col-xs-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Profissão </label>
												<div id="relatorioProfiss">
													<select data-placeholder="Selecione a profissão" name="COD_PROFISS" id="COD_PROFISS" class="chosen-select-deselect">
														<option value=""></option>
														<?php
														$sql = "select COD_PROFISS, DES_PROFISS from PROFISSOES_PREF WHERE COD_EMPRESA = $cod_empresa order by DES_PROFISS ";
														$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

														while ($qrListaProfi = mysqli_fetch_assoc($arrayQuery)) {
															echo "
																		<option value='" . $qrListaProfi['COD_PROFISS'] . "'>" . $qrListaProfi['DES_PROFISS'] . "</option> 
																	";
														}
														?>
														<option class="addprof" value="add">+&nbsp;ADICIONAR NOVO</option>
													</select>
													<script>
														$("#formulario #COD_PROFISS").val("<?= $cod_profiss; ?>").trigger("chosen:updated");
														$("#COD_PROFISS").change(function() {
															let valor = $("#COD_PROFISS").val()
															if (valor == "add") {
																$("#formulario #COD_PROFISS").val("").trigger("chosen:updated");
																$("#bnt_profiss").click();

															}
														})
													</script>
													<a type="hidden" name="bnt_profiss" id="bnt_profiss" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1811) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Cadastrar Profissão"></a>
													<div class="help-block with-errors"></div>
												</div>
											</div>
										</div>

										<div class="col-xs-3">
											<div class="form-group">
												<label for="inputName" class="control-label">Local de Trabalho</label>
												<input type="text" class="form-control input-sm" name="DES_LOCALTRAB" id="DES_LOCALTRAB" value="<?= $des_localtrab ?>" maxlength="20" data-error="Campo obrigatório">
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-xs-1">
											<div class="form-group">
												<label for="inputName" class="control-label">Tem Benefício?</label><br />
												<label class="switch switch-small">
													<input type="checkbox" name="LOG_RECEBE" id="LOG_RECEBE" class="switch" value="S" <?= $checkRecebe ?> />
													<span></span>
												</label>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-xs-3" id="BENEFICIOS">
											<div class="form-group">
												<label for="inputName" class="control-label">Benefícios</label>
												<div id="relatorioBeneficio">
													<select data-placeholder="Selecione os benefícios" name="COD_BENEFICIOS[]" id="COD_BENEFICIOS" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1" <?= $disabledRecebe ?>>
														<?php

														$sql = "SELECT * FROM BENEFICIOS 
																	WHERE COD_EMPRESA = $cod_empresa
																	";

														$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

														while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
															echo "
																	<option value='" . $qrLista['COD_BENEFICIOS'] . "'>" . $qrLista['DES_BENEFICIOS'] . "</option> 
																";
														}
														?>

														<option class="addprof" value="add">+&nbsp;ADICIONAR NOVO</option>
													</select>
													<script>
														$("#formulario #COD_BENEFICIOS").val("<?= $cod_benefic; ?>").trigger("chosen:updated");
														$("#COD_BENEFICIOS").change(function() {
															let valor = $("#COD_BENEFICIOS").val()
															if (valor == "add") {
																$("#formulario #COD_BENEFICIOS").val("").trigger("chosen:updated");
																$("#bnt_benefic").click();
															}
														})
													</script>

													<a type="hidden" name="bnt_benefic" id="bnt_benefic" class="addBox" data-url="action.php?mod=<?= fnEncode(1815) ?>&id=<?= fnEncode($cod_empresa) ?>&pop=true" data-title="Cadastrar Benefício"></a>
													<a class="btn btn-default btn-sm" id="iNone3" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>
												</div>
											</div>
										</div>

									</div>


									<?php
									//gabinete damaris		
									if ($cod_empresa == 136) {
									?>

										<div class="row">

											<div class="col-xs-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Igreja</label>
													<input type="text" class="form-control input-sm" name="DES_IGREJA" value="<?php echo $des_igreja; ?>" id="DES_IGREJA" maxlength="20">
													<div class="help-block with-errors"></div>
												</div>
											</div>


											<div class="col-xs-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Localidade</label>
													<input type="text" class="form-control input-sm" name="DES_LOCAL" value="<?php echo $des_local; ?>" id="DES_LOCAL" maxlength="20">
													<div class="help-block with-errors"></div>
												</div>
											</div>

										</div>

									<?php
									} else {
									?>
										<input type="hidden" name="DES_IGREJA" id="DES_IGREJA" value="<?= fnValor($des_igreja, 0) ?>">
										<input type="hidden" name="DES_LOCAL" id="DES_LOCAL" value="<?= fnValor($des_local, 0) ?>">

									<?php
									}
									?>

									<div class="push10"></div>

									<div class="row">

										<?php

										//indicador obrigatório - gabinete damaris
										if ($cod_empresa == 136) {
											$obriga_indic = "required";
										} else {
											$obriga_indic = "";
										}

										?>

										<div class="col-xs-4">
											<label for="inputName" class="control-label <?php echo $obriga_indic ?>">Nome do Indicador</label>
											<div class="input-group">
												<span class="input-group-btn">
													<a type="button" name="btnBuscaInd" id="btnBuscaInd" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?php echo fnEncode(0) ?>&pop=true&op=IND" data-title="Busca Indicador"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
												</span>
												<input type="text" name="NOM_INDICA" id="NOM_INDICA" value="<?= $nom_indicad ?>" maxlength="50" readonly="" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório" <?php echo $obriga_indic ?>>
												<input type="hidden" name="COD_INDICA" id="COD_INDICA" value="">
											</div>
											<div class="help-block with-errors"></div>
										</div>

										<div class="col-xs-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Data da Indicação</label>
												<input type="text" class="form-control input-sm leitura" name="DAT_INDICA" id="DAT_INDICA" value="<?= $dat_indicad ?>" maxlength="50" readonly="readonly">
												<div class="help-block with-errors"></div>
											</div>
										</div>

									</div>


									<!-- fim bloco dados basicos -->
								</div>

								<!-- bloco foto  -->
								<div class="col-xs-2">

									<div class="push20"></div>

									<div class="col-xs-12 text-center">
										<?php

										$sql = "SELECT * FROM FOTO_APOIADOR WHERE COD_CLIENTE = $cod_cliente";
										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

										$qrFoto = mysqli_fetch_assoc($arrayQuery);

										if (isset($qrFoto)) {
											$nom_arquivo = 'media/clientes/' . $cod_empresa . '/perfil/' . $qrFoto['NOM_ARQUIVO'] . '?rnd=';
										} else {
											$nom_arquivo = "media/clientes/" . $cod_empresa . "/default-user-avatar.png?rnd=";
										}

										?>
										<div id="div_perfil">
											<img id="foto_perfil" class="foto img-responsive" alt="Sem imagem">
										</div>
										<script type="text/javascript">
											var url = "<?= $nom_arquivo ?>" + Math.random();
											$('#foto_perfil').attr('src', url);
										</script>
									</div>

									<?php if ($cod_cliente != 0) { ?>

										<div class="col-xs-12 text-center">
											<button class="btn btn-xs btn-primary addBox" id="btn-foto" data-url="action.php?mod=<?php echo fnEncode(1447) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?php echo fnEncode($cod_cliente) ?>&pop=true" data-title="Adicionar Foto" style="width: 221px; margin-left: 1px; margin-top: 2px;"><i class="fas fa-camera"></i></button>
										</div>

									<?php } ?>

								</div>
								<!-- fim bloco foto  -->

							</fieldset>

							<?php if ($cod_empresa == 311 || $cod_empresa == 136) { ?>

								<div class="push10"></div>

								<fieldset>
									<legend>Dados Adicionais</legend>

									<div class="row">

										<!-- <div class="col-md-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Salario (base) </label>
                                                    <input type="text" class="form-control input-sm money" name="VAL_SALBASE" id="VAL_SALBASE" value="<?= fnValor($val_salbase, 2) ?>">
													<div class="help-block with-errors"></div>
												</div>
											</div> -->



										<div class="col-md-2">
											Remuneração <a href="javascript:void(0)" class="btn btn-default btn-xs addBox" data-url="action.php?mod=<?php echo fnEncode(1764) ?>&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($cod_cliente); ?>&pop=true&tipo=F" data-placement='top' data-title='Cadastro de Remuneração'><i class="fal fa-plus f12" aria-hidden="true"></i></a>

											<div class="push10"></div>

											<ul>

												<?php

												$sql = "SELECT LA.*, TC.DES_TIPO FROM LANCAMENTO_AUTOMATICO LA
																INNER JOIN TIP_CREDITO TC ON TC.COD_TIPO = LA.COD_TIPO
																WHERE LA.COD_EMPRESA = $cod_empresa
																AND LA.COD_CLIENTE = $cod_cliente
																LIMIT 3";

												// fnEscreve($sql);
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

												$count = 0;
												while ($qrRemunera = mysqli_fetch_assoc($arrayQuery)) {

												?>

													<li class="f12"><?= $qrRemunera[DES_TIPO] . ": " . fnValor($qrRemunera['VAL_LANCAME'], 2) ?></li>

												<?php

												}

												?>

											</ul>


											<div class="push20"></div>

										</div>

										<?php if ($cod_cliente != 0 && $cod_cliente != "") { ?>

											<div class="col-md-2">
												Dependentes <a href="javascript:void(0)" class="btn btn-default btn-xs addBox" data-url="action.php?mod=<?php echo fnEncode(1689) ?>&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($cod_cliente); ?>&pop=true" data-placement='top' data-title='Dependentes'><i class="fal fa-plus f12" aria-hidden="true"></i></a>

												<div class="push10"></div>

												<ul>

													<?php

													$sql = "SELECT CL.*,DA.COD_GRAUESC FROM CLIENTES CL 
															INNER JOIN DADOS_APOIADOR DA ON DA.COD_CLIENTE = CL.COD_CLIENTE
															WHERE CL.COD_EMPRESA = $cod_empresa 
															AND CL.LOG_TITULAR = 'N'
															AND CL.COD_TITULAR = $cod_cliente
															AND CL.COD_TITULAR != 0
															ORDER BY CL.NOM_CLIENTE
															LIMIT 3";

													// fnEscreve($sql);
													$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

													$count = 0;
													while ($qrDepende = mysqli_fetch_assoc($arrayQuery)) {

														switch ($qrDepende['TIP_DEPENDE']) {

															case 1:
																$tip_depende = "Esposo(a)";
																break;

															case 2:
																$tip_depende = "Pai/Mãe";
																break;

															default:
																$tip_depende = "Filho(a)";
																break;
														}
														switch ($qrDepende['COD_GRAUESC']) {
															case 1:
																$grau_esc = "Educação infantil";
																break;
															case 2:
																$grau_esc = "Fundamental";
																break;
															case 3:
																$grau_esc = "Médio";
																break;
															case 4:
																$grau_esc = "Superior (Graduação)";
																break;
															case 5:
																$grau_esc = "Pós-graduação";
																break;
															case 6:
																$grau_esc = "Mestrado";
																break;
															case 7:
																$grau_esc = "Doutorado";
																break;
															case 8:
																$grau_esc = "Escola";
																break;
														}
													?>

														<li class="f12"><?= $tip_depende . " - " . $qrDepende[NOM_CLIENTE] . " (" . $qrDepende[IDADE] . ")" . " - " . $grau_esc ?></li>

													<?php

													}

													?>

												</ul>


												<div class="push20"></div>

											</div>

										<?php
										}
										?>

									</div>

								</fieldset>

							<?php } ?>

							<div class="push10"></div>

							<fieldset>
								<legend>Comunicação</legend>

								<div class="row">

									<div class="col-xs-4">
										<div class="form-group">
											<label for="inputName" class="control-label">e-Mail</label>
											<input type="text" class="form-control input-sm" name="DES_EMAILUS" id="DES_EMAILUS" value="<?php echo $des_emailus; ?>" maxlength="100" value="" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Contato</label>
											<input type="text" class="form-control input-sm" name="DES_CONTATO" value="<?php echo $des_contato; ?>" id="DES_CONTATO" maxlength="20">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Telefone Principal</label>
											<input type="text" class="form-control input-sm fone" name="NUM_TELEFON" value="<?php fnCorrigeTelefone($num_telefon); ?>" id="NUM_TELEFON" maxlength="20">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Telefone Celular</label>
											<input type="text" class="form-control input-sm sp_celphones" name="NUM_CELULAR" value="<?php fnCorrigeTelefone($num_celular); ?>" id="NUM_CELULAR" maxlength="20" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Telefone Comercial</label>
											<input type="text" class="form-control input-sm sp_celphones" name="NUM_COMERCIAL" value="<?php fnCorrigeTelefone($num_comercial); ?>" id="NUM_COMERCIAL" maxlength="20">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="push10"></div>

								<div class="row">

									<div class="col-xs-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Recebe <br />e-Mail</label><br />
											<label class="switch">
												<input type="checkbox" name="LOG_EMAIL" id="LOG_EMAIL" class="switch" value="S" <?php echo $check_mail; ?> />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Recebe <br />SMS</label><br />
											<label class="switch">
												<input type="checkbox" name="LOG_SMS" id="LOG_SMS" class="switch" value="S" <?php echo $check_sms; ?> />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Recebe <br />Telemarketing</label><br />
											<label class="switch">
												<input type="checkbox" name="LOG_TELEMARK" id="LOG_TELEMARK" class="switch" value="S" <?php echo $check_telemark; ?> />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-3">
										<label for="inputName" class="control-label">Tag Persona (online)</label>

										<div class="push10"></div>
										<?php
										/*
														$sqlPersonaCli = "SELECT  A.COD_PERSONA 
														FROM PERSONACLASSIFICA A
														WHERE A.COD_CLIENTE = $cod_cliente ";	
														fnEscreve($sqlPersonaCli);
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlPersonaCli) or die(mysqli_error());	
														$qtdPersonaOn = mysqli_num_rows($arrayQuery);
														$contaLinha = 1;
														while ($qrPersonaCli = mysqli_fetch_assoc($arrayQuery))
														  {
															if ( $contaLinha !=  (int) ($qtdPersonaOn) ){$addOr = " OR ";} else {$addOr = " ";}
															$personaProduto = $personaProduto." FIND_IN_SET('".$qrPersonaCli['COD_PERSONA']."',A.COD_PERSONA_TKT) $addOr ";  
															$contaLinha++;
														  }															
														  fnEscreve($personaProduto);
														  fnEscreve($qtdPersonaOn);
														  */
										?>

										<ul class="tag">

											<?php
											$sql = "SELECT  B.DES_PERSONA, B.COD_PERSONA 
														FROM PERSONACLASSIFICA A, PERSONA B
														WHERE 
														A.COD_PERSONA=B.COD_PERSONA AND
														COD_CLIENTE=$cod_cliente ";
											//fnEscreve($sql);
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
											while ($qrListaTagPersonaOn = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<li class="tag" idp="<?php echo $qrListaTagPersonaOn['COD_PERSONA']; ?>"><span class="label label-success">● &nbsp; <?php echo ucfirst($qrListaTagPersonaOn['DES_PERSONA']); ?></span></li>

											<?php
											}
											?>

										</ul>

									</div>

									<div class="col-xs-3">


										<label for="inputName" class="control-label">Tag Persona (histórico)</label>

										<div class="push10"></div>

										<ul class="tag">
											<?php
											$sql = "SELECT DISTINCT B.DES_PERSONA
														FROM CREDITOSDEBITOS A, PERSONA B
														WHERE 
														A.COD_PERSONA=B.COD_PERSONA AND
														A.COD_CLIENTE=$cod_cliente ";
											//fnEscreve($sql);
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
											while ($qrListaTagPersonaOff = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<li class="tag"><span class="label label-warning">● &nbsp; <?php echo ucfirst($qrListaTagPersonaOff['DES_PERSONA']); ?></span></li>
											<?php
											}
											?>
										</ul>


									</div>

								</div>

							</fieldset>

							<div class="push10"></div>

							<fieldset>
								<legend>Observação</legend>

								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<textarea class="form-control input-sm" rows="4" name="DES_COMENT" id="DES_COMENT"><?= $des_coment ?></textarea>
											<div class="help-block with-errors"></div>
										</div>
									</div>
								</div>

							</fieldset>

							<div class="push10"></div>

							<?php

							$sql = "SELECT COD_TPFILTRO, DES_TPFILTRO, LOG_REQUIRED FROM TIPO_FILTRO
											WHERE COD_EMPRESA = $cod_empresa
											ORDER BY NUM_ORDENAC";
							$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));
							//fnEscreve($sql);
							if (mysqli_num_rows($arrayQuery) > 0) {
								$countFiltros = 0;

							?>
								<style>
									@import url("css/fa5all.css");
								</style>
								<fieldset>
									<legend>Filtros</legend>

									<div class="row">

										<?php
										while ($qrTipo = mysqli_fetch_assoc($arrayQuery)) {

											if ($qrTipo['LOG_REQUIRED'] == "S") {
												$obriga = "required";
												$obrigaChosen = "requiredChk";
											} else {
												$obriga = "";
												$obrigaChosen = "";
											}

										?>

											<style type="text/css">
												#COD_FILTRO_<?= $qrTipo["COD_TPFILTRO"] ?>_chosen .chosen-drop .chosen-results li:last-child {
													font-weight: bolder;
													font-size: 11px;
													color: #000;
												}

												#COD_FILTRO_<?= $qrTipo["COD_TPFILTRO"] ?>_chosen .chosen-drop .chosen-results li:last-child:before {
													content: '\002795';
													font-weight: bolder;
													font-size: 9px;
												}
											</style>

											<div class="col-xs-3">
												<div class="form-group">
													<label for="inputName" class="control-label <?= $obriga ?>"><?= $qrTipo['DES_TPFILTRO'] ?></label>
													<div id="relatorioFiltro_<?= $countFiltros ?>">
														<input type="hidden" name="COD_TPFILTRO_<?= $countFiltros ?>" id="COD_TPFILTRO_<?= $countFiltros ?>" value="<?= $qrTipo['COD_TPFILTRO'] ?>">
														<select data-placeholder="Selecione o filtro" name="COD_FILTRO_<?= $countFiltros ?>" id="COD_FILTRO_<?= $qrTipo[COD_TPFILTRO] ?>" class="chosen-select-deselect last-chosen-link <?= $obrigaChosen ?>" <?= $obriga ?>>
															<option value=""></option>
															<?php
															$sqlFiltro = "SELECT COD_FILTRO, DES_FILTRO FROM FILTROS_CLIENTE
																					  WHERE COD_TPFILTRO = " . $qrTipo['COD_TPFILTRO'];

															$arrayFiltros = mysqli_query(connTemp($cod_empresa, ''), trim($sqlFiltro));
															while ($qrFiltros = mysqli_fetch_assoc($arrayFiltros)) {
															?>

																<option value="<?= $qrFiltros['COD_FILTRO'] ?>"><?= $qrFiltros['DES_FILTRO'] ?></option>

																<?php
															}

															if ($cod_usuario != "" && $cod_usuario != 0) {
																$sqlChosen = "SELECT COD_FILTRO FROM CLIENTE_FILTROS
																								WHERE COD_CLIENTE = $cod_usuario AND COD_TPFILTRO =" . $qrTipo['COD_TPFILTRO'];
																$arrayChosen = mysqli_query(connTemp($cod_empresa, ''), $sqlChosen);
																if (mysqli_num_rows($arrayChosen) > 0) {
																	$qrChosen = mysqli_fetch_assoc($arrayChosen);
																?>
																	<script>
																		$('#COD_FILTRO_<?= $qrTipo[COD_TPFILTRO] ?>').val(<?= $qrChosen['COD_FILTRO'] ?>).trigger('chosen:updated');
																	</script>
															<?php
																	// VERIFICANDO SE FILTRO É GRUPO DE TRABALHO
																	if ($qrTipo[COD_TPFILTRO] == 28) {
																		// CAPTURANDO O GRUPO DE TRABALHO CADASTRADO DO CLIENTE PARA EXIBIR OU NÃO A COMBO DE ENTIDADE
																		$grupoTrabalho = $qrChosen['COD_FILTRO'];
																	}
																}
															}
															?>
															<option value="add">&nbsp;ADICIONAR NOVO</option>
														</select>
														<script type="text/javascript">
															$('#COD_FILTRO_<?= $qrTipo[COD_TPFILTRO] ?>').change(function() {
																valor = $(this).val();
																if (valor == "add") {
																	$(this).val('').trigger("chosen:updated");
																	$('#btnCad_<?= $countFiltros ?>').click();
																}
																// VERIFICANDO SE FILTRO É GRUPO DE TRABALHO
																if ('<?= $qrTipo[COD_TPFILTRO] ?>' == 28) {
																	//CARREGANDO ENTIDADES NA COMBO
																	$.ajax({
																		method: 'POST',
																		url: 'ajxEntidadeCliente.do?id=<?= fnEncode($cod_empresa) ?>&idg=' + $('#COD_FILTRO_<?= $qrTipo[COD_TPFILTRO] ?>').val(),
																		beforeSend: function() {
																			$('#grupoEntidade').html('<div class="loading" style="width: 100%;"></div>');
																		},
																		success: function(data) {
																			$('#grupoEntidade').html(data);
																		}
																	});
																}
															});
														</script>
														<div class="help-block with-errors"></div>
													</div>
												</div>
											</div>
											<a type="hidden" name="btnCad_<?= $countFiltros ?>" id="btnCad_<?= $countFiltros ?>" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1398) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idF=<?= fnEncode($qrTipo[COD_TPFILTRO]) ?>&idS=<?= fnEncode($countFiltros) ?>&pop=true" data-title="Cadastrar Filtro - <?= $qrTipo[DES_TPFILTRO] ?>"></a>

										<?php
											$countFiltros++;
										}
										?>

										<div class="col-xs-6">
											<div class="form-group">
												<label for="inputName" class="control-label">Comentário</label>
												<input type="text" class="form-control input-sm" name="DES_AUXFILTRO" id="DES_AUXFILTRO" value="<?php echo $des_auxfiltro; ?>" maxlength="200">
												<div class="help-block with-errors"></div>
											</div>
										</div>

									</div>
									<div class="row" id="grupoEntidade">

										<?php
										if ($grupoTrabalho != "") {
											// fnEscreve($grupoTrabalho);
										?>


											<div class="col-md-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Igreja</label>
													<select data-placeholder="Selecione a igreja" name="COD_GRUPOENT" id="COD_GRUPOENT" class="chosen-select-deselect">
														<option value=""></option>
														<?php
														$sql = "SELECT COD_GRUPOENT,DES_GRUPOENT FROM Entidade_Grupo WHERE COD_EMPRESA = $cod_empresa AND COD_REGITRA = $grupoTrabalho";
														$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

														$grupos = "";

														while ($qrListaGrupoEntidade = mysqli_fetch_assoc($arrayQuery)) {
															$grupos .= "$qrListaGrupoEntidade[COD_GRUPOENT],";
														}
														$grupos = rtrim($grupos, ',');

														// fnEscreve($sql2);
														$sql2 = "SELECT COD_ENTIDAD,NOM_ENTIDAD FROM ENTIDADE WHERE COD_EMPRESA = $cod_empresa AND COD_GRUPOENT IN($grupos) ORDER BY NOM_ENTIDAD";
														$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql2);

														// fnEscreve($sql2);
														while ($qrListaGrupoEntidade2 = mysqli_fetch_assoc($arrayQuery2)) {
															echo "
																					  <option value='" . $qrListaGrupoEntidade2['COD_ENTIDAD'] . "'>" . $qrListaGrupoEntidade2['NOM_ENTIDAD'] . "</option> 
																					";
														}
														?>
													</select>
													<script>
														$("#formulario #COD_GRUPOENT").val("<?php echo $cod_grupoent; ?>").trigger("chosen:updated");
													</script>
													<div class="help-block with-errors"></div>
												</div>
											</div>


										<?php
										} else {
										?>
											<input type="hidden" name="COD_GRUPOENT" id="COD_GRUPOENT" value="">
										<?php
										}
										?>

									</div>
								</fieldset>

								<div class="push10"></div>

							<?php
							}
							?>
							<div style="display:<?= $acesso ?>">
								<fieldset>
									<legend>Dados Eleitorais</legend>

									<div class="row">

										<div class="col-xs-3">
											<div class="form-group">
												<label for="inputName" class="control-label">Zona Eleitoral</label>
												<input type="text" class="form-control input-sm" name="DES_ZONA" id="DES_ZONA" value="<?php echo $des_zona; ?>" maxlength="40">
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-xs-3">
											<div class="form-group">
												<label for="inputName" class="control-label">Região Administrativa</label>
												<input type="text" class="form-control input-sm" name="DES_REGADM" id="DES_REGADM" value="<?php echo $des_regadm; ?>" maxlength="40">
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-xs-3">
											<div class="form-group">
												<label for="inputName" class="control-label">Prefeitura</label>
												<input type="text" class="form-control input-sm" name="DES_PREF" id="DES_PREF" value="<?php echo $des_pref; ?>" maxlength="40">
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-xs-3">
											<div class="form-group">
												<label for="inputName" class="control-label">Subprefeitura</label>
												<input type="text" class="form-control input-sm" name="DES_SUBPREF" id="DES_SUBPREF" value="<?php echo $des_subpref; ?>" maxlength="40">
												<div class="help-block with-errors"></div>
											</div>
										</div>

									</div>

								</fieldset>
							</div>

							<div class="push10"></div>

							<fieldset>
								<legend>Localização</legend>

								<div class="row">

									<div class="col-xs-1">

										<div class="push15"></div>
										<a href="javascript:void(0)" class="btn btn-info btn-block btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1444) ?>&id=<?php echo fnEncode($cod_empresa); ?>&pop=true" data-title="Busca CEP/Logradouro" data-toggle='tooltip' data-placement='top' data-original-title='Busca CEP/Logradouro'><i class="fal fa-map-marked-alt f16" aria-hidden="true"></i></a>

									</div>

									<div class="col-xs-4">
										<div class="form-group">
											<label for="inputName" class="control-label <?= $endObriga ?>">Endereço</label>
											<input type="text" class="form-control input-sm" name="DES_ENDEREC" value="<?php echo $des_enderec; ?>" id="DES_ENDEREC" maxlength="40" <?= $endObriga ?>>
											<div class="help-block with-errors"></div>
											<!-- <label>Rua:
        													<input name="rua" type="text" id="rua" size="60" /></label><br /> -->
											<!-- <label>Endereço:
        													<input name="DES_ENDEREC" type="text" id="DES_ENDEREC" size="40" /></label><br /> -->

										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label <?= $endObriga ?>">Número</label>
											<input type="text" class="form-control input-sm" name="NUM_ENDEREC" value="<?php echo $num_enderec; ?>" id="NUM_ENDEREC" maxlength="10" <?= $endObriga ?>>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Complemento</label>
											<input type="text" class="form-control input-sm" name="DES_COMPLEM" value="<?php echo $des_complem; ?>" id="DES_COMPLEM" maxlength="100">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Bairro</label>
											<input type="text" class="form-control input-sm" name="DES_BAIRROC" value="<?php echo $des_bairroc; ?>" id="DES_BAIRROC" maxlength="60">
											<div class="help-block with-errors"></div>
											<!-- <label>Bairro:
        													<input name="bairro" type="text" id="bairro" size="40" /></label><br /> -->
											<!-- <label>Bairro:
        													<input name="DES_BAIRROC" type="text" id="DES_BAIRROC" size="20" /></label><br /> -->
										</div>
									</div>

									<div class="push10"></div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label <?= $endObriga ?>">CEP</label>
											<input type="text" class="form-control input-sm cep" name="NUM_CEPOZOF" value="<?php echo $num_cepozof; ?>" id="NUM_CEPOZOF" maxlength="9" <?= $endObriga ?>>
											<div class="help-block with-errors"></div>
											<!-- <label>Cep:
        													<input name="cep" type="text" id="cep" value="" size="10" maxlength="9" /></label><br /> -->
											<!-- <label>Cep:
        													<input name="NUM_CEPOZOF" type="text" id="NUM_CEPOZOF" value="<?php echo $num_cepozof; ?>" size="10" maxlength="9" /></label><br /> -->
										</div>
									</div>

									<!-- <div class="col-xs-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Cidade</label>
                                                            <input type="text" class="form-control input-sm" name="NOM_CIDADEC" value="<?php echo $nom_cidadec; ?>" id="NOM_CIDADEC" maxlength="40" required>
															<div class="help-block with-errors"></div>
														</div>
													</div> -->

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label <?= $endObriga ?>">Estado</label>
											<select data-placeholder="Selecione um estado" name="COD_ESTADO" id="COD_ESTADO" class="chosen-select-deselect" <?= $endObriga ?>>
												<option value=""></option>
												<?php

												$sql = "SELECT COD_ESTADO, UF FROM ESTADO ORDER BY UF";
												$arrayEstado = mysqli_query(connTemp($cod_empresa, ''), $sql);
												while ($qrEstado = mysqli_fetch_assoc($arrayEstado)) {
												?>
													<option value="<?= $qrEstado['COD_ESTADO'] ?>"><?= $qrEstado['UF'] ?></option>
												<?php
												}

												?>
											</select>
											<script>
												$("#formulario #COD_ESTADO").val("<?php echo $cod_estado; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2" id="relatorioCidade">
										<div class="form-group">
											<label for="inputName" class="control-label <?= $endObriga ?>">Cidade</label>
											<select data-placeholder="Selecione um estado" name="COD_MUNICIPIO" id="COD_MUNICIPIO" class="chosen-select-deselect" <?= $endObriga ?>>
												<option value=""></option>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Latitude</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="LATITUDE" id="LATITUDE" value="<?= $latitude ?>">
										</div>
									</div>

									<div class="col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Longitude</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="LONGITUDE" id="LONGITUDE" value="<?= $longitude ?>">
										</div>
									</div>


								</div>

							</fieldset>

							<div class="push10"></div>

							<fieldset>
								<legend>Controle de Acesso</legend>

								<div class="row">

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Data de Cadastro</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" value="<?php echo $dat_cadastr; ?>" name="DAT_CADASTR" id="DAT_CADASTR">
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Colaborador que Cadastrou</label>
											<?php

											$sql = "SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $cod_usucada";
											$qrUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sql));

											?>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" value="<?= $qrUsu['NOM_USUARIO'] ?>" name="USUCADA" id="USUCADA">
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Código Externo</label>
											<input type="text" class="form-control input-sm" name="COD_EXTERNO" value="<?php echo $cod_externo; ?>" id="COD_EXTERNO" maxlength="20">
										</div>
									</div>

									<div class="col-xs-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Chave Externa</label>
											<input type="text" class="form-control input-sm" name="KEY_EXTERNO" value="<?php echo $key_externo; ?>" id="KEY_EXTERNO" maxlength="20">
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Login Apoiador</label>
											<input type="text" class="form-control input-sm" name="LOG_USUARIO" id="LOG_USUARIO" value="<?php echo $log_usuario; ?>" maxlength="50" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2 text-center">
										<div class="form-group">
											<label>&nbsp;</label>
											<div class="push"></div>
											<a href="javascript:void(0)" class="btn btn-default addBox form-control" data-url="action.php?mod=<?php echo fnEncode(1512) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_cliente) ?>&pop=true" data-title="Alterar Senha" style="height: 35px;padding-top: 5px;"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp; Senha</a>
										</div>
									</div>

									<!-- <div class="col-xs-1">
											<div class="form-group">
												<label for="inputName" class="control-label">N° Acessos</label>
												<input type="text" class="form-control input-sm" name="NUM_TENTATI" id="NUM_TENTATI" value="<?php echo $num_tentati; ?>" maxlength="2" value="">
												<div class="help-block with-errors"></div>
											</div>
										</div> -->

									<div class="push10"></div>

									<div class="col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Unidade de Atendimento </label>
											<select data-placeholder="Selecione a unidade de atendimento" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect" required>
												<option value=""></option>
												<?php
												$sql = "select COD_UNIVEND, NOM_FANTASI from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' order by NOM_UNIVEND ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

												while ($qrListaUnidade = mysqli_fetch_assoc($arrayQuery)) {
													echo "
																<option value='" . $qrListaUnidade['COD_UNIVEND'] . "'>" . $qrListaUnidade['NOM_FANTASI'] . "</option> 
															";
												}
												?>
											</select>
											<script>
												$("#formulario #COD_UNIVEND").val("<?php echo $cod_univend; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<!-- <div class="col-xs-9">
											<div class="form-group">
												<label for="inputName" class="control-label">Acesso Múltiplo </label>
												<select data-placeholder="Selecione as unidades autorizadas" name="COD_MULTEMP[]" id="COD_MULTEMP" multiple="multiple" class="chosen-select-deselect">
													<?php
													$sql = "SELECT COD_UNIVEND, NOM_FANTASI from unidadevenda where COD_EMPRESA = $cod_empresa order by NOM_UNIVEND ";
													$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

													while ($qrListaUnidade = mysqli_fetch_assoc($arrayQuery)) {
														echo "
																			  <option value='" . $qrListaUnidade['COD_UNIVEND'] . "'>" . $qrListaUnidade['NOM_FANTASI'] . "</option> 
																			";
													}
													?>
												</select>

												<div class="help-block with-errors"></div>
											</div>
										</div> -->

								</div>

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<?php
								//botoes normais 
								if ($popUp != "true") {
								?>
									<a href="#" class="btn btn-info addBox pull-left" id="print" data-url="action.php?mod=<?php echo fnEncode(1425) ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($cod_cliente); ?>&pop=true" data-title="Impressão de Cadastro"><i class="fal fa-print" aria-hidden="true"></i>&nbsp; Impressão de Cadastro </a>

									<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Excluir</button>
									<?php if ($cod_cliente == 0) { ?>
										<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
									<?php } else { ?>
										<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
									<?php } ?>

								<?php } else { ?>

									<a href="javascript:window.print();" class="btn btn-info"><i class="fal fa-print" aria-hidden="true"></i>&nbsp; Impressão de Cadastro </a>

								<?php } ?>

							</div>

							<input type="hidden" name="COD_ESTADOF" id="COD_ESTADOF" value="<?= $cod_estadof ?>">
							<input type="hidden" name="NOM_CIDADEC" id="NOM_CIDADEC" value="<?= $nom_cidadec ?>">
							<input type="hidden" name="COD_MUNICIPIO_AUX" id="COD_MUNICIPIO_AUX" value="">
							<input type="hidden" name="LOG_FOTO" id="LOG_FOTO" value="N">
							<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
							<input type="hidden" name="REFRESH_FILTRO" id="REFRESH_FILTRO" value="N">
							<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
							<input type="hidden" name="COD_TPFILTRO" id="COD_TPFILTRO" value="">
							<input type="hidden" name="idS" id="idS" value="">
							<input type="hidden" name="LOG_PROFISS" id="LOG_PROFISS" value="N">
							<input type="hidden" name="LOG_BENEFICIO" id="LOG_BENEFICIO" value="N">

							<input type="hidden" name="COUNT_FILTROS" id="COUNT_FILTROS" value="<?= $countFiltros ?>">
							<!-- <input type="hidden" name="TIP_CLIENTE" id="TIP_CLIENTE" value="<?php echo $tip_cliente; ?>"> -->
							<input type="hidden" name="COD_CHAVECO" id="COD_CHAVECO" value="<?php echo $cod_chaveco; ?>">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							<div class="push5"></div>

						</form>

						<div class="push50"></div>

						<div class="push"></div>

					</div>

				</div>
				</div>
				<!-- fim Portlet -->
			</div>

	</div>

	<!-- modal -->
	<div class="modal fade" id="popModal" tabindex='-1'>
		<div class="modal-dialog" style="">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="push20"></div>


	<script type="text/javascript">
		$(document).ready(function() {

			$(".cpfcnpj").keyup(function() {
				var num_cgcecpf = $("#NUM_CGCECPF").val();
				if (num_cgcecpf.length == 18) {
					$("#DAT_NASCIME,#COD_SEXOPES,#DES_IGREJA,#DES_LOCAL").prop('required', false);
					$(".lbl_req").removeClass("required");
				} else {
					$("#DAT_NASCIME,#COD_SEXOPES,#DES_IGREJA,#DES_LOCAL").prop('required', true);
					$(".lbl_req").addClass("required");
				}
			});

			$(".toggle-password").click(function() {

				$(this).toggleClass("fa-eye fa-eye-slash");
				var input = $($(this).attr("toggle"));
				if (input.attr("type") == "password") {
					input.attr("type", "text");
				} else {
					input.attr("type", "password");
				}
			});

			carregaComboCidades('<?= $cod_estado ?>');

			$("#formulario #COD_ESTADOF").val($("#COD_ESTADO option:selected").text());

			$(".addBox").click(function() {
				if ($(this).attr("id") == "btn-foto") {
					$('#popModal').find('.modal-content').css({
						'width': '1000px',
						'height': '650px',
						'marginLeft': 'auto',
						'marginRight': 'auto'
					});
					$('#popModal').find('.modal-dialog').css({
						'maxWidth': '1080px'
					});
				} else if ($(this).attr("id") == "print") {
					$('#popModal').find('.modal-content').css({
						'width': '70vw',
						'height': 'auto',
						'marginLeft': 'auto',
						'marginRight': 'auto'
					});
					$('#popModal').find('.modal-dialog').css({
						'maxWidth': '100vw'
					});
				} else {
					$('#popModal').find('.modal-content').css({
						'width': 'auto',
						'height': 'auto'
					});
					$('#popModal').find('.modal-dialog').css({
						'maxWidth': '1080px'
					});
				}
			});

			var SPMaskBehavior = function(val) {
					return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
				},
				spOptions = {
					onKeyPress: function(val, e, field, options) {
						field.mask(SPMaskBehavior.apply({}, arguments), options);
					}
				};

			$('.sp_celphones').mask(SPMaskBehavior, spOptions);

			//mascaraCpfCnpj($("#formulario #NUM_CGCECPF"));
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			//modal close
			$('.modal').on('hidden.bs.modal', function() {

				if ($('#REFRESH_CLIENTE').val() == "S") {
					var newCli = $('#NOVO_CLIENTE').val();
					window.location.href = "action.php?mod=<?php echo $_GET['mod']; ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=" + newCli + " ";
					$('#REFRESH_PRODUTOS').val("N");
				}

				if ($('#LOG_FOTO').val() == "S") {

					$.ajax({
						method: 'POST',
						url: 'ajxFotoApoiador.php?opcao=carregar',
						data: {
							COD_EMPRESA: <?= $cod_empresa ?>,
							COD_CLIENTE: <?= $cod_cliente ?>
						},
						beforeSend: function() {
							$('#div_perfil').html('<div class="loading" style="width: 100%;"></div>');
						},
						success: function(data) {
							$('#div_perfil').html(data);
						}
					});

				}

				if ($('#REFRESH_FILTRO').val() == "S") {

					$.ajax({
						method: 'POST',
						url: 'ajxTipoFiltro.php?idS=' + $('#idS').val(),
						data: {
							COD_EMPRESA: <?= $cod_empresa ?>,
							COD_TPFILTRO: $('#COD_TPFILTRO').val()
						},
						beforeSend: function() {
							$('#relatorioFiltro_' + $('#idS').val()).html('<div class="loading" style="width: 100%;"></div>');
						},
						success: function(data) {
							// console.log(data);
							$('#relatorioFiltro_' + $('#idS').val()).html(data);
							$('#REFRESH_FILTRO').val("N");
						}
					});

				}
				if ($("#LOG_PROFISS").val() == "S") {
					$.ajax({
						method: "POST",
						url: "ajxCarregaComboProfiss.php?id=<?= fnEncode($cod_empresa) ?>",
						data: {
							COD_EMPRESA: <?= $cod_empresa ?>
						},
						beforeSend: function() {
							$('#relatorioProfiss').html('<div class="loading" style="width: 100%;"></div>');
						},
						success: function(data) {
							console.log(data);
							$('#relatorioProfiss').html(data);
							$('#LOG_PROFISS').val("N");
						}
					})
				}
				if ($("#LOG_BENEFICIO").val() == "S") {
					$.ajax({
						method: "POST",
						url: "ajxCarregaComboBeneficio.php?id=<?= fnEncode($cod_empresa) ?>",
						data: {
							COD_EMPRESA: "<?= $cod_empresa ?>"
						},
						beforeSend: function() {
							$('#relatorioBeneficio').html('<div class="loading" style="width: 100%;"></div>');
						},
						success: function(data) {
							console.log(data);
							$('#relatorioBeneficio').html(data);
							$('#LOG_BENEFICIO').val("N");
						}
					})
				}

			});

			$("#COD_ESTADO").change(function() {
				cod_estado = $(this).val();
				carregaComboCidades(cod_estado);
				estado = $("#COD_ESTADO option:selected").text();
				$('#COD_ESTADOF').val(estado);
				$('#NOM_CIDADEC').val('');
			});


			$('#iNone3').on('click', function(e) {
				e.preventDefault();
				$("#COD_BENEFICIOS option:selected").removeAttr("selected").trigger('chosen:updated');
			});

			$("#LOG_RECEBE").change(function() {

				if (!$(this).prop("checked")) {

					$("#COD_BENEFICIOS option:selected").removeAttr("selected").trigger('chosen:updated');
					$('#COD_BENEFICIOS').attr("disabled", true);
				} else {
					$('#COD_BENEFICIOS').attr("disabled", false);
				}
				$("#COD_BENEFICIOS").chosen("destroy").chosen({
					allow_single_deselect: true
				});
				$('#formulario').validator('validate');
			});

		});

		//retorno combo multiplo - master
		$("#formulario #COD_MULTEMP").val('').trigger("chosen:updated");
		var sistemasMst = "<?= $cod_multemp; ?>";
		var sistemasMstArr = sistemasMst.split(',');
		//opções multiplas
		for (var i = 0; i < sistemasMstArr.length; i++) {
			$("#formulario #COD_MULTEMP option[value=" + sistemasMstArr[i] + "]").prop("selected", "true");
		}
		$("#formulario #COD_MULTEMP").trigger("chosen:updated");

		$("#formulario #COD_BENEFICIOS").val('').trigger("chosen:updated");
		var sistemasMst = "<?= $cod_benefic; ?>";
		var sistemasMstArr = sistemasMst.split(',');
		//opções multiplas
		for (var i = 0; i < sistemasMstArr.length; i++) {
			$("#formulario #COD_BENEFICIOS option[value=" + sistemasMstArr[i] + "]").prop("selected", "true");
		}
		$("#formulario #COD_BENEFICIOS").trigger("chosen:updated");


		function uploadFile(idField, typeFile) {
			var formData = new FormData();
			var nomeArquivo = $('#' + idField)[0].files[0]['name'];

			formData.append('arquivo', $('#' + idField)[0].files[0]);
			formData.append('diretorio', '../media/clientes/');
			formData.append('id', <?php echo $cod_empresa ?>);
			formData.append('typeFile', typeFile);

			$('.progress').show();
			$.ajax({
				xhr: function() {
					var xhr = new window.XMLHttpRequest();
					$('#btnUploadFile').addClass('disabled');
					xhr.upload.addEventListener("progress", function(evt) {
						if (evt.lengthComputable) {
							var percentComplete = evt.loaded / evt.total;
							percentComplete = parseInt(percentComplete * 100);
							if (percentComplete !== 100) {
								$('.progress-bar').css('width', percentComplete + "%");
								$('.progress-bar > span').html(percentComplete + "%");
							}
						}
					}, false);
					return xhr;
				},
				url: '../uploads/uploaddoc.php',
				type: 'POST',
				data: formData,
				processData: false, // tell jQuery not to process the data
				contentType: false, // tell jQuery not to set contentType
				success: function(data) {
					$('.jconfirm-open').fadeOut(300, function() {
						$(this).remove();
					});
					if (!data.trim()) {
						$('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);
						$.alert({
							title: "Mensagem",
							content: "Upload feito com sucesso",
							type: 'green'
						});

					} else {
						$.alert({
							title: "Erro ao efetuar o upload",
							content: data,
							type: 'red'
						});
					}
				}
			});
		}


		function carregaComboCidades(cod_estado) {
			$.ajax({
				method: 'POST',
				url: 'ajxComboMunicipio.php?id=<?= fnEncode($cod_empresa) ?>',
				data: {
					COD_ESTADO: cod_estado
				},
				beforeSend: function() {
					$('#relatorioCidade').html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					$("#relatorioCidade").html(data);
					if ($("#formulario #COD_MUNICIPIO_AUX").val() != '') {
						$("#formulario #COD_MUNICIPIO").val($("#COD_MUNICIPIO_AUX").val()).trigger("chosen:updated");
					} else {
						$("#formulario #COD_MUNICIPIO").val("<?php echo $cod_municipio; ?>").trigger("chosen:updated");
					}
					$("#formulario #NOM_CIDADEC").val($("#COD_MUNICIPIO option:selected").text());
					// $('#formulario').validator('validate');
				}
			});
		}

		$(function() {
			$('html, body').animate({
				scrollTop: 0
			}, 500);
			$("#NOM_USUARIO").focus();
		});
	</script>