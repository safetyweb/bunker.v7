<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$cod_id = "";
$cod_quarto = "";
$nom_quarto = "";
$des_imagem = "";
$des_banner = "";
$des_video = "";
$des_quarto = "";
$log_home = "";
$log_banner = "";
$cod_externo = "";
$cod_hotel = "";
$tam_propriedade = "";
$val_efetivo = "";
$qtd_hospedes = 0;
$qtd_quartos = 0;
$qtd_banheiros = 0;
$log_badge = "";
$txt_badge = "";
$cor_badge = "";
$cor_txtbadge = "";
$meta_title = "";
$meta_description = "";
$dat_ini_dest = "";
$dat_fim_dest = "";
$nom_usuario = "";
$cod_usucada = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$insertData = "";
$updateData = "";
$sqlCad = "";
$arrayProc = [];
$cod_erro = "";
$sqlAlt = "";
$arrayAlt = [];
$sqlExc = "";
$arrayExc = [];
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$formBack = "";
$abaAdorai = "";
$abaManutencaoAdorai = "";
$abaUsuario = "";
$arrayHotel = [];
$qrHoteis = "";
$check_status = "";
$check_statusIni = "";
$check_Banner = "";
$check_badge = "";
$des_sac = "";
$qrLista = "";
$destaque = "";
$banner = "";
$sqlDetalhes = "";
$arrayDetalhes = [];
$detalhe = "";
$sqlFotos = "";
$arrayFotos = [];
$fotos = "";


//echo fnDebug('true');

$hashLocal = mt_rand();

