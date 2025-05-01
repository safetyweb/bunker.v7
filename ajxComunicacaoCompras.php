<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
  echo fnDebug('true');
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
}
$cod_campanha = "";
$cod_canalcom = "";
$tip_lancame = "";
$dat_ini = "";
$dat_fim = "";
$dias30 = "";
$hoje = "";
$andCampanha = "";
$andCanal = "";
$andLancamento = "";
$nomeRel = "";
$arquivo = "";
$writer = "";
$arrayQuery = [];
$array = [];
$qrLista = "";
$newRow = "";
$qtd_produto = 0;
$val_unitario = "";
$val_total = 0;
$qtd_envio = 0;
$msg = "";
$qrCamp = "";
$id = "";
$dat_validade = "";
$qtd_contrato = 0;
$arrayColumnsNames = [];
$sqlcontador = "";
$and_canal = "";
$retorno = "";
$inicio = "";
$qtd_email = 0;
$qtd_sms = 0;
$qtd_wpp = 0;
$qtd_cred = 0;
$qtd_deb = 0;
$credSms = "";
$credEmail = "";
$debSms = "";
$debEmail = "";


include '_system/_functionsMain.php';
require_once 'js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

//echo fnDebug('true');

$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_campanha = fnLimpaCampo(@$_REQUEST['COD_CAMPANHA']);
$cod_canalcom = fnLimpaCampo(@$_REQUEST['COD_CANALCOM']);
$tip_lancame = fnLimpaCampo(@$_REQUEST['TIP_LANCAME']);
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
  $dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
  $dat_fim = fnDataSql($hoje);
}

if ($cod_campanha != 0 && $cod_campanha != '') {
  $andCampanha = "AND pedido.COD_CAMPANHA = $cod_campanha";
} else {
  $andCampanha = "";
}

if ($cod_canalcom != 0 && $cod_canalcom != '') {
  $andCanal = "AND prod.COD_CANALCOM = $cod_canalcom";
} else {
  $andCanal = "";
}

if ($tip_lancame == 'D') {
  $andLancamento = "AND pedido.TIP_LANCAMENTO = 'D'";
} else if ($tip_lancame == 'C') {
  $andLancamento = "AND pedido.TIP_LANCAMENTO = 'C'";
} else {
  $andLancamento = "";
}

