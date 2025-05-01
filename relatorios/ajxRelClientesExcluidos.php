<?php
include '../_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$array = "";
$key = "";
$default = "";
$opcao = "";
$itens_por_pagina = "";
$pagina = "";
$cod_empresa = "";
$cod_univend = "";
$lojasSelecionadas = "";
$cod_cliente = "";
$cod_externo = "";
$num_cgcecpf = "";
$dat_ini = "";
$dat_fim = "";
$andCliente = "";
$andExterno = "";
$andDatIni = "";
$andDatFim = "";
$andCpf = "";
$nomeRel = "";
$arquivoCaminho = "";
$sql = "";
$arrayQuery = "";
$arquivo = "";
$headers = "";
$row = "";
$canal = "";
$limpandostring = "";
$textolimpo = "";
$newRow = "";
$cont = "";
$objeto = "";
$usuario = "";
$arrayColumnsNames = "";
$writer = "";
$retorno = "";
$total_itens_por_pagina = "";
$inicio = "";
$qrListaEmpresas = "";

function getInput($array, $key, $default = '')
{
    return isset($array[$key]) ? $array[$key] : $default;
}



require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// definir o numero de itens por pagina
$opcao = getInput($_GET, 'opcao');
$itens_por_pagina = getInput($_GET, 'itens_por_pagina');
$pagina = getInput($_GET, 'idPage');
$cod_empresa = fnDecode(getInput($_GET, 'id'));

$cod_univend = getInput($_POST, 'COD_UNIVEND');
$lojasSelecionadas = getInput($_POST, 'LOJAS');

$cod_cliente = fnLimpacampo($_REQUEST['COD_CLIENTE']);
$cod_externo = fnLimpacampozero($_REQUEST['COD_EXTERNO']);
$num_cgcecpf = fnLimpaDoc(fnLimpacampo($_REQUEST['NUM_CGCECPF']));
$dat_ini = fnDataSql(getInput($_POST, 'DAT_INI'));
$dat_fim = fnDataSql(getInput($_POST, 'DAT_FIM'));

if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = "";
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = "";
}

if ($cod_cliente != 0) {
    $andCliente = "AND A.COD_CLIENTE = $cod_cliente";
} else {
    $andCliente = '';
}

if ($cod_externo != 0) {
    $andExterno = "AND A.COD_EXTERNO = $cod_externo";
} else {
    $andExterno = '';
}

if ($dat_ini == "") {
    $andDatIni = "";
} else {
    $andDatIni = "AND DATE_FORMAT(A.DAT_EXCLUSA, '%Y-%m-%d') >= '$dat_ini' ";
}

if ($dat_fim == "") {
    $andDatFim = "";
} else {
    $andDatFim = "AND DATE_FORMAT(A.DAT_EXCLUSA, '%Y-%m-%d') <= '$dat_fim' ";
}

if ($num_cgcecpf != '') {
    $andCpf = 'AND A.NUM_CGCECPF =' . $num_cgcecpf;
} else {
    $andCpf = ' ';
}

