<?php
include "_system/_functionsMain.php";
include '_system/_FUNCTION_WS.php';
//echo $_SERVER['REQUEST_METHOD'];
//echo fnDebug('true');

$buscaAjx1 = fnLimpacampo($_GET['ajx1']);
$buscaAjx2 = fnLimpacampo($_GET['ajx2']);
$buscaAjx3 = fnLimpacampo($_GET['ajx3']);
$cod_empresa = $buscaAjx2;
//fnEscreve($buscaAjx1);
//fnEscreve($buscaAjx2);
//fnEscreve($buscaAjx3);



//$sql = "select LOG_USUARIO, DES_SENHAUS from usuarios where COD_USUARIO = '".$_SESSION["SYS_COD_USUARIO"]."' and DAT_EXCLUSA is null ";
$sql = "select LOG_USUARIO, DES_SENHAUS from usuarios where  cod_empresa=$cod_empresa and cod_tpusuario=10 limit 1";
//fnEscreve($sql);
$qrBuscaUsuario = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sql));

$logUsuarioDoc =  $qrBuscaUsuario['LOG_USUARIO'];
$senhaUsuarioDoc =  fnDecode($qrBuscaUsuario['DES_SENHAUS']);


//$consultaDoc = fnconsultaCPF($buscaAjx1,$cod_empresa,$logUsuarioDoc,$senhaUsuarioDoc);



$consultaDoc = fnconsultaBase($buscaAjx2, LIMPA_DOC($buscaAjx1), $buscaAjx2, $logUsuarioDoc, $connAdm->connAdm());

//if ($consultaDoc[0]['sexo'] == 1){$sexoCli = 1;} else {$sexoCli = 2;}  
if ($consultaDoc[0]['sexo'] == "M" || $consultaDoc[0]['sexo'] == 1) {
	$sexoCli = "1";
} else {
	$sexoCli = "2";
}
//echo "<pre>";
//print_r($consultaDoc);
//echo "<pre>";
//fnEscreve($consultaDoc['sexo'][0]);
//fnEscreve($sexoCli);
//echo '<pre>';
//print_r($consultaDoc);
//echo '<pre>';
//echo $consultaDoc['cartao'][0];
//echo $consultaDoc['nome'][0];
//echo $consultaDoc['datanascimento'][0];
//echo $consultaDoc['sexo'][0];
//echo $consultaDoc['cpf'][0];
//echo $consultaDoc['retornodnamais'][0];
if ($consultaDoc[0]['nome'] == 'Array' || $consultaDoc[0]['nome'] == '') {
	@$nomecliente = '';
} else {
	@$nomecliente = $consultaDoc[0]['nome'];
}

$email = "";
if (isset($consultaDoc[0]['email'])) {
	$email = $consultaDoc[0]['email'];
}

$celular = "";
if (isset($consultaDoc[0]['telcelular'])) {
	$celular = $consultaDoc[0]['telcelular'];
}
?>

<div class="push50"></div>
<div class="push20"></div>

<div class="row">

	<div class="col-md-4">
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<label for="inputName" class="control-label required">Nome </label>
			<input type="text" class="form-control" name="NOM_USUARIO" id="NOM_USUARIO" maxlength="50" value="<?php echo $nomecliente; ?>" required>
			<div class="help-block with-errors"></div>
		</div>
	</div>

	<div class="col-md-4">
	</div>

</div>

<div class="push10"></div>

