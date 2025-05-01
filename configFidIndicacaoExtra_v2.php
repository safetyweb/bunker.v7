<?php

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

		$cod_controle = fnLimpaCampoZero($_POST['COD_CONTROLE']);
		$cod_campanha = fnLimpaCampoZero($_POST['COD_CAMPANHA']);
		$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
		$cod_usoind = fnLimpaCampoZero($_POST['COD_USOIND']);
		$cod_produto = fnLimpaCampoZero($_POST['COD_PRODUTO']);
		$qtd_extraind = fnLimpaCampo($_POST['QTD_EXTRAIND']);
		$tip_extraind = fnLimpaCampo($_POST['TIP_EXTRAIND']);
		$qtd_diasind = fnLimpaCampo($_POST['QTD_DIASIND']);
		$tip_cliente = fnLimpaCampo($_POST['TIP_CLIENTE']);
		$limit_uso = fnLimpaCampoZero($_POST['LIMIT_USO']);
		$faixa_ini = fnLimpaCampoZero(fnValorSql($_POST['FAIXA_INI']));
		$faixa_fim = fnLimpaCampoZero(fnValorSql($_POST['FAIXA_FIM']));
		$tip_campanha = fnLimpaCampoZero($_POST['TIP_CAMPANHA']);

		//fnEscreve($limit_uso);

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($faixa_fim > 0) {
			$log_faixa = 'S';
		} else {
			$log_faixa = 'N';
		}

		if ($opcao != '') {

			//busca dados da regra extra (tela) 
			$sql = "SELECT COD_EXTRA FROM VANTAGEMEXTRA where COD_CAMPANHA = '" . $cod_campanha . "' ";


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

			$sql = "CALL SP_ALTERA_INDICA_CLIENTE_CAMPANHA (
				'" . $cod_controle . "', 
				'" . $cod_empresa . "', 
				'" . $cod_campanha . "', 
				'" . fnValorSql($qtd_extraind) . "',
				'" . $tip_extraind . "', 
				'" . $qtd_diasind . "', 
				'" . $cod_usucada . "', 
				'" . $cod_usoind . "', 
				'" . $tip_cliente . "', 
				'" . $cod_produto . "', 
				'" . $limit_uso . "', 
				'" . $faixa_ini . "', 
				'" . $faixa_fim . "', 
				'" . $tip_campanha . "',
				'" . $log_faixa . "',
				'" . $opcao . "'    
			) ";

			$arrayFiltro = mysqli_query(connTemp($cod_empresa, ''), trim($sql)) or die(mysqli_error());

			if (!$arrayFiltro) {
				$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
			} else {


				$sql2 = "select count(*) as TEMFAIXA from INDICA_CLIENTE_CAMPANHA where COD_EMPRESA = '" . $cod_empresa . "' and  COD_CAMPANHA = '" . $cod_campanha . "'  ";
				//fnEscreve($sql2);

				$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql2) or die(mysqli_error());
				$qrBuscaTotalExtra = mysqli_fetch_assoc($arrayQuery);
				$temfaixa = $qrBuscaTotalExtra['TEMFAIXA'];
				//fnEscreve($temfaixa);

				$sql3 = "update VANTAGEMEXTRA set QTD_INDICA = " . $temfaixa . " where cod_campanha = " . $cod_campanha . " ";
				$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql3) or die(mysqli_error());

				//atualiza lista iframe				
?>
				<script>
					try {
						parent.$('#REFRESH_INDICA').val("S");
					} catch (err) {}
					//alert(parent.$('#REFRESH_CAT').val())
				</script>
<?php

			}

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
//
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$log_ativo = $qrBuscaCampanha['LOG_ATIVO'];
	$des_campanha = $qrBuscaCampanha['DES_CAMPANHA'];
	$abr_campanha = $qrBuscaCampanha['ABR_CAMPANHA'];
	$des_icone = $qrBuscaCampanha['DES_ICONE'];
	$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];
	$qtd_diasind = $qrBuscaCampanha['QTD_DIASIND'];
	$log_realtime = $qrBuscaCampanha['LOG_REALTIME'];
}

