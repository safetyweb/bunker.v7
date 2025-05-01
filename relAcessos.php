<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$log_unifica = "";
$hoje = "";
$dias30 = "";
$msgRetorno = "";
$msgTipo = "";
$dat_ini = "";
$dat_fim = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$disableEmpresa = "";
$checkUnifica = "";
$groupUnifica = "";
$cod_univendUsu = "";
$qtd_univendUsu = 0;
$lojasAut = "";
$usuReportAdm = "";
$lojasReportAdm = "";
$formBack = "";
$andEmpresa = "";
$lojasSelecionadas = "";
$retorno = "";
$inicio = "";
$qrAcesso = "";
$status = "";
$content = "";



// definir o numero de itens por pagina
$itens_por_pagina = 50;

// Página default
$pagina = 1;
$hashLocal = mt_rand();
$log_unifica = "";

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
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
		$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
		$cod_univend = @$_REQUEST['COD_UNIVEND'];
		if (empty(@$_REQUEST['LOG_UNIFICA'])) {
			$log_unifica = 'N';
		} else {
			$log_unifica = @$_REQUEST['LOG_UNIFICA'];
		}

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '' && $opcao != 0) {
		}
	}
}

//busca dados da url	
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
		$disableEmpresa = "disabled";
	}
} else {
	$cod_empresa = 0;
	$disableEmpresa = "";
	//fnEscreve('entrou else');
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

if ($log_unifica == "S") {
	$checkUnifica = "checked";
	$groupUnifica = "GROUP BY LA.COD_USUARIO";
} else {
	$checkUnifica = "";
	$groupUnifica = "";
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
						<span class="text-primary"> <?php echo $NomePg; ?> <?php echo $nom_empresa; ?></span>
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
										<label for="inputName" class="control-label required">Data Inicial</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Final</label>

										<div class="input-group date datePicker" id="DAT_FIM_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Unificar Acessos</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_UNIFICA" id="LOG_UNIFICA" class="switch" value="S" <?= $checkUnifica ?>>
											<span></span>
										</label>
									</div>
								</div>

							</div>

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
											<th><small>Acessos</small></th>
											<th><small>Tipo Usuário</small></th>
											<th class="text-center"><small>Data do Acesso</small></th>
											<th class="text-center"><small>Tempo de Acesso</small></th>
											<th><small>IP de Acesso</small></th>
											<th><small>Porta</small></th>
											<th width="5%"><small>Online</small></th>
										</tr>
									</thead>
									<tbody id="relatorioConteudo">

										<?php

										if ($cod_empresa != '' && $cod_empresa != 0) {
											$andEmpresa = "AND LA.COD_EMPRESA = $cod_empresa ";
										} else {
											$andEmpresa = "";
										}

										// Filtro por Grupo de Lojas
										include "filtroGrupoLojas.php";

										$sql = "SELECT LA.ID_CESSO FROM LOG_ACESSO LA
												INNER JOIN USUARIOS US ON US.COD_USUARIO = LA.COD_USUARIO
												INNER JOIN TIPOUSUARIO TU ON TU.COD_TPUSUARIO = US.COD_TPUSUARIO
												LEFT JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = US.COD_UNIVEND
												LEFT JOIN EMPRESAS EM ON EM.COD_EMPRESA = LA.COD_EMPRESA
												WHERE
												LA.DATA_ACESSO BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
												$andEmpresa
												AND US.COD_UNIVEND IN($lojasSelecionadas)
												$groupUnifica
											";

										//fnEscreve($sql);

										$retorno = mysqli_query($connAdm->connAdm(), $sql);
										$total_itens_por_pagina = mysqli_num_rows($retorno);

										$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										$sql = "SELECT LA.*, ";

										if ($log_unifica == "S") {
											$sql .= "COUNT(LA.ID_CESSO) QTD_ACESSOS, 
													ifnull(TIMESTAMPDIFF(MINUTE ,DATA_ACESSO,DATA_LOGOFF),0) TEMPO_CONECTADO, ";
										}


										$sql .= "TU.DES_TPUSUARIO, EM.NOM_FANTASI, UV.NOM_FANTASI AS UNIDADE FROM LOG_ACESSO LA
												INNER JOIN USUARIOS US ON US.COD_USUARIO = LA.COD_USUARIO
												INNER JOIN TIPOUSUARIO TU ON TU.COD_TPUSUARIO = US.COD_TPUSUARIO
												LEFT JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = US.COD_UNIVEND	
												LEFT JOIN EMPRESAS EM ON EM.COD_EMPRESA = LA.COD_EMPRESA
												WHERE
												LA.DATA_ACESSO BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
												$andEmpresa
												AND US.COD_UNIVEND IN($lojasSelecionadas)
												$groupUnifica
												ORDER BY LA.DATA_ACESSO DESC
												LIMIT $inicio,$itens_por_pagina";

										//fnEscreve($sql);
										//fnTestesql(connTemp($cod_empresa,''),$sql);										

										$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

										$count = 0;

										while ($qrAcesso = mysqli_fetch_assoc($arrayQuery)) {

											if ($qrAcesso['COD_ALTERACAO'] == 1) {
												$status = "<span class='fas fa-circle text-success'></span>";
											} else {
												$status = "<span class='fas fa-circle text-danger'></span>";
											}

											$count++;

										?>
											<tr>
												<td><small><?= $qrAcesso['UNIDADE'] ?></small></td>
												<td><small><?= $qrAcesso['NOM_USUARIO'] ?></small></td>
												<td><small><?= @$qrAcesso['QTD_ACESSOS'] ?></small></td>
												<td><small><?= $qrAcesso['DES_TPUSUARIO'] ?></small></td>
												<td class="text-center"><small><?= fnDataFull($qrAcesso['DATA_ACESSO']) ?></small></td>
												<td><small><?= @$qrAcesso['TEMPO_CONECTADO'] ?></small></td>
												<td><small><?= $qrAcesso['IP_ACESSO'] ?></small></td>
												<td><small><?= $qrAcesso['PORTA_ACESSO'] ?></small></td>
												<!-- <td><small><?= $qrAcesso['DATA_LOGOFF'] ?></small></td> -->
												<td class="text-center"><small><?= $status ?></small></td>
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

						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?= $lojasSelecionadas ?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

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
										url: "ajxRelAcessos.do?opcao=exportar&nomeRel=" + nome,
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
			url: "ajxRelAcessos.do?idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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

	function abreDetail(idBloco) {
		var idItem = $('.abreDetail_' + idBloco)
		if (!idItem.is(':visible')) {
			idItem.show();
			$('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');
		} else {
			idItem.hide();
			$('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
		}
	}
</script>