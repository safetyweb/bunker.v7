<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$num_faixain = "";
$num_faixafi = "";
$num_tamanho = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$arrayProc = [];
$cod_erro = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$formBack = "";
$abaEmpresa = "";
$qrBuscaLoteCartao = "";


$hashLocal = mt_rand();

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$num_faixain = fnLimpaCampoZero(@$_REQUEST['NUM_FAIXAIN']);
		$num_faixafi = fnLimpaCampoZero(@$_REQUEST['NUM_FAIXAFI']);
		$num_tamanho = fnLimpaCampoZero(@$_REQUEST['NUM_TAMANHO']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != '') {

			$sql = "CALL SP_LOTECARTAO (
				 '" . $num_faixain . "', 
				 '" . $num_faixafi . "', 
				 '" . $cod_empresa . "', 
				 '" . $cod_usucada . "',    
				 '" . $num_tamanho . "'    
				) ";


			//echo $sql;

			$arrayProc = mysqli_query($conn, $sql);

			if (!$arrayProc) {

				$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
			}

			//mensagem de retorno
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
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
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

?>

<style>
	#blocker {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		opacity: .8;
		background-color: #fff;
		z-index: 1000;
	}

	#blocker div {
		position: absolute;
		top: 30%;
		left: 48%;
		width: 200px;
		height: 2em;
		margin: -1em 0 0 -2.5em;
		color: #000;
		font-weight: bold;
	}
</style>


<div id="blocker">
	<div style="text-align: center;"><img src="images/loading2.gif"><br /> Aguarde. Processando... ;-)</div>
</div>


<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php
				$formBack = "1019";
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


				<?php $abaEmpresa = 1100;
				include "abasEmpresaConfig.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<div class="row">
								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_FORMAPA" id="COD_FORMAPA" value="">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Tamanho do Cartão</label>
										<select data-placeholder="Selecione o tamanho do cartão" name="NUM_TAMANHO" id="NUM_TAMANHO" class="chosen-select-deselect" required>
											<option value=""></option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="8">8</option>
											<!--<option value="10" disabled>10</option>-->
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Faixa Inicial</label>
										<input type="text" class="form-control input-sm int" name="NUM_FAIXAIN" id="NUM_FAIXAIN" maxlength="20" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Faixa Final</label>
										<input type="text" class="form-control input-sm int" name="NUM_FAIXAFI" id="NUM_FAIXAFI" maxlength="20">
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

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="col-lg-12">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th>Faixa Inicial</th>
											<th>Faixa Final</th>
											<th>Tamanho</th>
											<th>Gerados</th>
											<th>Utilizados</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT A.*,
															(SELECT COUNT(*) 
															FROM GERACARTAO
															WHERE COD_LOTCARTAO=A.COD_LOTCARTAO
															) AS QTD_GERADO,
															(SELECT COUNT(*) FROM GERACARTAO WHERE LOG_USADO='S' AND COD_LOTCARTAO=A.COD_LOTCARTAO
															) AS QTD_USADO 
															FROM LOTECARTAO A
															WHERE COD_EMPRESA = $cod_empresa 
															ORDER BY A.NUM_FAIXAIN ";

										$arrayQuery = mysqli_query($conn, $sql);

										$count = 0;
										while ($qrBuscaLoteCartao = mysqli_fetch_assoc($arrayQuery)) {
											$count++;
											echo "
												<tr>
													<td>" . $qrBuscaLoteCartao['NUM_FAIXAIN'] . "</td>
													<td>" . $qrBuscaLoteCartao['NUM_FAIXAFI'] . "</td>
													<td>" . $qrBuscaLoteCartao['NUM_TAMANHO'] . "</td>
													<td>" . fnValor($qrBuscaLoteCartao['QTD_GERADO'], 0) . "</td>
													<td>" . fnValor($qrBuscaLoteCartao['QTD_USADO'], 0) . "</td>
												</tr>
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

<script type="text/javascript">
	function retornaForm(index) {
		$("#formulario #COD_FORMAPA").val($("#ret_COD_FORMAPA_" + index).val());
		$("#formulario #DES_FORMAPA").val($("#ret_DES_FORMAPA_" + index).val());
		$("#formulario #COD_EXTERNO").val($("#ret_COD_EXTERNO_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
	$('#NUM_TAMANHO').change(function() {
		faixa = $('#NUM_TAMANHO').val();
		$('#NUM_FAIXAIN, #NUM_FAIXAFI').val('').attr('maxlength', faixa);
	});
</script>