<?php


include '../_system/_functionsMain.php';

$opcao = fnLimpaCampo($_GET['opcao']);
$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
$mostraSenha = fnLimpaCampoZero(fnDecode($_GET['logS']));
$codunivend_campanha = fnLimpaCampoZero($_GET['campanha']);
$isapp = $_REQUEST['ISAPP'];

if ($isapp == "") {
	$isapp = false;
}

// echo "$opcao";

switch ($opcao) {

	case 'TKNALT':

		include_once '../totem/funWS/GeraToken.php';
		include_once '../totem/funWS/buscaConsumidor.php';
		include_once '../totem/funWS/buscaConsumidorCNPJ.php';

		$sql = "SELECT COD_EMPRESA, NOM_FANTASI, QTD_CHARTKN, TIP_TOKEN, TIP_RETORNO, NUM_DECIMAIS_B  FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

		if (isset($arrayQuery)) {
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
			$qtd_chartkn = $qrBuscaEmpresa['QTD_CHARTKN'];
			$tip_token = $qrBuscaEmpresa['TIP_TOKEN'];


			if ($qrBuscaEmpresa['TIP_RETORNO'] == 1) {
				$casasDec = 0;
			} else {
				$casasDec = $qrBuscaEmpresa['NUM_DECIMAIS_B'];
				$pref = "R$ ";
			}

			// echo($casasDec);
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

		$urltotem = $log_usuario . ';'
			. $des_senhaus . ';'
			. $idlojaKey . ';'
			. $idmaquinaKey . ';'
			. $cod_empresa . ';'
			. $codvendedorKey . ';'
			. $nomevendedorKey;

		$arrayCampos = explode(";", $urltotem);

		$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CELULAR']));
		$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CGCECPF']));
		$nom_cliente = fnLimpaCampo($_POST['NOM_CLIENTE']);

		if ($num_celular == "") {
			if ($isapp) {
				$num_celular = fnLimpaCampo(fnLimpaDoc(fnDecode($_POST['KEY_NUM_CELULAR'])));
			} else {
				$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['KEY_NUM_CELULAR']));
			}
		}

		if ($num_cgcecpf == "") {
			if ($isapp) {
				$num_cgcecpf = fnLimpaCampo(fnLimpaDoc(fnDecode($_POST['KEY_NUM_CGCECPF'])));
				$k_num_cgcecpf = fnLimpaCampo(fnLimpaDoc(fnDecode($_POST['KEY_NUM_CGCECPF'])));
			} else {
				$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['KEY_NUM_CGCECPF']));
				$k_num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['KEY_NUM_CGCECPF']));
			}
			if (strlen($k_num_cgcecpf) <= '11') {

				// echo '<pre>';

				$buscaconsumidor = fnconsulta(fnCompletaDoc($k_num_cgcecpf, 'F'), $arrayCampos);

				// print_r($buscaconsumidor);

				// echo '</pre>';

			} else {

				// echo 'else';

				$buscaconsumidor = fnconsultacnpf(fnCompletaDoc($k_num_cgcecpf, 'J'), $arrayCampos);
			}
		}

		if ($num_cgcecpf == "00000000000" || trim($num_cgcecpf) == "") {
			$num_cgcecpf = $num_celular;
		}

		$dadosenvio = array(
			'tipoGeracao' => '1',
			'nome' => "$nom_cliente",
			'cpf' => "$num_cgcecpf",
			'celular' => "$num_celular",
			'email' => ''
		);

		$retornoEnvio = GeraToken($dadosenvio, $arrayCampos);

		// if($isapp){

		// 	echo '<pre>';
		// 	print_r($dadosenvio);
		// 	print_r($retornoEnvio);
		// 	echo '</pre>';
		// 	exit();

		// }

		$cod_envio = $retornoEnvio["body"]["envelope"]["body"]["geratokenresponse"]["retornatoken"]["coderro"];

