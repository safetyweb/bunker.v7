<?php

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

//adicionado por Lucas referente ao chamado 6045 controle de data de nascimento
$log_dat_nascime = $qrCampos['LOG_DAT_NASCIME'];
if ($log_dat_nascime == 'S') {

	$sql = "SELECT * FROM controle_alterac_cli WHERE COD_EMPRESA = '$cod_empresa' AND NUM_CGCECPF = '$cpf'";
	$array = mysqli_query(connTemp($cod_empresa, ''), $sql);
	$qrResult = mysqli_fetch_assoc($array);
	$qtd_alterac = $qrResult['QTD_ALTERAC'];

	if ($qtd_alterac >= 1) {
		$per_altera_dat = "disabled";
		$msg_altera_dat = "Esse campo já foi alterado e não permite novas alterações, consulte o gestor de sua empresa";
	} else {
		$per_altera_dat = "";
		$msg_altera_dat = "";
	}
}

?>
<style type="text/css">
	#corpoForm {

		width: 100% !important;
		margin: 0 !important;
		padding: 0 !important;
	}

	#caixaForm {
		overflow: auto;
	}

	#caixaImg,
	#caixaForm {
		height: 100vh;
	}

	#caixaImg {
		background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_img; ?>') no-repeat center center;
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
			/*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; */
			background: #fff;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			overflow: auto !important;
		}

		#corpoForm {
			width: unset !important;
		}

		#caixaImg,
		#caixaForm {
			height: unset;
		}

		#caixaImg {
			background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
			-webkit-background-size: 100% 100%;
			height: 360px;
		}

	}

	/* (320x480) Smartphone, Portrait */
	@media only screen and (device-width: 320px) and (orientation: portrait) {
		body {
			/*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; */
			background: #fff;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			overflow: auto !important;
		}

		#corpoForm {
			width: unset !important;
		}

		#caixaImg,
		#caixaForm {
			height: unset;
		}

		#caixaImg {
			background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
			-webkit-background-size: 100% 100%;
			height: 360px;
		}

	}

	/* (320x480) Smartphone, Landscape */
	@media only screen and (device-width: 480px) and (orientation: landscape) {
		body {
			/*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; */
			background: #fff;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}

	}

	/* (1024x768) iPad 1 & 2, Landscape */
	@media only screen and (min-device-width: 768px) and (max-device-width: 1367px) and (orientation: landscape) {
		body {
			/*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; */
			background: #fff;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}



		.navbar img {
			margin-top: 0;
		}

		#caixaImg {
			padding: 0;
		}

	}

	/* (1280x800) Tablets, Portrait */
	@media only screen and (max-width: 800px) and (orientation : portrait) {
		body {
			/*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat bottom fixed; */
			background: #fff;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: 103%;
		}

		.navbar img {
			margin-top: -10px;
		}

		#corpoForm {
			width: unset !important;
		}

		#caixaImg,
		#caixaForm {
			height: unset;
		}

		#caixaImg {
			background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
			-webkit-background-size: 100% 100%;
			height: 360px;
		}

	}

	/* (768x1024) iPad 1 & 2, Portrait */
	@media only screen and (max-width: 768px) and (orientation : portrait) {
		body {
			/*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; */
			background: #fff;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			overflow: auto !important;
		}



		.navbar img {
			margin-top: 0;
		}

		#corpoForm {
			width: unset !important;
		}

		#caixaImg,
		#caixaForm {
			height: unset;
		}

		#caixaImg {
			background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
			-webkit-background-size: 100% 100%;
			height: 360px;
		}

	}

	/* (2048x1536) iPad 3 and Desktops*/
	@media only screen and (min-device-width: 1536px) and (max-device-width: 2048px) {
		body {
			/*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; */
			background: #fff;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}



		.navbar img {
			margin-top: 0;
		}

		#caixaImg {
			background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_img_g; ?>') no-repeat center center;
			padding: 0;
		}

	}

	@media only screen and (min-device-width: 1100px) and (orientation : portrait) {
		body {
			/*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; */
			background: #fff;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			overflow: auto !important;
		}



		.navbar img {
			margin-top: 0;
		}

		#corpoForm {
			width: unset !important;
		}

		#caixaImg,
		#caixaForm {
			height: unset;
		}

		#caixaImg {
			background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
			-webkit-background-size: 100% 100%;
			height: 360px;
		}

	}

	@media (max-height: 824px) and (max-width: 416px) {
		body {
			/*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; */
			background: #fff;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			overflow: auto !important;
		}

		#corpoForm {
			width: unset !important;
		}

		#caixaImg,
		#caixaForm {
			height: unset;
		}

		#caixaImg {
			background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
			-webkit-background-size: 100% 100%;
			height: 360px;
		}
	}

	/* (320x480) iPhone (Original, 3G, 3GS) */
	@media (max-device-width: 737px) and (max-height: 416px) {
		body {
			/*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; */
			background: #fff;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}

		#caixaImg {
			padding: 0;
		}


	}

	.input-sm,
	.chosen-single {
		font-size: 20px !important;
	}

	.logo-center {
		margin-left: auto;
		margin-right: auto;
	}
