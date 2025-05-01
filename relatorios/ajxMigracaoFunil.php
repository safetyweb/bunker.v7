<?php

include '../_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$des_placa = "";
$lojasSelecionadas = "";
$andCliente = "";
$dat_ini = "";
$dat_fim = "";
$nomeRel = "";
$arquivoCaminho = "";
$arrayQuery = "";
$arquivo = "";
$headers = "";
$row = "";
$limpandostring = "";
$textolimpo = "";
$array = "";
$retorno = "";
$inicio = "";
$qrListaEmpresas = "";



// definir o numero de itens por pagina
$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_univend = @$_POST['COD_UNIVEND'];
$des_placa = fnLimpacampozero(['DES_PLACA']);
$lojasSelecionadas = @$_POST['LOJAS'];
$andCliente = @$_POST['AND_CLIENTE'];

$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);

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


        $sql = "SELECT distinct c.NOM_CLIENTE,

        GROUP_CONCAT( distinct case when a.cod_frequencia=1 then
              'Casual'
                when a.cod_frequencia=2 then
                'Frequente'
                when a.cod_frequencia=3 then
                'Fiel'
                when a.cod_frequencia=4 then
                'Fã'
                
        END SEPARATOR ' -> ') AS frequencia,
        length(GROUP_CONCAT( DISTINCT a.cod_frequencia SEPARATOR ' -> '))frequencia1
        from hitorico_cliente_frequencia a,FECHAMENTO_CLIENTES b,clientes c
        WHERE a.cod_controle=b.cod_controle 
        AND a.COD_CLIENTE=c.cod_cliente 
        AND b.cod_empresa=$cod_empresa 
        AND b.dat_fim >= '$dat_ini' 
        AND b.dat_fim <= '$dat_fim'
        $andCliente  
        GROUP BY a.cod_cliente
        HAVING frequencia1>1
        ORDER BY frequencia DESC";


        //fnEscreve($sql);

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

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
        //paginação
        $sql = "SELECT 
		
                                        1
                                        FROM hitorico_cliente_frequencia a,FECHAMENTO_CLIENTES b,clientes c
                                        WHERE a.cod_controle=b.cod_controle AND 
                                        a.COD_CLIENTE=c.cod_cliente AND 
                                        b.cod_empresa=$cod_empresa
                                        $andCliente
                                        AND b.dat_fim >= '$dat_ini'
                                        AND b.dat_fim <= '$dat_fim'
                                        GROUP BY a.cod_cliente
                                        HAVING LENGTH(GROUP_CONCAT(DISTINCT a.cod_frequencia SEPARATOR ' -> ')) >1";

        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
        $total_itens_por_pagina = mysqli_num_rows($retorno);
        // fnEscreve($total_itens_por_pagina);
        $numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);
        //variavel para calcular o início da visualização com base na página atual
        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


        $sql = "SELECT distinct c.NOM_CLIENTE,

                                        GROUP_CONCAT( distinct case when a.cod_frequencia=1 then
                                              'Casual'
                                                when a.cod_frequencia=2 then
                                                'Frequente'
                                                when a.cod_frequencia=3 then
                                                'Fiel'
                                                when a.cod_frequencia=4 then
                                                'Fã'
                                                
                                        END SEPARATOR ' -> ') AS frequencia,
                                        length(GROUP_CONCAT( DISTINCT a.cod_frequencia SEPARATOR ' -> '))frequencia1
                                        from hitorico_cliente_frequencia a,FECHAMENTO_CLIENTES b,clientes c
                                        WHERE a.cod_controle=b.cod_controle 
                                        AND a.COD_CLIENTE=c.cod_cliente 
                                        AND b.cod_empresa=$cod_empresa 
                                        AND b.dat_fim >= '$dat_ini' 
                                        AND b.dat_fim <= '$dat_fim'
                                        $andCliente  
                                        GROUP BY a.cod_cliente
                                        HAVING frequencia1>1
                                        ORDER BY frequencia DESC
                                        LIMIT $inicio,$itens_por_pagina";

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
        //fnEscreve($sql);
        $count = 0;
        while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {

            $count++;

            echo "
                <tr>
                    <td><small>" . $qrListaEmpresas['NOM_CLIENTE'] . "</small></td>     
                    <td class='text-left'><small>" . $qrListaEmpresas['frequencia']  . "</small></td>
                </tr>";
        }

        break;
}
