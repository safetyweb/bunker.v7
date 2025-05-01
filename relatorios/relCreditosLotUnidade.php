<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}


// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina  = "1";


$dat_ini = '';
$dat_fim = '';
$hoje = '';


$hashLocal = mt_rand();

//inicialização de variáveis
//$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 60 days')));
$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-01'));
$cod_status = 11;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_univend = @$_POST['COD_UNIVEND'];
		$cod_credlot = @$_POST['COD_CREDLOT'];
		$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
		$cod_status = @$_POST['COD_STATUS'];
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
		$log_resgate = @$_REQUEST['LOG_RESGATE'];

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
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

if (!isset($cod_controle)) {
	$cod_controle = 0;
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

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

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

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Região</label>
										<?php include "grupoRegiaoMulti.php"; ?>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Persona</label>

										<select data-placeholder="Selecione uma ou mais unidades" multiple="multiple" name="COD_CREDLOT[]" id="COD_CREDLOT" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
											<?php

											$sql = "SELECT B.DES_PERSONA,
											B.COD_PERSONA,
											A.QTD_PESCLASS,
											A.VAL_CREDITO,
											(A.QTD_PESCLASS*A.VAL_CREDITO) AS TOT_CREDITO,
											A.DAT_CADASTR,
											A.DAT_VALIDADE,
											B.LOG_ATIVO,
											A.COD_CREDLOT
											FROM PERSONA B
											INNER JOIN CREDITOS_LOT A ON A.COD_PERSONAS=B.COD_PERSONA AND A.cod_empresa=$cod_empresa
											WHERE B.COD_EMPRESA = $cod_empresa
											AND B.LOG_ATIVO = 'S'
											ORDER BY A.DAT_CADASTR DESC";

											$cod_credlot = isset($cod_credlot) ? $cod_credlot : [];
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
											while ($qrListaCamp = mysqli_fetch_assoc($arrayQuery)) {
												$selected = '';

												if (in_array($qrListaCamp['COD_CREDLOT'], $cod_credlot)) {
													$selected = 'selected';
												}

												echo "
														<option value='" . $qrListaCamp['COD_CREDLOT'] . "'" . $selected . ">" . ucfirst($qrListaCamp['DES_PERSONA']) . " " . fnDataFull($qrListaCamp['DAT_CADASTR']) . "</option>
														";
											}
											?>
										</select>

										<div class="help-block with-errors"></div>
										<a class="btn btn-default btn-sm" id="iAlll" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square-o" aria-hidden="true"></i> selecionar todos</a>&nbsp;
										<a class="btn btn-default btn-sm" id="iNonee" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>

										<div class="help-block with-errors"></div>
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

				<div class="row text-center">

					<?php
					// Filtro por Grupo de Lojas
					include "filtroGrupoLojas.php";

					$creditoSelecionados = implode(",", @$cod_credlot);
					$andCreditos = '';

					if (!empty($creditoSelecionados)) {
						$andCreditos = "AND b.COD_CREDLOT IN ($creditoSelecionados)";
					}

					$sql = "SELECT 
					COUNT(distinct c.COD_CLIENTE) AS QTD_CLIENTE, 
					SUM(b.VAL_CREDITO/(SELECT COUNT(*) FROM historico_resgate WHERE historico_resgate.VAL_RESGATADO != historico_resgate.VAL_ESTORNO AND historico_resgate.cod_credito = a.cod_credito)) AS TOT_CREDCAMPANHA, 
					SUM(a.val_resgatado) AS TOT_CAMPANHA,
					SUM((SELECT val_totprodu FROM vendas WHERE vendas.cod_venda = a.COD_VENDA_COM_RESGATE AND vendas.cod_statuscred IN (0, 1, 2, 3, 4, 5, 7, 8, 9))) AS tot_vinculado
					FROM historico_resgate a, creditosdebitos b, clientes c, unidadevenda d 
					WHERE a.COD_CREDITO = b.cod_credito 
					AND a.COD_UNIVEND = d.COD_UNIVEND 
					AND b.cod_empresa = $cod_empresa 
					AND a.COD_UNIVEND IN ($lojasSelecionadas) 
					AND b.cod_cliente = c.COD_CLIENTE 
					AND b.cod_credlot != 0 
					AND a.DAT_CADASTR >= '$dat_ini 00:00:00' 
					AND a.DAT_CADASTR <= '$dat_fim 23:59:59' 
					AND a.VAL_RESGATADO != a.VAL_ESTORNO 
					$andCreditos 
					ORDER BY a.COD_CREDITO, a.cod_cliente";

					$query = mysqli_query(connTemp($cod_empresa, ''), $sql);

					if (!$query) {
						die("Erro na consulta: " . mysqli_error(connTemp($cod_empresa, '')));
					}

					$qrBusca = mysqli_fetch_assoc($query);

					?>
					<div class="form-group text-center col-md-1 col-lg-1"></div>

					<div class="form-group text-center col-md-2 col-lg-2">

						<div class="push20"></div>

						<div class="form-group">
							<input type="text" class="form-control input-sm leitura text-center" readonly="readonly" name="QTD_CLIENTES" id="QTD_CLIENTES" maxlength="100" value="<?= fnValor($qrBusca['QTD_CLIENTE'], 0) ?>">
							<label for="inputName" class="control-label"><b>Qtd. Clientes que Resgataram</b></label>
							<div class="help-block with-errors"></div>
						</div>


						<div class="push20"></div>

					</div>

					<div class="form-group text-center col-md-2 col-lg-2">

						<div class="push20"></div>

						<div class="form-group">
							<input type="text" class="form-control input-sm leitura text-center" readonly="readonly" name="TOT_BONUS_CONCEDIDO" id="TOT_BONUS_CONCEDIDO" maxlength="100" value="R$ <?= fnValor($qrBusca['TOT_CREDCAMPANHA'], 2) ?>">
							<label for="inputName" class="control-label"><b>Tot. Bônus Concedido que Houve Resgate</b></label>
							<div class="help-block with-errors"></div>
						</div>

						<div class="push20"></div>

					</div>

					<div class="form-group text-center col-md-2 col-lg-2">

						<div class="push20"></div>

						<div class="form-group">
							<input type="text" class="form-control input-sm leitura text-center" readonly="readonly" name="TOT_BONUS_SEM_RESGATE" id="TOT_BONUS_SEM_RESGATE" maxlength="100" value="R$ <?= fnValor($qrBusca['TOT_CAMPANHA'], 2) ?>">
							<label for="inputName" class="control-label"><b>Tot. Bônus Resgatado</b></label>
							<div class="help-block with-errors"></div>
						</div>

						<div class="push20"></div>

					</div>

					<div class="form-group text-center col-md-2 col-lg-2">

						<div class="push20"></div>

						<div class="form-group">
							<input type="text" class="form-control input-sm leitura text-center" readonly="readonly" name="TOT_RESGATADO" id="TOT_RESGATADO" maxlength="100" value="R$ <?= fnValor($qrBusca['TOT_CREDCAMPANHA'] - $qrBusca['TOT_CAMPANHA'], 2) ?>">
							<label for="inputName" class="control-label"><b>Tot. Bônus Sem Resgate</b></label>
							<div class="help-block with-errors"></div>
						</div>

						<div class="push20"></div>

					</div>

					<div class="form-group text-center col-md-2 col-lg-2">

						<div class="push20"></div>

						<div class="form-group">
							<input type="text" class="form-control input-sm leitura text-center" readonly="readonly" name="TOT_DISPONIVEL_PARA_RESGATE" id="TOT_DISPONIVEL_PARA_RESGATE" maxlength="100" value="R$ <?= fnValor($qrBusca['tot_vinculado'], 2) ?>">
							<label for="inputName" class="control-label"><b>Tot. Vendas Vinculadas</b></label>
							<div class="help-block with-errors"></div>
						</div>

						<div class="push20"></div>

					</div>

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

										<th>
											<div class="form-group">
												<label for="inputName" style="font-size: 16px;" class="control-label"><small><b>Cód. Unidade</b></small></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_COD_UNIVEND" id="TOUR_COD_UNIVEND" maxlength="100" value="">
												<div class="help-block with-errors"></div>
											</div>
										</th>

										<th>
											<div class="form-group">
												<label for="inputName" style="font-size: 16px;" class="control-label"><small><b>Unidade</b></small></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_UNIDADE" id="TOUR_UNIDADE" maxlength="100" value="">
												<div class="help-block with-errors"></div>
											</div>
										</th>

										<th>
											<div class="form-group">
												<label for="inputName" style="font-size: 16px;" class="control-label"><small><b>Cód. Cliente</b></small></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_COD_CLIENTE" id="TOUR_COD_CLIENTE" maxlength="100" value="">
												<div class="help-block with-errors"></div>
											</div>
										</th>

										<th>
											<div class="form-group">
												<label for="inputName" style="font-size: 16px;" class="control-label"><small><b>Nome Cliente</b></small></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_NOM_CLIENTE" id="TOUR_NOM_CLIENTE" maxlength="100" value="">
												<div class="help-block with-errors"></div>
											</div>
										</th>

										<th>
											<div class="form-group">
												<label for="inputName" style="font-size: 16px;" class="control-label"><small><b>Cód. Crédito</b></small></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_CODIGO_CREDITO" id="TOUR_CODIGO_CREDITO" maxlength="100" value="">
												<div class="help-block with-errors"></div>
											</div>
										</th>

										<th>
											<div class="form-group">
												<label for="inputName" style="font-size: 16px;" class="control-label"><small><b>Valor do crédito</b></small></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_VAL_CREDITO" id="TOUR_VAL_CREDITO" maxlength="100" value="">
												<div class="help-block with-errors"></div>
											</div>
										</th>

										<th>
											<div class="form-group">
												<label for="inputName" style="font-size: 16px;" class="control-label"><small><b>Valor Resgatado</b></small></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_VAL_RESGATADO" id="TOUR_VAL_RESGATADO" maxlength="100" value="">
												<div class="help-block with-errors"></div>
											</div>
										</th>

										<th>
											<div class="form-group">
												<label for="inputName" style="font-size: 16px;" class="control-label"><small><b>Venda Vinculada</b></small></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_VEND_VINCULADA" id="TOUR_VEND_VINCULADA" maxlength="100" value="">
												<div class="help-block with-errors"></div>
											</div>
										</th>

										<th>
											<div class="form-group">
												<label for="inputName" style="font-size: 16px;" class="control-label"><small><b>Data Resgate</b></small></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_DAT_RESGATE" id="TOUR_DAT_RESGATE" maxlength="100" value="">
												<div class="help-block with-errors"></div>
											</div>
										</th>
									</tr>
								</thead>

								<tbody id="relatorioConteudo">

									<?php
									// Filtro por Grupo de Lojas
									include "filtroGrupoLojas.php";

									//fnEscreve($cod_credlot);

									$creditoSelecionados = implode(",", @$cod_credlot);
									$andCreditos = '';

									if (!empty($creditoSelecionados)) {
										$andCreditos = "AND b.COD_CREDLOT IN ($creditoSelecionados)";
									}

									$sql = "SELECT  a.COD_CREDITO,
									d.COD_UNIVEND, 
									d.NOM_FANTASI, 
									c.COD_CLIENTE, 
									c.NOM_CLIENTE, 
									a.DAT_CADASTR,
									b.VAL_CREDITO/(SELECT COUNT(*) FROM historico_resgate WHERE historico_resgate.VAL_RESGATADO!=historico_resgate.VAL_ESTORNO AND historico_resgate.cod_credito=a.cod_credito) AS val_credito,  
									a.val_resgatado


									FROM historico_resgate a,creditosdebitos b,clientes c, unidadevenda d
									WHERE a.COD_CREDITO=b.cod_credito AND 
									a.COD_UNIVEND=d.COD_UNIVEND AND 
									b.cod_empresa=$cod_empresa AND
									a.COD_UNIVEND IN($lojasSelecionadas) AND 
									b.cod_cliente=c.COD_CLIENTE AND 
									b.cod_credlot != 0 AND 
									a.DAT_CADASTR >= '$dat_ini 00:00:00' AND a.DAT_CADASTR <= '$dat_fim 23:59:59' AND 
									a.VAL_RESGATADO!=a.VAL_ESTORNO
									$andCreditos 
									ORDER BY a.COD_CREDITO, a.COD_CLIENTE
									";

									$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
									$total_itens_por_pagina = mysqli_num_rows($retorno);

									$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

									$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

									/*$sql = "SELECT  a.COD_CREDITO,
									d.COD_UNIVEND, 
									d.NOM_FANTASI, 
									c.COD_CLIENTE, 
									c.NOM_CLIENTE, 
									a.DAT_CADASTR,
									b.VAL_CREDITO/(SELECT COUNT(*) FROM historico_resgate WHERE historico_resgate.VAL_RESGATADO!=historico_resgate.VAL_ESTORNO AND historico_resgate.cod_credito=a.cod_credito) AS val_credito,  
									a.val_resgatado

									FROM historico_resgate a,creditosdebitos b,clientes c, unidadevenda d
									WHERE a.COD_CREDITO=b.cod_credito AND 
									a.COD_UNIVEND=d.COD_UNIVEND AND 
									b.cod_empresa=$cod_empresa AND
									a.COD_UNIVEND IN($lojasSelecionadas) AND 
									b.cod_cliente=c.COD_CLIENTE AND 
									b.cod_credlot != 0 AND 
									b.DAT_CADASTR >= '$dat_ini' AND A.DAT_CADASTR <= '$dat_fim' AND 
									a.VAL_RESGATADO!=a.VAL_ESTORNO
									$andCredlot
									ORDER BY a.COD_CREDITO, a.COD_CLIENTE
									LIMIT $inicio, $itens_por_pagina
									";*/

									//Adilson adicionou o campo VVR, Atualizado por Lucas 02/04/2024
									$sql = "SELECT 	a.COD_CREDITO, 
									d.COD_UNIVEND, 
									d.NOM_FANTASI, 
									c.COD_CLIENTE,
									c.NOM_CLIENTE, 
									a.DAT_CADASTR, 
									b.VAL_CREDITO/(SELECT COUNT(*) FROM historico_resgate WHERE historico_resgate.VAL_RESGATADO!=historico_resgate.VAL_ESTORNO AND historico_resgate.cod_credito=a.cod_credito) AS val_credito, 
									a.val_resgatado,
									(select val_totprodu from vendas where vendas.cod_venda=a.COD_VENDA_COM_RESGATE AND vendas.cod_statuscred IN(0,1,2,3,4,5,7,8,9)) AS val_compra_vvr  
									FROM historico_resgate a,creditosdebitos b,clientes c, unidadevenda d 
									WHERE a.COD_CREDITO=b.cod_credito AND 
									a.COD_UNIVEND=d.COD_UNIVEND AND 
									b.cod_empresa=$cod_empresa AND 
									a.COD_UNIVEND IN($lojasSelecionadas) AND 
									b.cod_cliente=c.COD_CLIENTE AND 
									b.cod_credlot != 0 AND 
									a.DAT_CADASTR >= '$dat_ini 00:00:00' AND 
									a.DAT_CADASTR <= '$dat_fim 23:59:59' AND 
									a.VAL_RESGATADO!=a.VAL_ESTORNO 
									$andCreditos 
									order by a.COD_CREDITO,a.cod_cliente
									LIMIT $inicio, $itens_por_pagina";

									//fnEscreve($sql);
									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

									if (mysqli_num_rows($arrayQuery) != 0) {

										// fnEscreve("if");
										$countLinha = 1;
										while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

									?>
											<tr>
												<td><small><?php echo $qrListaVendas['COD_UNIVEND']; ?></small></td>
												<td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>

												<?php
												if ($autoriza == 1) {
												?>
													<td><a href="action.do?mod=<?php echo fnEncode(1081); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['COD_CLIENTE']; ?></a></td>
												<?php
												} else {
												?>
													<td><?php echo $qrListaVendas['COD_CLIENTE']; ?></td>
												<?php
												}
												?>
												<td><small><?php echo $qrListaVendas['NOM_CLIENTE']; ?></small></td>
												<td><small><?php echo $qrListaVendas['COD_CREDITO']; ?></small></td>
												<td class="text-right"><small><?php echo fnValor($qrListaVendas['val_credito'], $casasDec); ?></small></td>
												<td class="text-right"><small><?php echo fnValor(($qrListaVendas['val_resgatado'] * -1), $casasDec); ?></small></td>
												<td class="text-right"><small><?php echo fnValor($qrListaVendas['val_compra_vvr'], 2); ?></small></td>
												<td><small><?php echo fnDataShort($qrListaVendas['DAT_CADASTR']); ?></small></td>

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
							<?php
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


		$('#iAlll').on('click', function(e) {
			e.preventDefault();
			$('#COD_UNIVEND option').prop('selected', true).trigger('chosen:updated');
		});

		$('#iNonee').on('click', function(e) {
			e.preventDefault();
			$("#COD_UNIVEND option:selected").removeAttr("selected").trigger('chosen:updated');
		});

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
										url: "relatorios/ajxrelCreditosLotUnidade.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&itens_por_pagina=<?php echo $itens_por_pagina; ?>&idc=<?= fnEncode(@$cod_controle) ?>&credlot=<?= $creditoSelecionados ?>",
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
			url: "relatorios/ajxrelCreditosLotUnidade.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?= fnEncode($cod_controle) ?>&itens_por_pagina=<?php echo $itens_por_pagina; ?>&lojas=<?php echo $lojasSelecionadas ?>&credlot=<?= $creditoSelecionados ?>&idPage=" + idPage,
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