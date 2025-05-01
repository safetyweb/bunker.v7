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

		$cod_imagem = fnLimpaCampoZero($_REQUEST['COD_IMAGEM']);
		$cod_chale = fnLimpaCampoZero($_REQUEST['COD_CHALE']);
		$des_imagem = fnLimpaCampo($_REQUEST['DES_IMAGEM']);
		$nom_imagem = fnLimpaCampo($_REQUEST['NOM_IMAGEM']);

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

					$sql = "INSERT INTO IMAGENS_ADORAI(
													COD_EMPRESA,
													COD_CHALE,
													NOM_IMAGEM,
													DES_IMAGEM,
													COD_USUCADA
												)VALUES(
													$cod_empresa,
													$cod_chale,
													'$nom_imagem',
													'$des_imagem',
													$cod_usucada
												)";

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

					$sql = "UPDATE IMAGENS_ADORAI SET
													DES_IMAGEM = '$des_imagem',
													NOM_IMAGEM = '$nom_imagem',
													COD_ALTERAC = $cod_usucada,
													DAT_ALTERAC = NOW()
							WHERE COD_EMPRESA = $cod_empresa
							AND COD_IMAGEM = $cod_imagem";

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

					$sql = "UPDATE IMAGENS_ADORAI SET
													COD_EXCLUSA = $cod_usucada,
													DAT_EXCLUSA = NOW()
							WHERE COD_EMPRESA = $cod_empresa
							AND COD_IMAGEM = $cod_imagem";

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

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Nome Imagem</label>
										<input type="text" class="form-control input-sm" name="NOM_IMAGEM" id="NOM_IMAGEM">									
										<div class="help-block with-errors"></div>
									</div>
								</div>
								
								<div class="col-md-8">
									<div class="form-group">
										<label for="inputName" class="control-label">URL Imagem</label>
										<input type="text" class="form-control input-sm" name="DES_IMAGEM" id="DES_IMAGEM">									
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>

						<div class="row">
							
							<div class="col-md-4">

								<a href="action.do?mod=<?php echo fnEncode(1843)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?php echo fnEncode($cod_chale)?>&pop=true" class="btn btn-info getBtn"><i class="fal fa-cog" aria-hidden="true"></i>&nbsp;Ir para Detalhes</a>

							</div>
							<div class="text-right col-md-8">

								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

							</div>

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
						<input type="hidden" name="COD_CHALE" id="COD_CHALE" value="<?php echo $cod_chale ?>">
						<input type="hidden" name="COD_IMAGEM" id="COD_IMAGEM" value="<?php echo $cod_imagem ?>">

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
											<th width="5%">Código</th>
											<th>Imagem</th>
											<th>URL</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT COD_IMAGEM, DES_IMAGEM, NOM_IMAGEM FROM IMAGENS_ADORAI WHERE COD_EMPRESA = $cod_empresa AND COD_CHALE = $cod_chale AND COD_EXCLUSA = 0";
										$arrayQuery = mysqli_query($conn, $sql);

										$count = 0;
										while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
											$count++;
											echo "
													<tr>
														<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
														<td>" . $qrBuscaModulos['COD_IMAGEM'] . "</td>
														<td>" . $qrBuscaModulos['NOM_IMAGEM'] . "</td>
														<td>" . $qrBuscaModulos['DES_IMAGEM'] . "</td>
													</tr>
													<input type='hidden' id='ret_COD_IMAGEM_" . $count . "' value='" . $qrBuscaModulos['COD_IMAGEM'] . "'>
													<input type='hidden' id='ret_DES_IMAGEM_" . $count . "' value='" . $qrBuscaModulos['DES_IMAGEM'] . "'>
													<input type='hidden' id='ret_NOM_IMAGEM_" . $count . "' value='" . $qrBuscaModulos['NOM_IMAGEM'] . "'>
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

	$(function(){

		

	});

	function retornaForm(index) {
		$("#formulario #COD_IMAGEM").val($("#ret_COD_IMAGEM_" + index).val());
		$("#formulario #DES_IMAGEM").val($("#ret_DES_IMAGEM_" + index).val());
		$("#formulario #NOM_IMAGEM").val($("#ret_NOM_IMAGEM_" + index).val());
		
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}

</script>