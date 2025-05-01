<?php 

include "_system/_functionsMain.php"; 
$cod_empresa = fnLimpacampo($_REQUEST['COD_EMPRESA']);
//array das unidades de venda
if (isset($_POST['COD_UNIVEND'])){
	$Arr_COD_UNIVEND = $_POST['COD_UNIVEND'];
	//print_r($Arr_COD_MULTEMP);			 
 
   for ($i=0;$i<count($Arr_COD_UNIVEND);$i++) 
   { 
	$cod_univend = $cod_univend.$Arr_COD_UNIVEND[$i].",";
   } 
   
   $cod_univend = rtrim($cod_univend,',');
	
}else{$cod_univend = "0";}

if($cod_univend == 9999){
	$andUnivend = "";
}else{
	$andUnivend = "AND FIND_IN_SET($cod_univend,COD_UNIVEND)";
}
//fnEscreve(fnDecode($cod_univend));
// fnEscreve($cod_univend);
?>
<div class="form-group">
	<label for="inputName" class="control-label">Usuários</label>
	<select data-placeholder="Selecione os usuários" name="COD_USUARIO[]" id="COD_USUARIO" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
		<option value=""></option>
		<?php
			//$sql = "select * from usuarios where COD_EMPRESA = '".$cod_empresa."' and FIND_IN_SET('".fnDecode($cod_univend)."',COD_UNIVEND) and COD_TPUSUARIO != 7 and DAT_EXCLUSA is null order by nom_usuario ";
	        $sql = "SELECT * FROM USUARIOS WHERE COD_EMPRESA = $cod_empresa $andUnivend AND LOG_ESTATUS = 'S' ORDER BY NOM_USUARIO ";
			$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
			//fnEscreve($sql);
			while ($qrListaUsuario = mysqli_fetch_assoc($arrayQuery))
			  {														
				echo"
					  <option value='".$qrListaUsuario['COD_USUARIO']."'>".$qrListaUsuario['NOM_USUARIO']."</option> 
					"; 
				  }	
		?>
	</select>
	<div class="help-block with-errors"></div>
</div>
<?php // fnEscreve($sql); ?>

<script type="text/javascript">
	$("#COD_USUARIO").chosen();
</script>			
