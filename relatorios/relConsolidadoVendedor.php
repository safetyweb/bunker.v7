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
$dat_ini = "";
$dat_fim = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_cliente = "";
$formBack = "";
$lojasSelecionadas = "";
$countLinha = "";
$qrListaVendas = "";
$loja = "";
$val_vendasemticket = "";
$tmVct = "";
$tmVst = "";
$varTm = "";
$total_reg = 0;

$hashLocal = mt_rand();
//inicialização de variáveis
//$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 30 days')));
$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 2 days')));
$cod_univend = "9999";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;
		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_univend = @$_POST['COD_UNIVEND'];
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];
		if ($opcao != '' && $opcao != 0) {
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

if (is_string($cod_univend) && strlen($cod_univend) == 0) {
	$cod_univend = "9999";
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

//fnMostraForm();
//fnEscreve($cod_cliente);

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"> <?php echo $NomePg; ?></span>
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

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasCombo.php"; ?>
									</div>
								</div>

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

						<div class="push20"></div>


						<div class="row">

							<div class="col-md-12" id="div_Produtos">

								<div class="push20"></div>

								<table class="table table-bordered table-hover  ">

									<thead>
										<tr>
											<th></th>
											<th class="text-center"><small>Vendedor</small></th>
											<th class="text-center"><small>Total <br />Vendas</small></th>
											<th class="text-center"><small>Total <br />Vendas (R$)</small></th>
											<th class="text-center"><small>Índice</small></th>
											<th class="text-center"><small>Tickets <br />Gerados</small></th> <!-- todas vendas fidelizadas -->
											<th class="text-center"><small>Vendas com <br />Ticket</small></th> <!-- qtd vendas com produtos gerados no ticket -->
											<th class="text-center"><small>Vendas com <br />Ticket (R$)</small></th> <!-- volume total com produtos gerados no ticket -->
											<th class="text-center"><small>Vendas sem <br />Ticket</small></th> <!-- qtd vendas avulsas -->
											<th class="text-center"><small>Vendas sem <br />Ticket (R$)</small></th> <!-- volume total vendas avulsas -->
											<th class="text-center"><small>TM <br />VCT</small></th> <!-- (R$ vendas com produtos de ticket / qtd vendas com ticket) -->
											<th class="text-center"><small>TM <br />VST</small></th> <!-- (R$ vendas sem ticket  / qtd vendas sem ticket) -->
											<th class="text-center"><small>Variação <br>TM </small></th> <!-- (VCT  / VST) -1 -->
										</tr>
									</thead>

									<?php

									$sql = "SELECT 
																A.COD_USUARIO COD_USUARIO,
																B.NOM_USUARIO NOM_USUARIO,
																A.COD_UNIVEND COD_UNIVEND,
																C.NOM_FANTASI NOM_UNIVEND,
																A.COD_VENDEDOR COD_VENDEDOR,
																SUM(A.VAL_VINCULADO) VAL_VINCULADO,
																SUM(A.QTD_TOTAVULSA)QTD_TOTAVULSA,
																SUM(A.QTD_TOTFIDELIZ)QTD_TOTFIDELIZ,
																ROUND((( SUM(A.QTD_TOTFIDELIZ) / SUM(A.QTD_TOTVENDA) )*100), 2)PCT_FIDELIZADO,
																SUM(A.VAL_TOTFIDELIZ)VAL_TOTFIDELIZ,
																SUM(A.VAL_TOTVENDA)VAL_TOTVENDA,
																SUM(A.VAL_RESGATE)VAL_RESGATE,
																SUM(A.VAL_CREDITOGERADO) VAL_CREDITOGERADO,
																SUM(IFNULL(A.VAL_TICKET,0)) VAL_TICKET,
																SUM(IFNULL(A.QTD_TICKET,0)) QTD_TICKET,
																SUM(IFNULL(A.QTD_TOTVENDA,0)) QTD_TOTVENDA
																FROM VENDAS_DIARIAS A
																LEFT JOIN $connAdm->DB.USUARIOS B ON B.COD_USUARIO = A.COD_VENDEDOR
																LEFT JOIN $connAdm->DB.UNIDADEVENDA C ON C.COD_UNIVEND = A.COD_UNIVEND
																WHERE 
																DATE_FORMAT(A.DAT_MOVIMENTO, '%Y-%m-%d') >= '$dat_ini' 
																AND DATE_FORMAT(A.DAT_MOVIMENTO, '%Y-%m-%d') <= '$dat_fim' 
																AND A.COD_EMPRESA = $cod_empresa 
																AND A.COD_UNIVEND IN($lojasSelecionadas)
															        GROUP BY A.COD_VENDEDOR,A.COD_UNIVEND
																ORDER BY C.NOM_UNIVEND, NOM_USUARIO ";

									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
									//fnEscreve($sql);

									$countLinha = 1;
									while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
										//monta primeiro cabeçalho
										if ($countLinha == 1) {
											$loja = $qrListaVendas['COD_UNIVEND'];
									?>
											<thead>
												<tr id="bloco_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
													<th width="50" class="text-center"><a href="javascript:void(0);" onclick="abreDetail(<?php echo $qrListaVendas['COD_UNIVEND']; ?>)" style="padding:10px;"><i class="fa fa-angle-right" aria-hidden="true"></i></a></th>
													<th colspan="12"><?php echo $qrListaVendas['NOM_UNIVEND']; ?></th>
												</tr>
											</thead>
											</tbody>
										<?php
										}
										//monta primeira linha
										if ($loja != $qrListaVendas['COD_UNIVEND']) {
											$loja = $qrListaVendas['COD_UNIVEND'];
										?>
											<thead>
												<tr id="bloco_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
													<th width="50" class="text-center"><a href="javascript:void(0);" onclick="abreDetail(<?php echo $qrListaVendas['COD_UNIVEND']; ?>)" style="padding:10px;"><i class="fa fa-angle-right" aria-hidden="true"></i></a></th>
													<th colspan="12"><?php echo $qrListaVendas['NOM_UNIVEND']; ?></th>
												</tr>
											</thead>
											</tbody>
										<?php
										}
										//fnEscreve($loja);
										$val_vendasemticket = $qrListaVendas['VAL_TOTVENDA'] - $qrListaVendas['VAL_TOTFIDELIZ'];
										$tmVct = $qrListaVendas['QTD_TICKET'] != 0 ? ($qrListaVendas['VAL_TICKET'] / $qrListaVendas['QTD_TICKET']) : 0;
										$tmVst = $qrListaVendas['QTD_TOTAVULSA'] != 0 ? ($val_vendasemticket / $qrListaVendas['QTD_TOTAVULSA']) : 0;
										$varTm =  $tmVst != 0 ? (($tmVct  / $tmVst) - 1) * 100 : 0;
										//( $total_reg == 0 ? 1 : $total_reg )
										?>
										<tr style="background-color: #fff; display: none;" class="abreDetail_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
											<td class="text-center"></td>
											<td><small><?php echo $qrListaVendas['COD_VENDEDOR']; ?></small> <?php echo $qrListaVendas['NOM_USUARIO']; ?></td>
											<td class="text-center"><small><?php echo fnValor($qrListaVendas['QTD_TOTVENDA'], 0); ?></small></td>
											<td class="text-center"><small><small>R$</small> <?php echo fnValor($qrListaVendas['VAL_TOTVENDA'], 2); ?></small></td>
											<td class="text-center"><small><?php echo fnValor($qrListaVendas['PCT_FIDELIZADO'], 2); ?>%</small></td>
											<td class="text-center"><small><?php echo fnValor($qrListaVendas['QTD_TOTFIDELIZ'], 0); ?></small></td>
											<td class="text-center"><small><?php echo fnValor($qrListaVendas['QTD_TICKET'], 0); ?></small></td>
											<td class="text-center"><small><small>R$</small> <?php echo fnValor($qrListaVendas['VAL_TICKET'], 2); ?></small></td>
											<td class="text-center"><small><?php echo fnValor($qrListaVendas['QTD_TOTAVULSA'], 0); ?></small></td>
											<td class="text-center"><small><small>R$</small> <?php echo fnValor($val_vendasemticket, 2); ?></small></td>
											<td class="text-center"><small><small>R$</small> <?php echo fnValor($tmVct, 2); ?></small></td>
											<td class="text-center"><small><small>R$</small> <?php echo fnValor($tmVst, 2); ?></small></td>
											<td class="text-center"><small><small>R$</small> <?php echo fnValor($varTm, 2); ?>%</small></td>
										</tr>
									<?php

										$countLinha++;
									}

									//fnEscreve($countLinha-1);				
									?>

									</tbody>
								</table>

							</div>

						</div>

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
			maxDate: moment().subtract(1, 'days'),
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
</script>