//busca dados do tipo da campanha
$sql = "SELECT * FROM TIPOCAMPANHA where COD_TPCAMPA = '" . $tip_campanha . "' ";
//
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
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
$sql = "SELECT NOM_VANTAGE FROM CAMPANHAREGRA where COD_CAMPANHA = '" . $cod_campanha . "' ";
//
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$nom_vantagem = $qrBuscaTpCampanha['NOM_VANTAGE'];
}

//BUSCA DADOS DA INDICAÇÃO
$sql = "SELECT * FROM INDICA_CLIENTE_CAMPANHA WHERE COD_CAMPANHA = '" . $cod_campanha . "' ";
//
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql)) or die(mysqli_error());
$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$cod_controle = $qrBuscaTpCampanha['COD_CONTROLE'];
	$qtd_extraind = $qrBuscaTpCampanha['QTD_EXTRAIND'];
	$tip_extraind = $qrBuscaTpCampanha['TIP_EXTRAIND'];
	$qtd_diasind = $qrBuscaTpCampanha['QTD_DIASIND'];
	$cod_usoind = $qrBuscaTpCampanha['COD_USOIND'];
} else {
	$cod_controle = 0;
	$cod_usoind = 0;
	$qtd_extraind = "";
	$tip_extraind = "";
	$qtd_diasind = "";
}

