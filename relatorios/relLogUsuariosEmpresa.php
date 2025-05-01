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
$cod_tpusuario = "";
$log_estatus = "";
$dat_ini = "";
$dat_fim = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_univendUsu = "";
$qtd_univendUsu = 0;
$lojasAut = "";
$usuReportAdm = "";
$lojasReportAdm = "";
$formBack = "";
$qrListaTipoUsu = "";
$andEmpresa = "";
$andTipoUsu = "";
$andEstatus = "";
$lojasSelecionadas = "";
$retorno = "";
$inicio = "";
$qrUsuario = "";
$ativo = "";
$content = "";


// definir o numero de itens por pagina
$itens_por_pagina = 50;

// Página default
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
		$cod_tpusuario = fnLimpaCampoZero(@$_POST['COD_TPUSUARIO']);
		$log_estatus = fnLimpaCampo(@$_POST['LOG_ESTATUS']);
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
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

//busca revendas do usuário
include "unidadesAutorizadas.php";

//fnMostraForm();	
//fnEscreve($dat_ini);
//fnEscreve($dat_fim);
//fnEscreve($cod_univendUsu);
//fnEscreve($qtd_univendUsu);
//fnEscreve($lojasAut);
//fnEscreve($usuReportAdm);
//fnEscreve($lojasReportAdm);

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

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Grupo de Lojas</label>
										<?php include "grupoLojasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Região</label>
										<?php include "grupoRegiaoMulti.php"; ?>
									</div>
								</div>

							</div>

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

										<label for="inputName" class="control-label">Tipo de Usuário</label>
										<select data-placeholder="Selecione o tipo de usuário" name="COD_TPUSUARIO" id="COD_TPUSUARIO" class="chosen-select-deselect">
											<option value=""></option>
											<?php
											$sql = "select COD_TPUSUARIO, DES_TPUSUARIO from tipousuario order by des_tpusuario ";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrListaTipoUsu = mysqli_fetch_assoc($arrayQuery)) {
												echo "
																  <option value='" . $qrListaTipoUsu['COD_TPUSUARIO'] . "'>" . $qrListaTipoUsu['DES_TPUSUARIO'] . "</option> 
																";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
										<script type="text/javascript">
											$("#formulario #COD_TPUSUARIO").val('<?= $cod_tpusuario ?>').trigger("chosen:updated");
										</script>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">

										<label for="inputName" class="control-label">Status</label>
										<select data-placeholder="Selecione o status" name="LOG_ESTATUS" id="LOG_ESTATUS" class="chosen-select-deselect">
											<option value=""></option>
											<option value="S">Ativos</option>
											<option value="N">Inativos</option>
											<option value="I">Indefinidos</option>
										</select>
										<div class="help-block with-errors"></div>
										<script type="text/javascript">
											$("#formulario #LOG_ESTATUS").val('<?= $log_estatus ?>').trigger("chosen:updated");
										</script>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>


							</div>

						</fieldset>
					</div>
				</div>
			</div>

			<div class="push20"></div>

			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="push20"></div>

						<div class="row">
							<div class="col-md-12">

								<div class="push20"></div>

								<table class="table table-bordered table-hover tablesorter">

									<thead>
										<tr>
											<th><small>Loja</small></th>
											<th><small>Usuário</small></th>
											<th><small>Login</small></th>
											<th><small>Tipo Usu.</small></th>
											<th><small>Tipo Perfil.</small></th>
											<th><small>Dt. Cadastro</small></th>
											<th><small>Dt. Alteração</small></th>
											<th><small>Usu. Exclusão</small></th>
											<th><small>Dt. Exclusão</small></th>
											<th width="5%"><small>Ativo</small></th>
										</tr>
									</thead>
									<tbody id="relatorioConteudo">

										<?php

										if ($cod_empresa != '' && $cod_empresa != 0) {
											$andEmpresa = "AND U.COD_EMPRESA = $cod_empresa ";
										} else {
											$andEmpresa = "";
										}

										if ($cod_tpusuario != '' && $cod_tpusuario != 0) {
											$andTipoUsu = "AND U.COD_TPUSUARIO = $cod_tpusuario ";
										} else {
											$andTipoUsu = "";
										}

										if ($log_estatus != '' && $log_estatus != 'I') {
											$andEstatus = "AND U.LOG_ESTATUS = '$log_estatus' ";
										} else if ($log_estatus == 'I') {
											$andEstatus = "AND (U.LOG_ESTATUS = '' OR U.LOG_ESTATUS IS NULL)  ";
										} else {
											$andEstatus = "";
										}

										// Filtro por Grupo de Lojas
										include "filtroGrupoLojas.php";

										$sql = "SELECT U.COD_USUARIO
											FROM USUARIOS U
											INNER JOIN TIPOUSUARIO TP ON TP.COD_TPUSUARIO=u.COD_TPUSUARIO
											LEFT JOIN UNIDADEVENDA UN ON UN.COD_UNIVEND = U.COD_UNIVEND
											WHERE U.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
											AND U.COD_UNIVEND IN($lojasSelecionadas)
											$andEmpresa
											$andTipoUsu
											$andEstatus
									";

										//fnEscreve($sql);

										$retorno = mysqli_query($connAdm->connAdm(), $sql);
										$total_itens_por_pagina = mysqli_num_rows($retorno);

										$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


										// Filtro por Grupo de Lojas
										include "filtroGrupoLojas.php";

										$sql = "SELECT  
										      U.COD_UNIVEND,
										      UN.NOM_FANTASI,
											  U.NOM_USUARIO,
											  U.LOG_USUARIO,
											  U.DAT_CADASTR,
											  U.DAT_ALTERAC,
											  U.DAT_EXCLUSA,
											  (SELECT US.NOM_USUARIO FROM USUARIOS US WHERE US.COD_USUARIO = U.COD_EXCLUSA) AS USU_EXCLUSA,
											  U.LOG_ESTATUS,
											  TP.DES_TPUSUARIO,
											  (SELECT CONCAT_WS(',',DES_PERFILS) FROM perfil WHERE COD_PERFILS IN (U.COD_PERFILS)) AS DES_PERFILS
										FROM USUARIOS U
										INNER JOIN TIPOUSUARIO TP ON TP.COD_TPUSUARIO=U.COD_TPUSUARIO
										LEFT JOIN UNIDADEVENDA UN ON UN.COD_UNIVEND = U.COD_UNIVEND
										WHERE U.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
										AND U.COD_UNIVEND IN($lojasSelecionadas)
										$andEmpresa
										$andTipoUsu
										$andEstatus
										ORDER BY COD_USUARIO desc
										LIMIT $inicio,$itens_por_pagina";

										//fnEscreve($sql);
										//fnTestesql(connTemp($cod_empresa,''),$sql);

										$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

										$count = 0;

										while ($qrUsuario = mysqli_fetch_assoc($arrayQuery)) {

											if ($qrUsuario['LOG_ESTATUS'] == "S") {
												$ativo = "<span class='fas fa-check text-success'></span>";
											} else if ($qrUsuario['LOG_ESTATUS'] == "N") {
												$ativo = "<span class='fas fa-times text-danger'></span>";
											} else {
												$ativo = "<span class='f14'>Indefinido</span>";
											}


											$count++;

										?>
											<tr>
												<td><small><?= $qrUsuario['NOM_FANTASI'] ?></small></td>
												<td><small><?= $qrUsuario['NOM_USUARIO'] ?></small></td>
												<td><small><?= $qrUsuario['LOG_USUARIO'] ?></small></td>
												<td><small><?= $qrUsuario['DES_TPUSUARIO'] ?></small></td>
												<td><small><?= $qrUsuario['DES_PERFILS'] ?></small></td>
												<td class="text-center"><small><?= fnDataFull($qrUsuario['DAT_CADASTR']) ?></small></td>
												<td class="text-center"><small><?= fnDataFull($qrUsuario['DAT_ALTERAC']) ?></small></td>
												<td><small><?= $qrUsuario['USU_EXCLUSA'] ?></small></td>
												<td class="text-center"><small><?= fnDataFull($qrUsuario['DAT_EXCLUSA']) ?></small></td>
												<td class="text-center"><?= $ativo ?></td>
											</tr>


										<?php
										}
										?>
									</tbody>

									<tfoot>
										<tr>
											<th colspan="100">
												<a class="btn btn-info btn-sm exportarCSV"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar </a>
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

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?= $lojasSelecionadas ?>">
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
										url: "relatorios/ajxRelLogUsuariosEmpresa.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&LOJAS=<?php echo $lojasSelecionadas; ?>",
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

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxRelLogUsuariosEmpresa.do?idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&opcao=paginar",
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