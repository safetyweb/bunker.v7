<?php

include '_system/_functionsMain.php';

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}


$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);
$conn = conntemp($cod_empresa, "");

switch ($opcao) {
    case 'paginar':

        $sql = "SELECT $connAdm->DB.unidadevenda.NOM_FANTASI, MAQUINAS.* FROM MAQUINAS 
        LEFT JOIN $connAdm->DB.unidadevenda ON $connAdm->DB.unidadevenda.COD_UNIVEND=MAQUINAS.COD_UNIVEND
        WHERE MAQUINAS.COD_EMPRESA = $cod_empresa order by NOM_FANTASI, DES_MAQUINA";
        $retorno = mysqli_query($conn, $sql);

        $totalitens_por_pagina = mysqli_num_rows($retorno);

        $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

        //variavel para calcular o início da visualização com base na página atual
        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

        $sql = "SELECT $connAdm->DB.unidadevenda.NOM_FANTASI, MAQUINAS.* FROM MAQUINAS 
                            LEFT JOIN $connAdm->DB.unidadevenda ON $connAdm->DB.unidadevenda.COD_UNIVEND=MAQUINAS.COD_UNIVEND
                            WHERE MAQUINAS.COD_EMPRESA = $cod_empresa order by NOM_FANTASI, DES_MAQUINA
                            LIMIT $inicio, $itens_por_pagina";

        $arrayQuery = mysqli_query($conn, $sql);

        // fnEscreve($sql);

        $count = 0;
        while ($qrBuscaMaquinas = mysqli_fetch_assoc($arrayQuery)) {
            $count++;
            echo "
                <tr>
                    <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
                    <td>" . $qrBuscaMaquinas['COD_MAQUINA'] . "</td>
                    <td>" . $qrBuscaMaquinas['NOM_FANTASI'] . "</td>
                    <td>" . $qrBuscaMaquinas['DES_MAQUINA'] . "</td>
                </tr>
                <input type='hidden' id='ret_COD_MAQUINA_" . $count . "' value='" . $qrBuscaMaquinas['COD_MAQUINA'] . "'>
                <input type='hidden' id='ret_DES_MAQUINA_" . $count . "' value='" . $qrBuscaMaquinas['DES_MAQUINA'] . "'>
                <input type='hidden' id='ret_COD_UNIVEND_" . $count . "' value='" . $qrBuscaMaquinas['COD_UNIVEND'] . "'>
                ";
        }
        break;
}
