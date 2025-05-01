<?php

include '../_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$opcao = "";
$dat_ini = "";
$dat_fim = "";
$num_cgcecpf = "";
$lojasSelecionadas = "";
$cod_campanha = "";
$num_celular = "";
$autoriza = "";
$log_optout = "";
$log_retorno = "";
$dias30 = "";
$hoje = "";
$andCpf = "";
$andCelular = "";
$andCampanha = "";
$andData = "";
$curl = "";
$response = "";
$err = "";
$nomeRel = "";
$arquivo = "";
$writer = "";
$arrayQuery = [];
$array = [];
$row = "";
$newRow = "";
$objeto = "";
$verdadeiro = "";
$arrayColumnsNames = [];
$retorno = "";
$totalitens_por_pagina = "";
$inicio = "";
$qrRetorno = "";
$recebido = "";
$confirmacao = "";
$bounce = "";
$optout = "";
$colCliente = "";

require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;


$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$cod_enviado = fnLimpaCampo(@$_REQUEST['COD_ENVIADO']);
$num_cgcecpf = fnLimpaCampo(fnLimpaDoc(@$_REQUEST['NUM_CGCECPF']));
$lojasSelecionadas = @$_POST['LOJAS'];
$cod_campanha = fnLimpaCampoZero(@$_POST['COD_CAMPANHA']);
$num_celular = fnLimpaCampo(@$_REQUEST['NUM_CELULAR']);
$autoriza = fnLimpaCampoZero(@$_POST['AUTORIZA']);

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = fnDataSql($hoje);
}

if ($num_cgcecpf != '' && $num_cgcecpf != 0) {
    $andCpf = "AND CL.NUM_CGCECPF = '$num_cgcecpf'";
} else {
    $andCpf = "";
}

if ($num_celular != '' && $num_celular != 0) {
    $andCelular = "AND SLR.NUM_CELULAR = '" . fnLimpaDoc($num_celular) . "'";
} else {
    $andCelular = "";
}

if ($cod_campanha != 0 && $cod_campanha != '') {
    $andCampanha = "AND SLR.COD_CAMPANHA = $cod_campanha";
} else {
    $andCampanha = "";
}

if ($cod_enviado != '') {
    $andEnviado = "AND SLR.COD_ENVIADO = '$cod_enviado'";
} else {
    $andEnviado = "";
}

