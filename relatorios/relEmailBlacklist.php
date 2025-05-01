<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$hoje = "";
$dias30 = "";
$msgRetorno = "";
$msgTipo = "";
$cod_usuario = "";
$des_email = "";
$dat_ini = "";
$dat_fim = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$cod_campanha = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$sqlUsuarios = "";
$arrayUsuarios = [];
$cod_usuarios = "";
$qrUsuarios = "";
$formBack = "";
$qrLista = "";
$andUsuario = "";
$andEmail = "";
$sql2 = "";
$qrCount2 = "";
$qrCount = "";
$ARRAY_VENDEDOR1 = "";
$ARRAY_VENDEDOR = "";
$retorno = "";
$inicio = "";
$qrRet = "";
$NOM_ARRAY_NON_VENDEDOR = "";
$usuario = "";
$sqlCount = "";
$arrayCount = [];
$content = "";
$itens_por_pagina = 50;
$pagina = 1;
$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_usuario = fnLimpaCampoZero(@$_POST['COD_USUARIO']);
		$des_email = fnLimpaCampo(@$_POST['DES_EMAIL']);
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		// fnEscreve($cod_campanha);

		if ($opcao != '' && $opcao != 0) {
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	// $cod_campanha = fnDecode(@$_GET['idc']);	
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	$nom_empresa = "";
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

$sqlUsuarios = "SELECT DISTINCT COD_USUCADA FROM BLACKLIST_EMAIL WHERE COD_EMPRESA = $cod_empresa";
$arrayUsuarios = mysqli_query(connTemp($cod_empresa, ''), $sqlUsuarios);

$cod_usuarios = "";

while ($qrUsuarios = mysqli_fetch_assoc($arrayUsuarios)) {
	$cod_usuarios = $cod_usuarios . $qrUsuarios['COD_USUCADA'] . ',';
}

$cod_usuarios = ltrim(rtrim($cod_usuarios, ','), ',');

if ($cod_usuarios == "") {
	$cod_usuarios = 0;
}



?>

<style>
	table a:not(.btn),
	.table a:not(.btn) {
		text-decoration: none;
	}

	table a:not(.btn):hover,
	.table a:not(.btn):hover {
		text-decoration: underline;
	}

	.dropdown-menu>li>a {
		color: unset;
	}

	.dropdown-menu>li>a:hover {
		text-decoration: none !important;
		background-color: #ECF0F1 !important;
		color: #2C3E50 !important;
	}

	.dropdown-toggle {
		width: 100%;
	}

	.dropleft ul {
		left: unset;
		right: 70%;
	}
</style>

<div class="push30"></div>

<div class="row" id="div_Report">

	<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="fal fa-terminal"></i>
						<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
					</div>

					<?php
					//$formBack = "1015";
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


					<div class="login-form">

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Data Inicial</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>">
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Data Final</label>

										<div class="input-group date datePicker" id="DAT_FIM_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>">
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">

										<label for="inputName" class="control-label">Usuário</label>
										<select data-placeholder="Selecione o status" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect">
											<option value=""></option>
											<option value="9999">Integração</option>
											<?php

											$sql = "SELECT COD_USUARIO, NOM_USUARIO FROM USUARIOS 
	                                                            WHERE COD_EMPRESA = $cod_empresa 
	                                                            AND DAT_EXCLUSA IS NULL 
	                                                            AND COD_USUARIO IN($cod_usuarios) 
	                                                            ORDER BY NOM_USUARIO";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
												echo "
	                                                              <option value='" . $qrLista['COD_USUARIO'] . "'>" . $qrLista['NOM_USUARIO'] . "</option> 
	                                                            ";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
										<script type="text/javascript">
											$("#formulario #COD_USUARIO").val('<?= $cod_usuario ?>').trigger("chosen:updated");
										</script>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Email</label>
										<input type="text" class="form-control input-sm" name="DES_EMAIL" id="DES_EMAIL" value="<?php echo $des_email ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>


							</div>

						</fieldset>
					</div>
				</div>
			</div>

			<div class="push20"></div>

			<div class=" portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="push50"></div>

						<div class="row text-center">

							<?php

							if ($cod_usuario != 0 && $cod_usuario != '') {
								$andUsuario = "AND COD_USUCADA = $cod_usuario";
							} else {
								$andUsuario = "";
							}

							if ($des_email != '' && $des_email != 0) {
								$andEmail = "AND DES_EMAIL = '$des_email'";
							} else {
								$andEmail = "";
							}

							$sql2 = "SELECT COUNT(DES_EMAIL) AS QTD_EMAIL, 
											   SUM((SELECT COUNT(CL.COD_CLIENTE) FROM CLIENTES CL 
											   	WHERE CL.DES_EMAILUS = BE.DES_EMAIL
											   	AND CL.COD_EMPRESA = $cod_empresa)) AS QTD_CADASTRO
										FROM BLACKLIST_EMAIL BE
									    WHERE COD_EMPRESA = $cod_empresa
									    ";

							// fnEscreve($sql);
							//fnTestesql(connTemp($cod_empresa,''),$sql);

							$qrCount2 = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql2));

							$sql = "SELECT COUNT(DES_EMAIL) AS QTD_EMAIL, 
											   SUM((SELECT COUNT(CL.COD_CLIENTE) FROM CLIENTES CL 
											   	WHERE CL.DES_EMAILUS = BE.DES_EMAIL
											   	AND CL.COD_EMPRESA = $cod_empresa)) AS QTD_CADASTRO
										FROM BLACKLIST_EMAIL BE
									    WHERE COD_EMPRESA = $cod_empresa
									    AND DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
									    $andUsuario
								    	$andEmail
									    ";

							// fnEscreve($sql);
							//fnTestesql(connTemp($cod_empresa,''),$sql);

							$qrCount = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

							?>

							<div class="form-group text-center col-md-3 col-lg-3">

								<div class="push20"></div>

								<p><?= fnValor($qrCount2['QTD_EMAIL'], 0) ?></p>
								<p><b>Quantidade de emails na lista</b></p>

								<div class="push20"></div>

							</div>


							<div class="form-group text-center col-md-3 col-lg-3">

								<div class="push20"></div>

								<p><?= fnValor($qrCount2['QTD_CADASTRO'], 0) ?></p>
								<p><b>Quantidade de cadastros afetados</b></p>

								<div class="push20"></div>

							</div>

							<div class="form-group text-center col-md-3 col-lg-3">

								<div class="push20"></div>

								<p><?= fnValor($qrCount['QTD_CADASTRO'], 0) ?></p>
								<p><b>Quantidade de cadastros afetados (no período)</b></p>

								<div class="push20"></div>

							</div>

							<div class="form-group text-center col-md-3 col-lg-3">

								<div class="push20"></div>

								<p><?= fnValor($qrCount['QTD_EMAIL'], 0) ?></p>
								<p><b>Quantidade de emails na lista (no período)</b></p>

								<div class="push20"></div>

							</div>

						</div>

						<div class="push20"></div>

						<div class="row">

							<div class="col-md-12">

								<div class="push20"></div>

								<table class="table table-bordered table-hover tablesorter">

									<thead>
										<tr>
											<th><small>Email</small></th>
											<th><small>Usuário</small></th>
											<th><small>Dt. Cadastro</small></th>
											<th><small>Cadastros</small></th>
											<th class="{sorter: false}"></th>
										</tr>
									</thead>
									<tbody id="relatorioConteudo">

										<?php

										$ARRAY_VENDEDOR1 = array(
											'sql' => "select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa",
											'cod_empresa' => $cod_empresa,
											'conntadm' => $connAdm->connAdm(),
											'IN' => 'N',
											'nomecampo' => '',
											'conntemp' => '',
											'SQLIN' => ""
										);
										$ARRAY_VENDEDOR = fnUniVENDEDOR($ARRAY_VENDEDOR1);

										$sql = "SELECT COD_BLKLIST FROM BLACKLIST_EMAIL
										    WHERE COD_EMPRESA = $cod_empresa
										    AND DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
										    $andUsuario
									    	$andEmail";

										//fnEscreve($sql);

										$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
										$total_itens_por_pagina = mysqli_num_rows($retorno);

										$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										$sql = "SELECT COD_BLKLIST, 
													   DES_EMAIL, 
													   COD_USUCADA, 
													   DAT_CADASTR,
													   (SELECT COUNT(CL.COD_CLIENTE) FROM CLIENTES CL 
													   	WHERE CL.DES_EMAILUS = BE.DES_EMAIL
													   	AND CL.COD_EMPRESA = $cod_empresa) AS QTD_EMAIL
												FROM BLACKLIST_EMAIL BE
											    WHERE COD_EMPRESA = $cod_empresa
											    AND DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
											    $andUsuario
										    	$andEmail
											    LIMIT $inicio,$itens_por_pagina";

										// fnEscreve($sql);
										//fnTestesql(connTemp($cod_empresa,''),$sql);

										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

										$count = 0;

										while ($qrRet = mysqli_fetch_assoc($arrayQuery)) {

											$count++;
											$NOM_ARRAY_NON_VENDEDOR = (array_search($qrRet['COD_USUCADA'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));

											if ($qrRet['COD_USUCADA'] == 9999) {
												$usuario = "Integração";
											} else {
												$usuario = $ARRAY_VENDEDOR[$NOM_ARRAY_NON_VENDEDOR]['NOM_USUARIO'];
											}

											// $sqlCount = "SELECT COD_CLIENTE FROM CLIENTES 
											// 			 WHERE COD_EMPRESA = $cod_empresa 
											// 			 AND DES_EMAILUS = $qrRet['DES_EMAIL']";

											// $arrayCount = mysqli_query(connTemp($cod_empresa,''),$sqlCount);


										?>
											<tr>
												<td><small><?= $qrRet['DES_EMAIL'] ?></small></td>
												<td><small><?= $usuario ?></small></td>
												<td><small><?= fnDataShort($qrRet['DAT_CADASTR']) ?></small></td>
												<td><small><?= fnValor($qrRet['QTD_EMAIL'], 0) ?></small></td>
												<td class="text-center">
													<small>
														<div class="btn-group dropdown dropleft">
															<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																ações &nbsp;
																<span class="fas fa-caret-down"></span>
															</button>
															<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																<li class="text-info"><a href='javascript:void(0)' class='addBox' data-url="action.do?mod=<?php echo fnEncode(1547) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idm=<?php echo fnEncode($qrRet['COD_BLKLIST']) ?>&idp=<?= $pagina ?>&pop=true" data-title="Clientes / <?= $qrRet['DES_EMAIL'] ?>"><i class='fal fa-list'></i> Lista </a></li>
																<?php if ($qrRet['QTD_EMAIL'] == 0) { ?>
																	<li class="text-danger"><a href="javascript:void(0)" onclick='excluirEmail("<?= fnEncode($qrRet['COD_BLKLIST']) ?>","<?= $qrRet['DES_EMAIL'] ?>")'><i class='fal fa-trash-alt'></i> Excluir </a></li>
																<?php } ?>
																<!-- <li class="divider"></li> -->
																<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
															</ul>
														</div>
													</small>
												</td>
											</tr>


										<?php
										}
										?>
									</tbody>

									<tfoot>
										<tr>
											<th colspan="100">
												<a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
											</th>
										</tr>
										<tr>
											<th class="" colspan="100">
												<center>
													<ul id="paginacao" class="pagination-sm"></ul>
												</center>
											</th>
										</tr>
									</tfoot>

								</table>

							</div>


						</div>

						<input type="hidden" name="PAGINA" id="PAGINA" value="<?= $pagina ?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<div class="push5"></div>



						<div class="push50"></div>

						<div class="push"></div>

					</div>

				</div>
			</div>
			<!-- fim Portlet -->
		</div>
	</form>
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

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script>
	//datas
	$(function() {

		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			maxDate: 'now',
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		$("#DAT_INI_GRP").on("dp.change", function(e) {
			$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
		});

		$("#DAT_FIM_GRP").on("dp.change", function(e) {
			$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
		});

		$(".exportarCSV").click(function() {
			$.confirm({
				title: 'Exportação',
				content: '' +
					'<form action="" class="formName">' +
					'<div class="form-group">' +
					'<label>Insira o nome do arquivo:</label>' +
					'<input type="text" placeholder="Nome" class="nome form-control" required />' +
					'</div>' +
					'</form>',
				buttons: {
					formSubmit: {
						text: 'Gerar',
						btnClass: 'btn-blue',
						action: function() {
							var nome = this.$content.find('.nome').val();
							if (!nome) {
								$.alert('Por favor, insira um nome');
								return false;
							}

							$.confirm({
								title: 'Mensagem',
								type: 'green',
								icon: 'fa fa-check-square-o',
								content: function() {
									var self = this;
									return $.ajax({
										url: "relatorios/ajxEmailBlacklist.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
										data: $('#formulario').serialize(),
										method: 'POST'
									}).done(function(response) {
										self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
										var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
										SaveToDisk('media/excel/' + fileName, fileName);
										console.log(response);
									}).fail(function() {
										self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
									});
								},
								buttons: {
									fechar: function() {
										//close
									}
								}
							});
						}
					},
					cancelar: function() {
						//close
					},
				}
			});
		});

	});

	function excluirEmail(cod_blklist, des_email) {
		$.alert({
			icon: 'fal fa-exclamation-triangle',
			title: "Aviso",
			content: 'Deseja deletar <i>' + des_email + '</i> da blacklist?',
			backgroundDismiss: true,
			type: 'orange',
			buttons: {
				Cancelar: function() {

				},
				"Confirmar": {
					btnClass: 'btn-primary',
					action: function() {
						$.ajax({
							type: "POST",
							url: "relatorios/ajxEmailBlacklist.do?id=<?= fnEncode($cod_empresa) ?>&opcao=exc",
							data: {
								COD_BLKLIST: cod_blklist
							},
							success: function(data) {

								$.alert({
									icon: 'fal fa-check',
									title: "Sucesso",
									content: "Ação concluída.",
									type: 'green',
									buttons: {
										Ok: function() {
											reloadPage($("#PAGINA").val());
										}
									}
								});

							},

							error: function() {
								$.alert('Algo deu errado!');
							}

						});
					}
				}
			}
		});
	}

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxEmailBlacklist.do?opcao=paginar&id=<?= fnEncode($cod_empresa) ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
			},
			error: function(data) {
				console.log(data);
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
	}
</script>