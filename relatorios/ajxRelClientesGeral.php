<?php

include '../_system/_functionsMain.php';

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$log_univend = 'N';

$opcao = @$_GET['opcao'];
$cod_empresa = fnDecode(@$_GET['id']);
$univend = fnDecode(@$_GET['unv']);
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];

$andCodigo = '';

$canal_cadastro = fnLimpaCampoZero(@$_REQUEST['CANAL_CADASTRO']);
$cod_univend = $_POST['COD_UNIVEND'];
$lojasSelecionadas = $_POST['LOJAS'];
@$nom_cliente = fnLimpaCampo($_POST['NOM_CLIENTE']);
@$des_placa = fnLimpacampo($_REQUEST['DES_PLACA']);
@$num_cartao = fnLimpaCampo($_POST['NUM_CARTAO']);
@$num_cgcecpf = fnLimpaDoc(fnLimpaCampo($_POST['NUM_CGCECPF']));
@$num_celular = fnLimpaCampo($_POST['NUM_CELULAR']);
$dat_ini = (@$_POST['DAT_INI'] == "" ? "" : fnDataSql(@$_POST['DAT_INI']));
$dat_fim = (@$_POST['DAT_FIM'] == "" ? "" : fnDataSql(@$_POST['DAT_FIM']));

if (empty($_REQUEST['LOG_UNIVEND'])) {
    $log_univend = 'N';
} else {
    $log_univend = $_REQUEST['LOG_UNIVEND'];
}

if (empty($_POST['LOG_FUNCIONARIO'])) {
    $log_funcionario = 'N';
} else {
    $log_funcionario = $_POST['LOG_FUNCIONARIO'];
}
if (empty($_REQUEST['LOG_INATIVOS'])) {
    $log_inativos = 'N';
} else {
    $log_inativos = $_REQUEST['LOG_INATIVOS'];
}
if (empty($_REQUEST['LOG_MASTER'])) {
    $log_master = 'N';
} else {
    $log_master = $_REQUEST['LOG_MASTER'];
}

if (empty($_REQUEST['LOG_CAMPANHA'])) {
    $log_campanha = 'N';
} else {
    $log_campanha = $_REQUEST['LOG_CAMPANHA'];
}

if (empty($_REQUEST['LOG_CELULAR'])) {
    $log_celular = 'N';
} else {
    $log_celular = $_REQUEST['LOG_CELULAR'];
}


