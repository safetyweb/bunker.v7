<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_veiculo = fnLimpaCampoZero($_REQUEST['COD_VEICULO']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
		$des_renavam = fnLimpaCampoZero($_REQUEST['DES_RENAVAM']);
		$des_tipo = ucwords(strtolower(fnLimpacampo($_REQUEST['DES_TIPO'])));
		$des_placa = strtoupper(fnLimpacampo($_REQUEST['DES_PLACA']));
		$des_marca = ucwords(strtolower(fnLimpacampo($_REQUEST['DES_MARCA'])));
		$des_modelo = ucwords(strtolower(fnLimpacampo($_REQUEST['DES_MODELO'])));
		$des_ano = fnLimpaCampo($_REQUEST['DES_ANO']);
		$cod_usucada = $_SESSION[SYS_COD_USUARIO];

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sql = "INSERT INTO VEICULO_CLIENTE(
												COD_EMPRESA,
												COD_CLIENTE,
												DES_RENAVAM,
												DES_TIPO,
												DES_PLACA,
												DES_MARCA,
												DES_MODELO,
												DES_ANO,
												COD_USUCADA
											) VALUES(
												$cod_empresa,
												$cod_cliente,
												'$des_renavam',
												'$des_tipo',
												'$des_placa',
												'$des_marca',
												'$des_modelo',
												'$des_ano',
												$cod_usucada
											)";

					// fnEscreve($sql);

					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$sqlVeic = "SELECT MAX(COD_VEICULO) COD_VEICULO FROM VEICULO_CLIENTE 
									WHERE COD_EMPRESA = $cod_empresa 
									AND COD_USUCADA = $cod_usucada";

					$arrayVeic = mysqli_query(connTemp($cod_empresa, ''), $sqlVeic);

					$qrVeic = mysqli_fetch_assoc($arrayVeic);

					$cod_veiculo = $qrVeic[COD_VEICULO];


					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;
				case 'ALT':

					$sql = "UPDATE VEICULO_CLIENTE SET
												DES_RENAVAM='$des_renavam',
												DES_TIPO='$des_tipo',
												DES_PLACA = '$des_placa',
												DES_MARCA = '$des_marca',
												DES_MODELO = '$des_modelo',
												DES_ANO = '$des_ano',
												COD_ALTERAC=$cod_usucada,
												DAT_ALTERAC=NOW()
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_VEICULO = $cod_veiculo";

					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'EXC':

					$sql = "UPDATE VEICULO_CLIENTE SET
										COD_EXCLUSA = $cod_usucada,
										DAT_EXCLUSA = NOW()
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_CLIENTE = $cod_cliente
								AND COD_VEICULO = $cod_veiculo";

					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
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
	$cod_cliente = fnDecode($_GET['idc']);
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
	//fnEscreve('entrou else');
}

//fnMostraForm();

