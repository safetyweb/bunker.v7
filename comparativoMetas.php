<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa,"");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_compara = fnLimpaCampoZero($_REQUEST['COD_COMPARA']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_comp1 = fnLimpaCampoZero($_REQUEST['COD_COMP1']);
		$tip_comp1 = fnLimpaCampoZero($_REQUEST['TIP_COMP1']);
		$cod_comp2 = fnLimpaCampoZero($_REQUEST['COD_COMP2']);
		$tip_comp2 = fnLimpaCampoZero($_REQUEST['TIP_COMP2']);
		$tip_compara = fnLimpaCampoZero($_REQUEST['TIP_COMPARA']);
		$des_compara = fnLimpaCampo($_REQUEST['DES_COMPARA']);
		$val_compara = fnValorSql($_REQUEST['VAL_COMPARA']);

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
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

				// CREATE TABLE COMPARATIVO_METAS(
				// COD_COMPARA INT PRIMARY KEY AUTO_INCREMENT,
				// COD_EMPRESA INT,
				// COD_COMP1 INT,
				// COD_COMP2 INT,
				// DES_COMPARA CHAR(3),
				// VAL_COMPARA DECIMAL(15,2),
				// COD_USUCADA INT,
				// DAT_CADASTR TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				// COD_ALTERAC INT,
				// DAT_ALTERAC DATETIME
				// )

					$sql = "INSERT INTO COMPARATIVO_METAS(
											COD_EMPRESA,
											COD_COMP1,
											TIP_COMP1,
											COD_COMP2,
											TIP_COMP2,
											TIP_COMPARA,
											DES_COMPARA,
											VAL_COMPARA,
											COD_USUCADA
										) VALUES(
											$cod_empresa,
											$cod_comp1,
											$tip_comp1,
											$cod_comp2,
											$tip_comp2,
											$tip_compara,
											'$des_compara',
											'$val_compara',
											$cod_usucada
										)";

					//echo $sql;

					$arrayProc = mysqli_query(conntemp($cod_empresa,""), $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm,conntemp($cod_empresa,""), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':

					$sql = "UPDATE COMPARATIVO_METAS SET
										COD_COMP1 = $cod_comp1,
										TIP_COMP1 = $tip_comp1,
										COD_COMP2 = $cod_comp2,
										TIP_COMP2 = $tip_comp2,
										TIP_COMPARA = $tip_compara,
										DES_COMPARA = '$des_compara',
										VAL_COMPARA = '$val_compara',
										COD_ALTERAC = $cod_usucada,
										DAT_ALTERAC = NOW()
							WHERE COD_EMPRESA = $cod_empresa
							AND COD_COMPARA = $cod_compara";

					// fnEscreve($sql);

					$arrayProc = mysqli_query(conntemp($cod_empresa,""), $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm,conntemp($cod_empresa,""), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':

					$sql = "DELETE FROM COMPARATIVO_METAS
							WHERE COD_EMPRESA = $cod_empresa
							AND COD_COMPARA = $cod_compara";

					//echo $sql;

					$arrayProc = mysqli_query(conntemp($cod_empresa,""), $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm,conntemp($cod_empresa,""), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
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

$sqlComp = "SELECT * FROM COMPARATIVO_METAS 
			WHERE COD_EMPRESA = $cod_empresa";

$arrComp = mysqli_query(conntemp($cod_empresa,""), $sqlComp);

if(isset($arrComp)){

	$qrComp = mysqli_fetch_assoc($arrComp);

	$cod_compara = $qrComp['COD_COMPARA'];
	$cod_comp1 = $qrComp['COD_COMP1'];
	$tip_comp1 = $qrComp['TIP_COMP1'];
	$cod_comp2 = $qrComp['COD_COMP2'];
	$tip_comp2 = $qrComp['TIP_COMP2'];
	$tip_compara = $qrComp['TIP_COMPARA'];
	$des_compara = $qrComp['DES_COMPARA'];
	$val_compara = fnValor($qrComp['VAL_COMPARA'],0);
	

}else{

	$cod_compara = "";
	$cod_comp1 = "";
	$tip_comp1 = "";
	$cod_comp2 = "";
	$tip_comp2 = "";
	$tip_compara = "";
	$des_compara = "";
	$val_compara = "";

}



//fnMostraForm();

?>

<div class="push30"></div>

<div class="row">

	<!-- Portlet -->
	<?php if ($popUp != "true"){  ?>							
	<div class="portlet portlet-bordered">
	<?php } else { ?>
	<div class="portlet" style="padding: 0 20px 20px 20px;" >
	<?php } ?>
	
		<?php if ($popUp != "true"){  ?>
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
			
			<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Comparação 1</label>
										<select data-placeholder="Selecione uma lista" name="COD_COMP1" id="COD_COMP1" class="chosen-select-deselect">
											<option value=""></option>					
											<option value="1">Coluna 1</option> 
											<option value="2">Coluna 2</option>					
										</select>
										<script>$("#formulario #COD_COMP1").val("<?php echo $cod_comp1; ?>").trigger("chosen:updated"); </script>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Comparador 1</label>
										<select data-placeholder="Selecione um comparador" name="TIP_COMP1" id="TIP_COMP1" class="chosen-select-deselect">
											<option value=""></option>					
											<option value="1">Itens/Litros</option> 
											<option value="2">Valor venda (Reais)</option> 
											<option value="3">Qtd. Vendas</option>				
										</select>
										<script>$("#formulario #TIP_COMP1").val("<?php echo $tip_comp1; ?>").trigger("chosen:updated"); </script>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Comparação 2</label>
										<select data-placeholder="Selecione uma lista" name="COD_COMP2" id="COD_COMP2" class="chosen-select-deselect">
											<option value=""></option>					
											<option value="1">Coluna 1</option> 
											<option value="2">Coluna 2</option>						
										</select>
										<script>$("#formulario #COD_COMP2").val("<?php echo $cod_comp2; ?>").trigger("chosen:updated"); </script>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Comparador 2</label>
										<select data-placeholder="Selecione um comparador" name="TIP_COMP2" id="TIP_COMP2" class="chosen-select-deselect">
											<option value=""></option>					
											<option value="1">Itens/Litros</option> 
											<option value="2">Valor venda (Reais)</option> 
											<option value="3">Qtd. Vendas</option>				
										</select>
										<script>$("#formulario #TIP_COMP2").val("<?php echo $tip_comp2; ?>").trigger("chosen:updated"); </script>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Regra da Comparação</label>
										<select data-placeholder="Selecione um estado" name="DES_COMPARA" id="DES_COMPARA" class="chosen-select-deselect">
											<option value=""></option>					
											<option value="PMA">Percentual (maior)</option> 
											<option value="PMN">Percentual (menor)</option> 
											<option value="VMA">Valor (maior)</option> 
											<option value="VMN">Valor (menor)</option>						
										</select>
										<script>$("#formulario #DES_COMPARA").val("<?php echo $des_compara; ?>").trigger("chosen:updated"); </script>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Valor da Meta</label>
										<input type="text" class="form-control input-sm int" value="<?=$val_compara?>" name="VAL_COMPARA" id="VAL_COMPARA" maxlength="50" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Comparador da Meta</label>
										<select data-placeholder="Selecione um estado" name="TIP_COMPARA" id="TIP_COMPARA" class="chosen-select-deselect">
											<option value=""></option>					
											<option value="1">Itens/Litros</option> 
											<option value="2">Valor venda (Reais)</option> 
											<option value="3">Qtd. Vendas</option>						
										</select>
										<script>$("#formulario #TIP_COMPARA").val("<?php echo $tip_compara; ?>").trigger("chosen:updated"); </script>
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
						<input type="hidden" name="COD_COMPARA" id="COD_COMPARA" value="<?php echo $cod_compara ?>">
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
