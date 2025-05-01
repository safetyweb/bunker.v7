<?php 

	include '../_system/_functionsMain.php'; 

	$cod_estado = fnLimpaCampoZero($_REQUEST['COD_ESTADO']);

?>

<div class="form-group">
	<label for="inputName" class="control-label">Cidade</label>

	<select data-placeholder="Selecione um município" name="COD_CIDADE" id="COD_CIDADE" class="chosen-select-deselect">
		<?php

			$sql = "SELECT COD_MUNICIPIO, NOM_MUNICIPIO FROM MUNICIPIOS WHERE COD_ESTADO = $cod_estado ORDER BY NOM_MUNICIPIO";
			while($qrCidade = mysqli_fetch_assoc($arrayEstado)){
		?>
				<option value="<?=$qrCidade['COD_MUNICIPIO']?>"><?=$qrCidade['NOM_MUNICIPIO']?></option>
		<?php
			}

		?>								
	</select>
	<div class="help-block with-errors"></div>
</div>