switch ($opcao) {
  case 'exportar':

    $nomeRel = @$_GET['nomeRel'];
    $arquivo = 'media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

    $writer = WriterFactory::create(Type::CSV);
    $writer->setFieldDelimiter(';');
    $writer->openToFile($arquivo);

    $sql = "SELECT pedido.TIP_LANCAMENTO,
                           pedido.COD_VENDA,
                           pedido.COD_CAMPANHA,
                           emp.NOM_EMPRESA,
                           pedido.DAT_CADASTR,
                           pedido.COD_ORCAMENTO,
                           canal.DES_CANALCOM,
                           pedido.VAL_UNITARIO,
                           pedido.ID_SESSION_PAGSEGURO,
                           pedido.DAT_VALIDADE,
                           round(pedido.VAL_UNITARIO * pedido.QTD_PRODUTO,2) AS VAL_TOTAL,
                           round(pedido.QTD_PRODUTO,0) AS QTD_PRODUTO,
                           if(pedido.PAG_CONFIRMACAO='S', 'Pagamento Confirmado', 'Aguardando Confirmação de Pagamento') AS DES_SITUACAO
                    FROM pedido_marka pedido 
                    INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
                    INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
                    INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA
                    WHERE pedido.COD_ORCAMENTO > 0 
                    AND pedido.COD_EMPRESA = $cod_empresa
                    AND pedido.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
                    $andCampanha
                    $andCanal
                    $andLancamento
                    ORDER BY pedido.DAT_CADASTR DESC, canal.DES_CANALCOM";

    //fnEscreve($sql);

    $arrayQuery = mysqli_query($connAdm->connAdm(), trim($sql));

    $array = array();
    while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {

      $newRow = array();

      if ($qrLista['TIP_LANCAMENTO'] == 'D') {

        $qtd_produto = "-" . fnValor($qrLista['QTD_PRODUTO'], 0);
        $val_unitario = "";
        $val_total = "";
        $qtd_envio = $qtd_envio + $qrLista['QTD_PRODUTO'];
        $msg = ucfirst(strtolower($qrLista['ID_SESSION_PAGSEGURO']));

        $sql = "SELECT DES_CAMPANHA FROM CAMPANHA WHERE COD_CAMPANHA = $qrLista[COD_CAMPANHA]";
        $qrCamp = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), trim($sql)));
        $id = $qrCamp['DES_CAMPANHA'];
        $dat_validade = "";
      } else {

        $qtd_produto = "+" . fnValor($qrLista['QTD_PRODUTO'], 0);
        $val_unitario = fnValor($qrLista['VAL_UNITARIO'], 6);
        $val_total = fnValor($qrLista['VAL_TOTAL'], 2);
        $qtd_contrato = $qtd_contrato + $qrLista['QTD_PRODUTO'];
        //$msg = "Pagamento Confirmado";
        $msg = $qrLista['DES_SITUACAO'];
        $id = $qrLista['COD_ORCAMENTO'];
        if ($id == 1) {
          $id = "Crédito Avulso";
        }

        $dat_validade = fnDataShort($qrLista['DAT_VALIDADE']);
      }


      array_push($newRow, $qrLista['DAT_CADASTR']);
      array_push($newRow, $id);
      array_push($newRow, $qrLista['DES_CANALCOM']);
      array_push($newRow, $val_unitario);
      array_push($newRow, $val_total);
      array_push($newRow, $qtd_produto);
      array_push($newRow, $msg);

      $array[] = $newRow;
    }

    $arrayColumnsNames = array();

    array_push($arrayColumnsNames, "DATA");
    array_push($arrayColumnsNames, "ID");
    array_push($arrayColumnsNames, "DESCRIÇÃO");
    array_push($arrayColumnsNames, "VL. UNITÁRIO");
    array_push($arrayColumnsNames, "TOTAL");
    array_push($arrayColumnsNames, "QUANTIDADE");
    array_push($arrayColumnsNames, "SITUAÇÃO");
    array_push($arrayColumnsNames, "VALIDADE");

    $writer->addRow($arrayColumnsNames);
    $writer->addRows($array);

    $writer->close();

    break;

  case 'paginar':

    $sqlcontador = "SELECT * FROM pedido_marka pedido
                        INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO     
                        WHERE pedido.COD_EMPRESA = $cod_empresa
                        $andCampanha
                        $and_canal
                        $andLancamento
                        ";

    $retorno = mysqli_query($connAdm->connAdm(), $sqlcontador);
    $total_itens_por_pagina = mysqli_num_rows($retorno);
    $numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);
    //variavel para calcular o início da visualização com base na página atual
    $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

    $sql = "SELECT pedido.TIP_LANCAMENTO,
                                   pedido.COD_VENDA,
                                   pedido.COD_CAMPANHA,
                                   emp.NOM_EMPRESA,
                                   pedido.DAT_CADASTR,
                                   pedido.DAT_VALIDADE,
                                   pedido.ID_SESSION_PAGSEGURO,
                                   CONCAT(DATE_FORMAT(MID(CONVERT(pedido.COD_ORCAMENTO,CHAR),1,6), '%d/%m/%Y')
                                   ,' ' , MID(pedido.COD_ORCAMENTO,7,2),':',MID(pedido.COD_ORCAMENTO,9,2),':', MID(pedido.COD_ORCAMENTO,11,2)) AS DAT_CADASTRO,
                                   pedido.COD_ORCAMENTO,
                                   canal.DES_CANALCOM,
                                   round(pedido.QTD_PRODUTO,0) AS QTD_PRODUTO,
                                   pedido.VAL_UNITARIO,
                                   round(pedido.VAL_UNITARIO * pedido.QTD_PRODUTO,2) AS VAL_TOTAL ,
                                   if(pedido.PAG_CONFIRMACAO='S', 'Pagamento Confirmado', 'Aguardando Confirmação de Pagamento') AS DES_SITUACAO
                            FROM pedido_marka pedido 
                            INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
                            INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
                            INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA
                            WHERE pedido.COD_ORCAMENTO > 0 
                            AND pedido.COD_EMPRESA = $cod_empresa
                            AND pedido.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
                            $andCampanha
                            $andCanal
                            $andLancamento
                            ORDER BY pedido.DAT_CADASTR DESC, canal.DES_CANALCOM LIMIT $inicio,$itens_por_pagina";

    //fnEscreve($sql);

    $arrayQuery = mysqli_query($connAdm->connAdm(), trim($sql));

    $count = 0;

    $qtd_contrato = 0;
    $qtd_envio = 0;
    $qtd_email = 0;
    $qtd_sms = 0;
    $qtd_wpp = 0;
    $qtd_cred = 0;
    $qtd_deb = 0;
    $credSms = 0;
    $credEmail = 0;
    $debSms = 0;
    $debEmail = 0;

    while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {

      $count++;

      if ($qrLista['TIP_LANCAMENTO'] == 'D') {

        $qtd_produto = "<span class='text-danger' style='font-size:14px;'><b>-</b></span>&nbsp;" . fnValor($qrLista['QTD_PRODUTO'], 0);
        $val_unitario = "";
        $val_total = "";
        $qtd_envio = $qtd_envio + $qrLista['QTD_PRODUTO'];
        $msg = ucfirst(strtolower($qrLista['ID_SESSION_PAGSEGURO']));
        $qtd_deb += $qrLista['QTD_PRODUTO'];

        $sql = "SELECT DES_CAMPANHA FROM CAMPANHA WHERE COD_CAMPANHA = $qrLista[COD_CAMPANHA]";
        $qrCamp = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), trim($sql)));
        $id = $qrCamp['DES_CAMPANHA'];
        $dat_validade = "";
      } else {

        $qtd_produto = "<span class='text-success' style='font-size:14px;'><b>+</b></span>&nbsp;" . fnValor($qrLista['QTD_PRODUTO'], 0);
        $val_unitario = fnValor($qrLista['VAL_UNITARIO'], 6);
        $val_total = fnValor($qrLista['VAL_TOTAL'], 2);
        $qtd_contrato = $qtd_contrato + $qrLista['QTD_PRODUTO'];
        if ($qrLista['COD_ORCAMENTO'] != "") {
          $msg = $qrLista['DES_SITUACAO'];
        } else {
          $msg = "Pagamento Confirmado";
        }
        $id = $qrLista['COD_ORCAMENTO'];
        $qtd_cred += $qrLista['QTD_PRODUTO'];

        if ($id == 1) {
          $id = "Crédito Avulso";
          $msg = "Crédito Avulso";
        }

        $dat_validade = fnDataShort($qrLista['DAT_VALIDADE']);
      }

      switch ($qrLista['DES_CANALCOM']) {

        case 'SMS':
          if ($qrLista['TIP_LANCAMENTO'] == 'D') {
            $qtd_sms = $qtd_sms - $qrLista['QTD_PRODUTO'];
            $debSms = $debSms - $qrLista['QTD_PRODUTO'];
          } else {
            $qtd_sms = $qtd_sms + $qrLista['QTD_PRODUTO'];
            $credSms = $credSms + $qrLista['QTD_PRODUTO'];
          }
          break;

        case 'Whats App':
          if ($qrLista['TIP_LANCAMENTO'] == 'D') {
            $qtd_wpp = $qtd_wpp - $qrLista['QTD_PRODUTO'];
          } else {
            $qtd_wpp = $qtd_wpp + $qrLista['QTD_PRODUTO'];
          }
          break;

        default:
          if ($qrLista['TIP_LANCAMENTO'] == 'D') {
            $qtd_email = $qtd_email - $qrLista['QTD_PRODUTO'];
            $debEmail = $debEmail - $qrLista['QTD_PRODUTO'];
          } else {
            $qtd_email = $qtd_email + $qrLista['QTD_PRODUTO'];
            $credEmail = $credEmail + $qrLista['QTD_PRODUTO'];
          }
          break;
      }

      echo " <tr>                   
                              <td><small>" . fnDataFull($qrLista['DAT_CADASTR']) . "</small></td>
                              <td><small>" . $id . "</td>
                              <td><small>" . $qrLista['DES_CANALCOM'] . "</small></td>
                              <td class='text-right'><small>" . $val_unitario . "</small></td>
                              <td class='text-right'><small>" . $qtd_produto . "</small></td>
                              <td class='text-right'><small>" . $val_total . "</small></td>   
                              <td><small>" . $msg . "</small></td>
                              <td><small>" . $dat_validade . "</small></td>
                            </tr>
                            ";
    }
}
