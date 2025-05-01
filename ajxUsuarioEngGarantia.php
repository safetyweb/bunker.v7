
<?php include "_system/_functionsMain.php"; 
$buscaAjx1 = fnLimpacampo($_GET['ajx1']);
$buscaAjx2 = fnLimpacampo($_GET['ajx2']);
$buscaAjx3 = fnLimpacampo($_GET['ajx3']);
$cod_tipo = fnLimpacampoZero(fnDecode($_GET['COD_TIPO']));
echo fnDebug('true');
if($cod_tipo == 7){
	$tp_usuario = 17;
}else{
	$tp_usuario = 18;
}
//fnEscreve($buscaAjx2);
?>
<select data-placeholder="Selecione o Usuario" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect">
	<option value="0">&nbsp;</option>
	<?php
		$sql = "SELECT * FROM USUARIOS WHERE FIND_IN_SET ($buscaAjx1, COD_UNIVEND) AND COD_EMPRESA = '$buscaAjx2' AND COD_TPUSUARIO = $tp_usuario";
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		
		while ($qrListaSubCategoria = mysqli_fetch_assoc($arrayQuery))
		  {													
			echo"
				  <option value='".$qrListaSubCategoria['COD_USUARIO']."'>".$qrListaSubCategoria['NOM_USUARIO']."</option> 
				"; 
			  }	
			  fnEscreve2($sql);
	?>
</select>			
<script language=javascript> 
$(".chosen-select-deselect").chosen({allow_single_deselect:true});
$("#COD_USUARIO").val(<?php echo $buscaAjx3 ?>).trigger("chosen:updated");
</script> 