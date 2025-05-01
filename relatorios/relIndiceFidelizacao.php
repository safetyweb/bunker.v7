<?php

// echo fnDebug('true');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$hoje = '';
$dias30 = '';
$dat_ini = '';
$dat_fim = '';
$TOTAL_QTD_TOTAVULSA = 0;
$TOTAL_QTD_TOTFIDELIZ = 0;
$TOTAL_VAL_TOTVENDA = 0;
$TOTAL_VAL_TOTFIDELIZ = 0;
$TOTAL_QTD_FUNCIONARIO = 0;
$TOTAL_QTD_INATIVO = 0;
$TOTAL_VAL_FUNCINATIVO = 0;
$TOTAL_QTD_LOJAS = 0;

$hashLocal = mt_rand();

//inicialização de variáveis
//$hoje = fnFormatDate(date("Y-m-d"));
$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 2 days')));

$conn = conntemp($cod_empresa, '');
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
		if (@$_POST['DAT_INI_ORI'] <> "") {
			$dat_ini = fnDataSql($_POST['DAT_INI_ORI']);
		} else {
			$dat_ini = fnDataSql($_POST['DAT_INI']);
		}
		if (@$_POST['DAT_FIM_ORI'] <> "") {
			$dat_fim = fnDataSql($_POST['DAT_FIM_ORI']);
		} else {
			$dat_fim = fnDataSql($_POST['DAT_FIM']);
		}

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
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

$modulo = fnDecode($_GET['mod']);

