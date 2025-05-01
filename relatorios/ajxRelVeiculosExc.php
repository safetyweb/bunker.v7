<?php
include '../_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$opcao = "";
$lojasSelecionadas = "";
$andCli = "";
$cod_cliente = "";
$cod_externo = "";
$des_placa = "";
$num_cpf = "";
$dat_ini = "";
$dat_fim = "";
$autoriza = "";
$andCliente = "";
$andDatIni = "";
$andDatFim = "";
$andCpf = "";
$andPlaca = "";
$nomeRel = "";
$arquivoCaminho = "";
$arrayQuery = [];
$arquivo = "";
$headers = "";
$row = "";
$limpandostring = "";
$array = [];
$andCli1 = "";
$retorno = "";
$inicio = "";
$qrListaEmpresas = "";
$colCliente = "";



// definir o numero de itens por pagina
$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_univend = @$_REQUEST['COD_UNIVEND'];
$lojasSelecionadas = @$_REQUEST['LOJAS'];
$andCli = fnLimpacampozero(@$_REQUEST['AND_CLI']);


$cod_cliente = fnLimpacampo(@$_REQUEST['COD_CLIENTE']);
$cod_externo = fnLimpacampozero(@$_REQUEST['COD_EXTERNO']);
$des_placa = fnLimpacampo(@$_REQUEST['DES_PLACA']);
$num_cpf = fnLimpacampozero(@$_REQUEST['NUM_CPF']);
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);

$autoriza = fnLimpaCampoZero(@$_REQUEST['AUTORIZA']);

if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = "";
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = "";
}


if ($cod_cliente != 0 && $cod_cliente != '') {
    $andCliente = "AND CL.COD_CLIENTE = $cod_cliente";
} else {
    $andCliente = '';
}

if ($dat_ini == "") {
    $andDatIni = "";
} else {
    $andDatIni = "AND DATE_FORMAT(VL.DAT_EXCLUSA, '%Y-%m-%d') >= '$dat_ini' ";
}

if ($dat_fim == "") {
    $andDatFim = "";
} else {
    $andDatFim = "AND DATE_FORMAT(VL.DAT_EXCLUSA, '%Y-%m-%d') <= '$dat_fim' ";
}

if ($num_cpf == "") {
    $andCpf = "";
} else {
    $andCpf = "AND CL.NUM_CGCECPF='$num_cpf'";
}

if ($des_placa != '' && $des_placa != 0) {
    $andPlaca = "AND VL.DES_PLACA like '%$des_placa%'";
} else {
    $andPlaca = "";
}


switch ($opcao) {

    case  'exportar':

        $nomeRel = @$_GET['nomeRel'];
        $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';


        $sql = "SELECT CL.COD_CLIENTE,CL.NOM_CLIENTE,CL.NUM_CGCECPF,UNI.NOM_FANTASI,VL.* FROM clientes CL
                INNER JOIN veiculos_exec  VL ON VL.COD_CLIENTE_EXT=CL.NUM_CGCECPF
                LEFT JOIN UNIDADEVENDA UNI ON UNI.COD_UNIVEND=CL.COD_UNIVEND
                WHERE CL.COD_EMPRESA=$cod_empresa
                AND UNI.COD_UNIVEND IN ($lojasSelecionadas)
                $andPlaca
                $andCli
                $andCpf
                $andDatIni
                $andDatFim ";


        fnEscreve($sql);

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrayQuery)) {
            $CABECHALHO[] = $headers->name;
        }

        fputcsv($arquivo, $CABECHALHO, ';', '"');

        while ($row = mysqli_fetch_assoc($arrayQuery)) {

            //$limpandostring= fnAcentos(Utf8_ansi(json_encode($row)));
            //$textolimpo=json_decode($limpandostring,true);
            $array = array_map("utf8_decode", $row);
            fputcsv($arquivo, $textolimpo, ';', '"');
        }
        fclose($arquivo);

        break;

    case  'paginar':

        //paginação
        $sql = "SELECT 1 FROM clientes CL
        LEFT JOIN veiculos_exec  VL ON VL.COD_CLIENTE_EXT=CL.NUM_CGCECPF
        LEFT JOIN UNIDADEVENDA UNI ON UNI.COD_UNIVEND = CL.COD.UNIVEND
        LEFT JOIN USUARIOS US ON US.COD_USUARIO = CL.COD_CLIENTE
        WHERE CL.COD_EMPRESA=$cod_empresa
        AND UNI.COD_UNIVEND IN ($lojasSelecionadas)
        $andCli1
        $andCpf
        $andCliente
        $andDatIni
        $andDatFim
        $andPlaca";

        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
        $total_itens_por_pagina = mysqli_num_rows($retorno);
        //fnEscreve($sql);
        $numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

        //variavel para calcular o início da visualização com base na página atual
        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


        $sql = "SELECT CL.COD_CLIENTE,CL.NOM_CLIENTE,CL.NUM_CGCECPF,UNI.NOM_FANTASI,VL.* FROM clientes CL
        INNER JOIN veiculos_exec  VL ON VL.COD_CLIENTE_EXT=CL.NUM_CGCECPF
        LEFT JOIN UNIDADEVENDA UNI ON UNI.COD_UNIVEND=CL.COD_UNIVEND
        WHERE CL.COD_EMPRESA=$cod_empresa
        AND UNI.COD_UNIVEND IN ($lojasSelecionadas)
        $andCli1
        $andCpf
        $andCliente
        $andDatIni
        $andDatFim 
        $andPlaca
        LIMIT $inicio,$itens_por_pagina";

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
        //fnEscreve($sql);
        $count = 0;
        while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {

            $count++;

            if ($autoriza == 1) {
                $colCliente = "<td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrListaEmpresas['NOM_CLIENTE']) . "</a></small></td>";
            } else {
                $colCliente = "<td><small>" . fnMascaraCampo($qrListaEmpresas['NOM_CLIENTE']) . "</small></td>";
            }

            echo "
        <tr>
        <td><small>" . $qrListaEmpresas['COD_CLIENTE'] . "</small></td>
        " . $colCliente . "
        <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CGCECPF'])  . "</small></td>
        <td><small>" . fnDataFull($qrListaEmpresas['DAT_CADASTR']) . "</small></td>
        <td><small>" . fnDataFull($qrListaEmpresas['DAT_EXCLUSA']) . "</small></td>
        <td><small>" . $qrListaEmpresas['NOM_FANTASI'] . "</small></td>
        <td> <small>" . $qrListaEmpresas['DES_PLACA'] . "</small></td>
        </tr>";
        }

        break;
}
