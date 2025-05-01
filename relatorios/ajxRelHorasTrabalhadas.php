<?php

include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

fnDebug('true');

$opcao = $_GET['opcao'];

$dat_ini = (@$_POST['DAT_INI'] == "" ? "" : fnDataSql(@$_POST['DAT_INI']));
$dat_fim = (@$_POST['DAT_FIM'] == "" ? "" : fnDataSql(@$_POST['DAT_FIM']));
$cod_usuario = @$_POST["COD_USUARIO"];
$adm = @$_POST["ADM"];

$where = "";
if ($dat_ini <> "") {
	$where .= "AND DATE_FORMAT(H.DAT_ATIVIDADE, '%Y-%m-%d') >= '$dat_ini' ";
}
if ($dat_fim <> "") {
	$where .= "AND DATE_FORMAT(H.DAT_ATIVIDADE, '%Y-%m-%d') <= '$dat_fim' ";
}
if (@$cod_usuario <> "") {
	$where .= "AND H.COD_USUARIO = '$cod_usuario' ";
}

switch ($opcao) {
/*    case 'exportar':

  $nomeRel = $_GET['nomeRel'];
  $arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

  $writer = WriterFactory::create(Type::CSV);
  $writer->setFieldDelimiter(';');
  $writer->openToFile($arquivo);


  $sql = "SELECT CL.COD_CLIENTE,
					   CL.NOM_CLIENTE,
					   CL.DAT_CADASTR,
					   CL.COD_UNIVEND,
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
				WHERE CL.COD_EMPRESA = $cod_empresa
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

  $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());

  $array = array();
  while ($row = mysqli_fetch_assoc($arrayQuery)) {



	$newRow = array();

	$cont = 0;
	foreach ($row as $objeto) {

	  // Colunas que são double converte com fnValor
	  if ($cont == 7 || $cont == 8) {
		array_push($newRow, fnValor($objeto, 2));
		// Muda cod_univend para nome da unidade
	  } else if ($cont == 3) {
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

  break;*/

case 'paginar':

  $itens_por_pagina = $_GET['itens_por_pagina'];
  $pagina = $_GET['idPage'];

//============================
  //paginação
  $sql = "SELECT COUNT(0) AS CONTADOR FROM horas_trabalhadas H WHERE 1=1 $where";
  $retorno = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
  $total_itens_por_pagina = mysqli_fetch_assoc($retorno);

  $numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

  //variavel para calcular o início da visualização com base na página atual
  $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


  //lista
  $sql = "SELECT 
								H.COD_HORA,
								H.COD_USUARIO,
								U.NOM_USUARIO,
								H.DAT_ATIVIDADE,
                CT.DESCRICAO,
								TIME_FORMAT(H.HOR_INICIAL,'%H:%i') HOR_INICIAL,
								TIME_FORMAT(H.HOR_FINAL,'%H:%i') HOR_FINAL,
								H.DES_OBSERVACAO,
								FN_CALCULO_HORAS(H.HOR_INICIAL,H.HOR_FINAL) AS DES_DURACAO
							FROM horas_trabalhadas H
							LEFT JOIN usuarios U ON (U.COD_USUARIO = H.COD_USUARIO)
              LEFT JOIN CENTRO_CUSTO CT ON (CT.ID = H.COD_CENTROCUSTO)
							WHERE 1=1 $where
							ORDER BY H.DAT_ATIVIDADE DESC, H.HOR_INICIAL DESC, H.HOR_FINAL DESC, H.COD_HORA DESC LIMIT $inicio,$itens_por_pagina";

  $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
  //fnEscreve($sql);
  //  echo "___".$sql."___";
  $count = 0;
  while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
	$count++;


	echo"
		<tr>
		  <td class='text-center'><input type='hidden' name='radio1' onclick='retornaForm(" . $count . ")'></th>
		  <td><small>" . $qrLista['NOM_USUARIO'] . "</small></td>
		  <td><small>" . $qrLista['DES_OBSERVACAO'] . "</small></td>
      <td><small>" . $qrLista['DESCRICAO'] . "</small></td>
		  <td><small>" . fnFormatDate($qrLista['DAT_ATIVIDADE']) . "</small></td>
		  <td><small>" . $qrLista['HOR_INICIAL'] . "</small></td>
		  <td><small>" . $qrLista['HOR_FINAL'] . "</small></td>
		  <td><small>" . $qrLista['DES_DURACAO'] . "</small></td>
		  <td class='text-center'>
			".($adm?"<a class='btn btn-xs btn-info addBox transparency' onClick='retornaForm(" . $count . ")'><i class='fas fa-pencil'></i> Editar </a>":"")."
		  </td>
		  <td class='text-center'>
			".($qrLista['HOR_FINAL'] == ""?"<a class='btn btn-xs btn-success transparency' onClick='retornaForm(" . $count . ")'><i class='fa fas fa-arrow-down'></i> Saída </a>":"")."
		  </td>
		</tr>
		<input type='hidden' id='ret_COD_HORA_" . $count . "' value='" . fnEncode($qrLista['COD_HORA']) . "'>
		";
  }
}



?>