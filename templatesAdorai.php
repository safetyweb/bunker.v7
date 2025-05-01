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

		$cod_template = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE']);
		$cod_gatilho = fnLimpaCampoZero($_REQUEST['COD_GATILHO']);
		$des_gatilho = fnLimpaCampo($_REQUEST['DES_GATILHO']);
		$cod_canalcom = fnLimpaCampoZero($_REQUEST['COD_CANALCOM']);
		$des_template = addslashes(htmlentities($_REQUEST['DES_TEMPLATE']));
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

				$sqlCad = "INSERT INTO TEMPLATES_ADORAI(
												COD_EMPRESA,
												COD_GATILHO,
												COD_CANALCOM,
												DES_GATILHO,
												DES_TEMPLATE,
												COD_USUCADA
													)VALUES(
												$cod_empresa,
												$cod_gatilho,
												$cod_canalcom,
												'$des_gatilho',
												'$des_template',
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
					$sqlAlt = "UPDATE TEMPLATES_ADORAI SET
													COD_GATILHO = $cod_gatilho,
													COD_CANALCOM = $cod_canalcom,
													DES_GATILHO = '$des_gatilho',
													DES_TEMPLATE = '$des_template',
													COD_ALTERAC = $cod_usucada,
													DAT_ALTERAC = NOW()
							WHERE COD_TEMPLATE = $cod_template
							AND COD_EMPRESA = $cod_empresa";

				//fnescreve($sqlAlt);
				//fntestesql(connTemp($cod_empresa,''),$sqlAlt);
				$arrayAlt = mysqli_query(conntemp($cod_empresa,''), $sqlAlt);

				if (!$arrayAlt) {

					$cod_erro = Log_error_comand($adm,conntemp($cod_empresa,''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlAlt,$nom_usuario);
				}
				break;
				case 'EXC':
					$sqlExc = "UPDATE TEMPLATES_ADORAI SET
													COD_EXCLUSA = $cod_usucada,
													DAT_EXCLUSA = NOW()
							WHERE COD_TEMPLATE = $cod_template
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

<style>
.jqte {
    border: #dce4ec 2px solid!important;
    border-radius: 3px!important;
    -webkit-border-radius: 3px!important;    
    box-shadow: 0 0 2px #dce4ec!important;
    -webkit-box-shadow: 0 0 0px #dce4ec!important;
    -moz-box-shadow: 0 0 3px #dce4ec!important;    
    transition: box-shadow 0.4s, border 0.4s;
    margin-top: 0px!important;
    margin-bottom: 0px!important;
}

.jqte_toolbar {   
    background: #fff!important;
    border-bottom: none!important;
}

.jqte_focused {
	border: none!important;
	box-shadow:0 0 3px #00BDFF; -webkit-box-shadow:0 0 3px #00BDFF; -moz-box-shadow:0 0 3px #00BDFF;
}

.jqte_titleText {
	border: none!important;
	border-radius:3px; -webkit-border-radius:3px; -moz-border-radius:3px;
	word-wrap:break-word; -ms-word-wrap:break-word
}

.jqte_tool, .jqte_tool_icon, .jqte_tool_label{
	border: none!important;
}

.jqte_tool_icon:hover{
	border: none!important;
	box-shadow: 1px 5px #EEE;
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
										<label for="inputName" class="control-label required">Gatilhos</label>
										<select data-placeholder="Selecione um Gatilho" name="COD_GATILHO" id="COD_GATILHO" class="chosen-select-deselect" style="width:100%;" required>									
										<option value=""></option>	
										<option value="1">Reservado</option>	
										<option value="2">Alterado</option>	
										<option value="3">Cancelado</option>	
										</select>									
										<div class="help-block with-errors"></div>
										<script type="text/javascript">$("#formulario #COD_GATILHO").val("<?=$cod_gatilho?>").trigger("chosen:updated");</script>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Título</label>
										<input type="text" class="form-control input-sm" name="DES_GATILHO" id="DES_GATILHO" maxlength="60" value="<?=$des_gatilho?>" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>
								
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Canal de Envio</label>
										<select data-placeholder="Selecione um Canal" name="COD_CANALCOM" id="COD_CANALCOM" class="chosen-select-deselect" style="width:100%;" required>									
										<option value=""></option>
										<?php 
											$sql = "SELECT COD_CANALCOM, DES_CANALCOM FROM CANAL_COMUNICACAO";
											  
											$arrayCanal = mysqli_query($connAdm->connAdm(),$sql);
											  
											while($qrCanal = mysqli_fetch_assoc($arrayCanal)){
												echo "<option value='".$qrCanal['COD_CANALCOM']."'>".$qrCanal['DES_CANALCOM']."</option>";
											}
										?>		
										</select>									
										<div class="help-block with-errors"></div>
										<script type="text/javascript">$("#formulario #COD_CANALCOM").val("<?=$cod_canalcom?>").trigger("chosen:updated");</script>
									</div>
								</div>

								<div class="push10"></div>

								<div class="col-lg-12">
									<div class="form-group">
										<label for="inputName" class="control-label required">Descrição:</label>
										<textarea class="editor form-control input-sm" rows="6" name="DES_TEMPLATE" id="DES_TEMPLATE" maxlength="4000"><?php echo $des_template; ?></textarea>
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
						<input type="hidden" name="COD_TEMPLATE" id="COD_TEMPLATE" value="<?=$cod_template?>">
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
											<th>Gatilho</th>
											<th>Canal</th>
										</tr>
									</thead>
									<tbody>

										<?php	

										$sql = "SELECT TA.* FROM TEMPLATES_ADORAI TA
												WHERE TA.COD_EMPRESA = $cod_empresa
												AND (TA.COD_EXCLUSA = 0 OR TA.COD_EXCLUSA IS NULL)";

												// fnEscreve($sql);
										$arrayQuery = mysqli_query(conntemp($cod_empresa,''), $sql);

										$count = 0;
										while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {

											switch ($qrLista[COD_GATILHO]) {
												case '2':
													$gatilho = "Alterado";
												break;

												case '3':
													$gatilho = "Cancelado";
												break;
												
												default:
													$gatilho = "Reservado";
												break;
											}

											switch ($qrLista[COD_CANALCOM]) {
												case '13':
													$canal = "e-Mail";
												break;

												case '20':
													$canal = "WhatsApp";
												break;
												
												default:
													$canal = "SMS";
												break;
											}

											$count++;
											echo "
												<tr>
													<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
													<td>" . $qrLista['COD_TEMPLATE'] . "</td>
													<td>" . $gatilho . "</td>
													<td>" . $canal . "</td>
												</tr>
												<input type='hidden' id='ret_COD_TEMPLATE_" . $count . "' value='" . $qrLista['COD_TEMPLATE'] . "'>
												<input type='hidden' id='ret_COD_GATILHO_" . $count . "' value='" . $qrLista['COD_GATILHO'] . "'>
												<input type='hidden' id='ret_DES_GATILHO_" . $count . "' value='" . $qrLista['DES_GATILHO'] . "'>
												<input type='hidden' id='ret_COD_CANALCOM_" . $count . "' value='" . $qrLista['COD_CANALCOM'] . "'>
												<input type='hidden' id='ret_DES_TEMPLATE_" . $count . "' value='" . html_entity_decode($qrLista['DES_TEMPLATE']) . "'>
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

	<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te-1.4.0.css">
	<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te.png">
	<script type="text/javascript" src="js/plugins/jQuery-TE/jquery-te-1.4.0.min.js"></script>	
	
<script type="text/javascript">

	$(function(){

		// TextArea
		$(".editor").jqte({
			sup: false,
			sub: false,
			outdent: false,
			indent: false,
			left: false,
    		center: false,
    		color: false,
    		right: false,
    		strike: false,
    		source: false,
	        link:false,
	        unlink: false,		        
	        remove: false,
	    	rule: false,
	    	fsize: false,
	    	format: false,
	    });
		
		// $(".jqte_editor").prop('contenteditable','false');
		// Fim

	});

	function retornaForm(index) {
		$("#formulario #COD_TEMPLATE").val($("#ret_COD_TEMPLATE_" + index).val());
		$("#formulario #COD_GATILHO").val($("#ret_COD_GATILHO_" + index).val()).trigger("chosen:updated");
		$("#formulario #DES_GATILHO").val($("#ret_DES_GATILHO_" + index).val());
		$("#formulario #COD_CANALCOM").val($("#ret_COD_CANALCOM_" + index).val()).trigger("chosen:updated");
		$("#formulario #DES_TEMPLATE").jqteVal($("#ret_DES_TEMPLATE_" + index).val());
		
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>