switch ($opcao) {

    case 'exportar':

        $nomeRel = @$_GET['nomeRel'];
        $arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        $writer = WriterFactory::create(Type::CSV);
        $writer->setFieldDelimiter(';');
        $writer->openToFile($arquivo);

        // Filtro por Grupo de Lojas
        include "filtroGrupoLojas.php";

        $sql = "SELECT  SLR.DT_CADASTR,
        CL.COD_CLIENTE,
        SLR.NOM_CLIENTE,
        CL.NUM_CGCECPF,
        SLR.COD_ENVIADO,
        SLR.NUM_CELULAR,
        CP.DES_CAMPANHA, 
        UV.NOM_FANTASI 
        FROM EMAIL_FILA SLR
        INNER JOIN CAMPANHA CP ON CP.COD_CAMPANHA = SLR.COD_CAMPANHA
        LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = SLR.COD_CLIENTE
        left JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = SLR.COD_UNIVEND
        WHERE SLR.COD_EMPRESA = $cod_empresa
        AND SLR.DT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
        AND SLR.COD_UNIVEND IN(0,$lojasSelecionadas)
        $andCpf
        $andEnviado
        $andCelular
        $andCampanha
        ORDER BY SLR.DT_CADASTR DESC";

        //fnEscreve($sql);

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $array = array();
        while ($row = mysqli_fetch_assoc($arrayQuery)) {
            $newRow = array();

            $cont = 0;
            foreach ($row as $objeto) {

                $verdadeiro = "";

                if ($cont == 7) {

                    $verdadeiro = "N";

                    if ($objeto == 0) {

                        $verdadeiro = "S";
                    }

                    array_push($newRow, $verdadeiro);
                } else if ($cont >= 8 && $cont <= 10) {

                    if ($objeto == 1) {

                        $verdadeiro = "S";
                    }

                    array_push($newRow, $verdadeiro);
                } else {

                    array_push($newRow, $objeto);
                }
            }

            $cont++;
            $array[] = $newRow;
        }

        $arrayColumnsNames = array();
        while ($row = mysqli_fetch_field($arrayQuery)) {
            array_push($arrayColumnsNames, $row->name);
        }

        $writer->addRow($arrayColumnsNames);
        $writer->addRows($array);

        $writer->close();

        break;
    case 'paginar':

        // Filtro por Grupo de Lojas
        include "../filtroGrupoLojas.php";

        $sql = "SELECT SLR.ID_FILA FROM EMAIL_FILA SLR
        INNER JOIN CAMPANHA CP ON CP.COD_CAMPANHA = SLR.COD_CAMPANHA
        left JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = SLR.COD_UNIVEND
        WHERE SLR.COD_EMPRESA = $cod_empresa
        AND SLR.DT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
        AND SLR.COD_UNIVEND IN(0,$lojasSelecionadas)
        $andCampanha
        $andCpf
        $andEnviado
        $andCelular";
        //fnTestesql(connTemp($cod_empresa,''),$sql);		
        //fnEscreve($sql);

        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
        $totalitens_por_pagina = mysqli_num_rows($retorno);

        $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

        //variavel para calcular o início da visualização com base na página atual
        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

        $sql = "SELECT  SLR.DT_CADASTR,
        CL.COD_CLIENTE,
        SLR.NOM_CLIENTE,
        CL.NUM_CGCECPF,
        SLR.COD_ENVIADO,
        SLR.NUM_CELULAR,
        CP.DES_CAMPANHA, 
        UV.NOM_FANTASI 
        FROM EMAIL_FILA SLR
        INNER JOIN CAMPANHA CP ON CP.COD_CAMPANHA = SLR.COD_CAMPANHA
        LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = SLR.COD_CLIENTE
        left JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = SLR.COD_UNIVEND
        WHERE SLR.COD_EMPRESA = $cod_empresa
        AND SLR.DT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
        AND SLR.COD_UNIVEND IN(0,$lojasSelecionadas)
        $andCpf
        $andCelular
        $andEnviado
        $andCampanha
        ORDER BY SLR.DT_CADASTR DESC
        LIMIT $inicio, $itens_por_pagina";

        // fnEscreve($sql);

        //fnTestesql(connTemp($cod_empresa,''),$sql);											
        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $count = 0;
        while ($qrRetorno = mysqli_fetch_assoc($arrayQuery)) {

            $recebido = "<span class='fal fa-times text-danger'></span>";

            if ($qrRetorno['COD_ENVIADO'] == 'S') {
                $recebido = "<span class='fal fa-check'></span>";
            }

            $count++;

            if ($autoriza == 1) {
                $colCliente = "<td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrRetorno['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrRetorno['NOM_CLIENTE']) . "</a></small></td>";
            } else {
                $colCliente = "<td><small>" . fnMascaraCampo($qrRetorno['NOM_CLIENTE']) . "</small></td>";
            }

            echo "
					<tr>
					  " . $colCliente . "
					  <!-- <td><small>" . fnMascaraCampo($qrRetorno['NUM_CGCECPF']) . "</small></td> -->
						<td><small>" . $qrRetorno['NUM_CGCECPF'] . "</small></td>
					  <td><small class='sp_celphones'>" . $qrRetorno['NUM_CELULAR'] . "</small></td>
					  <td><small>" . $qrRetorno['DES_CAMPANHA'] . "</small></td>
					  <td><small>" . $qrRetorno['NOM_FANTASI'] . "</small></td>
					  <td class='text-center'><small>" . fnDataFull($qrRetorno['DT_CADASTR']) . "</small></td>
					  <td class='text-center'><small>" . $recebido . "</small></td>
					</tr>
					";
        }

        break;
}
