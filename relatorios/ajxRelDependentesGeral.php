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
$andEstatus = $_POST['AND_ESTATUS'];

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

  if($cod_empresa == 19){
    $selectPlaca = "(SELECT MAX(DES_PLACA) FROM VEICULOS WHERE COD_CLIENTE = CL.COD_CLIENTE) AS DES_PLACA,";
  }else{
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
						   CL.NOM_CLIENTE AS 'DEPENDENTE',
						   (SELECT NOM_CLIENTE FROM CLIENTES WHERE CLIENTES.COD_CLIENTE = CL.COD_TITULAR) AS 'TITULAR',
						   CL.DAT_NASCIME AS 'DATA_NASCIMENTO',
						   CL.IDADE AS 'IDADE',
						   CL.COD_UNIVEND as 'UNIDADE',
               CL.DAT_ADMISSAO as 'DATA_ADMISSAO'

					FROM CLIENTES CL 
					WHERE CL.COD_EMPRESA = $cod_empresa AND CL.LOG_TITULAR = 'N'
          
                                                " . $andNome . "
                                                " . $andCartao . "
                                                " . $andCpf . "
                                                " . $andFuncionarios . "
                                                " . $andInativos . "
                                                  $andDatIni
                                                 $andDatFim
                                                 $andAnive
                                                 $andEstatus                                                 
	                ORDER BY CL.NOM_CLIENTE";

     //echo $sql;

      $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());

      $array = array();
      while ($row = mysqli_fetch_assoc($arrayQuery)) {

        $newRow = array();

        $cont = 0;
        foreach ($row as $objeto) {

          if ($cont == 5) {
            $NOM_ARRAY_UNIDADE = (array_search($objeto, array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
            array_push($newRow, $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']);
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
      $sql = "SELECT COUNT(COD_CLIENTE) AS CONTADOR FROM  " . connTemp($cod_empresa, 'true') . ".CLIENTES CL WHERE CL.COD_EMPRESA = $cod_empresa 
            AND CL.LOG_TITULAR = 'N'
			AND CL.COD_UNIVEND  IN  ($lojasSelecionadas)
						$andNome
						$andCartao
						$andCpf
						$andFuncionarios
						$andInativos
						$andPlaca
            $andAnive
            $andEstatus
					ORDER BY NOM_CLIENTE ";

      $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
      $total_itens_por_pagina = mysqli_fetch_assoc($retorno);

      $numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

      //variavel para calcular o início da visualização com base na página atual
      $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


	  //lista de clientes
	  $sql = "SELECT CL.*,
			  (SELECT NOM_CLIENTE FROM CLIENTES WHERE COD_CLIENTE=CL.COD_TITULAR) AS NOM_TITULAR,
			  (SELECT COD_CLIENTE FROM CLIENTES WHERE COD_CLIENTE=CL.COD_TITULAR) AS COD_TITULAR
			  FROM CLIENTES CL
			  WHERE CL.COD_EMPRESA = $cod_empresa AND CL.LOG_TITULAR = 'N'
			  AND (CL.COD_UNIVEND IN ($lojasSelecionadas) OR CL.COD_UNIVEND IS NULL OR CL.COD_UNIVEND = 0)
				$andNome
				$andCpf
				$andFuncionarios
				$andInativos
				$andDatIni
				$andDatFim
        $andAnive
        $andEstatus
			  ORDER BY CL.NOM_CLIENTE LIMIT $inicio,$itens_por_pagina";
			  
	  $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
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
		
		$log_estatus = $qrListaEmpresas['LOG_ESTATUS'];
		if ($log_estatus == "S") {
		  $mostraStatus = '<i class="fal fa-check" aria-hidden="true"></i>';
		} else {
		  $mostraStatus = '<i class="fal fa-times text-warning" aria-hidden="true"></i>';
		}

        $NOM_ARRAY_UNIDADE = (array_search($qrListaEmpresas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
        $count++;

        echo"
          <tr>
		  
			<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
			<td><small>" . $qrListaEmpresas['COD_CLIENTE'] . "</small></td>
			<td><small><a href='action.do?mod=" . fnEncode(1688) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "' target='_blank'>" . $qrListaEmpresas['NOM_CLIENTE'] . "&nbsp;" . $mostraCracha . "</a></small></td>
			<td><small><a href='action.do?mod=" . fnEncode(1688) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaEmpresas['COD_TITULAR']) . "' target='_blank'>" . $qrListaEmpresas['NOM_TITULAR'] . "&nbsp;" . $mostraCracha . "</a></small></td>
			<td><small>" . $qrListaEmpresas['DAT_NASCIME'] . "</small></td>
			<td><small>" . $qrListaEmpresas['IDADE'] . "</small></td>
			<td><small>" . $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'] . "</small></td>
		  
          </tr>
          <input type='hidden' id='ret_COD_CLIENTE_" . $count . "' value='" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "'>
          <input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . fnEncode($cod_empresa) . "'>
          ";
      }
  }

  
}
?>