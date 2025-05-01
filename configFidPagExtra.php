<?php

if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

//echo fnDebug('true');

$hashLocal = mt_rand();

$cod_geral = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_geral = fnLimpaCampoZero($_POST['COD_GERAL']);
		$cod_campanha = fnLimpaCampoZero($_POST['COD_CAMPANHA']);
		$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
		$cod_produto = 0;
		$tip_faixas = "PAG";
		$val_faixini = 0;
		$val_faixfim = 0;
		$qtd_faixext = $_POST['QTD_FAIXEXT'];
		$tip_faixext = $_POST['TIP_FAIXEXT'];
		$qtd_faixlim = 0;
		$cod_formapa = $_POST['COD_FORMAPA'];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			//busca dados da regra extra (tela) 
			$sql = "SELECT COD_EXTRA FROM VANTAGEMEXTRA where COD_CAMPANHA = '" . $cod_campanha . "' ";
			//fnEscreve($sql);

			$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
			$tem_extra = mysqli_num_rows($arrayQuery);

			if ($tem_extra == 0) {

				$sqlExtra = "INSERT INTO VANTAGEMEXTRA(
												COD_CAMPANHA, 
												COD_USUCADA, 
												COD_EMPRESA
											 ) VALUES(
											 	$cod_campanha,
											 	$cod_usucada,
											 	$cod_empresa
											 )";

				mysqli_query(connTemp($cod_empresa, ''), $sqlExtra);
			}

			//verifica se já existe	
			$sql3 = "select count(*) as JATEMFAIXA from VANTAGEMEXTRAFAIXA where COD_CAMPANHA = '" . $cod_campanha . "' AND COD_FORMAPA = '" . $cod_formapa . "' AND TIP_FAIXAS = 'PAG' ";
			$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql3);
			$qrBuscaConfere = mysqli_fetch_assoc($arrayQuery);
			$jatemfaixa = $qrBuscaConfere['JATEMFAIXA'];
			if ($opcao != "EXC" && $jatemfaixa > 1) {
				$opcao = "ALT";
			}

			$sql = "CALL SP_ALTERA_VANTAGEMEXTRAFAIXA (
				 '" . $cod_geral . "', 
				 '" . $cod_campanha . "', 
				 '" . $cod_empresa . "', 
				 '0', 
				 '" . $tip_faixas . "', 
				 '" . fnValorSql($val_faixini) . "',
				 '" . fnValorSql($val_faixfim) . "',
				 '" . fnValorSql($qtd_faixext) . "',
				 '" . $tip_faixext . "',
				 '" . $qtd_faixlim . "',
				 '" . $cod_produto . "',
				 '" . $cod_formapa . "',
				 '" . $cod_usucada . "', 
				 '1', 
				 '0', 
				 '" . $opcao . "'    
				) ";

			//echo $sql;

			// fnEscreve($sql);
			// fnTesteSql(connTemp($cod_empresa, ''), trim($sql));
			mysqli_query(connTemp($cod_empresa, ''), trim($sql));

			//busca quantidade total de itens	
			$sql2 = "select count(*) as TEMFAIXA from VANTAGEMEXTRAFAIXA where COD_CAMPANHA = '" . $cod_campanha . "' AND TIP_FAIXAS = 'PAG' ";
			//fnEscreve($sql2);

			//fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql2);
			$qrBuscaTotalExtra = mysqli_fetch_assoc($arrayQuery);
			$temfaixa = $qrBuscaTotalExtra['TEMFAIXA'];

			//if ($temfaixa > 0) {					

			$sql3 = "update VANTAGEMEXTRA set QTD_TOTFPAGA = " . $temfaixa . " where cod_campanha = " . $cod_campanha . " ";
			$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql3);

			//atualiza lista iframe				
?>
			<script>
				try {
					parent.$('#REFRESH_PAG').val("S");
				} catch (err) {}
			</script>
<?php

			//}

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

//busca dados da campanha
$cod_campanha = fnDecode($_GET['idc']);
$cod_empresa = fnDecode($_GET['id']);
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

//fnMostraForm();

?>


