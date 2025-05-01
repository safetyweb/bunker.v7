<?php

include '_system/_functionsMain.php';
include "_system/whatsapp/wstAdorai.php";

$valor = fnLimpaCampo($_REQUEST['value']);
$cod_senhaparc = fnLimpaCampoZero($_POST['senhaparc']);
$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));

if (substr($valor, 0, 2) === "55" && strlen($valor) === 13) {
    $sql = "SELECT * FROM SENHAS_WHATSAPP WHERE COD_EMPRESA = $cod_empresa AND COD_SENHAPARC = $cod_senhaparc";

    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

    if ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
        $delete = Fndelete("$qrBusca[NOM_SESSAO]", "$qrBusca[DES_AUTHKEY]", "$qrBusca[PORT_SERVICAO]");

        $sqlUpdate = "UPDATE SENHAS_WHATSAPP SET
                            CELULAR = '$valor'
                            WHERE COD_SENHAPARC = $cod_senhaparc
                            AND COD_EMPRESA = $cod_empresa";

        mysqli_query($connAdm->connAdm(), $sqlUpdate);

        echo json_encode(['status' => 'success']);
    }
} else {
    echo json_encode(['status' => 'error', 'mensagem' => 'Celular inválido. <br> O número deve começar com 55 e conter o total de 13 digitos.']);
}
