<?php

include '_system/_functionsMain.php';
require_once 'js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

// definir o numero de itens por pagina
$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];
$pagina = $_GET['idPage'];
$cod_empresa = fnDecode($_GET['id']);

$hHabilitado = $_REQUEST['hHabilitado'];
$hashForm = $_REQUEST['hashForm'];

$cod_filtro = fnLimpaCampoZero($_REQUEST['COD_FILTRO']);
$cod_tpfiltro = fnLimpaCampoZero($_REQUEST['COD_TPFILTRO']);
$des_filtro = fnLimpaCampo($_REQUEST['DES_FILTRO']);
$idS = fnLimpaCampo($_REQUEST['idS']);
$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
$cod_agrupador = $_REQUEST['SEL_TPFILTRO'];

if ($cod_agrupador != '') {
  $cod_tpfiltro = fnLimpaCampo(fnDecode(@$_GET['idF']));
  $andCodTp = "AND FC.COD_TPFILTRO = $cod_agrupador";
} else {
  $cod_tpfiltro = "";
  $andCodTp = "";
}



switch ($opcao) {

  case 'exportar':

    $nomeRel = $_GET['nomeRel'];
    $arquivo = 'media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

    $writer = WriterFactory::create(Type::CSV);
    $writer->setFieldDelimiter(';');
    $writer->openToFile($arquivo);



    $sql = "SELECT FC.*, TF.DES_TPFILTRO FROM FILTROS_CLIENTE FC
                                                    LEFT JOIN TIPO_FILTRO TF ON TF.COD_TPFILTRO = FC.COD_TPFILTRO
                                                    WHERE FC.COD_EMPRESA = $cod_empresa
                                                    $andCodTp
                                                    order by FC.COD_TPFILTRO, TF.DES_TPFILTRO
                                                    ";

    fnEscreve($sql);

    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

    $array = array();
    while ($row = mysqli_fetch_assoc($arrayQuery)) {
      $newRow = array();

      $cont = 0;
      foreach ($row as $objeto) {

        // Colunas que são double converte com fnValor
        if ($cont == 999) {
          array_push($newRow, fnValor($objeto, 2));
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

  case 'retornar':

    $sql = "SELECT FC.*, TF.DES_TPFILTRO FROM FILTROS_CLIENTE FC
                                LEFT JOIN TIPO_FILTRO TF ON TF.COD_TPFILTRO = FC.COD_TPFILTRO
                                WHERE FC.COD_EMPRESA = $cod_empresa
                                $andCodTp
                                order by FC.COD_TPFILTRO, TF.DES_TPFILTRO ";
    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
    //fnEscreve($sql);
    $count = 0;

    while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
      $count++;
      echo "
                    <tr>
                      <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
                      <td>" . $qrBuscaModulos['COD_FILTRO'] . "</td>
                      <td>" . $qrBuscaModulos['DES_TPFILTRO'] . "</td>
                      <td>" . $qrBuscaModulos['DES_FILTRO'] . "</td>
                    </tr>
                    <input type='hidden' id='ret_COD_FILTRO_" . $count . "' value='" . $qrBuscaModulos['COD_FILTRO'] . "'>
                    <input type='hidden' id='ret_COD_TPFILTRO_" . $count . "' value='" . $qrBuscaModulos['COD_TPFILTRO'] . "'>
                    <input type='hidden' id='ret_DES_FILTRO_" . $count . "' value='" . $qrBuscaModulos['DES_FILTRO'] . "'>
                    ";
    }

    break;

  case 'paginar':

    $sql = "SELECT 1 FROM filtros_cliente FC
                LEFT JOIN TIPO_FILTRO TF ON TF.COD_TPFILTRO = FC.COD_TPFILTRO
                WHERE FC.COD_EMPRESA = $cod_empresa
                $andCodTp
                order by FC.COD_TPFILTRO, TF.DES_TPFILTRO";
    //echo $sql;

    $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
    $total_itens_por_pagina = mysqli_num_rows($retorno);

    $numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

    //variavel para calcular o início da visualização com base na página atual
    $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

    $sql = "SELECT FC.*, TF.DES_TPFILTRO FROM FILTROS_CLIENTE FC
                        LEFT JOIN TIPO_FILTRO TF ON TF.COD_TPFILTRO = FC.COD_TPFILTRO
                        WHERE FC.COD_EMPRESA = $cod_empresa
                        $andCodTp
                        order by FC.COD_TPFILTRO, TF.DES_TPFILTRO
                        LIMIT $inicio, $itens_por_pagina";

    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
    //fnEscreve($sql);
    $count = 0;
    while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
      $count++;
      echo "
                        <tr>
                          <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
                          <td>" . $qrBuscaModulos['COD_FILTRO'] . "</td>
                          <td>" . $qrBuscaModulos['DES_TPFILTRO'] . "</td>
                          <td>" . $qrBuscaModulos['DES_FILTRO'] . "</td>
                        </tr>
                        <input type='hidden' id='ret_COD_FILTRO_" . $count . "' value='" . $qrBuscaModulos['COD_FILTRO'] . "'>
                        <input type='hidden' id='ret_COD_TPFILTRO_" . $count . "' value='" . $qrBuscaModulos['COD_TPFILTRO'] . "'>
                        <input type='hidden' id='ret_DES_FILTRO_" . $count . "' value='" . $qrBuscaModulos['DES_FILTRO'] . "'>
                        ";
    }
?>
    <script>
      carregarPaginacao(<?= $numPaginas ?>);
    </script>
<?php

    break;
}
?>