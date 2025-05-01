<?php

include '_system/_functionsMain.php';

$cod_empresa = fnLimpaCampoZero(fnDecode($_REQUEST['id']));
$cod_estado = intval(fnLimpaCampoZero($_REQUEST['COD_ESTADO']));

$conn = connTemp($cod_empresa, '');
if (!$conn) {
	die("Erro de conexão: " . mysqli_connect_error());
}

$sql = "SELECT COD_MUNICIPIO_E, NOM_MUNICIPIO FROM MUNICIPIOS WHERE COD_ESTADO = $cod_estado AND COD_MUNICIPIO_E != 0 ORDER BY NOM_MUNICIPIO";
$arrayCidade = mysqli_query($conn, $sql);

if (!$arrayCidade) {
	die("Erro na consulta: " . mysqli_error($conn));
}
?>

<div class="form-group">
	<label for="inputName" class="control-label required">Cidade</label>

	<select data-placeholder="Selecione um município" name="COD_MUNICIPIO_E" id="COD_MUNICIPIO_E" class="chosen-select-deselect" required>
		<option value=""></option>
		<?php while ($qrCidade = mysqli_fetch_assoc($arrayCidade)) { ?>
			<option value="<?= $qrCidade['COD_MUNICIPIO_E'] ?>"><?= $qrCidade['NOM_MUNICIPIO'] ?></option>
		<?php } ?>
	</select>

	<div class="help-block with-errors"></div>
	<script type="text/javascript">
		$("#COD_MUNICIPIO_E").chosen({
			allow_single_deselect: true
		});
	</script>
</div>