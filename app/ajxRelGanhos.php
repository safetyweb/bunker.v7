<?php 
include '_system/_functionsMain.php';

$cod_empresa = fnLimpaCampoZero(fnDecode($_POST['COD_EMPRESA']));
$cod_cliente = fnLimpaCampoZero(fnDecode($_POST['COD_CLIENTE']));
$casasDec = fnLimpaCampoZero($_POST['casasDec']);
$inicio = fnLimpaCampoZero($_POST['itens']);
$fim = $inicio+5;
$tip_campanha = fnLimpaCampoZero($_POST['TIP_CAMPANHA']);
$cor_textos = $_POST['corTextos'];

$sql = "CALL LISTA_WALLET($cod_cliente, '$cod_empresa', $inicio, $fim)";
// echo($sql);

$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

if(mysqli_num_rows($arrayQuery) > 0){

    while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)){

    $data = explode(" ", $qrBuscaProdutos['DAT_CADASTR']);

    $txtExpira = "Expira em:";
    $corExpira = "";
    $corCred = "";

    if(trim($qrBuscaProdutos['DES_STATUSCRED']) == 'Expirado'){
        $txtExpira = "Expirado em:";
        $corExpira = "text-danger";
    }

    $sinal = "+";

    if($qrBuscaProdutos[TIP_CREDITO] == 'D'){
        $sinal = "-";
        $txtExpira = "";
        $corExpira = "";
        $corCred = "text-danger";
    }

    if($qrBuscaProdutos[VAL_CREDITO] == 0){
        $sinal = "";
        $txtExpira = "";
        $corExpira = "";
    }

?>

        <div class="col-xs-12 reduzMargem corIcones" style="color: <?=$cor_textos?>">
            <div class="shadow2">
                <div class="push5"></div>
                <div class="col-xs-4 zeraPadLateral text-center">
                    <h5 class="f12"><b><?=fnDataShort($data[0])?></b><br/><span class="f10"><?=$data[1]?></span></h5>
                </div>
                <div class="col-xs-1 zeraPadLateral text-center">
                    <h5><?=$qrBuscaProdutos['TIP_CREDITO']?></h5>
                </div>
                <div class="col-xs-4 zeraPadLateral text-center">
                    <h5><?=$qrBuscaProdutos['NOM_FANTASI']?></h5>
                </div>
                <div class="col-xs-3 zeraPadLateral text-center">
                    <h5 class="<?=$corCred?>"><?=$sinal?> <?=fnValor($qrBuscaProdutos['VAL_CREDITO'],$casasDec)?></h5>
                </div>
                <div class="col-xs-12">
                    <h5 class="f10 <?=$corExpira?>" style="margin-top: -10px; margin-bottom: 0;"><?=$txtExpira?> <b><?=fnDataShort($qrBuscaProdutos['DAT_EXPIRA'])?></b></h5>
                </div>
                <div class="push5"></div>
            </div>
        </div>

<?php 

        $totCredito+=$qrBuscaProdutos['VAL_CREDITO'];

    }

?>

<?php

}else{

?>

    <div class="col-xs-12 reduzMargem corIcones" style="color: <?=$cor_textos?>">
        <div class="shadow2">
            <div class="push5"></div>
            <div class="col-xs-12 zeraPadLateral text-center">
                <h5>Não há mais movimentações</h5>
            </div>
            <div class="push5"></div>
        </div>
    </div>

<?php   

}

?>