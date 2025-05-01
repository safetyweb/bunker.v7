<?php

require '../_system/_functionsMain.php';



$sql =  "SELECT * FROM produtocontinuo WHERE DESCRICAO = ''";
//fnEscreve($sql);

$arrayQuery = mysqli_query($prod_continuo->connUser(), $sql);

while ($qrBuscaMedicamento = mysqli_fetch_assoc($arrayQuery)) {
    $data = fnDadosMedicacao($qrBuscaMedicamento['EAN1']);

    echo "Medicamento " . $qrBuscaMedicamento['COD_PRODUTO'] . " atualizado!<br>";
    /*
    echo "<pre>";
    print_r($qrBuscaMedicamento);
    print_r($data);
    echo "</pre>";
    */

    $sql =  "UPDATE produtocontinuo SET
                DESCRICAO = '" . @$data["name"] . "',
                DADOS = '" . str_replace('\\', '\\\\', json_encode($data)) . "',
                DAT_ATUALIZACAO = NOW()
            WHERE COD_PRODUTO = 0" . $qrBuscaMedicamento['COD_PRODUTO'];
    //fnEscreve($sql);
    mysqli_query($prod_continuo->connUser(), $sql);
}
