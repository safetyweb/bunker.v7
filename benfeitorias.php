<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa,"");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_tpbenfeitoria = fnLimpaCampoZero($_REQUEST['COD_TPBENFEITORIA']);
		$cod_benfeitoria = fnLimpaCampoZero($_REQUEST['COD_BENFEITORIA']);
		$cod_bem = fnLimpaCampoZero($_REQUEST['COD_BEM']);
		$des_benfeitoria = fnLimpaCampo($_REQUEST['DES_BENFEITORIA']);
		$qtd_capaci = fnLimpaCampoZero($_REQUEST['QTD_CAPACI']);
		$cod_tpunidade = fnLimpaCampoZero($_REQUEST['COD_TPUNIDADE']);
		$averbado = fnLimpaCampoZero($_POST['AVERBADO']);
		$valor_total = fnLimpaCampo(fnValorSql($_REQUEST['VALOR_TOTAL']));
		$vida_util = fnLimpaCampoZero($_REQUEST['VIDA_UTIL']);
		$area_beneficiada = fnLimpaCampoZero($_REQUEST['AREA_BENEFICIADA']);
		$data_benfeitoria = fnDataSql($_REQUEST['DATA_BENFEITORIA']);
		$estado_conservacao = fnLimpaCampo($_REQUEST['ESTADO_CONSERVACAO']);


		$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$dat_cadastr = "NOW()";

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao == 'CAD') {
			$sql = "INSERT INTO BENFEITORIA (
				COD_TPBENFEITORIA,
				DES_BENFEITORIA,
				COD_BEM,
				QTD_CAPACI,
				COD_TPUNIDADE,
				AVERBADO,
				VALOR_TOTAL,
				VIDA_UTIL,
				AREA_BENEFICIADA,
				DATA_BENFEITORIA,
				ESTADO_CONSERVACAO,
				COD_EMPRESA,
				COD_USUCADA
				) VALUES (
				'$cod_tpbenfeitoria',
				'$des_benfeitoria',
				'$cod_bem',
				'$qtd_capaci',
				'$cod_tpunidade',
				'$averbado',
				'$valor_total',
				'$vida_util',
				'$area_beneficiada',
			    '$data_benfeitoria',
				'$estado_conservacao',
				'$cod_empresa',
				'$cod_usucada'
			)";

				//fnEscreve($sql);

				//fnTesteSql(connTemp($cod_empresa,''),$sql);

				mysqli_query(connTemp($cod_empresa,''),$sql);

			}else if($opcao == "ALT"){

				$sql = "UPDATE BENFEITORIA SET
				COD_TPBENFEITORIA = '$cod_tpbenfeitoria',
				DES_BENFEITORIA = '$des_benfeitoria',
				QTD_CAPACI = '$qtd_capaci',
				COD_TPUNIDADE = '$cod_tpunidade',
				AVERBADO = '$averbado',
				VALOR_TOTAL = '$valor_total',
				VIDA_UTIL = '$vida_util',
				AREA_BENEFICIADA = '$area_beneficiada',
				DATA_BENFEITORIA = '$data_benfeitoria',
				ESTADO_CONSERVACAO = '$estado_conservacao',
				COD_ALTERAC = '$cod_usucada',
				DAT_ALTERAC = NOW()
				WHERE cod_benfeitoria = '$cod_benfeitoria' AND COD_BEM = '$cod_bem'";

				mysqli_query(connTemp($cod_empresa,''),$sql);

			}else if($opcao == "EXC"){

				$sql = "UPDATE BENFEITORIA SET 
				COD_EXCLUSA = '$cod_usucada',
				DAT_EXCLUSA = NOW()
				WHERE cod_benfeitoria = '$cod_benfeitoria' AND COD_BEM = '$cod_bem'";

				mysqli_query(connTemp($cod_empresa,''),$sql);
			}

		/*if ($opcao != '') {

			$sql = "CALL SP_ALTERA_CAT_PROMOCAO (
				 '" . $cod_categor . "', 
				 '" . $cod_empresa . "', 
				 '" . $cod_externo . "', 
				 '" . $des_categor . "', 
				 '" . $des_abrevia . "', 
				 '" . $des_icones . "', 
				 '" . $_SESSION["SYS_COD_USUARIO"] . "', 
				 '" . $opcao . "'    
				) ";

			$arrayProc = mysqli_query($conn, trim($sql));

			switch ($opcao) {
				case 'CAD':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível excluir o registro : $cod_erro";
					}
					break;					
			}
			if ($cod_erro == 0 || $cod_erro == "") {
				$msgTipo = 'alert-success';
			} else {
				$msgTipo = 'alert-danger';
			}
		}*/
	}
}

