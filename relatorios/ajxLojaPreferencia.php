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
$nom_cliente = "";
$des_placa = "";
$num_cartao = "";
$num_cgcecpf = "";
$dat_ini = "";
$dat_fim = "";
$autoriza = "";
$log_funcionario = "";
$log_inativos = "";
$selectPlaca = "";
$andNome = "";
$andPlaca = "";
$andCartao = "";
$andCpf = "";
$andLojas = "";
$andFuncionarios = "";
$checkInativos = "";
$andInativos = "";
$andDatIni = "";
$andDatFim = "";
$ARRAY_UNIDADE1 = "";
$ARRAY_UNIDADE = "";
$nomeRel = "";
$arquivoCaminho = "";
$arrayQuery = "";
$arquivo = "";
$headers = "";
$row = "";
$limpandostring = "";
$textolimpo = "";
$array = "";
$newRow = "";
$objeto = "";
$arrayColumnsNames = "";
$writer = "";
$inicio = "";
$itens_por_pagina = 50;
$pagina = 1;
$qrListaEmpresas = "";
$log_funciona = "";
$mostraCracha = "";
$mostraPlaca = "";
$unidadePref = "";
$email = "";
$NOM_ARRAY_UNIDADE = "";
$NOM_ARRAY_UNIDADE_PREF = "";
$colCliente = "";


fnDebug('true');