?>

		<style>
			.p-r-0 {
				padding-right: 0;
			}

			.p-l-0 {
				padding-left: 0;
			}

			.img-g {
				display: none;
			}

			.img-m {
				display: block;
			}

			.img-p {
				display: none;
			}

			@media only screen and (min-device-width: 320px) and (max-device-width: 480px) {

				.img-g {
					display: none;
				}

				.img-m {
					display: none;
				}

				.img-p {
					display: block;
				}

				#roteiro {
					display: none;
				}

				.p-r-0 {
					padding-right: 15px;
					padding-left: 15px;
					margin-bottom: 10px;
				}

				.p-l-0 {
					padding-left: 15px;
					padding-right: 15px;
				}

				.p-0 {
					padding: 0;
				}

				.nav-tabs li {
					width: 100%;
				}

				.nav-tabs li:last-child {
					margin-bottom: 5px;
				}


			}

			/* (320x480) Smartphone, Portrait */
			@media only screen and (device-width: 320px) and (orientation: portrait) {

				.img-g {
					display: none;
				}

				.img-m {
					display: none;
				}

				.img-p {
					display: block;
				}

				#roteiro {
					display: none;
				}

				.p-r-0 {
					padding-right: 15px;
					padding-left: 15px;
					margin-bottom: 10px;
				}

				.p-l-0 {
					padding-left: 15px;
					padding-right: 15px;
				}

				.p-0 {
					padding: 0;
				}

				.nav-tabs li {
					width: 100%;
				}

				.nav-tabs li:last-child {
					margin-bottom: 5px;
				}

			}

			/* (320x480) Smartphone, Landscape */
			@media only screen and (device-width: 480px) and (orientation: landscape) {

				.img-g {
					display: none;
				}

				.img-m {
					display: none;
				}

				.img-p {
					display: block;
				}

				#roteiro {
					display: none;
				}

				.p-r-0 {
					padding-right: 15px;
					padding-left: 15px;
					margin-bottom: 10px;
				}

				.p-l-0 {
					padding-left: 15px;
					padding-right: 15px;
				}

				.p-0 {
					padding: 0;
				}

				.nav-tabs li {
					width: 100%;
				}

				.nav-tabs li:last-child {
					margin-bottom: 5px;
				}

			}

			/* (1024x768) iPad 1 & 2, Landscape */
			@media only screen and (min-device-width: 768px) and (max-device-width: 1367px) and (orientation: landscape) {

				.img-g {
					display: none;
				}

				.img-m {
					display: none;
				}

				.img-p {
					display: block;
				}

				#roteiro {
					display: none;
				}

				.p-r-0 {
					padding-right: 15px;
					/* 15px */
				}

				.p-l-0 {
					padding-left: 0;
					/* 15p15px */
				}

				/* (1280x800) Tablets, Portrait */
				@media only screen and (max-width: 800px) and (orientation : portrait) {

					.img-g {
						display: none;
					}

					.img-m {
						display: none;
					}

					.img-p {
						display: block;
					}

					#roteiro {
						display: none;
					}

					.p-r-0 {
						padding-right: 15px;
						padding-left: 15px;
						margin-bottom: 10px;
					}

					.p-l-0 {
						padding-left: 15px;
						padding-right: 15px;
					}

					.p-0 {
						padding: 0;
					}

					.nav-tabs li {
						width: 100%;
					}

					.nav-tabs li:last-child {
						margin-bottom: 5px;
					}

				}

				/* (768x1024) iPad 1 & 2, Portrait */
				@media only screen and (max-width: 768px) and (orientation : portrait) {

					.img-g {
						display: none;
					}

					.img-m {
						display: none;
					}

					.img-p {
						display: block;
					}

					#roteiro {
						display: none;
					}

					.p-r-0 {
						padding-right: 15px;
						padding-left: 15px;
						margin-bottom: 10px;
					}

					.p-l-0 {
						padding-left: 15px;
						padding-right: 15px;
					}

					.p-0 {
						padding: 0;
					}

					.nav-tabs li {
						width: 100%;
					}

					.nav-tabs li:last-child {
						margin-bottom: 5px;
					}

				}

				/* (2048x1536) iPad 3 and Desktops*/
				@media only screen and (min-device-width: 1536px) and (max-device-width: 2048px) {

					.img-g {
						display: block;
					}

					.img-m {
						display: none;
					}

					.img-p {
						display: none;
					}

					.p-r-0 {
						padding-right: 0;
					}

					.p-l-0 {
						padding-left: 0;
					}

				}

				@media only screen and (min-device-width: 1100px) and (orientation : portrait) {

					.img-g {
						display: none;
					}

					.img-m {
						display: none;
					}

					.img-p {
						display: block;
					}

					#roteiro {
						display: none;
					}

					.p-r-0 {
						padding-right: 15px;
						padding-left: 15px;
						margin-bottom: 10px;
					}

					.p-l-0 {
						padding-left: 15px;
						padding-right: 15px;
					}

					.p-0 {
						padding: 0;
					}

					.nav-tabs li {
						width: 100%;
					}

					.nav-tabs li:last-child {
						margin-bottom: 5px;
					}

				}

				@media (max-height: 824px) and (max-width: 416px) {

					.img-g {
						display: none;
					}

					.img-m {
						display: none;
					}

					.img-p {
						display: block;
					}

					#roteiro {
						display: none;
					}

					.p-r-0 {
						padding-right: 15px;
						padding-left: 15px;
						margin-bottom: 10px;
					}

					.p-l-0 {
						padding-left: 15px;
						padding-right: 15px;
					}

					.p-0 {
						padding: 0;
					}

					.nav-tabs li {
						width: 100%;
					}

					.nav-tabs li:last-child {
						margin-bottom: 5px;
					}

				}

				/* (320x480) iPhone (Original, 3G, 3GS) */
				@media (max-device-width: 737px) and (max-height: 416px) {

					.img-g {
						display: none;
					}

					.img-m {
						display: none;
					}

					.img-p {
						display: block;
					}

					#roteiro {
						display: none;
					}

					.p-r-0 {
						padding-right: 15px;
						padding-left: 15px;
						margin-bottom: 10px;
					}

					.p-l-0 {
						padding-left: 15px;
						padding-right: 15px;
					}

					.p-0 {
						padding: 0;
					}

					.nav-tabs li {
						width: 100%;
					}

					.nav-tabs li:last-child {
						margin-bottom: 5px;
					}


				}
			}
		</style>

		<?php

		// echo "_".$cod_envio;

		if ($cod_envio == 39) {

			if ($tip_token == 2) {
				$type = "number";
			} else {
				$type = "text";
			}

		?>



			<div class="col-md-12 col-xs-12 text-left">

				<div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					Token enviado! Verifique o SMS recebido, e digite o token no campo abaixo:
				</div>

			</div>

			<script type="text/javascript">
				$("#btnCadastro").fadeOut('fast');
			</script>

		<?php
		} else if ($cod_envio == 0) {
		?>

			<div class="col-md-12 col-xs-12 text-left">

				<div class="alert alert-warning" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					Token não enviado, pois não há celular/email de destino. É necessário configurar a matriz de campos.
				</div>

			</div>

			<script type="text/javascript">
				$("#btnCadastro").fadeOut('fast');
			</script>

		<?php
			exit();
		} else if ($cod_envio == 96) {
		?>

			<div class="col-md-12 col-xs-12 text-left">

				<div class="alert alert-danger" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					Rotinas de token incompletas.<br>Contate o suporte.
				</div>

			</div>

			<script type="text/javascript">
				$("#btnCadastro").fadeOut('fast');
			</script>

		<?php
			exit();
		} else {

		?>

			<div class="col-md-12 col-xs-12 text-left">

				<div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					O token já enviado. Verifique o SMS recebido, e digite o token no campo abaixo. Caso não tenha recebido, por favor aguarde 5 minutos e tente enviar o token novamente.
				</div>

			</div>

			<script type="text/javascript">
				$("#btnCadastro").fadeOut('fast');
			</script>

		<?php

		}


		?>


		<div id="camposToken">

			<div class="col-md-12 col-xs-12 text-left" id="erroTkn" style="display: none;">

				<div class="alert alert-danger" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					Token inválido.
				</div>

			</div>

			<div class="col-md-8 col-xs-12 text-left p-r-0">
				<div class="form-group">
					<!-- <label for="inputName" class="control-label required">Token</label> -->
					<input type="<?= $type ?>" placeholder="Digite o token" name="DES_TOKEN" id="DES_TOKEN" value="" maxlength="<?= $qtd_chartkn ?>" class="form-control input-sm" style="height:43px; border-radius:0 3px 3px 0;">
					<div class="help-block with-errors"></div>
				</div>
			</div>

			<div class="col-md-4 col-xs-12 p-l-0">
				<!-- <label>&nbsp;</label> -->
				<a style="width: 100%; border-radius: 0!important;  height:43px; margin-top: 0px;" class="btn btn-success btn-sm f18" onclick='ajxValidaTkn()'>Clique aqui para validar o token</a>
			</div>

		</div>

		<div class="push20"></div>




	<?php

		break;

	case 'TKN':

		include_once '../totem/funWS/GeraToken.php';
		include_once '../totem/funWS/buscaConsumidor.php';
		include_once '../totem/funWS/buscaConsumidorCNPJ.php';

		$sql = "SELECT COD_EMPRESA, NOM_FANTASI, QTD_CHARTKN, TIP_TOKEN, TIP_RETORNO, NUM_DECIMAIS_B  FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

		if (isset($arrayQuery)) {
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
			$qtd_chartkn = $qrBuscaEmpresa['QTD_CHARTKN'];
			$tip_token = $qrBuscaEmpresa['TIP_TOKEN'];


			if ($qrBuscaEmpresa['TIP_RETORNO'] == 1) {
				$casasDec = 0;
			} else {
				$casasDec = $qrBuscaEmpresa['NUM_DECIMAIS_B'];
				$pref = "R$ ";
			}

			// echo($casasDec);
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

		$urltotem = $log_usuario . ';'
			. $des_senhaus . ';'
			. $idlojaKey . ';'
			. $idmaquinaKey . ';'
			. $cod_empresa . ';'
			. $codvendedorKey . ';'
			. $nomevendedorKey;

		$arrayCampos = explode(";", $urltotem);

		$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CELULAR']));
		$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CGCECPF']));
		$des_emailus = fnLimpaCampo($_POST['DES_EMAILUS']);
		$nom_cliente = fnLimpaCampo($_POST['NOM_CLIENTE']);

		if ($num_celular == "") {
			if ($isapp) {
				$num_celular = fnLimpaCampo(fnLimpaDoc(fnDecode($_POST['KEY_NUM_CELULAR'])));
			} else {
				$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['KEY_NUM_CELULAR']));
			}
		}

		if ($num_cgcecpf == "") {
			if ($isapp) {
				$num_cgcecpf = fnLimpaCampo(fnLimpaDoc(fnDecode($_POST['KEY_NUM_CGCECPF'])));
				$k_num_cgcecpf = fnLimpaCampo(fnLimpaDoc(fnDecode($_POST['KEY_NUM_CGCECPF'])));
			} else {
				$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['KEY_NUM_CGCECPF']));
				$k_num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['KEY_NUM_CGCECPF']));
			}
			if (strlen($k_num_cgcecpf) <= '11') {

				// echo '<pre>';

				$buscaconsumidor = fnconsulta(fnCompletaDoc($k_num_cgcecpf, 'F'), $arrayCampos);

				// print_r($buscaconsumidor);

				// echo '</pre>';

			} else {

				// echo 'else';

				$buscaconsumidor = fnconsultacnpf(fnCompletaDoc($k_num_cgcecpf, 'J'), $arrayCampos);
			}
		}

		if ($num_cgcecpf == "00000000000") {
			$num_cgcecpf = $num_celular;
		}

		$dadosenvio = array(
			'tipoGeracao' => '1',
			'nome' => "$nom_cliente",
			'cpf' => "$num_cgcecpf",
			'celular' => "$num_celular",
			'email' => "$des_emailus"
		);


		$retornoEnvio = GeraToken($dadosenvio, $arrayCampos);


		// if ($num_cgcecpf == '42819523811') {
		// 	echo '<pre>';
		// 	print_r($dadosenvio);
		// 	echo '</pre>';
		// 	echo '<pre>';
		// 	print_r($retornoEnvio);
		// 	echo '</pre>';
		// 	exit();
		// }



		$cod_envio = $retornoEnvio["body"]["envelope"]["body"]["geratokenresponse"]["retornatoken"]["coderro"];
		$msg_error = $retornoEnvio["body"]["envelope"]["body"]["geratokenresponse"]["retornatoken"]["msgerro"];

	?>

		<style>
			.p-r-0 {
				padding-right: 0;
			}

			.p-l-0 {
				padding-left: 0;
			}

			.img-g {
				display: none;
			}

			.img-m {
				display: block;
			}

			.img-p {
				display: none;
			}

			@media only screen and (min-device-width: 320px) and (max-device-width: 480px) {

				.img-g {
					display: none;
				}

				.img-m {
					display: none;
				}

				.img-p {
					display: block;
				}

				#roteiro {
					display: none;
				}

				.p-r-0 {
					padding-right: 15px;
					padding-left: 15px;
					margin-bottom: 10px;
				}

				.p-l-0 {
					padding-left: 15px;
					padding-right: 15px;
				}

				.p-0 {
					padding: 0;
				}

				.nav-tabs li {
					width: 100%;
				}

				.nav-tabs li:last-child {
					margin-bottom: 5px;
				}


			}

			/* (320x480) Smartphone, Portrait */
			@media only screen and (device-width: 320px) and (orientation: portrait) {

				.img-g {
					display: none;
				}

				.img-m {
					display: none;
				}

				.img-p {
					display: block;
				}

				#roteiro {
					display: none;
				}

				.p-r-0 {
					padding-right: 15px;
					padding-left: 15px;
					margin-bottom: 10px;
				}

				.p-l-0 {
					padding-left: 15px;
					padding-right: 15px;
				}

				.p-0 {
					padding: 0;
				}

				.nav-tabs li {
					width: 100%;
				}

				.nav-tabs li:last-child {
					margin-bottom: 5px;
				}

			}

			/* (320x480) Smartphone, Landscape */
			@media only screen and (device-width: 480px) and (orientation: landscape) {

				.img-g {
					display: none;
				}

				.img-m {
					display: none;
				}

				.img-p {
					display: block;
				}

				#roteiro {
					display: none;
				}

				.p-r-0 {
					padding-right: 15px;
					padding-left: 15px;
					margin-bottom: 10px;
				}

				.p-l-0 {
					padding-left: 15px;
					padding-right: 15px;
				}

				.p-0 {
					padding: 0;
				}

				.nav-tabs li {
					width: 100%;
				}

				.nav-tabs li:last-child {
					margin-bottom: 5px;
				}

			}

			/* (1024x768) iPad 1 & 2, Landscape */
			@media only screen and (min-device-width: 768px) and (max-device-width: 1367px) and (orientation: landscape) {

				.img-g {
					display: none;
				}

				.img-m {
					display: none;
				}

				.img-p {
					display: block;
				}

				#roteiro {
					display: none;
				}

				.p-r-0 {
					padding-right: 15px;
					/* 15px */
				}

				.p-l-0 {
					padding-left: 0;
					/* 15p15px */
				}

				/* (1280x800) Tablets, Portrait */
				@media only screen and (max-width: 800px) and (orientation : portrait) {

					.img-g {
						display: none;
					}

					.img-m {
						display: none;
					}

					.img-p {
						display: block;
					}

					#roteiro {
						display: none;
					}

					.p-r-0 {
						padding-right: 15px;
						padding-left: 15px;
						margin-bottom: 10px;
					}

					.p-l-0 {
						padding-left: 15px;
						padding-right: 15px;
					}

					.p-0 {
						padding: 0;
					}

					.nav-tabs li {
						width: 100%;
					}

					.nav-tabs li:last-child {
						margin-bottom: 5px;
					}

				}

				/* (768x1024) iPad 1 & 2, Portrait */
				@media only screen and (max-width: 768px) and (orientation : portrait) {

					.img-g {
						display: none;
					}

					.img-m {
						display: none;
					}

					.img-p {
						display: block;
					}

					#roteiro {
						display: none;
					}

					.p-r-0 {
						padding-right: 15px;
						padding-left: 15px;
						margin-bottom: 10px;
					}

					.p-l-0 {
						padding-left: 15px;
						padding-right: 15px;
					}

					.p-0 {
						padding: 0;
					}

					.nav-tabs li {
						width: 100%;
					}

					.nav-tabs li:last-child {
						margin-bottom: 5px;
					}

				}

				/* (2048x1536) iPad 3 and Desktops*/
				@media only screen and (min-device-width: 1536px) and (max-device-width: 2048px) {

					.img-g {
						display: block;
					}

					.img-m {
						display: none;
					}

					.img-p {
						display: none;
					}

					.p-r-0 {
						padding-right: 0;
					}

					.p-l-0 {
						padding-left: 0;
					}

				}

				@media only screen and (min-device-width: 1100px) and (orientation : portrait) {

					.img-g {
						display: none;
					}

					.img-m {
						display: none;
					}

					.img-p {
						display: block;
					}

					#roteiro {
						display: none;
					}

					.p-r-0 {
						padding-right: 15px;
						padding-left: 15px;
						margin-bottom: 10px;
					}

					.p-l-0 {
						padding-left: 15px;
						padding-right: 15px;
					}

					.p-0 {
						padding: 0;
					}

					.nav-tabs li {
						width: 100%;
					}

					.nav-tabs li:last-child {
						margin-bottom: 5px;
					}

				}

				@media (max-height: 824px) and (max-width: 416px) {

					.img-g {
						display: none;
					}

					.img-m {
						display: none;
					}

					.img-p {
						display: block;
					}

					#roteiro {
						display: none;
					}

					.p-r-0 {
						padding-right: 15px;
						padding-left: 15px;
						margin-bottom: 10px;
					}

					.p-l-0 {
						padding-left: 15px;
						padding-right: 15px;
					}

					.p-0 {
						padding: 0;
					}

					.nav-tabs li {
						width: 100%;
					}

					.nav-tabs li:last-child {
						margin-bottom: 5px;
					}

				}

				/* (320x480) iPhone (Original, 3G, 3GS) */
				@media (max-device-width: 737px) and (max-height: 416px) {

					.img-g {
						display: none;
					}

					.img-m {
						display: none;
					}

					.img-p {
						display: block;
					}

					#roteiro {
						display: none;
					}

					.p-r-0 {
						padding-right: 15px;
						padding-left: 15px;
						margin-bottom: 10px;
					}

					.p-l-0 {
						padding-left: 15px;
						padding-right: 15px;
					}

					.p-0 {
						padding: 0;
					}

					.nav-tabs li {
						width: 100%;
					}

					.nav-tabs li:last-child {
						margin-bottom: 5px;
					}


				}
			}
		</style>

		<?php

		// echo "_".$cod_envio;

		if ($cod_envio == 39) {

			if ($tip_token == 2) {
				$type = "number";
			} else {
				$type = "text";
			}

			if ($des_emailus != "") {

				include "../_system/EMAIL/PHPMailer/PHPMailerAutoload.php";
				include '../externo/email/envio_sac.php';

				$texto = '<!DOCTYPE html>
							<html>
							<head>
								<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
								<title>Token de Cadastro</title>
							</head>
							<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f5f5;">
								<!-- Container Principal -->
								<table width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; margin: 0 auto;">
									<tr>
										<td style="padding: 20px 0;">
											<!-- Cabeçalho -->
											<table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #1b2a89;">
												<tr>
													<td style="padding: 25px 20px; text-align: center;">
														<h1 style="margin: 0; color: #ffffff; font-size: 22px; font-weight: 600;">Kings Club - Token</h1>
													</td>
												</tr>
											</table>
											
											<!-- Corpo do Email -->
											<table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #ffffff;">
												<tr>
													<td style="padding: 30px 20px;">
														<p style="margin: 0 0 15px 0; color: #333333; font-size: 15px; line-height: 1.5;">Prezado usuário,</p>
														<p style="margin: 0 0 20px 0; color: #333333; font-size: 15px; line-height: 1.5;">Informe o token abaixo para completar seu cadastro em nossa plataforma:</p>
														
														<!-- Box do Token -->
														<table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 25px;">
															<tr>
																<td style="background-color: #f8f9fa; border: 1px solid #e0e0e0; border-radius: 4px; padding: 15px; text-align: center;">
																	<span style="font-size: 28px; font-weight: bold; color: #1b2a89; letter-spacing: 5px;">' . $retornoEnvio["body"]["envelope"]["body"]["geratokenresponse"]["retornatoken"]["token"] . '</span>
																</td>
															</tr>
														</table>
														
														<!-- Informações Adicionais -->
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td style="padding-bottom: 5px;">
																	<p style="margin: 0; color: #666666; font-size: 13px;">• Este token é válido por 24 horas</p>
																</td>
															</tr>
															<tr>
																<td style="padding-bottom: 5px;">
																	<p style="margin: 0; color: #666666; font-size: 13px;">• Não compartilhe este código com ninguém</p>
																</td>
															</tr>
															<tr>
																<td>
																	<p style="margin: 0; color: #666666; font-size: 13px;">• Caso não tenha solicitado, ignore este e-mail</p>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
											
											<!-- Rodapé -->
											<table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f5f5f5;">
												<tr>
													<td style="padding: 15px 20px; text-align: center;">
														<p style="margin: 0; color: #999999; font-size: 12px;">© 2023 Marka Fidelização. Todos os direitos reservados.</p>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</body>
							</html>';
				// $texto="Seu token é: <strong>".$retornoEnvio['body']['envelope']['body']['geratokenresponse']['retornatoken']['token']."</strong>";

				$email['email1'] = $des_emailus;

				fnsacmail(
					$email,
					'Token APP',
					"<html>" . $texto . "</html>",
					"CADASTRO KINGS CLUB",
					"APP",
					$connAdm->connAdm(),
					connTemp(3, ""),
					3
				);
			}

		?>



			<div class="col-md-12 col-xs-12 text-left">

				<div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					Token enviado! Peça para o cliente verificar e informar o SMS recebido, e digite o token no campo abaixo:
				</div>

			</div>

			<script type="text/javascript">
				$("#btnCadastro").fadeOut('fast');
			</script>

		<?php
		} else if ($cod_envio == 0) {
		?>

			<div class="col-md-12 col-xs-12 text-left">

				<div class="alert alert-warning" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					Token não enviado, pois não há celular/email de destino. É necessário configurar a matriz de campos.
				</div>

			</div>

			<script type="text/javascript">
				$("#btnCadastro").fadeOut('fast');
			</script>

		<?php
			exit();
		} else if ($cod_envio == 96) {
		?>

			<div class="col-md-12 col-xs-12 text-left">

				<div class="alert alert-danger" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					Rotinas de token incompletas.<br>Contate o suporte.
				</div>

			</div>

			<script type="text/javascript">
				$("#btnCadastro").fadeOut('fast');
			</script>

		<?php
			exit();
		} else if ($cod_envio == 91) {

		?>

			<div class="col-md-12 col-xs-12 text-left">

				<div class="alert alert-danger" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<?= $msg_error ?>
				</div>

			</div>

			<script type="text/javascript">
				$("#btnCadastro").fadeOut('fast');
			</script>

		<?php
			exit();
		} else {

		?>

			<div class="col-md-12 col-xs-12 text-left">

				<div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					O token já havia sido enviado. Peça para o cliente verificar e informar o SMS recebido, e digite o token no campo abaixo:
				</div>

			</div>

			<script type="text/javascript">
				$("#btnCadastro").fadeOut('fast');
			</script>

			<?php

		}

		$log_lgpd = fnLimpaCampo(fnDecode($_POST['LOG_LGPD']));

		$sqlCampos = "SELECT NOM_CAMPOOBG, 
								 NOM_CAMPOOBG, 
								 DES_CAMPOOBG, 
								 MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG AS CAT_CAMPO, 
								 INTEGRA_CAMPOOBG.TIP_CAMPOOBG AS TIPO_DADO,
								 (SELECT COUNT(MCI.TIP_CAMPOOBG) 
									FROM matriz_campo_integracao MCI
									WHERE MCI.TIP_CAMPOOBG = 'OBG' 
									AND MCI.COD_CAMPOOBG = MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG
									AND MCI.COD_EMPRESA = $cod_empresa) AS OBRIGATORIO,
								 COL_MD, 
								 COL_XS, 
								 CLASSE_INPUT, 
								 CLASSE_DIV 
							FROM MATRIZ_CAMPO_INTEGRACAO                         
							LEFT JOIN INTEGRA_CAMPOOBG ON INTEGRA_CAMPOOBG.COD_CAMPOOBG=MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG                         
							WHERE MATRIZ_CAMPO_INTEGRACAO.COD_EMPRESA = $cod_empresa
							AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'KEY'
							AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'CAD'
							AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'TKN'
							AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'OPC'
							AND MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG != 24
							AND INTEGRA_CAMPOOBG.DES_CAMPOOBG NOT IN(
	
								SELECT  
								 DES_CAMPOOBG 
									FROM MATRIZ_CAMPO_INTEGRACAO                         
									LEFT JOIN INTEGRA_CAMPOOBG ON INTEGRA_CAMPOOBG.COD_CAMPOOBG=MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG                         
									WHERE MATRIZ_CAMPO_INTEGRACAO.COD_EMPRESA = $cod_empresa
									AND MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG != 24
									AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG = 'TKN'
								
							)
							ORDER BY NUM_ORDENAC ASC, COL_MD ASC, COL_XS ASC, MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG, MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG ASC";

		$arrayCampos = mysqli_query($connAdm->connAdm(), $sqlCampos);

		// echo($sqlCampos);

		$lastField = "";

		while ($qrCampos = mysqli_fetch_assoc($arrayCampos)) {

			// echo "<pre>";
			// print_r($qrCampos);
			// echo "</pre>";

			$colMd = $qrCampos["COL_MD"];
			$colXs = $qrCampos["COL_XS"];
			$dataError = "";

			$required = "";
			// echo "$qrCampos[NOM_CAMPOOBG]: $qrCampos[CAT_CAMPO] - $required<br>";

			if ($lastField == "") {
				$lastField = $qrCampos["NOM_CAMPOOBG"];
			} else if ($lastField == $qrCampos["NOM_CAMPOOBG"]) {
				continue;
			} else {
				$lastField = $qrCampos["NOM_CAMPOOBG"];
			}

			if ($qrCampos["OBRIGATORIO"] > 0) {
				$required = "required";
				$dataError = "data-error='Campo obrigatório'";
			}

			// echo "$qrCampos[CAT_CAMPO]";

			if ($colMd == "" || $colMd == 0) {
				$colMd = 12;
			}

			if ($colXs == "" || $colXs == 0) {
				$colXs = 12;
			}

			switch ($qrCampos["DES_CAMPOOBG"]) {

				case 'NOM_CLIENTE':

					$dado = $buscaconsumidor['nome'];
					$dadoEncrypt = "";

					break;

				case 'COD_SEXOPES':

					$dado = $buscaconsumidor['sexo'];
					$dadoEncrypt = "";

					break;

				case 'DES_EMAILUS':

					$dado = fnMascaraCampo($buscaconsumidor['email']);
					$dadoEncrypt = fnEncode($buscaconsumidor['email']);

					$helpCampo = $msgAlteraCampo;

					break;

				case 'NUM_CELULAR':

					if ($cod_empresa == 19) {
						$dado = fnMascaraCampo($buscaconsumidor['telcelular']);
					} else {
						$dado = $buscaconsumidor['telcelular'];
					}

					$dadoEncrypt = fnEncode($buscaconsumidor['telcelular']);

					$helpCampo = $msgAlteraCampo;

					break;

				case 'NUM_CARTAO':

					$dado = $buscaconsumidor['cartao'];
					$dadoEncrypt = "";

					break;

				case 'NUM_CGCECPF':

					$dado = $buscaconsumidor['cpf'];
					$dadoEncrypt = "";

					break;


				case 'DAT_NASCIME':

					$dado = $buscaconsumidor['datanascimento'];
					// $dado = fnMascaraCampo($buscaconsumidor['datanascimento']);
					// $dadoEncrypt = fnEncode($buscaconsumidor['datanascimento']);
					$dadoEncrypt = "";

					break;

				case 'COD_PROFISS':

					$dado = $buscaconsumidor['profissao'];
					$dadoEncrypt = "";

					break;

				case 'COD_ATENDENTE':

					$dado = $buscaconsumidor['codatendente'];
					$dadoEncrypt = "";

					break;

				case 'DES_SENHAUS':

					$dado = $buscaconsumidor['senha'];
					$dadoEncrypt = "";

					break;

				case 'DES_ENDEREC':

					$dado = $buscaconsumidor['endereco'];
					$dadoEncrypt = "";

					break;

				case 'NUM_ENDEREC':

					$dado = $buscaconsumidor['numero'];
					$dadoEncrypt = "";

					break;

				case 'NUM_CEPOZOF':

					$dado = $buscaconsumidor['cep'];
					$dadoEncrypt = "";

					break;

				case 'estado':

					$dado = $buscaconsumidor['estado'];
					$dadoEncrypt = "";

					break;

				case 'NOM_CIDADEC':

					$dado = $buscaconsumidor['cidade'];
					$dadoEncrypt = "";

					break;

				case 'DES_BAIRROC':

					$dado = $buscaconsumidor['bairro'];
					$dadoEncrypt = "";

					break;

				case 'DES_COMPLEM':

					$dado = $buscaconsumidor['complemento'];
					$dadoEncrypt = "";

					break;

				default:

					$dado = "";
					$dadoEncrypt = "";

					break;
			}

			switch ($qrCampos["TIPO_DADO"]) {

				case 'Data':

					if ($qrCampos["DES_CAMPOOBG"] == "DAT_NASCIME") {
						$calculaData = "calculaData";
					}

			?>
					<div class="col-md-<?= $colMd ?> col-xs-<?= $colXs ?>" id="blocoNascimento">
						<div class="form-group">
							<label>&nbsp;</label>
							<label for="inputName" class="control-label <?= $required ?>"><?= $qrCampos["NOM_CAMPOOBG"] ?></label>
							<input type="tel" placeholder="DD/MM/AAAA" value="<?= $dado ?>" class="form-control input-sm input-hg <?= $qrCampos["CLASSE_INPUT"] ?> data <?= $calculaData ?>" name="<?= $qrCampos["DES_CAMPOOBG"] ?>" id="<?= $qrCampos["DES_CAMPOOBG"] ?>" maxlenght="10" data-minlength="10" data-minlength-error="O formato da data deve ser DD/MM/AAAA" <?= $dataError ?> pattern="(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/(19|20)\d{2}" data-pattern-error="Formato inválido" <?= $required ?>>
							<div class="help-block with-errors"></div>
						</div>
					</div>

				<?php

					break;

				case 'email':

					$dataError = "";

				?>
					<div class="col-md-<?= $colMd ?> col-xs-<?= $colXs ?>">
						<div class="form-group">
							<label>&nbsp;</label>
							<label for="inputName" class="control-label <?= $required ?>"><?= $qrCampos["NOM_CAMPOOBG"] ?></label>
							<input type="email" value="<?= $dado ?>" class="form-control input-sm input-hg <?= $qrCampos["CLASSE_INPUT"] ?>" name="<?= $qrCampos["DES_CAMPOOBG"] ?>" id="<?= $qrCampos["DES_CAMPOOBG"] ?>" <?= $dataError ?> <?= $required ?>>
							<input type="hidden" value="<?= $dadoEncrypt ?>" name="HID_<?= $qrCampos["DES_CAMPOOBG"] ?>" id="HID_<?= $qrCampos["DES_CAMPOOBG"] ?>">
							<div class="help-block with-errors"><?= $helpCampo ?></div>
						</div>
					</div>

					<?php

					break;

				case 'numeric':

					if ($qrCampos["DES_CAMPOOBG"] == "COD_SEXOPES") {

					?>
						<div class="col-md-<?= $colMd ?> col-xs-<?= $colXs ?>" id="blocoSexo">
							<div class="form-group">
								<label>&nbsp;</label>
								<label for="inputName" class="control-label <?= $required ?>">Sexo</label>
								<select data-placeholder="Selecione o sexo" name="COD_SEXOPES" id="COD_SEXOPES" class="chosen-select-deselect input-sm <?= $qrCampos["CLASSE_INPUT"] ?>" <?= $required ?>>
									<option value=""></option>
									<?php
									$sql = "select COD_SEXOPES, DES_SEXOPES from sexo order by des_sexopes ";
									$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

									while ($qrListaSexo = mysqli_fetch_assoc($arrayQuery)) {
										echo "
															  <option value='" . $qrListaSexo['COD_SEXOPES'] . "'>" . $qrListaSexo['DES_SEXOPES'] . "</option> 
															";
									}
									?>
								</select>
								<script type="text/javascript">
									$("#COD_SEXOPES").val("<?= $dado ?>").trigger('chosen:updated');
								</script>
								<div class="help-block with-errors"></div>
							</div>
						</div>

					<?php

					} else if ($qrCampos["DES_CAMPOOBG"] == "COD_PROFISS") {

					?>
						<div class="col-md-12 col-xs-12">
							<div class="form-group">
								<label>&nbsp;</label>
								<label for="inputName" class="control-label <?= $required ?>">Profissão </label>
								<select data-placeholder="Selecione a profissão" name="COD_PROFISS" id="COD_PROFISS" class="chosen-select-deselect input-sm <?= $qrCampos["CLASSE_INPUT"] ?>" <?= $required ?>>
									<option value=""></option>
									<?php

									$sql = "select COD_PROFISS, DES_PROFISS from profissoes_empresa where cod_empresa=$cod_empresa  order by DES_PROFISS";

									if (mysqli_num_rows(mysqli_query(connTemp($cod_empresa, ''), $sql)) <= '0') {

										$sql = "select COD_PROFISS, DES_PROFISS from PROFISSOES order by DES_PROFISS ";
										$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
									} else {

										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
									}

									while ($qrListaProfi = mysqli_fetch_assoc($arrayQuery)) {
										echo "
															  <option value='" . $qrListaProfi['COD_PROFISS'] . "'>" . $qrListaProfi['DES_PROFISS'] . "</option> 
															";
									}

									?>
								</select>
								<script type="text/javascript">
									$("#COD_PROFISS").val("<?= $dado ?>").trigger('chosen:updated');
								</script>
								<div class="help-block with-errors"></div>
							</div>
						</div>

					<?php

					} else if ($qrCampos["DES_CAMPOOBG"] == "COD_ESTACIV") {

					?>
						<div class="col-md-<?= $colMd ?> col-xs-<?= $colXs ?>">
							<div class="form-group">
								<label>&nbsp;</label>
								<label for="inputName" class="control-label <?= $required ?>">Estado Civil</label>
								<select data-placeholder="Selecione um estado civil" name="COD_ESTACIV" id="COD_ESTACIV" class="chosen-select-deselect input-sm <?= $qrCampos["CLASSE_INPUT"] ?>" <?= $required ?>>
									<option value=""></option>
									<?php
									$sql = "select COD_ESTACIV, DES_ESTACIV from estadocivil order by des_estaciv; ";
									$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

									while ($qrListaEstCivil = mysqli_fetch_assoc($arrayQuery)) {
										echo "
															  <option value='" . $qrListaEstCivil['COD_ESTACIV'] . "'>" . $qrListaEstCivil['DES_ESTACIV'] . "</option> 
															";
									}
									?>
								</select>
								<script type="text/javascript">
									$("#COD_ESTACIV").val("<?= $dado ?>").trigger('chosen:updated');
								</script>
								<div class="help-block with-errors"></div>
							</div>
						</div>

					<?php

					} else {

						$type = "text";

						if ($qrCampos["DES_CAMPOOBG"] == "NUM_CGCECPF") {
							$nomeCampo = "CPF/CNPJ";
							$mask = "cpfcnpj";
							$type = "tel";
						} else {
							$nomeCampo = $qrCampos["NOM_CAMPOOBG"];
							$mask = "";
						}

					?>
						<div class="col-md-<?= $colMd ?> col-xs-<?= $colXs ?>">
							<div class="form-group">
								<label>&nbsp;</label>
								<label for="inputName" class="control-label <?= $required ?>"><?= $nomeCampo ?></label>
								<input type="<?= $type ?>" value="<?= $dado ?>" class="form-control input-sm input-hg <?= $qrCampos["CLASSE_INPUT"] ?> <?= $mask ?>" name="<?= $qrCampos["DES_CAMPOOBG"] ?>" id="<?= $qrCampos["DES_CAMPOOBG"] ?>" <?= $dataError ?> <?= $required ?>>
								<div class="help-block with-errors"><?= $helpCampo ?></div>
							</div>
						</div>

					<?php

					}

					break;

				default:

					$type = "text";
					$validacao = "";

					if ($qrCampos["DES_CAMPOOBG"] == "NUM_CGCECPF") {
						$nomeCampo = "CPF/CNPJ";
						$mask = "cpfcnpj";
						$type = "tel";
					} else if ($qrCampos["DES_CAMPOOBG"] == "NUM_CELULAR") {
						$type = "tel";
						$validacao = 'data-minlength="15" data-minlength-error="Número incompleto" pattern="(\([1-9]{2}\))\s([9]{1})([0-9]{4})-([0-9]{4})" data-pattern-error="Formato inválido"';
					} else if ($qrCampos["DES_CAMPOOBG"] == "NUM_TELEFONE" || $qrCampos["DES_CAMPOOBG"] == "NUM_CEPOZOF") {
						$type = "tel";
					} else {
						$nomeCampo = $qrCampos["NOM_CAMPOOBG"];
						$mask = "";
					}

					if ($qrCampos["DES_CAMPOOBG"] == "COD_ESTADOF") {

					?>
						<div class="col-md-<?= $colMd ?> col-xs-<?= $colXs ?>">
							<div class="form-group">
								<label>&nbsp;</label>
								<label for="inputName" class="control-label <?= $required ?>"><?= $nomeCampo ?></label>
								<select data-placeholder="Selecione um estado" name="COD_ESTADOF" id="COD_ESTADOF" class="chosen-select-deselect input-sm <?= $qrCampos["CLASSE_INPUT"] ?>" <?= $dataError ?> <?= $required ?>>
									<option value=""></option>
									<option value="AC">AC</option>
									<option value="AL">AL</option>
									<option value="AM">AM</option>
									<option value="AP">AP</option>
									<option value="BA">BA</option>
									<option value="CE">CE</option>
									<option value="DF">DF</option>
									<option value="ES">ES</option>
									<option value="GO">GO</option>
									<option value="MA">MA</option>
									<option value="MG">MG</option>
									<option value="MS">MS</option>
									<option value="MT">MT</option>
									<option value="PA">PA</option>
									<option value="PB">PB</option>
									<option value="PE">PE</option>
									<option value="PI">PI</option>
									<option value="PR">PR</option>
									<option value="RJ">RJ</option>
									<option value="RN">RN</option>
									<option value="RO">RO</option>
									<option value="RR">RR</option>
									<option value="RS">RS</option>
									<option value="SC">SC</option>
									<option value="SE">SE</option>
									<option value="SP">SP</option>
									<option value="TO">TO</option>
								</select>
								<script>
									$("#formulario #COD_ESTADOF").val("<?php echo $dado; ?>").trigger("chosen:updated");
								</script>
								<div class="help-block with-errors"></div>
							</div>
						</div>

					<?php

					} else {

					?>
						<div class="col-md-<?= $colMd ?> col-xs-<?= $colXs ?>">
							<div class="form-group">
								<label>&nbsp;</label>
								<label for="inputName" class="control-label <?= $required ?>"><?= $qrCampos["NOM_CAMPOOBG"] ?></label>
								<?php
								if ($cod_empresa == 19 && $cod_cliente != 0 && $qrCampos["DES_CAMPOOBG"] == "NUM_CELULAR") {
									$validacao = "";
									$qrCampos["CLASSE_INPUT"] = "";
									$type = "text";
									$dataError = "";
								}
								?>
								<input type="<?= $type ?>" value="<?= $dado ?>" class="form-control input-sm input-hg <?= $qrCampos["CLASSE_INPUT"] ?>" name="<?= $qrCampos["DES_CAMPOOBG"] ?>" id="<?= $qrCampos["DES_CAMPOOBG"] ?>" <?= $dataError ?> <?= $validacao ?> <?= $required ?>>
								<input type="hidden" value="<?= $dadoEncrypt ?>" name="HID_<?= $qrCampos["DES_CAMPOOBG"] ?>" id="HID_<?= $qrCampos["DES_CAMPOOBG"] ?>">
								<div class="help-block with-errors"><?= $helpCampo ?></div>
							</div>
						</div>

			<?php

					}

					break;
			}

			?>
			<!-- <div class="push10"></div> -->
			<?php

		}

		if ($mostraSenha == 1) {

			if ($cod_cliente == 0) {

				if ($codunivend_campanha == "") {

			?>

					<div class="push"></div>

					<div class="col-md-12 col-xs-12">
						<div class="form-group">
							<label>&nbsp;</label>
							<label for="inputName" class="control-label required">Loja de Cadastro</label>
							<select data-placeholder="Selecione a unidade de atendimento" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect input-sm" required>
								<option value=""></option>
								<?php
								$sql = "select COD_UNIVEND, NOM_FANTASI from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' AND LOG_ESTATUS = 'S' order by NOM_UNIVEND ";
								$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

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
						</div>
					</div>

					<div class="push20"></div>

				<?php

				} else {

				?>

					<input type="hidden" name="COD_UNIVEND" id="COD_UNIVEND" value="<?= $codunivend_campanha ?>">

			<?php
				}
			}

			$senha = $buscaconsumidor["senha"][0];

			if ($senha == 0) {
				$senha = "";
			}

			$inteiro = "";
			$helpSenha = "Máximo de 6 dígitos alfanuméricos";
			$maxSenha = "6";

			$sqlCampos = "SELECT NOM_FANTASI, 
							LOG_CADTOKEN,
							COD_CHAVECO,
			                TIP_RETORNO, 
			                LOG_BLOQUEIAPJ, 
			                TIP_SENHA, 
			                MIN_SENHA, 
			                MAX_SENHA, 
			                REQ_SENHA,
			                TIP_ENVIO,
			                LOG_RECUPERA,
			                LOG_DAT_NASCIME
			        FROM empresas 
			        where COD_EMPRESA = $cod_empresa";

			$arrayFields = mysqli_query($connAdm->connAdm(), $sqlCampos);

			// echo($sqlCampos);
			$lastField = "";

			$qrCampos = mysqli_fetch_assoc($arrayFields);

			// $log_cadtoken = $qrCampos['LOG_CADTOKEN'];
			$cod_chaveco = $qrCampos['COD_CHAVECO'];
			$tip_retorno = $qrCampos['TIP_RETORNO'];
			$log_bloqueiapj = $qrCampos['LOG_BLOQUEIAPJ'];
			$tip_senha = $qrCampos['TIP_SENHA'];
			$min_senha = $qrCampos['MIN_SENHA'];
			$max_senha = $qrCampos['MAX_SENHA'];
			$req_senha = $qrCampos['REQ_SENHA'];
			$tip_envio = $qrCampos['TIP_ENVIO'];
			$log_recupera = $qrCampos['LOG_RECUPERA'];

			switch ($tip_senha) {
				case '2':
					$helpSenha = "Min de $min_senha e max. de $max_senha caracteres numéricos";
					break;

				default:
					$helpSenha = "Min de $min_senha e max. de $max_senha caracteres alfanuméricos";
					break;
			}

			$wizardSenha = "int";

			if ($tip_senha != "2") {
				$wizardSenha = "pr-password";
			}


			if ($cod_empresa == 124) {
				$wizardSenha = "int";
				$helpSenha = "Máximo de 4 dígitos numéricos";
				$max_senha = "4";
			}

			?>
			<div class="push"></div>

			<div class="col-md-12 col-xs-12">
				<div class="form-group">
					<label>&nbsp;</label>
					<label for="inputName" class="control-label required">Crie sua senha de acesso</label>
					<input type="password" placeholder="Nova Senha" style="font-size: 36px;" class="form-control input-hg input-lg text-center <?= $wizardSenha ?> <?= $classeSenha ?>" data-minlength-error="Mínimo de <?= $min_senha ?> caracteres." data-maxlength-error="Máximo de <?= $max_senha ?> caracteres." name="DES_SENHAUS" id="DES_SENHAUS" minlength="<?= $min_senha ?>" maxlength="<?= $max_senha ?>" data-required-error="Campo obrigatório" autocomplete="new-password" required>
					<div class="help-block with-errors f12"><?= $helpSenha ?></div>
				</div>
			</div>

			<div class="col-md-12 col-xs-12">
				<div class="form-group">
					<label>&nbsp;</label>
					<label for="inputName" class="control-label required">Confirme sua senha</label>
					<input type="password" placeholder="Confirmar Senha" style="font-size: 36px;" class="form-control input-hg input-lg text-center <?= $classeSenha ?>" data-minlength-error="Mínimo de <?= $min_senha ?> caracteres." data-maxlength-error="Máximo de <?= $max_senha ?> caracteres." name="DES_SENHAUS_CONF" id="DES_SENHAUS_CONF" minlength="<?= $min_senha ?>" maxlength="<?= $max_senha ?>" data-match="#DES_SENHAUS" data-required-error="Campo obrigatório" data-match-error="Senhas diferentes" required>
					<div class="help-block with-errors f12"></div>
				</div>
			</div>

		<?php

		}

		if ($log_lgpd == 'S') {



		?>

			<div id="relatorioPreview">

				<div class="push10"></div>

				<div class="col-xs-12">
					<p><b><?= $qrControle['TXT_ACEITE'] ?></b></p>
				</div>

				<div class="push10"></div>

				<?php

				if ($log_separa == 'S') {

					$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO = 'N' AND TIP_TERMO != 'COM' ORDER BY NUM_ORDENAC";
					// fnEscreve($sql);
					$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

					$count = 0;
					$tipo = "";
					while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)) {

						if ($qrBuscaFAQ["LOG_OBRIGA"] == "S") {
							$obrigaChk = "required";
						} else {
							$obrigaChk = "";
						}


						$sqlChk = "SELECT 1 FROM CLIENTES_TERMOS
											   WHERE COD_CLIENTE = $cod_cliente
											   AND COD_CLIENTE != 0
											   AND COD_EMPRESA = $cod_empresa
											   AND COD_BLOCO = $qrBuscaFAQ[COD_BLOCO]
											   AND COD_TERMOS = '$qrBuscaFAQ[COD_TERMO]'";
						// echo($sqlChk);
						$arrayChk = mysqli_query(connTemp($cod_empresa, ''), $sqlChk);

						$chkTermo = "";

						if (mysqli_num_rows($arrayChk) == 1) {
							$chkTermo = "checked";
						}

						$sqlTermos = "SELECT * FROM TERMOS_EMPRESA
												  WHERE COD_EMPRESA = $cod_empresa
												  AND COD_TERMO IN($qrBuscaFAQ[COD_TERMO])";

						// fnEscreve($sqlTermos);

						$arrayTermos = mysqli_query(connTemp($cod_empresa, ''), $sqlTermos);

						$des_bloco = $qrBuscaFAQ['DES_BLOCO'];

						while ($qrTermos = mysqli_fetch_assoc($arrayTermos)) {
							// fnEscreve(strtoupper($qrTermos['ABV_TERMO']));

							$des_bloco = str_replace(
								"<#" . strtoupper($qrTermos['ABV_TERMO']) . ">",
								'
																	</label>
																		
																			<a class="addBox f16 text-success" 
																			   data-url="termos.do?id=' . fnEncode($cod_empresa) . '&idt=' . fnEncode($qrTermos["COD_TERMO"]) . '&pop=true&rnd=' . rand() . '" 
																			   data-title="' . $qrTermos['NOM_TERMO'] . '"
																			   style="cursor:pointer;">
																			   ' . $qrTermos['ABV_TERMO'] . '
																			</a>
																		
																  	<label class="f16" for="TERMOS_' . $qrBuscaFAQ["COD_BLOCO"] . '">
																',
								$des_bloco
							);
						}

				?>

						<div class="form-group">
							<div class="col-xs-12">
								<div class="col-xs-1" style="padding-left:0; padding-right: 0;">
									<input type="checkbox" name="TERMOS_<?= $qrBuscaFAQ["COD_BLOCO"] ?>" id="TERMOS_<?= $qrBuscaFAQ["COD_BLOCO"] ?>" style="width: 18px; height: 18px;" <?= $obrigaChk ?> <?= $chkTermo ?>>
									<label class="<?= $obrigaChk ?>"></label>
								</div>
								<div class="col-xs-10" style="padding-left:0; padding-right: 0;">
									<label class="f16" for="TERMOS_<?= $qrBuscaFAQ["COD_BLOCO"] ?>">
										&nbsp;<?= $des_bloco ?>
									</label>
								</div>
							</div>
							<div class="help-block with-errors"></div>
							<div class="push10"></div>
							<div class="push5"></div>
						</div>

					<?php

						$count++;
					}

					?>

					<div class="col-xs-12">
						<h5>
							<b>
								<p><?= $qrControle['TXT_COMUNICA'] ?></p>
							</b>
						</h5>
					</div>
					<div class="push10"></div>

					<?php

					$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO = 'N' AND TIP_TERMO = 'COM' ORDER BY NUM_ORDENAC";
					// fnEscreve($sql);
					$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

					// $count=0;
					$tipo = "";
					while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)) {

						if ($qrBuscaFAQ["LOG_OBRIGA"] == "S") {
							$obrigaChk = "required";
						} else {
							$obrigaChk = "";
						}


						$sqlChk = "SELECT 1 FROM CLIENTES_TERMOS
											   WHERE COD_CLIENTE = $cod_cliente
											   AND COD_CLIENTE != 0
											   AND COD_EMPRESA = $cod_empresa
											   AND COD_BLOCO = $qrBuscaFAQ[COD_BLOCO]
											   AND COD_TERMOS = '$qrBuscaFAQ[COD_TERMO]'";
						// echo($sqlChk);
						$arrayChk = mysqli_query(connTemp($cod_empresa, ''), $sqlChk);

						$chkTermo = "";

						if (mysqli_num_rows($arrayChk) == 1) {
							$chkTermo = "checked";
						}

						$sqlTermos = "SELECT * FROM TERMOS_EMPRESA
												  WHERE COD_EMPRESA = $cod_empresa
												  AND COD_TERMO IN($qrBuscaFAQ[COD_TERMO])";

						// fnEscreve($sqlTermos);

						$arrayTermos = mysqli_query(connTemp($cod_empresa, ''), $sqlTermos);

						$des_bloco = $qrBuscaFAQ['DES_BLOCO'];

						while ($qrTermos = mysqli_fetch_assoc($arrayTermos)) {
							// fnEscreve(strtoupper($qrTermos['ABV_TERMO']));

							$des_bloco = str_replace(
								"<#" . strtoupper($qrTermos['ABV_TERMO']) . ">",
								'
																	</label>
																		
																			<a class="addBox f16 text-success" 
																			   data-url="termos.do?id=' . fnEncode($cod_empresa) . '&idt=' . fnEncode($qrTermos["COD_TERMO"]) . '&pop=true&rnd=' . rand() . '" 
																			   data-title="' . $qrTermos['NOM_TERMO'] . '"
																			   style="cursor:pointer;">
																			   ' . $qrTermos['ABV_TERMO'] . '
																			</a>
																		
																  	<label class="f16" for="TERMOS_' . $qrBuscaFAQ["COD_BLOCO"] . '">
																',
								$des_bloco
							);
						}

					?>

						<div class="form-group">
							<div class="col-xs-12">
								<div class="col-xs-1" style="padding-left:0; padding-right: 0;">
									<input type="checkbox" name="TERMOS_<?= $qrBuscaFAQ["COD_BLOCO"] ?>" id="TERMOS_<?= $qrBuscaFAQ["COD_BLOCO"] ?>" style="width: 18px; height: 18px;" <?= $obrigaChk ?> <?= $chkTermo ?>>
									<label class="<?= $obrigaChk ?>"></label>
								</div>
								<div class="col-xs-10" style="padding-left:0; padding-right: 0;">
									<label class="f16" for="TERMOS_<?= $qrBuscaFAQ["COD_BLOCO"] ?>">
										&nbsp;<?= $des_bloco ?>
									</label>
								</div>
							</div>
							<div class="help-block with-errors"></div>
							<div class="push10"></div>
							<div class="push5"></div>
						</div>

					<?php

						$count++;
					}
				} else {

					$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO = 'N' ORDER BY NUM_ORDENAC";
					// fnEscreve($sql);
					$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

					$count = 0;
					$tipo = "";
					while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)) {

						if ($qrBuscaFAQ["LOG_OBRIGA"] == "S") {
							$obrigaChk = "required";
						} else {
							$obrigaChk = "";
						}


						$sqlChk = "SELECT 1 FROM CLIENTES_TERMOS
											   WHERE COD_CLIENTE = $cod_cliente
											   AND COD_CLIENTE != 0
											   AND COD_EMPRESA = $cod_empresa
											   AND COD_BLOCO = $qrBuscaFAQ[COD_BLOCO]
											   AND COD_TERMOS = '$qrBuscaFAQ[COD_TERMO]'";
						// echo($sqlChk);
						$arrayChk = mysqli_query(connTemp($cod_empresa, ''), $sqlChk);

						$chkTermo = "";

						if (mysqli_num_rows($arrayChk) == 1) {
							$chkTermo = "checked";
						}

						$sqlTermos = "SELECT * FROM TERMOS_EMPRESA
												  WHERE COD_EMPRESA = $cod_empresa
												  AND COD_TERMO IN($qrBuscaFAQ[COD_TERMO])";

						// fnEscreve($sqlTermos);

						$arrayTermos = mysqli_query(connTemp($cod_empresa, ''), $sqlTermos);

						$des_bloco = $qrBuscaFAQ['DES_BLOCO'];

						while ($qrTermos = mysqli_fetch_assoc($arrayTermos)) {
							// fnEscreve(strtoupper($qrTermos['ABV_TERMO']));

							$des_bloco = str_replace(
								"<#" . strtoupper($qrTermos['ABV_TERMO']) . ">",
								'
																	</label>
																		
																			<a class="addBox f16 text-success" 
																			   data-url="termos.do?id=' . fnEncode($cod_empresa) . '&idt=' . fnEncode($qrTermos["COD_TERMO"]) . '&pop=true&rnd=' . rand() . '" 
																			   data-title="' . $qrTermos['NOM_TERMO'] . '"
																			   style="cursor:pointer;">
																			   ' . $qrTermos['ABV_TERMO'] . '
																			</a>
																		
																  	<label class="f16" for="TERMOS_' . $qrBuscaFAQ["COD_BLOCO"] . '">
																',
								$des_bloco
							);
						}

					?>

						<div class="form-group">
							<div class="col-xs-12">
								<div class="col-xs-1" style="padding-left:0; padding-right: 0;">
									<input type="checkbox" name="TERMOS_<?= $qrBuscaFAQ["COD_BLOCO"] ?>" id="TERMOS_<?= $qrBuscaFAQ["COD_BLOCO"] ?>" style="width: 18px; height: 18px;" <?= $obrigaChk ?> <?= $chkTermo ?>>
									<label class="<?= $obrigaChk ?>"></label>
								</div>
								<div class="col-xs-10" style="padding-left:0; padding-right: 0;">
									<label class="f16" for="TERMOS_<?= $qrBuscaFAQ["COD_BLOCO"] ?>">
										&nbsp;<?= $des_bloco ?>
									</label>
								</div>
							</div>
							<div class="help-block with-errors"></div>
							<div class="push10"></div>
							<div class="push5"></div>
						</div>

				<?php

						$count++;
					}
				}

				?>

			</div>

		<?php }
		?>


		<div id="camposToken">

			<div class="col-md-12 col-xs-12 text-left" id="erroTkn" style="display: none;">

				<div class="alert alert-danger" role="alert">
					<a type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
					Token inválido.
				</div>

			</div>

			<div class="col-md-8 col-xs-12 text-left p-r-0">
				<div class="form-group">
					<!-- <label for="inputName" class="control-label required">Token</label> -->
					<input type="<?= $type ?>" placeholder="Digite o token" name="DES_TOKEN" id="DES_TOKEN" value="" maxlength="<?= $qtd_chartkn ?>" class="form-control input-sm" style="height:43px; border-radius:0 3px 3px 0;">
					<div class="help-block with-errors"></div>
				</div>
			</div>

			<div class="col-md-4 col-xs-12 p-l-0">
				<!-- <label>&nbsp;</label> -->
				<a style="width: 100%; border-radius: 0!important;  height:43px; margin-top: 0px;" class="btn btn-success btn-sm f18" onclick='ajxValidaTkn()'>Clique aqui para validar o token</a>
			</div>

		</div>

		<div class="push20"></div>

		<script language=javascript>
			$(".chosen-select-deselect").chosen({
				allow_single_deselect: true
			});
		</script>