//busca dados da url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
    $cod_bem = fnLimpacampoZero(fnDecode($_GET['idBem']));

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

//fnEscreve($cod_empresa);

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
                    $abaBens = 1962; include "abasBens.php";
                                                            
                    ?>

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend>

								<div class="row">
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_BENFEITORIA" id="COD_BENFEITORIA" value="">
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Tipo de Benfeitoria</label>

											<select data-placeholder="Selecione uma benfeitoria" name="COD_TPBENFEITORIA" id="COD_TPBENFEITORIA" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
												<option value="">Selecione</option>
												<?php
												$sql = "SELECT * FROM TIP_BENFEITORIA WHERE DAT_EXCLUSA IS NULL AND COD_EMPRESA = '$cod_empresa'";
												$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
												while ($qrListaCamp = mysqli_fetch_assoc($arrayQuery)) {

													echo "
													<option value='" . $qrListaCamp['COD_TPBENFEITORIA'] . "'" . $disabled . ">" . ucfirst($qrListaCamp['DES_TPBENFEITORIA']) . "</option> 
													";
												}

												?>
											</select>

											<div class="help-block with-errors"></div>
										</div>
									</div>


									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Quantidade/Capacidade</label>
											<input type="number" class="form-control input-sm" name="QTD_CAPACI" id="QTD_CAPACI" value="0">
											<div class="help-block with-errors"></div>
										</div>
									</div>


									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Tipo de Unidade</label>

											<select data-placeholder="Selecione uma benfeitoria" name="COD_TPUNIDADE" id="COD_TPUNIDADE" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
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
											<label for="inputName" class="control-label required">Averbado</label>

											<div>
												<input type='radio' name='radioGroup' id='0' onclick='checkRadioButton(this)'>
												<label for='opcao1'>Sim</label>

												<span style="margin-right: 10px;"></span>

												<input type='radio' name='radioGroup' id='1' onclick='checkRadioButton(this)'>
												<label for='opcao1'>Não</label>
											</div>

											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Valor Total (R$)</label>
											<input type="text" class="form-control input-sm money" name="VALOR_TOTAL" id="VALOR_TOTAL" value="<?= $valor_total ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Área Beneficiada</label>
											<input type="text" class="form-control input-sm" name="AREA_BENEFICIADA" id="AREA_BENEFICIADA" value="<?= $area_beneficiada ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label for="inputName" class="control-label required">Descrição</label>
											<input type="text" class="form-control input-sm" name="DES_BENFEITORIA" id="DES_BENFEITORIA" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data</label>

											<div class="input-group date datePicker" id="DAT_INI_GRP">
												<input type='text' class="form-control input-sm data" name="DATA_BENFEITORIA" id="DATA_BENFEITORIA" value=""/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Estado de Conservação</label>

											<select data-placeholder="Selecione uma benfeitoria" name="ESTADO_CONSERVACAO" id="ESTADO_CONSERVACAO" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
												<option value=""></option>
												<option value="Novo">Novo</option>
												<option value="Ruim">Ruim</option>
												<option value="Usado">Usado</option>
											</select>

											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Vida Útil</label>
											<input type="number" class="form-control input-sm" name="VIDA_UTIL" id="VIDA_UTIL" value="<?= $vida_util ?>">
											<div class="help-block with-errors"></div>
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
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
							<input type="hidden" name="COD_BEM" id="COD_BEM" value="<?php echo $cod_bem; ?>">
							<input type="hidden" name="AVERBADO" id="AVERBADO" value="<?php echo $averbado; ?>">

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
												<th>Tipo</th>
												<th>Descrição</th>
												<th>Qtd. Capacidade</th>
												<th>Unidade</th>
												<th>Valor Total</th>
												<th>Estado Conservação</th>
												<th>Data</th>

											</tr>
										</thead>
										<tbody>

											<?php

											$sql = "SELECT B.*, TP.DES_TPBENFEITORIA, TPU.DES_TPUNIDADES
														FROM BENFEITORIA B
														INNER JOIN TIP_BENFEITORIA TP ON B.cod_tpbenfeitoria = TP.cod_tpbenfeitoria
														INNER JOIN TIP_UNIDADES TPU ON B.cod_tpunidade = TPU.cod_tpunidades
														WHERE B.DAT_EXCLUSA IS NULL 
														AND B.COD_EMPRESA = '$cod_empresa'
														AND B.COD_BEM = '$cod_bem';";
											$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

											$count = 0;
											while ($qrBuscaCampanhaExtra = mysqli_fetch_assoc($arrayQuery)) {
												$count++;

												echo "
													<tr>
														<td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
														<td>".$qrBuscaCampanhaExtra['COD_BENFEITORIA']."</td>
														<td>".$qrBuscaCampanhaExtra['DES_TPBENFEITORIA']."</td>	
														<td>".$qrBuscaCampanhaExtra['DES_BENFEITORIA']."</td>	
														<td>".$qrBuscaCampanhaExtra['QTD_CAPACI']."</td>													
														<td>".$qrBuscaCampanhaExtra['DES_TPUNIDADES']."</td>								
														<td>".fnValor($qrBuscaCampanhaExtra['VALOR_TOTAL'],2)."</td>										
														<td>".$qrBuscaCampanhaExtra['ESTADO_CONSERVACAO']."</td>		
														<td>".date("d/m/Y", strtotime($qrBuscaCampanhaExtra['DATA_BENFEITORIA']))."</td>													
													</tr>
													<input type='hidden' id='ret_COD_BENFEITORIA_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_BENFEITORIA'] . "'>
													<input type='hidden' id='ret_COD_TPBENFEITORIA_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_TPBENFEITORIA'] . "'>
													<input type='hidden' id='ret_DES_BENFEITORIA_" . $count . "' value='" . $qrBuscaCampanhaExtra['DES_BENFEITORIA'] . "'>
													<input type='hidden' id='ret_QTD_CAPACI_" . $count . "' value='" . $qrBuscaCampanhaExtra['QTD_CAPACI'] . "'>
													<input type='hidden' id='ret_COD_TPUNIDADE_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_TPUNIDADE'] . "'>
													<input type='hidden' id='ret_AVERBADO_" . $count . "' value='" . $qrBuscaCampanhaExtra['AVERBADO'] . "'>
													<input type='hidden' id='ret_VALOR_TOTAL_" . $count . "' value='" . fnValor($qrBuscaCampanhaExtra['VALOR_TOTAL'],2) . "'>
													<input type='hidden' id='ret_VIDA_UTIL_" . $count . "' value='" . $qrBuscaCampanhaExtra['VIDA_UTIL'] . "'>
													<input type='hidden' id='ret_AREA_BENEFICIADA_" . $count . "' value='" . $qrBuscaCampanhaExtra['AREA_BENEFICIADA'] . "'>
													<input type='hidden' id='ret_DATA_BENFEITORIA_" . $count . "' value='" . date("d/m/Y", strtotime($qrBuscaCampanhaExtra['DATA_BENFEITORIA'])) . "'>

													<input type='hidden' id='ret_ESTADO_CONSERVACAO_" . $count . "' value='" . $qrBuscaCampanhaExtra['ESTADO_CONSERVACAO'] . "'>
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
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

	<script>
		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			maxDate : 'now',
		}).on('changeDate', function(e){
			$(this).datetimepicker('hide');
		});

		function checkRadioButton(radioButton) {
			var radioGroup = radioButton.name;

			var radioButtons = document.getElementsByName(radioGroup);
			for (var i = 0; i < radioButtons.length; i++) {
				radioButtons[i].checked = false;
			}

			radioButton.checked = true;

			var radioValue = radioButton.id;

			$('#AVERBADO').val(radioValue);
		}

		function retornaForm(index) {
			$("#formulario #COD_BENFEITORIA").val($("#ret_COD_BENFEITORIA_" + index).val());
			$("#formulario #DES_BENFEITORIA").val($("#ret_DES_BENFEITORIA_" + index).val());
			$("#formulario #QTD_CAPACI").val($("#ret_QTD_CAPACI_" + index).val());

			$("#formulario #VALOR_TOTAL").val($("#ret_VALOR_TOTAL_" + index).val());
			$("#formulario #VIDA_UTIL").val($("#ret_VIDA_UTIL_" + index).val());
			$("#formulario #AREA_BENEFICIADA").val($("#ret_AREA_BENEFICIADA_" + index).val());
			$("#formulario #DATA_BENFEITORIA").val($("#ret_DATA_BENFEITORIA_" + index).val());
			$("#formulario #ESTADO_CONSERVACAO").val($("#ret_ESTADO_CONSERVACAO_" + index).val()).trigger("chosen:updated");
			$("#formulario #COD_TPBENFEITORIA").val($("#ret_COD_TPBENFEITORIA_" + index).val()).trigger("chosen:updated");
			$("#formulario #COD_TPUNIDADE").val($("#ret_COD_TPUNIDADE_" + index).val()).trigger("chosen:updated");
			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');

			$("input[name='radioGroup']").filter("[id='" + $("#ret_AVERBADO_" + index).val() + "']").prop("checked", true);
		}
	</script>