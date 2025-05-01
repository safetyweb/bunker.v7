<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$cod_campaprod = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_campaprod = fnLimpaCampoZero($_POST['COD_CAMPAPROD']);
		$cod_campanha = fnLimpaCampoZero($_POST['COD_CAMPANHA']);
		$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);

		$cod_categor = fnLimpaCampoZero($_REQUEST['COD_CATEGOR']);
		$cod_subcate = fnLimpaCampoZero($_REQUEST['COD_SUBCATE']);
		$cod_fornecedor = fnLimpaCampoZero($_REQUEST['COD_FORNECEDOR']);
		$cod_vantage = fnLimpaCampoZero($_REQUEST['COD_VANTAGE']);
		$tip_calculo = fnLimpaCampoZero($_REQUEST['TIP_CALCULO']);


		$val_pontuacao = $_REQUEST['VAL_PONTUACAO'];
		$val_pontoext = $_REQUEST['VAL_PONTOEXT'];
		$tip_pontuacao = $_REQUEST['TIP_PONTUACAO'];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];


		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_CAMPANHAPRODUTO (
				 '" . $cod_campaprod . "', 
				 '" . $cod_campanha . "', 
				 '" . $cod_empresa . "', 
				 '" . $cod_categor . "', 
				 '" . $cod_subcate . "', 
				 '" . $cod_fornecedor . "', 
				 '" . fnValorSql($val_pontuacao) . "',
				 '" . fnValorSql($val_pontoext) . "',
				 '" . $tip_pontuacao . "',
				 '" . $cod_usucada . "',
				 '" . $cod_vantage . "',
                 '" . $tip_calculo . "',
				 '" . $opcao . "'    
				) ";

			//echo $sql;				
			//fntesteSql(connTemp($cod_empresa,''),trim($sql));
			//exit();

			mysqli_query(connTemp($cod_empresa, ''), trim($sql));


			// //adicionado por Lucas 31/05 chamado 6399
			//Removido solicitado por josé 14/11/2024

			// $sqlUp = "SELECT COD_CAMPAPROD
			// 	from CAMPANHAPRODUTO
			// 	where CAMPANHAPRODUTO.COD_CAMPANHA = '".$cod_campanha."' AND COD_EXCLUSAO = 0";													

			// 	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlUp) or die(mysqli_error());
			// 	$numRows = mysqli_num_rows($arrayQuery);

			// if($numRows != "" && $numRows != 0){
			// 	$sqlCamp = "SELECT LOG_PRODUTO FROM CAMPANHAREGRA WHERE COD_CAMPANHA=$cod_campanha";
			// 	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlCamp) or die(mysqli_error());
			// 	$qrBusca = mysqli_fetch_assoc($arrayQuery);
			// 	$log_produto = $qrBusca['LOG_PRODUTO'];


			// 	if($log_produto == 'N'){

			// 		$update = "UPDATE CAMPANHAREGRA SET LOG_PRODUTO = 'S' WHERE COD_CAMPANHA =$cod_campanha";
			// 		mysqli_query(connTemp($cod_empresa, ''),$update);
			// 		//fnEscreve($update);
			// 	}
			// }
			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;
				case 'ALT':
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
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
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];

		//liberação das abas
		$abaPersona	= "S";
		$abaVantagem = "S";
		$abaRegras = "S";
		$abaComunica = "S";
		$abaAtivacao = "N";
		$abaResultado = "N";

		$abaPersonaComp = "active ";
		$abaCampanhaComp = "active";
		$abaVantagemComp = "active ";
		$abaRegrasComp = "completed ";
		$abaComunicaComp = "";
		$abaAtivacaoComp = "";
		$abaResultadoComp = "";
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//busca dados da campanha
$cod_campanha = fnDecode($_GET['idc']);
$sql = "SELECT * FROM CAMPANHA where COD_CAMPANHA = '" . $cod_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$log_ativo = $qrBuscaCampanha['LOG_ATIVO'];
	$des_campanha = $qrBuscaCampanha['DES_CAMPANHA'];
	$abr_campanha = $qrBuscaCampanha['ABR_CAMPANHA'];
	$des_icone = $qrBuscaCampanha['DES_ICONE'];
	$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];
	$log_realtime = $qrBuscaCampanha['LOG_REALTIME'];
}

