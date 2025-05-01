<?php
// echo fnDebug('true');
$cod_cliente_header = fnLimpacampoZero(fnDecode($_GET["idC"]));

$sql = "SELECT * FROM CLIENTES 
WHERE COD_CLIENTE = $cod_cliente_header
AND COD_EMPRESA = $cod_empresa";
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error(connTemp($cod_empresa, '')));
$cli = mysqli_fetch_assoc($arrayQuery);

$sql = "SELECT BENS.*,CLIENTES.NOM_CLIENTE FROM BENS
LEFT JOIN CLIENTES ON CLIENTES.COD_CLIENTE=BENS.COD_CLIENTE
WHERE BENS.COD_BEM = 0" . fnLimpacampoZero(fnDecode($_GET['idBem'])) . "
AND BENS.COD_EMPRESA = $cod_empresa
AND BENS.COD_EXCLUSA IS NULL
ORDER BY BENS.NOM_BEMUSOS";
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error(connTemp($cod_empresa, '')));
$hbem = mysqli_fetch_assoc($arrayQuery);



if (is_numeric(fnLimpaCampoZero(fnDecode($_GET['idCt'])))) {

    $num_contrato = fnDecode($_GET['idCt']);
    $sql = "SELECT NUM_CONTRATO, COD_STATUS, COD_TIPOBEM FROM CONTRATO_BLOCK where NUM_CONTRATO = '" . $num_contrato . "' AND COD_CLIENTE = '" . $cod_cliente . "' AND COD_EMPRESA = '" . $cod_empresa . "' ";

    $query = mysqli_query(connTemp($cod_empresa, ''), $sql);
    $qrBuscaContrato = mysqli_fetch_assoc($query);

    if (isset($query)) {
        $num_contrato_header = $qrBuscaContrato['NUM_CONTRATO'];
        $cod_status_header = $qrBuscaContrato['COD_STATUS'];

        if ($cod_status_header == 1) {
            $status_header = "Proposta";
        } else {
            $status_header = "";
        }
    } else {
        $cod_status = 0;
    }
}


?>
<style type="text/css">
    .header-cliente, .campo-cliente{
        background-color: #F2F5F6!important;
        border-color: #e5e5e500!important;
    }
</style>
<fieldset class="header-cliente">
    <!-- <legend>Dados Gerais</legend> -->
    <div class="push20"></div>

    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label required">Código</label>
                <input type="text" class="form-control input-sm leitura campo-cliente" readonly="readonly" name="COD_BEM" id="COD_BEM" value="<?=$cod_cliente_header?>">
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label required">Empresa</label>
                <input type="text" class="form-control input-sm leitura campo-cliente" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?=$nom_empresa?>">
            </div>
        </div>

        <div class="col-md-5">
            <label class="control-label required">Cliente</label>
            <input type="hidden" name="COD_CLIENTE_HEADER" id="COD_CLIENTE_HEADER" value="<?= $cod_cliente_header ?>">
            <input type="text" class="form-control input-sm leitura campo-cliente" readonly="readonly" name="NOM_CLIENTE" id="NOM_CLIENTE" value="<?= $cli["NOM_CLIENTE"] ?>">
        </div>

    </div>

    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label required">Número Contrato</label>
                <input type="text" class="form-control input-sm leitura campo-cliente" readonly="readonly" name="NUM_CONTRATO" id="NUM_CONTRATO" value="<?=$num_contrato_header?>">
            </div>
        </div>


        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label required">Status da Proposta</label>
                <input type="text" class="form-control input-sm leitura campo-cliente" readonly="readonly" name="COD_STATUS" id="COD_STATUS" value="<?=$status_header?>">
            </div>
        </div>
    </div>

    <div class="push10"></div>

</fieldset>

<div class="push10"></div>