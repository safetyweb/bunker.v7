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

        $sql = "SELECT 1
        FROM FORMAPAGAMENTO where LOG_ATIVO='S' and COD_EMPRESA = '" . $cod_empresa . "' order by DES_FORMAPA";

        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
        $totalitens_por_pagina = mysqli_num_rows($retorno);

        $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

        //variavel para calcular o início da visualização com base na página atual
        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

        // ================================================================================

        $sql = "select * from 
        FORMAPAGAMENTO 
        where LOG_ATIVO='S' 
        and COD_EMPRESA = '" . $cod_empresa . "' 
        order by DES_FORMAPA
        LIMIT $inicio, $itens_por_pagina";
        $arrayQuery = mysqli_query($conn, $sql);

        $count = 0;
        while ($qrBuscaPagamento = mysqli_fetch_assoc($arrayQuery)) {
            $count++;

            if ($qrBuscaPagamento['LOG_PONTUAR'] == "S") {
                $pontuarAtivo = '<span class="fas fa-check text-success"></span>';
            } else {
                $pontuarAtivo = '<span class="fas fa-times text-danger"></span>';
            }

            echo "
        <tr>
          <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
          <td>" . $qrBuscaPagamento['COD_FORMAPA'] . "</td>
          <td>" . $qrBuscaPagamento['DES_FORMAPA'] . "</td>
          <td>" . $qrBuscaPagamento['COD_EXTERNO'] . "</td>
          <td class='text-center'>" . $pontuarAtivo . "</td>
        </tr>
        <input type='hidden' id='ret_COD_FORMAPA_" . $count . "' value='" . $qrBuscaPagamento['COD_FORMAPA'] . "'>
        <input type='hidden' id='ret_DES_FORMAPA_" . $count . "' value='" . $qrBuscaPagamento['DES_FORMAPA'] . "'>
        <input type='hidden' id='ret_COD_EXTERNO_" . $count . "' value='" . $qrBuscaPagamento['COD_EXTERNO'] . "'>
        <input type='hidden' id='ret_LOG_PONTUAR_" . $count . "' value='" . $qrBuscaPagamento['LOG_PONTUAR'] . "'>
        ";
        }
        break;
}
