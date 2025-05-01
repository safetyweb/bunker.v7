<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}


$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;


		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if (@$opcao != '') {


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
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

$sqlInd = "SELECT COD_TPUSUARIO, COD_USUARIO, NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $_SESSION[SYS_COD_USUARIO]";
$qrUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), trim($sqlInd)));

if ($qrUsu['COD_TPUSUARIO'] != 9 && $qrUsu['COD_TPUSUARIO'] != 16) {
	$cod_usuario = $qrUsu['COD_USUARIO'];
	$nom_usuario = strtoupper($qrUsu['NOM_USUARIO']);
} else {
	$cod_usuario = "";
}

// $cod_usuario = "";
// echo($_SESSION['SYS_COD_USUARIO']);
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
					<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php
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

				<div class="push30"></div>

				<div class="login-form">

					<form method="post" id="formLista" action="action.php?mod=<?php echo $DestinoPg; ?>&id=0">


						<div class="row">

							<?php
							if ($cod_usuario == "") {
							?>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Selecione o usuário</label>
										<select data-placeholder="Selecione o usuário" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect">
											<option value=""></option>
											<?php

											$sql = "SELECT US.COD_USUARIO, US.NOM_USUARIO FROM USUARIOS US
																			WHERE US.COD_EMPRESA = $cod_empresa
																			AND US.LOG_ESTATUS = 'S'
																			AND (US.COD_EXCLUSA IS NULL OR US.COD_EXCLUSA = 0) 
																			ORDER BY TRIM(US.NOM_USUARIO)";

											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrDesafio = mysqli_fetch_assoc($arrayQuery)) {

											?>

												<option value="<?= fnEncode($qrDesafio['COD_USUARIO']) ?>"><?= $qrDesafio['NOM_USUARIO'] ?></option>

											<?php

											}


											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>
							<?php
							} else {
							?>
								<div class="col-md-4">
									<div class="form-group">
										<h3><?= $nom_usuario ?></h3>
										<input type="hidden" id="COD_USUARIO" value="<?= fnEncode($cod_usuario) ?>">
									</div>
								</div>

							<?php
							}
							?>

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

						<div class="push30"></div>

						<div class="row">

							<div class="col-lg-12 listaUsuarios">

								<div class="no-more-tables">

									<table class="table table-bordered table-striped table-hover tableSorter">
										<thead>
											<tr>
												<th class="{ sorter: false }" width="40"></th>
												<th>Código</th>
												<th>Desafio</th>
												<th>Validade</th>
											</tr>
										</thead>
										<tbody>

											<?php

											$sql = "SELECT COD_DESAFIO, NOM_DESAFIO, DAT_INI, DAT_FIM
															FROM DESAFIO WHERE COD_EMPRESA = $cod_empresa
															ORDER BY COD_DESAFIO DESC";

											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											//fnEscreve($sql);

											$count = 0;
											while ($qrListaUsu = mysqli_fetch_assoc($arrayQuery)) {
												$count++;
												if (@$qrListaUsu['LOG_ESTATUS'] == 'S') {
													$mostraAtivo = '<i class="fal fa-check-square-o" aria-hidden="true"></i>';
												} else {
													$mostraAtivo = ' ';
												}

												echo "
															<tr>
															  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
															  <td>" . $qrListaUsu['COD_DESAFIO'] . "</td>
															  <td>" . $qrListaUsu['NOM_DESAFIO'] . "</td>
															  <td>" . fndatashort($qrListaUsu['DAT_INI']) . " - " . fndatashort($qrListaUsu['DAT_FIM']) . "</td>

															</tr>
															<input type='hidden' id='ret_COD_DESAFIO_" . $count . "' value='" . fnEncode($qrListaUsu['COD_DESAFIO']) . "'>
															";
											}

											?>

										</tbody>
									</table>

					</form>

				</div>

			</div>

		</div>

		<span style="color:#fff;"><?php echo ($count); ?></span>

		<div class="push10"></div>

	</div>

</div>
</div>
<!-- fim Portlet -->
</div>

</div>

<div class="push20"></div>

<?php
if (!is_null($RedirectPg)) {
	$DestinoPg = fnEncode($RedirectPg);
} else {
	$DestinoPg = "";
}
?>

<script type="text/javascript">
	/*
		$("#COD_DESAFIO").change(function(){
			cod_desafio = $(this).val();
			if(cod_desafio != ""){
				$(".listaUsuarios").fadeIn('fast');
			}else{
				$(".listaUsuarios").fadeOut('fast');
			}
		});
		*/

	function retornaForm(index) {

		//$("#nomBusca").val($("#ret_NOM_EMPRESA_"+index).val());
		$('#formLista').attr('action', 'action.do?mod=<?= $DestinoPg ?>&id=<?= fnEncode($cod_empresa) ?>&idU=' + $("#COD_USUARIO").val() + '&idD=' + $("#ret_COD_DESAFIO_" + index).val());
		$('#formLista').submit();

	}
</script>