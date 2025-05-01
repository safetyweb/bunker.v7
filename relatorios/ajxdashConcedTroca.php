<?php


echo fnDebug('true');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../_system/_functionsMain.php';

$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];
$pagina = $_GET['idPage'];

$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
$cod_univend = $_REQUEST['COD_UNIVEND'];
$cod_grupotr = $_REQUEST['COD_GRUPOTR'];
$cod_tiporeg = $_REQUEST['COD_TIPOREG'];
$cod_filtro = $_REQUEST['COD_FILTRO'];
$dat_ini = fnDataSql($_POST['DAT_INI']);
$dat_fim = fnDataSql($_POST['DAT_FIM']);
$hHabilitado = $_REQUEST['hHabilitado'];
$hashForm = $_REQUEST['hashForm'];
$lojasSelecionadas = $_POST['LOJAS'];

switch ($opcao) {
  case 'exportar':

    $nomeRel = $_GET['nomeRel'];
    $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

    $sqlConsulta = "SELECT  
                      UV.NOM_FANTASI loja,
                      UV.COD_UNIVEND,
                      sum(val_credito) val_credito,
                      sum(val_resgate) val_resgate,
                      sum(vl_vinculado) vvr,
                      count(distinct qtd_clientes_resgate) qtd_clientes_resgate,
                      val_expirado,
                      sum(val_a_expirar) val_a_expirar,
                      qtd_cliente_credito_expirado,
                      sum(val_saldo_total) val_saldo_total,
                      cada_um_investido
                      FROM (
                      SELECT 
                        '' NOM_FANTASI, 
                         a.COD_UNIVEND,
                         a.COD_EMPRESA,
                         CASE WHEN a.tip_credito = 'C'  
                         and   a.cod_statuscred IN(1, 2, 3, 4, 5, 7, 8, 9, 10)  
                         THEN a.val_credito  ELSE  '0.00' END  val_credito, 
                         CASE WHEN a.tip_credito = 'D' 
                         AND a.cod_statuscred IN(1, 2, 3, 4, 5, 7, 8, 9, 10) 
                         THEN a.val_credito  ELSE '0.00' END val_resgate, 
                         case when  date(a.dat_expira) BETWEEN '$dat_ini' AND '$dat_fim' and
                         a.cod_statuscred = '1' and
                         a.tip_credito = 'C'  AND 
                         a.val_saldo > 0  then  a.val_saldo ELSE '0.00' END val_a_expirar,
                         case when  date(a.dat_expira) >= '$dat_ini' AND
                         a.cod_statuscred = '1' and
                         a.tip_credito = 'C'  AND 
                         a.val_saldo > 0  then  a.val_saldo ELSE '0.00' END val_saldo_total,
                        '' val_expirado,
                         CASE WHEN a.tip_credito = 'D' and  a.cod_statuscred IN(1, 2, 3, 4, 5, 7, 8, 9, 10) THEN a.cod_cliente ELSE null END  qtd_clientes_resgate, 
                         CASE WHEN a.tip_credito = 'D' AND  a.cod_statuscred IN(1, 2, 3, 4, 5, 7, 8, 9, 10) THEN a.val_vinculado ELSE '0.00' END  vl_vinculado, 
                         '0.00' cada_um_investido,

                        '' qtd_cliente_credito_expirado 

                         FROM creditosdebitos a 
                         WHERE  
                         a.COD_EMPRESA=$cod_empresa and
                         date(a.dat_reproce) BETWEEN '$dat_ini'  AND '$dat_fim'
                         AND a.COD_UNIVEND IN($lojasSelecionadas)
                         )tmpcreditos
                       LEFT JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = tmpcreditos.COD_UNIVEND 
                        GROUP BY tmpcreditos.COD_UNIVEND
                        ORDER BY UV.NOM_FANTASI ASC";
    //fnescreve($sqlConsulta);
    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sqlConsulta);

    $arquivo = fopen($arquivoCaminho, 'w', 0);

    while ($headers = mysqli_fetch_field($arrayQuery)) {
      $CABECHALHO[] = $headers->name;
    }
    fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

    while ($row = mysqli_fetch_assoc($arrayQuery)) {

      $cada_um_investido = ($row['vvr'] - $row['val_resgate']) / $row['val_resgate'];
      $row[val_credito] = fnValor($row['val_credito'], 2);
      $row[val_resgate] = fnValor($row['val_resgate'], 2);
      $row[val_a_expirar] = fnValor($row['val_a_expirar'], 2);


      $sqlSaldoExp = "SELECT Sum(val_saldo) val_expirado
                        FROM   creditosdebitos AA
                        INNER JOIN clientes c ON c.cod_cliente=AA.cod_cliente
                        WHERE date(AA.dat_expira) BETWEEN '$dat_ini' AND '$dat_fim'  
                        AND AA.log_expira = 'S' 
                        AND AA.cod_statuscred IN (4) 
                        AND AA.cod_empresa = $cod_empresa
                        AND c.COD_UNIVEND = $row[COD_UNIVEND]";

      $arraySaldoExp = mysqli_query(connTemp($cod_empresa, ''), $sqlSaldoExp);
      $qrSaldoExp = mysqli_fetch_assoc($arraySaldoExp);


      $row[val_expirado] = fnValor($qrSaldoExp['val_expirado'], 2);

      $row[vvr] = fnValor($row['vvr'], 2);
      $row[val_saldo_total] = fnValor($row['val_saldo_total'], 2);

      $sqlCliExp = "SELECT COUNT(DISTINCT a.COD_CLIENTE) qtd_cliente_credito_expirado 
                                                    FROM  creditosdebitos a
                                                          INNER JOIN clientes c ON c.cod_cliente = a.cod_cliente 
                                                        WHERE a.cod_statuscred IN(4)  AND 
                                                        DATE(a.DAT_EXPIRA) BETWEEN '$dat_ini' AND '$dat_fim' AND 
                                                        a.tip_credito = 'C' AND 
                                                        a.COD_EMPRESA=$cod_empresa   AND
                                                        c.COD_UNIVEND = $row[COD_UNIVEND]";

      $arrayCliExp = mysqli_query(connTemp($cod_empresa, ''), $sqlCliExp);
      $qrCliExp = mysqli_fetch_assoc($arrayCliExp);

      $row[qtd_cliente_credito_expirado] = fnValor($qrCliExp['qtd_cliente_credito_expirado'], 2);

      $row[cada_um_investido] = fnValor($cada_um_investido, 2);
      //$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
      //$textolimpo = json_decode($limpandostring, true);
      $array = array_map("utf8_decode", $row);
      fputcsv($arquivo, $array, ';', '"', '\n');
    }
    fclose($arquivo);
    /*
    $array = array();
    while ($row = mysqli_fetch_assoc($arrayQuery)) {
      $newRow = array();

      $cont = 0;
      foreach ($row as $objeto) {

        // Colunas que são double converte com fnValor
        if (($cont > 0 && $cont <= 4) || ($cont == 6)) {

          array_push($newRow, fnValor($objeto, 2));
        } else {

          array_push($newRow, $objeto);
        }

        $cont++;
      }
      $array[] = $newRow;

      echo "<pre>";
      print_r($row);
      echo "</pre>";
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
}
