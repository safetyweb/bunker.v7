<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$cod_usucada = "";
$opc = "";
$cod_empresaCode = "";
$cod_cliente = "";
$nom_cliente = "";
$num_cartao = "";
$des_emailus = "";
$num_telefon = "";
$num_cgcecpf = "";
$cod_sexopes = "";
$dat_nascime = "";
$mensagem = "";
$msgTipo = "";
$k = "";
$v = "";
$cod_usuario = "";
$nom_usuario = "";
$log_funciona = "";
$cod_chaveco = "";
$log_fidelizado = "";
$log_email = "";
$log_sms = "";
$log_telemark = "";
$log_whatsapp = "";
$log_push = "";
$sql1 = "";
$log_usuario = "";
$log_estatus = "";
$log_trocaprod = "";
$num_rgpesso = "";
$cod_estaciv = "";
$num_celular = "";
$num_comercial = "";
$cod_externo = "";
$num_tentati = "";
$des_enderec = "";
$num_enderec = "";
$des_complem = "";
$des_bairroc = "";
$num_cepozof = "";
$nom_cidadec = "";
$cod_estadof = "";
$des_apelido = "";
$cod_profiss = "";
$cod_univend_pref = "";
$tip_cliente = "";
$des_contato = "";
$nom_pai = "";
$nom_mae = "";
$cod_multemp = "";
$key_externo = "";
$cod_tpcliente = "";
$des_coment = "";
$execCliente = "";
$qrGravaCliente = "";
$cod_clienteRetorno = "";
$rs = "";
$qrBusca = "";
$e = "";
$cod_filtro = "";
$cod_tpfiltro = "";
$cod_indicado = "";
$msgRetorno = "";
$popUp = "";
$nom_empresa = "";
$usuario = "";
$cliente = "";
$plural = "";
$pref = "";
$arrayQuery = [];
$qrListaSexo = "";
$countFiltros = "";
$qrTipo = "";
$sqlFiltro = "";
$arrayFiltros = [];
$qrFiltros = "";
$sqlChosen = "";
$arrayChosen = [];
$qrChosen = "";
$RedirectPg = "";
$DestinoPg = "";
$andCodigo = "";
$andNumCartao = "";
$andcpf = "";
$andNome = "";
$andEmail = "";
$andTelefone = "";
$andSexopes = "";
$anddat_nascime = "";
$resPagina = "";
$total = 0;
$registros = "";
$inicio = "";
$qrListaEmpresas = "";
$cpfIndicado = "";
$i = 0;




$hashLocal = mt_rand();
$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

