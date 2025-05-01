<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_comod = fnLimpaCampoZero($_REQUEST['COD_COMOD']);
		$des_comod = fnLimpaCampo($_REQUEST['DES_COMOD']);
		if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}
		$cod_empresa = 274;

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			switch($opcao){
			
			case 'CAD':
				$sqlCad = "INSERT INTO COMODIDADES_ADORAI(
											COD_EMPRESA,
											DES_COMOD,
											LOG_ATIVO,
											COD_USUCADA
											)VALUES(
											$cod_empresa,
											'$des_comod',
											'$log_ativo',
											$cod_usucada
											)";

				//fnescreve($sqlCad);

				//fnTestesql(connTemp($cod_empresa),$sqlCad);				
				$arrayProc = mysqli_query(conntemp($cod_empresa,''), $sqlCad);

				if (!$arrayProc) {

					$cod_erro = Log_error_comand($adm,conntemp($cod_empresa,''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlCad,$nom_usuario);
				}
				break;
				case 'ALT':	
					$sqlAlt = "UPDATE COMODIDADES_ADORAI SET
													DES_COMOD = '$des_comod',
													LOG_ATIVO = '$log_ativo',
													COD_ALTERAC = $cod_usucada,
													DAT_ALTERAC = NOW()
							WHERE COD_COMOD = $cod_comod
							AND COD_EMPRESA = $cod_empresa";

				//fnescreve($sqlAlt);
				//fntestesql(connTemp($cod_empresa,''),$sqlAlt);
				$arrayAlt = mysqli_query(conntemp($cod_empresa,''), $sqlAlt);

				if (!$arrayAlt) {

					$cod_erro = Log_error_comand($adm,conntemp($cod_empresa,''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlAlt,$nom_usuario);
				}
				break;
				case 'EXC':
					$sqlExc = "DELETE FROM COMODIDADES_ADORAI
								WHERE COD_COMOD = $cod_comod
								AND COD_EMPRESA = $cod_empresa";
				$arrayExc = mysqli_query(conntemp($cod_empresa,''), $sqlExc);

				if (!$arrayExc) {

					$cod_erro = Log_error_comand($adm,conntemp($cod_empresa,''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlExc,$nom_usuario);
				}
				break;
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
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
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

$cod_empresa = 274;

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

				<?php 
					$abaAdorai = 1833;
					include "abasAdorai.php";

					$abaManutencaoAdorai = fnDecode($_GET['mod']);
					//echo $abaUsuario;

					//se não for sistema de campanhas

					echo ('<div class="push20"></div>');
					include "abasManutencaoAdorai.php";
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Ativo</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S" checked>
											<span></span>
										</label>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Comodidade</label>
										<input type="text" class="form-control input-sm" name="DES_COMOD" id="DES_COMOD" maxlength="60" required>
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
						<input type="hidden" name="COD_COMOD" id="COD_COMOD" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="col-lg-12">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-striped table-hover tableSorter">
									<thead>
										<tr>
											<th class="{ sorter: false }" width="40"></th>
											<th>Código</th>
											<th>Comodidade</th>
											<th>Ativo</th>
										</tr>
									</thead>
									<tbody>

										<?php	

										$sql = "SELECT * FROM COMODIDADES_ADORAI ORDER BY DES_COMOD";
										$arrayQuery = mysqli_query(conntemp($cod_empresa,''), $sql);

										$count = 0;
										while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {

											$count++;

											$mostraAtivo = "<span class='fal fa-times text-danger'></span>";

											if($qrLista['LOG_ATIVO'] == "S"){
												$mostraAtivo = "<span class='fal fa-check text-success'></span>";
											}

											?>
												<tr>
													<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(<?=$count?>)'></th>
													<td><?=$qrLista['COD_COMOD']?></td>
													<td><?=$qrLista['DES_COMOD']?></td>
													<td><?=$mostraAtivo?></td>
												</tr>
												<input type='hidden' id='ret_COD_COMOD_<?=$count?>' value='<?=$qrLista['COD_COMOD']?>'>
												<input type='hidden' id='ret_DES_COMOD_<?=$count?>' value='<?=$qrLista['DES_COMOD']?>'>
												<input type='hidden' id='ret_LOG_ATIVO_<?=$count?>' value='<?=$qrLista['LOG_ATIVO']?>'>
											<?php
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

<!-- modal -->									
<div class="modal fade" id="popModal" tabindex='-1'>
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

<div class="push20"></div>

	<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te-1.4.0.css">
	<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te.png">
	<script type="text/javascript" src="js/plugins/jQuery-TE/jquery-te-1.4.0.min.js"></script>	
	
<script type="text/javascript">



	function retornaForm(index) {
		$("#formulario #COD_COMOD").val($("#ret_COD_COMOD_" + index).val());
		$("#formulario #DES_COMOD").val($("#ret_DES_COMOD_" + index).val());
		if ($("#ret_LOG_ATIVO_" + index).val() == 'S') {
			$('#formulario #LOG_ATIVO').prop('checked', true);
		} else {
			$('#formulario #LOG_ATIVO').prop('checked', false);
		}
		
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>