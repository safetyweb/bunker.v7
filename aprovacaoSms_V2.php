<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();
$qtd_push = 0;
$qtd_wpp = 0;
$qtd_email = 0;
$qtd_sms = 0;
$qtd_whatsapp = 0;
$count = 0;
$cod_personas = 0;
if (isset($_GET['pop'])) {
	$popUp = fnLimpaCampo($_GET['pop']);
} else {
	$popUp = '';
}

$id_msg = "msgRetorno";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_campanha = fnLimpaCampo(@$_REQUEST['COD_CAMPANHA']);
		$cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);
		$des_emailex = fnLimpaCampo(@$_POST['DES_EMAILEX']);
		$cod_template = fnLimpaCampoZero(@$_REQUEST['COD_TEMPLATE_SMS']);

		if (isset($_POST['COD_PERSONA'])) {
			$Arr_COD_PERSONAS = $_POST['COD_PERSONA'];

			for ($i = 0; $i < count($Arr_COD_PERSONAS); $i++) {
				$cod_personas = $cod_personas . $Arr_COD_PERSONAS[$i] . ",";
			}

			$cod_personas = rtrim($cod_personas, ",");
			$cod_personas = ltrim($cod_personas, ",");
		} else {
			$cod_personas = "0";
		}

		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {


			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sql = "DELETE FROM SMS_CONTROLE_AUX WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha; ";
					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$sql = "CALL SP_RELAT_SMS_CONTROLE($cod_empresa, $cod_campanha, '$cod_personas', '$opcao')";

					// fnEscreve($sql);

					$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());

					$qrCont = mysqli_fetch_assoc($arrayQuery);

					$sql = "DELETE FROM SMS_CONTROLE_AUX WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha; ";

					$sql .= "INSERT INTO SMS_CONTROLE_AUX(
												COD_EMPRESA,
												COD_PERSONAS,
												COD_CAMPANHA,
												COD_TEMPLATE,
												TOTAL_PERSONAS,
												CLIENTES_UNICOS,
												CLIENTES_UNICOS_SMS,
												TOTAL_CLIENTE_SMS_NAO
											) VALUES(
												$cod_empresa,
												'$cod_personas',
												$cod_campanha,
												$cod_template,
												$qrCont[TOTAL_PERSONAS],
												$qrCont[CLIENTES_UNICOS],
												$qrCont[CLIENTES_UNICOS_SMS],
												$qrCont[TOTAL_CLIENTE_SMS_NAO]
											); ";
					// FNeSCREVE($sql);

					$sql .= "UPDATE SMS_CONTROLE 
								 SET COD_LISTA = (SELECT MAX(COD_LISTA) FROM SMS_PARAMETROS 
												  WHERE COD_EMPRESA = $cod_empresa 
												  AND COD_CAMPANHA = $cod_campanha)
								 WHERE COD_EMPRESA = $cod_empresa 
								 AND COD_CAMPANHA = $cod_campanha";

					mysqli_multi_query(connTemp($cod_empresa, ''), $sql);

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;

				case 'ENV':
					$msgRetorno = "Lista enviada com <strong>sucesso!</strong>";
					break;
				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;
			}
			$msgTipo = 'alert-success';
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_campanha = fnDecode($_GET['idc']);
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

$sql = "SELECT * FROM SMS_CONTROLE_AUX WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha";
$qrCont = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

if (isset($qrCont) && $qrCont != "") {

	$cod_persona = $qrCont['COD_PERSONAS'];
	$cod_template_email = $qrCont['COD_TEMPLATE'];
	$tot_personas = $qrCont['TOTAL_PERSONAS'];
	$clientes_unicos = $qrCont['CLIENTES_UNICOS'];
	$clientes_unicos_sms = $qrCont['CLIENTES_UNICOS_SMS'];
	$total_cliente_sms_nao = $qrCont['TOTAL_CLIENTE_SMS_NAO'];
	$cadastrar = "N";
	//$lista_envio = $clientes_unicos_sms-$total_cliente_sms_nao;
	//mudança da elina
	$lista_envio = $clientes_unicos - $total_cliente_sms_nao;
} else {
	$cod_persona = 0;
	$cod_template_email = 0;
	$tot_personas = "0";
	$clientes_unicos = "0";
	$clientes_unicos_sms = "0";
	$total_cliente_sms_nao = "0";
	$cadastrar = "S";
	$lista_envio = 0;
}