if ($tip_campanha == 22 || $tip_campanha == 23) {
	$desabilitaFaixa = "style='display: none;'";
} else {
	$desabilitaFaixa = "";
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
								<legend>Dados da Configuração da Indicação</legend>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Tipo de Cliente</label>
											<select data-placeholder="Selecione a vantagem extra" name="TIP_CLIENTE" id="TIP_CLIENTE" class="chosen-select-deselect" required>
												<option value=""></option>
												<option value="CLI">Indicador (cliente)</option>
												<option value="IND">Indicado (Novo Cadastro)</option>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Qtd. Extra</label>
											<input type="text" class="form-control input-sm text-center money" name="QTD_EXTRAIND" id="QTD_EXTRAIND" maxlength="10" value="" required>
											<span class="help-block">valor</span>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Tipo da Vantagem Extra</label>
											<select data-placeholder="Selecione a vantagem extra" name="TIP_EXTRAIND" id="TIP_EXTRAIND" class="chosen-select-deselect" required>
												<option value=""></option>
												<option value="CPC">Créditos para Próxima Compra</option>
												<option value="PRD">Produto</option>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Validade em Dias</label>
											<input type="text" class="form-control input-sm text-center" name="QTD_DIASIND" id="QTD_DIASIND" maxlength="20" value="" required>
											<span class="help-block">quantidade máxima</span>
										</div>
									</div>

									<div class="col-md-2 limit-uso" style='display: none;'>
										<div class="form-group">
											<label for="inputName" class="control-label">Limite de Uso</label>
											<input type="text" class="form-control input-sm text-center int" name="LIMIT_USO" id="LIMIT_USO" maxlength="20" value="">
											<span class="help-block"></span>
										</div>
									</div>

								</div>

								<h5 <?php echo $desabilitaFaixa ?>>Faixa de Valor</h5>

								<div class="row">

									<div class="col-md-6" <?= $desabilitaFaixa ?>>

										<div class="col-md-6" style="padding-left: 0;">
											<div class="form-group">
												<label for="inputName" class="control-label">Inicial</label>
												<input type="text" class="form-control text-center input-sm money" name="FAIXA_INI" id="FAIXA_INI" maxlength="12" value="">
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label for="inputName" class="control-label">Final</label>
												<input type="text" class="form-control text-center input-sm money" name="FAIXA_FIM" id="FAIXA_FIM" maxlength="12" value="">
												<div class="help-block with-errors"></div>
											</div>
										</div>

									</div>

									<div class="col-md-4 " id="divProduto" style="display: none;">
										<label for="inputName" class="control-label required">Produto </label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary addBox" data-url="action.php?mod=<?php echo fnEncode(1062) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Busca Produtos"><i class="fa fa-search" aria-hidden="true"></i></a>
											</span>
											<input type="text" name="DES_PRODUTO" id="DES_PRODUTO" class="form-control input-sm leituraOff" style="border-radius: 0 3px 3px  0;" readonly="readonly" placeholder="Procurar produto específico...">
											<input type="hidden" name="COD_PRODUTO" id="COD_PRODUTO" value="">
										</div>
									</div>

									<!-- <div class="col-md-3">
										<div class="form-group"  $desabilitaFaixa ?>>
											<label for="inputName" class="control-label">Faixa de Valor Inicial</label>
											<input type="text" class="form-control input-sm text-center money" name="FAIXA_INI" id="FAIXA_INI" maxlength="20" value="">
											<span class="help-block"></span>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group"  $desabilitaFaixa ?>>
											<label for="inputName" class="control-label">Faixa de Valor Final</label>
											<input type="text" class="form-control input-sm text-center money" name="FAIXA_FIM" id="FAIXA_FIM" maxlength="20" value="">
											<span class="help-block"></span>
										</div>
									</div> -->
								</div>

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>


							</div>

							<input type="hidden" name="COD_CONTROLE" id="COD_CONTROLE" value="<?php echo $cod_controle; ?>">
							<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?php echo $cod_campanha; ?>">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
							<input type="hidden" name="TIP_CAMPANHA" id="TIP_CAMPANHA" value="<?php echo $tip_campanha; ?>">

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

							<div class="push5"></div>

						</form>

						<div class="push50"></div>

						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista">

									<table class="table table-bordered table-striped table-hover table-sortable">
										<thead>
											<tr>
												<th width="40"></th>
												<th width="40"></th>
												<th>Código</th>
												<th>Tipo Cliente</th>
												<th>Vantagem Extra</th>
												<th>Qtd. Extra</th>
												<th>Validade</th>
												<th>Produto</th>

											</tr>
										</thead>
										<tbody>

											<?php

											$sql = "SELECT IND.*, PD.DES_PRODUTO FROM INDICA_CLIENTE_CAMPANHA AS IND
														LEFT JOIN PRODUTOCLIENTE AS PD ON IND.COD_PRODUTO = PD.COD_PRODUTO
														WHERE IND.COD_EMPRESA = $cod_empresa AND IND.COD_CAMPANHA = $cod_campanha";

											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$count = 0;

											while ($qrBuscaCampanhaExtra = mysqli_fetch_assoc($arrayQuery)) {
												$count++;
												switch ($qrBuscaCampanhaExtra['TIP_EXTRAIND']) {
													case 'CPC':
														$tip_extra = "Créditos para Próxima Compra";
														$produto = "<td></td>";
														$dinheiro = "R$ ";
														break;

													default:
														$tip_extra = "Produto";
														$produto = "<td>" . $qrBuscaCampanhaExtra['DES_PRODUTO'] . "</td>";
														$dinheiro = "";
														break;
												}

												switch ($qrBuscaCampanhaExtra['TIP_CLIENTE']) {
													case 'CLI':
														$tip_clien = "Indicador (cliente)";
														break;

													default:
														$tip_clien = "Indicado (amigo)";
														break;
												}


												echo "
												<tr>
												<td></td>
												<td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
												<td>" . $qrBuscaCampanhaExtra['COD_CONTROLE'] . "</td>
												<td>" . $tip_clien . "</td>
												<td>" . $tip_extra . "</td>
												<td>" . $dinheiro . fnValor($qrBuscaCampanhaExtra['QTD_EXTRAIND'], 2) . "</td>	
												<td>" . $qrBuscaCampanhaExtra['QTD_DIASIND'] . "</td>
												" . $produto . "
												

												</tr>
												<input type='hidden' id='ret_COD_CONTROLE_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_CONTROLE'] . "'>
												<input type='hidden' id='ret_QTD_EXTRAIND_" . $count . "' value='" . fnValor($qrBuscaCampanhaExtra['QTD_EXTRAIND'], 2) . "'>
												<input type='hidden' id='ret_TIP_EXTRAIND_" . $count . "' value='" . $qrBuscaCampanhaExtra['TIP_EXTRAIND'] . "'>
												<input type='hidden' id='ret_COD_USOIND_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_USOIND'] . "'>
												<input type='hidden' id='ret_QTD_DIASIND_" . $count . "' value='" . $qrBuscaCampanhaExtra['QTD_DIASIND'] . "'>
												<input type='hidden' id='ret_TIP_CLIENTE_" . $count . "' value='" . $qrBuscaCampanhaExtra['TIP_CLIENTE'] . "'>
												<input type='hidden' id='ret_COD_PRODUTO_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_PRODUTO'] . "'>
												<input type='hidden' id='ret_DES_PRODUTO_" . $count . "' value='" . $qrBuscaCampanhaExtra['DES_PRODUTO'] . "'>
												<input type='hidden' id='ret_LIMIT_USO_" . $count . "' value='" . $qrBuscaCampanhaExtra['LIMIT_USO'] . "'>
												<input type='hidden' id='ret_FAIXA_INI_" . $count . "' value='" . fnValor($qrBuscaCampanhaExtra['FAIXA_INI'], 2) . "'>
												<input type='hidden' id='ret_FAIXA_FIM_" . $count . "' value='" . fnValor($qrBuscaCampanhaExtra['FAIXA_FIM'], 2) . "'>
												";
											}

											?>

										</tbody>
									</table>

								</form>

							</div>

						</div>

					</div>

					<div class="push"></div>

				</div>

				</div>
			</div>
			<!-- fim Portlet -->

			<!-- modal -->
			<div class="modal fade popModalAux" id="popModalAux" tabindex='-1'>
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
	</div>

