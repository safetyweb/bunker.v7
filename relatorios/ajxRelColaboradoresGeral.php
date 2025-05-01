<?php

include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

//fnDebug('true');

$opcao = $_GET['opcao'];
$cod_empresa = fnDecode($_GET['id']);

$cod_univend = $_POST['COD_UNIVEND'];
$lojasSelecionadas = $_POST['LOJAS'];
@$nom_cliente = fnLimpaCampo($_POST['NOM_CLIENTE']);
@$des_placa = fnLimpacampo($_REQUEST['DES_PLACA']);
@$num_cartao = fnLimpaCampo($_POST['NUM_CARTAO']);
@$num_cgcecpf = fnLimpaDoc(fnLimpaCampo($_POST['NUM_CGCECPF']));
$dat_ini = (@$_POST['DAT_INI'] == "" ? "" : fnDataSql(@$_POST['DAT_INI']));
$dat_fim = (@$_POST['DAT_FIM'] == "" ? "" : fnDataSql(@$_POST['DAT_FIM']));
$andAnive = $_POST['AND_ANIVE'];
$andEstus = $_POST['AND_ESTATUS'];

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
    $andNome = 'and cl.nom_cliente like "' . $nom_cliente . '%"';
  } else {
    $andNome = ' ';
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
    $andInativos = "AND CL.LOG_ESTATUS = 'S'";
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

  $ARRAY_UNIDADE1 = array(
    'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
    'cod_empresa' => $cod_empresa,
    'conntadm' => $connAdm->connAdm(),
    'IN' => 'N',
    'nomecampo' => '',
    'conntemp' => '',
    'SQLIN' => ""
  );

  $ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);

  switch ($opcao) {
    case 'exportar':

      $nomeRel = $_GET['nomeRel'];
      $arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

      $writer = WriterFactory::create(Type::CSV);
      $writer->setFieldDelimiter(';');
      $writer->openToFile($arquivo);

      $sql = "SELECT CL.COD_CLIENTE AS 'CODIGO',
              CL.NOM_CLIENTE AS 'COLABORADOR',
              CL.LOG_ESTATUS AS 'ATIVO',
              CL.DAT_CADASTR AS 'CADASTRO',
              CL.DAT_NASCIME AS 'DATA_NASCIMENTO',
              CL.NUM_CGCECPF AS 'CPF',
              CL.NUM_CELULAR as 'CELULAR',
              (SELECT MAX(VAL_LANCAME)
                FROM lancamento_automatico,tip_credito 
                WHERE lancamento_automatico.COD_CLIENTE = CL.COD_CLIENTE AND 
                lancamento_automatico.COD_TIPO = tip_credito.COD_TIPO AND 
                lancamento_automatico.TIP_LANCAME != 'B' AND tip_credito.COD_TIPO=1 LIMIT 1) as 'SALARI0_BASE',
              CL.NUM_RGPESSO AS 'RG',
              CL.DES_ENDEREC AS 'LOGRADOURO',
              CL.NUM_ENDEREC AS 'NUMERO',
              CL.DES_COMPLEM AS 'COMPLEMENTO',
              CL.DES_BAIRROC AS 'BAIRRO',
              CL.NOM_CIDADEC  AS 'CIDADE',
              CL.NUM_CEPOZOF AS 'CEP',
              CL.DAT_ADMISSAO as 'DATA_ADMISSAO',
              CL.NUM_PIS     AS 'NUMERO PIS',
              CL.DAT_INDICAD AS 'DATA EXAME',
              CL.DAT_DEMISSAO AS 'DATA DEMISSAO',
              PP.DES_PROFISS AS 'CARGO'

    					FROM CLIENTES CL 
              LEFT JOIN PROFISSOES_PREF AS PP ON PP.COD_PROFISS = CL.COD_PROFISS
              LEFT JOIN LANCAMENTO_AUTOMATICO AS LA ON LA.COD_CLIENTE = CL.COD_CLIENTE
              INNER JOIN TIP_CREDITO TC ON TC.COD_TIPO = LA.COD_TIPO
    					WHERE CL.COD_EMPRESA = $cod_empresa AND CL.LOG_TITULAR = 'S'
              AND LA.TIP_LANCAME != 'B'
              AND CL.COD_UNIVEND  IN  ($lojasSelecionadas)
                                                    " . $andNome . "
                                                    " . $andCartao . "
                                                    " . $andCpf . "
                                                    " . $andFuncionarios . "
                                                    " . $andInativos . "
                                                      $andDatIni
                                                     $andDatFim
                                                     $andAnive
                                                     $andEstus                                                 
    	                ORDER BY CL.NOM_CLIENTE";
      $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
      //fnEscreve($sql);
      $array = array();
      while ($row = mysqli_fetch_assoc($arrayQuery)) {

        $newRow = array();

        $cont = 0;
        foreach ($row as $objeto) {

          // Colunas que são double converte com fnValor
          if ($cont == 7) {
            array_push($newRow, fnValor($objeto, 2));

            // Muda cod_usucada para nome do usuario
          } elseif ($cont == 17 || $cont == 15) {
            array_push($newRow, fnDataShort($objeto));
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

      $itens_por_pagina = $_GET['itens_por_pagina'];
      $pagina = $_GET['idPage'];

      //============================
      //paginação
      $sql = "SELECT COUNT(CL.COD_CLIENTE) AS CONTADOR FROM  " . connTemp($cod_empresa, 'true') . ".CLIENTES CL
                                  WHERE CL.COD_EMPRESA = " . $cod_empresa . " 
								  AND CL.LOG_TITULAR = 'S'
                                  AND CL.COD_UNIVEND  IN  ($lojasSelecionadas)
																	" . $andCodigo . "
																	" . $andNome . "
																	" . $andCpf . "
																	" . $andFuncionarios . "
																	" . $andInativos . "
                                  " . $andAnive . "
																	ORDER BY NOM_CLIENTE ";

      $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
      $total_itens_por_pagina = mysqli_fetch_assoc($retorno);

      $numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

      //variavel para calcular o início da visualização com base na página atual
      $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


      //lista de clientes
      $sql = "SELECT CL.*,
                (SELECT MAX(VAL_LANCAME)
                  FROM lancamento_automatico,tip_credito 
                  WHERE lancamento_automatico.COD_CLIENTE = CL.COD_CLIENTE AND 
                  lancamento_automatico.COD_TIPO = tip_credito.COD_TIPO AND 
                  lancamento_automatico.TIP_LANCAME != 'B' AND tip_credito.COD_TIPO=1 LIMIT 1) as 'SALARI0_BASE'
                FROM CLIENTES CL
                WHERE CL.COD_EMPRESA = $cod_empresa AND CL.LOG_TITULAR = 'S'
                AND (CL.COD_UNIVEND IN ($lojasSelecionadas) OR CL.COD_UNIVEND IS NULL OR CL.COD_UNIVEND = 0)
                $andNome
                $andCpf
                $andFuncionarios
                $andInativos
                $andDatIni
                $andDatFim
                $andAnive
                ORDER BY CL.NOM_CLIENTE LIMIT $inicio,$itens_por_pagina";

      $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
      //echo($sql);
      //echo "___".$sql."___";
      $count = 0;
      while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {

        $log_funciona = $qrListaEmpresas['LOG_FUNCIONA'];
        if ($log_funciona == "S") {
          $mostraCracha = '<i class="fa fa-address-card" aria-hidden="true"></i>';
        } else {
          $mostraCracha = "";
        }

        if ($qrListaEmpresas['COD_UNIVEND'] != 0) {
          $NOM_ARRAY_UNIDADE = (array_search($qrListaEmpresas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
          $unidade = $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'];
        } else {
          $unidade = "Sem unidade";
        }

        $log_estatus = $qrListaEmpresas['LOG_ESTATUS'];
        if ($log_estatus == "S") {
          $mostraStatus = '<i class="fal fa-check" aria-hidden="true"></i>';
        } else {
          $mostraStatus = '<i class="fal fa-times text-warning" aria-hidden="true"></i>';
        }


        $count++;

        echo "
        <tr>
          <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
          <td><small>" . $qrListaEmpresas['COD_CLIENTE'] . "</small></td>
          <td><small><a href='action.do?mod=" . fnEncode(1688) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "' target='_blank'>" . $qrListaEmpresas['NOM_CLIENTE'] . "&nbsp;" . $mostraCracha . "</a></small></td>
          <td><small>" . $mostraStatus . "</small></td>
          <td><small>" . fnDataFull($qrListaEmpresas['DAT_CADASTR']) . "</small></td>
          <td><small>" . $qrListaEmpresas['DAT_NASCIME'] . "</small></td>
          <td><small>" . $qrListaEmpresas['NUM_CGCECPF'] . "</small></td>
          <td><small>" . $qrListaEmpresas['NUM_CELULAR'] . "</small></td>
    <td class='text-right'><small>R$ " . fnValor($qrListaEmpresas['SALARI0_BASE'], 2) . "</small></td>
        </tr>
        <input type='hidden' id='ret_COD_CLIENTE_" . $count . "' value='" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "'>
        <input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . fnEncode($cod_empresa) . "'>
        ";
      }
  }
}
