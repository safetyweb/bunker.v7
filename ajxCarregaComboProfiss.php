<?php

include '_system/_functionsMain.php';

$cod_empresa = fnLimpaCampoZero(fnDecode($_REQUEST['id']));

?>

<select data-placeholder="Selecione a profissão" name="COD_PROFISS" id="COD_PROFISS" class="chosen-select-deselect">
    <option value=""></option>
    <?php
    $sql = "select COD_PROFISS, DES_PROFISS from PROFISSOES_PREF WHERE COD_EMPRESA = $cod_empresa order by DES_PROFISS ";
    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

    while ($qrListaProfi = mysqli_fetch_assoc($arrayQuery)) {
        echo "
            <option value='" . $qrListaProfi['COD_PROFISS'] . "'>" . $qrListaProfi['DES_PROFISS'] . "</option> 
        ";
    }
    ?>
    <option class="addprof" value="add">+&nbsp;ADICIONAR NOVO</option>
</select>
<script>
    $("#formulario #COD_PROFISS").chosen({allow_single_deselect: true});
    $("#COD_PROFISS").change(function() {
        let valor = $("#COD_PROFISS").val()
        if (valor == "add") {
            $("#formulario #COD_PROFISS").val("").trigger("chosen:updated");
            $("#bnt_profiss").click();

        }
    });
</script>
<a type="hidden" name="bnt_profiss" id="bnt_profiss" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1811) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Cadastrar Profissão"></a>
<div class="help-block with-errors"></div>