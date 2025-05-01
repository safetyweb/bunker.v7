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
$qtd_produto = 0;
$qtd_relacionada = 0;
$cod_produto = "";
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
$countLinha = "";
$countRegistro = "";
$countQuebra = "";
$qrListaVendas = "";
$cod_loop = "";
$lojasSelecionadas = "";
$content = "";

$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
//$hoje = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 2 days')));
$qtd_produto = 10;
$qtd_relacionada = 5;
$cod_produto = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_univend = @$_POST['COD_UNIVEND'];
		$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
		$cod_produto = fnLimpaCampoZero(@$_POST['COD_PRODUTO']);
		$qtd_produto = fnLimpaCampoZero(@$_POST['QTD_PRODUTO']);
		$qtd_relacionada = fnLimpaCampoZero(@$_POST['QTD_RELACIONADA']);

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
//fnEscreve($qtd_produto);
//fnEscreve($qtd_relacionada);

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"> <?php echo $NomePg; ?></span>
				</div>

				<?php
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

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
									</div>
								</div>

								<div class="col-md-6">
									<label for="inputName" class="control-label required">Produto </label>
									<div class="input-group">
										<span class="input-group-btn">
											<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary addBox" data-url="action.php?mod=<?php echo fnEncode(1062) ?>&id=<?php echo fnEncode($cod_empresa) ?>&tipo=rel&pop=true" data-title="Busca Produtos"><i class="fa fa-search" aria-hidden="true"></i></a>
										</span>
										<input type="text" name="DES_PRODUTO" id="DES_PRODUTO" class="form-control input-sm leituraOff" style="border-radius: 0 3px 3px  0;" readonly="readonly" placeholder="Procurar produto específico...">
										<input type="hidden" name="COD_PRODUTO" id="COD_PRODUTO" value="">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Lista de Produtos <small>(mais vendidos)</small></label>
										<select data-placeholder="escolha a quantidade" name="QTD_PRODUTO" id="QTD_PRODUTO" class="chosen-select-deselect">
											<option value="0">&nbsp;</option>
											<option value="10">10</option>
											<option value="50">50</option>
											<option value="100">100</option>
											<option value="200">200</option>
											<option value="500">500</option>
										</select>
										<div class="help-block with-errors"></div>
									</div>
									<script>
										$("#formulario #QTD_PRODUTO").val(<?php echo $qtd_produto; ?>).trigger("chosen:updated");
									</script>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Produtos Relacionados</label>
										<select data-placeholder="escolha a quantidade" name="QTD_RELACIONADA" id="QTD_RELACIONADA" class="chosen-select-deselect">
											<option value="0">&nbsp;</option>
											<option value="5">5</option>
											<option value="10">10</option>
										</select>
										<div class="help-block with-errors"></div>
									</div>
									<script>
										$("#formulario #QTD_RELACIONADA").val(<?php echo $qtd_relacionada; ?>).trigger("chosen:updated");
									</script>
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
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>


							</div>

						</fieldset>

						<div class="push20"></div>

						<div class="row">

							<div class="col-md-12" id="div_Produtos">

								<div class="push20"></div>


								<?php

								$sql = "CALL SP_DEFINE_TOP_PRODUTOS(
															" . $cod_empresa . ",
															'" . fnDataSql($dat_ini) . "',
															'" . fnDataSql($dat_fim) . "',															
															'" . $qtd_produto . "',
															'" . $qtd_relacionada . "',								
															'" . $cod_produto . "'								
															) ";

								//fnEscreve($sql);
								$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

								$countLinha = 1;
								$countRegistro = 1;
								$countQuebra = 1;



								while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

									if ($countLinha == 0) {
										echo "<pre>";
										print_r($qrListaVendas);
										echo "</pre>";
									}

									if ($countRegistro == 1) {
										$cod_loop = $qrListaVendas['COD_PRODUTO_ORIGEM'];
										echo "<div class='col-md-3'>";
										echo "<b><small>" . $qrListaVendas['PRODUTO_ORIGEM'] . "</small></b><br/>";
										echo "<span class='label-as-badge text-center label-success' style='border-radius: 3px;'><span style='color: #fff; padding: 0 3px 2px 3px; font-size: 10px;'>" . @$qrListaVendas['QTD_ITEM_ORIGEM'] . "</span></span>&nbsp;";
										echo "<span class='label-as-badge text-center label-success' style='border-radius: 3px;'><span style='color: #fff; padding: 0 3px 2px 3px; font-size: 10px;'>" . @$qrListaVendas['COD_EXTERNO_ORIGEM'] . "</span></span>&nbsp;";
										echo "<div class='push3'></div>";
									}

								?>
									<!--<b><?php echo $countRegistro; ?> / <?php echo $qrListaVendas['COD_PRODUTO_ORIGEM']; ?></b>-->
									- <span class="f12"><?php echo $qrListaVendas['PRODUTO']; ?></small>&nbsp;
										<span class="label-as-badge text-center label-info" style="border-radius: 3px;"><span style="color: #fff; padding: 0 3px 2px 3px; font-size: 9px;"><?php echo $qrListaVendas['QTD_ITEM']; ?></span></span>&nbsp;
										<span class="label-as-badge text-center label-info" style="border-radius: 3px;"><span style="color: #fff; padding: 0 3px 2px 3px; font-size: 9px;"><?php echo fnValor($qrListaVendas['PERCENTUAL_ITEM'], 2); ?>%</span></span>
										<span class="label-as-badge text-center label-default" style="border-radius: 3px;"><span style="color: #fff; padding: 0 3px 2px 3px; font-size: 9px;"><?php echo $qrListaVendas['COD_PRODUTO']; ?></span></span>
										<div class='push5'></div>

									<?php


									if ($countRegistro == $qtd_relacionada) {
										//if ($qrListaVendas['COD_PRODUTO_ORIGEM'] == $cod_loop ){
										echo "</div>";
										$countRegistro = 0;
									}

									if ($countQuebra == (4 * $qtd_relacionada)) {
										echo "<div class='push20'></div>";
										echo "<div class='push10'></div>";
										$countQuebra = 0;
									}

									$countRegistro++;
									$countLinha++;
									$countQuebra++;
								}

								//fnEscreve($countLinha-1);				
									?>



							</div>

						</div>
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
						<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
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



<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script>
	//datas
	$(function() {

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

	});


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

	function exportarCSV(btn) {
		log_detalhes = $(btn).attr('value');
		// alert(id);
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
									url: "relatorios/ajxRelProdutosTop.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&LOJAS=<?php echo $lojasSelecionadas; ?>&log_detalhes=" + log_detalhes,
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
	}
</script>