// fnEscreve($modulo);


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
					include "backReport.php";
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

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Dados online </label>
										<div class="push5"></div>
										<input type="hidden" class="form-control input-sm" name="TOUR_LOG_ONLINE" id="TOUR_LOG_ONLINE" maxlength="100" value="">
										<label class="switch switch-small">
											<input type="checkbox" name="LOG_ONLINE" id="LOG_ONLINE" class="switch switch-small" value="S" <?= (@$_POST["LOG_ONLINE"] == "S" ? "checked" : "") ?>>
											<span></span>
										</label>
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

							<div class="col-md-12" id="div_Produtos">

								<div class="push20"></div>

								<table class="table table-bordered table-hover tablesorter">

									<thead>
										<tr>
											<th class="{sorter:false}"></th>
											<th class="{sorter:false}">
												<div class="form-group">
													<label for="inputName" style="font-size: 16px;" class="control-label"><small><b>Vendedor</b></small></label>
													<input type="hidden" class="form-control input-sm" name="TOUR_VENDEDOR" id="TOUR_VENDEDOR" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>

											<th class="{sorter:false}">
												<div class="form-group">
													<label for="inputName" style="font-size: 16px;" class="control-label"><small>Qtd. Vendas <br />Total</small></label>
													<input type="hidden" class="form-control input-sm" name="TOUR_QTD_VENDAS" id="TOUR_QTD_VENDAS" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>

											<th class="{sorter:false}">
												<div class="form-group">
													<label for="inputName" style="font-size: 16px;" class="control-label"><small>Qtd. Vendas <br />Avulsas</small></label>
													<input type="hidden" class="form-control input-sm" name="TOUR_QTD_VENDAS" id="TOUR_QTD_VENDAS" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>

											<th class="{sorter:false}">
												<div class="form-group">
													<label for="inputName" style="font-size: 16px;" class="control-label"><small>Qtd. Vendas <br />Fidelizados</small></label>
													<input type="hidden" class="form-control input-sm" name="TOUR_QTD_VENDAS_FIDELIZADOS" id="TOUR_QTD_VENDAS_FIDELIZADOS" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>

											<th class="{sorter:false} text-center">
												<div class="form-group">
													<label for="inputName" style="font-size: 16px;" class="control-label"><small>Índice de <br />Fidelização</small></label>
													<input type="hidden" class="form-control input-sm" name="TOUR_INDICE_FIDELIZACAO" id="TOUR_INDICE_FIDELIZACAO" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>
											<?php if ($modulo != 1617) { ?>
												<th class="{sorter:false} text-center">
													<div class="form-group">
														<label for="inputName" style="font-size: 16px;" class="control-label"><small>Vendas <br />Geral</small></label>
														<input type="hidden" class="form-control input-sm" name="TOUR_VENDA_GERAL" id="TOUR_VENDA_GERAL" maxlength="100" value="">
														<div class="help-block with-errors"></div>
													</div>
												</th>

												<th class="{sorter:false} text-center">
													<div class="form-group">
														<label for="inputName" style="font-size: 16px;" class="control-label"><small>Vendas <br />Fidelizados</small></label>
														<input type="hidden" class="form-control input-sm" name="TOUR_VENDAS_FIDELIZADOS" id="TOUR_VENDAS_FIDELIZADOS" maxlength="100" value="">
														<div class="help-block with-errors"></div>
													</div>
												</th>
											<?php } ?>
										</tr>
									</thead>

									<?php
									//busca por grupo de trabalho
									//Filtro por Grupo de Lojas
									include "filtroGrupoLojas.php";

									if (@$_POST['LOG_ONLINE'] == 'S') {
										$sql = "SELECT COD_USUARIO,
										COD_UNIVEND,
										1 AS CONT_LOJA,
										NOM_FANTASI,
										NOM_USUARIO,
										COD_VENDEDOR,
										COD_EXTERNO,
										IFNULL(TRUNCATE(SUM(VAL_VINCULADO), 2), 0) VAL_VINCULADO,
										SUM(QTD_TOTAVULSA) QTD_TOTAVULSA,
										SUM(QTD_TOTFIDELIZ) QTD_TOTFIDELIZ,
										round(((SUM(QTD_TOTFIDELIZ) / (SUM(QTD_TOTAVULSA) + sum(QTD_TOTFIDELIZ))) * 100),2) AS PCT_FIDELIZADO,
										TRUNCATE(SUM(VAL_TOTFIDELIZ), 2) VAL_TOTFIDELIZ,
										SUM(VAL_TOTVENDA) VAL_TOTVENDA,
										IFNULL(TRUNCATE(SUM(VAL_RESGATE), 2), 0) VAL_RESGATE,
										IFNULL(TRUNCATE(SUM(VAL_CREDITOGERADO), 2), 0) VAL_CREDITOGERADO,
										IFNULL(TRUNCATE(SUM(VAL_TOTPRODU_FUNC), 2), 0) VAL_VENDA_CLIENTE_FUNCIONARIO,
										IFNULL(TRUNCATE(SUM(QTD_VENDA_FUNC), 2), 0) QTD_VENDA_CLIENTE_FUNCIONARIO,
										IFNULL(TRUNCATE(SUM(QTD_VENDA_FUNC_INATIVO), 2), 0) QTD_VENDA_CLIENTE_INATIVO,
										IFNULL(TRUNCATE(SUM(VAL_TOTPRODU_FUNC_INATIVO), 2), 0) VAL_VENDA_CLIENTE_INATIVO
										FROM
										(SELECT A.COD_USUCADA AS COD_USUARIO,
											A.COD_UNIVEND,
											uni.nom_fantasi,
											A.COD_VENDEDOR,
											US.NOM_USUARIO,
											US.COD_EXTERNO,

											(SELECT SUM(VLR.VAL_VINCULADO)
												FROM CREDITOSDEBITOS VLR
												WHERE VLR.COD_EMPRESA=A.COD_EMPRESA
												AND VLR.COD_VENDA=A.COD_VENDA
												AND TIP_CREDITO = 'D'
												GROUP BY VLR.COD_VENDA) AS VAL_VINCULADO,
											CASE
											WHEN A.COD_AVULSO = 1 THEN A.QTD_VENDA
											ELSE '0'
											END AS QTD_TOTAVULSA,
											CASE
											WHEN A.COD_AVULSO = 2 THEN 1
											ELSE '0'
											END AS QTD_TOTFIDELIZ,
											'0.00' AS PCT_FIDELIZADO,
											CASE
											WHEN A.COD_AVULSO = 2 THEN A.VAL_TOTVENDA
											ELSE '0.00'
											END AS VAL_TOTFIDELIZ,
											A.VAL_TOTVENDA AS VAL_TOTVENDA,

											(SELECT SUM(VLR.VAL_CREDITO)
												FROM CREDITOSDEBITOS VLR
												WHERE VLR.COD_EMPRESA=A.COD_EMPRESA
												AND VLR.COD_VENDA=A.COD_VENDA
												AND TIP_CREDITO = 'D'
												GROUP BY VLR.COD_VENDA) AS VAL_RESGATE,

											(SELECT SUM(VLR.VAL_CREDITO)
												FROM CREDITOSDEBITOS VLR
												WHERE VLR.COD_EMPRESA=A.COD_EMPRESA
												AND VLR.COD_VENDA=A.COD_VENDA
												AND TIP_CREDITO = 'C'
												GROUP BY VLR.COD_VENDA) AS VAL_CREDITOGERADO,
											case when  A.COD_AVULSO= 2 AND c.LOG_FUNCIONA='S' then A.VAL_TOTVENDA ELSE 0 END   VAL_TOTPRODU_FUNC,
											case when  A.COD_AVULSO= 2 AND c.LOG_FUNCIONA='S' then A.QTD_VENDA ELSE 0 END   QTD_VENDA_FUNC,
											case when  A.COD_AVULSO= 2 AND c.LOG_FUNCIONA='S' AND c.LOG_ESTATUS='N' then A.QTD_VENDA ELSE 0 END   QTD_VENDA_FUNC_INATIVO,
											case when  A.COD_AVULSO= 2 AND c.LOG_FUNCIONA='S' then A.VAL_TOTVENDA  and c.LOG_ESTATUS='N' ELSE 0 END   VAL_TOTPRODU_FUNC_INATIVO
											FROM VENDAS A
											INNER JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
											left JOIN clientes c ON c.COD_CLIENTE=A.COD_CLIENTE
											LEFT JOIN USUARIOS US ON US.COD_USUARIO = A.COD_VENDEDOR
											WHERE date(A.DAT_CADASTR_WS) BETWEEN CURDATE() AND CURDATE()
											AND A.COD_STATUSCRED IN(0,
												1,
												2,
												3,
												4,
												5,
												7,
												8,
												9)
											AND A.COD_EMPRESA = $cod_empresa
											AND A.COD_UNIVEND IN($lojasSelecionadas)
											GROUP BY A.COD_VENDA) TMPTABLE
										GROUP BY COD_VENDEDOR,
										COD_UNIVEND
										ORDER BY COD_UNIVEND,
										PCT_FIDELIZADO DESC";
									} else {
										$sql = "SELECT 	A.COD_USUARIO, 
										A.COD_UNIVEND,
										1 as CONT_LOJA,
										uni.NOM_FANTASI,
										A.COD_VENDEDOR,
										SUM(A.QTD_VENDA_CLIENTE_FUNCIONARIO) AS QTD_VENDA_CLIENTE_FUNCIONARIO,
										SUM(A.VAL_VENDA_CLIENTE_FUNCIONARIO) AS VAL_VENDA_CLIENTE_FUNCIONARIO,
										SUM(A.QTD_VENDA_CLIENTE_INATIVO) AS QTD_VENDA_CLIENTE_INATIVO,
										SUM(A.VAL_VENDA_CLIENTE_INATIVO) AS VAL_VENDA_CLIENTE_INATIVO,
										US.NOM_USUARIO,
										US.COD_EXTERNO,
										SUM(D.VAL_VINCULADO) VAL_VINCULADO,
										SUM(A.QTD_TOTAVULSA) as QTD_TOTAVULSA, 
										SUM(A.QTD_TOTFIDELIZ) as QTD_TOTFIDELIZ, 
										ROUND(((SUM(A.QTD_TOTFIDELIZ)/SUM(A.QTD_TOTVENDA))*100),2)as PCT_FIDELIZADO, 
										SUM(A.VAL_TOTFIDELIZ) as VAL_TOTFIDELIZ, 
										SUM(A.VAL_TOTVENDA) as VAL_TOTVENDA, 
										SUM(D.VAL_RESGATE) VAL_RESGATE,
										SUM(D.VAL_CREDITO_GERADO) VAL_CREDITOGERADO
										FROM VENDAS_DIARIAS A 
										LEFT JOIN CREDITOSDEBITOS_DIARIAS D ON D.COD_EMPRESA=A.COD_EMPRESA AND D.COD_UNIVEND=A.COD_UNIVEND AND D.COD_VENDEDOR=A.COD_VENDEDOR AND D.DAT_MOVIMENTO=A.DAT_MOVIMENTO
										LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
										LEFT JOIN USUARIOS US ON US.COD_USUARIO = A.COD_VENDEDOR
										WHERE date(A.DAT_MOVIMENTO)  between '$dat_ini' AND  '$dat_fim' AND 
										A.COD_EMPRESA = $cod_empresa AND 
										A.COD_UNIVEND IN($lojasSelecionadas) 
										GROUP BY A.COD_VENDEDOR,A.COD_UNIVEND
										ORDER BY A.COD_UNIVEND, PCT_FIDELIZADO DESC ";
									}


									//fnEscreve($sql);
									//fnTestesql($conn, $sql) ;
									$arrayQuery = mysqli_query($conn, $sql);

									$countLinha = 1;
									$loja = 0;
									$conta_loja = 0;
									while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
										/*$NOM_ARRAY_UNIDADE=(array_search($qrListaVendas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
										*/
										//$NOM_ARRAY_NON_VENDEDOR = (array_search($qrListaVendas['COD_VENDEDOR'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));

										//echo "<pre>";
										//print_r($qrListaVendas);
										//echo "</pre>";

										if ($loja != $qrListaVendas['COD_UNIVEND']) {
											$loja = $qrListaVendas['COD_UNIVEND'];
											$conta_loja = $conta_loja + 1;
											//fnEscreve("entrou");
									?>

											<tr id="bloco_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
												<th width="3%" class="{sorter:false} text-center"><a href="javascript:void(0);" onclick="abreDetail(<?php echo $qrListaVendas['COD_UNIVEND']; ?>)" style="padding:10px;"><i class="fa fa-angle-right" aria-hidden="true"></i></a></th>
												<th width="10%"><?php echo $qrListaVendas['NOM_FANTASI']; ?> </th>

												<th width="10%" class="text-center">
													<div style="display: inline;" id="total_col0_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
													<span style="font-size: 13px; color:rgba(128, 128, 128, 0.6);" class="total-text"> / </span>
													<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);" id="total_col6_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
												</th>

												<th width="10%" class="text-center">
													<div style="display: inline;" id="total_col1_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
													<span style="font-size: 13px; color:rgba(128, 128, 128, 0.6);" class="total-text"> / </span>
													<span style="font-size: 13px; color:rgba(128, 128, 128, 0.6);" id="col1_<?php echo $qrListaVendas['COD_UNIVEND']; ?>" class="total-text"> </span>
												</th>

												<th width="10%" class="text-center">
													<div style="display: inline;" id="total_col2_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
													<span style="font-size: 13px; color:rgba(128, 128, 128, 0.6);" class="total-text"> / </span>
													<span style="font-size: 13px; color:rgba(128, 128, 128, 0.6);" id="col2_<?php echo $qrListaVendas['COD_UNIVEND']; ?>" class="total-text"> </span>
												</th>
												<th width="14%" class="text-center">
													<div class="porcent" style="display: inline;" id="total_col3_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>%
													<span style="font-size: 13px; color:rgba(128, 128, 128, 0.6);" class="total-text"> / </span>
													<span style="font-size: 13px; color:rgba(128, 128, 128, 0.6);" class="porcent" id="col3_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></span>

												</th>

												<?php if ($modulo != 1617) { ?>
													<th width="16%" class="text-center"><small>R$ </small>
														<div style="display: inline;" class="money" id="total_col4_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
														<span style="font-size: 13px; color:rgba(128, 128, 128, 0.6);" class="total-text"> / </span>
														<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);" class="money" id="total_col7_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>

													</th>
													<th width="16%" class="text-center"><small>R$ </small>
														<div style="display: inline;" class="money" id="total_col5_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
														<span style="font-size: 13px; color:rgba(128, 128, 128, 0.6);" class="total-text"> / </span>
														<span style="font-size: 13px; color:rgba(128, 128, 128, 0.6);" id="col5_<?php echo $qrListaVendas['COD_UNIVEND']; ?>" class=" money"> </span>
													</th>
												<?php } ?>
											</tr>

											</tbody>

											<tr style="background-color: #fff; display: none;" class="abreDetail_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
												<td width="3%"></td>
												<td width="10%"><small><b><?php echo $qrListaVendas['NOM_USUARIO']; ?></b>&nbsp; <small><?php echo $qrListaVendas['COD_EXTERNO']; ?></small></small></td>

												<td width="10%" class="text-center"><small class="qtde_col0_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['QTD_TOTAVULSA'] + $qrListaVendas['QTD_TOTFIDELIZ'], 0); ?></small>
													<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"> / </div>
													<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"> <?= fnValor($qrListaVendas['QTD_TOTAVULSA'] + $qrListaVendas['QTD_TOTFIDELIZ'], 0) - fnValor($qrListaVendas['QTD_VENDA_CLIENTE_FUNCIONARIO'] + $qrListaVendas['QTD_VENDA_CLIENTE_INATIVO'], 0); ?> </div>
												</td>


												<td width="10%" class="text-center"><small class="qtde_col1_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['QTD_TOTAVULSA'], 0); ?></small>
													<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"> / </div>
													<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"><?php echo fnValor($qrListaVendas['QTD_TOTAVULSA'], 0); ?></div>
												</td>


												<td width="10%" class="text-center"><small class="qtde_col2_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['QTD_TOTFIDELIZ'], 0); ?></small>
													<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"> / </div>
													<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"><?= fnValor($qrListaVendas['QTD_TOTFIDELIZ'], 0) - fnValor($qrListaVendas['QTD_VENDA_CLIENTE_FUNCIONARIO'] + $qrListaVendas['QTD_VENDA_CLIENTE_INATIVO'], 0); ?></div>
												</td>


												<td width="14%" class="text-center"><small class="qtde_col3_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['PCT_FIDELIZADO'], 2); ?></small>%
													<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"> / </div>
													<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"><?= fnValor(($qrListaVendas['QTD_TOTFIDELIZ'] - ($qrListaVendas['QTD_VENDA_CLIENTE_FUNCIONARIO'] + $qrListaVendas['QTD_VENDA_CLIENTE_INATIVO'])) / ($qrListaVendas['QTD_TOTAVULSA'] + ($qrListaVendas['QTD_TOTFIDELIZ'] - ($qrListaVendas['QTD_VENDA_CLIENTE_FUNCIONARIO'] + $qrListaVendas['QTD_VENDA_CLIENTE_INATIVO']))) * 100, 2) ?>%</div>
												</td>


												<?php if ($modulo != 1617) { ?>
													<td width="16%" class="text-center"><small class="qtde_col4_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['VAL_TOTVENDA'], 2); ?></small>
														<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"> / </div>
														<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"><?= fnValor($qrListaVendas['VAL_TOTVENDA'] - ($qrListaVendas['VAL_VENDA_CLIENTE_FUNCIONARIO'] + $qrListaVendas['VAL_VENDA_CLIENTE_INATIVO']), 2); ?></div>
													</td>


													<td width="16%" class="text-center"><small class="qtde_col5_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['VAL_TOTFIDELIZ'], 2); ?></small>
														<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"> / </div>
														<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"><?= fnValor($qrListaVendas['VAL_TOTFIDELIZ'] - ($qrListaVendas['VAL_VENDA_CLIENTE_FUNCIONARIO'] + $qrListaVendas['VAL_VENDA_CLIENTE_INATIVO']), 2); ?></div>
													</td>


												<?php } ?>
												<td style="display: none;" class="text-center"><small class="qtde_col6_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['QTD_VENDA_CLIENTE_FUNCIONARIO'] + $qrListaVendas['QTD_VENDA_CLIENTE_INATIVO'], 0); ?></small></td>
												<td style="display: none;" class="text-center"><small class="qtde_col7_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['VAL_VENDA_CLIENTE_FUNCIONARIO'] + $qrListaVendas['VAL_VENDA_CLIENTE_INATIVO'], 2); ?></small></td>
											</tr>

										<?php
										} else {
										?>
											<tr style="background-color: #fff; display: none;" class="abreDetail_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
												<td width="3%"></td>
												<td width="10%"><small><b><?php echo $qrListaVendas['NOM_USUARIO']; ?></b>&nbsp; <small><?php echo $qrListaVendas['COD_EXTERNO']; ?></small></small></td>

												<td width="10%" class="text-center"><small class="qtde_col0_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['QTD_TOTAVULSA'] + $qrListaVendas['QTD_TOTFIDELIZ'], 0); ?></small>
													<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"> / </div>
													<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"> <?= fnValor($qrListaVendas['QTD_TOTAVULSA'] + $qrListaVendas['QTD_TOTFIDELIZ'], 0) - fnValor($qrListaVendas['QTD_VENDA_CLIENTE_FUNCIONARIO'] + $qrListaVendas['QTD_VENDA_CLIENTE_INATIVO'], 0); ?> </div>
												</td>


												<td width="10%" class="text-center"><small class="qtde_col1_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['QTD_TOTAVULSA'], 0); ?></small>
													<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"> / </div>
													<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"><?php echo fnValor($qrListaVendas['QTD_TOTAVULSA'], 0); ?></div>
												</td>


												<td width="10%" class="text-center"><small class="qtde_col2_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['QTD_TOTFIDELIZ'], 0); ?></small>
													<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"> / </div>
													<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"><?= fnValor($qrListaVendas['QTD_TOTFIDELIZ'], 0) - fnValor($qrListaVendas['QTD_VENDA_CLIENTE_FUNCIONARIO'] + $qrListaVendas['QTD_VENDA_CLIENTE_INATIVO'], 0); ?></div>
												</td>


												<td width="14%" class="text-center"><small class="qtde_col3_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['PCT_FIDELIZADO'], 2); ?></small>%
													<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"> / </div>
													<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"><?= fnValor(($qrListaVendas['QTD_TOTFIDELIZ'] - ($qrListaVendas['QTD_VENDA_CLIENTE_FUNCIONARIO'] + $qrListaVendas['QTD_VENDA_CLIENTE_INATIVO'])) / ($qrListaVendas['QTD_TOTAVULSA'] + ($qrListaVendas['QTD_TOTFIDELIZ'] - ($qrListaVendas['QTD_VENDA_CLIENTE_FUNCIONARIO'] + $qrListaVendas['QTD_VENDA_CLIENTE_INATIVO']))) * 100, 2) ?>%</div>
												</td>


												<?php if ($modulo != 1617) { ?>
													<td width="16%" class="text-center"><small class="qtde_col4_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['VAL_TOTVENDA'], 2); ?></small>
														<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"> / </div>
														<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"><?= fnValor($qrListaVendas['VAL_TOTVENDA'] - ($qrListaVendas['VAL_VENDA_CLIENTE_FUNCIONARIO'] + $qrListaVendas['VAL_VENDA_CLIENTE_INATIVO']), 2); ?></div>
													</td>


													<td width="16%" class="text-center"><small class="qtde_col5_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['VAL_TOTFIDELIZ'], 2); ?></small>
														<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"> / </div>
														<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"><?= fnValor($qrListaVendas['VAL_TOTFIDELIZ'] - ($qrListaVendas['VAL_VENDA_CLIENTE_FUNCIONARIO'] + $qrListaVendas['VAL_VENDA_CLIENTE_INATIVO']), 2); ?></div>
													</td>


												<?php } ?>
												<td style="display: none;" class="text-center"><small class="qtde_col6_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['QTD_VENDA_CLIENTE_FUNCIONARIO'] + $qrListaVendas['QTD_VENDA_CLIENTE_INATIVO'], 0); ?></small></td>
												<td style="display: none;" class="text-center"><small class="qtde_col7_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['VAL_VENDA_CLIENTE_FUNCIONARIO'] + $qrListaVendas['VAL_VENDA_CLIENTE_INATIVO'], 2); ?></small></td>
											</tr>
									<?php
										}

										$TOTAL_QTD_LOJAS += $qrListaVendas['CONT_LOJA'];
										$TOTAL_QTD_TOTAVULSA += $qrListaVendas['QTD_TOTAVULSA'];
										$TOTAL_QTD_TOTFIDELIZ += $qrListaVendas['QTD_TOTFIDELIZ'];
										$TOTAL_VAL_TOTVENDA += $qrListaVendas['VAL_TOTVENDA'];
										$TOTAL_VAL_TOTFIDELIZ += $qrListaVendas['VAL_TOTFIDELIZ'];
										$TOTAL_QTD_FUNCIONARIO += $qrListaVendas['QTD_VENDA_CLIENTE_FUNCIONARIO'];
										$TOTAL_QTD_INATIVO += $qrListaVendas['QTD_VENDA_CLIENTE_INATIVO'];
										$TOTAL_VAL_FUNCINATIVO += $qrListaVendas['VAL_VENDA_CLIENTE_FUNCIONARIO'] + $qrListaVendas['VAL_VENDA_CLIENTE_INATIVO'];

										$countLinha++;
									}

									?>

									<tr>
										<td class="text-center"><b><?php echo $conta_loja; ?></b></td>
										<td></td>
										<td class="text-center">
											<b><?php echo fnValor($TOTAL_QTD_TOTAVULSA + $TOTAL_QTD_TOTFIDELIZ, 0); ?></b>
											<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"> / </div>
											<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"> <?= fnValor(($TOTAL_QTD_TOTAVULSA + $TOTAL_QTD_TOTFIDELIZ) - ($TOTAL_QTD_FUNCIONARIO - $TOTAL_QTD_INATIVO), 0); ?> </div>
										</td>


										<td class="text-center">
											<b><?php echo fnValor($TOTAL_QTD_TOTAVULSA, 0); ?></b>
											<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"> / </div>
											<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"><?= fnValor($TOTAL_QTD_TOTAVULSA, 0); ?> </div>
										</td>


										<td class="text-center">
											<b><?php echo fnValor($TOTAL_QTD_TOTFIDELIZ, 0); ?></b>
											<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"> / </div>
											<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"><?= fnValor($TOTAL_QTD_TOTFIDELIZ - ($TOTAL_QTD_FUNCIONARIO - $TOTAL_QTD_INATIVO), 0); ?> </div>
										</td>


										<td class="text-center">
											<b><?php echo  fnValor((($TOTAL_QTD_TOTAVULSA + $TOTAL_QTD_TOTFIDELIZ) != 0) ? $TOTAL_QTD_TOTFIDELIZ / ($TOTAL_QTD_TOTAVULSA + $TOTAL_QTD_TOTFIDELIZ) * 100 : 0,  2); ?></b>%
											<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"> / </div>
											<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"><?= fnValor((($TOTAL_QTD_TOTAVULSA + ($TOTAL_QTD_TOTFIDELIZ - ($TOTAL_QTD_FUNCIONARIO + $TOTAL_QTD_INATIVO))) != 0) ? (($TOTAL_QTD_TOTFIDELIZ - ($TOTAL_QTD_FUNCIONARIO + $TOTAL_QTD_INATIVO)) / ($TOTAL_QTD_TOTAVULSA + ($TOTAL_QTD_TOTFIDELIZ - ($TOTAL_QTD_FUNCIONARIO + $TOTAL_QTD_INATIVO)))) * 100 : 0, 2) ?> </div>
										</td>


										<?php if ($modulo != 1617) { ?>
											<td class="text-center">
												<b><small>R$ </small><?php echo fnValor($TOTAL_VAL_TOTVENDA, 2); ?></b>
												<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"> / </div>
												<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"><?= fnValor($TOTAL_VAL_TOTVENDA - $TOTAL_VAL_FUNCINATIVO, 2); ?> </div>
											</td>


											<td class="text-center">
												<b><small>R$ </small><?php echo fnValor($TOTAL_VAL_TOTFIDELIZ, 2); ?></b>
												<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"> / </div>
												<div style="font-size: 13px; display: inline; color:rgba(128, 128, 128, 0.6);"><?= fnValor($TOTAL_VAL_TOTFIDELIZ - $TOTAL_VAL_FUNCINATIVO, 2); ?> </div>
											</td>
										<?php } ?>
									</tr>

									<?php

									//fnEscreve($countLinha-1);				
									?>

									</tbody>

									<tfoot>
										<td class="text-left">
											<small>
												<div class="btn-group dropdown left">
													<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fal fa-file-excel"></i>
														&nbsp; Exportar &nbsp;
														<span class="fas fa-caret-down"></span>
													</button>
													<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
														<li><a class="btn btn-sm exportarVendedorCSV">&nbsp;Exportar Vendedores</a></li>
														<li><a class="btn btn-sm exportarLojaCSV">&nbsp;Exportar Lojas </a></li>
														<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
													</ul>
												</div>
											</small>
										</td>
									</tfoot>

								</table>

							</div>

						</div>

						<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
						<input type="hidden" name="mod" id="mod" value="<?= fnEncode($modulo) ?>">
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?= $lojasSelecionadas ?>">
						<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
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

		var cod_empresa = "<?= $cod_empresa ?>";

		/*
		if(cod_empresa == 77){

			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				 maxDate : 'now',
				 minDate : '2018-12-31'
			}).on('changeDate', function(e){
				$(this).datetimepicker('hide');
			});

		}else{
		*/
		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			maxDate: moment().subtract(1, 'days'),
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		//}

		$("#DAT_INI_GRP").on("dp.change", function(e) {
			$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
		});

		$("#DAT_FIM_GRP").on("dp.change", function(e) {
			$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
		});

		$("#LOG_ONLINE").change(function() {
			var v_checked = ($("#LOG_ONLINE:checked").val() == "S");
			$("#DAT_INI").attr("readonly", v_checked);
			$("#DAT_FIM").attr("readonly", v_checked);
			if (v_checked) {
				$("#DAT_INI").val("<?= date("d/m/Y") ?>");
				$("#DAT_FIM").val("<?= date("d/m/Y") ?>");
			} else {
				$("#DAT_INI").val("<?= fnFormatDate($dat_ini) ?>");
				$("#DAT_FIM").val("<?= fnFormatDate($dat_fim) ?>");
			}
		});
		$("#LOG_ONLINE").change();

		// Carregar totais de quantidade na linhas
		$("div[id^='total_col']").each(function(index) {
			var total = 0;
			var total6 = 0;
			var total2 = 0;
			var total7 = 0;
			var total8 = 0;
			var colId = $(this).attr('id').replace('total_col', 'col');

			// Se não tiver a classe porcent faça
			if (!$(this).hasClass('porcent')) {
				$(".qtde_col" + $(this).attr('id').replace('total_col', '')).each(function(index, item) {
					total += limpaValor($(this).text());
				});

				var parts = $(this).attr('id').split('_');

				if (parts[1] === 'col6') {
					let colunaTotal = $("#total_col0_" + parts[2]).text();
					total6 = colunaTotal - total;
				}

				if (parts[1] === 'col7') {
					let colunaVenda = $("#total_col4_" + parts[2]).text();
					total7 = colunaVenda - total;
				}

				if (parts[1] === 'col5') {
					let auxiliar1 = $("#total_col4_" + parts[2]).text();
					let auxiliar2 = $("#total_col7_" + parts[2]).text();
					res = auxiliar1 - auxiliar2;
					total8 = total - res;
				}

				if (parts[1] === 'col2') {
					let auxiliar = $("#total_col6_" + parts[2]).text();
					let totalVendas = $("#total_col0_" + parts[2]).text();
					let totalFidelizados = totalVendas - auxiliar;
					total2 = total - totalFidelizados;
				}

				var totalVar = $('#' + $(this).attr('id'));
				totalVar.unmask();
				if ($(this).hasClass('money')) {

					if (parts[1] === 'col7') {
						totalVar.text(total7.toFixed(2));
						$('#' + colId).text(total7.toFixed(2));
					} else if (parts[1] === 'col5') {
						totalVar.text(total.toFixed(2));
						$('#' + colId).text(total8.toFixed(2));
					} else {
						totalVar.text(total.toFixed(2));
						$('#' + colId).text(total.toFixed(2));
					}

				} else {

					if (parts[1] === 'col6') {
						totalVar.text(total6.toFixed(0));
						$('#' + colId).text(total6.toFixed(0));
					} else if (parts[1] === 'col2') {
						totalVar.text(total.toFixed(0));
						$('#' + colId).text(total2.toFixed(0));
					} else {
						totalVar.text(total.toFixed(0));
						$('#' + colId).text(total.toFixed(0));
					}


					//totalVar.text(total.toFixed(0));
					//$('#' + colId).text(total.toFixed(0));
				}
				// totalVar.mask("#.##0,00", {reverse: true});	

			} else {

				var numLinha = $(this).attr('id').replace('total_col3_', '');
				var result = limpaValor($('#total_col2_' + numLinha).text()) / (limpaValor($('#total_col1_' + numLinha).text()) + limpaValor($('#total_col2_' + numLinha).text())) * 100;
				var totalVar = $('#' + $(this).attr('id'));

				var auxi = limpaValor($('#col2_' + numLinha).text()) / (limpaValor($('#col1_' + numLinha).text()) + limpaValor($('#col2_' + numLinha).text())) * 100;

				//console.log($('#col2_' + numLinha).text());

				totalVar.unmask();
				totalVar.text(result.toFixed(2));
				totalVar.mask("#.##0,00", {
					reverse: true
				});

				var totalCol = $('#' + colId);
				totalCol.unmask();
				totalCol.text(auxi.toFixed(2));
				totalCol.mask("#.##0,00", {
					reverse: true
				});
			}
		});

		$("div[id^='total_col0']").each(function(index) {
			//$(this).text(parseFloat($(this).text()));
		});

		$("div[id^='total_col1']").each(function(index) {
			//$(this).text(parseFloat($(this).text()));
		});

		$("div[id^='total_col2']").each(function(index) {
			//$(this).text(parseFloat($(this).text()));
		});

		$(".exportarVendedorCSV").click(function() {
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
										url: "relatorios/ajxRelIndiceFidelizacao.do?opcao=exportarVendedor&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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

		$(".exportarLojaCSV").click(function() {
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
										url: "relatorios/ajxRelIndiceFidelizacao.do?opcao=exportarLoja&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&LOJAS=<?php echo $lojasSelecionadas; ?>",
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