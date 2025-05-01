<?php 
include "_system/_functionsMain.php"; 

$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
//fnEscreve($cod_empresa);

if ($_SESSION["SYS_COD_EMPRESA"] == $cod_empresa) { 							

	$sql1 = 'SELECT COD_UNIVEND FROM  USUARIOS WHERE COD_EMPRESA = '.$cod_empresa.' and  cod_usuario='.$_SESSION["SYS_COD_USUARIO"] ;
	//fnEscreve($sql1);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql1) or die(mysqli_error());																
	$qrListaUniveUsu = mysqli_fetch_assoc($arrayQuery);
	$unidades_usuario = $qrListaUniveUsu['COD_UNIVEND'];
	$sqlUnidades = "SELECT COD_UNIVEND, NOM_FANTASI, LOG_ESTATUS FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVEND IN ($unidades_usuario) ORDER BY NOM_FANTASI ";
} else {
	$sqlUnidades = "SELECT COD_UNIVEND, NOM_FANTASI, LOG_ESTATUS FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa ORDER BY NOM_FANTASI ";	
}	
?>					

	<div class="col-md-3"></div>

	<?php
		switch ($cod_empresa) {
			case 121: //águia postos
			case 91: //renaza 
			case 143: //águia postos
			case 176: // posto amigao
			case 178: // central
			case 190: // viplac
				$mostrac10 = "style='display: block;'";
				$disabled = "";
				$cartaoRequired = 'true';
			break;

			default:
				$mostrac10 = "style='display: none;'";
				$disabled = "disabled";
				$cartaoeRquired = 'false';
			break;
		}
	?>

	<div class="col-md-6">
		<div class="form-group">
			<label for="inputName" class="control-label required">Unidades de Venda</label>
			
				<select data-placeholder="Selecione uma unidade para acesso" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1">
					<option value="">&nbsp;</option>
					<?php												
					$arrayQuery = mysqli_query($connAdm->connAdm(),$sqlUnidades) or die(mysqli_error());																
					while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery))
					  {			
					  if($qrListaUnive['LOG_ESTATUS'] == 'N'){ $disabled = "disabled"; }else{ $disabled = " "; }																
						echo"
							  <option value='".$qrListaUnive['COD_UNIVEND']."'".$disabled.">".ucfirst($qrListaUnive['NOM_FANTASI']). "</option> 
							"; 
						  }	
					?>								
				</select>
				<?php //fnEscreve($sql); ?>		
			<div class="help-block with-errors"></div>
		</div>
	</div>	
	<div class="col-md-3"></div>
	
	<div class="push10"></div>
	
	<div class="col-md-3"></div>

	<div class="col-md-6">
			<div class="form-group">
					<label for="inputName" class="control-label"></label>
					<input type="text" class="form-control input-lg text-center cpfcnpj" name="c1" id="c1" value="" placeholder="Informe seu CPF/CNPJ">
					<div class="help-block with-errors"></div>
			</div>
	</div>

	<div class="col-md-3"></div>	

	<div class="push20"></div> 				

	<div class="col-md-3">
	</div>	

	<div class="col-md-6 col-sm-10 f21 text-center" <?=$mostrac10?>>OU</div>
	
	<div class="col-md-3">
	</div>	

	<div class="push15"></div> 				

	<div class="col-md-3 col-sm-1">
	</div>	
	
	<div class="col-md-6 col-sm-10">
		<div class="form-group">
			<label for="inputName" class="control-label"></label>
			<input type="text" class="form-control input-lg text-center cartao" name="c10" id="c10" value="" maxlength="10" autocomplete="off" placeholder="Número do Cartão" <?=$mostrac10?> <?=$disabled?>>
			<div class="help-block with-errors"></div>
		</div>
	</div>	

	<div class="push30"></div>

	<div class="col-md-3 col-md-offset-3">
	
		<!-- <input type="hidden" class="form-control input-lg text-center cpfcnpj" name="c10" id="c10" value="0" placeholder="Informe seu CPF/CNPJ" required>												 -->
		<button type="button" name="ZERO" id="ZERO" class="btn btn-info btn-lg btn-block getBtn " tabindex="5"><i class="fa fa-user-times" aria-hidden="true"></i>&nbsp; Compra Avulsa</button>
</div>

<div class="col-md-3">
	<button type="submit" name="PESQUISA" id="PESQUISA" class="btn btn-success btn-lg btn-block getBtn" tabindex="5"><i class="fa fa-search" aria-hidden="true"></i>&nbsp; Pesquisar Cliente</button>
</div>

<div class="col-md-3"></div>
