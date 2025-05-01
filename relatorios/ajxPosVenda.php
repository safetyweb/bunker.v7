<?php

include '../_system/_functionsMain.php';

echo fnDebug('true');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$log_univend = 'N';

$opcao = @$_GET['opcao'];
$cod_empresa = fnDecode(@$_GET['id']);
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];

$cod_univend = @$_POST['COD_UNIVEND'];
$lojasSelecionadas = @$_POST['LOJAS'];
@$nom_cliente = fnLimpaCampo(@$_POST['NOM_CLIENTE']);
@$des_placa = fnLimpacampo(@$_REQUEST['DES_PLACA']);
@$num_cartao = fnLimpaCampo(@$_POST['NUM_CARTAO']);
@$num_cgcecpf = fnLimpaDoc(fnLimpaCampo(@$_POST['NUM_CGCECPF']));
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

if ($cod_empresa != 0) {

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

    // echo $cod_univend."_<br>";
    // echo $log_univend."_<br>";
    // echo $andUnidades."_<br>";

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

    //rotina de controle de acessos por módulo
    include "moduloControlaAcesso.php";

    /*$ARRAY_UNIDADE1 = array(
      'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
      'cod_empresa' => $cod_empresa,
      'conntadm' => $connAdm->connAdm(),
      'IN' => 'N',
      'nomecampo' => '',
      'conntemp' => '',
      'SQLIN' => ""
  );

  $ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);
  */

    switch ($opcao) {
        case 'exportar':

            $nomeRel = $_GET['nomeRel'];
            $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';


            $sql = "SELECT 
                  ven.COD_CLIENTE as Codigo,
                  cli.NOM_CLIENTE Cliente,
                  cli.NUM_CELULAR as Celular,
                  uni.NOM_FANTASI as Loja,    
                  vend.NOM_USUARIO as Vendedor,
                  ven.DAT_CADASTR_WS as Ultima_compra,
                  ven.VAL_TOTVENDA Val_Compra,
                 
                  (
                  SELECT IFNULL(SUM(VAL_SALDO),0)
                  FROM CREDITOSDEBITOS CDB
                  WHERE CDB.COD_CLIENTE=cli.COD_CLIENTE AND TIP_CREDITO='C' AND COD_STATUSCRED=1 AND 
                  (date(DAT_EXPIRA) >= CURDATE() OR(LOG_EXPIRA='N')) AND COD_EMPRESA = $cod_empresa) AS Cred_Total, 
                  (SELECT IFNULL(SUM(b.val_saldo),0) 
                    FROM   creditosdebitos b
                    WHERE  b.cod_cliente = cli.COD_CLIENTE
                        AND b.cod_statuscred IN(0,1,2,3,4,5,7,8,9) 
                        AND b.tip_credito = 'C' 
                        AND date(b.DAT_EXPIRA) BETWEEN CURDATE() AND DATE_add(CURDATE(), INTERVAL 30 day)) AS Cred_Expirar
                  
                   FROM vendas ven
                   LEFT JOIN clientes cli ON cli.COD_CLIENTE=ven.COD_CLIENTE AND ven.COD_EMPRESA=cli.COD_EMPRESA
                   LEFT JOIN usuarios vend ON vend.COD_USUARIO=ven.COD_VENDEDOR
                   LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=ven.COD_UNIVEND
                  WHERE 
                      ven.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9)  AND
                      row(ven.COD_VENDA,ven.COD_EMPRESA) IN (SELECT 
                                                                max(ven.COD_VENDA) COD_VENDA, ven.COD_EMPRESA                                   
                                                                 FROM vendas ven
                                                                WHERE ven.COD_EMPRESA=$cod_empresa and
                                                                      date(ven.DAT_CADASTR_WS) BETWEEN '$dat_ini' AND '$dat_fim' AND 
                                                                        ven.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) and
                                                                        ven.COD_AVULSO= 2  AND 
                                                                        ven.COD_UNIVEND IN ($lojasSelecionadas)
                                                                GROUP BY ven.COD_CLIENTE 
                                                            )
                      
                        GROUP BY ven.COD_CLIENTE";

            //echo $sql;

            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

            $arquivo = fopen($arquivoCaminho, 'w', 0);

            while ($headers = mysqli_fetch_field($arrayQuery)) {
                $CABECHALHO[] = $headers->name;
            }
            fputcsv($arquivo, $CABECHALHO, ';', '"');

            while ($row = mysqli_fetch_assoc($arrayQuery)) {
                $row['Val_Compra'] = fnValor($row['Val_Compra'], 2);
                $row['Cred_Total'] = fnValor($row['Cred_Total'], 2);
                $row['Cred_Expirar'] = fnValor($row['Cred_Expirar'], 2);
                //$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
                //$textolimpo = json_decode($limpandostring, true);
                $array = array_map("utf8_decode", $row);
                fputcsv($arquivo, $array, ';', '"');

                //echo "<pre>";
                //print_r($row);
                //echo "</pre>";
            }
            fclose($arquivo);

            break;

        case 'paginar':

            //============================
            //paginação
            $sql = "SELECT 1 FROM vendas ven
                  WHERE 
                        ven.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9)  AND
                    row(ven.COD_VENDA,ven.COD_EMPRESA) IN (SELECT 
                                                              max(ven.COD_VENDA) COD_VENDA, ven.COD_EMPRESA                                   
                                                               FROM vendas ven
                                                              WHERE ven.COD_EMPRESA=$cod_empresa and
                                                                    date(ven.DAT_CADASTR_WS) BETWEEN '$dat_ini' AND '$dat_fim' AND 
                                                                      ven.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) and
                                                                      ven.COD_AVULSO= 2  AND 
                                                                      ven.COD_UNIVEND IN ($lojasSelecionadas)
                                                              GROUP BY ven.COD_CLIENTE 
                                                          )
                    
                      GROUP BY ven.COD_CLIENTE";

            //fnEscreve($sql);

            $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
            $total_itens_por_pagina = mysqli_fetch_assoc($retorno);

            $numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

            //variavel para calcular o início da visualização com base na página atual
            $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

            //lista de clientes
            $sql = "SELECT 
                  uni.COD_UNIVEND,
                  uni.NOM_FANTASI,
                  ven.COD_CLIENTE,
                  cli.NOM_CLIENTE,
                  cli.NUM_CGCECPF,
                  cli.NUM_CELULAR,
                  ven.COD_VENDEDOR,
                  vend.NOM_USUARIO,
                  ven.COD_VENDA,
                  ven.VAL_TOTVENDA,
                  ven.DAT_CADASTR_WS,
                  (
                  SELECT IFNULL(SUM(VAL_SALDO),0)
                  FROM CREDITOSDEBITOS CDB
                  WHERE CDB.COD_CLIENTE=cli.COD_CLIENTE AND TIP_CREDITO='C' AND COD_STATUSCRED=1 AND 
                  (date(DAT_EXPIRA) >= CURDATE() OR(LOG_EXPIRA='N')) AND COD_EMPRESA = $cod_empresa) AS VAL_SALDO_DISPONIVEL, 
                  (
                  SELECT IFNULL(SUM(VAL_SALDO),0)
                  FROM CREDITOSDEBITOS CDB
                  WHERE CDB.COD_CLIENTE=cli.COD_CLIENTE AND TIP_CREDITO='C' AND 
                      COD_STATUSCRED IN (3,7) AND (date(DAT_EXPIRA) >= CURDATE() OR(LOG_EXPIRA='N')) AND COD_EMPRESA = $cod_empresa) AS SALDO_BLOQUEADO,
                  FROM   creditosdebitos b
                                                            WHERE  b.cod_cliente = cli.COD_CLIENTE
                                                                AND b.cod_statuscred IN(0,1,2,3,4,5,7,8,9) 
                                                                AND b.tip_credito = 'C' 
                                                                AND date(b.DAT_EXPIRA) BETWEEN CURDATE() AND DATE_add(CURDATE(), INTERVAL 30 day)) AS CREDITO_EXPIRADOS          

                   FROM vendas ven
                   LEFT JOIN clientes cli ON cli.COD_CLIENTE=ven.COD_CLIENTE AND ven.COD_EMPRESA=cli.COD_EMPRESA
                   LEFT JOIN usuarios vend ON vend.COD_USUARIO=ven.COD_VENDEDOR
                   LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=ven.COD_UNIVEND
                  WHERE 
                        ven.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9)  AND
                        row(ven.COD_VENDA,ven.COD_EMPRESA) IN (SELECT 
                                                                  max(ven.COD_VENDA) COD_VENDA, ven.COD_EMPRESA                                   
                                                                   FROM vendas ven
                                                                  WHERE ven.COD_EMPRESA=$cod_empresa and
                                                                        date(ven.DAT_CADASTR_WS) BETWEEN '$dat_ini' AND '$dat_fim' AND 
                                                                          ven.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) and
                                                                          ven.COD_AVULSO= 2  AND 
                                                                          ven.COD_UNIVEND IN ($lojasSelecionadas)
                                                                  GROUP BY ven.COD_CLIENTE 
                                                              )
                        
                          GROUP BY ven.COD_CLIENTE 
                        LIMIT $inicio,$itens_por_pagina";

            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
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
                $count++;

                if (fnControlaAcesso("1024", $arrayParamAutorizacao) === true) {
                    $colCliente = "<td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrListaEmpresas['NOM_CLIENTE']) . "&nbsp;" . $mostraCracha . "</a></small></td>";
                } else {
                    $colCliente = "<td><small>" . fnMascaraCampo($qrListaEmpresas['NOM_CLIENTE']) . "&nbsp;" . $mostraCracha . "</small></td>";
                }

                echo "
              <tr>
                <td><small>" . $qrListaEmpresas['COD_CLIENTE'] . "</small></td>
                " . $colCliente . "
                <td><small>" . fnDataFull($qrListaEmpresas['DAT_CADASTR_WS']) . "</small></td>
                <td><small>" . $unidade . "</small></td>
                <td> <small>" . $qrListaEmpresas['NOM_USUARIO'] . "</small></td>
                <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CELULAR']) . "</small></td>
                <td><small>" . $email . "</small></td>
                $mostraPlaca
                <td class='text-right'><small>R$ " . fnValor($qrListaEmpresas['VAL_SALDO_DISPONIVEL'], 2) . "</small></td>
                <td class='text-right'><small>R$ " . fnValor($qrListaEmpresas['SALDO_BLOQUEADO'], 2) . "</small></td>
              </tr>
              <input type='hidden' id='ret_COD_CLIENTE_" . $count . "' value='" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "'>
              <input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . fnEncode($cod_empresa) . "'>
              ";
            }
    }
}
