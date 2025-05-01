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
$cod_players = "";
$cod_usuario = "";
$des_paghome = "";
$val_inativo = "";
$log_ticket = "";
$log_nps = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$qrBuscaUsuTeste = "";
$log_usuario = "";
$des_senhaus = "";
$qrBuscaSiteTotem = "";
$destinoHome = "";
$popUp = "";
$qrListaUnidades = "";
$disabled = "";
$check_TICKET = "";
$andSemUnidade = "";
$qrLista = "";
$idlojaKey = "";
$idmaquinaKey = "";
$codvendedorKey = "";
$nomevendedorKey = "";
$urltotem = "";

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_players = fnLimpaCampoZero(@$_REQUEST['COD_PLAYERS']);
		$cod_univend = fnLimpaCampoZero(fnDecode(@$_REQUEST['COD_UNIVEND']));
		$cod_usuario = fnLimpaCampoZero(fnDecode(@$_REQUEST['COD_USUARIO']));
		$cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);
		$des_paghome = fnLimpaCampo(@$_REQUEST['DES_PAGHOME']);
		$val_inativo = fnLimpaCampoZero(@$_REQUEST['VAL_INATIVO']);
		if (empty(@$_REQUEST['LOG_TICKET'])) {
			$log_ticket = 'N';
		} else {
			$log_ticket = @$_REQUEST['LOG_TICKET'];
		}
		if (empty(@$_REQUEST['LOG_NPS'])) {
			$log_nps = 'N';
		} else {
			$log_nps = @$_REQUEST['LOG_NPS'];
		}

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sql = "INSERT INTO totem_players ( COD_UNIVEND, 
															COD_USUARIO, 
															COD_EMPRESA,
															DES_PAGHOME,
															VAL_INATIVO,
															LOG_TICKET,
															LOG_NPS
														  ) VALUES (
														  	'$cod_univend', 
														  	'$cod_usuario', 
														  	'$cod_empresa',
														  	'$des_paghome',
														  	'$val_inativo',
														  	'$log_ticket',
														  	'$log_nps'
														  );";

					mysqli_query(connTemp($cod_empresa, ""), $sql);

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;
				case 'ALT':

					$sql = "UPDATE totem_players SET
									  	COD_UNIVEND = '$cod_univend', 
									  	COD_USUARIO = '$cod_usuario', 
									  	COD_EMPRESA = '$cod_empresa',
									  	DES_PAGHOME = '$des_paghome',
									  	VAL_INATIVO = '$val_inativo',
									  	LOG_TICKET = '$log_ticket',
									  	LOG_NPS = '$log_nps'
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_PLAYERS = $cod_players";

					mysqli_query(connTemp($cod_empresa, ""), $sql);

					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'EXC':

					$sql = "DELETE FROM totem_players WHERE COD_PLAYERS = $cod_players AND COD_EMPRESA = $cod_empresa ";
					mysqli_query(connTemp($cod_empresa, ""), $sql);

					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;
			}
			$msgTipo = 'alert-success';
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
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
	// fnescreve($des_senhaus);
}

//busca dados da tabela
$sql = "SELECT DES_PAGHOME FROM TOTEM WHERE COD_EMPRESA = $cod_empresa";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaSiteTotem = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaSiteTotem)) {
	$des_paghome = $qrBuscaSiteTotem['DES_PAGHOME'];
	if ($des_paghome == "index") {
		$destinoHome = "";
	} else {
		$destinoHome = "banner.do";
	}
}

//fnMostraForm();

?>