$cod_empresa = fnDecode(@$_GET['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$opc = (@$_REQUEST['opcao'] == "BUS" ? "BUS" : "CAD");
	//$cod_empresa = fnLimpacampo(fnDecode(@$_REQUEST['COD_EMPRESA']));
	//$cod_empresaCode = fnLimpacampo(@$_REQUEST['COD_EMPRESA']);
	$cod_cliente  = fnLimpacampo(@$_REQUEST['COD_CLIENTE']);
	$nom_cliente  = fnLimpacampo(@$_REQUEST['NOM_CLIENTE']);
	$num_cartao = fnLimpacampo(@$_REQUEST['NUM_CARTAO']);
	$des_emailus = fnLimpacampo(@$_REQUEST['DES_EMAILUS']);
	$num_telefon = fnLimpacampo(@$_REQUEST['NUM_TELEFON']);
	$num_cgcecpf = fnLimpacampo(fnLimpaDoc(@$_REQUEST['NUM_CGCECPF']));
	$cod_sexopes = fnLimpacampoZero(@$_REQUEST['COD_SEXOPES']);
	$dat_nascime = fnLimpacampo(@$_REQUEST['DAT_NASCIME']);
	$cod_univend = fnLimpacampo(@$_REQUEST['COD_UNIVEND']);

	if ($opc == "CAD") {

		$mensagem = "";
		$cod_cliente = 0;
		if (trim($nom_cliente) == "") {
			$mensagem = "Digite o Nome!";
			$msgTipo = 'alert-danger';
		} elseif (trim($num_cgcecpf) == "") {
			$mensagem = "Digite o CPF/CNPJ!";
			$msgTipo = 'alert-danger';
			//}elseif (trim($num_cartao) == ""){
			//$mensagem = "Digite n&ordm; Cart&atilde;o!";
			//$msgTipo = 'alert-danger';
		} elseif (trim($des_emailus) == "") {
			$mensagem = "Digite o E-mail!";
			$msgTipo = 'alert-danger';
		} elseif (trim($num_telefon) == "") {
			$mensagem = "Digite o Telefone!";
			$msgTipo = 'alert-danger';
		} else {
			foreach (@$_POST as $k => $v) {
				if (substr($k, 0, 10) == "COD_FILTRO") {
					//if (trim($v) == ""){
					//$mensagem = "Preencha todos os filtros!";
					//$msgTipo = 'alert-danger';
					//}
				}
			}

			if ($mensagem == "") {

				$opcao = "CAD";
				$cod_usuario = $cod_cliente;
				$nom_usuario = $nom_cliente;
				$num_cartao = $num_cgcecpf;
				$log_funciona = "S";
				$cod_chaveco = 1;
				$log_fidelizado = "S";
				$log_email = "S";
				$log_sms = "S";
				$log_telemark = "S";
				$log_whatsapp = "S";
				$log_push = "S";

				$sql1 = "CALL SP_ALTERA_CLIENTES(
								'" . @$cod_usuario . "',
								'" . @$cod_empresa . "',
								'" . @$nom_usuario . "',
								'" . @$log_usuario . "',
								'" . @$des_emailus . "',
								'" . @$_SESSION["SYS_COD_USUARIO"] . "',    
								'" . fnLimpaDoc(@$num_cgcecpf) . "',
								'" . @$log_estatus . "',
								'" . @$log_trocaprod . "',
								'" . @$num_rgpesso . "',
								'" . @$dat_nascime . "',
								'0" . @$cod_estaciv . "',
								'0" . @$cod_sexopes . "',
								'" . @$num_telefon . "',
								'" . @$num_celular . "',
								'" . @$num_comercial . "',
								'" . @$cod_externo . "',
								'" . fnLimpaDoc(@$num_cartao) . "',
								'0" . @$num_tentati . "',
								'" . @$des_enderec . "',
								'" . @$num_enderec . "',
								'" . @$des_complem . "',
								'" . @$des_bairroc . "',
								'" . @$num_cepozof . "',
								'" . @$nom_cidadec . "',
								'" . @$cod_estadof . "',
								'" . @$des_apelido . "',
								'0" . @$cod_profiss . "',
								0" . @$cod_univend . ",
								0" . @$cod_univend_pref . ",
								'" . @$tip_cliente . "',
								'" . @$des_contato . "',
								'" . @$log_email . "',
								'" . @$log_sms . "',
								'" . @$log_telemark . "',
								'" . @$log_whatsapp . "',
								'" . @$log_push . "',
								'" . @$log_fidelizado . "',
								'" . @$nom_pai . "',
								'" . @$nom_mae . "',
								'0" . @$cod_chaveco . "',
								'" . @$cod_multemp . "',
								'" . @$key_externo . "',
								'0" . @$cod_tpcliente . "',
								'" . @$log_funciona . "',
								'" . @$des_coment . "',
								'" . @$opcao . "'   
						);";

				// fnEscreve($sql1);
				$execCliente = mysqli_query(connTemp($cod_empresa, ''), $sql1);
				$qrGravaCliente = mysqli_fetch_assoc($execCliente);
				$cod_clienteRetorno = $qrGravaCliente['COD_CLIENTE'];
				$mensagem = $qrGravaCliente['MENSAGEM'];
				$msgTipo = 'alert-success';

				$sql = "SELECT COD_CLIENTE,NOM_CLIENTE FROM clientes WHERE COD_CLIENTE IN (
								SELECT MAX(COD_CLIENTE) COD_CLIENTE FROM clientes WHERE COD_EMPRESA=0$cod_empresa
							)";
				$rs = mysqli_query($connUser->connUser(), trim($sql));
				$qrBusca = mysqli_fetch_assoc($rs);
				$cod_cliente = $qrBusca['COD_CLIENTE'];
				$nom_cliente = $qrBusca['NOM_CLIENTE'];

				$sql = "DELETE FROM CLIENTE_FILTROS WHERE COD_EMPRESA=$cod_empresa AND COD_CLIENTE=$cod_cliente";
				$rs = mysqli_query($connUser->connUser(), trim($sql));
				foreach (@$_POST as $k => $v) {
					if (substr($k, 0, 10) == "COD_FILTRO") {
						$e = explode("_", $k);
						$cod_filtro = fnLimpacampoZero(@$_REQUEST["COD_FILTRO_" . @$e['2']]);
						$cod_tpfiltro = fnLimpacampoZero(@$_REQUEST["COD_TPFILTRO_" . @$e['2']]);

						$sql = "INSERT INTO CLIENTE_FILTROS(
													COD_EMPRESA,
													COD_TPFILTRO,
													COD_FILTRO,
													COD_CLIENTE,
													COD_USUCADA
													)VALUES(
													$cod_empresa,
													$cod_tpfiltro,
													$cod_filtro,
													$cod_cliente,
													$cod_usucada
													);";
						$rs = mysqli_query($connUser->connUser(), trim($sql));
					}
				}
			}
		}
	}
	// fnEscreve($num_cgcecpf);

} else {

	//$cod_empresa = 0;
	//$cod_empresaCode = 0;
	$cod_cliente  = 0;
	$nom_cliente  = "";
}




