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
$itens_por_pagina = "";
$pagina = "";
$val_ini = "";
$val_fim = "";
$hashLocal = "";
$hoje = "";
$dias30 = "";
$cod_status = "";
$request = "";
$msgRetorno = "";
$msgTipo = "";
$cod_empresa = "";
$num_cgcecpf = "";
$num_cartao = "";
$dat_ini = "";
$dat_fim = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$sql = "";
$arrayQuery = "";
$qrBuscaEmpresa = "";
$nom_empresa = "";
$tip_retorno = "";
$casasDec = "";
$arrayParamAutorizacao = "";
$autoriza = "";
$cod_cliente = "";
$formBack = "";
$filtroSaldo = "";
$lojasSelecionadas = "";
$retorno = "";
$total_itens_por_pagina = "";
$cod_controle = "";
$sqlTotal = "";
$qrTotal = "";
$andCpf = "";
$condicaoCartao = "";
$inicio = "";
$ARRAY_UNIDADE1 = "";
$ARRAY_UNIDADE = "";
$limit = "";
$countLinha = "";
$qrListaVendas = "";
$email = "";
$sem_result = "";
$content = "";

function getInput($array, $key, $default = '')
{
	return isset($array[$key]) ? $array[$key] : $default;
}




// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina  = "1";
$val_ini = fnValorSql(0);
$val_fim = fnValorSql(9999999999);

$hashLocal = mt_rand();

//inicialização de variáveis
//$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 60 days')));
$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));
$cod_status = 11;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(getInput($_POST, 'COD_EMPRESA'));
		$num_cgcecpf = fnLimpaCampo(fnLimpaDoc(getInput($_POST, 'NUM_CGCECPF')));
		$num_cartao = fnLimpaCampo(getInput($_POST, 'NUM_CARTAO'));
		$cod_univend = getInput($_POST, 'COD_UNIVEND');
		$cod_grupotr = $_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = $_REQUEST['COD_TIPOREG'];
		$cod_status = getInput($_POST, 'COD_STATUS');
		$dat_ini = fnDataSql(getInput($_POST, 'DAT_INI'));
		$dat_fim = fnDataSql(getInput($_POST, 'DAT_FIM'));
		$val_ini = fnValorSql(fnLimpaCampo(getInput($_POST, 'VAL_INI')));
		$val_fim = fnValorSql(fnLimpaCampo(getInput($_POST, 'VAL_FIM')));
		/*
			if($val_fim == fnValorSql(0)){
				$val_fim = fnValorSql(9999999999);
			}
			*/

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

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
	$dat_ini = fnDataSql($hoje);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

//rotina de controle de acessos por módulo
include "moduloControlaAcesso.php";

if (fnControlaAcesso("1024", $arrayParamAutorizacao) === true) {
	$autoriza = 1;
} else {
	$autoriza = 0;
}