$sql = "SELECT MAX(DAT_ENVIO) AS DAT_ENVIO 
			FROM SMS_CONTROLE 
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_CAMPANHA = $cod_campanha";

$qrEnvio = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

if ($qrEnvio['DAT_ENVIO'] != '') {
	$dat_envio_global = $qrEnvio['DAT_ENVIO'];
} else {
	$dat_envio_global = "";
}

$sql2 = "SELECT DISTINCT ME.COD_TEMPLATE_SMS, ME.DAT_CADASTR, TE.NOM_TEMPLATE 
			 FROM MENSAGEM_SMS ME
			 LEFT JOIN TEMPLATE_SMS TE ON TE.COD_TEMPLATE = ME.COD_TEMPLATE_SMS
			 WHERE ME.COD_EMPRESA = $cod_empresa
			 AND ME.COD_CAMPANHA = $cod_campanha";

// fnEscreve($sql2);

$arrayTemplates = mysqli_query(connTemp($cod_empresa, ''), $sql2);

if (mysqli_num_rows($arrayTemplates) == 0) {
	$msgRetorno = "Nenhuma <b>mensagem</b> configurada na automação";
	$msgTipo = 'alert-warning';
	$hideBtn = 'display: none';
	$id_msg = '';
} else {
	$hideBtn = '';
}

$sql = "SELECT case 
         when   SUM(PM.QTD_SALDO_ATUAL) <=   SUM(PM.QTD_PRODUTO)
            then 
               SUM(PM.QTD_SALDO_ATUAL) 
            ELSE 
             SUM(PM.QTD_PRODUTO) - SUM(PM.QTD_SALDO_ATUAL) end QTD_PRODUTO ,
                 PM.TIP_LANCAMENTO,
                 CC.DES_CANALCOM 
          FROM PEDIDO_MARKA PM
          INNER JOIN PRODUTO_MARKA PRM ON PRM.COD_PRODUTO = PM.COD_PRODUTO
          INNER JOIN CANAL_COMUNICACAO CC ON CC.COD_CANALCOM = PRM.COD_CANALCOM 
          WHERE PM.COD_ORCAMENTO > 0 
          AND PM.PAG_CONFIRMACAO='S'
          AND  PM.TIP_LANCAMENTO='C'
          AND PM.COD_EMPRESA = $cod_empresa
          AND  PM.QTD_SALDO_ATUAL > 0
          GROUP BY CC.COD_TPCOM";

// fnEscreve($sql);

$arrayQuery = mysqli_query($connAdm->connAdm(), trim($sql));

while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {

	// fnEscreve($qrLista[QTD_PRODUTO]);

	$count++;

	switch ($qrLista['DES_CANALCOM']) {

		case 'SMS':
			if ($qrLista['TIP_LANCAMENTO'] == 'D') {
				$qtd_sms = $qtd_sms - $qrLista['QTD_PRODUTO'];
			} else {
				$qtd_sms = $qtd_sms + $qrLista['QTD_PRODUTO'];
			}
			break;

		case 'WhatsApp':
			if ($qrLista['TIP_LANCAMENTO'] == 'D') {
				$qtd_wpp = $qtd_wpp - $qrLista['QTD_PRODUTO'];
			} else {
				$qtd_wpp = $qtd_wpp + $qrLista['QTD_PRODUTO'];
			}
			break;

		default:
			if ($qrLista['TIP_LANCAMENTO'] == 'D') {
				$qtd_email = $qtd_email - $qrLista['QTD_PRODUTO'];
			} else {
				$qtd_email = $qtd_email + $qrLista['QTD_PRODUTO'];
			}
			break;
	}
}

