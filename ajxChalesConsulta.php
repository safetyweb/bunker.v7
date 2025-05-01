<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_REQUEST['id']));
	$cod_hotel = fnLimpaCampo($_REQUEST['COD_HOTEL']);
//	$cod_municipio = fnLimpaCampoZero($_REQUEST['COD_MUNICIPIO']);
?>

<select data-placeholder="Selecione o chalé" name="COD_CHALE" id="COD_CHALE" class="chosen-select-deselect">
	<option value=""></option>
	<?php
		$sqlChale = "SELECT COD_EXTERNO, NOM_QUARTO 
					 FROM ADORAI_CHALES 
					 WHERE COD_EMPRESA = $cod_empresa
					 AND COD_EXCLUSA = 0
					 AND COD_HOTEL = $cod_hotel";
		$arrayChale = mysqli_query(connTemp($cod_empresa,''), $sqlChale);

		while ($qrChale = mysqli_fetch_assoc($arrayChale)) {
	?>
			<option value="<?=$qrChale[COD_EXTERNO]?>"><?=$qrChale[NOM_QUARTO]?></option>
	<?php 
		}
	?>
</select>
<script type="text/javascript">
	$("#COD_CHALE").chosen({allow_single_deselect:true});
</script>