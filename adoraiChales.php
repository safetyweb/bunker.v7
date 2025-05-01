<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

$cod_empresa = 274;

$conn = conntemp($cod_empresa,"");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_detalhe = fnLimpaCampoZero($_REQUEST['COD_DETALHE']);
		$des_chamada = fnLimpaCampo($_REQUEST['DES_CHAMADA']);
		$val_diaria = fnValorSql($_REQUEST['VAL_DIARIA']);
		$num_hospede = fnLimpaCampoZero($_REQUEST['NUM_HOSPEDE']);
		$des_template = addslashes(htmlentities($_REQUEST['DES_TEMPLATE']));
		$cod_chale = fnLimpaCampoZero($_REQUEST['COD_CHALE']);

		//array das empresas multiacesso
		if (isset($_POST['COD_COMOD'])) {
			$Arr_COD_COMOD = $_POST['COD_COMOD'];
			//print_r($Arr_COD_COMOD);			 

			for ($i = 0; $i < count($Arr_COD_COMOD); $i++) {
				$cod_comod = $cod_comod . $Arr_COD_COMOD[$i] . ",";
			}

			$cod_comod = ltrim(rtrim(trim($cod_comod), ","), ",");
		} else {
			$cod_comod = "0";
		}

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sql = "INSERT INTO DETALHES_ADORAI(
													COD_EMPRESA,
													COD_CHALE,
													DES_CHAMADA,
													VAL_DIARIA,
													NUM_HOSPEDE,
													DES_TEMPLATE,
													COD_COMOD,
													COD_USUCADA
												)VALUES(
													$cod_empresa,
													$cod_chale,
													'$des_chamada',
													'$val_diaria',
													$num_hospede,
													'$des_template',
													'$cod_comod',
													$cod_usucada
												)";

					//echo $sql;

					$arrayProc = mysqli_query($conn, $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}

				break;

				case 'ALT':

					$sql = "UPDATE DETALHES_ADORAI SET
													DES_CHAMADA = '$des_chamada',
													VAL_DIARIA = '$val_diaria',
													NUM_HOSPEDE = $num_hospede,
													DES_TEMPLATE = '$des_template',
													COD_COMOD = '$cod_comod',
													COD_ALTERAC = $cod_usucada,
													DAT_ALTERAC = NOW()
							WHERE COD_EMPRESA = $cod_empresa
							AND COD_DETALHE = $cod_detalhe";

					//echo $sql;

					$arrayProc = mysqli_query($conn, $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}

				break;

				case 'EXC':

					$sql = "UPDATE DETALHES_ADORAI SET
													COD_EXCLUSA = $cod_usucada,
													DAT_EXCLUSA = NOW()
							WHERE COD_EMPRESA = $cod_empresa
							AND COD_DETALHE = $cod_detalhe";

					//echo $sql;

					$arrayProc = mysqli_query($conn, $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
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

$cod_chale = fnDecode($_GET['idc']);

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$cod_chale = fnDecode($_GET['idc']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	// $cod_empresa = 0;
	$cod_empresa = 274;
	//fnEscreve('entrou else');
}

$sqlDetalhe = "SELECT * FROM DETALHES_ADORAI
			   WHERE COD_EMPRESA = $cod_empresa
			   AND COD_CHALE = $cod_chale
			   AND COD_EXCLUSA = 0";

$arrayQuery = mysqli_query($conn, $sqlDetalhe);

if(isset($arrayQuery)){

	while ($qrChale = mysqli_fetch_assoc($arrayQuery)){

		$cod_detalhe = $qrChale[COD_DETALHE];
		$des_chamada = $qrChale[DES_CHAMADA];
		$val_diaria = fnValor($qrChale[VAL_DIARIA],2);
		$num_hospede = $qrChale[NUM_HOSPEDE];
		$des_template = html_entity_decode($qrChale[DES_TEMPLATE]);
		$cod_comod = $qrChale[COD_COMOD];

	}

}else{

	$cod_detalhe = 0;
	$des_chamada = "";
	$val_diaria = "";
	$num_hospede = "";
	$des_template = "";
	$cod_comod = "";

}

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

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">


								<div class="col-md-8">
									<div class="form-group">
										<label for="inputName" class="control-label">Chamada da Descrição</label>
										<input type="text" class="form-control input-sm" name="DES_CHAMADA" id="DES_CHAMADA" maxlength="200" value="<?=$des_chamada?>" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Valor Referência Diária</label>
										<input type="text" class="form-control input-sm money" name="VAL_DIARIA" id="VAL_DIARIA" value="<?=$val_diaria?>" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Máx. Hóspedes</label>
										<select data-placeholder="Selecione a quantidade" name="NUM_HOSPEDE" id="NUM_HOSPEDE" class="chosen-select-deselect" style="width:100%;" required>									
										<option value=""></option>		
										<option value="1">1</option>		
										<option value="2">2</option>		
										<option value="3">3</option>		
										<option value="4">4</option>		
										<option value="5">5</option>		
										<option value="6">6</option>		
										<option value="7">7</option>		
										<option value="8">8</option>		
										<option value="9">9</option>		
										<option value="10">10</option>		
										</select>									
										<div class="help-block with-errors"></div>
										<script type="text/javascript">$("#formulario #NUM_HOSPEDE").val("<?=$num_hospede?>").trigger("chosen:updated");</script>
									</div>
								</div>

							</div>

							<div class="push10"></div>

							<div class="row">
								
								<div class="col-lg-12">
									<div class="form-group">
										<label for="inputName" class="control-label required">Descrição:</label>
										<textarea class="editor form-control input-sm" rows="6" name="DES_TEMPLATE" id="DES_TEMPLATE" maxlength="4000"><?php echo $des_template; ?></textarea>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="push10"></div>

							<div class="row">
								
								<div class="col-md-12">
									<div class="form-group">
										<label for="inputName" class="control-label required">Comodidades</label>
										<select data-placeholder="Selecione as comodidades" name="COD_COMOD[]" id="COD_COMOD" multiple class="chosen-select-deselect" style="width:100%;" required>									
										<option value=""></option>
										<?php 
											$sql = "SELECT * FROM COMODIDADES_ADORAI ORDER BY DES_COMOD";
											  
											$arrayCanal = mysqli_query(connTemp($cod_empresa,''),$sql);
											  
											while($qrCanal = mysqli_fetch_assoc($arrayCanal)){
												echo "<option value='".$qrCanal['COD_COMOD']."'>".$qrCanal['DES_COMOD']."</option>";
											}
										?>		
										</select>									
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>

						<div class="row">
							<div class="col-md-4">

								<a href="action.do?mod=<?php echo fnEncode(1845)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?php echo fnEncode($cod_chale)?>&pop=true" class="btn btn-info getBtn"><i class="fal fa-image" aria-hidden="true"></i>&nbsp;Ir para Imagens</a>

							</div>
							<div class="text-right col-md-8">

								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<?php if($cod_detalhe > 0){ ?>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
									<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
								<?php }else{ ?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<?php }?>

							</div>
						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" name="COD_DETALHE" id="COD_DETALHE" value="<?php echo $cod_detalhe ?>">
						<input type="hidden" name="COD_CHALE" id="COD_CHALE" value="<?php echo $cod_chale ?>">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

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

	    //retorno combo multiplo - lojas
		$("#formulario #COD_COMOD").val('').trigger("chosen:updated");
		if ("<?=$cod_comod?>" != "" && "<?=$cod_comod?>" != "0") {
			var sistemasUni = "<?=$cod_comod?>";
			var sistemasUniArr = sistemasUni.split(',');
			//opções multiplas
			for (var i = 0; i < sistemasUniArr.length; i++) {
				$("#formulario #COD_COMOD option[value=" + Number(sistemasUniArr[i]) + "]").prop("selected", "true");
			}
			$("#formulario #COD_COMOD").trigger("chosen:updated");
		}
		
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