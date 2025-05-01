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
$cod_fornecedor = "";
$cod_externo = "";
$nom_fornecedor = "";
$des_abrevia = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$des_icones = "";
$arrayProc = [];
$cod_erro = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$formBack = "";
$abaEmpresa = "";
$qrBuscaProdutos = "";


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

		$cod_fornecedor = fnLimpaCampoZero(@$_REQUEST['COD_FORNECEDOR']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_externo = fnLimpaCampo(@$_REQUEST['COD_EXTERNO']);
		$nom_fornecedor = fnLimpaCampo(@$_REQUEST['NOM_FORNECEDOR']);
		$des_abrevia = fnLimpaCampo(@$_REQUEST['DES_ABREVIA']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		//fnEscreve($des_icones);	

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_FORNECEDORMRKA (
				 '" . $cod_fornecedor . "', 
				 '" . $cod_empresa . "', 
				 '" . $cod_externo . "', 
				 '" . $nom_fornecedor . "', 
				 '" . $des_abrevia . "', 
				 '" . $_SESSION["SYS_COD_USUARIO"] . "', 
				 '" . $opcao . "'    
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
	//$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";

	$sql = "SELECT EMPRESAS.NOM_FANTASI,FORNECEDORMRKA.* FROM $connAdm->DB.EMPRESAS
				left JOIN FORNECEDORMRKA ON FORNECEDORMRKA.COD_EMPRESA=EMPRESAS.COD_EMPRESA
				where EMPRESAS.COD_EMPRESA = " . $cod_empresa . " ";

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($conn, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$cod_fornecedor = $qrBuscaEmpresa['COD_FORNECEDOR'];
		$cod_externo = $qrBuscaEmpresa['COD_EXTERNO'];
		$nom_fornecedor = $qrBuscaEmpresa['NOM_FORNECEDOR'];
		$des_abrevia = $qrBuscaEmpresa['DES_ABREVIA'];
	}
} else {
	$cod_empresa = 0;
	$nom_empresa = "";
	$cod_fornecedor = 0;
	$cod_externo = "";
	$nom_fornecedor = "";
	$des_abrevia = "";
}

//fnMostraForm();

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
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

				<?php $abaEmpresa = 1064;
				include "abasProdutosConfig.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>
							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_FORNECEDOR" id="COD_FORNECEDOR" value="">
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
										<label for="inputName" class="control-label required">Nome do Fornecedor</label>
										<input type="text" class="form-control input-sm" name="NOM_FORNECEDOR" id="NOM_FORNECEDOR" maxlength="20" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Abreviação</label>
										<input type="text" class="form-control input-sm" name="DES_ABREVIA" id="DES_ABREVIA" value="">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Código Externo</label>
										<input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" value="">
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
							<!--<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>-->

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

						<div class="push5"></div>

					</form>
				</div>
			</div>
		</div>

		<div class="push20"></div>

		<div class="portlet portlet-bordered">

			<div class="portlet-body">

				<div class="login-form">

					<div class="push50"></div>

					<div class="col-lg-12">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover tablesorter">
									<thead>
										<tr>
											<th class="{sorter:false}" width="40"></th>
											<th>Código</th>
											<th>Cód. Externo </th>
											<th>Nome do Fornecdor</th>
											<th>Abreviação</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "select * from FORNECEDORMRKA where COD_EMPRESA = " . $cod_empresa . " order by nom_fornecedor";
										$arrayQuery = mysqli_query($conn, $sql);

										$count = 0;
										while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)) {
											$count++;
											echo "
													<tr>
														<td><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></td>
														<td>" . $qrBuscaProdutos['COD_FORNECEDOR'] . "</td>
														<td>" . $qrBuscaProdutos['COD_EXTERNO'] . "</td>
														<td>" . $qrBuscaProdutos['NOM_FORNECEDOR'] . "</td>
														<td>" . $qrBuscaProdutos['DES_ABREVIA'] . "</td>
													</tr>
													<input type='hidden' id='ret_COD_FORNECEDOR_" . $count . "' value='" . $qrBuscaProdutos['COD_FORNECEDOR'] . "'>
													<input type='hidden' id='ret_COD_EXTERNO_" . $count . "' value='" . $qrBuscaProdutos['COD_EXTERNO'] . "'>
													<input type='hidden' id='ret_NOM_FORNECEDOR_" . $count . "' value='" . $qrBuscaProdutos['NOM_FORNECEDOR'] . "'>
													<input type='hidden' id='ret_DES_ABREVIA_" . $count . "' value='" . $qrBuscaProdutos['DES_ABREVIA'] . "'>
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
	$(document).ready(function() {

		$(function() {
			var tabelaFiltro = $('table.tablesorter')
			tabelaFiltro.find("tbody > tr").find("td:eq(1)").mousedown(function() {
				$(this).prev().find(":checkbox").click()
			});
			$("#filter").keyup(function() {
				$.uiTableFilter(tabelaFiltro, this.value);
			})
			$('#formLista').submit(function() {
				tabelaFiltro.find("tbody > tr:visible > td:eq(1)").mousedown();
				return false;
			}).focus();
		});

	});

	function retornaForm(index) {
		$("#formulario #COD_FORNECEDOR").val($("#ret_COD_FORNECEDOR_" + index).val());
		$("#formulario #COD_EXTERNO").val($("#ret_COD_EXTERNO_" + index).val());
		$("#formulario #NOM_FORNECEDOR").val($("#ret_NOM_FORNECEDOR_" + index).val());
		$("#formulario #DES_ABREVIA").val($("#ret_DES_ABREVIA_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>