<style>
	.chosen-container {
		width: 100% !important;
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
				<div class="portlet" style="padding: 0;">
				<?php } ?>

				<?php if ($popUp != "true") {  ?>
					<div class="portlet-title">
						<div class="caption">
							<i class="glyphicon glyphicon-calendar"></i>
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
				<?php } ?>

				</div>
				<div class="portlet-body">

					<?php if ($msgRetorno <> '') { ?>
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php } ?>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend>

								<div class="row">

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label required">Player</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PLAYERS" id="COD_PLAYERS" value="">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Unidade de Atendimento</label>
											<select data-placeholder="Selecione a unidade de atendimento" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect" required>
												<option value="0"></option>
												<?php
												$sql = "select COD_UNIVEND, NOM_FANTASI, LOG_ESTATUS from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' order by NOM_UNIVEND ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrListaUnidades = mysqli_fetch_assoc($arrayQuery)) {

													if ($qrListaUnidades['LOG_ESTATUS'] == 'N') {
														$disabled = "disabled";
													} else {
														$disabled = " ";
													}
													echo "
																				<option value='" . fnEncode($qrListaUnidades['COD_UNIVEND']) . "'" . $disabled . ">" . $qrListaUnidades['NOM_FANTASI'] . "</option> 
																				";
												}
												?>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Página Inicial</label>
											<select data-placeholder="Selecione uma página inicial" name="DES_PAGHOME" id="DES_PAGHOME" class="chosen-select-deselect" required>
												<option value=""></option>
												<option value="index">Pesquisa de CPF/CNPJ</option>
												<option value="banner">Banner Rotativo</option>
												<option value="nps">Pesquisa NPS</option>
												<option value="cad">Fluxo LGPD</option>
												<option value="atd">Atendente</option>
												<?php if ($_SESSION['SYS_COD_EMPRESA'] == 2 || $_SESSION['SYS_COD_EMPRESA'] == 3) { ?>
													<option value="meta">Metas</option>
												<?php } ?>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Tempo de Inatividade</label>
											<select data-placeholder="Selecione uma inatividade" name="VAL_INATIVO" id="VAL_INATIVO" class="chosen-select-deselect" required>
												<option value=""></option>
												<option value="0">Nenhum</option>
												<option value="5">5 segundos</option>
												<option value="15">15 segundos</option>
												<option value="30">30 segundos</option>
												<option value="60">60 segundos</option>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="push10"></div>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Usuário</label>
											<div id="divId_usu">
												<select data-placeholder="Selecione um usuário" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect">
													<option value="0"></option>
												</select>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Habilitar Pesquisa</label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_NPS" id="LOG_NPS" class="switch" value="S" <?php echo $check_TICKET; ?>>
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Gerar Ticket de Ofertas</label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_TICKET" id="LOG_TICKET" class="switch" value="S" <?php echo $check_TICKET; ?>>
												<span></span>
											</label>
										</div>
									</div>

								</div>

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>

							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

							<div class="push5"></div>

						</form>

						<div class="push50"></div>

						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista">

									<table class="table table-bordered table-striped table-hover">
										<thead>
											<tr>
												<th width="40"></th>
												<th>Player</th>
												<th>Unidade</th>
												<th>Página Inicial</th>
												<th>Usuário</th>
												<th></th>
												<th></th>
											</tr>
										</thead>
										<tbody>

											<?php

											//se multicoisas, libera totem sem unidade
											if ($cod_empresa == 77) {
												$andSemUnidade = "";
											} else {
												$andSemUnidade = " AND U.LOG_ESTATUS != 'N' ";
											}

											$sql = "SELECT T.COD_PLAYERS,
																   T.COD_EMPRESA,
																   T.COD_UNIVEND,
																   U.NOM_FANTASI,
																   T.COD_USUARIO,
																   S.NOM_USUARIO, 
																   T.VAL_INATIVO, 
																   T.LOG_TICKET, 
																   T.DES_PAGHOME,
																   CASE WHEN T.DES_PAGHOME = 'index' THEN
																   'Pesquisa de CPF/CNPJ'
																   WHEN T.DES_PAGHOME = 'banner' THEN
																   'Banner Rotativo'
																   WHEN T.DES_PAGHOME = 'nps' THEN
																   'Pesquisa NPS'
																   WHEN T.DES_PAGHOME = 'cad' THEN
																   'Fluxo LGPD'
																   WHEN T.DES_PAGHOME = 'meta' THEN
																   'Metas' 
																   WHEN T.DES_PAGHOME = 'atd' THEN
																   'Atendente'
																   END PAG_INICIAL,
																   T.LOG_NPS 
															FROM TOTEM_PLAYERS T 
															LEFT JOIN UNIDADEVENDA U ON U.COD_UNIVEND=T.COD_UNIVEND
															LEFT JOIN USUARIOS S ON S.COD_USUARIO=T.COD_USUARIO
															WHERE T.COD_EMPRESA = $cod_empresa
															$andSemUnidade ";

											//fnEscreve($sql);
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);

											$count = 0;
											while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
												$count++;

												$idlojaKey = $qrLista['COD_UNIVEND'];
												$idmaquinaKey = 0;
												$codvendedorKey = 0;
												$nomevendedorKey = 0;

												$urltotem = fnEncode(
													$log_usuario . ';'
														. $des_senhaus . ';'
														. $idlojaKey . ';'
														. $idmaquinaKey . ';'
														. $cod_empresa . ';'
														. $codvendedorKey . ';'
														. $nomevendedorKey . ';'
														. $qrLista['COD_PLAYERS']
												);

												// echo($log_usuario);

												$des_paghome = $qrLista['DES_PAGHOME'];
												$destinoHome = "";

												if ($des_paghome == "index") {
													$destinoHome = "";
												} else if ($des_paghome == "nps") {
													$destinoHome = "pesquisa.do";
												} else if ($des_paghome == "cad") {
													$destinoHome = "consulta_V2.do";
												} else if ($des_paghome == "meta") {
													$destinoHome = "meta.do";
												} else if ($des_paghome == "atd") {
													$destinoHome = "atendente.do";
												} else {
													$destinoHome = "banner.do";
												}

											?>
												<tr class="dropdown">
													<td class="text-center"><input type="radio" name="radio1" onclick="retornaForm(<?php echo $count; ?>)"></th>
													<td><?php echo $qrLista['COD_PLAYERS']; ?></td>
													<td><?php echo $qrLista['NOM_FANTASI']; ?></td>
													<td><?php echo $qrLista['PAG_INICIAL']; ?></td>
													<td><?php echo $qrLista['NOM_USUARIO']; ?></td>
													<td class="dropdown">
														<a class="dropdown-toggle btn-xs btn-info" data-toggle="dropdown" href="#"> ações &nbsp;
															<span class="fa fa-caret-down"></span>
														</a>
														<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenuButton">
															<li class="pull-left"><a class='btn btn-xs btn' href="https://totem.bunker.mk/<?php echo $destinoHome; ?>?key=<?php echo $urltotem; ?>&r=" target="_blank"><i class='fa fa-share'></i>&nbsp; Acessar </a></li>
															<li class="pull-left"><a class='btn btn-xs btn bt<?php echo $count; ?>' onclick="copiaLink(<?php echo $count ?>)"><i class='fal fa-file'></i>&nbsp; Copiar link </a></li>
															<li class="pull-left"><a class='btn btn-xs btn addBox' data-title="Player <?= $qrLista['COD_PLAYERS'] ?> - <?= $qrLista['NOM_FANTASI'] ?> - <?= $qrLista['NOM_USUARIO'] ?> (QrCode)" data-url="action.do?mod=<?php echo fnEncode(1679) ?>&id=<?= fnEncode($cod_empresa) ?>&idp=<?= fnEncode($qrLista['COD_PLAYERS']) ?>&pop=true">QrCode</a></li>
														</ul>
													</td>
												</tr>
												<div id="AREACODE_OFF_<?php echo $count; ?>" style="display: none;">
													<textarea id="AREACODE_<?php echo $count; ?>" rows="1" style="width: 100%;">https://totem.bunker.mk/<?php echo $destinoHome; ?>?key=<?php echo $urltotem; ?>&r=</textarea>
												</div>
												<input type="hidden" id="totem_<?php echo $count; ?>" value="https://totem.bunker.mk/<?php echo $destinoHome; ?>?key=<?php echo $urltotem; ?>&r=">
												<input type="hidden" id="ret_COD_PLAYERS_<?php echo $count; ?>" value="<?php echo $qrLista['COD_PLAYERS']; ?>">
												<input type="hidden" id="ret_COD_UNIVEND_<?php echo $count; ?>" value="<?php echo fnEncode($qrLista['COD_UNIVEND']); ?>">
												<input type="hidden" id="ret_COD_USUARIO_<?php echo $count; ?>" value="<?php echo fnEncode($qrLista['COD_USUARIO']); ?>">
												<input type="hidden" id="ret_DES_PAGHOME_<?php echo $count; ?>" value="<?php echo $qrLista['DES_PAGHOME']; ?>">
												<input type="hidden" id="ret_VAL_INATIVO_<?php echo $count; ?>" value="<?php echo $qrLista['VAL_INATIVO']; ?>">
												<input type="hidden" id="ret_LOG_TICKET_<?php echo $count; ?>" value="<?php echo $qrLista['LOG_TICKET']; ?>">
												<input type="hidden" id="ret_LOG_NPS_<?php echo $count; ?>" value="<?php echo $qrLista['LOG_NPS']; ?>">

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

