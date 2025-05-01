<?php
include '../_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$opcao = "";
$andLoja = "";
$andData = "";
$dat_ini = "";
$dat_fim = "";
$nomeRel = "";
$arquivoCaminho = "";
$arrayQuery = [];
$arquivo = "";
$headers = "";
$row = "";
$limpandostring = "";
$textolimpo = "";
$array = [];
$retorno = "";
$inicio = "";
$qrListaEmpresas = "";

$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

$andLoja = @$_POST['AND_LOJA'];
$andData = @$_POST['AND_DATA'];

if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = "";
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = "";
}


switch ($opcao) {

    case  'exportar':

        $nomeRel = @$_GET['nomeRel'];
        $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';


        $sql = "SELECT  id AS ID,
                        loja AS LOJA,
                        idmaquina AS MAQUINA,
                        codvendedor AS COD_VENDEDOR,
                        nomevendedor AS NOM_VENDEDOR,
                        pagina AS PAGINA,
                        msgerro AS MSG_ERRO
                        from ws_log 
                        WHERE empresa=$cod_empresa
                        $andData
                        $andLoja 
                        AND empresa=$cod_empresa";


        //fnEscreve($sql);

        $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrayQuery)) {
            $CABECHALHO[] = $headers->name;
        }
        fputcsv($arquivo, $CABECHALHO, ';', '"');

        while ($row = mysqli_fetch_assoc($arrayQuery)) {

            //$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
            //$textolimpo = json_decode($limpandostring, true);
            $array = array_map("utf8_decode", $row);
            fputcsv($arquivo, $array, ';', '"');
        }
        fclose($arquivo);
        break;

    case  'paginar':

        //paginação
        $sql = "SELECT 
        1
        from ws_log 
        WHERE empresa=$cod_empresa
        $andData
        $andLoja  
        AND empresa=$cod_empresa";

        $retorno = mysqli_query($connAdm->connAdm(), $sql);
        $total_itens_por_pagina = mysqli_num_rows($retorno);
        //fnEscreve($total_itens_por_pagina);
        $numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);
        //variavel para calcular o início da visualização com base na página atual
        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


        $sql = "SELECT id AS ID,
        loja AS LOJA,
        idmaquina AS MAQUINA,
        codvendedor AS COD_VENDEDOR,
        nomevendedor AS NOM_VENDEDOR,
        pagina AS PAGINA,
        msgerro AS MSG_ERRO
        from ws_log 
        WHERE empresa=$cod_empresa
        $andData
        $andLoja 
        LIMIT $inicio,$itens_por_pagina";

        $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

        $count = 0;
        while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {

            $count++;

            echo "
                <tr>
                    <td>" . $qrListaEmpresas['ID'] . "</td>     
                    <td> " . $qrListaEmpresas['LOJA']  . "</td>
                    <td> " . $qrListaEmpresas['MAQUINA']  . "</td>
                    <td> " . $qrListaEmpresas['COD_VENDEDOR']  . "</td>
                    <td> " . $qrListaEmpresas['NOM_VENDEDOR']  . "</td>
                    <td> " . $qrListaEmpresas['PAGINA']  . "</td>
                    <td> " . $qrListaEmpresas['MSG_ERRO']  . "</td>
                </tr>";
        }

        break;
}
