<?php

include '_system/_functionsMain.php';

$cod_empresa = fnLimpaCampoZero(fnDecode($_REQUEST['id']));

?>
<select data-placeholder="Selecione os benefícios" name="COD_BENEFICIOS[]" id="COD_BENEFICIOS" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
    <?php

    $sql = "SELECT * FROM BENEFICIOS 
            WHERE COD_EMPRESA = $cod_empresa
            ";

    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

    while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
        echo "
            <option value='" . $qrLista['COD_BENEFICIOS'] . "'>" . $qrLista['DES_BENEFICIOS'] . "</option> 
        ";
    }
    ?>

    <option class="addprof" value="add">+&nbsp;ADICIONAR NOVO</option>
</select>
<script>
    $("#formulario #COD_BENEFICIOS").chosen({allow_single_deselect: true});
    $("#COD_BENEFICIOS").change(function() {
        let valor = $("#COD_BENEFICIOS").val()
        if (valor == "add") {
            $("#formulario #COD_BENEFICIOS").val("").trigger("chosen:updated");
            $("#bnt_benefic").click();
        }
    })
</script>

<a type="hidden" name="bnt_benefic" id="bnt_benefic" class="addBox" data-url="action.php?mod=<?= fnEncode(1815) ?>&id=<?= fnEncode($cod_empresa) ?>&pop=true" data-title="Cadastrar Benefício"></a>
<a class="btn btn-default btn-sm" id="iNone3" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>