<div class="push20"></div>
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


<script type="text/javascript">
	function copiaLink(index) {
		$("#AREACODE_OFF_" + index).show();
		$("#AREACODE_" + index).select();
		document.execCommand('copy');
		$('.bt' + index).fadeOut(function() {
			$('.bt' + index).css('background', '#2C3E50');
			$('.bt' + index).text('Copiado');
			$('.bt' + index).fadeIn(200);
		});

		$("#AREACODE_OFF_" + index).hide();
	}


	// ajax
	$("#COD_UNIVEND").change(function() {
		var codBusca = $("#COD_UNIVEND").val();
		var codBusca2 = $("#COD_EMPRESA").val();
		buscaUsuario(codBusca, codBusca2);
	});

	function buscaUsuario(idUnidade, idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxBuscaUsuarioChave.php",
			data: {
				ajx1: idUnidade,
				ajx2: idEmp
			},
			beforeSend: function() {
				$('#divId_usu').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#divId_usu").html(data);
			},
			error: function() {
				$('#divId_usu').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function buscaUsuarioRetornaForm(id_usuario, idUnidade, idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxBuscaUsuarioChave.php",
			data: {
				ajx1: idUnidade,
				ajx2: idEmp
			},
			beforeSend: function() {
				$('#divId_usu').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#divId_usu").html(data);
				$("#formulario #COD_USUARIO").val(id_usuario).trigger("chosen:updated");
			},
			error: function() {
				$('#divId_usu').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function retornaForm(index) {
		$("#formulario #COD_PLAYERS").val($("#ret_COD_PLAYERS_" + index).val());
		$("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_" + index).val()).trigger("chosen:updated");
		$("#formulario #VAL_INATIVO").val($("#ret_VAL_INATIVO_" + index).val()).trigger("chosen:updated");
		$("#formulario #DES_PAGHOME").val($("#ret_DES_PAGHOME_" + index).val()).trigger("chosen:updated");
		if ($("#ret_LOG_TICKET_" + index).val() == 'S') {
			$('#formulario #LOG_TICKET').prop('checked', true);
		} else {
			$('#formulario #LOG_TICKET').prop('checked', false);
		}
		if ($("#ret_LOG_NPS_" + index).val() == 'S') {
			$('#formulario #LOG_NPS').prop('checked', true);
		} else {
			$('#formulario #LOG_NPS').prop('checked', false);
		}
		buscaUsuarioRetornaForm($("#ret_COD_USUARIO_" + index).val(), $("#ret_COD_UNIVEND_" + index).val(), <?php echo $cod_empresa; ?>);
		//alert($("#ret_COD_USUARIO_"+index).val());
		//$("#formulario #COD_USUARIO").val($("#ret_COD_USUARIO_"+index).val()).trigger("chosen:updated");
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>