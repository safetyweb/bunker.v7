<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$array = "";
$key = "";
$default = "";
$cod_persona = "";
$pagina = "";
$hashLocal = "";
$hoje = "";
$dias30 = "";
$request = "";
$msgRetorno = "";
$msgTipo = "";
$dat_ini = "";
$dat_fim = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = "";
$qrBuscaEmpresa = "";
$nom_empresa = "";
$tip_retorno = "";
$casasDec = "";
$arrayParamAutorizacao = "";
$autoriza = "";
$formBack = "";
$qrListaPersonas = "";
$lojasSelecionadas = "";
$andPersonas = "";
$retorno = "";
$inicio = "";
$qrListaVendas = "";
$content = "";
$cod_controle = "";

function getInput($array, $key, $default = '')
{
	return isset($array[$key]) ? $array[$key] : $default;
}


// echo fnDebug('true');
// fnTesteSql();

// definir o numero de itens por pagina
$itens_por_pagina = 50;
$cod_persona = 0;
$pagina  = "1";

$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date('Y-m-d'));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(getInput($_POST, 'COD_EMPRESA'));
		$cod_univend = getInput($_POST, 'COD_UNIVEND');
		$cod_grupotr = getInput($_REQUEST, 'COD_GRUPOTR');
		$cod_tiporeg = getInput($_REQUEST, 'COD_TIPOREG');
		$dat_ini = fnDataSql(getInput($_POST, 'DAT_INI'));
		$dat_fim = fnDataSql(getInput($_POST, 'DAT_FIM'));
		@$cod_personas = fnLimpaCampoArray(@$_POST['COD_PERSONA']);

		$opcao = getInput($_REQUEST, 'opcao');
		$hHabilitado = getInput($_REQUEST, 'hHabilitado');
		$hashForm = getInput($_REQUEST, 'hashForm');

		if ($opcao != '') {
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(getInput($_GET, 'id'))))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(getInput($_GET, 'id'));
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, TIP_RETORNO FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];

		if ($tip_retorno == 1) {
			$casasDec = 0;
		} else {
			$casasDec = 2;
		}
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

//rotina de controle de acessos por módulo
include "moduloControlaAcesso.php";