//fnEscreve($cod_indicado);
//fnEscreve($nom_cliente);

// fnEscreve($cod_empresa);	
//fnMostraForm();

$msgRetorno = @$mensagem;
?>

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
							<i class="glyphicon glyphicon-calendar"></i>
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
				<?php } ?>

				<div class="portlet-body">

					<?php if ($msgRetorno <> '') { ?>
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php }

					switch ($_SESSION["SYS_COD_SISTEMA"]) {
						case 16: //gabinete
							$usuario = "Colaborador";
							$cliente = "Apoiador";
							$plural = "es";
							$pref = 'S';
							break;
						default;
							$usuario = "Usuário";
							$cliente = "Cliente";
							$plural = "s";
							$pref = 'N';
							break;
					}

					?>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados para Pesquisa</legend>
								<div class="row">
									<div class="col-xs-3">
										<div class="form-group">
											<a class="btn btn-default btn-sm" onclick="copiaDadosUsu();" style="padding: 0 2px ; font-size: 10px;">carregar dados do usuário</a>
										</div>
									</div>
								</div>

								<div class="row">
									<input type='text' name='COD_UNIVEND' id='COD_UNIVEND' style='display:none'>

									<div class="col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Código</label>
											<input type="text" class="form-control input-sm" name="COD_CLIENTE" id="COD_CLIENTE" value="<?= @$cod_cliente ?>">
										</div>
									</div>

									<div class="col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Nome do <?= $cliente ?></label>
											<input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" maxlength="40" value="<?= @$nom_cliente ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label">CPF/CNPJ</label>
											<input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" maxlength="18" value="<?= @$num_cgcecpf ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>


									<div class="col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Data de Nascimento</label>
											<input type="text" class="form-control input-sm data" name="DAT_NASCIME" id="DAT_NASCIME" maxlength="18" value="<?= @$dat_nascime ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">e-Mail</label>
											<input type="text" class="form-control input-sm" name="DES_EMAILUS" id="DES_EMAILUS" maxlength="100" data-error="Campo obrigatório" value="<?= @$des_emailus ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Celular/Telefone</label>
											<input type="text" class="form-control input-sm fone" name="NUM_TELEFON" id="NUM_TELEFON" maxlength="20" value="<?= @$num_telefon ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Sexo</label>
											<select data-placeholder="Selecione o sexo" name="COD_SEXOPES" id="COD_SEXOPES" class="chosen-select-deselect">
												<option value=""></option>
												<?php
												$sql = "select COD_SEXOPES, DES_SEXOPES from sexo order by des_sexopes ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrListaSexo = mysqli_fetch_assoc($arrayQuery)) {
													echo "
																				  <option value='" . $qrListaSexo['COD_SEXOPES'] . "' " . ($cod_sexopes == $qrListaSexo['COD_SEXOPES'] ? "selected" : "") . ">" . $qrListaSexo['DES_SEXOPES'] . "</option> 
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


							</fieldset>



							<div class="push10"></div>

							<?php

							$sql = "SELECT COD_TPFILTRO, DES_TPFILTRO FROM TIPO_FILTRO
											WHERE COD_EMPRESA = $cod_empresa
											ORDER BY NUM_ORDENAC";
							$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));

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

											<div class="col-md-3">
												<div class="form-group">
													<label for="inputName" class="control-label"><?= $qrTipo['DES_TPFILTRO'] ?></label>
													<div id="relatorioFiltro_<?= $countFiltros ?>">
														<input type="hidden" name="COD_TPFILTRO_<?= $countFiltros ?>" id="COD_TPFILTRO_<?= $countFiltros ?>" value="<?= $qrTipo['COD_TPFILTRO'] ?>">
														<select data-placeholder="Selecione o filtro" name="COD_FILTRO_<?= $countFiltros ?>" id="COD_FILTRO_<?= $qrTipo['COD_TPFILTRO'] ?>" class="chosen-select-deselect last-chosen-link">
															<option value=""></option>
															<?php
															$sqlFiltro = "SELECT COD_FILTRO, DES_FILTRO FROM FILTROS_CLIENTE
																					  WHERE COD_TPFILTRO = " . $qrTipo['COD_TPFILTRO'];

															$arrayFiltros = mysqli_query(connTemp($cod_empresa, ''), trim($sqlFiltro));
															while ($qrFiltros = mysqli_fetch_assoc($arrayFiltros)) {
															?>

																<option value="<?= $qrFiltros['COD_FILTRO'] ?>" <?= (@$_POST["COD_FILTRO_" . $countFiltros] == $qrFiltros['COD_FILTRO'] ? "selected" : "") ?>><?= $qrFiltros['DES_FILTRO'] ?></option>

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
																		$('#COD_FILTRO_<?= $qrTipo['COD_TPFILTRO'] ?>').val(<?= $qrChosen['COD_FILTRO'] ?>).trigger('chosen:updated');
																	</script>
															<?php
																}
															}
															?>
															<option value="add">&nbsp;ADICIONAR NOVO</option>
														</select>
														<script type="text/javascript">
															$('#COD_FILTRO_<?= $qrTipo['COD_TPFILTRO'] ?>').change(function() {
																valor = $(this).val();
																if (valor == "add") {
																	$(this).val('').trigger("chosen:updated");
																	$('#btnCad_<?= $countFiltros ?>').click();
																}
															});
														</script>
														<div class="help-block with-errors"></div>
													</div>
												</div>
											</div>
											<a type="hidden" name="btnCad_<?= $countFiltros ?>" id="btnCad_<?= $countFiltros ?>" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1398) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idF=<?= fnEncode($qrTipo['COD_TPFILTRO']) ?>&idS=<?= fnEncode($countFiltros) ?>&pop=true" data-title="Cadastrar Filtro - <?= $qrTipo['DES_TPFILTRO'] ?>"></a>

										<?php
											$countFiltros++;
										}
										?>

									</div>

								</fieldset>

							<?php
							}
							?>




							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
								<?php

								if ($cod_empresa == 136) {
								?>

									<a href="javascript:void(0)" data-target="action.php?mod=<?= fnEncode(1423) ?>&id=<?= fnEncode($cod_empresa) ?>&idc=<?= fnEncode(0) ?>" class="btn btn-primary btnCadCli"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Novo Cliente</a>

								<?php
								}

								?>
								<button type="submit" name="BUS" id="BUS" class="btn btn-primary getBtn"><i class="fa fa-search" aria-hidden="true"></i>&nbsp; Pesquisar</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>

							</div>

							<?php
							if (!is_null($RedirectPg)) {
								$DestinoPg = fnEncode($RedirectPg);
							} else {
								$DestinoPg = "";
							}
							?>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

							<div class="push5"></div>

						</form>

						<div class="push50"></div>

						<?php

						if ($_SERVER['REQUEST_METHOD'] == 'POST' && $opc == "BUS") {
							//if ($cod_empresa != 0 && $cod_empresa != ''){

							$pagina = (isset($_GET['pagina'])) ? @$_GET['pagina'] : 1;

							if ($cod_cliente != 0 && $cod_cliente != '') {
								$andCodigo = 'and cod_cliente=' . $cod_cliente;
							} else {
								$andCodigo = ' ';
							}

							if ($num_cartao != 0 && $num_cartao != '') {
								$andNumCartao = 'and num_cartao=' . $num_cartao;
							} else {
								$andNumCartao = ' ';
							}

							if ($num_cgcecpf != 0 && $num_cgcecpf != '') {
								$andcpf = 'and num_cgcecpf=' . $num_cgcecpf;
							} else {
								$andcpf = ' ';
							}

							if ($nom_cliente != '' && $nom_cliente != 0) {
								$andNome = 'and nom_cliente like "' . $nom_cliente . '%"';
							} else {
								$andNome = ' ';
							}

							if ($des_emailus != '' && $des_emailus != 0) {
								$andEmail = 'and des_emailus like "' . $des_emailus . '%"';
							} else {
								$andEmail = "";
							}

							if ($num_telefon != '' && $num_telefon != 0) {
								$andTelefone = 'and (num_celular like "' . $num_telefon . '%" or num_telefon like "' . $num_telefon . '%")';
							} else {
								$andTelefone = "";
							}

							if ($cod_sexopes != '' && $cod_sexopes != 0) {
								$andSexopes = " and (cod_sexopes = $cod_sexopes) ";
							} else {
								$andSexopes = "";
							}

							if ($dat_nascime != '' && $dat_nascime != 0) {
								$anddat_nascime = " and (dat_nascime = $dat_nascime) ";
							} else {
								$anddat_nascime = "";
							}

							$sql = "select count(COD_CLIENTE) as CONTADOR from  $connUser->DB.clientes where cod_empresa = " . $cod_empresa . " 
                                                                                                                                                    " . $andCodigo . "
                                                                                                                                                    " . $andNome . "
                                                                                                                                                    " . $andNumCartao . "
                                                                                                                                                    " . $andcpf . "
                                                                                                                                                    " . $andEmail . "
                                                                                                                									" . $andTelefone . "
																																					" . $andSexopes . "
																																					" . $anddat_nascime . "
                                                                                                                                                    order by NOM_CLIENTE ";
							//fnEscreve($sql);

							$resPagina = mysqli_query($connUser->connUser(), $sql);
							$total = mysqli_fetch_assoc($resPagina);
							//seta a quantidade de itens por página, neste caso, 2 itens
							$registros = 100;
							//fnEscreve($total['CONTADOR']);
							//calcula o número de páginas arredondando o resultado para cima
							$numPaginas = ceil($total['CONTADOR'] / $registros);
							//variavel para calcular o início da visualização com base na página atual
							$inicio = ($registros * $pagina) - $registros;
						} else {
							$numPaginas = 1;
						}

						if ($_SERVER['REQUEST_METHOD'] == 'POST' && $opc == "BUS") {
						?>

							<div class="col-lg-12">

								<div class="no-more-tables">

									<form name="formLista" id="formLista" method="post" action="">

										<table class="table table-bordered table-striped table-hover" id="tablista">
											<thead>
												<tr>
													<th class="{ sorter: false }" width="40"></th>
													<th>Código</th>
													<th>Cartão</th>
													<th>Nome do <?= $cliente ?></th>
													<th>e-Mail</th>
													<th>Celular/Telefone</th>
													<th>CPF</th>
												</tr>
											</thead>
											<tbody>

												<?php
												if ($_SERVER['REQUEST_METHOD'] == 'POST') {

													// fnEscreve('teste');
													//if ($cod_empresa != 0 && $cod_empresa != ''){

													if ($cod_cliente != 0 && $cod_cliente != '') {
														$andCodigo = 'and cod_cliente=' . $cod_cliente;
													}

													if ($nom_cliente != '' && $nom_cliente != 0) {
														$andNome = 'and nom_cliente like "%' . $nom_cliente . '%"';
													}

													if ($des_emailus != '' && $des_emailus != 0) {
														$andEmail = 'and des_emailus like "' . $des_emailus . '%"';
													}

													if ($num_telefon != '' && $num_telefon != 0) {
														$andTelefone = 'and (num_celular like "' . $num_telefon . '%"  or num_telefon like "' . $num_telefon . '%" )';
													}
													if ($cod_sexopes != '' && $cod_sexopes != 0) {
														$andSexopes = " and (cod_sexopes = $cod_sexopes) ";
													} else {
														$andSexopes = "";
													}

													if ($dat_nascime != '' && $dat_nascime != 0) {
														$anddat_nascime = " and (dat_nascime = $dat_nascime) ";
													} else {
														$anddat_nascime = "";
													}
													$sql = "select COD_CLIENTE, NOM_CLIENTE, DES_EMAILUS, NUM_CGCECPF, NUM_TELEFON, NUM_CELULAR, COD_INDICAD from clientes where cod_empresa = " . $cod_empresa . " 
                                                                                                                " . $andCodigo . "
                                                                                                                " . $andNome . "
                                                                                                                " . $andNumCartao . "
                                                                                                                " . $andcpf . "
                                                                                                                " . $andEmail . "
                                                                                                                " . $andTelefone . "
																												" . $andSexopes . "
																												" . $anddat_nascime . "
                                                                                                                order by NOM_CLIENTE limit $inicio,$registros";
													// fnEscreve($sql);
													$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

													$count = 0;

													while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {
														$count++;

														echo "
															<tr>
															  <td><a href='javascript: downForm(" . $count . ")' style='margin-left: 10px;'><i class='fa fa-arrow-circle-down' aria-hidden='true'></i></a></th>
															  <td>" . $qrListaEmpresas['COD_CLIENTE'] . "</td>
															  <td></td>
															  <td>" . $qrListaEmpresas['NOM_CLIENTE'] . "</td>
															  <td>" . $qrListaEmpresas['DES_EMAILUS'] . "</td>
															  <td>" . $qrListaEmpresas['NUM_CELULAR'] . "/" . $qrListaEmpresas['NUM_TELEFON'] . "</td>
															  <td>" . $qrListaEmpresas['NUM_CGCECPF'] . "</td>
															</tr>
															<input type='hidden' id='ret_ENCODE_" . $count . "' value='" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "'>
															<input type='hidden' id='ret_COD_CLIENTE_" . $count . "' value='" . $qrListaEmpresas['COD_CLIENTE'] . "'>
															<input type='hidden' id='ret_COD_CLIENTE_ENC_" . $count . "' value='" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "'>
															<input type='hidden' id='ret_COD_INDICADOR_" . $count . "' value='" . $qrListaEmpresas['COD_INDICAD'] . "'>
															<input type='hidden' id='ret_NOM_CLIENTE_" . $count . "' value='" . $qrListaEmpresas['NOM_CLIENTE'] . "'>
															<input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . $cod_empresa . "'>
															<input type='hidden' class='cpfcnpj' id='ret_CPF_INDICADO_" . $count . "' value='" . @$cpfIndicado . "'>
															";
													}
												}
												?>

											</tbody>
											<?php if ($cod_empresa != 0 && $cod_empresa != '') {  ?>
												<tfoot>
													<tr>
														<th colspan="100">
															<ul class="pagination pagination-sm pull-right">
																<?php
																for ($i = 1; $i < $numPaginas + 1; $i++) {
																	echo "<li class='pagination'><a href='{$_SERVER['PHP_SELF']}?mod=NN7xULiFM88¢&pagina=$i' style='text-decoration: none;'>" . $i . "</a></li>";
																}
																?></ul>
														</th>
													</tr>
												</tfoot>
											<?php }   ?>

										</table>


										<div class="push"></div>

									</form>

								</div>

							</div>

							<?php
							$count = 0;
							if ($pref == "S" && mysqli_num_rows($arrayQuery) == 0) {
							?>
								<div class="row">
									<div class="col-md-4 col-md-offset-4 text-center">
										<a href="javascript:void(0)" data-target="action.php?mod=<?= fnEncode(1423) ?>&id=<?= fnEncode($cod_empresa) ?>&idc=<?= fnEncode(0) ?>" class="btn btn-info btnCadCli"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp; Adicionar Cliente</a>
									</div>
								</div>
						<?php
							}
						}

						?>

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
		$(document).keypress(function(event) {
			var keycode = (event.keyCode ? event.keyCode : event.which);
			if (keycode == '13') {
				$('#BUS').click();
			}

		});


		$(document).ready(function() {
			if ($("#COD_UNIVEND", window.parent.document).val() != null) {
				$("#COD_UNIVEND").val($("#COD_UNIVEND", window.parent.document).val()[0]);
			}
			<?php if (@$opc == "CAD" && $cod_cliente <> "" && $cod_cliente <> 0) { ?>
				try {
					parent.$('#NOM_INDICA').val("<?= @$nom_cliente ?>");
					//parent.$('#NOM_INDICA').attr("readonly", "readonly");
					//parent.$('#NOM_INDICA').addClass('leitura');
				} catch (err) {}
				try {
					parent.$('#COD_INDICA').val("<?= @$cod_cliente ?>");
					parent.$('#COD_INDICA_ENC').val("<?= fnEncode(@$cod_cliente) ?>");
					//parent.$('#btnBuscaInd').hide();
				} catch (err) {}

				$(this).removeData('bs.modal');
				parent.$('.modal').modal('hide');

				return false;
			<?php } ?>

			$(".btnCadCli").click(function() {
				parent.$('#popModal').modal('hide');
				parent.window.location.replace($(this).attr("data-target"));
			});

			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			//table sorter
			$(function() {
				var tabelaFiltro = $('table.tablesorter')
				tabelaFiltro.find("tbody > tr").find("td:eq(1)").mousedown(function() {
					$(this).prev().find(":checkbox").click()
				});
				$("#filter").keyup(function() {
					$.uiTableFilter(tabelaFiltro, this.value);
				})
				$('#formLista').submit(function() {
					tabelaFiltro.find("tbody > tr:visible > td:eq(1)").mousedown();
					return false;
				}).focus();
			});

			//pesquisa table sorter
			$('.filter-all').on('input', function(e) {
				if ('' == this.value) {
					var lista = $("#filter").find("ul").find("li");
					filtrar(lista, "");
				}
			});

		});


		function retornaForm(index) {

			$('#formulario').attr('action', 'action.php?mod=<?php echo $DestinoPg; ?>&id=' + $("#ret_COD_EMPRESA_" + index).val() + '&idC=' + $("#ret_COD_CLIENTE_" + index).val());
			$("#formulario #hHabilitado").val('S');
			$("#formulario")[0].submit();

		}

		function downForm(index) {

			// alert('entrou');
			cod_cliente = '<?= @$cod_indicado ?>',
				cod_indicador_novo = $("#ret_COD_CLIENTE_" + index).val();
			cod_indicador = $("#ret_COD_INDICADOR_" + index).val();

			if (cod_cliente != cod_indicador_novo && cod_cliente != cod_indicador && cod_cliente != 0) {

				// alert('entrou if');

				$.ajax({
					type: "POST",
					url: "ajxClienteIndicador.php?id=" + <?= $cod_empresa ?>,
					data: {
						COD_INDICADOR: cod_indicador_novo,
						COD_CLIENTE: cod_cliente
					},
					success: function(data) {
						//console.log(data);
						try {
							parent.$('#NOM_INDICA').val($("#ret_NOM_CLIENTE_" + index).val());
							parent.$('#NOM_INDICA').attr("readonly", "readonly");
							parent.$('#NOM_INDICA').addClass('leitura');
						} catch (err) {}
						try {
							parent.$('#COD_INDICA').val($("#ret_COD_CLIENTE_" + index).val());
							parent.$('#COD_INDICA_ENC').val($("#ret_COD_CLIENTE_ENC_" + index).val());
							parent.$('#btnBuscaInd').hide();
						} catch (err) {}
						try {
							parent.$('#DAT_INDICA').val(data);
						} catch (err) {}

					},
					error: function() {
						alert('Algo deu errado :(');
					}
				});

			} else if (cod_cliente == cod_indicador_novo && cod_cliente != cod_indicador && cod_cliente != 0) {

				// alert('entrou else if 1');
				$.alert({
					title: "Mensagem",
					content: "Cliente indicado não pode ser igual a indicador.",
					type: 'red'
				});
			} else if (cod_cliente != cod_indicador_novo && cod_cliente == cod_indicador && cod_cliente != 0) {

				cpfIndicado = $("#ret_CPF_INDICADO_" + index).val();
				$.alert({
					title: "Mensagem",
					content: "Cliente já foi Indicado por CPF " + cpfIndicado,
					type: 'red'
				});
			} else {
				// alert('entrou else');
				try {
					parent.$('#NOM_INDICA').val($("#ret_NOM_CLIENTE_" + index).val());
					parent.$('#COD_INDICA').val($("#ret_COD_CLIENTE_" + index).val());
					parent.$('#COD_INDICA_ENC').val($("#ret_COD_CLIENTE_ENC_" + index).val());
				} catch (err) {}

			}




			$(this).removeData('bs.modal');
			parent.$('.modal').modal('hide');

			// alert('passou o hide');

		}


		function copiaDadosUsu() {
			$("#NOM_CLIENTE").val($("#NOM_USUARIO", window.parent.document).val());
			$("#NUM_CGCECPF").val($("#NUM_CGCECPF", window.parent.document).val());
			$("#DAT_NASCIME").val($("#DAT_NASCIME", window.parent.document).val());
			$("#DES_EMAILUS").val($("#DES_EMAILUS", window.parent.document).val());
			$("#NUM_TELEFON").val($("#NUM_TELEFON", window.parent.document).val());
			$("#COD_SEXOPES").val($("#COD_SEXOPES", window.parent.document).val()).trigger("chosen:updated");
			if ($("#COD_UNIVEND", window.parent.document).val() != null) {
				$("#COD_UNIVEND").val($("#COD_UNIVEND", window.parent.document).val()[0]);
			}
		}
	</script>