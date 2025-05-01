<?php include "_system/_functionsMain.php"; 
$buscaAjx1 = fnLimpacampo($_GET['ajx1']);
$buscaAjx2 = fnLimpacampo($_GET['ajx2']);

if(!is_numeric($buscaAjx1)){
	$buscaAjx1 = fnDecode($buscaAjx1);
}
//fnEscreve(fnDecode($buscaAjx1));
//fnEscreve($buscaAjx2);
?>
<select data-placeholder="Selecione um usuário" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect requiredChk" required>
	<option value=""></option>
	<?php
		//$sql = "select * from usuarios where COD_EMPRESA = '".$buscaAjx2."' and FIND_IN_SET('".fnDecode($buscaAjx1)."',COD_UNIVEND) and COD_TPUSUARIO != 7 and DAT_EXCLUSA is null order by nom_usuario ";
        $sql = "SELECT * from usuarios where COD_EMPRESA = $buscaAjx2 and FIND_IN_SET($buscaAjx1,COD_UNIVEND) and DAT_EXCLUSA is null order by nom_usuario ";
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		//fnEscreve($sql);
		while ($qrListaUsuario = mysqli_fetch_assoc($arrayQuery))
		  {														
			echo"
				  <option value='".fnEncode($qrListaUsuario['COD_USUARIO'])."'>".$qrListaUsuario['NOM_USUARIO']."</option> 
				"; 
			  }	
	?>
</select>
<div class="help-block with-errors"></div>
<?php //fnEscreve($sql); ?>			
<script language=javascript> 
$("#COD_USUARIO").chosen({allow_single_deselect:true});
//$("#formulario #COD_USUARIO").val("zO27adm7tmQ¢").trigger("chosen:updated");
//$("#COD_SUBCATE").val(<?php echo $buscaAjx2 ?>).trigger("chosen:updated");
</script> 