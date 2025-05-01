<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa,"");
$adm = $connAdm->connAdm();

$cod_bem = fnLimpacampoZero(fnDecode($_GET['idBem']));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_producao = fnLimpaCampoZero($_REQUEST['COD_PRODUCAO']);
		$cod_bem = fnLimpaCampoZero($_REQUEST['COD_BEM']);
		$cod_tpunidade = fnLimpaCampoZero($_REQUEST['COD_TPUNIDADE']);
		$area_ha = fnLimpaCampoZero($_REQUEST['AREA_HA']);
		$produtividade_ha = fnLimpaCampoZero($_REQUEST['PRODUTIVIDADE_HA']);
		$producao = fnLimpaCampo(fnValorSql($_REQUEST['PRODUCAO']));
		$valor_unit = fnLimpaCampo(fnValorSql($_REQUEST['VALOR_UNIT']));
		$receita_esperada = fnLimpaCampo(fnValorSql($_REQUEST['RECEITA_ESPERADA']));

		$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao == 'CAD') {
			$sql = "INSERT INTO PRODUCAO (
				COD_TPUNIDADE,
				COD_BEM,
				AREA_HA,
				PRODUTIVIDADE_HA,
				PRODUCAO,
				VALOR_UNIT,
				RECEITA_ESPERADA,
				COD_EMPRESA,
				COD_USUCADA
				) VALUES (
				'$cod_tpunidade',
				'$cod_bem',
				'$area_ha',
				'$produtividade_ha',
				'$producao',
				'$valor_unit',
				'$receita_esperada',
				'$cod_empresa',
				'$cod_usucada'
			)";

				//fnEscreve($sql);

				mysqli_query(connTemp($cod_empresa,''),$sql);

			}else if($opcao == "ALT"){

				$sql = "UPDATE PRODUCAO SET
				COD_TPUNIDADE = '$cod_tpunidade',
				AREA_HA = '$area_ha',
				PRODUTIVIDADE_HA = '$produtividade_ha',
				PRODUCAO = '$producao',
				VALOR_UNIT = '$valor_unit',
				RECEITA_ESPERADA = '$receita_esperada',
				COD_ALTERAC = '$cod_usucada',
				DAT_ALTERAC = NOW()
				WHERE COD_PRODUCAO = '$cod_producao' AND COD_BEM = '$cod_bem'";

				mysqli_query(connTemp($cod_empresa,''),$sql);

			}else if($opcao == "EXC"){

				$sql = "UPDATE PRODUCAO SET 
				COD_EXCLUSA = '$cod_usucada',
				DAT_EXCLUSA = NOW()
				WHERE COD_PRODUCAO = '$cod_producao' AND COD_BEM = '$cod_bem'";

				mysqli_query(connTemp($cod_empresa,''),$sql);
			}
		}
	}

