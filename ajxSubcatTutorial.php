<?php include "_system/_functionsMain.php"; 

//echo fnDebug('true');

$cod_categor = fnLimpaCampoZero($_REQUEST['COD_CATEGOR']);

$sql="SELECT COD_SUBCATEGOR, DES_SUBCATEGOR FROM SUBCATEGORIA_TUTORIAL WHERE COD_CATEGOR = $cod_categor";

//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

?>

    <div class="form-group">
        <label for="inputName" class="control-label">Subcategoria</label>
            <select  class="chosen-select-deselect" data-placeholder="Selecione a subcategoria" name="COD_SUBCATEGOR" id="COD_SUBCATEGOR">
            	<option value=""></option>
                  <?php 

                  	while ($qrSub = mysqli_fetch_assoc($arrayQuery)){

                  		?>
                  			<option value="<?=$qrSub['COD_SUBCATEGOR']?>"><?=$qrSub['DES_SUBCATEGOR']?></option>
                  		<?php 
                  	}

                   ?>

            </select>                                                                   
        <div class="help-block with-errors"></div>                                                          
    </div>
<script type="text/javascript">
	$('#COD_SUBCATEGOR').chosen({allow_single_deselect:true});
</script>