$opcao = @$_GET['opcao'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_univend = @$_POST['COD_UNIVEND'];
$lojasSelecionadas = @$_POST['LOJAS'];
@$nom_cliente = fnLimpaCampo(@$_POST['NOM_CLIENTE']);
@$des_placa = fnLimpacampo(@$_REQUEST['DES_PLACA']);
@$num_cartao = fnLimpaCampo(@$_POST['NUM_CARTAO']);
@$num_cgcecpf = fnLimpaDoc(fnLimpaCampo(@$_POST['NUM_CGCECPF']));
$dat_ini = (@$_POST['DAT_INI'] == "" ? "" : fnDataSql(@$_POST['DAT_INI']));
$dat_fim = (@$_POST['DAT_FIM'] == "" ? "" : fnDataSql(@$_POST['DAT_FIM']));

$autoriza = fnLimpaCampoZero(@$_POST['AUTORIZA']);

if (empty(@$_POST['LOG_FUNCIONARIO'])) {
  $log_funcionario = 'N';
} else {
  $log_funcionario = @$_POST['LOG_FUNCIONARIO'];
}
if (empty(@$_REQUEST['LOG_INATIVOS'])) {
  $log_inativos = 'N';
} else {
  $log_inativos = @$_REQUEST['LOG_INATIVOS'];
}

if ($cod_empresa != 0 && $cod_empresa != '') {

  if ($cod_empresa == 19) {
    $selectPlaca = "(SELECT MAX(DES_PLACA) FROM VEICULOS WHERE COD_CLIENTE = CL.COD_CLIENTE) AS DES_PLACA,";
  } else {
    $selectPlaca = "";
  }

  if ($nom_cliente != '' && $nom_cliente != 0) {
    $andNome = 'and cl.nom_cliente like "' . $nom_cliente . '%"';
  } else {
    $andNome = ' ';
  }

  if ($des_placa != '' && $des_placa != 0) {
    $andPlaca = 'AND CL.COD_CLIENTE = (SELECT COD_CLIENTE FROM VEICULOS WHERE DES_PLACA = "' . $des_placa . '")';
  } else {
    $andPlaca = ' ';
  }

  if ($num_cartao != '' && $num_cartao != 0) {
    $andCartao = 'and cl.num_cartao=' . $num_cartao;
  } else {
    $andCartao = ' ';
  }

  if ($num_cgcecpf != '' && $num_cgcecpf != 0) {
    $andCpf = 'and cl.num_cgcecpf =' . $num_cgcecpf;
  } else {
    $andCpf = ' ';
  }

  if ($cod_univend != '' && $cod_univend != 0) {
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

      $nomeRel = @$_GET['nomeRel'];
      $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

      $sql = "SELECT CL.COD_CLIENTE,
                        CL.NOM_CLIENTE,
                        CL.DAT_CADASTR,
                        CL.COD_UNIVEND,
                        uni.NOM_FANTASI,
                        CL.NUM_TELEFON,
                        CL.NUM_CELULAR,
                        CL.NUM_CARTAO,
                        CL.NUM_CGCECPF AS 'CPF/CNPJ',
                        CL.DES_EMAILUS AS EMAIL,
                        (SELECT ifnull(SUM(VAL_SALDO),0)
                        FROM CREDITOSDEBITOS CDB
                        WHERE CDB.COD_CLIENTE=CL.COD_CLIENTE AND
                        TIP_CREDITO='C' AND
                        COD_STATUSCRED=1 AND
                        (DATE_FORMAT(DAT_EXPIRA, '%Y-%m-%d')  >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(LOG_EXPIRA='N'))
                        AND COD_EMPRESA = $cod_empresa ) AS VAL_SALDO,
                        (SELECT ifnull(SUM(VAL_SALDO),0)
                        FROM CREDITOSDEBITOS CDB
                        WHERE CDB.COD_CLIENTE=CL.COD_CLIENTE AND
                        TIP_CREDITO='C' AND
                        COD_STATUSCRED=3 AND
                        (DATE_FORMAT(DAT_EXPIRA, '%Y-%m-%d')  >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(LOG_EXPIRA='N'))
                        AND COD_EMPRESA = $cod_empresa ) AS SALDO_BLOQUEADO
                        FROM CLIENTES CL
                        LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND= CL.COD_UNIVEND
                        WHERE CL.COD_EMPRESA = $cod_empresa
                        AND CL.cod_univend <> CL.cod_univend_PREF								  
                        AND CL.COD_UNIVEND  IN  ($lojasSelecionadas)
                        " . $andNome . "
                        " . $andCartao . "
                        " . $andCpf . "
                        " . $andFuncionarios . "
                        " . $andInativos . "
                        $andDatIni
                        $andDatFim
                        $andPlaca
                        ORDER BY CL.NOM_CLIENTE";

      //echo $sql;

      $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

      $arquivo = fopen($arquivoCaminho, 'w', 0);

      while ($headers = mysqli_fetch_field($arrayQuery)) {
        $CABECHALHO[] = $headers->name;
      }
      fputcsv($arquivo, $CABECHALHO, ';', '"');

      while ($row = mysqli_fetch_assoc($arrayQuery)) {

        $row['VAL_SALDO'] = fnValor($row['VAL_SALDO'], 2);
        $row['SALDO_BLOQUEADO'] = fnValor($row['SALDO_BLOQUEADO'], 2);
        //$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
        //$textolimpo = json_decode($limpandostring, true);
        $array = array_map("utf8_decode", $row);
        fputcsv($arquivo, $array, ';', '"');
      }
      fclose($arquivo);
      /*
      $array = array();
      while ($row = mysqli_fetch_assoc($arrayQuery)) {


        $newRow = array();

        $cont = 0;
        foreach ($row as $objeto) {

          // Colunas que são double converte com fnValor
          if ($cont == 7 || $cont == 8) {
            array_push($newRow, fnValor($objeto, 2));
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

    case 'paginar':
      //============================
      //paginação
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
                          AND COD_STATUSCRED=3 
                          AND (DATE_FORMAT(DAT_EXPIRA, '%Y-%m-%d')  >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(LOG_EXPIRA='N'))
                          AND COD_EMPRESA = $cod_empresa ) AS SALDO_BLOQUEADO

                FROM CLIENTES CL
                LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=CL.COD_UNIVEND
                WHERE CL.COD_EMPRESA = $cod_empresa
                AND CL.COD_UNIVEND  IN  ($lojasSelecionadas)
                AND CL.COD_UNIVEND <> CL.COD_UNIVEND_PREF								  
                  $andNome
                  $andPlaca
                  $andCartao
                  $andCpf
                  $andFuncionarios
                  $andInativos
                  $andDatIni
                  $andDatFim
                ORDER BY CL.NOM_CLIENTE LIMIT $inicio,$itens_por_pagina";

      $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
      //fnEscreve($sql);

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
        if ($qrListaEmpresas['COD_UNIVEND_PREF'] == 0) {
          $unidadePref = "Sem unidade preferêncial";
        } else {
          $unidadePref = $qrListaEmpresas['COD_UNIVEND_PREF'];
        }
        if ($qrListaEmpresas['DES_EMAILUS'] == "") {
          $email = "e-mail não cadastrado!";
        } else {
          $email = fnMascaraCampo($qrListaEmpresas['DES_EMAILUS']);
        }
        /*$NOM_ARRAY_UNIDADE = (array_search($qrListaEmpresas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
           * 
           */
        //$NOM_ARRAY_UNIDADE_PREF = (array_search($qrListaEmpresas['COD_UNIVEND_PREF'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
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
                <td><small>" . $qrListaEmpresas['NOM_FANTASI'] . "</small></td>
                <td><small>" .  $unidadePref . "</small></td>
                <td><small>" . $qrListaEmpresas['NUM_CARTAO'] . "</small></td>
                <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CGCECPF']) . "</small></td>
                <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_TELEFON']) . "</small></td>
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
  }
}