//busca dados da url
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);

		$sql = "SELECT EMPRESAS.NOM_FANTASI,CATEGORIA.* FROM $connAdm->DB.EMPRESAS
		left JOIN CATEGORIA ON CATEGORIA.COD_EMPRESA=EMPRESAS.COD_EMPRESA
		where EMPRESAS.COD_EMPRESA = $cod_empresa ";

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error(connTemp($cod_empresa, '')));
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

		if (isset($qrBuscaEmpresa)) {
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
	} else {
		$cod_empresa = 0;
	}

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
								<span class="text-primary"><?php echo $NomePg; ?></span>
							</div>
						</div>

					<?php } ?>

					<div class="portlet-body">

						<?php if ($msgRetorno <> '') { ?>
							<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<?php echo $msgRetorno; ?>
							</div>
						<?php } ?>

						<?php 
                    //manu superior - empresas
						$abaBens = 1964; include "abasBens.php";

						?>

						<div class="push30"></div>

						<div class="login-form">

							<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

								<fieldset>
									<legend>Dados de Produção</legend>

									<div class="row">
										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">Código</label>
												<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PRODUCAO" id="COD_PRODUCAO" value="">
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">ÁREA (HA)</label>
												<input type="number" class="form-control input-sm" name="AREA_HA" id="AREA_HA" step="0.01">
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">PRODUTIVIDADE (HA)</label>
												<input type="number" class="form-control input-sm" name="PRODUTIVIDADE_HA" id="PRODUTIVIDADE_HA" step="0.01">
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label required">Tipo de Unidade</label>

												<select data-placeholder="Selecione uma unidade" name="COD_TPUNIDADE" id="COD_TPUNIDADE" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
													<option value="">Selecione</option>
													<?php
													$sql = "SELECT * FROM TIP_UNIDADES WHERE COD_EMPRESA = $cod_empresa AND DAT_EXCLUSA IS NULL";
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
													while ($qrListaCamp = mysqli_fetch_assoc($arrayQuery)) {

														echo "
														<option value='" . $qrListaCamp['COD_TPUNIDADES'] . "'" . $disabled . ">" . ucfirst($qrListaCamp['DES_TPUNIDADES']) . "</option> 
														";
													}

													?>
												</select>

												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">PRODUÇÃO</label>
												<input type="text" class="form-control input-sm leitura" readonly="readonly" name="PRODUCAO" id="PRODUCAO" value="">
											</div>
										</div>

									</div>

								</fieldset>

								<div class="push20"></div>

								<fieldset>
									<legend>Receitas Esperadas</legend>

									<div class="row">
										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">ÁREA (HA)</label>
												<input type="number" class="form-control input-sm" name="AREA_RECEITAS" id="AREA_RECEITAS" step="0.01" disabled>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">PRODUTIVIDADE (HA)</label>
												<input type="number" class="form-control input-sm" name="PRODUTIVIDADE_RECEITAS" id="PRODUTIVIDADE_RECEITAS" step="0.01" disabled>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">VALOR UNITÁRIO</label>
												<input type="text" class="form-control input-sm money" name="VALOR_UNIT" id="VALOR_UNIT" maxlength="100" value="<?= $val_informado ?>">
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">VALOR RECEITA ESPERADA</label>
												<input type="text" class="form-control input-sm leitura" readonly="readonly" name="RECEITA_ESPERADA" id="RECEITA_ESPERADA">
											</div>
										</div>
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

								<input type="hidden" name="opcao" id="opcao" value="">
								<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
								<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
								<input type="hidden" name="COD_BEM" id="COD_BEM" value="<?php echo $cod_bem; ?>">
								<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

								<div class="push5"></div>

							</form>

							<div class="push50"></div>

							<div class="col-lg-12">

								<div class="no-more-tables">

									<form name="formLista">

										<table class="table table-bordered table-striped table-hover">
											<thead>
												<tr>
													<th width="50"></th>
													<th>Código</th>
													<th>Área (HA)</th>
													<th>Produtividade (HA)</th>
													<th>Unidade</th>
													<th>Produção</th>
													<th>Valor Unit.</th>
													<th>Receita Esperada</th>
												</tr>
											</thead>
											<tbody>

												<?php

												$sql = "SELECT B.*, TPU.DES_TPUNIDADES
												FROM PRODUCAO B
												INNER JOIN TIP_UNIDADES TPU ON B.cod_tpunidade = TPU.cod_tpunidades
												WHERE B.DAT_EXCLUSA IS NULL AND B.COD_EMPRESA = '$cod_empresa' AND COD_BEM = '$cod_bem'";
												$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

												$count = 0;
												while ($qrBuscaCampanhaExtra = mysqli_fetch_assoc($arrayQuery)) {
													$count++;

													echo "
													<tr>
													<td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
													<td>".$qrBuscaCampanhaExtra['COD_PRODUCAO']."</td>
													<td>".$qrBuscaCampanhaExtra['AREA_HA']."</td>	
													<td>".$qrBuscaCampanhaExtra['PRODUTIVIDADE_HA']."</td>	
													<td>".$qrBuscaCampanhaExtra['DES_TPUNIDADES']."</td>								
													<td>".fnValor($qrBuscaCampanhaExtra['PRODUCAO'],2)."</td>										
													<td>".fnValor($qrBuscaCampanhaExtra['VALOR_UNIT'],2)."</td>										
													<td>".fnValor($qrBuscaCampanhaExtra['RECEITA_ESPERADA'],2)."</td>										
													
													</tr>
													<input type='hidden' id='ret_COD_PRODUCAO_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_PRODUCAO'] . "'>
													<input type='hidden' id='ret_AREA_HA_" . $count . "' value='" . $qrBuscaCampanhaExtra['AREA_HA'] . "'>
													<input type='hidden' id='ret_PRODUTIVIDADE_HA_" . $count . "' value='" . $qrBuscaCampanhaExtra['PRODUTIVIDADE_HA'] . "'>
													<input type='hidden' id='ret_COD_TPUNIDADE_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_TPUNIDADE'] . "'>

													<input type='hidden' id='ret_PRODUCAO_" . $count . "' value='" . fnValor($qrBuscaCampanhaExtra['PRODUCAO'],2) . "'>
													<input type='hidden' id='ret_VALOR_UNIT_" . $count . "' value='" . $qrBuscaCampanhaExtra['VALOR_UNIT'] . "'>
													<input type='hidden' id='ret_RECEITA_ESPERADA_" . $count . "' value='" . $qrBuscaCampanhaExtra['RECEITA_ESPERADA'] . "'>
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

		<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

		<script>

			function fnValor(numero) {
				return numero.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
			}

			$(document).ready(function () {

				$('#AREA_HA, #PRODUTIVIDADE_HA').on('change', function () {

					var valorTotal = parseFloat($('#AREA_HA').val());
					var produtividade = parseFloat($('#PRODUTIVIDADE_HA').val());

					if (!isNaN(valorTotal) && !isNaN(produtividade)) {
						var resultado = valorTotal * produtividade;

						$('#AREA_RECEITAS').val(valorTotal);
						$('#PRODUTIVIDADE_RECEITAS').val(produtividade);
						$('#PRODUCAO').val(fnValor(resultado));
					}
				});
			});

			$(document).ready(function () {
				$('#VALOR_UNIT').on('change', function () {
					var areaReceitas = parseFloat($('#AREA_RECEITAS').val());
					var produtividadeReceitas = parseFloat($('#PRODUTIVIDADE_RECEITAS').val());
					var valorUnitario = parseFloat($('#VALOR_UNIT').val());

					if (!isNaN(areaReceitas) && !isNaN(produtividadeReceitas) && !isNaN(valorUnitario)){
						var result = (areaReceitas * produtividadeReceitas) * valorUnitario;

						$('#RECEITA_ESPERADA').val(fnValor(result));
					}
				});
			});


			function retornaForm(index) {
				$("#formulario #COD_PRODUCAO").val($("#ret_COD_PRODUCAO_" + index).val());
				$("#formulario #AREA_RECEITAS").val($("#ret_AREA_HA_" + index).val());
				$("#formulario #AREA_HA").val($("#ret_AREA_HA_" + index).val());
				$("#formulario #PRODUTIVIDADE_HA").val($("#ret_PRODUTIVIDADE_HA_" + index).val());
				$("#formulario #PRODUTIVIDADE_RECEITAS").val($("#ret_PRODUTIVIDADE_HA_" + index).val());
				$("#formulario #COD_TPUNIDADE").val($("#ret_COD_TPUNIDADE_" + index).val()).trigger("chosen:updated");
				$("#formulario #PRODUCAO").val($("#ret_PRODUCAO_" + index).val());
				$("#formulario #VALOR_UNIT").val($("#ret_VALOR_UNIT_" + index).val());
				$("#formulario #RECEITA_ESPERADA").val($("#ret_RECEITA_ESPERADA_" + index).val());

				$('#formulario').validator('validate');
				$("#formulario #hHabilitado").val('S');

			}
		</script>