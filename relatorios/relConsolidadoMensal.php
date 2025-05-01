<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$hoje = '';

$TOTAL_QTD_TOTFIDELIZ = 0;
$TOTAL_VAL_TOTFIDELIZ = 0;
$TOTAL_QTD_CLIENTE_FIDELIZ = 0;
$TOTAL_CLIENTES_PERIODO = 0;
$QTD_CREDITO_GERADO = 0;
$TOTAL_VAL_CREDITOGERADO = 0;
$TOTAL_VALORCLIENTE = 0;
$TOTAL_QTD_RESGATE = 0;
$TOTAL_VAL_RESGATE = 0;
$TOTAL_QTD_CLIENTE_RESGATEE = 0;

$hashLocal = mt_rand();

//inicialização de variáveis
//$hoje = fnFormatDate(date("Y-m-d"));
$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 2 days')));
$maxDate = fnDataSql($hoje);

$conn = connTemp($cod_empresa, '');
$adm = $connAdm->connAdm();

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
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, TIP_RETORNO FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
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

//fnMostraForm();
//fnEscreve($cod_cliente);

?>

<div class="push30"></div>

<div class="row">

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

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Grupo de Lojas</label>
										<?php include "grupoLojasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Região</label>
										<?php include "grupoRegiaoMulti.php"; ?>
									</div>
								</div>

								<div class="push10"></div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Inicial</label>
										<input type="hidden" name="DAT_INI_TOUR" id="DAT_INI_TOUR">
										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnDataShort($dat_ini); ?>" required />
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
										<input type="hidden" name="DAT_FIM_TOUR" id="DAT_FIM_TOUR">
										<div class="input-group date datePicker" id="DAT_FIM_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnDataShort($dat_fim); ?>" required />
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
					</div>
				</div>
			</div>

			<div class="push20"></div>

			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="push20"></div>

						<div class="row">

							<div class="col-md-12 wrapper" id="div_Produtos">

								<div class="push20"></div>

								<table class="table table-bordered table-hover tablesorter">

									<thead>
										<tr>
											<th class="{sorter:false}"></th>
											<th class="text-center">
												<div class="form-group">
													<label for="inputName" style="font-size: 16px;" class="control-label"><small>Total <br />Vendas <br /> Fidelizadas</small></label>
													<input type="hidden" class="form-control input-sm" name="TOUR_TOT_FIDELIZADAS" id="TOUR_TOT_FIDELIZADAS" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>

											<th class="text-center {sorter:false}">
												<div class="form-group">
													<label for="inputName" style="font-size: 16px;" class="control-label"><small>Total <br />Vendas <br /> Fidelizadas Limpo (R$)</small></label>
													<input type="hidden" class="form-control input-sm" name="TOUR_TOT_FIDELIZADAS_LIMPO" id="TOUR_TOT_FIDELIZADAS_LIMPO" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>

											<th class="{sorter:false}">
												<div class="form-group">
													<label for="inputName" style="font-size: 16px;" class="control-label"><small><B>Clientes com <br />Compras</small></B></label>
													<input type="hidden" class="form-control input-sm" name="TOUR_CLIENTES_COMPRAS" id="TOUR_CLIENTES_COMPRAS" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>

											<th class="text-center">
												<div class="form-group">
													<label for="inputName" style="font-size: 16px;" class="control-label"><small>Cadastrados <br />no Período</small></label>
													<input type="hidden" class="form-control input-sm" name="TOUR_TOT_FIDELIZADAS_LIMPO" id="TOUR_TOT_FIDELIZADAS_LIMPO" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>

											</th>

											<th class="text-center">
												<div class="form-group">
													<label for="inputName" style="font-size: 16px;" class="control-label"><small>Clientes Créditos/Pontos <br />Gerados</small></label>
													<input type="hidden" class="form-control input-sm" name="TOUR_CLIENTES_CREDPTO_GERADOS" id="TOUR_CLIENTES_CREDPTO_GERADOS" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>

											</th>

											<th class="text-center">
												<div class="form-group">
													<label for="inputName" style="font-size: 16px;" class="control-label"><small>Créditos/Pontos <br />Gerados</small></label>
													<input type="hidden" class="form-control input-sm" name="TOUR_CREDPTO_GERADOS" id="TOUR_CREDPTO_GERADOS" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>

											</th>

											<th class="text-center">
												<div class="form-group">
													<label for="inputName" style="font-size: 16px;" class="control-label"><small>Ticket <br />Médio</small></label>
													<input type="hidden" class="form-control input-sm" name="TOUR_TICKET_MEDIO" id="TOUR_TICKET_MEDIO" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>

											</th>

											<th class="text-center">
												<div class="form-group">
													<label for="inputName" style="font-size: 16px;" class="control-label"><small>Valor por <br />Cliente (R$)</small></label>
													<input type="hidden" class="form-control input-sm" name="TOUR_VALOR_CLIENTE" id="TOUR_VALOR_CLIENTE" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>

											</th>

											<th class="text-center">
												<div class="form-group">
													<label for="inputName" style="font-size: 16px;" class="control-label"><small>Quantidade <br /> de Resgates</small></label>
													<input type="hidden" class="form-control input-sm" name="TOUR_QTD_RESGATES" id="TOUR_QTD_RESGATES" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>

											</th>

											<th class="text-center">
												<div class="form-group">
													<label for="inputName" style="font-size: 16px;" class="control-label"><small>Total <br /> Resgatados (R$)</small></label>
													<input type="hidden" class="form-control input-sm" name="TOUR_TOT_RESGATADOS" id="TOUR_TOT_RESGATADOS" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>

											</th>

											<th>
												<div class="form-group">
													<label for="inputName" class="control-label"><b><small>Clientes <br /> Resgates</small><b></label>
													<input type="hidden" class="form-control input-sm" name="CLIENTES_RESGATE_TOUR" id="CLIENTES_RESGATE_TOUR" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>
										</tr>
									</thead>

									<tbody>


										<?php
										/*$ARRAY_UNIDADE1=array(
														   'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa",
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

										// Filtro por Grupo de Lojas
										include "filtroGrupoLojas.php";

										$sql = "SELECT 'NOM_UNIVEND', 
												A.cod_univend COD_UNIVEND,
                                                                                                                    uni.nom_fantasi,
												Sum(A.qtd_totfideliz) QTD_TOTFIDELIZ, 
												Round(Sum(A.val_totfideliz), 2) VAL_TOTFIDELIZ, 
												Sum(qtd_cliente_fideliz) QTD_CLIENTE_FIDELIZ, 
												(SELECT Count(*) FROM clientes WHERE clientes.cod_univend = A.cod_univend AND dat_cadastr BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' ) CLIENTES_PERIODO, 
												Round(SUM(D.VAL_CREDITO_GERADO), 2) VAL_CREDITOGERADO,
												Round(( Round(Sum(A.val_totfideliz), 2) / Sum(A.qtd_totfideliz)  ), 2) VAL_TKTMEDIO, 
												Round(( Round(Sum(A.val_totfideliz), 2) / Sum(A.qtd_cliente_fideliz)  ), 2) VAL_CLIENTE, 
												Sum(D.qtd_resgate) QTD_RESGATE, 
												SUM(D.VAL_RESGATE) VAL_RESGATE,
												Sum(D.qtd_cliente_resgate) QTD_CLIENTE_RESGATE, 
												
												Round(Sum(A.val_totvenda), 2) VAL_TOTVENDA, 
												Round((( Sum(A.qtd_totfideliz) / Sum(A.qtd_totvenda) ) * 100 ), 2) PCT_FIDELIZADO, 
												Sum(Ifnull(A.qtd_ticket, 0)) QTD_TICKET, 
												Round(Sum(Ifnull(A.val_ticket, 0)), 2) VAL_TICKET, 
												SUM(D.VAL_VINCULADO) VAL_VINCULADO,
												Sum(A.qtd_totavulsa) QTD_TOTAVULSA, 
												Sum(A.qtd_clientes_prim) AS CLIENTE_PRIMEIRACOMPRA, 
												Sum(Ifnull(A.qtd_totvenda, 0)) QTD_TOTVENDA, 
												SUM(D.QTD_CLIENTE_GERADO) QTD_CLIENTE_GERADO
												FROM vendas_diarias A 
												LEFT JOIN CREDITOSDEBITOS_DIARIAS D ON D.COD_EMPRESA=A.COD_EMPRESA AND D.COD_UNIVEND=A.COD_UNIVEND AND D.COD_VENDEDOR=A.COD_VENDEDOR AND D.DAT_MOVIMENTO=A.DAT_MOVIMENTO
                                                                                                                    LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
												WHERE A.dat_movimento BETWEEN '$dat_ini' AND '$dat_fim' AND 
												A.cod_empresa = $cod_empresa AND 
												A.cod_univend IN ($lojasSelecionadas) 
												GROUP BY A.cod_univend 
												ORDER BY A.cod_univend";


										//fnEscreve($sql);
										$arrayQuery = mysqli_query($conn, $sql);

										$countLinha = 1;
										while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

											/*$NOM_ARRAY_UNIDADE=(array_search($qrListaVendas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
												
                                                                                                                         * 
                                                                                                                         */
											//monta primeiro cabeçalho
											$loja = $qrListaVendas['COD_UNIVEND'];
											//monta primeira linha
											//fnEscreve($loja);
											$ticketMedio = $qrListaVendas['QTD_TOTFIDELIZ'] != 0 ? $qrListaVendas['VAL_TOTFIDELIZ'] / $qrListaVendas['QTD_TOTFIDELIZ'] : 0;
											$valorCliente = $qrListaVendas['QTD_CLIENTE_FIDELIZ'] !=  0 ? $qrListaVendas['VAL_TOTFIDELIZ'] / $qrListaVendas['QTD_CLIENTE_FIDELIZ'] : 0;
										?>
											<tr>
												<td><b><small>
															<!--<?php echo $countLinha; ?> - <?php echo $qrListaVendas['COD_UNIVEND']; ?></small>--> <?php echo $qrListaVendas['nom_fantasi']; ?></small></b></td>
												<td class="text-center"><small><?php echo fnValor($qrListaVendas['QTD_TOTFIDELIZ'], 0); ?></small></td>
												<td class="text-center"><small><small>R$</small> <?php echo fnValor($qrListaVendas['VAL_TOTFIDELIZ'], 2); ?></small></td>
												<td class="text-center"><small><?php echo fnValor($qrListaVendas['QTD_CLIENTE_FIDELIZ'], 0); ?></small></td>
												<td class="text-center"><small><?php echo fnValor($qrListaVendas['CLIENTES_PERIODO'], 0); ?></small></td>
												<td class="text-center"><small><?php echo fnValor($qrListaVendas['QTD_CLIENTE_GERADO'], 0); ?></small></td>
												<td class="text-center"><small><?php echo fnValor($qrListaVendas['VAL_CREDITOGERADO'], $casasDec); ?></small></td>
												<td class="text-center"><small><small>R$</small> <?php echo fnValor($ticketMedio, 2); ?></small></td>
												<td class="text-center"><small><small>R$</small> <?php echo fnValor($valorCliente, 2); ?></small></td>
												<td class="text-center"><small><?php echo fnValor($qrListaVendas['QTD_RESGATE'], 0); ?></small></td>
												<td class="text-center"><small><small>R$</small> <?php echo fnValor($qrListaVendas['VAL_RESGATE'], 2); ?></small></td>
												<td class="text-center"><small><small></small> <?php echo fnValor($qrListaVendas['QTD_CLIENTE_RESGATE'], 0); ?></small></td>
											</tr>
										<?php

											$TOTAL_QTD_TOTFIDELIZ += $qrListaVendas['QTD_TOTFIDELIZ'];
											$TOTAL_VAL_TOTFIDELIZ += $qrListaVendas['VAL_TOTFIDELIZ'];
											$TOTAL_QTD_CLIENTE_FIDELIZ += $qrListaVendas['QTD_CLIENTE_FIDELIZ'];
											$TOTAL_CLIENTES_PERIODO += $qrListaVendas['CLIENTES_PERIODO'];
											$QTD_CREDITO_GERADO += $qrListaVendas['QTD_CLIENTE_GERADO'];
											$TOTAL_VAL_CREDITOGERADO += $qrListaVendas['VAL_CREDITOGERADO'];
											$TOTAL_VALORCLIENTE += $valorCliente;
											$TOTAL_QTD_RESGATE += $qrListaVendas['QTD_RESGATE'];
											$TOTAL_VAL_RESGATE += $qrListaVendas['VAL_RESGATE'];
											$TOTAL_QTD_CLIENTE_RESGATEE += $qrListaVendas['QTD_CLIENTE_RESGATE'];

											$countLinha++;
										}

										?>
									</tbody>
									<!-- <script>
																	$("#TOTAL_QTD_TOTFIDELIZ").text("<?= fnValor($TOTAL_QTD_TOTFIDELIZ, 0) ?>");
																	$("#TOTAL_VAL_TOTFIDELIZ").text("<?= fnValor($TOTAL_VAL_TOTFIDELIZ, 2) ?>");
																	$("#TOTAL_QTD_CLIENTE_FIDELIZ").text("<?= fnValor($TOTAL_QTD_CLIENTE_FIDELIZ, 0) ?>");
																	$("#TOTAL_CLIENTES_PERIODO").text("<?= fnValor($TOTAL_CLIENTES_PERIODO, 0) ?>");
																	$("#TOTAL_VAL_CREDITOGERADO").text("<?= fnValor($TOTAL_VAL_CREDITOGERADO, $casasDec) ?>");
																	$("#TOTAL_VAL_TOTFIDELIZdivTOTAL_QTD_TOTFIDELIZ").text("<?= fnValor($TOTAL_VAL_TOTFIDELIZ / $TOTAL_QTD_TOTFIDELIZ, 2) ?>");
																	$("#TOTAL_VAL_TOTFIDELIZdivTOTAL_QTD_CLIENTE_FIDELIZ").text("<?= fnValor($TOTAL_VAL_TOTFIDELIZ / $TOTAL_QTD_CLIENTE_FIDELIZ, 2) ?>");
																	$("#TOTAL_QTD_RESGATE").text("<?= fnValor($TOTAL_QTD_RESGATE, 0) ?>");
																	$("#TOTAL_VAL_RESGATE").text("<?= fnValor($TOTAL_VAL_RESGATE, 2) ?>");
																	$("#TOTAL_QTD_CLIENTE_RESGATEE").text("<?= fnValor($TOTAL_QTD_CLIENTE_RESGATEE, 0) ?>");
																</script> -->

									<?php
									//fnEscreve($countLinha-1);				
									?>



									<tfoot>
										<tr>
											<th></th>
											<th class="text-center"><b><small><?php echo fnValor($TOTAL_QTD_TOTFIDELIZ, 0); ?></small></b></th>
											<th class="text-center"><b><small><small>R$</small> <?php echo fnValor($TOTAL_VAL_TOTFIDELIZ, 2); ?></small></b></th>
											<th class="text-center"><b><small><?php echo fnValor($TOTAL_QTD_CLIENTE_FIDELIZ, 0); ?></small></b></th>
											<th class="text-center"><b><small><?php echo fnValor($TOTAL_CLIENTES_PERIODO, 0); ?></small></b></th>
											<th class="text-center"><b><small><?php echo fnValor($QTD_CREDITO_GERADO, 0); ?></small></b></th>
											<th class="text-center"><b><small><?php echo fnValor($TOTAL_VAL_CREDITOGERADO, $casasDec); ?></small></b></th>
											<th class="text-center"><b><small><small>R$</small> <?php echo ($TOTAL_QTD_TOTFIDELIZ != 0) ? fnValor($TOTAL_VAL_TOTFIDELIZ / $TOTAL_QTD_TOTFIDELIZ, 2)	: 0; ?></small></b></th>
											<th class="text-center"><b><small><small>R$</small> <?php echo ($TOTAL_QTD_CLIENTE_FIDELIZ != 0) ? fnValor($TOTAL_VAL_TOTFIDELIZ / $TOTAL_QTD_CLIENTE_FIDELIZ, 2) : 0; ?></small></b></th>
											<th class="text-center"><b><small><?php echo fnValor($TOTAL_QTD_RESGATE, 0); ?></small></b></th>
											<th class="text-center"><b><small><small>R$</small> <?php echo fnValor($TOTAL_VAL_RESGATE, 2); ?></small></b></th>
											<th class="text-center"><b><small><small></small> <?php echo fnValor($TOTAL_QTD_CLIENTE_RESGATEE, 0); ?></small></b></th>
										</tr>
										<tr>
											<th colspan="100">
												<a class="btn btn-info btn-sm exportarCSV"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp;Exportar </a>
											</th>
										</tr>
									</tfoot>

								</table>

							</div>

						</div>

						<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
						<input type="hidden" name="CASAS_DEC" id="CASAS_DEC" value="<?= $casasDec ?>">
						<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>">
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
			//maxDate: "<?= $maxDate ?>",
		}).on('dp.change', function(e) {
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
										url: "relatorios/ajxRelConsolidadoMensal.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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