// $qtd_sms = 99999999999999;

$msgTipoSaldo = 'alert-info';
$msgRetornoSaldo = "<span class='fal fa-exclamation-triangle f16'></span><strong> &nbsp;Atenção!</strong> Você possui <strong>" . fnValor($qtd_sms, 0) . "</strong> envios restantes. &nbsp;<a href='https://adm.bunker.mk/action.do?mod=" . fnEncode(1485) . "&id=" . fnEncode($cod_empresa) . "' target='_blank' style='color: #FFF; text-decoration: underline;'>Contratar mais envios</a>";

//fnescreve($cod_persona);

?>

<style>
	body {
		overflow-y: scroll;
		scrollbar-width: none;
		/* Firefox */
		-ms-overflow-style: none;
		/* IE 10+ */
	}

	body::-webkit-scrollbar {
		/* WebKit */
		width: 0;
		height: 0;
	}
</style>


<div class="row">

	<div class="col-md12 margin-bottom-30" id="corpo">
		<!-- Portlet -->
		<?php if ($popUp != "true") {  ?>
			<div class="portlet portlet-bordered">
			<?php } else { ?>
				<div class="portlet" style="padding: 0 20px 20px 20px;">
				<?php } ?>

				<?php if ($popUp != "true") {  ?>
					<div class="portlet-title">
						<div class="caption">
							<i class="glyphicon glyphicon-calendar"></i>
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
				<?php } ?>
				<div class="portlet-body">

					<div class="alert <?php echo $msgTipoSaldo; ?> top30 bottom30" role="alert" id="msgRetornoSaldo">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetornoSaldo; ?>
					</div>

					<?php if ($msgRetorno <> '') { ?>
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="<?= $msgRetorno ?>">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php } ?>

					<h4 style="margin: 0 0 5px 0;"><span class="bolder">Parâmetros de Geração da Lista</span></h4>
					<div class="push20"></div>


					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">



							<div class="row">

								<div class="col-sm-6">
									<div class="form-group">
										<label for="inputName" class="control-label">Personas para Geração da Lista</label>

										<select data-placeholder="Selecione a persona desejada" name="COD_PERSONA[]" id="COD_PERSONA" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1">
											<option value=""></option>
											<?php

											if (fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"]) == '1') {

												$andUnidade = "";
											} else {

												$andUnidade = "AND PERSONA.COD_UNIVEND IN($_SESSION[SYS_COD_UNIVEND])";
											}

											$sql = "SELECT IFNULL(PERSONAREGRA.COD_REGRA,0) AS TEM_REGRA, 
																			PERSONA.* 
																		 	FROM PERSONA 
																			LEFT JOIN PERSONAREGRA ON PERSONAREGRA.COD_PERSONA = PERSONA.COD_PERSONA
																		 	WHERE COD_EMPRESA = $cod_empresa 
																		 	$andUnidade
																		 	ORDER BY DES_PERSONA ";

											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());

											while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {

												if ($qrListaPersonas['LOG_ATIVO'] == "N") {
													$desabilitado = "disabled";
													$desabilitadoOnTxt = " (Off)";
												} else {
													$desabilitado = "";
													$desabilitadoOnTxt = "";
												}

												if ($qrListaPersonas['TEM_REGRA'] == "0") {
													$desabilitadoRg = " disabled";
													$desabilitadoRgTxt = " (s/ regra)";
												} else {
													$desabilitadoRg = "";
													$desabilitadoRgTxt = "";
												}

												echo "
																			  <option value='" . $qrListaPersonas['COD_PERSONA'] . "' " . $desabilitado . $desabilitadoRg . ">" . ucfirst($qrListaPersonas['DES_PERSONA']) . $desabilitadoRgTxt . $desabilitadoOnTxt . "</option> 
																			";
											}

											?>
										</select>

									</div>

								</div>

								<div class="col-sm-6">
									<div class="form-group">
										<label for="inputName" class="control-label">Template</label>


										<select data-placeholder="Selecione a template desejada" name="COD_TEMPLATE_SMS" id="COD_TEMPLATE_SMS" class="chosen-select-deselect" tabindex="1" required>
											<option value=""></option>
											<?php

											$sql = "SELECT DISTINCT ME.COD_TEMPLATE_SMS, TE.NOM_TEMPLATE 
																		FROM MENSAGEM_SMS ME
																		LEFT JOIN TEMPLATE_SMS TE ON TE.COD_TEMPLATE = ME.COD_TEMPLATE_SMS
																		WHERE ME.COD_EMPRESA = $cod_empresa
																		AND ME.COD_CAMPANHA = $cod_campanha";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
											while ($qrTemplate = mysqli_fetch_assoc($arrayQuery)) {

												echo "
																	  <option value='" . $qrTemplate['COD_TEMPLATE_SMS'] . "'>" . $qrTemplate['NOM_TEMPLATE'] . "</option> 
																	";
											}

											?>
										</select>
										<div class="help-block with-errors">Selecione a template</div>
									</div>
									<script>
										$("#COD_TEMPLATE_SMS").val("<?= $cod_template_email ?>").trigger("chosen:updated");
									</script>
								</div>

								<!-- <div class="col-sm-4">
													<div class="form-group">
														<label for="inputName" class="control-label">Emails Extras</label>
														<input type="text" class="form-control input-sm" name="DES_EMAILEX" id="DES_EMAILEX" maxlength="500" value="">
													</div>
													<div class="help-block with-errors">Separar múltiplos emails por ";"</div>
												</div> -->

							</div>

							<div class="push30"></div>

							<div class="flexrow">

								<div class="col text-center">
									<i class="fal fa-users fa-2x">&nbsp; </i><span class="f17" id="TOT_PERSONAS"><?= $tot_personas ?></span>
									<h5>Personas Selecionadas</h5>
								</div>

								<div class="col text-center">
									<i class="fal fa-user-tag fa-2x">&nbsp; </i><span class="f17" id="CLIENTES_UNICOS"><?= $clientes_unicos ?></span>
									<h5>Clientes Únicos</h5>
								</div>

								<div class="col text-center">
									<i class="fal fa-phone fa-2x">&nbsp; </i><span class="f17" id="CLIENTES_UNICOS_SMS"><?= $clientes_unicos_sms ?></span>
									<h5>Clientes Únicos Com Celular</h5>
								</div>

								<div class="col text-center">
									<i class="fal fa-phone-slash fa-2x">&nbsp; </i><span class="f17" id="TOTAL_CLIENTE_SMS_NAO"><?= $total_cliente_sms_nao ?></span>
									<h5>Clientes Opt Out</h5>
								</div>

								<div class="col text-center">
									<i class="fal fa-paper-plane fa-2x">&nbsp; </i><span class="f17" id="LISTA_ENVIO"><?= $lista_envio ?></span>
									<h5>Lista de Envio</h5>
								</div>

							</div>

							<!-- <div class="push10"></div>

											<div class="row">
												
												<div class="col-md-3">
													<div class="form-group">
														<label for="inputName" class="control-label required">Manter Pessoas em Cópia</label>
														<input type="text" class="form-control input-sm" name="DES_PESSOAS" id="DES_PESSOAS" value="<?php echo "" ?>">
													</div>														
												</div>

											</div> -->

							<div class="push10"></div>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12" style="<?= $hideBtn ?>">
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Gerar lista de aprovação</button>
								<?php

								if ($cadastrar == 'N') {

									if ($dat_envio_global == "" && $qtd_sms >= $lista_envio) {

										if ($lista_envio <= 10) {

								?>

											<a href="javascript:void(0);" name="ENV" id="ENV" class="btn btn-info getBtn pull-left" onclick="enviarAprovacao()"><i class="fa fa-paper-plane" aria-hidden="true"></i>&nbsp; Enviar lista de teste</a>

										<?php
										} else {

										?>
											<div class="col-md-6 pull-left text-left" style="padding-left: 0; padding-right: 0;">

												<span class="text-warning"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; Lista de contatos para envio de aprovação maior que o recomendado</a></span>
												<div class="push5"></div>
												<a href="javascript:void(0);" name="ENV" id="ENV" class="btn btn-info getBtn" onclick="confirmaAprovacao()"><i class="fa fa-paper-plane" aria-hidden="true"></i>&nbsp; Enviar lista de teste</a>

											</div>

										<?php
										}
									} else if ($qtd_sms < $lista_envio) {

										?>

										<!-- <a href="javascript:void(0);" class="btn btn-warning getBtn pull-left disabled"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; Saldo insuficiente para envio da lista</a> -->

										<a href="javascript:void(0);" class="btn btn-warning getBtn pull-left disabled"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; Saldo insuficiente para envio da lista</a>

									<?php

									} else {

									?>

										<a href="javascript:void(0);" class="btn btn-success getBtn pull-left disabled"><i class="fa fa-check" aria-hidden="true"></i>&nbsp; Lista de teste enviada</a>

								<?php

									}
								}

								?>

							</div>

							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
							<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?= $cod_campanha ?>">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

							<div class="push30"></div>

							<div id="relatorioAjax"></div>

							<?php

							$sql = "SELECT COD_CONTROLE FROM SMS_CONTROLE
													WHERE COD_EMPRESA = $cod_empresa
													AND COD_CAMPANHA = $cod_campanha";

							$num_clientes = mysqli_num_rows(mysqli_query(connTemp($cod_empresa, ''), $sql));

							if ($num_clientes != 0) {

							?>

								<div class="col-lg-12">

									<div class="no-more-tables">

										<table class="table table-bordered table-striped table-hover tableSorter">
											<thead>
												<tr>
													<!-- <th class="text-center { sorter: false }" width="40"><input type='checkbox' id="selectAll" onchange="$(this).closest('table').find('td input:checkbox').prop('checked', this.checked); attListaClientes(this);" checked></th> -->
													<th>Cliente</th>
													<th>Cel.</th>
													<th>Loja</th>
													<th>Dt. Envio</th>
													<!-- <th>Dt. Abertura</th> -->
													<th>Dt. Confirmação</th>
													<th class="{ sorter: false }"></th>
												</tr>
											</thead>
											<tbody id="relatorioConteudo">

												<?php

												$ARRAY_UNIDADE1 = array(
													'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
													'cod_empresa' => $cod_empresa,
													'conntadm' => $connAdm->connAdm(),
													'IN' => 'N',
													'nomecampo' => '',
													'conntemp' => '',
													'SQLIN' => ""
												);
												$ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);

												$sql = "SELECT EC.COD_CONTROLE,
																   EC.NOM_CLIENTE, 
																   EC.NUM_CELULAR,
																   EC.LOG_OK,
																   EC.DAT_OK,
																   EC.DAT_ENVIO,
																   CL.COD_UNIVEND, 
																   CL.COD_CLIENTE
															FROM SMS_CONTROLE EC 
															INNER JOIN CLIENTES CL ON CL.COD_CLIENTE = EC.COD_CLIENTE
															WHERE EC.COD_EMPRESA = $cod_empresa
															AND EC.LOG_SMS = 'S'
															AND EC.COD_CAMPANHA = $cod_campanha
															AND EC.COD_LISTA = (SELECT MAX(COD_LISTA) FROM SMS_PARAMETROS 
																				  WHERE COD_EMPRESA = $cod_empresa 
																				  AND COD_CAMPANHA = $cod_campanha)
															ORDER BY EC.NOM_CLIENTE
															LIMIT 50";

												// fnEscreve($sql);

												sleep(1);

												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());

												$count = 0;
												$countAprovado = 0;
												while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {

													$count++;
													$NOM_ARRAY_UNIDADE = (array_search($qrLista['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));

													if ($qrLista['LOG_OK'] == 'S') {
														$log_ok = "<a href='javascript:void(0);' class='btn btn-xs btn-success transparency'>Ok <span class='fa fa-check'></span></a>";
														$countAprovado++;
													} else {
														$log_ok = "<a href='javascript:void(0);' class='btn btn-xs btn-danger transparency' onclick='okCliente(\"" . fnEncode($qrLista['COD_CONTROLE']) . "\"," . $count . ")'>Ok <span class='fa fa-times'></span></a>";
													}

													if ($qrLista['DAT_OK'] != '') {
														$dat_ok = fnDataFull($qrLista['DAT_OK']);
													} else {
														$dat_ok = "";
													}

													if ($qrLista['DAT_ENVIO'] != '') {
														$dat_envio = fnDataFull($qrLista['DAT_ENVIO']);
													} else {
														$dat_envio = "";
													}

													if ($dat_envio_global != "") {
														$confirma = "<td class='text-center'>" . $log_ok . "</small></td>";
													} else {
														$confirma = "<td></td>";
													}

													echo "
															<tr id='" . fnEncode($qrLista['COD_CONTROLE']) . "'>
															  <!-- <td class='text-center'><input type='checkbox' id='check_$count' name='check_$count' onclick='retornaFormPersonas(" . $count . ")' checked value='" . $qrLista['COD_CLIENTE'] . "'>&nbsp;</td> -->
															  <td><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrLista['COD_CLIENTE']) . "' class='f14' target='_blank'>" . ucwords(strtolower(($qrLista['NOM_CLIENTE']))) . "</a></td>
															  <td><small>" . $qrLista['NUM_CELULAR'] . "</td>
															  <td><small>" . $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'] . "</small></td>
			  												  <td class='data-envio'><small>" . $dat_envio . "</small></td>
			  												  <td><small></small>" . $dat_ok . "</small></td>
															  " . $confirma . "
															</tr>
															<input type='hidden' id='ret_COD_CLIENTE_" . $count . "' value='" . $qrLista['COD_CLIENTE'] . "'>
														";
												}

												?>

											</tbody>
										</table>

										<?php
										if ($num_clientes > 50) { ?>
											<a class="btn btn-primary col-md-4 col-md-offset-4" type="button" id="loadMore">Carregar mais clientes da lista</a>
										<?php
										}
										?>

									</div>

								</div>

							<?php } ?>

							<input type="hidden" name="QTD_CLIENTES" id="QTD_CLIENTES" value="<?= $count ?>">

						</form>

					</div>

					<div class="col-md-12">
						<a href="javascript:void(0)" class="btn btn-primary" onclick="proximoPasso()">Próximo Passo&nbsp;&nbsp;<span class="fal fa-arrow-right"></span></a>
					</div>
					<div class="push100"></div>

				</div>
				</div>
				<!-- fim Portlet -->
			</div>

	</div>

	<div class="push20"></div>

	<script type="text/javascript">
		parent.$("#conteudoAba").css("height", $(".pagina").height() + "px");

		var aprovaConfig = "<?= $countAprovado ?>",
			qtdLista = "<?= $count ?>",
			msgBlock = "";

		$(function() {

			$('#selectAll').change(function() {
				alert('check');
			});

			var cont = 0;

			$('#loadMore').click(function() {

				cont += 50;

				$.ajax({
					type: "GET",
					url: "ajxAprovacaoSms_V2.php?opcao=loadMore&itens=" + cont + "&id=<?= fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_campanha) ?>",
					beforeSend: function() {
						$('#loadMore').text('Carregando...');
					},
					success: function(data) {
						if (cont >= "<?= $num_clientes ?>") {
							$('#loadMore').text('Todos os clientes já se encontam na lista');
							$('#loadMore').addClass('disabled');
						} else {
							$('#loadMore').text('Carregar mais clientes da lista');
						}
						$('#relatorioConteudo').append(data);

						parent.$("#conteudoAba").css("height", $(document).height() + "px");

						console.log(data);
					},
					error: function() {
						alert('Erro ao carregar...');
						console.log(data);
					}
				});
			});

			var cod_persona = '<?php echo $cod_persona; ?>';
			//alert(cod_persona);
			if (cod_persona != 0 && cod_persona != "") {
				//retorno combo multiplo - USUARIOS_ENV
				$("#formulario #COD_PERSONA").val('').trigger("chosen:updated");

				var sistemasUni = cod_persona;
				var sistemasUniArr = sistemasUni.split(',');
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
					$("#formulario #COD_PERSONA option[value=" + Number(sistemasUniArr[i]).toString() + "]").prop("selected", "true");
				}
				$("#formulario #COD_PERSONA").trigger('chosen:updated');

			}

		});

		function proximoPasso() {

			if (qtdLista > 0 && aprovaConfig > 0) {

				parent.$('#ATIVACAO').click();

			} else {

				if (qtdLista == 0) {
					msgBlock = "Nenhuma lista foi gerada,";
				} else {
					msgBlock = "Não há aprovação na lista,";
				}

				parent.$.alert({
					title: "Aviso",
					content: msgBlock + " e não será possível ativar a campanha. Deseja prosseguir?",
					type: 'orange',
					buttons: {
						"PROSSEGUIR": {
							btnClass: 'btn-primary',
							action: function() {
								parent.$('#ATIVACAO').click();
							}
						},
						"CANCELAR": {
							btnClass: 'btn-default',
							action: function() {

							}
						}
					},
					backgroundDismiss: true
				});
			}

		}

		function okCliente(cod_controle, count) {

			$.ajax({
				type: "POST",
				url: "ajxAprovacaoSms_V2.php?opcao=okCliente&id=<?= fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_campanha) ?>",
				data: {
					COD_CONTROLE: cod_controle,
					COUNT: count
				},
				beforeSend: function() {
					$('#' + cod_controle).html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					$('#' + cod_controle).html(data);
					aprovaConfig++;
					// console.log(data);
				},
				error: function() {
					alert('Erro ao carregar...');
					console.log(data);
				}
			});

		}

		function confirmaAprovacao() {
			parent.$.alert({
				title: "Aviso",
				content: "Sua lista possui mais que a quantidade recomendada de <b>10 contatos ou menos</b>. Deseja prosseguir?",
				type: 'orange',
				buttons: {
					"PROSSEGUIR": {
						btnClass: 'btn-primary',
						action: function() {
							enviarAprovacao();
						}
					},
					"CANCELAR": {
						btnClass: 'btn-default',
						action: function() {

						}
					}
				},
				backgroundDismiss: true
			});
		}

		function enviarAprovacao() {

			$.ajax({
				type: "POST",
				url: "ajxEnvioTesteSms_V2.php?id=<?= fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_campanha) ?>",
				data: $("#formulario").serialize(),
				beforeSend: function() {
					$('#ENV').text('Enviando lista de teste...');
					$('#ENV').attr('disabled');
				},
				success: function(data) {
					//console.log("====>",data);
					if (data.trim() != 'erro' && data.trim() != 'Erro no upload da lista') {
						$('#ENV').html("<span class='fa fa-check'></span>&nbsp;Lista de teste enviada").removeAttr('disabled').removeClass('btn-info').addClass('btn-success').addClass('disabled');
						$('#ENV').attr('disabled');
						$('.data-envio').text(data);
						if (data !== "Erro interno") {
							// location.reload();
						}
					} else {
						$('#ENV').html("<span class='fa fa-times'></span>&nbsp;" + data).removeAttr('disabled').removeClass('btn-primary').addClass('btn-danger');
					}
					// $('#relatorioAjax').html(data);
					console.log(data);
				},
				error: function() {
					alert('Erro ao carregar...');
					// console.log(data);
				}
			});

		}
	</script>