<div class="row">

	<div class="col-md-4">
	</div>

	<div class="col-md-2">
		<div class="form-group">
			<label for="inputName" class="control-label required">Data de Nascimento </label>
			<input type="text" class="form-control text-center data" name="DAT_NASCIME" id="DAT_NASCIME" maxlength="10" value="<?php echo $consultaDoc[0]['datanascimento']; ?>" required>
			<div class="help-block with-errors"></div>
		</div>
	</div>

	<style>
		.chosen-container {
			font-size: 16px;
		}

		.chosen-container-single .chosen-single {
			height: 45px;
		}

		.chosen-container-single .chosen-single span {
			margin-top: 5px;
		}
	</style>

	<div class="col-md-2">
		<div class="form-group">
			<label for="inputName" class="control-label">Sexo</label>
			<select data-placeholder="Selecione" name="COD_SEXOPES" id="COD_SEXOPES" class="chosen-select-deselect">
				<option value=""></option>
				<?php
				$sql = "select COD_SEXOPES, DES_SEXOPES from sexo order by des_sexopes ";
				$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

				//fnEscreve($sql);
				if ($qrListaSexo['COD_SEXOPES'] == $sexoCli) {
					$selecionado = "selected";
				} else {
					$selecionado = "";
				}

				while ($qrListaSexo = mysqli_fetch_assoc($arrayQuery)) {
					echo "<option value='" . $qrListaSexo['COD_SEXOPES'] . "' " . $selecionado . ">" . $selecionado . $qrListaSexo['DES_SEXOPES'] . "</option>";
				}
				?>
			</select>
			<script>
				$("#COD_SEXOPES").val(<?php echo $sexoCli; ?>).trigger("chosen:updated");
			</script>
			<div class="help-block with-errors"></div>
		</div>
	</div>

	<div class="col-md-4">
	</div>

</div>

<div class="push10"></div>

<div class="row">

	<div class="col-md-4">
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<?php
			$sql = "select max(cod_univend) as COD_UNIVEND, count(cod_univend) as CONTAUNIVE from unidadevenda where COD_EMPRESA = $cod_empresa ";
			$qrContaUnive = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sql));
			$cod_univend = $qrContaUnive['COD_UNIVEND'];
			$contaunive = $qrContaUnive['CONTAUNIVE'];
			//fnEscreve($cod_univend);
			//fnEscreve($contaunive);
			?>
		</div>
	</div>

	<div class="col-md-4">
	</div>

</div>

<div class="push10"></div>

<div class="row">

	<div class="col-md-4">
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<label for="inputName" class="control-label required">e-Mail </label>
			<input type="text" class="form-control" name="DES_EMAILUS" id="DES_EMAILUS" maxlength="50" value="<?php echo $email; ?>" required>
			<div class="help-block with-errors"></div>
		</div>
	</div>

	<div class="col-md-4">
	</div>

</div>


<div class="push10"></div>

<div class="row">

	<div class="col-md-4">
	</div>

	<div class="col-md-2">
		<div class="form-group">
			<label for="inputName" class="control-label required">Celular</label>
			<input type="text" class="form-control text-center celular" name="NUM_CELULAR" id="NUM_CELULAR" maxlength="20" value="<?php fnCorrigeTelefone($celular); ?>" required>
			<div class="help-block with-errors"></div>
		</div>
	</div>

	<div class="col-md-4">
	</div>

</div>

<div class="push50"></div>
<hr>
<div class="form-group text-right col-lg-12">
	<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>

	<?php if ($contaunive > 0) { ?>

		<button type="submit" name="GETDOC2" id="GETDOC2" class="btn btn-primary getBtn"><i class="fa fa-search" aria-hidden="true"></i>&nbsp; Enviar</button>
		<input type="hidden" name="COD_UNIVEND" id="COD_UNIVEND" value="<?= $buscaAjx3 ?>">

	<?php } else { ?>
		<button type="button" name="" id="" onclick="alert('Esta empresa não possui unidade de venda cadastrada');" class="btn btn-primary getBtn"><i class="fa fa-search" aria-hidden="true"></i>&nbsp; Enviar</button>
	<?php }  ?>

</div>

<div class="push10"></div>

<input type="hidden" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?= $buscaAjx1 ?>">
<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
<input type="hidden" name="COD_UNIVEND" id="COD_UNIVEND" value="<?= $buscaAjx3 ?>">
<input type="hidden" name="opcao" id="opcao" value="">
<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

<script type="text/javascript">
	$(document).ready(function() {
		$("#GETDOC2").click(function() {
			getImpressao();
		});

		$("#COD_SEXOPES").trigger("liszt:updated");
	});
</script>