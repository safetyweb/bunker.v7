<?php include "_system/_functionsMain.php";

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$buscaAjx1 = fnLimpacampo($_GET['ajx1']);
$buscaAjx2 = fnLimpacampo($_GET['ajx2']);
$buscaAjx3 = fnLimpacampo($_GET['ajx3']);
//fnEscreve($buscaAjx2);
?>
<select data-placeholder="Selecione o sub grupo" name="COD_SUBCATE" id="COD_SUBCATE" class="chosen-select-deselect" required>
	<option value="0">&nbsp;</option>
	<?php
	$sql = "select * from SUBCATEGORIA where COD_CATEGOR = '" . $buscaAjx1 . "' AND COD_EMPRESA = '" . $buscaAjx3 . "' order by DES_SUBCATE ";
	$arrayQuery = mysqli_query(connTemp($buscaAjx3, ''), $sql);

	while ($qrListaSubCategoria = mysqli_fetch_assoc($arrayQuery)) {
		echo "
				  <option value='" . $qrListaSubCategoria['COD_SUBCATE'] . "'>" . $qrListaSubCategoria['COD_SUBCATE'] . " - " . $qrListaSubCategoria['DES_SUBCATE'] . "</option> 
				";
	}
	?>
</select>
<?php
//fnEscreve($sql);
//fnEscreve($buscaAjx1);
//fnEscreve($buscaAjx2);
?>
<script language=javascript>
	$(".chosen-select-deselect").chosen({
		allow_single_deselect: true
	});
	$("#COD_SUBCATE").val(<?php echo $buscaAjx2 ?>).trigger("chosen:updated");
</script>