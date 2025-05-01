<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hoje = "";
$dias30 = "";
$msgRetorno = "";
$msgTipo = "";
$dat_ini = "";
$dat_fim = "";
$cod_vendapdv = "";
$NUM_CGCECPF = "";
$CUPOM = "";
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
$andCodPDV = "";
$NUM_CGCECPF1 = "";
$CUPOM1 = "";
$lojasSelecionadas = "";
$retorno = "";
// $totalitens_por_pagina = 0;
$inicio = "";
$sqlcomand = "";
$countLinha = "";
$sqldadosr = "";
$itens_por_pagina = 50;
$pagina = 1;
$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));
$cod_univend = "9999"; //todas revendas - default

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
		$cod_vendapdv = @$_POST['COD_VENDAPDV'];
		$NUM_CGCECPF = @$_POST['NUM_CGCECPF'];
		$CUPOM = @$_REQUEST['CUPOM'];


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
if (is_array($cod_univend)) {
	$cod_univend = implode(",", $cod_univend); // Converte array em string
}

if (strlen($cod_univend) == 0) {
	$cod_univend = "9999";
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
						<span class="text-primary"> <?php echo $NomePg; ?>/ <?php echo $nom_empresa; ?></span>
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

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
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

							</div>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">ID PDV</label>
										<input type="text" class="form-control input-sm" name="COD_VENDAPDV" id="COD_VENDAPDV" value="<?php echo $cod_vendapdv; ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">CPF</label>
										<input type="text" class="form-control input-sm" name="NUM_CGCECPF" id="COD_VENDAPDV" value="<?php echo $NUM_CGCECPF; ?>">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">CUPOM</label>
										<input type="text" class="form-control input-sm" name="CUPOM" id="CUPOM" value="<?php echo $CUPOM; ?>">
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




						<style>
							table {
								border-collapse: collapse;
							}

							th,
							td {
								border: 1px solid orange;
								padding: 10px;
								text-align: left;
							}
						</style>


						<table class="table table-bordered table-hover tablesorter">
							<thead>
								<tr>
									<th><small>Data/Hora</small></th>
									<th><small>Usuario</small></th>
									<th><small>Máquina</small></th>
									<th><small>Cpf/Cnpj</small></th>
									<th><small>Id Pdv</small></th>
									<th><small>IP</small></th>
									<th><small>Unidade Venda</small></th>
									<th><small>Mensagem</small></th>
									<th class="{ sorter: false } text-center"><small>Entrada</small></th>
									<th class="{ sorter: false } text-center"><small>Retorno</small></th>

								</tr>
							</thead>


							<tbody id="relatorioConteudo">

								<?php

								if ($cod_vendapdv == "") {
									$andCodPDV = " ";
								} else {
									$andCodPDV = "COD_PDV = '" . $cod_vendapdv . "' AND ";
								}
								if ($NUM_CGCECPF != '' && $NUM_CGCECPF != 0) {
									$NUM_CGCECPF1 = "and NUM_CGCECPF='" . $NUM_CGCECPF . "'";
								} else {
									$NUM_CGCECPF1 = '';
								}
								if ($CUPOM != '' && $CUPOM != 0) {
									$CUPOM1 = "and CUPOM in($CUPOM)";
								} else {
									$CUPOM1 = '';
								}

								// $sql="SHOW TABLE STATUS LIKE 'origemvenda';";

								$sql = "SELECT  DAT_CADASTR
								from origemvenda
								inner join msg_venda on origemvenda.COD_ORIGEM=msg_venda.ID
								where 
								$andCodPDV
								COD_EMPRESA = $cod_empresa
								AND DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'	
                                $NUM_CGCECPF1 
                                $CUPOM1    
								AND COD_UNIVEND IN($lojasSelecionadas)
						";

								$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
								$totalitens_por_pagina = mysqli_num_rows($retorno);
								// fnEscreve($totalitens_por_pagina);
								$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

								//variavel para calcular o início da visualização com base na página atual
								$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

								//,MSG,DES_VENDA 
								//select dinâmico do relatório
								$sql = "SELECT  DAT_CADASTR, NOM_USUARIO, ID_MAQUINA ,NUM_CGCECPF, COD_PDV, MSG,origem_retorno, COD_ORIGEM,COD_UNIVEND, IP
								from origemvenda
								inner join msg_venda on origemvenda.COD_ORIGEM=msg_venda.ID
								where 
								$andCodPDV
								COD_EMPRESA = $cod_empresa
								AND DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'	
                                                                $NUM_CGCECPF1 
                                                                $CUPOM1 
                                                                    
								AND COD_UNIVEND IN($lojasSelecionadas)
                                                               AND  case when origem_retorno !='' then '1'
                                                                         when origem_retorno IS NOT NULL then '2'
                                                                           ELSE '0' END IN ('1','2','0')

								order by origemvenda.COD_ORIGEM desc limit $inicio,$itens_por_pagina
							  ";

								$sqlcomand = mysqli_query(connTemp($cod_empresa, ''), $sql);

								//fnEscreve($sql);

								$countLinha = 1;
								while ($sqldadosr = mysqli_fetch_assoc($sqlcomand)) {

									echo '<tr>';
									echo '<td><small> ' . fnFormatDateTime($sqldadosr['DAT_CADASTR']) . '</small></td>';
									echo '<td><small> ' . $sqldadosr['NOM_USUARIO'] . '</small></td>';
									echo '<td><small> ' . $sqldadosr['ID_MAQUINA'] . '</small></td>';
									echo '<td><small> ' . $sqldadosr['NUM_CGCECPF'] . '</small></td>';
									echo '<td><small> ' . $sqldadosr['COD_PDV'] . '</small></td>';
									echo '<td><small> ' . $sqldadosr['IP'] . '</small></td>';
									echo '<td><small> ' . $sqldadosr['COD_UNIVEND'] . '</small></td>';
									echo '<td><small> ' . $sqldadosr['MSG'] . '</small></td>';
									echo '<td class="text-center">';
								?>
									<a class="btn btn-xs btn-default addBox" data-url="action.php?mod=<?php echo fnEncode(1203); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idR=<?php echo fnEncode($sqldadosr['COD_ORIGEM']); ?>&pop=true" data-title="XML Recebido"><small><i class="fa fa-code"></i></small></a>
									<?php
									echo '</td>';
									echo '<td class="text-center">';
									?>
									<a class="btn btn-xs btn-default addBox" data-url="action.php?mod=<?php echo fnEncode(1259); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idR=<?php echo fnEncode($sqldadosr['COD_ORIGEM']); ?>&pop=true" data-title="XML Enviado"><small><i class="fa fa-code"></i></small></a>
								<?php
									echo '</tr>';
									$countLinha++;
								}

								?>


							</tbody>

							<tfoot>
								<tr>
									<th class="" colspan="100">
										<center>
											<ul id="paginacao" class="pagination-sm"></ul>
										</center>
									</th>
								</tr>
							</tfoot>

						</table>

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


						<div class="push5"></div>

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">


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


	});

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxRelVendaWS.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&andCodPDV=<?php echo $andCodPDV; ?>",
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
</script>