<?php
include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

//echo fnDebug('true');

$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];
$pagina = $_GET['idPage'];
$cod_empresa = fnDecode($_GET['id']);

$cod_univend = $_POST['COD_UNIVEND'];
$cod_grupotr = $_REQUEST['COD_GRUPOTR'];
$cod_tiporeg = $_REQUEST['COD_TIPOREG'];
$casasDec = $_REQUEST['CASAS_DEC'];
$dat_ini = fnDataSql($_POST['DAT_INI']);
$dat_fim = fnDataSql($_POST['DAT_FIM']);
$lojasSelecionadas = $_POST['LOJAS'];
$condicaoCartao = $_GET['condicaoCartao'];
$andNome = $_GET['andNome'];
$andCreditos = $_GET['andCreditos'];
$andDataRetro = $_GET['andDataRetro'];
$condicaoVendaPDV = $_GET['condicaoVendaPDV'];
$autoriza = fnLimpaCampoZero($_POST['AUTORIZA']);

$tip_ordenac = fnLimpaCampoZero($_POST['TIP_ORDENAC']);

if (empty($_REQUEST['LOG_ALTERAC'])) {
    $log_alterac = 'N';
} else {
    $log_alterac = $_REQUEST['LOG_ALTERAC'];
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = fnDataSql($hoje);
}
if (strlen($cod_univend) == 0) {
    $cod_univend = "9999";
}
//faz pesquisa por revenda (geral)
if ($cod_univend == "9999") {
    $temUnivend = "N";
} else {
    $temUnivend = "S";
}

if ($tip_ordenac == 1) {
    $orderBy = "ORDER BY VAL_TOTVENDA DESC";
} else if ($tip_ordenac == 2) {
    $orderBy = "ORDER BY VAL_CREDITOS DESC";
} else {
    $orderBy = "ORDER BY A.DAT_ALTERAC_WS DESC";
}

if ($cod_empresa == 19) {
    $selectPlaca = "(SELECT MAX(DES_PLACA) FROM VEICULOS WHERE COD_CLIENTE = CL.COD_CLIENTE) AS DES_PLACA,";
} else {
    $selectPlaca = "";
}

if ($nom_cliente != '') {
    $andNome = 'AND CL.NOM_CLIENTE LIKE "' . $nom_cliente . '%"';
} else {
    $andNome = ' ';
}

if ($des_placa != '') {
    $andPlaca = 'AND CL.COD_CLIENTE = (SELECT COD_CLIENTE FROM VEICULOS WHERE DES_PLACA = "' . $des_placa . '")';
} else {
    $andPlaca = ' ';
}

if ($num_cartao != '') {
    $andCartao = 'AND CL.NUM_CARTAO=' . $num_cartao;
} else {
    $andCartao = ' ';
}

if ($num_cgcecpf != '') {
    $andCpf = 'AND CL.NUM_CGCECPF =' . $num_cgcecpf;
} else {
    $andCpf = ' ';
}

if ($cod_univend != '') {
    $andLojas = 'AND CL.COD_UNIVEND  IN  (0,' . $lojasSelecionadas . ')';
} else {
    $andLojas = ' ';
}

if ($log_funcionario == 'S') {
    $andFuncionarios = " AND CL.LOG_FUNCIONA = 'S' ";
} else {
    $andFuncionarios = "";
}

// if ($dat_ini == "") {
//     $andDatIni = "AND CL.DAT_ALTERAC IS NOT NULL ";
// } else {
//     $andDatIni = "AND DATE_FORMAT(CL.DAT_ALTERAC, '%Y-%m-%d') >= '$dat_ini' ";
// }

// if ($dat_fim == "") {
//     $andDatFim = "AND CL.DAT_ALTERAC IS NOT NULL ";
// } else {
//     $andDatFim = "AND DATE_FORMAT(CL.DAT_ALTERAC, '%Y-%m-%d') <= '$dat_fim' ";
// }

