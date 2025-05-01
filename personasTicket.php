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
$cod_registro = "";
$nom_relaciona = "";
$Arr_COD_UNIVEND = "";
$Arr_COD_MULTEMP = "";
$i = "";
$Arr_COD_PERSONAS = "";
$cod_personas = "";
$cod_usucada = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$arrayProc = [];
$cod_erro = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$popUp = "";
$abaModulo = "";
$qrListaUnive = "";
$qrListaPersonas = "";
$CarregaMaster = "";
$arrayAutorizado = [];
$persona = "";
$qrBuscaModulos = "";


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

		$cod_registro = fnLimpaCampoZero(@$_REQUEST['COD_REGISTRO']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$nom_relaciona = fnLimpaCampo(@$_REQUEST['NOM_RELACIONA']);

		//array das unidades de venda
		if (isset($_POST['COD_UNIVEND'])) {
			$Arr_COD_UNIVEND = @$_POST['COD_UNIVEND'];
			//print_r($Arr_COD_MULTEMP);			 

			for ($i = 0; $i < count($Arr_COD_UNIVEND); $i++) {
				@$cod_univend = @$cod_univend . $Arr_COD_UNIVEND[$i] . ",";
			}

			$cod_univend = substr($cod_univend, 0, -1);
		} else {
			$cod_univend = "0";
		}

		//array das personas
		if (isset($_POST['COD_PERSONAS'])) {
			$Arr_COD_PERSONAS = @$_POST['COD_PERSONAS'];
			//print_r($Arr_COD_MULTEMP);			 

			for ($i = 0; $i < count($Arr_COD_PERSONAS); $i++) {
				$cod_personas = $cod_personas . $Arr_COD_PERSONAS[$i] . ",";
			}

			$cod_personas = substr($cod_personas, 0, -1);
		} else {
			$cod_personas = "0";
		}

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					// CREATE TABLE PERSONAS_TICKET(
					// COD_REGISTRO INT PRIMARY KEY AUTO_INCREMENT,
					// COD_EMPRESA INT,
					// NOM_RELACIONA VARCHAR(120),
					// COD_PERSONAS VARCHAR(2000),
					// COD_UNIVEND VARCHAR(2000),
					// COD_USUCADA INT,
					// DAT_CADASTR TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					// COD_ALTERAC INT,
					// DAT_ALTERAC DATETIME,
					// COD_EXCLUSA INT,
					// DAT_EXCLUSA DATETIME
					// );

					$sql = "INSERT INTO PERSONAS_TICKET(
											COD_EMPRESA,
											NOM_RELACIONA,
											COD_PERSONAS,
											COD_UNIVEND,
											COD_USUCADA
										) VALUES(
											$cod_empresa,
											'$nom_relaciona',
											'$cod_personas',
											'$cod_univend',
											$cod_usucada
										)";

					$arrayProc = mysqli_query(connTemp($cod_empresa, ''), $sql);

					// fnEscreve($sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}

					break;
				case 'ALT':

					$sql = "UPDATE PERSONAS_TICKET SET
								   NOM_RELACIONA = '$nom_relaciona',
								   COD_PERSONAS = '$cod_personas',
								   COD_UNIVEND = '$cod_univend',
								   COD_alterac = $cod_usucada,
								   DAT_ALTERAC = NOW()
							WHERE COD_EMPRESA = $cod_empresa
							AND COD_REGISTRO = $cod_registro";

					$arrayProc = mysqli_query(connTemp($cod_empresa, ''), $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}

					break;
				case 'EXC':

					$sql = "DELETE FROM PERSONAS_TICKET 
							WHERE COD_EMPRESA = $cod_empresa
							AND COD_REGISTRO = $cod_registro";

					$arrayProc = mysqli_query(connTemp($cod_empresa, ''), $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
					}

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

					<?php $abaModulo = 1806;
					include "abasTicketConfig.php"; ?>

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nome do Relacionamento</label>
											<input type="text" class="form-control input-sm" name="NOM_RELACIONA" id="NOM_RELACIONA" value="" maxlength="120" required>
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Unidade de referência</label>
											<!-- <div class="push5"></div> -->
											<select data-placeholder="Selecione a unidade de referência" name="COD_UNIVEND[]" id="COD_UNIVEND" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1" required>
												<option value="9999">Todas as Unidades</option>
												<?php
												$sql = "SELECT COD_UNIVEND, NOM_FANTASI, LOG_ESTATUS FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS = 'S' AND DAT_EXCLUSA IS NULL ORDER BY NOM_FANTASI ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
												while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery)) {
													echo "
													  <option value='" . $qrListaUnive['COD_UNIVEND'] . "'>" . ucfirst($qrListaUnive['NOM_FANTASI']) . "</option> 
													";
												}
												?>
											</select>

											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Personas participantes</label>

											<select data-placeholder="Selecione as personas desejadas" name="COD_PERSONAS[]" id="COD_PERSONAS" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
												<?php
												//se sistema marka
												$sql = "select * from persona where cod_empresa = " . $cod_empresa . " and LOG_ATIVO = 'S' order by DES_PERSONA  ";
												$arrayQuery = mysqli_query($conn, $sql);
												while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {

													// if($CarregaMaster=='0' && $qrListaPersonas['COD_UNIVEND'] != "9999"){
													// if($CarregaMaster=='0'){
													// 	if(recursive_array_search($qrListaPersonas['COD_UNIVEND'],$arrayAutorizado) === false){
													// 		continue;
													// 	}
													// }

													echo "
													<option value='" . $qrListaPersonas['COD_PERSONA'] . "'>" . ucfirst($qrListaPersonas['DES_PERSONA']) . "</option> 
												";
													$persona[$qrListaPersonas['COD_PERSONA']] = array(
														'DES_COR' => $qrListaPersonas['DES_COR'],
														'DES_ICONE' => $qrListaPersonas['DES_ICONE'],
														'DES_PERSONA' => $qrListaPersonas['DES_PERSONA'],
													);
												}
												?>
											</select>
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
							<input type="hidden" name="COD_REGISTRO" id="COD_REGISTRO" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

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
												<th>Nome do Grupo</th>
											</tr>
										</thead>
										<tbody>

											<?php

											$sql = "SELECT * FROM PERSONAS_TICKET WHERE COD_EMPRESA = $cod_empresa ORDER BY COD_REGISTRO DESC";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$count = 0;
											while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
												$count++;
												echo "
													<tr>
														<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
														<td>" . $qrBuscaModulos['COD_REGISTRO'] . "</td>
														<td>" . $qrBuscaModulos['NOM_RELACIONA'] . "</td>
													</tr>
													<input type='hidden' id='ret_COD_REGISTRO_" . $count . "' value='" . $qrBuscaModulos['COD_REGISTRO'] . "'>
													<input type='hidden' id='ret_NOM_RELACIONA_" . $count . "' value='" . $qrBuscaModulos['NOM_RELACIONA'] . "'>
													<input type='hidden' id='ret_COD_UNIVEND_" . $count . "' value='" . $qrBuscaModulos['COD_UNIVEND'] . "'>
													<input type='hidden' id='ret_COD_PERSONAS_" . $count . "' value='" . $qrBuscaModulos['COD_PERSONAS'] . "'>
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
			$("#formulario #COD_REGISTRO").val($("#ret_COD_REGISTRO_" + index).val());
			$("#formulario #NOM_RELACIONA").val($("#ret_NOM_RELACIONA_" + index).val());

			//retorno combo multiplo - lojas
			$("#formulario #COD_UNIVEND").val('').trigger("chosen:updated");

			var sistemasUni = $("#ret_COD_UNIVEND_" + index).val();
			var sistemasUniArr = sistemasUni.split(',');
			//opções multiplas
			for (var i = 0; i < sistemasUniArr.length; i++) {
				$("#formulario #COD_UNIVEND option[value=" + Number(sistemasUniArr[i]) + "]").prop("selected", "true");
			}
			$("#formulario #COD_UNIVEND").trigger("chosen:updated");

			//retorno combo multiplo - PERSONAS
			$("#formulario #COD_PERSONAS").val('').trigger("chosen:updated");

			var sistemasUni2 = $("#ret_COD_PERSONAS_" + index).val();
			var sistemasUniArr2 = sistemasUni2.split(',');
			//opções multiplas
			for (var i = 0; i < sistemasUniArr2.length; i++) {
				$("#formulario #COD_PERSONAS option[value=" + Number(sistemasUniArr2[i]) + "]").prop("selected", "true");
			}
			$("#formulario #COD_PERSONAS").trigger("chosen:updated");

			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}
	</script>