// CREATE TABLE VEICULO_CLIENTE (
// 	COD_VEICULO INT(11) PRIMARY KEY AUTO_INCREMENT,
// 	COD_EMPRESA INT(11),
// 	COD_CLIENTE INT(11),
// 	DES_RENAVAM VARCHAR(30),
// 	DES_TIPO VARCHAR(20),
// 	DES_PLACA VARCHAR(10),
// 	DES_MARCA VARCHAR(30),
// 	DES_MODELO VARCHAR(30),
// 	DES_ANO VARCHAR(4),
// 	DES_COR VARCHAR(15),
// 	COD_CADASTR INT(11),
// 	DAT_CADASTR TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
// 	COD_ALTERAC INT(11),
// 	DAT_ALTERAC DATETIME,
// 	COD_EXCLUSA INT(11),
// 	DAT_EXCLUSA DATETIME
// );

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md-12 margin-bottom-30">
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
							<span class="text-primary"><?php echo $NomePg; ?></span>
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

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Renavam</label>
											<input type="text" class="form-control input-sm" name="DES_RENAVAM" id="DES_RENAVAM" value="<?php echo $des_renavam; ?>">
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Tipo</label>
											<input type="text" class="form-control input-sm" name="DES_TIPO" id="DES_TIPO" value="<?php echo $des_tipo; ?>">
										</div>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Placa</label>
											<input type="text" class="form-control input-sm placa" placeholder="Sua placa" data-minlength="7" data-minlength-error="Formato inválido" name="DES_PLACA" id="DES_PLACA" value="<?php echo $des_placa; ?>">
										</div>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Marca</label>
											<input type="text" class="form-control input-sm" name="DES_MARCA" id="DES_MARCA" value="<?php echo $des_marca; ?>">
										</div>
									</div>

								</div>

								<div class="row">
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Modelo</label>
											<input type="text" class="form-control input-sm" name="DES_MODELO" id="DES_MODELO" value="<?php echo $des_modelo; ?>">
										</div>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Ano</label>
											<input type="tel" class="form-control input-sm" name="DES_ANO" id="DES_ANO" maxlength="4" data-minlength="4" data-minlength-error="Ano deve ter 4 dígitos" value="<?php echo $des_ano; ?>">
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
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
							<input type="hidden" name="COD_VEICULO" id="COD_VEICULO" value="<?= $cod_veiculo ?>">
							<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?= $cod_cliente ?>">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

							<div class="push5"></div>

						</form>

						<div class="push50"></div>

						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista">

									<table class="table table-bordered table-striped table-hover tableSorter">
										<thead>
											<tr>
												<th class="{ sorter: false }" width="40"></th>
												<th>Código</th>
												<th>Renavam</th>
												<th>Tipo</th>
												<th>Placa</th>
												<th>Marca</th>
												<th>Modelo</th>
												<th>Ano</th>
											</tr>
										</thead>
										<tbody>

											<?php

											$sql = "SELECT * FROM VEICULO_CLIENTE 
													WHERE COD_CLIENTE = $cod_cliente
													AND COD_EMPRESA = $cod_empresa
													AND COD_EXCLUSA = 0";

											// fnEscreve($sql);
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$count = 0;
											while ($qrDepende = mysqli_fetch_assoc($arrayQuery)) {

												

												$count++;
												echo "
													<tr>
													<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
													<td>" . $qrDepende['COD_VEICULO'] . "</td>
													<td>" . $qrDepende['DES_RENAVAM'] . "</td>
													<td>" . $qrDepende['DES_TIPO'] . "</td>
													<td>" . $qrDepende['DES_PLACA'] . "</td>
													<td>" . $qrDepende['DES_MARCA'] . "</td>
													<td>" . $qrDepende['DES_MODELO'] . "</td>
													<td>" . $qrDepende['DES_ANO'] . "</td>
													<input type='hidden' id='ret_COD_VEICULO_" . $count . "' value='" . $qrDepende['COD_VEICULO'] . "'>
													<input type='hidden' id='ret_DES_RENAVAM_" . $count . "' value='" . $qrDepende['DES_RENAVAM'] . "'>
													<input type='hidden' id='ret_DES_TIPO_" . $count . "' value='" . $qrDepende['DES_TIPO'] . "'>
													<input type='hidden' id='ret_DES_PLACA_" . $count . "' value='" . $qrDepende['DES_PLACA'] . "'>
													<input type='hidden' id='ret_DES_MARCA_" . $count . "' value='" . $qrDepende['DES_MARCA'] . "'>
													<input type='hidden' id='ret_DES_MODELO_" . $count . "' value='" . $qrDepende['DES_MODELO'] . "'>
													<input type='hidden' id='ret_DES_ANO_" . $count . "' value='" . $qrDepende['DES_ANO'] . "'>
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
				</div> <!-- fim Portlet -->
			</div>
	</div>
</div>

<div class="push20"></div>

<script type="text/javascript">

	$(function(){
		$("body").delegate('input.placa','paste', function(e) {
			$(this).unmask();
		});
		$("body").delegate('input.placa','input', function(e) {
			$('input.placa').mask(MercoSulMaskBehavior, mercoSulOptions);
		});
	});

	function retornaForm(index) {
		$("#formulario #COD_VEICULO").val($("#ret_COD_VEICULO_" + index).val());
		$("#formulario #DES_RENAVAM").val($("#ret_DES_RENAVAM_" + index).val());
		$("#formulario #DES_TIPO").val($("#ret_DES_TIPO_" + index).val());
		$("#formulario #DES_PLACA").val($("#ret_DES_PLACA_" + index).val());
		$("#formulario #DES_MARCA").val($("#ret_DES_MARCA_" + index).val());
		$("#formulario #DES_MODELO").val($("#ret_DES_MODELO_" + index).val());
		$("#formulario #DES_ANO").val($("#ret_DES_ANO_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}

	var MercoSulMaskBehavior = function (val) {
		var myMask = 'SSS0A00';
		var mercosul = /([A-Za-z]{3}[0-9]{1}[A-Za-z]{1})/;
		var normal = /([A-Za-z]{3}[0-9]{2})/;
		var replaced = val.replace(/[^\w]/g, '');
		if (normal.exec(replaced)) {
			myMask = 'SSS-0000';
		} else if (mercosul.exec(replaced)) {
			myMask = 'SSS0A00';
		}
		return myMask;
	},

	mercoSulOptions = {
		onKeyPress: function(val, e, field, options) {
			field.mask(MercoSulMaskBehavior.apply({}, arguments), options);
		}
	};
</script>