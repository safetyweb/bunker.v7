<?php

$hashLocal = mt_rand();	
require_once "_system/whatsapp/wstAdorai.php";
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

		$cod_senhaparc = fnLimpaCampoZero($_REQUEST['COD_SENHAPARC']);
		$proxy_host = fnLimpaCampo($_REQUEST['PROXY_HOST']);
		$proxy_port = fnLimpaCampo($_REQUEST['PROXY_PORT']);
		$proxy_protocol = fnLimpaCampo($_REQUEST['PROXY_PROTOCOL']);
		$proxy_user = fnLimpaCampo($_REQUEST['PROXY_USER']);
		$proxy_pass = fnLimpaCampo($_REQUEST['PROXY_PASS']);
		$nom_sessao = fnLimpaCampo($_REQUEST['NOM_SESSAO']);
		$des_authkey = fnLimpaCampo($_REQUEST['DES_AUTHKEY']);
		$port_servicao = fnLimpaCampo($_REQUEST['PORT_SERVICAO']);



		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != ''){

			switch ($opcao)
			{
				case 'CAD':

				$proxy = fnPROXY("$nom_sessao", "$proxy_host", "$proxy_port", "$proxy_protocol", "$proxy_user", "$proxy_pass", "$des_authkey", "$port_servicao");

				if($proxy['proxy']['proxy']['enabled'] == true){

					$sql = "UPDATE SENHAS_WHATSAPP SET
	                    PROXY_HOST='$proxy_host',
	                    PROXY_PORT='$proxy_port',
	                    PROXY_PROTOCOL='$proxy_protocol',
	                    PROXY_USER='$proxy_user',
	                    PROXY_PASS='$proxy_pass'
	                    WHERE COD_SENHAPARC = $cod_senhaparc";

	                    mysqli_query($connAdm->connAdm(), $sql);

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					$msgTipo = 'alert-success';
				}else{
					$erro = $proxy['response']['response']['message'][0];
					$msgRetorno = "Erro ao Cadastrar Proxy: $erro";
					$msgTipo = "alert-warning";
				}
				break;

			}			

		}  	

	}
}

if(is_numeric(fnLimpacampo(fnDecode($_GET['CDS'])))){
	$cod_senhaparc = fnDecode($_GET['CDS']);
	$sql = "SELECT * FROM SENHAS_WHATSAPP WHERE COD_SENHAPARC = $cod_senhaparc";
	
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	
	if($qrBuscaSenha = mysqli_fetch_assoc($arrayQuery)){
		$cod_senhaparc = $qrBuscaSenha['COD_SENHAPARC'];
		$celular = $qrBuscaSenha['CELULAR'];
		$nom_sessao = $qrBuscaSenha['NOM_SESSAO'];
		$des_authkey = $qrBuscaSenha['DES_AUTHKEY'];
		$port_servicao = $qrBuscaSenha['PORT_SERVICAO'];

	}else{

		$cod_senhaparc = "";
		$celular = "";
		$nom_sessao = "";
		$des_authkey = "";
		$port_servicao = "";
	}
}


?>


<div class="row">				

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet" style="padding: 0 20px 20px 20px;" >

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

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Código</label>
										<input type="text" class="form-control input-sm leitura" name="COD_SENHAPARC" id="COD_SENHAPARC" maxlength="100" value="<?= $cod_senhaparc ?>">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Celular</label>
										<input type="text" class="form-control input-sm leitura" name="CELULAR" id="CELULAR" maxlength="100" value="<?= fnmasktelefone($celular) ?>">
									</div>
									<div class="help-block with-errors"></div>
								</div>	

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Nome da Sessão</label>
										<input type="text" class="form-control input-sm leitura" name="NOM_SESSAO" id="NOM_SESSAO" maxlength="100" value="<?= $nom_sessao ?>">
									</div>
									<div class="help-block with-errors"></div>
								</div>														

							</div>

							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Proxy Host</label>
										<input type="text" class="form-control input-sm" name="PROXY_HOST" id="PROXY_HOST" maxlength="100" value="" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Proxy Port</label>
										<input type="text" class="form-control input-sm" name="PROXY_PORT" id="PROXY_PORT" maxlength="100" value="" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Proxy Protocol</label>
										<input type="text" class="form-control input-sm" name="PROXY_PROTOCOL" id="PROXY_PROTOCOL" maxlength="100" value="" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Proxy User</label>
										<input type="text" class="form-control input-sm" name="PROXY_USER" id="PROXY_USER" maxlength="100" value="" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Proxy Pass</label>
										<input type="text" class="form-control input-sm" name="PROXY_PASS" id="PROXY_PASS" maxlength="100" value="" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
							</div>

						</fieldset>										

						<div class="push10"></div>
						<hr>

						<div class="form-group text-right col-md-12">
							<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<input type="hidden" class="form-control input-sm" name="NOM_SESSAO" id="NOM_SESSAO" value="<?php echo $nom_sessao; ?>">
						<input type="hidden" class="form-control input-sm" name="DES_AUTHKEY" id="DES_AUTHKEY" value="<?php echo $des_authkey; ?>">
						<input type="hidden" class="form-control input-sm" name="PORT_SERVICAO" id="PORT_SERVICAO" value="<?php echo $port_servicao; ?>">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		

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

<link rel="stylesheet" href="js/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>
<script type="text/javascript" src="js/bootstrap-iconpicker-iconset-fa5.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>

<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
<link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">

<script type="text/javascript">

</script>	