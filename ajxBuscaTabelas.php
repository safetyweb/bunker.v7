<?php include "_system/_functionsMain.php"; 
$buscaAjx1 = fnLimpacampo($_GET['ajx1']);
$buscaAjx2 = fnLimpacampo($_GET['ajx2']);
//$buscaAjx3 = fnLimpacampo($_GET['ajx3']);
//fnEscreve($buscaAjx1);

$dbmane=$connGERADOR->DB=$buscaAjx1;
?>

<select data-placeholder="Selecione a tabela desejada" name="TABELAS" id="TABELAS" class="chosen-select-deselect">
	<option value="">&nbsp;</option>					
	<?php 																	
		$sql = "SELECT * FROM information_schema.TABLES  
				where  TABLE_SCHEMA='".$buscaAjx1."' and TABLE_NAME not like '%vw_%'";
		$arrayQuery = mysqli_query($connGERADOR->connGERADOR(),$sql) or die(mysqli_error());
	
		while ($qrListaTabelas = mysqli_fetch_assoc($arrayQuery))
		  {														
			echo"
				  <option value='".$qrListaTabelas['TABLE_NAME']."'>".$qrListaTabelas['TABLE_NAME']."</option> 
				"; 
			  }											
	?>	
</select>
<script language=javascript> 
$(".chosen-select-deselect").chosen({allow_single_deselect:true});
<?php if ($buscaAjx2 != "0") {?>
$("#TABELAS").val("<?php echo $buscaAjx2; ?>").trigger("chosen:updated");
<?php }?>
</script>