<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$mod = "";
$cod_chamado = "";
$dat_cadastro = "";
$msgRetorno = "";
$msgTipo = "";
$des_comentario = "";
$cod_usucada = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$popUp = "";


$hashLocal = mt_rand();
$mod = fnDecode(@$_GET['mod']);
$cod_chamado = fnLimpaCampoZero(fnDecode(@$_GET['idC']));
$dat_cadastro = date("Y-m-d H:i:s");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request'] = $request;

		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$des_comentario = fnLimpaCampo(@$_REQUEST['DES_COMENTARIO']);
		$coment_base64 = base64_encode($des_comentario);
	}
}

$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

$opcao = @$_REQUEST['opcao'];
$hHabilitado = @$_REQUEST['hHabilitado'];
$hashForm = @$_REQUEST['hashForm'];

$sql = "";

if ($opcao != '') {

	if ($opcao == 'CAD') {

		$sql = "INSERT INTO SAC_COMENTECNICO(
		COD_CHAMADO,
		DES_COMENTARIO,
		COD_EMPRESA,
		COD_USUARIO,
		DAT_CADASTRO)
		VALUES(
		'$cod_chamado',
		'$coment_base64',
		'$cod_empresa',
		$cod_usucada,
		'$dat_cadastro')
		";

		mysqli_query($connAdmSAC->connAdm(), $sql);

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
		}
		$msgTipo = 'alert-success';
	}
}

// Busca dados da URL
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id']))) && fnDecode(@$_GET['id']) != 0) {
	// Busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 7;
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
}

if (isset($_GET['pop'])) {
	$popUp = $_GET['pop'];
}

?>


<div class="push30"></div>

<div class="row">
	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<?php if ($popUp != "true") { ?>
			<div class="portlet portlet-bordered">
			<?php } else { ?>
				<div class="portlet" style="padding: 0 20px 20px 20px;">
				<?php } ?>

				<?php if ($popUp != "true") { ?>
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
				</div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<div class="row">

							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label required">Código Chamado</label>
									<input type="text" class="form-control input-sm leitura" readonly="readonly" name="ID" id="ID" value="<?php echo $cod_chamado; ?>">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label required">Empresa</label>
									<input type="text" class="form-control input-sm leitura" readonly="readonly" name="ID" id="ID" value="<?php echo $nom_empresa; ?>">
								</div>
							</div>

						</div>

						<div class="push30"></div>
						<div class="row">

							<div class="col-lg-12">
								<div class="form-group">
									<label for="inputName" class="control-label required">Mensagem: </label>
									<textarea class="editor form-control input-sm" rows="6" name="DES_COMENTARIO" id="DES_COMENTARIO"></textarea>
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
						<div class="push30"></div>
						<div class="form-group text-right col-lg-12">
							<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-send" aria-hidden="true"></i>&nbsp; Enviar</button>
						</div>

						<input type="hidden" name="COD_CHAMADO" id="COD_CHAMADO" value="<?= $cod_chamado ?>">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />

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
				</div>
			</div>
	</div>
</div>
</div>