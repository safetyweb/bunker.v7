<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$insertCampoControle = '';

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;
		$cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		//if ($opcao != ''){
		if ($opcao != '') {

			//limpa dados anterior tabela
			$delete = "DELETE FROM controle_campos WHERE COD_EMPRESA = $cod_empresa";
			mysqli_query($connAdm->connAdm(), $delete);

			//monta array - novos campos escolhidos
			foreach ($_REQUEST['COD_CAMPOOBG'] as $selected) {
				$part = explode('_', $selected);
				list($part1, $part2) = explode('_', $selected);
				$insertCampoControle .= "INSERT INTO controle_campos (NOM_CAMPOOBG, COD_CAMPOOBG, COD_EMPRESA) VALUES ('$part1','$part2', '$cod_empresa');";
			}

			//grava novos campos
			//fnEscreve($insertCampoControle);
			mysqli_multi_query($connAdm->connAdm(), $insertCampoControle);

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
	$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//fnMostraForm();

?>
<style>
	.bigCheck {
		width: 20px;
		height: 20px;
		margin-top: 5px
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

				<div class="push20"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push30"></div>

						<div class="col-lg-12">

							<div class="no-more-tables">

								<table class="table table-bordered table-hover">
									<thead>
										<tr>
											<th colspan="6">Lista de Campos para Controle</th>
										</tr>
									</thead>
									<tbody>
										<tr>

											<?php

											$sqllista = "select * from controle_campos where cod_empresa=$cod_empresa ";
											$arrayQuerylista = mysqli_query($connAdm->connAdm(), $sqllista);

											$countProfi = 0;
											while ($qrBuscaProfissaoAutlista = mysqli_fetch_assoc($arrayQuerylista)) {
												$countProfi++;
												$arrayRetorno[] = $qrBuscaProfissaoAutlista['COD_CAMPOOBG'];
											}

											$sql = "select A.* 
													from INTEGRA_CAMPOOBG A where A.COD_CAMPOOBG IN (5,8,13,11,19,25) 
													order by A.NUM_ORDENAC";
											//fnEscreve($sql);
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											$count = 0;
											$countLinha = 0;
											while ($qrBuscaCampoAut = mysqli_fetch_assoc($arrayQuery)) {

												if (recursive_array_search($qrBuscaCampoAut['COD_CAMPOOBG'], array_filter($arrayRetorno)) !== false) {
													$checado = "checked";
												} else {
													$checado = "";
												}

												$count++;
												$countLinha++;
											?>
												<td width="16%">
													<input type="checkbox" name="COD_CAMPOOBG[]" class="bigCheck" value="<?php echo $qrBuscaCampoAut['NOM_CAMPOOBG']; ?>_<?php echo $qrBuscaCampoAut['COD_CAMPOOBG']; ?>" <?php echo $checado; ?>> &nbsp;
													<span><?php echo $qrBuscaCampoAut['NOM_CAMPOOBG']; ?></span>
												</td>
												<?php
												if ($countLinha == 4) {
													// echo "</tr>";	
													$countLinha = 0;
												}
												?>


											<?php
											}
											?>


									</tbody>
								</table>
							</div>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<?php if ($countProfi == 0) { ?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<?php } else { ?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
								<?php } ?>

							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

					</form>


				</div>

				<div class="push50"></div>

			</div>

		</div>
	</div>
	<!-- fim Portlet -->
</div>

</div>

<div class="push20"></div>

<script type="text/javascript">
	function retornaForm(index) {
		$("#formulario #COD_TIPOREG").val($("#ret_COD_TIPOREG_" + index).val());
		$("#formulario #DES_TIPOREG").val($("#ret_DES_TIPOREG_" + index).val());
		$("#formulario #COD_EXTERNO").val($("#ret_COD_EXTERNO_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>