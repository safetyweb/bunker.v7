<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_REQUEST['id']));
	$cod_clientes = fnLimpaCampo($_REQUEST['COD_CLIENTES']);
//	$cod_municipio = fnLimpaCampoZero($_REQUEST['COD_MUNICIPIO']);
?>

<span class="input-group-btn">
    <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071)?>&id=<?php echo fnEncode($cod_empresa)?>&op=AGE&pop=true" data-title="Busca <?=$cliente?>"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;" ></i></a>
</span>
<select data-placeholder="Nenhum Selecionado" name="COD_CLIENTE_MULT[]" id="COD_CLIENTE_MULT" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
    <?php 

    if($cod_clientes != ""){
        $sql = "SELECT COD_CLIENTE, NOM_CLIENTE FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE IN($cod_clientes)";
        $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
        
        while ($qrLista = mysqli_fetch_assoc($arrayQuery))
        {														
            echo"
            <option value='".$qrLista['COD_CLIENTE']."' selected>".$qrLista['NOM_CLIENTE']."</option> 
            "; 
        }

    }

    ?>
</select>

<script type="text/javascript">
	$("#COD_CLIENTE_MULT").chosen({allow_single_deselect:true});
</script>