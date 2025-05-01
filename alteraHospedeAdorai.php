<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();
$adm = $connAdm->connAdm();

$hoje = fnFormatDate(date("Y-m-d"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {

		$_SESSION['last_request']  = $request;
		$cod_proxpagamento = fnLimpaCampo($_REQUEST['COD_PROXPAGAMENTO']);
		$cod_formapag = fnLimpaCampo($_REQUEST['COD_FORMAPAG']);
		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
		$val_valor = fnLimpaCampo(fnValorSql($_REQUEST['VAL_VALOR']));
		$dat_pagamento = fnDataSql($_POST['DAT_PAGAMENTO']);

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


					$sql = "INSERT INTO PROXPAGAMENTO_ADORAI(
						COD_FORMAPAG,
						VAL_VALOR,
						DAT_PAGAMENTO
					)
					VALUES(
						$cod_formapag,
						$val_valor,
						'$dat_pagamento'
						)
					 ";

					$arrayProc = mysqli_query(connTemp($cod_empresa,''),$sql);

					if (!$arrayProc){
						$cod_error = Log_error_comand($connAdm->connAdm(),$connTemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
					}
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':
					$sql = "UPDATE PROXPAGAMENTO_ADORAI SET 
						COD_FORMAPAG = $cod_formapag,
						VAL_VALOR = $val_valor,
						DAT_PAGAMENTO = $dat_pagamento
						WHERE COD_PROXPAGAMENTO = $cod_proxpagamento
					";
				
					$arrayProc = mysqli_query(connTemp($cod_empresa,''),$sql);
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':
					$sql = "";
			
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
	$cod_empresa = 274;
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


?>


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

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
								
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome</label>
										<input type="text" class="form-control input-sm" name="NOM_PROPRIEDADE" id="NOM_PROPRIEDADE" maxlength="60" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Sobrenome</label>
										<input type="text" class="form-control input-sm" name="NOM_PROPRIEDADE" id="NOM_PROPRIEDADE" maxlength="60" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">CPF</label>
										<input type="text" class="form-control input-sm" name="NOM_PROPRIEDADE" id="NOM_PROPRIEDADE" maxlength="60" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Email</label>
										<input type="text" class="form-control input-sm" name="NOM_PROPRIEDADE" id="NOM_PROPRIEDADE" maxlength="60" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Telefone</label>
										<input type="text" class="form-control input-sm" name="NOM_PROPRIEDADE" id="NOM_PROPRIEDADE" maxlength="60" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>


							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
				
						<div class="form-group text-right col-lg-8 col-lg-offset-4">

							<div class="form-group text-right col-lg-12">
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							</div>
						</div>
						
						<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

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

</script>