switch ($opcao) {

    case  'exportar':

        $nomeRel = getInput($_GET, 'nomeRel');
        $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';


        $sql = "SELECT A.COD_CLIENTE,
                        A.COD_EXTERNO,
                        A.NUM_CARTAO,
                        A.DAT_EXCLUSA,
                        D.NOM_FANTASI,
                        A.COD_CANAL,
                        A.KEY_EXTERNO,
                        B.NOM_USUARIO,
                        IFNULL((SELECT SUM(VAL_SALDO) FROM CREDITOSDEBITOS C WHERE C.COD_CLIENTE=A.COD_CLIENTE AND A.COD_EMPRESA=C.COD_EMPRESA),0) VAL_SALDO
                        FROM CLIENTES_EXC A 
                        LEFT JOIN USUARIOS B ON A.COD_EXCLUSA=B.COD_USUARIO AND A.COD_EMPRESA=B.COD_EMPRESA
                        LEFT JOIN UNIDADEVENDA D ON D.COD_UNIVEND=A.COD_UNIVEND AND A.COD_EMPRESA=D.COD_EMPRESA
                        WHERE A.COD_EMPRESA = $cod_empresa 
                        AND A.COD_UNIVEND IN($lojasSelecionadas) 
                        $andCpf
                        $andCliente
                        $andExterno
                        $andDatIni
                        $andDatFim
                        ORDER BY A.DAT_EXCLUSA DESC";


        //fnEscreve($sql);

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrayQuery)) {
            $CABECHALHO[] = $headers->name;
        }
        fputcsv($arquivo, $CABECHALHO, ';', '"');

        while ($row = mysqli_fetch_assoc($arrayQuery)) {

            //echo "<pre>";
            //print_r($row);
            //echo "</pre>";

            switch ($row['COD_CANAL']) {

                case 2:
                    $canal = 'Hotsite';
                    $row['COD_CANAL'] = $canal;
                    $row['NOM_USUARIO'] = $canal;
                    break;

                case 3:
                    $canal = 'Totem';
                    $row['COD_CANAL'] = $canal;
                    $row['NOM_USUARIO'] = $canal;
                    break;

                default:
                    $row['COD_CANAL'] = 'Bunker';
                    break;
            }

            $row['VAL_SALDO'] = fnValor($row['VAL_SALDO'], 2);

            //$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
            //$textolimpo = json_decode($limpandostring, true);
            $array = array_map("utf8_decode", $row);
            fputcsv($arquivo, $array, ';', '"');
        }
        fclose($arquivo);
        /*
        $array = array();

        while($row = mysqli_fetch_assoc($arrayQuery)){

            $newRow = array();
              
            $cont = 0;

            foreach ($row as $objeto) {   

                if($cont == 5){

                    switch ($objeto) {

                        case 2:
                            $canal = 'Hotsite';
                        break;

                        case 3:
                            $canal = 'Totem';
                        break;
                        
                        default:
                            $canal = 'Bunker';
                        break;

                    }

                    array_push($newRow, $canal);

                }else if($cont == 7){

                    $usuario = $objeto;

                    switch ($row['COD_CANAL']) {

                        case 2:
                            $usuario = 'Hotsite';
                        break;

                        case 3:
                            $usuario = 'Totem';
                        break;

                    }

                    array_push($newRow, $usuario);

                }else if($cont == 8){

                    array_push($newRow, fnValor($objeto,2));

                }else{               

                    array_push($newRow, $objeto);

                }

                $cont++;

            }                                                       

            $array[] = $newRow;

        }
        
        $arrayColumnsNames = array();

        while($row = mysqli_fetch_field($arrayQuery)){

            array_push($arrayColumnsNames, $row->name);

        }           

        $writer->addRow($arrayColumnsNames);
        $writer->addRows($array);
        $writer->close();
*/
        break;

    case  'paginar':

        $sql = "SELECT 1
                FROM CLIENTES_EXC A 
                WHERE A.COD_EMPRESA = $cod_empresa  
                AND A.COD_UNIVEND IN($lojasSelecionadas)
                $andCpf
                $andCliente
                $andExterno
                $andDatIni
                $andDatFim";

        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
        $total_itens_por_pagina = mysqli_num_rows($retorno);

        $numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

        //variavel para calcular o início da visualização com base na página atual
        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


        $sql = "SELECT A.COD_CLIENTE,
                        A.COD_EXTERNO,
                        A.NUM_CARTAO,
                        A.COD_CANAL,
                        B.NOM_USUARIO,
                        A.DAT_EXCLUSA,
                        A.KEY_EXTERNO,
                        IFNULL((SELECT SUM(VAL_SALDO) FROM CREDITOSDEBITOS C WHERE C.COD_CLIENTE=A.COD_CLIENTE AND A.COD_EMPRESA=C.COD_EMPRESA),0) VAL_SALDO,
                        D.NOM_FANTASI
                        FROM CLIENTES_EXC A 
                        LEFT JOIN USUARIOS B ON A.COD_EXCLUSA=B.COD_USUARIO AND A.COD_EMPRESA=B.COD_EMPRESA
                        LEFT JOIN UNIDADEVENDA D ON D.COD_UNIVEND=A.COD_UNIVEND AND A.COD_EMPRESA=D.COD_EMPRESA
                        WHERE A.COD_EMPRESA = $cod_empresa 
                        AND A.COD_UNIVEND IN($lojasSelecionadas)
                        $andCpf
                        $andCliente
                        $andExterno
                        $andDatIni
                        $andDatFim
                        ORDER BY A.DAT_EXCLUSA DESC 
                        LIMIT $inicio,$itens_por_pagina";

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
        // fnEscreve($sql);
        $count = 0;
        while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {

            $usuario = $qrListaEmpresas['NOM_USUARIO'];

            switch ($qrListaEmpresas['COD_CANAL']) {

                case 2:
                    $canal = 'Hotsite';
                    $usuario = $canal;
                    break;

                case 3:
                    $canal = 'Totem';
                    $usuario = $canal;
                    break;

                default:
                    $canal = 'Bunker';
                    break;
            }

            $count++;

            echo "
                <tr>
                <td><small>" . $qrListaEmpresas['COD_CLIENTE'] . "</small></td>
                <td><small>" . $qrListaEmpresas['COD_EXTERNO'] . "</small></td>
                <td><small>" . $qrListaEmpresas['NUM_CARTAO'] . "</small></td>
                <td><small>" . fnDataFull($qrListaEmpresas['DAT_EXCLUSA']) . "</small></td>
                <td><small>" . $qrListaEmpresas['NOM_FANTASI'] . "</small></td>
                <td><small>" . $canal . "</small></td>
                <td> <small>" . $usuario . "</small></td>
                <td class='text-right'><small>" . fnValor($qrListaEmpresas['VAL_SALDO'], 2) . "</small></td>
                </tr>";
        }

        break;
}
