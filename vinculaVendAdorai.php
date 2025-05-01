<?php

	//echo fnDebug('true');

$hashLocal = mt_rand();

if( $_SERVER['REQUEST_METHOD']=='POST' )
{
	$request = md5( implode( $_POST ) );

	if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
	{
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	}
	else
	{
		$_SESSION['last_request']  = $request;
		
		$cod_cupomadorai = fnLimpaCampoZero($_POST['COD_CUPOMADORAI']);			
		$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);			
		$cod_usuario = fnLimpaCampoZero($_POST['COD_USUARIO']);	
		$cod_pedido = fnLimpaCampoZero($_POST['COD_PEDIDO']);	

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];	

		$nom_usuarioSESSION = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != ''){

			switch ($opcao)
			{
				case 'CAD':

				$sql = "UPDATE ADORAI_PEDIDO SET
								    COD_VENDEDOR = $cod_usuario
									WHERE COD_PEDIDO = $cod_pedido";

				$arrayProc = mysqli_query(conntemp($cod_empresa, ''), $sql);

				if (!$arrayProc) {
					$cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
					$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
				}else{
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					?>
					<script>parent.$("#LOG_ATUALIZA").val('S');</script>
				<?php
				}


				break;
				case 'ALT':

				$sql = "UPDATE ADORAI_PEDIDO SET
								    COD_VENDEDOR = $cod_usuario
									WHERE COD_PEDIDO = $cod_pedido";

				$arrayProc = mysqli_query(conntemp($cod_empresa, ''), $sql);

				if (!$arrayProc) {
					$cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
					$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
				}else{
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					?>
					<script>parent.$("#LOG_ATUALIZA").val('S');</script>
				<?php
				}
		
				break;
				case 'EXC':

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
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 274;
	//fnEscreve('entrou else');
}

//busca dados do cupom
if(is_numeric(fnLimpacampo(fnDecode($_GET['idp'])))) {

	$cod_pedido = fnDecode($_GET['idp']);
	$sql = "SELECT * FROM ADORAI_PEDIDO WHERE COD_PEDIDO = $cod_pedido";
	$array = mysqli_query(conntemp($cod_empresa, ''), $sql);

	if($qrBusca = mysqli_fetch_assoc($array)){
		$cod_usuario = $qrBusca['COD_VENDEDOR'];
		$cod_pedido = $qrBusca['COD_PEDIDO'];
	}

}else{
		$cod_usuario = 0;
		$cod_pedido =0;	
	}
?>	

<style>

	.rdo-grp {
		position: absolute;
		top: calc(50% - 10px);
	}
	.rdo-grp label {
		cursor: pointer;
		-webkit-tap-highlight-color: transparent;
		padding: 6px 8px;
		border-radius: 20px;
		float: left;
		transition: all 0.2s ease;
	}
	.rdo-grp label:hover {
		background: rgba(52,152,219,0.06);
	}
	.rdo-grp label:not(:last-child) {
		margin-right: 16px;
	}
	.rdo-grp label span {
		vertical-align: middle;
	}
	.rdo-grp label span:first-child {
		position: relative;
		display: inline-block;
		vertical-align: middle;
		width: 20px;
		height: 20px;
		background: #e8eaed;
		border-radius: 50%;
		transition: all 0.2s ease;
		margin-right: 8px;
	}
	.rdo-grp label span:first-child:after {
		content: '';
		position: absolute;
		width: 16px;
		height: 16px;
		margin: 2px;
		background: #fff;
		border-radius: 50%;
		transition: all 0.2s ease;
	}
	.rdo-grp label:hover span:first-child {
		background: #3498DB;
	}
	.rdo-grp input {
		display: none;
	}
	.rdo-grp input:checked + label span:first-child {
		background: #3498DB;
	}
	.rdo-grp input:checked + label span:first-child:after {
		transform: scale(0.5);
	}

	.compra,
	.forma,
	.itemRadio,
	.outros{
		display: none;
	}


</style>								  


<?php if ($popUp != "true"){  ?>							
	<div class="push30"></div> 
<?php } ?>

<div class="row">				

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<?php if ($popUp != "true"){  ?>							
			<div class="portlet portlet-bordered">
			<?php } else { ?>
				<div class="portlet" style="padding: 0 20px 20px 20px;" >
				<?php } ?>

				<?php if ($popUp != "true"){  ?>
					<div class="portlet-title">
						<div class="caption">
							<i class="fal fa-terminal"></i>
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

					<div class="push30"></div> 

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend>  

									<div class="push20"></div>
								<div class="row">

									<div class="col-md-2" id="">
										<div class="form-group">
											<label for="inputName" class="control-label">Cód. Reserva</label>
											<input type="number" readonly="" class="form-control input-sm leitura" name="QTD_UTILIZADO" id="QTD_UTILIZADO" maxlength="50" value="<?= $cod_pedido ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Vendedor</label>
											<select data-placeholder="Selecione um Vendedor" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect" style="width:100%;" required>									
												<option value="" selected></option>
												<?php 

												$sql = "SELECT * FROM usuarios WHERE cod_empresa = $cod_empresa AND LOG_ESTATUS = 'S'";

												$arrayUsuario = mysqli_query($connAdm->connAdm(),$sql);

												while($qrUsuario = mysqli_fetch_assoc($arrayUsuario)){
													echo "<option value='".$qrUsuario['COD_USUARIO']."'>".$qrUsuario['NOM_USUARIO']."</option>";
												}
												?>		
											</select>									
											<div class="help-block with-errors"></div>
											<?php if($cod_usuario != ""){ ?>
												<script>$("#formulario #COD_USUARIO").val("<?php echo $cod_usuario; ?>").trigger("chosen:updated"); </script>
											<?php } ?>
										</div>
									</div>

								</div>

								<div class="push10"></div>												

							</fieldset>	

							<div class="push10"></div>
							<hr>	
							<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
						<?php if($cod_usuario == ""){ ?>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
						<?php }else{ ?>
							 <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
						<?php } ?>

							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />		
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
							<input type="hidden" name="COD_PEDIDO" id="COD_PEDIDO" value="<?= $cod_pedido ?>" />		
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		

							<div class="push5"></div> 

						</form>									

						<div class="push"></div>

					</div>	
				</div>
			</div>
			<!-- fim Portlet -->
		</div>

	</div>					

	<div class="push20"></div> 

	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />	
	
	<script>
		
		$(document).ready( function() {
			
				//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="hidden"],[type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			
		});
		
	</script>	