if (fnControlaAcesso("1081", $arrayParamAutorizacao) === true) {
	$autoriza = 1;
} else {
	$autoriza = 0;
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
</style>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php
				$formBack = "1015";
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

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-sm-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Persona</label>

										<select data-placeholder="Selecione a persona desejada" name="COD_PERSONA[]" id="COD_PERSONA" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1">
											<option value=""></option>
											<?php

											$sql = "SELECT * from persona where cod_empresa = $cod_empresa and LOG_ATIVO = 'S' order by DES_PERSONA  ";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);
											while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {

												echo "
													<option value='" . $qrListaPersonas['COD_PERSONA'] . "'>" . ucfirst($qrListaPersonas['DES_PERSONA']) . "</option> 
												";
											}

											?>
										</select>

									</div>

								</div>

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
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>


							</div>
						</fieldset>

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="AUTORIZA" id="AUTORIZA" value="<?= $autoriza ?>" />
						<input type="hidden" name="CASAS_DEC" id="CASAS_DEC" value="<?= $casasDec ?>">
						<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
						<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<div class="push5"></div>

					</form>
				</div>
			</div>
		</div>

		<div class="push30"></div>

		<div class="portlet portlet-bordered">
			<div class="portlet-body">
				<div class="login-form">
					<div class="row">
						<div class="col-md-12" id="div_Produtos">
							<table class="table table-bordered table-hover tablesorter">

								<thead>
									<tr>
										<th><small>Código</small></th>
										<th><small>Nome</small></th>
										<th><small>Cpf</small></th>
										<th><small>Cartão</small></th>
										<th><small>Email</small></th>
										<th><small>Celular</small></th>
									</tr>
								</thead>

								<tbody id="relatorioConteudo">

									<?php

									// Filtro por Grupo de Lojas
									include "filtroGrupoLojas.php";

									// fnEscreve($cod_personas);

									$andPersonas = "";

									if ($cod_personas != '' && $cod_personas != 0) {
										$andPersonas = "AND CL.COD_CLIENTE IN(SELECT COD_CLIENTE FROM PERSONACLIENTES 
																					WHERE COD_EMPRESA = $cod_empresa 
																					AND COD_PERSONA IN($cod_personas))";
									}

									$sql = "SELECT 1
												FROM clientes CL
												WHERE CL.COD_EMPRESA = $cod_empresa
												AND CL.LOG_AVULSO = 'N'
												AND CL.LOG_ESTATUS = 'S'
												AND CL.COD_UNIVEND IN($lojasSelecionadas)
												$andPersonas
												AND CL.COD_CLIENTE NOT IN(
																SELECT COD_CLIENTE FROM vendas
																WHERE COD_EMPRESA = CL.COD_EMPRESA
																AND DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
												)";

									$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
									$total_itens_por_pagina = mysqli_num_rows($retorno);

									$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

									//variavel para calcular o início da visualização com base na página atual
									$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

									$sql = "SELECT 
														CL.COD_CLIENTE,
														CL.NOM_CLIENTE,
														CL.NUM_CGCECPF,
														CL.NUM_CARTAO,
														CL.DES_EMAILUS,
														CL.NUM_CELULAR
													FROM clientes CL
														WHERE CL.COD_EMPRESA = $cod_empresa
														AND CL.LOG_AVULSO = 'N'
														AND CL.LOG_ESTATUS = 'S'
														AND CL.COD_UNIVEND IN($lojasSelecionadas)
														$andPersonas
														AND CL.COD_CLIENTE NOT IN(
																		SELECT COD_CLIENTE FROM vendas
																		WHERE COD_EMPRESA = CL.COD_EMPRESA
																		AND DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
															)
												LIMIT $inicio, $itens_por_pagina";

									// FNeSCREVE($sql);

									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

									while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
									?>

										<tr>
											<td><small><?php echo $qrListaVendas['COD_CLIENTE']; ?></small></td>
											<?php
											if ($autoriza == 1) {
											?>
												<td><a href="action.do?mod=<?php echo fnEncode(1081); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE']; ?></a></td>
											<?php
											} else {
											?>
												<td><?php echo $qrListaVendas['NOM_CLIENTE']; ?></td>
											<?php
											}
											?>
											<td><small><?php echo fnMascaraCampo($qrListaVendas['NUM_CGCECPF']); ?></small></td>
											<td><small><?php echo fnMascaraCampo($qrListaVendas['NUM_CARTAO']); ?></small></td>
											<td><small><?php echo $qrListaVendas['DES_EMAILUS']; ?></small></td>
											<td><small><?php echo fnmasktelefone($qrListaVendas['NUM_CELULAR']); ?></small></td>
										</tr>

									<?php
									}

									?>

								</tbody>

								<tfoot>
									<tr>
										<th colspan="100">
											<a class="btn btn-info btn-sm exportarCSV"><i class="fa fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar </a>
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


<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script>
	//datas
	$(function() {

		var persona = '<?php echo $cod_personas; ?>';
		if (persona != 0 && persona != "") {
			//retorno combo multiplo - USUARIOS_ENV
			$("#formulario #COD_PERSONA").val('').trigger("chosen:updated");

			var sistemasUni = '<?php echo $cod_personas; ?>';
			var sistemasUniArr = sistemasUni.split(',');
			//opções multiplas
			for (var i = 0; i < sistemasUniArr.length; i++) {
				$("#formulario #COD_PERSONA option[value=" + Number(sistemasUniArr[i]).toString() + "]").prop("selected", "true");
			}
			$("#formulario #COD_PERSONA").trigger("chosen:updated");
		}

		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY'
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		$("#DAT_INI_GRP").on("dp.change", function(e) {
			$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
		});

		$("#DAT_FIM_GRP").on("dp.change", function(e) {
			$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
		});

		$("#DAT_INI").val("<?= fnDataShort($dat_ini) ?>");

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
								icon: 'fa fa-check-square',
								content: function() {
									var self = this;
									return $.ajax({
										url: "relatorios/ajxRelInatividade.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&itens_por_pagina=<?php echo $itens_por_pagina; ?>&idc=<?= fnEncode($cod_controle) ?>",
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
			url: "relatorios/ajxRelInatividade.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?= fnEncode($cod_controle) ?>&itens_por_pagina=<?php echo $itens_por_pagina; ?>&lojas=<?php echo $lojasSelecionadas ?>&idPage=" + idPage,
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
			},
			error: function() {
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