if ($cod_empresa != 0) {

    if ($cod_empresa == 19) {
        $selectPlaca = "(SELECT MAX(DES_PLACA) FROM VEICULOS WHERE COD_CLIENTE = CL.COD_CLIENTE) AS DES_PLACA,";
    } else {
        $selectPlaca = "";
    }

    if ($nom_cliente != '') {
        $andNome = 'and cl.nom_cliente like "' . $nom_cliente . '%"';
    } else {
        $andNome = ' ';
    }

    if ($cod_univend == 9999) {
        if ($log_univend == "N") {
            $andUnidades = "AND (CL.COD_UNIVEND IN (" . $lojasSelecionadas . ") OR CL.COD_UNIVEND IS NULL OR CL.COD_UNIVEND = 0)";
        } else {
            // $andUnidades = "AND (CL.COD_UNIVEND IS NULL OR CL.COD_UNIVEND = 0)";
            $andUnidades = "
                            -- AND CL.COD_UNIVEND IN (" . $lojasSelecionadas . ")
                            AND 
                              case when CL.COD_UNIVEND IS NULL then '1'
                                   when CL.COD_UNIVEND = '0' then '1'
                                   when CL.COD_UNIVEND > '0' then '1'
                              ELSE '0' END IN (1)";
        }
    } else {
        if ($log_univend == "N") {
            $andUnidades = "AND CL.COD_UNIVEND IN (" . $lojasSelecionadas . ")";
        } else {
            // $andUnidades = "AND (CL.COD_UNIVEND IS NULL OR CL.COD_UNIVEND = 0)";
            $andUnidades = "
                            -- AND CL.COD_UNIVEND IN (" . $lojasSelecionadas . ")
                            AND 
                              case when CL.COD_UNIVEND IS NULL then '1'
                                   when CL.COD_UNIVEND = '0' then '1'
                                   when CL.COD_UNIVEND > '0' then '1'
                              ELSE '0' END IN (1)";
        }
    }

    if ($des_placa != '') {
        $andPlaca = 'AND CL.COD_CLIENTE = (SELECT COD_CLIENTE FROM VEICULOS WHERE DES_PLACA = "' . $des_placa . '")';
    } else {
        $andPlaca = ' ';
    }

    if ($num_cartao != '') {
        $andCartao = 'and cl.num_cartao=' . $num_cartao;
    } else {
        $andCartao = ' ';
    }

    if ($num_cgcecpf != '') {
        $andCpf = 'and cl.num_cgcecpf =' . $num_cgcecpf;
    } else {
        $andCpf = ' ';
    }

    if ($cod_univend != '') {
        $andLojas = 'and cl.cod_univend  in  (0,' . $lojasSelecionadas . ')';
    } else {
        $andLojas = ' ';
    }

    if ($log_funcionario == 'S') {
        $andFuncionarios = " AND cl.LOG_FUNCIONA = 'S' ";
    } else {
        $andFuncionarios = ' ';
    }

    if ($log_inativos == "S") {
        $checkInativos = "checked";
        $andInativos = "AND CL.LOG_ESTATUS = 'N'";
    } else {
        $checkInativos = "";
        $andInativos = "";
    }

    if ($log_master == 'S') {
        $andMaster = " AND CL.LOG_MASTER = 'S' ";
    } else {
        $andMaster = "";
    }

    if ($log_campanha == 'S') {
        $andCampanha = "AND CL.COD_CLIENTE in (SELECT DISTINCT cod_cliente  FROM CREDITOSDEBITOS    WHERE COD_CAMPANHA in   (SELECT cap.COD_CAMPANHA FROM campanha cap
      LEFT JOIN campanharegra reg ON reg.COD_CAMPANHA=cap.COD_CAMPANHA
      WHERE cap.tip_campanha IN (22,23) AND cap.COD_EMPRESA=$cod_empresa))";
    } else {
        $andCampanha = "";
    }

    if ($dat_ini == "") {
        $andDatIni = " ";
    } else {
        $andDatIni = "AND DATE_FORMAT(CL.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' ";
    }

    if ($dat_fim == "") {
        $andDatFim = " ";
    } else {
        $andDatFim = "AND DATE_FORMAT(CL.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim' ";
    }

    if ($log_celular == "S") {
        if ($dat_ini != "") {
            $andDat = "AND sub_cl.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'";
            $andDatSub = "AND DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'";
        } else {
            $andDat = "";
            $andDatSub = "";
        }

        $andLogCel = "AND CL.num_celular IN (
                      SELECT num_celular
                      FROM clientes
                      WHERE cod_empresa = $cod_empresa
                      AND cod_univend IN (null,0," . $lojasSelecionadas . ")
                      $andDatSub
                      GROUP BY num_celular
                      HAVING COUNT(*) > 2
                  )
      ";
        $order = "ORDER BY CL.NUM_CELULAR";
    } else {
        $andLogCel = "";
        $order = "ORDER BY CL.NOM_CLIENTE";
    }

    if ($num_celular != "") {
        $andCelular = "AND cl.num_celular = $num_celular";
    } else {
        $andCelular = "";
    }

    if ($canal_cadastro != "") {
        $andCanal = "AND LC.COD_CANAL = $canal_cadastro";
    } else {
        $andCanal = "";
    }



    switch ($opcao) {
        case 'exportar':

            $nomeRel = $_GET['nomeRel'];
            $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';


            $sql = "SELECT  CL.COD_CLIENTE,
                      CL.NOM_CLIENTE,
                      CL.DAT_CADASTR,
                      CL.DAT_ULTCOMPR,
                      uni.NOM_FANTASI,
                      USU.NOM_USUARIO AS VENDEDOR,
                      CL.NUM_TELEFON,
                      CL.NUM_CELULAR,
                      CL.NUM_CARTAO,
                      CL.NUM_CGCECPF AS 'CPF/CNPJ',
                      CL.DES_EMAILUS AS EMAIL,
                      CL.DES_ENDEREC AS ENDERECO,
                      CL.NUM_ENDEREC AS NUMERO,
                      CL.DES_BAIRROC AS BAIRRO,
                      CL.NUM_CEPOZOF AS CEP,
                      CL.NOM_CIDADEC AS CIDADE,
                      CL.COD_ESTADOF AS ESTADO,
                      CL.MES AS MES,
                      CL.DAT_NASCIME AS DAT_NASCIME,
                      LC.COD_CANAL AS CANAL_CADASTRO,
                      SUM(CASE WHEN ven.VAL_TOTPRODU > 0 THEN 1 ELSE 0 END) AS QTD_RESGATE,
                      COALESCE(SUM(ven.VAL_TOTPRODU), 0.0) AS VAL_RESGATADO,
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
                  LEFT JOIN VENDAS ven ON ven.COD_CLIENTE = CL.COD_CLIENTE
                  LEFT JOIN USUARIOS USU ON USU.COD_USUARIO = CL.COD_ATENDENTE
                  LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=CL.COD_UNIVEND
                  LEFT JOIN LOG_CANAL AS LC ON LC.COD_CLIENTE = CL.COD_CLIENTE AND CL.COD_EMPRESA=LC.COD_EMPRESA
                  WHERE CL.COD_EMPRESA = $cod_empresa
                  $andUnidades
                  $andCampanha
                  " . $andNome . "
                  " . $andCartao . "
                  " . $andCpf . "
                  " . $andFuncionarios . "
                  " . $andInativos . "
                  " . $andCanal . "
                  " . $andMaster . "
                      $andDatIni
                      $andDatFim
                      $andPlaca
                  " . $andLogCel . "
                  " . $andCelular . "
                GROUP BY CL.COD_CLIENTE
                $order";
            //fnEscreve($sql);
            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));

            $arquivo = fopen($arquivoCaminho, 'w', 0);

            while ($headers = mysqli_fetch_field($arrayQuery)) {
                $CABECHALHO[] = $headers->name;
            }
            fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

            while ($row = mysqli_fetch_assoc($arrayQuery)) {
                $row['VAL_SALDO'] = fnValor($row['VAL_SALDO'], 2);
                $row['SALDO_BLOQUEADO'] = fnValor($row['SALDO_BLOQUEADO'], 2);
                $row['VAL_RESGATADO'] = fnValor($row['VAL_RESGATADO'], 2);

                switch ($row['CANAL_CADASTRO']) {

                    case 2:
                        $canal = "TOTEM";
                        break;

                    case 3:
                        $canal = "HOTSITE";
                        break;

                    case 4:
                        $canal = "BUNKER";
                        break;

                    case 5:
                        $canal = "PDV VIRTUAL";
                        break;

                    case 6:
                        $canal = "MAIS CASH";
                        break;

                    default:
                        $canal = "PDV SH";
                        break;
                }

                $row['CANAL_CADASTRO'] = $canal;
                //$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
                //$textolimpo = json_decode($limpandostring, true);
                $array = array_map("utf8_decode", $row);
                fputcsv($arquivo, $array, ';', '"', '\n');

                //echo "<pre>";
                //print_r($row);
                //echo "</pre>";
            }
            fclose($arquivo);
            /*
      $array = array();
      while ($row = mysqli_fetch_assoc($arrayQuery)) {



        $newRow = array();

        $cont = 0;
        foreach ($row as $objeto) {

          // Colunas que são double converte com fnValor
          if ($cont == 16 || $cont == 17) {
            array_push($newRow, fnValor($objeto, 2));
            // Muda cod_univend para nome da unidade
          } else if ($cont == 4) {
            $NOM_ARRAY_UNIDADE = (array_search($objeto, array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
            array_push($newRow, $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']);
            // Muda cod_usucada para nome do usuario
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
      */
            break;

        case 'expdupli':

            $nomeRel = $_GET['nomeRel'];
            $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

            $sql = "SELECT CL.COD_CLIENTE,CL.NOM_CLIENTE,CL.NUM_CGCECPF,CL.NUM_CELULAR, USU.NOM_USUARIO
                            FROM clientes AS CL
                            LEFT JOIN USUARIOS AS USU ON USU.COD_USUARIO = CL.COD_ATENDENTE
                            WHERE CL.cod_empresa = $cod_empresa
                            AND CL.num_celular IN (
                              SELECT num_celular
                              FROM clientes
                              WHERE cod_empresa = $cod_empresa
                              AND COD_UNIVEND IN (" . $lojasSelecionadas . ")
                              GROUP BY num_celular
                              HAVING COUNT(*) > 2
                            )
                            ORDER BY CL.num_celular";


            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));

            $arquivo = fopen($arquivoCaminho, 'w', 0);

            $CABECHALHO = ['Código CLiente', 'Nome', 'CPF', 'Celular', 'Atendente'];
            $CABECHALHO = array_map('utf8_decode', $CABECHALHO);
            fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

            while ($row = mysqli_fetch_assoc($arrayQuery)) {
                $array = array_map("utf8_decode", $row);
                fputcsv($arquivo, $array, ';', '"', '\n');
            }
            fclose($arquivo);

            break;

        case 'exportUni':

            $nomeRel = $_GET['nomeRel'];
            $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

            $sql = "SELECT 
                  UNI.NOM_FANTASI,
                  CL.num_celular,
                  COUNT(CL.num_celular) AS total_repeticoes
                  FROM clientes AS CL
                  INNER JOIN UNIDADEVENDA AS UNI ON UNI.COD_UNIVEND = CL.COD_UNIVEND
                  WHERE CL.COD_UNIVEND IN (" . $lojasSelecionadas . ")
                  GROUP BY CL.num_celular, CL.COD_UNIVEND
                  HAVING COUNT(CL.num_celular) > 1
                  ORDER BY total_repeticoes DESC";

            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));

            $arquivo = fopen($arquivoCaminho, 'w', 0);

            $CABECHALHO = ['Unidade', 'Número de Celular', 'Total de Repetições'];
            $CABECHALHO = array_map('utf8_decode', $CABECHALHO);
            fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

            while ($row = mysqli_fetch_assoc($arrayQuery)) {
                $array = array_map("utf8_decode", $row);
                fputcsv($arquivo, $array, ';', '"', '\n');
            }
            fclose($arquivo);

            break;

        case 'exportSimpl':

            $nomeRel = $_GET['nomeRel'];
            $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

            $sql = "SELECT 
                    CL.NUM_CELULAR,
                    COUNT(*) AS QTD_REPETICOES
                FROM 
                    clientes AS CL
                WHERE 
                    CL.COD_EMPRESA = $cod_empresa
                GROUP BY
                    CL.NUM_CELULAR
                HAVING 
                    COUNT(*) > 1
                ORDER BY 
                    QTD_REPETICOES DESC";

            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));

            $arquivo = fopen($arquivoCaminho, 'w', 0);

            $CABECHALHO = ['Número de Celular', 'Total de Repetições'];
            $CABECHALHO = array_map('utf8_decode', $CABECHALHO);
            fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

            while ($row = mysqli_fetch_assoc($arrayQuery)) {
                $array = array_map("utf8_decode", $row);
                fputcsv($arquivo, $array, ';', '"', '\n');
            }
            fclose($arquivo);


            break;

        case 'paginar':

            //============================
            //paginação
            $sql = "SELECT COUNT(CL.COD_CLIENTE) AS CONTADOR FROM  " . connTemp($cod_empresa, 'true') . ".CLIENTES CL
      LEFT JOIN LOG_CANAL AS LC ON LC.COD_CLIENTE = CL.COD_CLIENTE AND CL.COD_EMPRESA=LC.COD_EMPRESA
                WHERE CL.COD_EMPRESA = " . $cod_empresa . "
                " . $andCodigo . "
                " . $andUnidades . "
                " . $andCampanha . "
                " . $andNome . "
                " . $andPlaca . "
                " . $andCartao . "
                " . $andCpf . "
                " . $andFuncionarios . "
                " . $andInativos . "
                " . $andMaster . "
                " . $andCanal . "
                " . $andDatIni . "
                " . $andDatFim . "
                " . $andLogCel . "
                " . $andCelular . "               
                $order ";

            $retorno = mysqli_query(connTemp($cod_empresa, ''), trim($sql));
            $total_itens_por_pagina = mysqli_fetch_assoc($retorno);

            $numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

            //variavel para calcular o início da visualização com base na página atual
            $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

            //lista de clientes
            $sql = "SELECT CL.*, $selectPlaca
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
                            uni.NOM_FANTASI,
                            LC.COD_CANAL

                FROM CLIENTES CL
                LEFT JOIN USUARIOS USU ON USU.COD_USUARIO = CL.COD_ATENDENTE
                LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=CL.COD_UNIVEND
                LEFT JOIN LOG_CANAL AS LC ON LC.COD_CLIENTE = CL.COD_CLIENTE AND CL.COD_EMPRESA=LC.COD_EMPRESA
                WHERE CL.COD_EMPRESA = $cod_empresa
                    $andUnidades
                    $andCampanha
                    $andNome
                    $andPlaca
                    $andCartao
                    $andCpf
                    $andFuncionarios
                    $andInativos
                    $andMaster
                    $andDatIni
                    $andCanal 
                    $andDatFim
                    $andLogCel
                    $andCelular
                $order LIMIT $inicio,$itens_por_pagina";

            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));
            // fnEscreve($sql);
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
                if ($qrListaEmpresas['DES_EMAILUS'] == "") {
                    $email = "e-mail não cadastrado!";
                } else {
                    $email = fnmascaracampo($qrListaEmpresas['DES_EMAILUS']);
                }

                if ($qrListaEmpresas['COD_UNIVEND'] != 0) {
                    $unidade = $qrListaEmpresas['NOM_FANTASI'];
                } else {
                    $unidade = "Sem unidade";
                }

                switch ($qrListaEmpresas['COD_CANAL']) {

                    case 2:
                        $canal = "TOTEM";
                        break;

                    case 3:
                        $canal = "HOTSITE";
                        break;

                    case 4:
                        $canal = "BUNKER";
                        break;

                    case 5:
                        $canal = "PDV VIRTUAL";
                        break;

                    case 6:
                        $canal = "MAIS CASH";
                        break;

                    default:
                        $canal = "PDV SH";
                        break;
                }

                $count++;

                echo "
              <tr>
              <td><small>" . $qrListaEmpresas['COD_CLIENTE'] . "</small></td>
              <td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrListaEmpresas['NOM_CLIENTE']) . "&nbsp;" . $mostraCracha . "</a></small></td>
              <td><small>" . fnDataFull($qrListaEmpresas['DAT_CADASTR']) . "</small></td>
              <td><small>" . fnDataFull($qrListaEmpresas['DAT_ULTCOMPR']) . "</small></td>
              <td><small>" . $unidade . "</small></td>
              <td><small>" . $canal . "</small></td>
              <td> <small>" . $qrListaEmpresas['NOM_USUARIO'] . "</small></td>
              <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CARTAO']) . "</small></td>
              <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CGCECPF']) . "</small></td>
              <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CELULAR']) . "</small></td>
              <td><small>" . $email . "</small></td>
              $mostraPlaca
              <td class='text-right'><small>R$ " . fnValor($qrListaEmpresas['VAL_SALDO'], 2) . "</small></td>
              <td class='text-right'><small>R$ " . fnValor($qrListaEmpresas['SALDO_BLOQUEADO'], 2) . "</small></td>
              </tr>
              <input type='hidden' id='ret_COD_CLIENTE_" . $count . "' value='" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "'>
              <input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . fnEncode($cod_empresa) . "'>
        ";
            }
            break;

            //adicionado por Lucas ref. chamado 6247 07/05/2024

        case 'campanha22':