$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_id = fnLimpaCampoZero(@$_REQUEST['ID']);
		$cod_quarto = fnLimpaCampoZero(@$_REQUEST['COD_QUARTO']);
		$nom_quarto = fnLimpaCampo(@$_REQUEST['NOM_QUARTO']);
		$des_imagem = fnLimpaCampo(@$_REQUEST['DES_IMAGEM']);
		$des_banner = fnLimpaCampo(@$_REQUEST['DES_BANNER']);
		$des_video = fnLimpaCampo(@$_REQUEST['DES_VIDEO']);
		$des_quarto = fnLimpaCampo(@$_REQUEST['DES_QUARTO']);
		if (empty(@$_REQUEST['LOG_HOME'])) {
			$log_home = 'N';
		} else {
			$log_home = @$_REQUEST['LOG_HOME'];
		}
		if (empty(@$_REQUEST['LOG_BANNER'])) {
			$log_banner = 'N';
		} else {
			$log_banner = @$_REQUEST['LOG_BANNER'];
		}
		$cod_externo = fnLimpaCampoZero(@$_REQUEST['COD_EXTERNO']);
		$cod_hotel = fnLimpaCampoZero(@$_REQUEST['COD_EXTERNO']);
		$tam_propriedade = fnLimpaCampoZero(@$_REQUEST['TAM_PROPRIEDADE']);
		$val_efetivo = fnLimpaCampoZero(fnValorSql(@$_REQUEST['VAL_EFETIVO']));
		$qtd_hospedes = fnLimpaCampoZero(@$_REQUEST['QTD_HOSPEDES']);
		$qtd_quartos = fnLimpaCampoZero(@$_REQUEST['QTD_QUARTOS']);
		$qtd_banheiros = fnLimpaCampoZero(@$_REQUEST['QTD_BANHEIROS']);
		if (empty(@$_REQUEST['LOG_BADGE'])) {
			$log_badge = 'N';
		} else {
			$log_badge = @$_REQUEST['LOG_BADGE'];
		}
		$txt_badge = fnLimpaCampo(@$_REQUEST['TXT_BADGE']);
		$cor_badge = fnLimpaCampo(@$_REQUEST['COR_BADGE']);
		$cor_txtbadge = fnLimpaCampo(@$_REQUEST['COR_TXTBADGE']);
		$meta_title = fnLimpaCampo(@$_REQUEST['META_TITLE']);
		$meta_description = fnLimpaCampo(@$_REQUEST['META_DESCRIPTION']);

		$dat_ini_dest = fnDataSql(@$_POST['DAT_INI_DEST']);
		$dat_fim_dest = fnDataSql(@$_POST['DAT_FIM_DEST']);

		$cod_empresa = 274;

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		$insertData = "'$dat_ini_dest',
					   '$dat_fim_dest',";

		$updateData = "DAT_INI_DEST = '$dat_ini_dest',
					   DAT_FIM_DEST = '$dat_fim_dest',";

		if ($dat_ini_dest == "") {
			$insertData = "null,
						   null,";

			$updateData = "DAT_INI_DEST = null,
						   DAT_FIM_DEST = null,";
		}

		if ($opcao != '' && $opcao != 0) {

			switch ($opcao) {

				case 'CAD':
					$sqlCad = "INSERT INTO ADORAI_CHALES (
											COD_EMPRESA,
											COD_HOTEL,
											COD_EXTERNO,
											LOG_HOME,
											LOG_BANNER,
											NOM_QUARTO,
											DES_IMAGEM,
											DES_BANNER,
											DES_VIDEO,
											DES_QUARTO,
											TAM_PROPRIEDADE,
											QTD_HOSPEDES,
											QTD_QUARTOS,
											QTD_BANHEIROS,
											DAT_INI_DEST,
											DAT_FIM_DEST,
											LOG_BADGE,
											TXT_BADGE,
											COR_BADGE,
											COR_TXTBADGE,
											VAL_EFETIVO,
											META_TITLE,
											META_DESCRIPTION,
											COD_USUCADA,
											DAT_CADASTR
											)VALUES(
											$cod_empresa,
											$cod_externo,
											$cod_quarto,
											'$log_home',
											'$log_banner',
											'$nom_quarto',
											'$des_imagem',
											'$des_banner',
											'$des_video',
											'$des_quarto',
											$tam_propriedade,
											$qtd_hospedes,
											$qtd_quartos,
											$qtd_banheiros,

											$insertData

											'$log_badge',
											'$txt_badge',
											'$cor_badge',
											'$cor_txtbadge',
											'$val_efetivo',
											'$meta_title',
											'$meta_description',
											$cod_usucada,
											NOW()
											)";

					//fnescreve($sqlCad);

					// fnTestesql(connTemp($cod_empresa),$sqlCad);				
					$arrayProc = mysqli_query(conntemp($cod_empresa, ''), $sqlCad);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm, conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlCad, $nom_usuario);
					}
					break;
				case 'ALT':
					$sqlAlt = "UPDATE ADORAI_CHALES SET
													COD_EXTERNO = $cod_quarto,
													COD_HOTEL = $cod_hotel,
													LOG_HOME = '$log_home',
													LOG_BANNER = '$log_banner',
													NOM_QUARTO = '$nom_quarto',
													DES_IMAGEM = '$des_imagem',
													DES_BANNER = '$des_banner',
													DES_VIDEO = '$des_video',
													DES_QUARTO = '$des_quarto',
													COD_ALTERAC = $cod_usucada,
													TAM_PROPRIEDADE = $tam_propriedade,
													QTD_HOSPEDES = $qtd_hospedes,
													QTD_QUARTOS = $qtd_quartos,
													QTD_BANHEIROS = $qtd_banheiros,
													META_DESCRIPTION = '$meta_description',
													META_TITLE = '$meta_title',
													
													$updateData

													LOG_BADGE = '$log_badge',
													TXT_BADGE = '$txt_badge',
													COR_BADGE = '$cor_badge',
													VAL_EFETIVO = '$val_efetivo',
													COR_TXTBADGE = '$cor_txtbadge',
													DAT_ALTERAC = NOW()
							WHERE ID = $cod_id
							AND COD_EMPRESA = $cod_empresa";

					// fnescreve($sqlAlt);
					// fntestesql(connTemp($cod_empresa,''),$sqlAlt);
					$arrayAlt = mysqli_query(conntemp($cod_empresa, ''), $sqlAlt);

					if (!$arrayAlt) {

						$cod_erro = Log_error_comand($adm, conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlAlt, $nom_usuario);
					}
					break;
				case 'EXC':
					$sqlExc = "UPDATE ADORAI_CHALES SET
													COD_EXCLUSA = $cod_usucada,
													DAT_EXCLUSA = NOW()
							WHERE ID = $cod_id
							AND COD_EMPRESA = $cod_empresa";
					$arrayExc = mysqli_query(conntemp($cod_empresa, ''), $sqlExc);

					if (!$arrayExc) {

						$cod_erro = Log_error_comand($adm, conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlExc, $nom_usuario);
					}
					break;
			}

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível excluir o registro : $cod_erro";
					}
					break;
			}
			if ($cod_erro == 0 || $cod_erro == "") {
				$msgTipo = 'alert-success';
			} else {
				$msgTipo = 'alert-danger';
			}
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