//busca dados do tipo da campanha
$sql = "SELECT * FROM TIPOCAMPANHA where COD_TPCAMPA = '" . $tip_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$nom_tpcampa = $qrBuscaTpCampanha['NOM_TPCAMPA'];
	$abv_tpcampa = $qrBuscaTpCampanha['ABV_TPCAMPA'];
	$des_iconecp = $qrBuscaTpCampanha['DES_ICONE'];
	$label_1 = $qrBuscaTpCampanha['LABEL_1'];
	$label_2 = $qrBuscaTpCampanha['LABEL_2'];
	$label_3 = $qrBuscaTpCampanha['LABEL_3'];
	$label_4 = $qrBuscaTpCampanha['LABEL_4'];
	$label_5 = $qrBuscaTpCampanha['LABEL_5'];
}

//busca dados da regra 
$sql = "SELECT * FROM CAMPANHAREGRA where COD_CAMPANHA = '" . $cod_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$cod_persona = $qrBuscaTpCampanha['COD_PERSONA'];
	if (!empty($cod_persona)) {
		$tem_personas = "sim";
	} else {
		$tem_personas = "nao";
	}
	$pct_vantagem = $qrBuscaTpCampanha['PCT_VANTAGEM'];
	$qtd_vantagem = $qrBuscaTpCampanha['QTD_VANTAGEM'];
	$qtd_resultado = $qrBuscaTpCampanha['QTD_RESULTADO'];
	$nom_vantagem = $qrBuscaTpCampanha['NOM_VANTAGE'];
	$num_pessoas = $qrBuscaTpCampanha['NUM_PESSOAS'];
	$cod_vantage = $qrBuscaTpCampanha['COD_VANTAGE'];
} else {

	$cod_persona = 0;
	$pct_vantagem = "";
	$qtd_vantagem = "";
	$qtd_vantagem = "";
	$nom_vantagem = "";
	$num_pessoas = 0;
	$cod_vantage = 0;
}