</div>

<div class="push20"></div>

<script>
	//Inicia div de produto oculta;
	$('#divProduto').hide();

	$(document).ready(function() {


		$('#TIP_EXTRAIND').change(function() {
			var selecionado = $(this).val();

			if (selecionado == "PRD") {
				$('#divProduto').show();
				$('#COD_PRODUTO').prop('required', true);
				$('#QTD_EXTRAIND').val('1');
				$('#QTD_EXTRAIND').prop('disabled', true);
			} else {
				$('#divProduto').hide();
				$('#COD_PRODUTO').val("");
				$('#DES_PRODUTO').val("");
				$('#COD_PRODUTO').prop('required', false);
				$('#QTD_EXTRAIND').prop('disabled', false);
			}
		});

		$('#TIP_CLIENTE').change(function() {
			var selecionado = $(this).val();

			if (selecionado == "CLI") {
				$('.limit-uso').show();
			} else {
				$('.limit-uso').hide();
			}
		});

		//chosen obrigatório
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

	});

	function retornaForm(index) {

		$("#formulario #COD_CONTROLE").val($("#ret_COD_CONTROLE_" + index).val());
		$("#formulario #QTD_EXTRAIND").val($("#ret_QTD_EXTRAIND_" + index).val());
		$("#formulario #COD_USOIND").val($("#ret_COD_USOIND_" + index).val());
		$("#formulario #QTD_DIASIND").val($("#ret_QTD_DIASIND_" + index).val());
		$("#formulario #LIMIT_USO").val($("#ret_LIMIT_USO_" + index).val());
		$("#formulario #FAIXA_INI").val($("#ret_FAIXA_INI_" + index).val());
		$("#formulario #FAIXA_FIM").val($("#ret_FAIXA_FIM_" + index).val());
		$("#formulario #TIP_CLIENTE").val($("#ret_TIP_CLIENTE_" + index).val()).trigger("chosen:updated");
		$("#formulario #TIP_EXTRAIND").val($("#ret_TIP_EXTRAIND_" + index).val()).trigger("chosen:updated");


		let retonarSele = $("#ret_COD_PRODUTO_" + index).val();

		if (retonarSele != "" && retonarSele != 0) {
			$("#formulario #COD_PRODUTO").val($("#ret_COD_PRODUTO_" + index).val());
			$("#formulario #DES_PRODUTO").val($("#ret_DES_PRODUTO_" + index).val());
			$('#divProduto').show();
		} else {
			$('#divProduto').hide();
			$('#COD_PRODUTO').val("");
			$('#DES_PRODUTO').val("");
		}

		let tip_cliente = $("#ret_TIP_CLIENTE_" + index).val();

		if (tip_cliente == "CLI") {
			$('.limit-uso').show();
			$("#formulario #LIMIT_USO").val($("#ret_LIMIT_USO_" + index).val());
		} else {
			$('.limit-uso').hide();
		}

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>