<?php

		break;

	case "VALTKNCAD":

		include_once '../totem/funWS/GeraToken.php';

		$des_token = fnLimpaCampo(fnLimpaDoc($_POST['DES_TOKEN']));
		$nom_cliente = fnLimpaCampo($_POST['NOM_USUARIO']);
		$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CELULAR']));
		$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CGCECPF']));

		if ($num_celular == "") {
			$num_celular = fnLimpaCampo(fnLimpaDoc(fnDecode($_POST['CAD_NUM_CELULAR'])));
		}

		if ($num_cgcecpf == "") {
			if (is_numeric(fnLimpaDoc(fnDecode($_POST['CAD_NUM_CGCECPF'])))) {
				$num_cgcecpf = fnLimpaCampo(fnLimpaDoc(fnDecode($_POST['CAD_NUM_CGCECPF'])));
			} else {
				$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['CAD_NUM_CGCECPF']));
			}
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

		$urltotem = $log_usuario . ';'
			. $des_senhaus . ';'
			. $idlojaKey . ';'
			. $idmaquinaKey . ';'
			. $cod_empresa . ';'
			. $codvendedorKey . ';'
			. $nomevendedorKey;

		$arrayCampos = explode(";", $urltotem);

		$dadosenvio = array(
			'tipoGeracao' => '1',
			'token' => "$des_token",
			'celular' => "$num_celular",
			'cpf' => "$num_cgcecpf"
		);

		$retornoEnvio = ValidaToken($dadosenvio, $arrayCampos);

		// echo '<pre>';
		// print_r($dadosenvio);
		// print_r($retornoEnvio);
		// echo '</pre>';
		// exit();

		// if ($num_cgcecpf == '598.287.690-93') {
		// 	echo $sql;
		// 	echo '<br>';
		// 	echo '<pre>';
		// 	print_r($dadosenvio);
		// 	echo '</pre>';
		// 	echo '<pre>';
		// 	print_r($retornoEnvio);
		// 	echo '</pre>';
		// 	exit();
		// }

		$cod_envio = $retornoEnvio["body"]["envelope"]["body"]["validatokenresponse"]["retornatoken"]["coderro"];

		if ($cod_envio == 39) {
			echo "validado";
		} else {
			echo 0;
		}

		break;

	case 'encerrarDq':

		if ($cod_empresa == 19) {

			$cod_cliente = fnLimpaCampoZero(fnDecode($_POST['COD_CLIENTE']));

			$sql = "SELECT DES_EMAIL FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
			$qrEmail = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

			$sqlEmp = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
			$qrEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlEmp));

			$sqlCli = "SELECT NOM_CLIENTE, NUM_CGCECPF, DES_EMAILUS, NUM_CELULAR FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE = $cod_cliente";
			// echo($sqlCli);
			$arrayCli = mysqli_query(connTemp($cod_empresa, ''), $sqlCli);
			$qrCli = mysqli_fetch_assoc($arrayCli);

			include "../_system/EMAIL/PHPMailer/PHPMailerAutoload.php";
			include '../externo/email/envio_sac.php';

			$texto = 'DATA DA SOLICITAÇÃO DA EXCLUSÃO: ' . date("d/m/Y H:i:s") .
				'<br>NOME DO CLIENTE: ' . fnLimpaCampo($qrCli['NOM_CLIENTE']) .
				'<br>EMAIL: ' . fnLimpaCampo($qrCli['DES_EMAILUS']) .
				'<br>CELULAR: ' . fnLimpaCampo($qrCli['NUM_CELULAR']) .
				'<br>CPF : ' . fnLimpaCampo($qrCli['NUM_CGCECPF']);

			// echo $qrEmp[NOM_FANTASI];

			$email['email1'] = $qrEmail["DES_EMAIL"];
			$email['email5'] = 'coordenacaoti@markafidelizacao.com.br';

			$retorno = fnsacmail(
				$email,
				'Suporte Marka',
				"<html>" . $texto . "</html>",
				"EXCLUSÃO DE CONTA - APP",
				"APP $qrEmp[NOM_FANTASI]",
				$connAdm->connAdm(),
				connTemp($cod_empresa, ""),
				$cod_empresa
			);

			print_r($retorno);
		}

		break;

	default:

		$cod_cliente = fnLimpaCampoZero(fnDecode($_POST['COD_CLIENTE']));

		if ($cod_empresa == 19) {

			$log_placa = "INSERT INTO veiculos_exec(  COD_VEICULOS,
														COD_EXTERNO,
														DAT_CADASTR,
														COD_CLIENTE,
														COD_CLIENTE_EXT,
														COD_MARCA,
														COD_EXTMARCA,
														COD_MODELO,
														COD_EXTMODE,
														DES_PLACA,
														DAT_ALTERAC,
														COD_USUALTE,
														COD_EMPRESA,
														LOG_ATUALIZ,
														DAT_EXCLUSA
													) 
													SELECT  COD_VEICULOS,
					                                        COD_EXTERNO,
					                                        DAT_CADASTR,
					                                        COD_CLIENTE,
					                                        COD_CLIENTE_EXT,
					                                        COD_MARCA,
					                                        COD_EXTMARCA,
					                                        COD_MODELO,
					                                        COD_EXTMODE,
					                                        DES_PLACA,
					                                        DAT_ALTERAC,
					                                        COD_USUALTE,
					                                        COD_EMPRESA,
					                                        LOG_ATUALIZ,
					                                        NOW() 
                from veiculos WHERE COD_CLIENTE = $cod_cliente AND COD_EMPRESA = $cod_empresa;";
			$Rslog_placa = mysqli_query(connTemp($cod_empresa, ''), $log_placa);

			$sql = "DELETE FROM VEICULOS WHERE COD_CLIENTE = $cod_cliente AND COD_EMPRESA = $cod_empresa";
			mysqli_query(connTemp($cod_empresa, ''), $sql);
		}

		$sql = "CALL `SP_EXCLUI_CLIENTES`($cod_cliente, $cod_empresa, '9998', 'exc', 2)";
		// fnEscreve($sql);
		mysqli_query(connTemp($cod_empresa, ''), $sql);
		break;
}

?>