//fnMostraForm();
//fnEscreve($cod_cliente);

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
											<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="" required />
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
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Valor</label>
										<input type="text" class="form-control input-sm money" name="VAL_INI" id="VAL_INI" maxlength="50" value="<?= ($val_ini == fnValorSql(0)) ? '' : fnValor($val_ini, $casasDec) ?>">
										<div class="help-block with-errors">Inicial</div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">&nbsp;</label>
										<input type="text" class="form-control input-sm money" name="VAL_FIM" id="VAL_FIM" maxlength="50" value="<?= ($val_fim == fnValorSql(0) || $val_fim == fnValorSql(9999999999)) ? '' : fnValor($val_fim, $casasDec) ?>">
										<div class="help-block with-errors">Final</div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">CPF</label>
										<input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?php echo $num_cgcecpf; ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Número do Cartão</label>
										<input type="text" class="form-control input-sm" name="NUM_CARTAO" id="NUM_CARTAO" value="<?php echo $num_cartao; ?>">
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>


							</div>

						</fieldset>

						<div class="push20"></div>


						<div class="row">

							<div class="col-md-12">

								<div class="push20"></div>

								<?php

								// Filtro por Grupo de Lojas
								include "filtroGrupoLojas.php";

								if ($val_ini > 0 && $val_fim > 0) {
									$filtroSaldo = "AND (SELECT Sum(val_saldo) 
																					   FROM   creditosdebitos AA 
																					   WHERE AA.dat_expira BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
																					   AND AA.log_expira = 'S' 
																					   AND AA.cod_statuscred IN ( 0, 1, 2, 3, 4, 5, 7, 8, 9 ) 
																					   AND AA.cod_empresa = $cod_empresa 
																					   AND AA.val_saldo > 0
																					   AND AA.COD_CLIENTE=A.COD_CLIENTE 
																										   ) BETWEEN $val_ini AND $val_fim ";
								}

								$sql = "CALL SP_TOTA_CREDITO_EXPIRA($cod_empresa, '$lojasSelecionadas', '$dat_ini 00:00:00', '$dat_fim 23:59:59','$val_ini',' $val_fim', '$num_cartao', '$num_cgcecpf')";
								//fnEscreve($sql);

								$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
								//fnTestesql(connTemp($cod_empresa,""),trim($sql));
								$total_itens_por_pagina = mysqli_fetch_assoc($retorno);
								$cod_controle = fnLimpaCampoZero($total_itens_por_pagina['COD_CONTROLE']);
								//fnEscreve($cod_controle);														

								$sqlTotal = "SELECT SUM(saldo_total) as SALDO_TOTAL FROM TOTAL_SALDO_EXPIRAR
																		 WHERE cod_empresa = $cod_empresa AND 
																		 cod_controle = $cod_controle ";

								//fnEscreve($sqlTotal);
								$qrTotal = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sqlTotal));

								?>
							</div>
						</div>

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="AUTORIZA" id="AUTORIZA" value="<?= $autoriza ?>" />
						<input type="hidden" name="CASAS_DEC" id="CASAS_DEC" value="<?= $casasDec ?>">
						<input type="hidden" name="COD_CONTROLE" id="COD_CONTROLE" value="<?= $cod_controle ?>">
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

		<div class="row">

			<div class="col-md-12 col-lg-12 margin-bottom-30">
				<!-- Portlet -->
				<div class="portlet portlet-bordered">

					<div class="portlet-body">


						<div class="row text-center">

							<div class="form-group text-center col-md-6 col-lg-6">

								<div class="push20"></div>

								<p>R$ <span><?php echo fnValor($total_itens_por_pagina['val_totcompra'], 2); ?></span></p>
								<p><b>Total de Compras</b></p>

								<div class="push20"></div>

							</div>

							<div class="form-group text-center col-md-6 col-lg-6">

								<div class="push20"></div>

								<p>R$ <span><?php echo fnValor($total_itens_por_pagina['val_expira'], $casasDec); ?></span></p>
								<p><b>Total de Créditos/Pontos Expirados</b></p>

								<div class="push20"></div>

							</div>

						</div>

					</div>
					<!-- fim Portlet -->
				</div>

			</div>

		</div>


		<div class="portlet portlet-bordered">
			<div class="portlet-body">
				<div class="login-form">
					<div class="row">
						<div class="col-md-12" id="div_Produtos">
							<table class="table table-bordered table-hover tablesorter">

								<thead>
									<tr>
										<th><small>Nome</small></th>
										<th><small>Cartão</small></th>
										<th><small>E-mail</small></th>
										<th class="text-center"><small>Telefone</small></th>
										<th class="text-center"><small>Loja</small></th>
										<th class="text-center"><small>Data <br />da Compra</small></th>
										<th class="text-center"><small>Valor (R$)</small></th>
										<th class="text-center"><small>Créditos/Pontos</small></th>
										<th class="text-center"><small>Créditos/Pontos <br />Expirados</small></th>
										<th class="text-center"><small>Data <br />Expiração</small></th>
										<!-- <th class="text-center"><small>Saldo <br/>Total</small></th> -->
									</tr>
								</thead>

								<tbody id="relatorioConteudo">

									<?php

									$sql = "SELECT  1 FROM credito_expira_tmp A
																		LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
																		WHERE A.COD_EMPRESA = $cod_empresa 
																		AND COD_CONTROLE = $cod_controle
																		AND A.COD_UNIVEND IN($lojasSelecionadas)
																		$andCpf
																		$condicaoCartao
																		";


									//fnEscreve($sql);
									$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
									$total_itens_por_pagina = mysqli_num_rows($retorno);

									$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

									//variavel para calcular o início da visualização com base na página atual
									$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

									// Filtro por Grupo de Lojas
									include "filtroGrupoLojas.php";

									/*$ARRAY_UNIDADE1=array(
																	   'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
																	   'cod_empresa'=>$cod_empresa,
																	   'conntadm'=>$connAdm->connAdm(),
																	   'IN'=>'N',
																	   'nomecampo'=>'',
																	   'conntemp'=>'',
																	   'SQLIN'=> ""   
																	   );
														$ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);
                                                                                                                 * 
                                                                                                                 */


									if ($num_cgcecpf == "") {
										$andCpf = " ";
									} else {
										$andCpf = "AND NUM_CARTAO = $num_cgcecpf ";
										$limit = "";
									}

									if ($num_cartao == "") {
										$condicaoCartao = " ";
									} else {
										$condicaoCartao = "AND NUM_CARTAO = $num_cartao ";
										// $limit = "";
									}


									$sql = "SELECT A.COD_CLIENTE,
																		A.NOM_CLIENTE,
																		A.NUM_CARTAO,
																		A.DES_EMAILUS,
																		A.NUM_CELULAR,
																		A.COD_UNIVEND,
																		uni.NOM_FANTASI,
																		A.COD_VENDA,
																		A.DAT_CADASTR,
																		A.VAL_COMPRADO,
																		A.CREDITOS_GERADO,
																		A.CREDITOS_EXPIRAR,
																		A.DAT_EXPIRA,
																		A.SALDO_TOTAL
																FROM credito_expira_tmp A
																LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
																WHERE A.COD_EMPRESA = $cod_empresa 
																AND COD_CONTROLE = $cod_controle
																AND A.COD_UNIVEND IN($lojasSelecionadas)
																$andCpf
																$condicaoCartao
																ORDER BY DAT_EXPIRA 
																LIMIT $inicio, $itens_por_pagina
																";

									//fnEscreve($sql);
									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

									if (mysqli_num_rows($arrayQuery) != 0) {

										$countLinha = 1;
										while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

											if ($qrListaVendas['DES_EMAILUS'] == "") {
												$email = "e-mail não cadastrado!";
											} else {
												$email = fnmascaraCampo($qrListaVendas['DES_EMAILUS']);
											}
									?>
											<tr>
												<?php
												if ($autoriza == 1) {
												?>
													<td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE']; ?></a></td>
												<?php
												} else {
												?>
													<td><?php echo $qrListaVendas['NOM_CLIENTE']; ?></td>
												<?php
												}
												?>
												<td><small><?php echo fnMascaraCampo($qrListaVendas['NUM_CARTAO']); ?></small></td>
												<td><small><?php echo $email; ?></small></td>
												<td><small><?php echo fnMascaraCampo($qrListaVendas['NUM_CELULAR']); ?></small></td>
												<td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
												<td class="text-center"><small><?php echo $qrListaVendas['DAT_CADASTR']; ?></small></td>
												<td class="text-center"><small><small>R$</small> <?php echo fnValor($qrListaVendas['VAL_COMPRADO'], 2); ?></small></td>
												<td class="text-center"><small><?php echo fnValor($qrListaVendas['CREDITOS_GERADO'], $casasDec); ?></small></td>
												<td class="text-center"><small><?php echo fnValor($qrListaVendas['CREDITOS_EXPIRAR'], $casasDec); ?></small></td>
												<td class="text-center"><small><?php echo $qrListaVendas['DAT_EXPIRA']; ?></small></td>
												<!-- <td class="text-center"><small><small>R$</small> <?php echo fnValor($qrListaVendas['SALDO_TOTAL'], 2); ?></small></td> -->
											</tr>
										<?php

											$countLinha++;
										}
									} else {
										$sem_result = "sim";
										?>

										<thead>
											<tr>
												<th colspan="100">
													<center>
														<div style="margin: 10px; font-size: 17px; font-weight: bold">Não há créditos/pontos à expirar nesse período</div>
													</center>
												</th>
											</tr>
										</thead>


									<?php
									}
									?>

								</tbody>
								<?php
								//fnEscreve($sem_result);
								if ($sem_result != "sim") {
								?>
									<tfoot>
										<tr>
											<th colspan="100">
												<a class="btn btn-info btn-sm exportarCSV"><i class="fal fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar </a>
											</th>
										</tr>
										<?php
										// if($num_cgcecpf == "" && $num_cartao == ""){
										?>
										<tr>
											<th class="" colspan="100">
												<center>
													<ul id="paginacao" class="pagination-sm"></ul>
												</center>
											</th>
										</tr>
									</tfoot>
								<?php
									// }
								}
								?>

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

		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			maxDate: moment()
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		$("#DAT_INI_GRP").on("dp.change", function(e) {
			$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
		});

		// $("#DAT_FIM_GRP").on("dp.change", function (e) {
		// 	$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
		// 	$("#DAT_INI").val("<?= fnDataShort($dat_ini) ?>");
		// });

		$("#DAT_INI").val("<?= fnDataShort($dat_ini) ?>");
		$("#DAT_FIM").val("<?= fnDataShort($dat_fim) ?>");

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
										url: "relatorios/ajxRelCreditoExpirados.do?opcao=exportar&tipo=expirados&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&itens_por_pagina=<?php echo $itens_por_pagina; ?>&idc=<?= fnEncode($cod_controle) ?>",
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
			url: "relatorios/ajxRelCreditoExpirados.do?opcao=paginar&tipo=expirados&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?= fnEncode($cod_controle) ?>&itens_por_pagina=<?php echo $itens_por_pagina; ?>&lojas=<?php echo $lojasSelecionadas ?>&idPage=" + idPage,
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