?>

            <label for="inputName" class="control-label">Unidade de Atendimento</label>

            <select data-placeholder="Selecione a unidade de atendimento" name="COD_UNIVEND[]" id="COD_UNIVEND" multiple="multiple" class="chosen-select-deselect">

                <?php

                $sql = "SELECT DISTINCT UNI.* FROM unidadevenda AS UNI
                            INNER JOIN campanharegra AS CR ON CR.COD_UNIVENDESP = UNI.COD_UNIVEND OR CR.COD_UNIVENDESP = 9999
                            INNER JOIN campanha AS CP ON CP.COD_CAMPANHA = CR.COD_CAMPANHA
                            WHERE CP.TIP_CAMPANHA IN (22,23)
                            AND UNI.LOG_ESTATUS = 'S'
                            AND UNI.COD_EMPRESA = $cod_empresa ";
                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                $count = 0;

                // $univend = split(',', $univend);
                $univend = explode(',', $univend);

                while ($qrListaUnidades = mysqli_fetch_assoc($arrayQuery)) {

                    if (recursive_array_search($qrListaUnidades['COD_UNIVEND'], array_filter($_REQUEST['COD_UNIVEND'])) !== false) {
                        $selecionado = "selected";
                    } else {
                        $selecionado = "";
                    }

                    echo "
                      <option value='" . $qrListaUnidades['COD_UNIVEND'] . "'" . $selecionado . ">" . $qrListaUnidades['NOM_FANTASI'] . "</option> 
                    ";

                    $count++;
                }
                ?>
            </select>
            <script language=javascript>
                $(".chosen-select-deselect").chosen({
                    allow_single_deselect: true
                });

                function validarFormulario() {
                    var unidadesSelecionadas = document.getElementById('COD_UNIVEND').value;

                    if (unidadesSelecionadas.length == 0) {
                        alert('Por favor, selecione pelo menos uma unidade de atendimento.');
                        return false;
                    }
                    return true;
                }
            </script>

        <?php

            break;

        case 'allunid':
        ?>

            <label for="inputName" class="control-label">Unidade de Atendimento</label>

            <select data-placeholder="Selecione a unidade de atendimento" name="COD_UNIVEND[]" id="COD_UNIVEND" multiple="multiple" class="chosen-select-deselect"

                <?php

                // alterado dia 07/12/2023 o esquema de verificação de unidades autorizadas por Ricardinho
                $arrUnidadesUsu = explode(",", $cod_univendUsu);

                //não mostra todas em telas que não são relatórios'
                $naomostra = fnDecode($_GET['mod']);
                switch ($naomostra) {
                    case 1406: //tela de cupom
                        $mostraTodas = "N";
                        break;
                    default;
                        $mostraTodas = "S";
                        break;
                }

                if ($mostraTodas == "S") {
                    if ($cod_univend == "9999") {
                        echo "<option value='9999' selected>Todas Unidades</option>";
                    } else {
                        echo "<option value='9999'>Todas Unidades</option>";
                    }
                }

                $sql = "select COD_UNIVEND, NOM_FANTASI, NOM_UNIVEND from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' AND LOG_ESTATUS = 'S' order by trim(NOM_FANTASI) ";
                $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
                //fnEscreve($sql);

                while ($qrListaUnidades = mysqli_fetch_assoc($arrayQuery)) {

                    //if ($cod_univend == $qrListaUnidades['COD_UNIVEND']){ $selecionado = "selected";}else{$selecionado = "";} 

                    if (recursive_array_search($qrListaUnidades['COD_UNIVEND'], array_filter($_REQUEST['COD_UNIVEND'])) !== false) {
                        $selecionado = "selected";
                    } else {
                        $selecionado = "";
                    }

                    //verifica acesso master
                    if ($usuReportAdm == "N") {
                        // alterado dia 07/12/2023 o esquema de verificação de unidades autorizadas por Ricardinho
                        // if (strlen(strstr($cod_univendUsu,$qrListaUnidades['COD_UNIVEND']))>0){ $lojaAtiva = "";}else{$lojaAtiva = "disabled";}
                        if (in_array($qrListaUnidades['COD_UNIVEND'], $arrUnidadesUsu)) {
                            $lojaAtiva = "";
                        } else {
                            $lojaAtiva = "disabled";
                        }
                    } else {
                        $lojaAtiva = " ";
                    }

                    if ($lojaAtiva != "disabled") {
                        echo "
                      <option value='" . $qrListaUnidades['COD_UNIVEND'] . "' " . $selecionado . " " . $lojaAtiva . ">" . $qrListaUnidades['NOM_FANTASI'] . "</option> 
                    ";
                    }
                }
                ?>
                </select>

                <div class="help-block with-errors"></div>
                <a class="btn btn-default btn-sm" id="iAll" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square-o" aria-hidden="true"></i> selecionar todos</a>&nbsp;
                <a class="btn btn-default btn-sm" id="iNone" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>

                <script type="text/javascript">
                    $(".chosen-select-deselect").chosen({
                        allow_single_deselect: true
                    });

                    $('#iAll').on('click', function(e) {
                        e.preventDefault();
                        $('#COD_UNIVEND option').prop('selected', true).trigger('chosen:updated');
                    });

                    $('#iNone').on('click', function(e) {
                        e.preventDefault();
                        $("#COD_UNIVEND option:selected").removeAttr("selected").trigger('chosen:updated');
                    });

                    function validarFormulario() {
                        var unidadesSelecionadas = document.getElementById('COD_UNIVEND').value;

                        if (unidadesSelecionadas.length == 0) {
                            alert('Por favor, selecione pelo menos uma unidade de atendimento.');
                            return false;
                        }
                        return true;
                    }
                </script>

    <?php

            break;
    }
}

    ?>