<?php if ($popUp != "true") {  ?>
	<div class="push30"></div>
<?php } ?>

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
							<i class="glyphicon glyphicon-calendar"></i>
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

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend>

								<div class="row">

									<div class="col-md-7">
										<label for="inputName" class="control-label required">Forma de Pagamento </label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary addBox" data-url="action.php?mod=<?php echo fnEncode(1095) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Vantagens Extras - Busca Formas de Pagamento"><i class="fa fa-search" aria-hidden="true"></i></a>
											</span>
											<input type="text" name="DES_FORMAPA" id="DES_FORMAPA" class="form-control input-sm leituraOff" readonly="readonly" placeholder="Procurar forma de pagamento...">
											<input type="hidden" name="COD_FORMAPA" id="COD_FORMAPA" value="">
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Qtd. Extra</label>
											<input type="text" class="form-control input-sm text-center money" name="QTD_FAIXEXT" id="QTD_FAIXEXT" maxlength="20" value="" required>
											<span class="help-block">valor</span>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Ganha</label>
											<select data-placeholder="Selecione um estado civil" name="TIP_FAIXEXT" id="TIP_FAIXEXT" class="chosen-select-deselect requiredChk" required>
												<option value="">...</option>
												<option value="PCT">Percentual sobre a venda</option>
												<option value="ABS"><?php echo $nom_tpcampa; ?></option>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

							</div>

							<input type="hidden" name="COD_GERAL" id="COD_GERAL" value="<?php echo $cod_geral; ?>">
							<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?php echo $cod_campanha; ?>">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

							<div class="push5"></div>

						</form>

						<!-- modal -->
						<div class="modal fade" id="popModalAux" tabindex='-1'>
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
												<th>Código</th>
												<th>Campanha</th>
												<th>Forma de Pagamento</th>
												<th>Ganha</th>
											</tr>
										</thead>
										<tbody>

											<?php

											$sql = "select A.*,B.DES_CAMPANHA as NOM_CAMPANHA,FP.DES_FORMAPA, IFNULL(FP.COD_FORMAPA,0) as COD_FORMAPA from VANTAGEMEXTRAFAIXA A
															LEFT join CAMPANHA B on A.COD_CAMPANHA= B.COD_CAMPANHA
															LEFT join FORMAPAGAMENTO FP on A.COD_FORMAPA = FP.COD_FORMAPA
															where A.COD_CAMPANHA = '" . $cod_campanha . "' AND A.TIP_FAIXAS = 'PAG' order by FP.DES_FORMAPA ";

											//fnEscreve($sql);
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$count = 0;

											while ($qrBuscaCampanhaExtra = mysqli_fetch_assoc($arrayQuery)) {
												$count++;

												if ($qrBuscaCampanhaExtra['TIP_FAIXEXT'] == "ABS") {
													$tipoGanho = $nom_tpcampa;
												} else {
													$tipoGanho = "%";
												}

												echo "
															<tr>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
															  <td>" . $qrBuscaCampanhaExtra['COD_VANTAGEMFAIXA'] . "</td>
															  <td>" . $qrBuscaCampanhaExtra['NOM_CAMPANHA'] . "</td>
															  <td>" . $qrBuscaCampanhaExtra['DES_FORMAPA'] . "</td>
															  <td>" . number_format($qrBuscaCampanhaExtra['QTD_FAIXEXT'], 2, ",", ".") . " " . $tipoGanho . "</td>															
															</tr>
															<input type='hidden' id='ret_COD_GERAL_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_VANTAGEMFAIXA'] . "'>
															<input type='hidden' id='ret_VAL_FAIXINI_" . $count . "' value='" . number_format($qrBuscaCampanhaExtra['VAL_FAIXINI'], 2, ",", ".") . "'>
															<input type='hidden' id='ret_VAL_FAIXFIM_" . $count . "' value='" . number_format($qrBuscaCampanhaExtra['VAL_FAIXFIM'], 2, ",", ".") . "'>
															<input type='hidden' id='ret_QTD_FAIXEXT_" . $count . "' value='" . number_format($qrBuscaCampanhaExtra['QTD_FAIXEXT'], 2, ",", ".") . "'>
															<input type='hidden' id='ret_TIP_FAIXEXT_" . $count . "' value='" . $qrBuscaCampanhaExtra['TIP_FAIXEXT'] . "'>
															<input type='hidden' id='ret_QTD_FAIXLIM_" . $count . "' value='" . $qrBuscaCampanhaExtra['QTD_FAIXLIM'] . "'>
															<input type='hidden' id='ret_COD_PRODUTO_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_PRODUTO'] . "'>
															<input type='hidden' id='ret_COD_FORMAPA_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_FORMAPA'] . "'>
															<input type='hidden' id='ret_DES_FORMAPA_" . $count . "' value='" . $qrBuscaCampanhaExtra['DES_FORMAPA'] . "'>
															";
											}

											?>

										</tbody>
									</table>

								</form>

							</div>

						</div>

						<div class="push"></div>

					</div>

				</div>
				</div>
				<!-- fim Portlet -->
			</div>

	</div>

	<div class="push20"></div>

	<script>
		$(document).ready(function() {

			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

		});


		function retornaForm(index) {

			$("#formulario #COD_GERAL").val($("#ret_COD_GERAL_" + index).val());
			$("#formulario #VAL_FAIXINI").val($("#ret_VAL_FAIXINI_" + index).val());
			$("#formulario #VAL_FAIXFIM").val($("#ret_VAL_FAIXFIM_" + index).val());
			$("#formulario #TIP_FAIXEXT").val($("#ret_TIP_FAIXEXT_" + index).val()).trigger("chosen:updated");
			$("#formulario #QTD_FAIXEXT").val($("#ret_QTD_FAIXEXT_" + index).val());
			$("#formulario #QTD_FAIXLIM").val($("#ret_QTD_FAIXLIM_" + index).val());
			$("#formulario #COD_PRODUTO").val($("#ret_COD_PRODUTO_" + index).val());
			$("#formulario #DES_PRODUTO").val($("#ret_DES_PRODUTO_" + index).val());
			$("#formulario #COD_FORMAPA").val($("#ret_COD_FORMAPA_" + index).val());
			$("#formulario #DES_FORMAPA").val($("#ret_DES_FORMAPA_" + index).val());

			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}
	</script>