if ($log_alterac == 'S') {

    $andAlterac = "AND CL.DAT_ALTERAC BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'";
    $andDatIni = "";
    $andDatFim = "";
} else {

    $andAlterac = "AND LOG_TERMO = 'S'";

    if ($dat_ini == "") {
        $andDatIni = "";
    } else {
        $andDatIni = "AND DATE_FORMAT(LC.DAT_ATIV, '%Y-%m-%d') >= '$dat_ini' ";
    }

    if ($dat_fim == "") {
        $andDatFim = "";
    } else {
        $andDatFim = "AND DATE_FORMAT(LC.DAT_ATIV, '%Y-%m-%d') <= '$dat_fim' ";
    }
}

switch ($opcao) {
    case 'exportar':

        $nomeRel = $_GET['nomeRel'];
        $arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        $writer = WriterFactory::create(Type::CSV);
        $writer->setFieldDelimiter(';');
        $writer->openToFile($arquivo);

        // Filtro por Grupo de Lojas
        include "filtroGrupoLojas.php";

        //============================
        /* $ARRAY_UNIDADE1 = array(
            'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa",
            'cod_empresa' => $cod_empresa,
            'conntadm' => $connAdm->connAdm(),
            'IN' => 'N',
            'nomecampo' => '',
            'conntemp' => '',
            'SQLIN' => ""
        );
        $ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);
        * 
        */
        $ARRAY_VENDEDOR1 = array(
            'sql' => "select COD_USUARIO ,COD_USUARIO as COD_ATENDENTE,COD_USUARIO as COD_VENDEDOR ,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa",
            'cod_empresa' => $cod_empresa,
            'conntadm' => $connAdm->connAdm(),
            'IN' => 'N',
            'nomecampo' => '',
            'conntemp' => '',
            'SQLIN' => ""
        );
        $ARRAY_VENDEDOR = fnUniVENDEDOR($ARRAY_VENDEDOR1);

        $sql = "SELECT CL.COD_CLIENTE CODIGO, 
                       CL.NOM_CLIENTE NOME,
                       CL.DAT_CADASTR CADASTRO,
                       CL.DAT_ALTERAC ALTERACAO,
                       LC.DAT_ATIV ATIVACAO,
                       CL.DAT_ULTCOMPR ULTIMA_COMPRA,
                       UV.NOM_FANTASI 'LOJA CADASTRO',
                       USU.NOM_USUARIO VENDEDOR,
                       CL.NUM_CARTAO CARTAO,
                       CL.NUM_CGCECPF CPF,
                       CL.DAT_NASCIME NASCIMENTO,
                       CL.COD_SEXOPES SEXO,
                       CL.NUM_TELEFON TELEFONE,
                       CL.NUM_CELULAR CELULAR,
                       CL.DES_EMAILUS EMAIL,
                    
                    (SELECT ifnull(SUM(VAL_SALDO),0)
                       FROM CREDITOSDEBITOS CDB
                      WHERE CDB.COD_CLIENTE=CL.COD_CLIENTE 
                        AND TIP_CREDITO='C' 
                        AND COD_STATUSCRED=1 
                        AND (DATE_FORMAT(DAT_EXPIRA, '%Y-%m-%d')  >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(LOG_EXPIRA='N'))
                        AND COD_EMPRESA = $cod_empresa ) AS VAL_SALDO,
                    (SELECT ifnull(SUM(VAL_SALDO),0)
                       FROM CREDITOSDEBITOS CDB
                      WHERE CDB.COD_CLIENTE=CL.COD_CLIENTE 
                        AND TIP_CREDITO='C' 
                        AND COD_STATUSCRED IN (3,7)
                        AND (DATE_FORMAT(DAT_EXPIRA, '%Y-%m-%d')  >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(LOG_EXPIRA='N'))
                        AND COD_EMPRESA = $cod_empresa ) AS SALDO_BLOQUEADO

              FROM CLIENTES CL
			  LEFT JOIN USUARIOS USU ON USU.COD_USUARIO = CL.COD_VENDEDOR
              LEFT JOIN LOG_CANAL LC ON LC.COD_CLIENTE = CL.COD_CLIENTE
              LEFT JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = CL.COD_UNIVEND
			  WHERE CL.COD_EMPRESA = $cod_empresa
              AND (CL.COD_UNIVEND IN ($lojasSelecionadas) OR CL.COD_UNIVEND IS NULL OR CL.COD_UNIVEND = 0)
                $andNome
                $andPlaca
                $andCartao
                $andCpf
                $andAlterac
                $andInativos
                $andDatIni
                $andDatFim
              ORDER BY CL.NOM_CLIENTE";

        // fnEscreve($sql);

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $array = array();
        while ($row = mysqli_fetch_assoc($arrayQuery)) {
            $newRow = array();

            $cont = 0;
            foreach ($row as $objeto) {

                // Colunas que são double converte com fnValor
                if ($cont == 15 || $cont == 16) {

                    array_push($newRow, fnValor($objeto, $casasDec));
                } else if ($cont == 11) {

                    switch ($objeto) {
                        case 1:
                            $sexo = "Masculino";
                            break;

                        case 2:
                            $sexo = "Feminino";
                            break;

                        default:
                            $sexo = "Não Informado";
                            break;
                    }

                    array_push($newRow, $sexo);
                } else {

                    array_push($newRow, $objeto);
                }

                $cont++;
            }
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

        //paginação
        $sql = "SELECT COUNT(CL.COD_CLIENTE) AS CONTADOR FROM  " . connTemp($cod_empresa, 'true') . ".CLIENTES CL
                LEFT JOIN LOG_CANAL LC ON LC.COD_CLIENTE = CL.COD_CLIENTE AND CL.COD_EMPRESA=LC.COD_EMPRESA AND CL.COD_UNIVEND=LC.COD_UNIVEND
                WHERE CL.COD_EMPRESA = " . $cod_empresa . "
                AND CL.COD_UNIVEND  IN  ($lojasSelecionadas)
                  " . $andCodigo . "
                  " . $andNome . "
                  " . $andPlaca . "
                  " . $andCartao . "
                  " . $andCpf . "
                  " . $andAlterac . "
                  " . $andInativos . "
                  ORDER BY NOM_CLIENTE ";

        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
        $total_itens_por_pagina = mysqli_fetch_assoc($retorno);

        $numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

        //variavel para calcular o início da visualização com base na página atual
        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

        //lista de clientes
        $sql = "SELECT CL.*,uni.NOM_FANTASI, $selectPlaca
                      (SELECT ifnull(SUM(VAL_SALDO),0)
                         FROM CREDITOSDEBITOS CDB
                        WHERE CDB.COD_CLIENTE=CL.COD_CLIENTE 
                          AND TIP_CREDITO='C' 
                          AND COD_STATUSCRED=1 
                          AND (DATE_FORMAT(DAT_EXPIRA, '%Y-%m-%d')  >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(LOG_EXPIRA='N'))
                          AND COD_EMPRESA = $cod_empresa ) AS VAL_SALDO,
                      (SELECT ifnull(SUM(VAL_SALDO),0)
                         FROM CREDITOSDEBITOS CDB
                        WHERE CDB.COD_CLIENTE=CL.COD_CLIENTE 
                          AND TIP_CREDITO='C' 
                          AND COD_STATUSCRED IN (3,7)
                          AND (DATE_FORMAT(DAT_EXPIRA, '%Y-%m-%d')  >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(LOG_EXPIRA='N'))
                          AND COD_EMPRESA = $cod_empresa ) AS SALDO_BLOQUEADO,
                      USU.NOM_USUARIO,
                      CL.DAT_ALTERAC,
                      LC.DAT_ATIV
                FROM CLIENTES CL
                LEFT JOIN USUARIOS USU ON USU.COD_USUARIO = CL.COD_VENDEDOR
                LEFT JOIN LOG_CANAL LC ON LC.COD_CLIENTE = CL.COD_CLIENTE
                LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND = CL.COD_UNIVEND
                WHERE CL.COD_EMPRESA = $cod_empresa
                AND (CL.COD_UNIVEND IN ($lojasSelecionadas) OR CL.COD_UNIVEND IS NULL OR CL.COD_UNIVEND = 0)
                  $andNome
                  $andPlaca
                  $andCartao
                  $andCpf
                  $andAlterac
                  $andInativos
                  $andDatIni
                  $andDatFim
                ORDER BY CL.NOM_CLIENTE LIMIT $inicio,$itens_por_pagina";

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
        //fnEscreve($sql);
        //  echo "___".$sql."___";
        $count = 0;
        while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {

            $log_funciona = $qrListaEmpresas['LOG_FUNCIONA'];
            if ($log_funciona == "S") {
                $mostraCracha = '<i class="fa fa-address-card" aria-hidden="true"></i>';
            } else {
                $mostraCracha = "";
            }

            if ($cod_empresa == 19) {
                $mostraPlaca = "<td class='text-center'><small>" . $qrListaEmpresas['DES_PLACA'] . "</small></td>";
            } else {
                $mostraPlaca = "";
            }

            if ($qrListaEmpresas['COD_UNIVEND'] != 0) {
                $unidade = $qrListaEmpresas['NOM_FANTASI'];
            } else {
                $unidade = "Sem unidade";
            }

            if ($log_alterac == 'S') {
                $dataFiltro = $qrListaEmpresas['DAT_ALTERAC'];
            } else {
                $dataFiltro = $qrListaEmpresas['DAT_ATIV'];
            }

            if ($qrListaEmpresas['DAT_ATIV'] != "") {
                $termos = "<span class='fal fa-check text-success'></span>";
            } else {
                $termos = "";
            }

            $count++;

            if ($autoriza == 1) {
                $colCliente = "<td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrListaEmpresas['NOM_CLIENTE']) . "&nbsp;" . $mostraCracha . "</a></small></td>";
            } else {
                $colCliente = "<td><small>" . fnMascaraCampo($qrListaEmpresas['NOM_CLIENTE']) . "&nbsp;" . $mostraCracha . "</small></td>";
            }

            echo "
                <tr>
                <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
                <td><small>" . $qrListaEmpresas['COD_CLIENTE'] . "</small></td>
                " . $colCliente . "
                <td><small>" . fnDataFull($qrListaEmpresas['DAT_CADASTR']) . "</small></td>
                <td><small>" . fnDataFull($dataFiltro) . "</small></td>
                <td><small>" . fnDataFull($qrListaEmpresas['DAT_ULTCOMPR']) . "</small></td>
                <td><small>" . $unidade . "</small></td>
                <td> <small>" . $qrListaEmpresas['NOM_USUARIO'] . "</small></td>
                <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CARTAO']) . "</small></td>
                <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CGCECPF']) . "</small></td>
                <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_TELEFON']) . "</small></td>
                <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CELULAR']) . "</small></td>
                <td><small>" . fnMascaraCampo(strtolower($qrListaEmpresas['DES_EMAILUS'])) . "</small></td>
                $mostraPlaca
                <td class='text-right'><small>R$ " . fnValor($qrListaEmpresas['VAL_SALDO'], 2) . "</small></td>
                <td class='text-right'><small>R$ " . fnValor($qrListaEmpresas['SALDO_BLOQUEADO'], 2) . "</small></td>
                <td class='text-center'><small>" . $termos . "</small></td>
                </tr>
                <input type='hidden' id='ret_COD_CLIENTE_" . $count . "' value='" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "'>
                <input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . fnEncode($cod_empresa) . "'>
                ";
        }
        break;
}