//fnMostraForm();

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<?php if ($popUp != "true") {  ?>
			<div class="portlet portlet-bordered">
			<?php } else { ?>
				<div class="portlet" style="padding: 0 20px 20px 20px;">
				<?php } ?>

				<?php if ($popUp != "true") {  ?>
					<div class="portlet-title">
						<div class="caption">
							<i class="fal fa-terminal"></i>
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
				<?php } ?>

				<div class="portlet-body">

					<?php if ($msgRetorno <> '') { ?>
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php } ?>

					<?php $abaCampanhas = 1022;
					include "abasCampanhasConfig.php"; ?>

					<div class="push10"></div>

					<?php $abaCli = 1061;
					include "abasRegrasConfig.php"; ?>

					<div class="push30"></div>


					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PROGRAM" id="COD_PROGRAM" value="<?php echo $cod_campanha ?>">
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Campanha</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_CAMPANHA" id="DES_CAMPANHA" value="<?php echo $des_campanha ?>">
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Tipo do Programa</label>
											<div class="push10"></div>
											<span class="fa <?php echo $des_iconecp; ?>"></span> <b><?php echo $nom_tpcampa; ?> (<?php echo $nom_vantagem; ?>) </b>
										</div>
									</div>


									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Pessoas Atingidas</label>
											<div class="push10"></div>
											<span class="fa fa-users"></span>&nbsp; <?php echo number_format($num_pessoas, 0, ",", "."); ?>
										</div>
									</div>

								</div>

							</fieldset>

							<div class="push20"></div>

							<fieldset>
								<legend>Classificação</legend>

								<div class="row">

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Grupo do Produto</label>
											<select data-placeholder="Selecione o grupo" name="COD_CATEGOR" id="COD_CATEGOR" class="chosen-select-deselect">
												<option value="0">&nbsp;</option>
												<?php
												$sql = "select * from CATEGORIA where COD_EMPRESA = $cod_empresa AND COD_EXCLUSA is null order by DES_CATEGOR";
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

												while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery)) {
													echo "
																				  <option value='" . $qrListaCategoria['COD_CATEGOR'] . "'>" . $qrListaCategoria['COD_CATEGOR'] . " - " . $qrListaCategoria['DES_CATEGOR'] . "</option> 
																				";
												}
												?>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Sub Grupo do Produto</label>
											<div id="divId_sub">
												<select data-placeholder="Selecione o sub grupo" name="COD_SUBCATE" id="COD_SUBCATE" class="chosen-select-deselect">
													<option value="0">&nbsp;</option>
												</select>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>


									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Fornecedor</label>
											<select data-placeholder="Selecione o grupo" name="COD_FORNECEDOR" id="COD_FORNECEDOR" class="chosen-select-deselect">
												<option value="0">&nbsp;</option>
												<?php
												$sql = "select * from FORNECEDORMRKA where COD_EMPRESA = $cod_empresa order by NOM_FORNECEDOR";
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

												while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery)) {
													echo "
																				  <option value='" . $qrListaCategoria['COD_FORNECEDOR'] . "'>" . $qrListaCategoria['NOM_FORNECEDOR'] . "</option> 
																				";
												}
												?>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required"><?php echo $nom_tpcampa; ?> Normais</label>
											<input type="text" class="form-control input-sm text-center money" name="VAL_PONTUACAO" id="VAL_PONTUACAO" maxlength="20" value="" required>
											<span class="help-block">valor</span>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label"><?php echo $nom_tpcampa; ?> Extra</label>
											<input type="text" class="form-control input-sm text-center money" name="VAL_PONTOEXT" id="VAL_PONTOEXT" maxlength="20" value="">
											<span class="help-block">valor</span>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Tipo do Ganho</label>
											<select data-placeholder="Selecione um tipo de ganho" name="TIP_PONTUACAO" id="TIP_PONTUACAO" class="chosen-select-deselect requiredChk" onchange="escondeCampo(this,'S',0)" required>
												<option value="">...</option>
												<option value="PCT">Percentual</option>
												<option value="ABS"><?php echo $nom_tpcampa; ?> (absoluto)</option>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3" id="PERCENTUAL" hidden>
										<div class="form-group">
											<label for="inputName" class="control-label required">Tipo do Percentual</label>
											<select data-placeholder="Selecione um tipo de percentual" name="TIP_CALCULO" id="TIP_CALCULO" class="chosen-select-deselect requiredChk" required>
												<option value="">...</option>
												<option value="1">Sobre valor geral (do produto)</option>
												<option value="2">Sobre valor líquido (do produto)</option>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<?php
									//se bloco de pontos
									if ($tip_campanha == 12) {
									?>

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label required">Valor de Referência</label>
												<select data-placeholder="Selecione o tipo da vantagem" name="COD_VANTAGE" id="COD_VANTAGE" class="chosen-select-deselect calcula requiredChk" required>
													<option value="">&nbsp;</option>
													<?php
													$sql = "select  COD_VANTAGE, DES_VANTAGE, LOG_ATIVO from tipovantagem ";
													$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

													while ($qrListaVantagem = mysqli_fetch_assoc($arrayQuery)) {
														if ($qrListaVantagem['LOG_ATIVO'] == "S") {
															$checado = " ";
														} else {
															$checado = "disabled";
														}
														echo "
																				  <option value='" . $qrListaVantagem['COD_VANTAGE'] . "' " . $checado . ">" . $qrListaVantagem['DES_VANTAGE'] . "</option> 
																				";
													}
													?>
												</select>
												<script>
													$("#formulario #COD_VANTAGE").val("<?php echo $cod_vantage; ?>").trigger("chosen:updated");
												</script>
												<div class="help-block with-errors"></div>
											</div>
										</div>

									<?php
									} else {
									?>
										<input type="hidden" class="form-control input-sm" name="COD_VANTAGE" id="COD_VANTAGE" maxlength="20" value="">
									<?php
									}
									?>

								</div>

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

							</div>

							<input type="hidden" name="COD_CAMPAPROD" id="COD_CAMPAPROD" value="">
							<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?php echo $cod_campanha; ?>">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

							<div class="push5"></div>

						</form>


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

						<div class="push50"></div>

						<div id="div_Ordena"></div>

						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista">

									<table class="table table-bordered table-striped table-hover table-sortable">
										<thead>
											<tr>
												<th width="40"></th>
												<th>Grupo</th>
												<th>Sub Grupo</th>
												<th>Fornecedor</th>
												<th><?php echo $nom_tpcampa; ?> Normais</th>
												<th><?php echo $nom_tpcampa; ?> Extras</th>
												<th>Ganho</th>
											</tr>
										</thead>
										<tbody>

											<?php
											$sql = "select CATEGORIA.DES_CATEGOR, CATEGORIA.COD_CATEGOR,SUBCATEGORIA.DES_SUBCATE, FORNECEDORMRKA.NOM_FORNECEDOR,CAMPANHAPRODUTO.* 
													from CAMPANHAPRODUTO
													LEFT JOIN CATEGORIA  ON CATEGORIA.COD_CATEGOR=CAMPANHAPRODUTO.COD_CATEGOR
													LEFT JOIN SUBCATEGORIA  ON SUBCATEGORIA.COD_SUBCATE=CAMPANHAPRODUTO.COD_SUBCATE
													LEFT JOIN FORNECEDORMRKA  ON FORNECEDORMRKA.COD_FORNECEDOR=CAMPANHAPRODUTO.COD_FORNECEDOR
													where CAMPANHAPRODUTO.COD_CAMPANHA = '" . $cod_campanha . "' AND COD_EXCLUSAO = 0 ORDER BY DES_CATEGOR, DES_SUBCATE, NOM_FORNECEDOR  												
													";

											//fnEscreve($sql);
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$count = 0;

											while ($qrBuscaCampanhaExtra = mysqli_fetch_assoc($arrayQuery)) {
												$count++;

												if ($qrBuscaCampanhaExtra['TIP_PONTUACAO'] == "ABS") {
													$tipoGanho = $nom_tpcampa;
												} else {
													$tipoGanho = "Percentual";
												}

												if ($qrBuscaCampanhaExtra['COD_VANTAGE'] == 1) {
													$tipoVantagem = "<span class='f12'><b> por R$ </b></span>";
												}
												if ($qrBuscaCampanhaExtra['COD_VANTAGE'] == 3) {
													$tipoVantagem = "<span class='f12'><b> por Qtd. </b></span>";
												}

												echo "
															<tr>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
															  <td>" . $qrBuscaCampanhaExtra['COD_CATEGOR'] . " - " . $qrBuscaCampanhaExtra['DES_CATEGOR'] . "</td>
															  <td>" . $qrBuscaCampanhaExtra['DES_SUBCATE'] . "</td>
															  <td>" . $qrBuscaCampanhaExtra['NOM_FORNECEDOR'] . "</td>
															  <td>" . number_format($qrBuscaCampanhaExtra['VAL_PONTUACAO'], 2, ",", ".") . "</td>
															  <td>" . number_format($qrBuscaCampanhaExtra['VAL_PONTOEXT'], 2, ",", ".") . "</td>
															  <td>" . $tipoGanho . $tipoVantagem . "</td>
															</tr>
															<input type='hidden' id='ret_COD_CAMPAPROD_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_CAMPAPROD'] . "'>
															<input type='hidden' id='ret_COD_CATEGOR_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_CATEGOR'] . "'>
															<input type='hidden' id='ret_COD_SUBCATE_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_SUBCATE'] . "'>
															<input type='hidden' id='ret_COD_FORNECEDOR_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_FORNECEDOR'] . "'>
															<input type='hidden' id='ret_VAL_PONTUACAO_" . $count . "' value='" . number_format($qrBuscaCampanhaExtra['VAL_PONTUACAO'], 2, ",", ".") . "'>
															<input type='hidden' id='ret_VAL_PONTOEXT_" . $count . "' value='" . number_format($qrBuscaCampanhaExtra['VAL_PONTOEXT'], 2, ",", ".") . "'>
															<input type='hidden' id='ret_TIP_PONTUACAO_" . $count . "' value='" . $qrBuscaCampanhaExtra['TIP_PONTUACAO'] . "'>
															<input type='hidden' id='ret_TIP_CALCULO_" . $count . "' value='" . $qrBuscaCampanhaExtra['TIP_CALCULO'] . "'>
															<input type='hidden' id='ret_COD_VANTAGE_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_VANTAGE'] . "'>
															";
											}

											?>

										</tbody>
									</table>

								</form>

							</div>

						</div>

						<div class="push30"></div>

					</div>

				</div>
				</div>
				<!-- fim Portlet -->
			</div>

	</div>

	<div class="push20"></div>

	<script>
		$(document).ready(function() {

			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

		});

		// ajax
		$("#COD_CATEGOR").change(function() {
			var codBusca = $("#COD_CATEGOR").val();
			var codBusca3 = $("#COD_EMPRESA").val();
			buscaSubCat(codBusca, 0, codBusca3);
		});

		function buscaSubCat(idCat, idSub, idEmp) {
			$.ajax({
				type: "GET",
				url: "ajxBuscaSubGrupo.php",
				data: {
					ajx1: idCat,
					ajx2: idSub,
					ajx3: idEmp
				},
				beforeSend: function() {
					$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					$("#divId_sub").html(data);
				},
				error: function() {
					$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});
		}

		function escondeCampo(el, vldt, val) {

			let tipo = $(el).val(),
				percentual = $("#PERCENTUAL"),
				campo = $("#TIP_CALCULO"),
				req = false;

			percentual.fadeOut('fast');

			if (tipo == "PCT") {
				req = true;
				percentual.fadeIn('fast');
			}

			campo.prop('required', req);

			if (vldt == 'S') {
				$('#formulario').validator('validate');
			}

			if (val != 0) {
				campo.val(val).trigger("chosen:updated");
			}

		}

		function retornaForm(index) {


			$("#formulario #COD_CAMPAPROD").val($("#ret_COD_CAMPAPROD_" + index).val()).trigger("chosen:updated");
			$("#formulario #COD_CATEGOR").val($("#ret_COD_CATEGOR_" + index).val()).trigger("chosen:updated");

			var codCat = $("#ret_COD_CATEGOR_" + index).val();
			var codSub = $("#ret_COD_SUBCATE_" + index).val();
			buscaSubCat(codCat, codSub, <?php echo $cod_empresa; ?>);

			$("#formulario #COD_FORNECEDOR").val($("#ret_COD_FORNECEDOR_" + index).val()).trigger("chosen:updated");
			$("#formulario #VAL_PONTUACAO").val($("#ret_VAL_PONTUACAO_" + index).val());
			$("#formulario #VAL_PONTOEXT").val($("#ret_VAL_PONTOEXT_" + index).val());
			$("#formulario #TIP_PONTUACAO").val($("#ret_TIP_PONTUACAO_" + index).val()).trigger("chosen:updated");
			$("#formulario #COD_VANTAGE").val($("#ret_COD_VANTAGE_" + index).val()).trigger("chosen:updated");

			escondeCampo("#TIP_PONTUACAO", "N", $("#ret_TIP_CALCULO_" + index).val());

			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}
	</script>