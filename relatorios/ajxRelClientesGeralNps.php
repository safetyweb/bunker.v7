<?php

include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

// fnDebug('true');

$opcao = $_GET['opcao'];
$cod_empresa = fnDecode($_GET['id']);

$cod_pesquisa = fnLimpaCampoZero($_POST['COD_PESQUISA']);
$lojasSelecionadas = $_POST['LOJAS'];
@$nom_cliente = fnLimpaCampo($_POST['NOM_CLIENTE']);
@$des_placa = fnLimpacampo($_REQUEST['DES_PLACA']);
@$num_cartao = fnLimpaCampo($_POST['NUM_CARTAO']);
@$num_cgcecpf = fnLimpaDoc(fnLimpaCampo($_POST['NUM_CGCECPF']));
$dat_ini = (@$_POST['DAT_INI'] == "" ? "" : fnDataSql(@$_POST['DAT_INI']));
$dat_fim = (@$_POST['DAT_FIM'] == "" ? "" : fnDataSql(@$_POST['DAT_FIM']));

if ($nom_cliente != '') {
  $andNome = 'AND CL.NOM_CLIENTE LIKE "' . $nom_cliente . '%"';
} else {
  $andNome = ' ';
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


  switch ($opcao) {

    case 'exportar':

      $nomeRel = $_GET['nomeRel'];
      $arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

      $tipo = fnLimpaCampo($_GET['tipo']);

      $writer = WriterFactory::create(Type::CSV);
      $writer->setFieldDelimiter(';');
      $writer->openToFile($arquivo);

      $sqlCli = "SELECT CL.*, UV.NOM_FANTASI FROM CLIENTES CL
                INNER JOIN WEBTOOLS.UNIDADEVENDA UV ON UV.COD_UNIVEND = CL.COD_UNIVEND
                WHERE CL.COD_UNIVEND IN($lojasSelecionadas)
                AND CL.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
                AND CL.LOG_CADTOTEM = 'S'
                AND CL.COD_CADPESQ != 0
                AND CL.COD_EMPRESA = $cod_empresa
              ORDER BY CL.COD_CADPESQ DESC, CL.NOM_CLIENTE ASC";

      // fnEscreve($sqlCli);

      $arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);

      $array = array();
      $newRow = array();
      $countHeader = 0;
      $countHorario = 0;
      $cod_pesquisa_anterior = 0;

      while($qrCli = mysqli_fetch_assoc($arrayCli)){

          if($cod_pesquisa_anterior != $qrCli['COD_CADPESQ']){

              if($cod_pesquisa_anterior != 0){
                  array_push($newRow, ' ');
                  $array[] = $newRow;
                  $newRow = array();
                  $countHeader = 0;
              }

              $sqlPerg = "SELECT * FROM MODELOPESQUISA 
                          WHERE COD_EMPRESA = $cod_empresa 
                          AND COD_TEMPLATE = $qrCli[COD_CADPESQ] 
                          AND COD_BLPESQU = 2
                          AND DAT_EXCLUSA IS NULL";

              // fnEscreve($sqlPerg);

              $arrayPerg = mysqli_query(connTemp($cod_empresa,''),$sqlPerg);

              while ($qrPerg = mysqli_fetch_assoc($arrayPerg)) {

                if($countHeader == 0){
                  array_push($newRow, 'LOJA');
                  array_push($newRow, 'NOME');
                  array_push($newRow, 'CPF');
                  array_push($newRow, 'HORÁRIO');
                }

                array_push($newRow, $qrPerg['DES_PERGUNTA']);

                $countHeader++;

              }

            $cod_pesquisa_anterior = $qrCli['COD_CADPESQ'];

          }

          $array[] = $newRow;

          $newRow = array();

          array_push($newRow, $qrCli['NOM_FANTASI']);
          array_push($newRow, $qrCli['NOM_CLIENTE']);
          array_push($newRow, $qrCli['NUM_CGCECPF']);

          $arrayPerg = mysqli_query(connTemp($cod_empresa,''),$sqlPerg);

          $countHorario = 0;

          while ($qrPerg = mysqli_fetch_assoc($arrayPerg)) {

              $sqlResp = "SELECT DP.DT_HORAINICIAL AS HORARIO, DPI.RESPOSTA_TEXTO 
                          FROM DADOS_PESQUISA_ITENS DPI
                          INNER JOIN DADOS_PESQUISA DP ON DP.COD_REGISTRO = DPI.COD_REGISTRO
                          WHERE DPI.COD_PERGUNTA = $qrPerg[COD_REGISTR]
                          AND DPI.COD_CLIENTE = $qrCli[COD_CLIENTE]";

              // fnEscreve($sqlResp);

              $qrResposta = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlResp));

              if($countHorario == 0){
                array_push($newRow, $qrResposta['HORARIO']);
              }

              switch ($qrPerg["DES_TIPO_RESPOSTA"]) {
                case 'R':
                case 'RB':

                  // lista e bloco
                  $resposta = json_decode($qrResposta['RESPOSTA_TEXTO'],true);
                  $resposta = implode(',', $resposta);
                  $resposta = explode(',', $resposta);
                  array_push($newRow, $resposta[0]);

                break;

                case 'C':
                case 'CB':

                  // lista multi e bloco multi
                  $resp = "";
                  $resposta = json_decode($qrResposta['RESPOSTA_TEXTO'],true);
                  foreach($resposta as $rk => $rv){
                    $resp .= $rv.", ";
                  }

                  $resp = ltrim(rtrim(trim($resp),','),',');
                  array_push($newRow, $resp);

                break;

                case 'A':

                  // avaliacao
                  $resp = "";
                  $resposta = json_decode($qrResposta['RESPOSTA_TEXTO'],true);

                  print_r($resposta);

                  foreach($resposta as $rk => $rv){

                    if($rv['opcao'] == 'S'){
                      $like = "UP";
                    }else{
                      $like = "DOWN";
                    }

                    $resp .= $rv['texto'].": ".$like.", ";
                  }

                  $resp = ltrim(rtrim(trim($resp),','),',');

                  array_push($newRow, $resp);

                break;
                
                default:

                  // texto
                  array_push($newRow, $qrResposta['RESPOSTA_TEXTO']);

                break;
              }

              $countHorario++;

          }

          $array[] = $newRow;
          $newRow = array();

      }

      $writer->addRows($array);

      $writer->close();

    break;

    case 'paginar':

      $itens_por_pagina = $_GET['itens_por_pagina'];
      $pagina = $_GET['idPage'];

      //============================
      //paginação
      $sql = "SELECT COUNT(CL.COD_CLIENTE) AS CONTADOR FROM  " . connTemp($cod_empresa, 'true') . ".CLIENTES CL
              WHERE CL.COD_EMPRESA = " . $cod_empresa . "
              AND CL.COD_UNIVEND  IN  ($lojasSelecionadas)
              AND CL.LOG_CADTOTEM = 'S'
              AND CL.COD_CADPESQ != 0
              " . $andCodigo . "
              " . $andNome . "
              " . $andCartao . "
              " . $andCpf . "
              ORDER BY NOM_CLIENTE ";

      $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
      $total_itens_por_pagina = mysqli_fetch_assoc($retorno);

      $numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

      //variavel para calcular o início da visualização com base na página atual
      $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


      //lista de clientes
      $sql = "SELECT CL.*, PQ.DES_PESQUISA, UV.NOM_FANTASI
              FROM CLIENTES CL
              INNER JOIN PESQUISA PQ ON PQ.COD_PESQUISA = CL.COD_CADPESQ
              INNER JOIN WEBTOOLS.UNIDADEVENDA UV ON UV.COD_UNIVEND = CL.COD_UNIVEND
              WHERE CL.COD_EMPRESA = $cod_empresa
              AND (CL.COD_UNIVEND IN ($lojasSelecionadas) OR CL.COD_UNIVEND IS NULL OR CL.COD_UNIVEND = 0)
              AND CL.LOG_CADTOTEM = 'S'
              AND CL.COD_CADPESQ != 0
                $andNome
                $andCartao
                $andCpf
                $andDatIni
                $andDatFim
              ORDER BY CL.NOM_CLIENTE 
              LIMIT $inicio,$itens_por_pagina";

      $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
      // fnEscreve($sql);
      //  echo "___".$sql."___";
      $count = 0;

      while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {
        $count++;

        echo"
            <tr>
              <td><small>" . $qrListaEmpresas['COD_CLIENTE'] . "</small></td>
              <td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "' target='_blank'>" . $qrListaEmpresas['NOM_CLIENTE'] . "&nbsp;" . $mostraCracha . "</a></small></td>
              <td><small>" . fnDataFull($qrListaEmpresas['DAT_CADASTR']) . "</small></td>
              <td><small>" . $qrListaEmpresas['NOM_FANTASI'] . "</small></td>
              <td><small>" . $qrListaEmpresas['DES_PESQUISA'] . "</small></td>
              <td><small>" . $qrListaEmpresas['NUM_CARTAO'] . "</small></td>
              <td><small>" . $qrListaEmpresas['NUM_CGCECPF'] . "</small></td>
              <td><small>" . $qrListaEmpresas['NUM_TELEFON'] . "</small></td>
              <td><small>" . $qrListaEmpresas['NUM_CELULAR'] . "</small></td>
              <td><small>" . strtolower($qrListaEmpresas['DES_EMAILUS']) . "</small></td>
            </tr>
            <input type='hidden' id='ret_COD_CLIENTE_" . $count . "' value='" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "'>
            <input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . fnEncode($cod_empresa) . "'>
            ";
      }

  break;  
}
?>