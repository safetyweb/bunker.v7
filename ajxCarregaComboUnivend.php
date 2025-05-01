<?php

include '_system/_functionsMain.php';

$cod_empresa = fnLimpaCampoZero(fnDecode($_REQUEST['id']));

?>

<select data-placeholder="Selecione a Secretaria" name="COD_UNIVEND_ATE" id="COD_UNIVEND_ATE" class="chosen-select-deselect">
    <option value=""></option>
    <?php
    $sql = "select COD_UNIVEND, NOM_FANTASI from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' AND LOG_ESTATUS = 'S' order by NOM_FANTASI ";
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

    while ($qrListaUnidade = mysqli_fetch_assoc($arrayQuery)) {
        echo "
                <option value='" . $qrListaUnidade['COD_UNIVEND'] . "'>" . $qrListaUnidade['NOM_FANTASI'] . "</option> 
            ";
    }
    ?>
    <option value="add">+&nbsp;ADICIONAR NOVO</option>
</select>
<script>
    $("#formulario #COD_UNIVEND").val("<?php echo $cod_univend; ?>").trigger("chosen:updated");
    $("#COD_UNIVEND").change(function(){
        let valor = $("#COD_UNIVEND").val()
        if(valor == "add"){
        $("#formulario #COD_UNIVEND").val("").trigger("chosen:updated");
        $("#bnt_univend").click();
        
    }   
    })
                                                    
</script>
<a type="hidden" name="bnt_univend" id="bnt_univend" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1816) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Cadastrar Profissão"></a>
<div class="help-block with-errors"></div>