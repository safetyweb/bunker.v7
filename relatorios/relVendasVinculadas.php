<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$hashLocal = mt_rand();

$log_online = "N";

$hoje = '';
$dias30 = '';
$dat_ini = '';
$dat_fim = '';

//inicialização de variáveis
//$hoje = fnFormatDate(date("Y-m-d"));
$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 2 days')));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_univend = @$_REQUEST['COD_UNIVEND'];
		$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
		$log_online = @$_REQUEST['LOG_ONLINE'];
		if (@$_POST['DAT_INI_ORI'] <> "") {
			$dat_ini = fnDataSql(@$_POST['DAT_INI_ORI']);
		} else {
			$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		}
		if (@$_POST['DAT_FIM_ORI'] <> "") {
			$dat_fim = fnDataSql(@$_POST['DAT_FIM_ORI']);
		} else {
			$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
		}

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
//fnEscreve($nom_empresa);

?>

<style>
	small[class^='qtde_col'] {
		font-weight: normal;
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

							</div>

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
											<input type='hidden' name="DAT_INI_ORI" value="<?php echo fnFormatDate($dat_ini); ?>" />
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
											<input type='hidden' name="DAT_FIM_ORI" value="<?php echo fnFormatDate($dat_fim); ?>" />
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
										<label class="switch switch-small">
											<input type="checkbox" name="LOG_ONLINE" id="LOG_ONLINE" class="switch switch-small" value="S" <?= (@$_POST["LOG_ONLINE"] == "S" ? "checked" : "") ?>>
											<span></span>
										</label>
									</div>
								</div>

								<div class="col-md-3">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>


							</div>

						</fieldset>

						<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
						<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>">

						<div class="push10"></div>

					</form>

					<!-- login-form -->
				</div>

				<!-- portlet-body -->
			</div>

			<!-- Portlet -->
		</div>

		<div class="push30"></div>

		<div class="row">

			<div class="col-md-12 col-lg-12 margin-bottom-30">
				<!-- Portlet -->
				<div class="portlet portlet-bordered">

					<div class="portlet-body">


						<div class="flexrow text-center">

							<div class="form-group text-center col">

								<div class="push20"></div>

								<p><span id="TR"></span></p>
								<p><b>Quantidade Total <br />Resgates</b></p>

								<div class="push20"></div>

							</div>

							<div class="form-group text-center col">

								<div class="push20"></div>

								<p>R$ <span id="IV"></span></p>
								<p><b>Incremento <br />Venda</b></p>

								<div class="push20"></div>

							</div>

							<div class="form-group text-center col">

								<div class="push20"></div>

								<p>R$ <span id="RE"></span></p>
								<p><b>Resgates <br />Efetuados</b></p>

								<div class="push20"></div>

							</div>

							<div class="form-group text-center col">

								<div class="push20"></div>

								<p>R$ <span id="VV"></span></p>
								<p><b>Vendas <br />Vinculadas</b></p>

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

							<div class="push20"></div>

							<table class="table table-bordered table-hover  ">

								<thead>
									<tr>
										<th></th>
										<th><small>Vendedor</small></th>
										<th><small>Qtd. Vendas <br />Avulsas</small></th>
										<th><small>Qtd. Vendas <br />Fidelizados</small></th>
										<th><small>Índice de <br />Fidelização (Qtde.)</small></th>
										<th><small>Vendas <br />Geral Limpo</small></th>
										<th><small>Vendas <br />Fidelizados Limpo</small></th>
										<th class="">
											<div class="form-group">
												<label for="inputName" style="font-size: 16px;" class="control-label"><small><B>Resgates <br />Efetuados</small></B></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_RESGATES_EFETUADOS" id="TOUR_RESGATES_EFETUADOS" maxlength="100" value="">
												<div class="help-block with-errors"></div>
											</div>
										</th>
										<th><small>Qtd. <br />Resgates</small></th>
										<th class="">
											<div class="form-group">
												<label for="inputName" style="font-size: 16px;" class="control-label"><small><B>Vendas <br />Vinculadas</small></B></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_VENDAS_VINCULADAS" id="TOUR_VENDAS_VINCULADAS" maxlength="100" value="">
												<div class="help-block with-errors"></div>
											</div>
										</th>
										<th class="">
											<div class="form-group">
												<label for="inputName" style="font-size: 16px;" class="control-label"><small><B>VVR (%)</small></B></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_VVR" id="TOUR_VVR" maxlength="100" value="">
												<div class="help-block with-errors"></div>
											</div>
										</th>
										<th><small>Incremento <br />Venda</small></th>
									</tr>
								</thead>

								<?php
								// Filtro por Grupo de Lojas
								include "filtroGrupoLojas.php";
								if ($log_online == 'S') {
									/*$sql="SELECT  	B.COD_USUARIO,
																			B.NOM_USUARIO,
																			A.COD_UNIVEND,
																			C.NOM_FANTASI,
																			A.COD_VENDEDOR,
																   Ifnull(Sum(D.val_vinculado), 0) AS VAL_VINCULADO,
																   case  when COD_AVULSO = 1 then count(A.COD_VENDA) ELSE '0' END QTD_TOTAVULSA,
																   case  when COD_AVULSO = 2 then count(A.COD_VENDA) ELSE '0' END QTD_TOTFIDELIZ,       
																   case  when COD_AVULSO = 2 then round(( ( count(A.COD_VENDA) / count(A.COD_VENDA) ) * 100 ), 2)  ELSE '0' END PCT_FIDELIZADO,      
																   case  when COD_AVULSO = 2 then sum(A.VAL_TOTVENDA) ELSE '0' END VAL_TOTFIDELIZ, 
																   (Ifnull(Sum(A.val_totvenda), 0) )  AS VAL_TOTVENDA,
																	 case  when TIP_CREDITO = 'D' then SUM(VAL_CREDITO) ELSE '0' END VAL_RESGATE,
																   case  when TIP_CREDITO = 'C' then SUM(VAL_CREDITO) ELSE '0' END VAL_CREDITOGERADO     
																 
															FROM   vendas A
																   LEFT JOIN $connAdm->DB.usuarios B  ON B.cod_usuario = A.cod_vendedor
																   LEFT JOIN unidadevenda C  ON C.cod_univend = A.cod_univend
																   LEFT JOIN creditosdebitos D ON D.cod_empresa = A.cod_empresa
																										 AND D.cod_univend = A.cod_univend
																										 AND D.cod_vendedor = A.cod_vendedor
																										 AND date(D.DAT_CADASTR) = A.DAT_CADASTR_WS
															WHERE  Date(A.DAT_CADASTR_WS) BETWEEN  '".date('Y-m-d')."' AND '".date('Y-m-d')."'
																   AND A.cod_empresa = $cod_empresa
																   AND A.cod_univend IN($lojasSelecionadas )
															GROUP  BY A.cod_vendedor,
																	  A.cod_univend
															ORDER  BY C.nom_fantasi";*/

									//fnEscreve("if");

									$sql = "SELECT COD_USUARIO,
						                        NOM_USUARIO,
						                        TMPVENDASRT.COD_UNIVEND,
						                        NOM_FANTASI,
						                        TMPVENDASRT.COD_VENDEDOR,
						                        truncate(IFNULL(SUM(D.VAL_VINCULADO), 0),2)    AS VAL_VINCULADO,
						                        SUM(QTD_TOTAVULSA) QTD_TOTAVULSA,
						                        SUM(QTD_TOTFIDELIZ) QTD_TOTFIDELIZ,
						                        truncate((SUM(QTD_TOTFIDELIZ)/ (SUM(QTD_TOTFIDELIZ)+ SUM(QTD_TOTAVULSA)))*100 ,2) AS PCT_FIDELIZADO,
						                        SUM(VAL_TOTFIDELIZ) VAL_TOTFIDELIZ,
						                        truncate(SUM(VAL_TOTAVULSO),2) VAL_TOTAVULSO,
						                        IFNULL(SUM(VAL_TOTVENDA), 0) AS VAL_TOTVENDA  ,
						                        SUM(VAL_RESGATE) VAL_RESGATE,
						                        CAST((CASE WHEN D.TIP_CREDITO = 'C' THEN D.VAL_CREDITO ELSE '0.00' END) AS DECIMAL(15,2)) VAL_CREDITOGERADO,
						                        SUM(CASE WHEN D.TIP_CREDITO = 'D' THEN 1 ELSE 0 END) QTD_RESGATE,                    
						                        truncate(IFNULL(SUM(VAL_VINCULADO)-SUM(VAL_RESGATE) ,0),2) AS INCREMENTO_VENDA,
						                        (((IFNULL(SUM(VAL_VINCULADO),0)/SUM(VAL_RESGATE))-1)*100) AS VVR
						                        FROM ( 
						                            SELECT
						                            A.COD_EMPRESA,
						                            A.COD_VENDA,
						                            B.COD_USUARIO,
						                            B.NOM_USUARIO,
						                            A.COD_UNIVEND,
						                            C.NOM_FANTASI,
						                            A.COD_VENDEDOR,
						                            CASE WHEN COD_AVULSO = 1 THEN A.QTD_VENDA ELSE '0' END  QTD_TOTAVULSA,
						                            CASE WHEN COD_AVULSO = 2 THEN A.QTD_VENDA ELSE '0'  END QTD_TOTFIDELIZ,
						                            '0.00' PCT_FIDELIZADO,
						                            CASE WHEN COD_AVULSO = 2 THEN A.VAL_TOTVENDA ELSE '0' END VAL_TOTFIDELIZ,
						                             CASE WHEN COD_AVULSO = 1 THEN A.VAL_TOTVENDA ELSE '0' END VAL_TOTAVULSO,
						                            A.VAL_TOTVENDA,
						                            A.VAL_RESGATE,
						                           '0.00' VAL_CREDITOGERADO,
						                            '' INCREMENTO_VENDA  ,
						                           '0.00' QTD_RESGATE
						                            FROM VENDAS A
						                            LEFT JOIN USUARIOS B ON B.COD_USUARIO = A.COD_VENDEDOR
						                            LEFT JOIN UNIDADEVENDA C ON C.COD_UNIVEND = A.COD_UNIVEND
						                            WHERE date(A.DAT_CADASTR_WS) BETWEEN CURDATE() AND CURDATE()
						                            AND A.COD_EMPRESA = $cod_empresa
						                            AND A.COD_UNIVEND IN($lojasSelecionadas)
						                            ) TMPVENDASRT            
						                             LEFT JOIN CREDITOSDEBITOS D  ON D.COD_EMPRESA = TMPVENDASRT.COD_EMPRESA AND D.COD_VENDA =TMPVENDASRT.COD_VENDA 
						                             GROUP  BY  TMPVENDASRT.COD_VENDEDOR,TMPVENDASRT.COD_UNIVEND
						                            ORDER  BY NOM_FANTASI;";
								} else {
									//fnEscreve("else");
									$sql = "SELECT 	A.COD_USUARIO, 
													B.NOM_USUARIO, 
													A.COD_UNIVEND, 
													C.NOM_FANTASI, 
													A.COD_VENDEDOR,
													IFNULL(SUM(D.VAL_VINCULADO1),0) AS VAL_VINCULADO,
													SUM(A.QTD_TOTAVULSA) AS QTD_TOTAVULSA, 
													SUM(A.QTD_TOTFIDELIZ) AS QTD_TOTFIDELIZ,																
													ROUND(((SUM(A.QTD_TOTFIDELIZ)/SUM(A.QTD_TOTVENDA))*100),2) AS PCT_FIDELIZADO,													
													(IFNULL(SUM(A.VAL_TOTFIDELIZ),0)) AS VAL_TOTFIDELIZ,									
													(IFNULL(SUM(A.VAL_TOTVENDA),0)) AS VAL_TOTVENDA,
													SUM(D.VAL_RESGATE) AS VAL_RESGATE,
													SUM(D.VAL_CREDITO_GERADO) AS VAL_CREDITOGERADO,
													IFNULL(SUM(D.VAL_VINCULADO1),0)-SUM(D.VAL_RESGATE) AS INCREMENTO_VENDA,
													IFNULL(SUM(D.QTD_RESGATE),0)  QTD_RESGATE
													FROM VENDAS_DIARIAS A 
													LEFT JOIN USUARIOS B ON B.COD_USUARIO = A.COD_VENDEDOR 
													LEFT JOIN UNIDADEVENDA C ON C.COD_UNIVEND = A.COD_UNIVEND 
													LEFT JOIN CREDITOSDEBITOS_DIARIAS D ON D.COD_EMPRESA=A.COD_EMPRESA AND D.COD_UNIVEND=A.COD_UNIVEND AND D.COD_VENDEDOR=A.COD_VENDEDOR AND D.DAT_MOVIMENTO=A.DAT_MOVIMENTO
													WHERE DATE_FORMAT(A.DAT_MOVIMENTO, '%Y-%m-%d') >= '$dat_ini' 
													AND	DATE_FORMAT(A.DAT_MOVIMENTO, '%Y-%m-%d') <= '$dat_fim'  
													AND	A.COD_EMPRESA = $cod_empresa
													AND A.COD_UNIVEND IN($lojasSelecionadas) 
													GROUP BY A.COD_VENDEDOR,A.COD_UNIVEND 
													ORDER BY C.NOM_FANTASI ";
								}
								//SUM(A.VAL_TOTVENDA) AS VAL_TOTVENDA, 
								//SUM(A.VAL_TOTFIDELIZ) AS VAL_TOTFIDELIZ,
								//SUM(D.VAL_VINCULADO) AS VAL_VINCULADO,

								//fnEscreve($sql);   

								$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

								if (mysqli_num_rows($arrayQuery) != 0) {
									$countLinha = 1;
									$tot_fideliz_uni = 0;
									$tot_avulso_uni = 0;
									$tot_indice_uni = 0;
									while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
										//  fnEscreve($qrListaVendas['INCREMENTO_VENDA']);
										//monta primeiro cabeçalho
										if ($countLinha == 1) {
											$loja = $qrListaVendas['COD_UNIVEND'];
								?>
											<thead>
												<tr id="bloco_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
													<th width="5%" class="text-center"><a href="javascript:void(0);" onclick="abreDetail(<?php echo $qrListaVendas['COD_UNIVEND']; ?>)" style="padding:10px;"><i class="fa fa-angle-right" aria-hidden="true"></i></a></th>
													<th width="25%"><?php echo $qrListaVendas['NOM_FANTASI']; ?></th>
													<th width="5%" class="text-center">
														<div id="total_col1_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
													</th>
													<th width="7%" class="text-center">
														<div id="total_col2_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
													</th>
													<th width="7%" class="text-center">
														<div style="display: inline;" id="INDICE_FIDELIZ_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div><small>%</small>
													</th>
													<th width="8%" class="text-center"><small><small>R$ </small></small>
														<div style="display: inline;" id="total_col3_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
													</th>
													<th width="8%" class="text-center"><small><small>R$ </small></small>
														<div style="display: inline;" id="total_col4_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
													</th>
													<th width="7%" class="text-center"><small><small>R$ </small></small>
														<div class="VAL_RESGATE" style="display: inline;" id="total_col5_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
													</th>
													<th width="7%" class="text-center">
														<div id="total_col10_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
													</th>
													<th width="7%" class="text-center"><small><small>R$ </small></small>
														<div class="VAL_VINCULADO" style="display: inline;" id="total_col6_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
													</th>
													<th width="7%" class="text-center">
														<div style="display: inline;" class="porcent" id="total_col7_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>%
													</th>
													<th width="7%" class="text-center"><small><small>R$ </small></small>
														<div style="display: inline;" id="total_col9_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
													</th>
												</tr>
											</thead>
											</tbody>
										<?php
										}
										//monta primeira linha
										if ($loja != $qrListaVendas['COD_UNIVEND']) {

										?>

											<script>
												$(function() {
													var tot_fideliz = "<?= fnValor(100 - (($tot_avulso_uni * 100) / $totalVendas_uni), 2) ?>";
													$("#INDICE_FIDELIZ_<?= $loja ?>").text(tot_fideliz);
												});
											</script>

											<?php

											$loja = $qrListaVendas['COD_UNIVEND'];
											$tot_avulso_uni = 0;
											$totalVendas_uni = 0;

											?>
											<thead>
												<tr id="bloco_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
													<th width="5%" class="text-center"><a href="javascript:void(0);" onclick="abreDetail(<?php echo $qrListaVendas['COD_UNIVEND']; ?>)" style="padding:10px;"><i class="fa fa-angle-right" aria-hidden="true"></i></a></th>
													<th width="25%"><?php echo $qrListaVendas['NOM_FANTASI']; ?></th>
													<th width="5%" class="text-center">
														<div id="total_col1_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
													</th>
													<th width="7%" class="text-center">
														<div id="total_col2_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
													</th>
													<th width="7%" class="text-center">
														<div style="display: inline;" id="INDICE_FIDELIZ_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div><small>%</small>
													</th>
													<th width="8%" class="text-center"><small><small>R$ </small></small>
														<div style="display: inline;" id="total_col3_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
													</th>
													<th width="8%" class="text-center"><small><small>R$ </small></small>
														<div style="display: inline;" id="total_col4_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
													</th>
													<th width="7%" class="text-center"><small><small>R$ </small></small>
														<div class="VAL_RESGATE" style="display: inline;" id="total_col5_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
													</th>
													<th width="7%" class="text-center">
														<div id="total_col10_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
													</th>
													<th width="7%" class="text-center"><small><small>R$ </small></small>
														<div class="VAL_VINCULADO" style="display: inline;" id="total_col6_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
													</th>
													<th width="7%" class="text-center">
														<div style="display: inline;" class="porcent" id="total_col7_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>%
													</th>
													<th width="7%" class="text-center"><small><small>R$ </small></small>
														<div style="display: inline;" id="total_col9_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
													</th>
												</tr>
											</thead>
											</tbody>
										<?php
										}

										$totalVendas = $qrListaVendas['QTD_TOTAVULSA'] + $qrListaVendas['QTD_TOTFIDELIZ'];
										$indicefideliz = 100 -  $totalVendas !=  0  ? (($qrListaVendas['QTD_TOTFIDELIZ'] * 100) / $totalVendas) : 0;

										$vltotalperceto = fnValor($qrListaVendas['VAL_RESGATE'] != 0 ? ((($qrListaVendas['VAL_VINCULADO'] / $qrListaVendas['VAL_RESGATE']) - 1) * 100) : 0, 2);
										// fnEscreve($qrListaVendas['VAL_VINCULADO']);
										// fnEscreve($qrListaVendas['VAL_RESGATE']);
										if ($vltotalperceto < '0,00') {
											$vltotalperceto = '0,00';
										} else {
											$vltotalperceto = $vltotalperceto;
										}
										?>
										<tr style="background-color: #fff; display: none;" class="abreDetail_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
											<th width="5%">
												</td>
											<th width="25%"><small><b><?php echo $qrListaVendas['NOM_USUARIO']; ?></b></small></td>
											<th width="5%" class="text-center"><small class="qtde_col1_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['QTD_TOTAVULSA'], 0); ?></small></td>
											<th width="5%" class="text-center"><small class="qtde_col2_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['QTD_TOTFIDELIZ'], 0); ?></small></td>
											<th width="7%" class="text-center"><small class="qtde_col8_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($indicefideliz, 2); ?></small><small>%</small></td>
											<th width="10%" class="text-center"><small><small>R$ </small></small><small class="qtde_col3_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['VAL_TOTVENDA'], 2); ?></small></td>
											<th width="10%" class="text-center"><small><small>R$ </small></small><small class="qtde_col4_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['VAL_TOTFIDELIZ'], 2); ?></small></td>
											<th width="8%" class="text-center"><small><small>R$ </small></small><small class="qtde_col5_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['VAL_RESGATE'], 2); ?></small></td>
											<th width="7%" class="text-center"><small class="qtde_col10_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['QTD_RESGATE'], 0); ?></small></td>
											<th width="6%" class="text-center"><small><small>R$ </small></small><small class="qtde_col6_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['VAL_VINCULADO'], 2); ?></small></td>
											<th width="8%" class="text-center"><small class="qtde_col7_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo $vltotalperceto; ?>%</small></td>
											<th width="6%" class="text-center"><small><small>R$ </small></small><small class="qtde_col9_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['INCREMENTO_VENDA'], 2); ?></small></td>
										</tr>
									<?php

										@$TOTAL_QTD_TOTAVULSA += $qrListaVendas['QTD_TOTAVULSA'];
										@$TOTAL_QTD_TOTFIDELIZ += $qrListaVendas['QTD_TOTFIDELIZ'];
										@$TOTAL_VAL_TOTVENDA += $qrListaVendas['VAL_TOTVENDA'];
										@$TOTAL_VAL_TOTFIDELIZ += $qrListaVendas['VAL_TOTFIDELIZ'];
										@$TOTAL_VAL_RESGATE += $qrListaVendas['VAL_RESGATE'];
										@$TOTAL_VAL_VINCULADO += $qrListaVendas['VAL_VINCULADO'];
										@$TOTAL_INCREMENTO_VENDA += $qrListaVendas['INCREMENTO_VENDA'];
										@$TOTAL_QTD_RESGATE += $qrListaVendas['QTD_RESGATE'];
										@$TOTAL_VENDAS += $totalVendas;

										@$tot_fideliz_uni += @$qrListaVendas['QTD_TOTFIDELIZ'];
										@$tot_avulso_uni += @$qrListaVendas['QTD_TOTAVULSA'];
										@$tot_indice_uni += @$indicefideliz;
										@$totalVendas_uni += @$totalVendas;

										$countLinha++;
									}

									?>

									<script>
										$(function() {
											var tot_fideliz = "<?= fnValor(100 - (($tot_avulso_uni * 100) / $totalVendas_uni), 2) ?>";
											$("#INDICE_FIDELIZ_<?= $loja ?>").text(tot_fideliz);
										});
									</script>

									<tr>
										<td></td>
										<td></td>
										<td class="text-center"><b><?= fnValor($TOTAL_QTD_TOTAVULSA, 0) ?></b></small></td>
										<td class="text-center"><b><?= fnValor($TOTAL_QTD_TOTFIDELIZ, 0) ?></b></small></td>
										<td class="text-center"><b><?= fnValor((100 - ($TOTAL_QTD_TOTAVULSA * 100) / $TOTAL_VENDAS), 2) ?>%</b></small></td>
										<td class="text-center"><b><small>R$ </small><?= fnValor($TOTAL_VAL_TOTVENDA, 2) ?></b></td>
										<td class="text-center"><b><small>R$ </small><?= fnValor($TOTAL_VAL_TOTFIDELIZ, 2) ?></b></td>
										<td class="text-center"><b><small>R$ </small><?= fnValor($TOTAL_VAL_RESGATE, 2) ?></b></td>
										<td class="text-center"><b><?= fnValor($TOTAL_QTD_RESGATE, 0) ?></b></small></td>
										<td class="text-center"><b><small>R$ </small><?= fnValor($TOTAL_VAL_VINCULADO, 2) ?></b></td>
										<td class="text-center"><b><?= fnValor(((($TOTAL_VAL_VINCULADO / $TOTAL_VAL_RESGATE) - 1) * 100), 2) ?>%</b></td>
										<td class="text-center"><b><small>R$ </small><?= fnValor($TOTAL_INCREMENTO_VENDA, 2) ?></b></td>
									</tr>

									<script>
										$("#VA").text("<?= fnValor($TOTAL_QTD_TOTAVULSA, 0) ?>");
										$("#VF").text("<?= fnValor($TOTAL_QTD_TOTFIDELIZ, 0) ?>");
										$("#IF").text("<?= fnValor((100 - ($TOTAL_QTD_TOTAVULSA * 100) / $TOTAL_VENDAS), 2) ?>");
										$("#VGL").text("<?= fnValor($TOTAL_VAL_TOTVENDA, 2) ?>");
										$("#VFL").text("<?= fnValor($TOTAL_VAL_TOTFIDELIZ, 2) ?>");
										$("#RE").text("<?= fnValor($TOTAL_VAL_RESGATE, 2) ?>");
										$("#VV").text("<?= fnValor($TOTAL_VAL_VINCULADO, 2) ?>");
										$("#VVR").text("<?= fnValor(((($TOTAL_VAL_VINCULADO / $TOTAL_VAL_RESGATE) - 1) * 100), 2) ?>%");
										$("#IV").text("<?= fnValor($TOTAL_INCREMENTO_VENDA, 2) ?>");
										$("#TR").text("<?= fnValor($TOTAL_QTD_RESGATE, 0) ?>");
									</script>

								<?php

								} else {
								?>
									<tbody>
										<thead>
											<tr>
												<th colspan="100">
													<center>
														<div style="margin: 10px; font-size: 17px; font-weight: bold">Não há vendas vinculadas a resgate nesse período</div>
													</center>
												</th>
											</tr>
										</thead>
									</tbody>

								<?php
								}
								?>
								</tbody>

								<tfoot>
									<td class="text-left">
										<small>
											<?php if ($log_online == "N") $textoOnline = "";
											else $textoOnline = "Online "; ?>
											<div class="btn-group dropdown left">
												<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fal fa-file-excel"></i>
													&nbsp; Exportar <?= $textoOnline ?>&nbsp;
													<span class="fas fa-caret-down"></span>
												</button>
												<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
													<li><a class="btn btn-sm exportarCSV" data-attr="all" style="text-align: left"><i aria-hidden="true"></i>&nbsp; Exportar <?= $textoOnline ?>(Geral) </a></li>
													<li><a class="btn btn-sm exportarCSV" data-attr="univend" style="text-align: left"><i aria-hidden="true"></i>&nbsp; Exportar <?= $textoOnline ?>(Unidades) </a></li>
												</ul>
											</div>
										</small>
									</td>
									<!--<tr>
										<th colspan="">
											<a class="btn btn-info btn-sm exportarCSV" data-attr="all"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar (Geral) </a>
										</th>
										<th colspan="">
											<a class="btn btn-info btn-sm exportarCSV" data-attr="univend"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar (Unidades) </a>
										</th>
									</tr>
									-->
								</tfoot>

							</table>

						</div>

					</div>

					<div class="push5"></div>

					<div class="push50"></div>

					<div class="push"></div>

				</div>

				<span class="f12" style="color: #fff;">
					<?php
					// echo ($sql);
					?>
				</span>

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

		var TOTAL_VAL_VINCULADO = 0;
		var TOTAL_VAL_RESGATE = 0;

		// Carregar totais de quantidade na linhas
		var count = 0;
		$("div[id^='total_col']").each(function(index) {
			var total = 0;
			count++;

			if (!$(this).hasClass('porcent')) {
				$(".qtde_col" + $(this).attr('id').replace('total_col', '')).each(function(index, item) {
					total += limpaValor($(this).text());
				});

				if ($(this).hasClass('VAL_VINCULADO')) {
					TOTAL_VAL_VINCULADO = total;
				}

				if ($(this).hasClass('VAL_RESGATE')) {
					TOTAL_VAL_RESGATE = total;
				}

				var totalVar = $('#' + $(this).attr('id'));
				totalVar.unmask();
				totalVar.text(total.toFixed(2));
				totalVar.mask("#.##0,00", {
					reverse: true
				});
			} else {

				if (TOTAL_VAL_VINCULADO == 0 && TOTAL_VAL_RESGATE == 0) {
					var resultado = -100;
				} else {
					var resultado = ((TOTAL_VAL_VINCULADO / TOTAL_VAL_RESGATE) - 1) * 100;
				}


				var totalVar = $('#' + $(this).attr('id'));
				totalVar.unmask();
				totalVar.text(resultado.toFixed(2));
				//totalVar.mask("#.##0,00", {reverse: true});	

				TOTAL_VAL_VINCULADO = 0;
				TOTAL_VAL_RESGATE = 0;
			}
		});

		$("div[id^='total_col1']").each(function() {
			$(this).text($(this).text().slice(0, -3));
		});

		$("div[id^='total_col2']").each(function() {
			$(this).text($(this).text().slice(0, -3));
		});

		$(".exportarCSV").click(function() {
			let tipo = $(this).attr("data-attr");
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
										url: "relatorios/ajxRelVendasVinculadas.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&tipo=" + tipo,
										data: $('#formulario').serialize(),
										method: 'POST'
									}).done(function(response) {
										self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
										var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
										SaveToDisk('media/excel/' + fileName, fileName);
										//aqui escrevo no console o retorno
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