$cod_empresa = 274;

//fnMostraForm();

?>



<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php
				$formBack = "1019";
				include "atalhosPortlet.php";
				?>

			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<?php
				$abaAdorai = 1833;
				include "abasAdorai.php";

				$abaManutencaoAdorai = fnDecode(@$_GET['mod']);
				//echo $abaUsuario;

				//se não for sistema de campanhas

				echo ('<div class="push20"></div>');
				include "abasManutencaoAdorai.php";
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Hotel</label>
										<select data-placeholder="Selecione um Hotel" name="COD_EXTERNO" id="COD_EXTERNO" class="chosen-select-deselect" style="width:100%;" required>
											<option value=""></option>
											<?php $sql = "SELECT NOM_UNIVEND,COD_EXTERNO FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa";

											$arrayHotel = mysqli_query(conntemp($cod_empresa, ''), $sql);

											while ($qrHoteis = mysqli_fetch_assoc($arrayHotel)) {
												echo "<option value='" . $qrHoteis['COD_EXTERNO'] . "'>" . $qrHoteis['NOM_UNIVEND'] . "</option>";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome do Quarto</label>
										<input type="text" class="form-control input-sm" name="NOM_QUARTO" id="NOM_QUARTO" maxlength="60" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Código Quarto</label>
										<input type="text" class="form-control input-sm" name="COD_QUARTO" id="COD_QUARTO" maxlength="20" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Imagem Promocional</label>
										<input type="text" class="form-control input-sm" name="DES_IMAGEM" id="DES_IMAGEM" maxlength="250" required>
										<div class="help-block with-errors">Imagem que será enviada no Whatsapp</div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Imagem Banner</label>
										<input type="text" class="form-control input-sm" name="DES_BANNER" id="DES_BANNER" maxlength="150" required>
										<div class="help-block with-errors">tam. recomendado 450 x 640 px (7:10)</div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Vídeo Promocional</label>
										<input type="text" class="form-control input-sm" name="DES_VIDEO" id="DES_VIDEO" maxlength="250">
										<div class="help-block with-errors">Vídeo que será enviado no Whatsapp</div>
									</div>
								</div>

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Destaque Site</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_HOME" id="LOG_HOME" class="switch" value="S" <?= $check_status . " " . $check_statusIni ?>>
											<span></span>
										</label>
										<div class="help-block with-errors">Aparecerá na seção destaques</div>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Banner</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_BANNER" id="LOG_BANNER" class="switch" value="S" <?= $check_Banner . " " . $check_Banner ?>>
											<span></span>
										</label>
										<div class="help-block with-errors">Aparecerá no banner inicial</div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Tam. Propriedade</label>
										<input type="text" class="form-control input-sm" name="TAM_PROPRIEDADE" id="TAM_PROPRIEDADE" maxlength="20" required>
										<div class="help-block with-errors">Em metros quadrados (m²)</div>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Nro. de Hóspedes</label>
										<input type="text" class="form-control input-sm" name="QTD_HOSPEDES" id="QTD_HOSPEDES" maxlength="20" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Nro. Quartos</label>
										<input type="text" class="form-control input-sm" name="QTD_QUARTOS" id="QTD_QUARTOS" maxlength="20" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Nro. Banheiros</label>
										<input type="text" class="form-control input-sm" name="QTD_BANHEIROS" id="QTD_BANHEIROS" maxlength="20" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Valor Efetivo</label>
										<input type="text" class="form-control input-sm money" name="VAL_EFETIVO" id="VAL_EFETIVO" maxlength="20">
										<div class="help-block with-errors">Custo</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Data Inicial Destaque</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_INI_DEST" id="DAT_INI_DEST" value="" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<span class="help-block">Inicio da Reserva</span>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Data Final Destaque</label>

										<div class="input-group date datePicker" id="DAT_FIM_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_FIM_DEST" id="DAT_FIM_DEST" value="" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<span class="help-block">Fim da Reserva</span>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Badge</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_BADGE" id="LOG_BADGE" class="switch" value="S" <?= $check_badge ?>>
											<span></span>
										</label>
										<div class="help-block with-errors">Fita colorida do card</div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Texto badge</label>
										<input type="text" class="form-control input-sm" name="TXT_BADGE" id="TXT_BADGE" maxlength="20">
										<div class="help-block with-errors">Texto da fita colorida</div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Cor da badge </label>
										<input type="text" class="form-control input-sm pickColor" name="COR_BADGE" id="COR_BADGE" maxlength="100" value="<?php echo $cor_badge; ?>" autocomplete="off">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Cor do texto da badge </label>
										<input type="text" class="form-control input-sm pickColor" name="COR_TXTBADGE" id="COR_TXTBADGE" maxlength="100" value="<?php echo $cor_txtbadge; ?>" autocomplete="off">
									</div>
								</div>

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-6">
									<div class="form-group">
										<label for="inputName" class="control-label">Meta Titulo</label>
										<input type="text" class="form-control input-sm" name="META_TITLE" id="META_TITLE" maxlength="200">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-5">
									<div class="form-group">
										<label for="inputName" class="control-label">Meta Descrição</label>
										<input type="text" class="form-control input-sm" name="META_DESCRIPTION" id="META_DESCRIPTION" maxlength="200">
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="push10"></div>

							<div class="row">
								<div class="col-lg-12">
									<div class="form-group">
										<label for="inputName" class="control-label required">Descrição:</label>
										<textarea class="editor form-control input-sm" rows="4" name="DES_QUARTO" id="DES_QUARTO" maxlength="1000" required><?php echo $des_sac; ?></textarea>
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="ID" id="ID" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="col-lg-12">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-striped table-hover tableSorter">
									<thead>
										<tr>
											<th class="{ sorter: false }" width="40"></th>
											<th>Código</th>
											<th>Hotel</th>
											<th>Quarto</th>
											<th class="text-center { sorter: false }">Destaque</th>
											<th class="text-center { sorter: false }">Banner</th>
											<th>Código Externo</th>
											<th class="text-center { sorter: false }">Detalhes</th>
											<th class="text-center { sorter: false }">Imagens</th>
											<th class="{ sorter: false }" width="40"></th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT AC.*, UV.NOM_FANTASI, UV.COD_EXTERNO AS ID_HOTEL FROM ADORAI_CHALES AC
												LEFT JOIN UNIDADEVENDA UV ON UV.COD_EXTERNO = AC.COD_HOTEL
												WHERE AC.COD_EMPRESA = $cod_empresa
												AND AC.COD_EXCLUSA = 0 
												ORDER BY AC.COD_HOTEL, NOM_QUARTO";
										$arrayQuery = mysqli_query(conntemp($cod_empresa, ''), $sql);

										$count = 0;
										while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {

											$count++;

											$destaque = "";
											$banner = "";

											$sqlDetalhes = "SELECT * FROM DETALHES_ADORAI 
															WHERE COD_EMPRESA = $cod_empresa
															AND COD_CHALE = $qrLista[ID]";

											$arrayDetalhes = mysqli_query(conntemp($cod_empresa, ''), $sqlDetalhes);

											$detalhe = "<span class='fal fa-times text-danger'></span>";

											if (mysqli_num_rows($arrayDetalhes) > 0) {
												$detalhe = "<span class='fal fa-check text-success'></span>";
											}

											$sqlFotos = "SELECT * FROM IMAGENS_ADORAI 
															WHERE COD_EMPRESA = $cod_empresa
															AND COD_CHALE = $qrLista[ID]";

											$arrayFotos = mysqli_query(conntemp($cod_empresa, ''), $sqlFotos);

											$fotos = "<span class='fal fa-times text-danger'></span>";

											if (mysqli_num_rows($arrayFotos) > 0) {
												$fotos = "<span class='fal fa-check text-success'></span>";
											}

											if ($qrLista['LOG_HOME'] == "S") {
												$destaque = "<span class='fal fa-check text-success'></span>";
											}

											if ($qrLista['LOG_BANNER'] == "S") {
												$banner = "<span class='fal fa-check text-success'></span>";
											}

										?>
											<tr>
												<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(<?= $count ?>)'></th>
												<td><?= $qrLista['ID'] ?></td>
												<td><?= $qrLista['NOM_FANTASI'] ?></td>
												<td><?= $qrLista['NOM_QUARTO'] ?></td>
												<td class="text-center"><?= $destaque ?></td>
												<td class="text-center"><?= $banner ?></td>
												<td><?= $qrLista['COD_EXTERNO'] ?></td>
												<td class="text-center"><?= $detalhe ?></td>
												<td class="text-center"><?= $fotos ?></td>
												<td class="text-center">
													<small>
														<div class="btn-group dropdown dropleft">
															<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																ações &nbsp;
																<span class="fas fa-caret-down"></span>
															</button>
															<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																<li><a href='javascript:void(0)' class='addBox' data-url="action.do?mod=<?php echo fnEncode(1843) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($qrLista['ID']) ?>&pop=true" data-title="Cadastro <?= $qrLista['NOM_QUARTO'] ?>"><span class="fal fa-cog"></span>&nbsp;Detalhes</a></li>
																<li><a href='javascript:void(0)' class='addBox' data-url="action.do?mod=<?php echo fnEncode(1845) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($qrLista['ID']) ?>&pop=true" data-title="Imagens <?= $qrLista['NOM_QUARTO'] ?>"><span class="fal fa-image"></span>&nbsp;Imagens</a></li>
																<li><a href='javascript:void(0)' class="bt<?php echo $count; ?>" onclick="copiaLink(<?php echo $count ?>)"><span class="fal fa-copy"></span>&nbsp;Copiar link da pág. de detalhes</a></li>
																<li><a href='javascript:void(0)' class="addBox" data-title="<?= $qrLista['NOM_QUARTO'] ?> (QrCode)" data-url="action.do?mod=<?php echo fnEncode(1855) ?>&id=<?= fnEncode($cod_empresa) ?>&idh=<?= $qrLista['ID_HOTEL'] ?>&idc=<?= $qrLista['COD_EXTERNO'] ?>&pop=true">qrCode</a></li>

																<!-- <li class="divider"></li> -->
																<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
															</ul>
														</div>
													</small>
												</td>
											</tr>
											<div id="AREACODE_OFF_<?php echo $count; ?>" style="display: none;">
												<textarea id="AREACODE_<?php echo $count; ?>" rows="1" style="width: 100%;">https://roteirosadorai.com.br/detalhes.php?idh=<?= $qrLista['ID_HOTEL'] ?>&idc=<?= $qrLista['COD_EXTERNO'] ?></textarea>
												<input type='hidden' id='ret_ID_<?= $count ?>' value='<?= $qrLista['ID'] ?>'>
												<input type='hidden' id='ret_COD_HOTEL_<?= $count ?>' value='<?= $qrLista['COD_HOTEL'] ?>'>
												<input type='hidden' id='ret_NOM_QUARTO_<?= $count ?>' value='<?= $qrLista['NOM_QUARTO'] ?>'>
												<input type='hidden' id='ret_COD_EXTERNO_<?= $count ?>' value='<?= $qrLista['COD_EXTERNO'] ?>'>
												<input type='hidden' id='ret_DES_IMAGEM_<?= $count ?>' value='<?= $qrLista['DES_IMAGEM'] ?>'>
												<input type='hidden' id='ret_DES_BANNER_<?= $count ?>' value='<?= $qrLista['DES_BANNER'] ?>'>
												<input type='hidden' id='ret_DES_VIDEO_<?= $count ?>' value='<?= $qrLista['DES_VIDEO'] ?>'>
												<input type='hidden' id='ret_DES_QUARTO_<?= $count ?>' value='<?= $qrLista['DES_QUARTO'] ?>'>
												<input type='hidden' id='ret_LOG_HOME_<?= $count ?>' value='<?= $qrLista['LOG_HOME'] ?>'>
												<input type='hidden' id='ret_LOG_BANNER_<?= $count ?>' value='<?= $qrLista['LOG_BANNER'] ?>'>
												<input type='hidden' id='ret_TAM_PROPRIEDADE_<?= $count ?>' value='<?= $qrLista['TAM_PROPRIEDADE'] ?>'>
												<input type='hidden' id='ret_QTD_HOSPEDES_<?= $count ?>' value='<?= $qrLista['QTD_HOSPEDES'] ?>'>
												<input type='hidden' id='ret_QTD_QUARTOS_<?= $count ?>' value='<?= $qrLista['QTD_QUARTOS'] ?>'>
												<input type='hidden' id='ret_QTD_BANHEIROS_<?= $count ?>' value='<?= $qrLista['QTD_BANHEIROS'] ?>'>
												<input type='hidden' id='ret_LOG_BADGE_<?= $count ?>' value='<?= $qrLista['LOG_BADGE'] ?>'>
												<input type='hidden' id='ret_TXT_BADGE_<?= $count ?>' value='<?= $qrLista['TXT_BADGE'] ?>'>
												<input type='hidden' id='ret_COR_BADGE_<?= $count ?>' value='<?= $qrLista['COR_BADGE'] ?>'>
												<input type='hidden' id='ret_COR_TXTBADGE_<?= $count ?>' value='<?= $qrLista['COR_TXTBADGE'] ?>'>
												<input type='hidden' id='ret_VAL_EFETIVO_<?= $count ?>' value='<?= fnValor($qrLista['VAL_EFETIVO'], 2) ?>'>
												<input type='hidden' id='ret_DAT_INI_DEST_<?= $count ?>' value='<?= fnDataShort($qrLista['DAT_INI_DEST']) ?>'>
												<input type='hidden' id='ret_DAT_FIM_DEST_<?= $count ?>' value='<?= fnDataShort($qrLista['DAT_FIM_DEST']) ?>'>
												<input type='hidden' id='ret_META_TITLE_<?= $count ?>' value='<?= $qrLista['META_TITLE'] ?>'>
												<input type='hidden' id='ret_META_DESCRIPTION_<?= $count ?>' value='<?= $qrLista['META_DESCRIPTION'] ?>'>

											<?php
										}

											?>

									</tbody>
								</table>

							</form>

						</div>

					</div>

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

<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te-1.4.0.css">
<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te.png">
<script type="text/javascript" src="js/plugins/jQuery-TE/jquery-te-1.4.0.min.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
<link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">

<script type="text/javascript">
	$(function() {
		//color picker
		$('.pickColor').minicolors({
			control: $(this).attr('data-control') || 'hue',
			theme: 'bootstrap'
		});
	});

	$('.datePicker').datetimepicker({
		format: 'DD/MM/YYYY',
	}).on('changeDate', function(e) {
		$(this).datetimepicker('hide');
	});

	function copiaLink(index) {
		$("#AREACODE_OFF_" + index).show();
		$("#AREACODE_" + index).select();
		document.execCommand('copy');
		$('.bt' + index).fadeOut(function() {
			// $('.bt'+index).css('background','#2C3E50');
			$('.bt' + index).text('Link copiado');
			$('.bt' + index).fadeIn(200);
		});

		$("#AREACODE_OFF_" + index).hide();
	}

	function retornaForm(index) {
		$("#formulario #ID").val($("#ret_ID_" + index).val());
		$("#formulario #COD_QUARTO").val($("#ret_COD_EXTERNO_" + index).val());
		$("#formulario #COD_EXTERNO").val($("#ret_COD_HOTEL_" + index).val()).trigger("chosen:updated");
		$("#formulario #NOM_QUARTO").val($("#ret_NOM_QUARTO_" + index).val());
		$("#formulario #DES_QUARTO").val($("#ret_DES_QUARTO_" + index).val());
		$("#formulario #DES_IMAGEM").val($("#ret_DES_IMAGEM_" + index).val());
		$("#formulario #DES_BANNER").val($("#ret_DES_BANNER_" + index).val());
		$("#formulario #DES_VIDEO").val($("#ret_DES_VIDEO_" + index).val());
		$("#formulario #DES_QUARTO").val($("#ret_DES_QUARTO_" + index).val());
		$("#formulario #TAM_PROPRIEDADE").val($("#ret_TAM_PROPRIEDADE_" + index).val());
		$("#formulario #QTD_HOSPEDES").val($("#ret_QTD_HOSPEDES_" + index).val());
		$("#formulario #QTD_QUARTOS").val($("#ret_QTD_QUARTOS_" + index).val());
		$("#formulario #QTD_BANHEIROS").val($("#ret_QTD_BANHEIROS_" + index).val());
		$("#formulario #DAT_INI_DEST").val($("#ret_DAT_INI_DEST_" + index).val());
		$("#formulario #DAT_FIM_DEST").val($("#ret_DAT_FIM_DEST_" + index).val());
		$("#formulario #TXT_BADGE").val($("#ret_TXT_BADGE_" + index).val());
		$("#formulario #COR_BADGE").val($("#ret_COR_BADGE_" + index).val());
		$("#formulario #VAL_EFETIVO").val($("#ret_VAL_EFETIVO_" + index).val());
		$("#formulario #COR_TXTBADGE").val($("#ret_COR_TXTBADGE_" + index).val());
		$("#formulario #META_TITLE").val($("#ret_META_TITLE_" + index).val());
		$("#formulario #META_DESCRIPTION").val($("#ret_META_DESCRIPTION_" + index).val());
		if ($("#ret_LOG_BADGE_" + index).val() == 'S') {
			$('#formulario #LOG_BADGE').prop('checked', true);
		} else {
			$('#formulario #LOG_BADGE').prop('checked', false);
		}
		if ($("#ret_LOG_HOME_" + index).val() == 'S') {
			$('#formulario #LOG_HOME').prop('checked', true);
		} else {
			$('#formulario #LOG_HOME').prop('checked', false);
		}

		if ($("#ret_LOG_BANNER_" + index).val() == 'S') {
			$('#formulario #LOG_BANNER').prop('checked', true);
		} else {
			$('#formulario #LOG_BANNER').prop('checked', false);
		}

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>