</style>


<link rel="stylesheet" type="text/css" href="https://bunker.mk/css/fontawesome-pro-5.13.0-web/css/all.min.css" />

<div class="col-md-6 col-xs-12" id="caixaImg">
	<?php if ($des_img_g != "") { ?>
		<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?= $des_img_g ?>" class="img-responsive desktop" style="margin-left: auto; margin-right: auto;">
	<?php } ?>
	<?php if ($des_img != "") { ?>
		<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?= $des_img ?>" class="img-responsive tablet" style="margin-left: auto; margin-right: auto;">
	<?php } ?>
	<?php if ($des_imgmob != "") { ?>
		<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?= $des_imgmob ?>" class="img-responsive mobile" style="margin-left: auto; margin-right: auto;">
	<?php } ?>
</div>

<div class="col-md-6 col-xs-12" id="caixaForm" style="background-color: #FFF;">

	<div class="push20"></div>

	<div class="col-xs-12" style="display: <?= $mostraMsgAniv ?>">

		<div class="col-md-12 alert-warning top30 bottom30" role="alert" id="msgRetorno">
			<div class="push20"></div>
			<span style="font-size: 26px; padding: 0 30px;"><?php echo $msgsbtr; ?></span>
			<div class="push20"></div>
		</div>

	</div>

	<div class="col-xs-12" style="display: <?= $mostraMsgCad ?>">

		<div class="alert-warning top30 bottom30" role="alert" id="msgRetorno">
			<div class="push20"></div>
			<span style="font-size: 26px; padding: 0 30px;"><?php echo $msgsbtr; ?></span>
			<div class="push20"></div>
		</div>

	</div>


	<?php

	if ($cod_empresa == 19 && $cod_cliente != 0) {

	?>

		<div class="row" style="padding-left: 15px; padding-right: 15px;">
			<div class="col-xs-12 text-center" style="background-color: #F39C12; color: #fff; border-radius: 15px; padding-top: 15px; padding-bottom: 5px;">
				<p class="f14">Para alterar as informações dos campos bloqueados, favor entrar em contato pelo WhatsApp (11) 3087-9697 ou pelo "Fale Conosco" do App</p>
			</div>
			<div class="push20"></div>
		</div>

		<?php

	}

	$andOpc = "AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'OPC'";

	if ($cod_cliente != 0) {
		$andOpc = "";
	}

	if ($cod_cliente == 0 && $log_cadtoken == 'S') {

		$camposIniciais = "AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG = 'TKN'";
		// $mostraSenha = 0;

	} else {

		$camposIniciais = "AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'KEY'
								   AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'CAD'
								   AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'TKN'
								   $andOpc";
	}

	$sqlCampos = "SELECT NOM_CAMPOOBG, 
								 NOM_CAMPOOBG, 
								 DES_CAMPOOBG, 
								 MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG AS CAT_CAMPO, 
								 INTEGRA_CAMPOOBG.TIP_CAMPOOBG AS TIPO_DADO,
								 (SELECT COUNT(DISTINCT MCI.TIP_CAMPOOBG) 
									FROM matriz_campo_integracao MCI
									WHERE MCI.TIP_CAMPOOBG IN('OBG','OPC') 
									AND MCI.COD_CAMPOOBG = MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG
									AND MCI.COD_EMPRESA = $cod_empresa) AS OBRIGATORIO,
								 COL_MD, 
								 COL_XS, 
								 CLASSE_INPUT, 
								 CLASSE_DIV 
							FROM MATRIZ_CAMPO_INTEGRACAO                         
							LEFT JOIN INTEGRA_CAMPOOBG ON INTEGRA_CAMPOOBG.COD_CAMPOOBG=MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG                         
							WHERE MATRIZ_CAMPO_INTEGRACAO.COD_EMPRESA = $cod_empresa
							AND MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG != 24
							$camposIniciais
							ORDER BY NUM_ORDENAC ASC, COL_MD ASC, COL_XS ASC, MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG, MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG ASC";

	$arrayCampos = mysqli_query($connAdm->connAdm(), $sqlCampos);

	$nroCampos = mysqli_num_rows($arrayCampos);

	// echo($sqlCampos);

	$lastField = "";

	while ($qrCampos = mysqli_fetch_assoc($arrayCampos)) {

		// echo "<pre>";
		// print_r($qrCampos);
		// echo "</pre>";

		$colMd = $qrCampos['COL_MD'];
		$colXs = $qrCampos['COL_XS'];
		$dataError = "";

		$required = "";
		// echo "$qrCampos[NOM_CAMPOOBG]: $qrCampos[CAT_CAMPO] - $required<br>";

		if ($lastField == "") {
			$lastField = $qrCampos['NOM_CAMPOOBG'];
		} else if ($lastField == $qrCampos['NOM_CAMPOOBG']) {
			continue;
		} else {
			$lastField = $qrCampos['NOM_CAMPOOBG'];
		}

		if ($qrCampos['OBRIGATORIO'] > 0) {
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

		$helpCampo = "";

		switch ($qrCampos['DES_CAMPOOBG']) {

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

				if ($cod_empresa == 19) {
					$dado = fnMascaraCampo($buscaconsumidor['datanascimento']);
				} else {
					$dado = $buscaconsumidor['datanascimento'];
				}

				$dadoEncrypt = fnEncode($buscaconsumidor['datanascimento']);

				// $dado = $buscaconsumidor['datanascimento'];
				// $dado = fnMascaraCampo($buscaconsumidor['datanascimento']);
				// $dadoEncrypt = fnEncode($buscaconsumidor['datanascimento']);
				// $dadoEncrypt = "";

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

		switch ($qrCampos['TIPO_DADO']) {

			case 'Data':

				if ($qrCampos['DES_CAMPOOBG'] == "DAT_NASCIME") {
					$calculaData = "calculaData";
				}
				$type = "tel";
				$mask = "data";
				$pattern = 'pattern="(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/(19|20)\d{2}"';

				if ($dado != "" && $cod_cliente != 0 && $cod_empresa == 19) {
					$type = "text";
					$mask = "";
					$calculaData = "";
					$pattern = "";
				}

		?>
				<div class="col-md-<?= $colMd ?> col-xs-<?= $colXs ?>" id="blocoNascimento">
					<div class="form-group">
						<label>&nbsp;</label>
						<label for="inputName" class="control-label <?= $required ?>"><?= $qrCampos['NOM_CAMPOOBG'] ?></label>
						<input type="<?= $type ?>" placeholder="DD/MM/AAAA" value="<?= $dado ?>" class="form-control input-sm input-hg <?= $qrCampos['CLASSE_INPUT'] ?> <?= $mask ?> <?= $calculaData ?>" name="<?= $qrCampos['DES_CAMPOOBG'] ?>" id="<?= $qrCampos['DES_CAMPOOBG'] ?>" maxlenght="10" data-minlength="10" data-minlength-error="O formato da data deve ser DD/MM/AAAA" <?= $per_altera_dat ?> <?= $dataError ?> <?= $pattern ?> data-pattern-error="Formato inválido" <?= $required ?>>
						<input type="hidden" value="<?= $dadoEncrypt ?>" name="HID_<?= $qrCampos['DES_CAMPOOBG'] ?>" id="HID_<?= $qrCampos['DES_CAMPOOBG'] ?>">
						<div class="help-block with-errors"><?= $msg_altera_dat ?></div>
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
						<label for="inputName" class="control-label <?= $required ?>"><?= $qrCampos['NOM_CAMPOOBG'] ?></label>
						<input type="email" value="<?= $dado ?>" class="form-control input-sm input-hg <?= $qrCampos['CLASSE_INPUT'] ?>" name="<?= $qrCampos['DES_CAMPOOBG'] ?>" id="<?= $qrCampos['DES_CAMPOOBG'] ?>" <?= $dataError ?> <?= $required ?>>
						<input type="hidden" value="<?= $dadoEncrypt ?>" name="HID_<?= $qrCampos['DES_CAMPOOBG'] ?>" id="HID_<?= $qrCampos['DES_CAMPOOBG'] ?>">
						<div class="help-block with-errors"><?= $helpCampo ?></div>
					</div>
				</div>

				<?php

				break;

			case 'numeric':

				if ($qrCampos['DES_CAMPOOBG'] == "COD_SEXOPES") {

				?>
					<div class="col-md-<?= $colMd ?> col-xs-<?= $colXs ?>" id="blocoSexo">
						<div class="form-group">
							<label>&nbsp;</label>
							<label for="inputName" class="control-label <?= $required ?>">Sexo</label>
							<select data-placeholder="Selecione o sexo" name="COD_SEXOPES" id="COD_SEXOPES" class="chosen-select-deselect input-sm <?= $qrCampos['CLASSE_INPUT'] ?>" <?= $required ?>>
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

				} else if ($qrCampos['DES_CAMPOOBG'] == "COD_PROFISS") {

				?>
					<div class="col-md-<?= $colMd ?> col-xs-<?= $colXs ?>">
						<div class="form-group">
							<label>&nbsp;</label>
							<label for="inputName" class="control-label <?= $required ?>">Profissão </label>
							<select data-placeholder="Selecione a profissão" name="COD_PROFISS" id="COD_PROFISS" class="chosen-select-deselect input-sm <?= $qrCampos['CLASSE_INPUT'] ?>" <?= $required ?>>
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

				} else if ($qrCampos['DES_CAMPOOBG'] == "COD_ESTACIV") {

				?>
					<div class="col-md-<?= $colMd ?> col-xs-<?= $colXs ?>">
						<div class="form-group">
							<label>&nbsp;</label>
							<label for="inputName" class="control-label <?= $required ?>">Estado Civil</label>
							<select data-placeholder="Selecione um estado civil" name="COD_ESTACIV" id="COD_ESTACIV" class="chosen-select-deselect input-sm <?= $qrCampos['CLASSE_INPUT'] ?>" <?= $required ?>>
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

					if ($qrCampos['DES_CAMPOOBG'] == "NUM_CGCECPF") {
						$nomeCampo = "CPF/CNPJ";
						$mask = "cpfcnpj";
						$type = "tel";
					} else {
						$nomeCampo = $qrCampos['NOM_CAMPOOBG'];
						$mask = "";
					}

				?>
					<div class="col-md-<?= $colMd ?> col-xs-<?= $colXs ?>">
						<div class="form-group">
							<label>&nbsp;</label>
							<label for="inputName" class="control-label <?= $required ?>"><?= $nomeCampo ?></label>
							<input type="<?= $type ?>" value="<?= $dado ?>" class="form-control input-sm input-hg <?= $qrCampos['CLASSE_INPUT'] ?> <?= $mask ?>" name="<?= $qrCampos['DES_CAMPOOBG'] ?>" id="<?= $qrCampos['DES_CAMPOOBG'] ?>" <?= $dataError ?> <?= $required ?>>
							<div class="help-block with-errors"><?= $helpCampo ?></div>
						</div>
					</div>

				<?php

				}

				break;

			default:

				$type = "text";
				$validacao = "";

				if ($qrCampos['DES_CAMPOOBG'] == "NUM_CGCECPF") {
					$nomeCampo = "CPF/CNPJ";
					$mask = "cpfcnpj";
					$type = "tel";
				} else if ($qrCampos['DES_CAMPOOBG'] == "NUM_CELULAR") {
					$type = "tel";
					$validacao = 'data-minlength="15" data-minlength-error="Número incompleto" pattern="(\([1-9]{2}\))\s([9]{1})([0-9]{4})-([0-9]{4})" data-pattern-error="Formato inválido"';
				} else if ($qrCampos['DES_CAMPOOBG'] == "NUM_TELEFONE" || $qrCampos['DES_CAMPOOBG'] == "NUM_CEPOZOF") {
					$type = "tel";
				} else {
					$nomeCampo = $qrCampos['NOM_CAMPOOBG'];
					$mask = "";
				}

				if ($qrCampos['DES_CAMPOOBG'] == "COD_ESTADOF") {

				?>
					<div class="col-md-<?= $colMd ?> col-xs-<?= $colXs ?>">
						<div class="form-group">
							<label>&nbsp;</label>
							<label for="inputName" class="control-label <?= $required ?>"><?= $nomeCampo ?></label>
							<select data-placeholder="Selecione um estado" name="COD_ESTADOF" id="COD_ESTADOF" class="chosen-select-deselect input-sm <?= $qrCampos['CLASSE_INPUT'] ?>" <?= $dataError ?> <?= $required ?>>
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
							<label for="inputName" class="control-label <?= $required ?>"><?= $qrCampos['NOM_CAMPOOBG'] ?></label>
							<?php
							if ($cod_empresa == 19 && $cod_cliente != 0 && $qrCampos['DES_CAMPOOBG'] == "NUM_CELULAR") {
								$validacao = "";
								$qrCampos['CLASSE_INPUT'] = "";
								$type = "text";
								$dataError = "";
							}
							?>
							<input type="<?= $type ?>" value="<?= $dado ?>" class="form-control input-sm input-hg <?= $qrCampos['CLASSE_INPUT'] ?>" name="<?= $qrCampos['DES_CAMPOOBG'] ?>" id="<?= $qrCampos['DES_CAMPOOBG'] ?>" <?= $dataError ?> <?= $validacao ?> <?= $required ?>>
							<input type="hidden" value="<?= $dadoEncrypt ?>" name="HID_<?= $qrCampos['DES_CAMPOOBG'] ?>" id="HID_<?= $qrCampos['DES_CAMPOOBG'] ?>">
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

	if ($cod_empresa == 19 && $cod_cliente == 0) {
	?>

		<div class="push"></div>

		<div class="col-md-12 col-xs-12">
			<div class="form-group">
				<label>&nbsp;</label>
				<label for="inputName" class="control-label">Código de Amigo Indicador</label>
				<input type="tel" value="" class="form-control input-sm input-hg" name="COD_INDICAD" id="COD_INDICAD">
				<input type="hidden" value="" name="HID_COD_INDICAD" id="HID_COD_INDICAD">
			</div>
		</div>

	<?php
	} else {
	?>
		<input type="hidden" name="COD_INDICAD" id="COD_INDICAD" value="<?= $cod_indicad ?>">
		<?php
	}

	if ($mostraSenha == 1 && $log_cadtoken == 'N') {

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
							$sql = "select COD_UNIVEND, NOM_FANTASI from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' AND LOG_ESTATUS = 'S' AND DAT_EXCLUSA IS NULL order by NOM_FANTASI ";

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

		$senha = $buscaconsumidor['senha'];

		if ($senha == "0") {
			$senha = "";
		}

		// if ($cpf == "39648555885") {
		// 	echo $senha;
		// }

		if ($senha == "") {

			$wizardSenha = "int";

			if ($tip_senha != "2") {
				$wizardSenha = "pr-password";
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
					<input type="password" placeholder="Confirmar Senha" style="font-size: 36px;" class="form-control input-hg input-lg text-center <?= $wizardSenha ?> <?= $classeSenha ?>" data-minlength-error="Mínimo de <?= $min_senha ?> caracteres." data-maxlength-error="Máximo de <?= $max_senha ?> caracteres." name="DES_SENHAUS_CONF" id="DES_SENHAUS_CONF" minlength="<?= $min_senha ?>" maxlength="<?= $max_senha ?>" data-match="#DES_SENHAUS" data-required-error="Campo obrigatório" data-match-error="Senhas diferentes" required>
					<div class="help-block with-errors f12"></div>
				</div>
			</div>

		<?php

		} else {
		?>
			<input type="hidden" name="DES_SENHAUS" id="DES_SENHAUS_HD" value="<?= fnEncode($senha) ?>">
			<input type="hidden" name="DES_SENHAUS_ENC" id="DES_SENHAUS_ENC" value="<?= fnEncode($senha) ?>">
		<?php
		}
	}

	if ($atendente == 1 && $log_cadtoken == 'N') {
		?>

		<div class="push"></div>

		<div class="col-md-12 col-xs-12">
			<div class="form-group">
				<label>&nbsp;</label>
				<label for="inputName" class="control-label required">Atendente de Cadastro</label>
				<select data-placeholder="Selecione o atendente" name="COD_ATENDENTE" id="COD_ATENDENTE" class="chosen-select-deselect input-sm" required>
					<option value=""></option>
					<?php
					$sql = "SELECT COD_EXTERNO, NOM_USUARIO FROM usuarios WHERE cod_tpusuario IN (11, 7) and cod_empresa = $cod_empresa AND FIND_IN_SET($cod_univend,COD_UNIVEND) AND cod_exclusa=0 AND LOG_ESTATUS = 'S' ORDER BY NOM_USUARIO";
					$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

					// fnEscreve($sql);

					while ($qrListaProfi = mysqli_fetch_assoc($arrayQuery)) {
						echo "
										  <option value='" . $qrListaProfi['COD_EXTERNO'] . "'>" . $qrListaProfi['NOM_USUARIO'] . "</option> 
										";
					}
					?>
				</select>
				<script>
					if (<?= $buscaconsumidor['codatendente'] ?> != 0 && <?= $buscaconsumidor['codatendente'] ?> != "") {
						$('#COD_ATENDENTE').val("<?= $buscaconsumidor['codatendente'] ?>").trigger('chosen:updated');
						if ($('#COD_ATENDENTE').val() != "") {
							$('#COD_ATENDENTE').prop('disabled', true);
						}
					}
				</script>
			</div>
		</div>

		<div class="push20"></div>

	<?php
	}
	?>



	<div class="push20"></div>

	<?php



	$displayTermos = "block";


	$mostraLgpd = 'N';

	// echo "$log_lgpd<br>";
	// echo "$log_cadtoken<br>";
	// echo "$qrControle[TXT_ACEITE]<br>";

	if ($log_lgpd == 'S' && $log_cadtoken == 'N' || $log_lgpd == 'S' && $cod_cliente != 0 && $atendente != 1) {
		$mostraLgpd = 'S';
	}


	if ($mostraLgpd == 'S') {



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
				fnEscreve($sql);
				$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

				$count = 0;
				$tipo = "";
				while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)) {

					if ($qrBuscaFAQ['LOG_OBRIGA'] == "S") {
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
																   data-url="termos.do?id=' . fnEncode($cod_empresa) . '&idt=' . fnEncode($qrTermos['COD_TERMO']) . '&pop=true&rnd=' . rand() . '" 
																   data-title="' . $qrTermos['NOM_TERMO'] . '"
																   style="cursor:pointer;">
																   ' . $qrTermos['ABV_TERMO'] . '
																</a>
															
													  	<label class="f16" for="TERMOS_' . $qrBuscaFAQ['COD_BLOCO'] . '">
													',
							$des_bloco
						);
					}

			?>

					<div class="form-group">
						<div class="col-xs-12">
							<div class="col-xs-1" style="padding-left:0; padding-right: 0;">
								<input type="checkbox" name="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" id="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" style="width: 18px; height: 18px;" <?= $obrigaChk ?> <?= $chkTermo ?>>
								<label class="<?= $obrigaChk ?>"></label>
							</div>
							<div class="col-xs-10" style="padding-left:0; padding-right: 0;">
								<label class="f16" for="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>">
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

					if ($qrBuscaFAQ['LOG_OBRIGA'] == "S") {
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
																   data-url="termos.do?id=' . fnEncode($cod_empresa) . '&idt=' . fnEncode($qrTermos['COD_TERMO']) . '&pop=true&rnd=' . rand() . '" 
																   data-title="' . $qrTermos['NOM_TERMO'] . '"
																   style="cursor:pointer;">
																   ' . $qrTermos['ABV_TERMO'] . '
																</a>
															
													  	<label class="f16" for="TERMOS_' . $qrBuscaFAQ['COD_BLOCO'] . '">
													',
							$des_bloco
						);
					}

				?>

					<div class="form-group">
						<div class="col-xs-12">
							<div class="col-xs-1" style="padding-left:0; padding-right: 0;">
								<input type="checkbox" name="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" id="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" style="width: 18px; height: 18px;" <?= $obrigaChk ?> <?= $chkTermo ?>>
								<label class="<?= $obrigaChk ?>"></label>
							</div>
							<div class="col-xs-10" style="padding-left:0; padding-right: 0;">
								<label class="f16" for="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>">
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

					if ($qrBuscaFAQ['LOG_OBRIGA'] == "S") {
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
																   data-url="termos.do?id=' . fnEncode($cod_empresa) . '&idt=' . fnEncode($qrTermos['COD_TERMO']) . '&pop=true&rnd=' . rand() . '" 
																   data-title="' . $qrTermos['NOM_TERMO'] . '"
																   style="cursor:pointer;">
																   ' . $qrTermos['ABV_TERMO'] . '
																</a>
															
													  	<label class="f16" for="TERMOS_' . $qrBuscaFAQ['COD_BLOCO'] . '">
													',
							$des_bloco
						);
					}

				?>

					<div class="form-group">
						<div class="col-xs-12">
							<div class="col-xs-1" style="padding-left:0; padding-right: 0;">
								<input type="checkbox" name="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" id="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" style="width: 18px; height: 18px;" <?= $obrigaChk ?> <?= $chkTermo ?>>
								<label class="<?= $obrigaChk ?>"></label>
							</div>
							<div class="col-xs-10" style="padding-left:0; padding-right: 0;">
								<label class="f16" for="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>">
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

	<?php } ?>

	<div class="push20"></div>

	<div class="col-md-12 col-xs-12">

		<?php

		if ($cod_cliente == 0) {

			$log_novocli = "S";

		?>

			<?php

			if ($log_cadtoken == 'S') {

				if ($nroCampos > 0) {

			?>

					<div id="relatorioToken">
						<a href="javascript:void(0)" class="btn btn-primary btn-lg btn-block" onclick='ajxToken()'><i class="fal fa-user-unlock" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp; Enviar Token</a>
					</div>

					<div id="btnCad" style="display: none;">
						<button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-lg btn-block getBtn" tabindex="5" style="color: #fff;">Aceitar Termos e Cadastrar</button>
					</div>

				<?php
				} else {

				?>

					<div class="col-md-12 col-xs-12 text-left">

						<div class="alert alert-danger" role="alert">
							<a type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
							Os campos <b>Iniciais/Token</b> não foram configurados na matriz. Contate o suporte.
						</div>

					</div>

				<?php

				}
			} else {

				?>

				<button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-lg btn-block getBtn" tabindex="5" style="color: #fff;">Aceitar Termos e Cadastrar</button>

			<?php } ?>

			<?php

		} else {

			$log_novocli = "N";
			$txtDescad = "Descadastrar-se";

			if ($cod_empresa == 77) {
				$txtDescad = "Excluir Cadastro";
			}

			if ($log_cadtoken == 'S' && $des_token == 0) {

			?>

				<div id="relatorioToken">
					<a href="javascript:void(0)" class="btn btn-primary btn-lg btn-block" onclick='ajxTokenAlt()'><i class="fal fa-user-unlock" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp; Enviar Token</a>
				</div>

				<div id="btnCad" style="display: none;">
					<button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-lg btn-block getBtn" tabindex="5" style="color: #fff;"><i class="fal fa-user-edit" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp; Aceitar Termos e Atualizar Cadastro</button>
				</div>

			<?php

			} else {

			?>

				<button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-lg btn-block getBtn" tabindex="5" style="color: #fff;"><i class="fal fa-user-edit" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp; Atualizar Cadastro</button>
				<?php
				if ($atendente != 1 && $cod_empresa != 0 && $cod_empresa != 19) {
				?>
					<div class="push20"></div>
					<div class="col-md-12 text-center">
						<a href="javascript:void(0)" name="EXC" id="EXC" tabindex="5" onclick='ajxDescadastra("<?= fnEncode($cod_cliente) ?>")' style="font-size: 16px;"><?= $txtDescad ?></a>
					</div>
					<?php
				} else if ($cod_empresa == 19) {

					if ($isApp) {

					?>
						<div class="push20"></div>
						<div class="col-md-12 text-center">
							<a href="formularioDescadastro.php?key=<?= $_GET["key"] ?>&idU=<?= $_GET["idU"] ?>&t=<?= $rand ?>" name="EXC" id="EXC" tabindex="5" style="font-size: 16px;"><?= $txtDescad ?></a>
						</div>
					<?php
					} else {
					?>
						<div class="push20"></div>
						<div class="col-md-12 text-center">
							<a href="javascript:void(0)" name="EXC" id="EXC" tabindex="5" onclick='ajxDescadastraDq("<?= fnEncode($cod_cliente) ?>")' style="font-size: 16px;"><?= $txtDescad ?></a>
						</div>
				<?php
					}
				}
				?>

		<?php
			}
		}

		?>

		<div class="push100"></div>

	</div>

	<div class="push100"></div>
	<div class="push100"></div>

	<div id="relatorioConteudo"></div>

</div>


<input type="hidden" name="opcao" id="opcao" value="">

<?php if ($isApp) { ?>

	<input type="hidden" name="KEY_DES_TOKEN" id="KEY_DES_TOKEN" value="">
	<input type="hidden" name="KEY_NUM_CARTAO" id="KEY_NUM_CARTAO" value="<?= fnEncode($k_num_cartao) ?>">
	<input type="hidden" name="KEY_NUM_CELULAR" id="KEY_NUM_CELULAR" value="<?= fnEncode($k_num_celular) ?>">
	<input type="hidden" name="KEY_COD_EXTERNO" id="KEY_COD_EXTERNO" value="<?= fnEncode($k_cod_externo) ?>">
	<input type="hidden" name="KEY_NUM_CGCECPF" id="KEY_NUM_CGCECPF" value="<?= fnEncode($k_num_cgcecpf) ?>">
	<input type="hidden" name="KEY_DAT_NASCIME" id="KEY_DAT_NASCIME" value="<?= fnEncode($k_dat_nascime) ?>">
	<input type="hidden" name="KEY_DES_EMAILUS" id="KEY_DES_EMAILUS" value="<?= fnEncode($k_des_emailus) ?>">
	<input type="hidden" name="CAD_NOM_CLIENTE" id="CAD_NOM_CLIENTE" value="<?= fnEncode($buscaconsumidor['nome']) ?>">
	<input type="hidden" name="CAD_NUM_CGCECPF" id="CAD_NUM_CGCECPF" value="<?= fnEncode($buscaconsumidor['cpf']) ?>">
	<input type="hidden" name="CAD_COD_SEXOPES" id="CAD_COD_SEXOPES" value="<?= fnEncode($buscaconsumidor['sexo']) ?>">
	<input type="hidden" name="CAD_NUM_CARTAO" id="CAD_NUM_CARTAO" value="<?= fnEncode($buscaconsumidor['cartao']) ?>">
	<input type="hidden" name="CAD_DES_EMAILUS" id="CAD_DES_EMAILUS" value="<?= fnEncode($buscaconsumidor['email']) ?>">
	<input type="hidden" name="CAD_DES_ENDEREC" id="CAD_DES_ENDEREC" value="<?= fnEncode($buscaconsumidor['endereco']) ?>">
	<input type="hidden" name="CAD_NUM_ENDEREC" id="CAD_NUM_ENDEREC" value="<?= fnEncode($buscaconsumidor['numero']) ?>">
	<input type="hidden" name="CAD_DES_BAIRROC" id="CAD_DES_BAIRROC" value="<?= fnEncode($buscaconsumidor['bairro']) ?>">
	<input type="hidden" name="CAD_DES_COMPLEM" id="CAD_DES_COMPLEM" value="<?= fnEncode($buscaconsumidor['complemento']) ?>">
	<input type="hidden" name="CAD_DES_CIDADEC" id="CAD_DES_CIDADEC" value="<?= fnEncode($buscaconsumidor['cidade']) ?>">
	<input type="hidden" name="CAD_COD_ESTADOF" id="CAD_COD_ESTADOF" value="<?= fnEncode($buscaconsumidor['estado']) ?>">
	<input type="hidden" name="CAD_NUM_CEPOZOF" id="CAD_NUM_CEPOZOF" value="<?= fnEncode($buscaconsumidor['cep']) ?>">
	<input type="hidden" name="CAD_DAT_NASCIME" id="CAD_DAT_NASCIME" value="<?= fnEncode($buscaconsumidor['datanascimento']) ?>">
	<input type="hidden" name="CAD_NUM_CELULAR" id="CAD_NUM_CELULAR" value="<?= fnEncode($buscaconsumidor['telcelular']) ?>">
	<input type="hidden" name="CAD_COD_PROFISS" id="CAD_COD_PROFISS" value="<?= fnEncode($buscaconsumidor['profissao']) ?>">
	<input type="hidden" name="CAD_COD_ATENDENTE" id="CAD_COD_ATENDENTE" value="<?= fnEncode($buscaconsumidor['codatendente']) ?>">

<?php } else { ?>

	<input type="hidden" name="KEY_DES_TOKEN" id="KEY_DES_TOKEN" value="">
	<input type="hidden" name="KEY_NUM_CARTAO" id="KEY_NUM_CARTAO" value="<?= $k_num_cartao ?>">
	<input type="hidden" name="KEY_NUM_CELULAR" id="KEY_NUM_CELULAR" value="<?= $k_num_celular ?>">
	<input type="hidden" name="KEY_COD_EXTERNO" id="KEY_COD_EXTERNO" value="<?= $k_cod_externo ?>">
	<input type="hidden" name="KEY_NUM_CGCECPF" id="KEY_NUM_CGCECPF" value="<?= $k_num_cgcecpf ?>">
	<input type="hidden" name="KEY_DAT_NASCIME" id="KEY_DAT_NASCIME" value="<?= $k_dat_nascime ?>">
	<input type="hidden" name="KEY_DES_EMAILUS" id="KEY_DES_EMAILUS" value="<?= $k_des_emailus ?>">
	<input type="hidden" name="CAD_NOM_CLIENTE" id="CAD_NOM_CLIENTE" value="<?= $buscaconsumidor['nome'] ?>">
	<input type="hidden" name="CAD_NUM_CGCECPF" id="CAD_NUM_CGCECPF" value="<?= $buscaconsumidor['cpf'] ?>">
	<input type="hidden" name="CAD_COD_SEXOPES" id="CAD_COD_SEXOPES" value="<?= $buscaconsumidor['sexo'] ?>">
	<input type="hidden" name="CAD_NUM_CARTAO" id="CAD_NUM_CARTAO" value="<?= $buscaconsumidor['cartao'] ?>">
	<input type="hidden" name="CAD_DES_EMAILUS" id="CAD_DES_EMAILUS" value="<?= $buscaconsumidor['email'] ?>">
	<input type="hidden" name="CAD_DES_ENDEREC" id="CAD_DES_ENDEREC" value="<?= $buscaconsumidor['endereco'] ?>">
	<input type="hidden" name="CAD_NUM_ENDEREC" id="CAD_NUM_ENDEREC" value="<?= $buscaconsumidor['numero'] ?>">
	<input type="hidden" name="CAD_DES_BAIRROC" id="CAD_DES_BAIRROC" value="<?= $buscaconsumidor['bairro'] ?>">
	<input type="hidden" name="CAD_DES_COMPLEM" id="CAD_DES_COMPLEM" value="<?= $buscaconsumidor['complemento'] ?>">
	<input type="hidden" name="CAD_DES_CIDADEC" id="CAD_DES_CIDADEC" value="<?= $buscaconsumidor['cidade'] ?>">
	<input type="hidden" name="CAD_COD_ESTADOF" id="CAD_COD_ESTADOF" value="<?= $buscaconsumidor['estado'] ?>">
	<input type="hidden" name="CAD_NUM_CEPOZOF" id="CAD_NUM_CEPOZOF" value="<?= $buscaconsumidor['cep'] ?>">
	<input type="hidden" name="CAD_DAT_NASCIME" id="CAD_DAT_NASCIME" value="<?= $buscaconsumidor['datanascimento'] ?>">
	<input type="hidden" name="CAD_NUM_CELULAR" id="CAD_NUM_CELULAR" value="<?= $buscaconsumidor['telcelular'] ?>">
	<input type="hidden" name="CAD_COD_PROFISS" id="CAD_COD_PROFISS" value="<?= $buscaconsumidor['profissao'] ?>">
	<input type="hidden" name="CAD_COD_ATENDENTE" id="CAD_COD_ATENDENTE" value="<?= $buscaconsumidor['codatendente'] ?>">

<?php } ?>
<input type="hidden" name="CAD_DES_SENHAUS" id="CAD_DES_SENHAUS" value="<?= fnEncode($buscaconsumidor['senha']) ?>">
<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?= fnEncode($cod_cliente) ?>">
<input type="hidden" name="LOG_NOVOCLI" id="LOG_NOVOCLI" value="<?= $log_novocli ?>">
<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">