<?php
include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

$opcao = "";
$dat_ini = "";
$dat_fim = "";
$tipoVenda = "";
$lojasSelecionadas = "";
$autoriza = "";
$dias30 = "";
$hoje = "";
$andCreditos = "";
$tamanho = "";
$maiusculas = "";
$numeros = "";
$simbolos = "";
$lmin = "";
$lmai = "";
$num = "";
$simb = "";
$retorno = "";
$caracteres = "";
$len = "";
$n = "";
$rand = "";
$ARRAY_UNIDADE1 = [];
$ARRAY_UNIDADE = [];
$nomeRel = "";
$arquivo = "";
$writer = "";
$arrayQuery = [];
$array = [];
$qrListaVendas = "";
$newRow = "";
$NOM_ARRAY_UNIDADE = [];
$countLinha = "";
$vendaIni = "";
$totalVenda = 0;
$sqlToken = "";
$tokenExec = "";
$queryToken = "";
$colunaEspecial = "";
$temToken = "";
$statusToken = "";
$arrayFinal = [];
$nomes_colunas = "";
$objeto = "";
$arrayColumnsNames = [];
$totalitens_por_pagina = 0;
$inicio = "";
$ARRAY_VENDEDOR1 = [];
$ARRAY_VENDEDOR = [];
$NOM_ARRAY_NON_VENDEDOR = [];
$vendaFim = "";


$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$tipoVenda = @$_POST['tipoVenda'];
$lojasSelecionadas = @$_POST['LOJAS'];

$autoriza = fnLimpaCampoZero(@$_POST['AUTORIZA']);

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

if ($tipoVenda == "T") {
	$andCreditos = " ";
} else {
	$andCreditos = "AND B.NUM_CARTAO != 0 ";
}

// function fngeraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
// {
// 	//$lmin = 'abcdefghijklmnopqrstuvwxyz';
// 	$lmai = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
// 	$num = '123456789';
// 	//$simb = '@#$';
// 	$retorno = '';
// 	$caracteres = '';
// 	$caracteres .= $lmin;
// 	if ($maiusculas) $caracteres .= $lmai;
// 	if ($numeros) $caracteres .= $num;
// 	if ($simbolos) $caracteres .= $simb;
// 	$len = strlen($caracteres);
// 	for ($n = 1; $n <= $tamanho; $n++) {
// 	$rand = mt_rand(1, $len);
// 	$retorno .= $caracteres[$rand-1];
// }
// 	return $retorno;
// }

//========================
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

//rotina de controle de acessos por módulo
include "../moduloControlaAcesso.php";


switch ($opcao) {
	case 'exportar':

		$nomeRel = @$_GET['nomeRel'];
		$arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$writer = WriterFactory::create(Type::CSV);
		$writer->setFieldDelimiter(';');
		$writer->openToFile($arquivo);

		// Filtro por Grupo de Lojas
		include "../filtroGrupoLojas.php";
		//============================

		$sql = "SELECT A.COD_VENDA, 
				   A.COD_VENDAPDV, 
                   A.COD_UNIVEND,
				   A.COD_MAQUINA, 
				   A.COD_VENDEDOR, 
				   A.COD_CUPOM, 
				   B.COD_CLIENTE, 
				   B.NOM_CLIENTE, 
				   B.NUM_CARTAO, 
				   A.DAT_CADASTR, 
				   A.VAL_TOTVENDA, 
				   C.NOM_USUARIO AS VENDEDOR, 
				   E.NOM_USUARIO AS OPERADOR, 
				   F.DES_TOKEM, 
				   G.NOM_ENTIDAD 
				   FROM VENDAS A  FORCE INDEX (COD_UNIVEND,COD_CLIENTE,COD_STATUSCRED,DAT_CADASTR) 
				   INNER JOIN CLIENTES B ON A.COD_CLIENTE=B.COD_CLIENTE 
				   LEFT JOIN webtools.USUARIOS C ON C.COD_USUARIO = A.COD_VENDEDOR 
				   LEFT JOIN webtools.USUARIOS E ON E.COD_USUARIO = A.COD_USUCADA 
				   LEFT JOIN tokem F ON F.COD_PDV = A.cod_vendapdv LEFT JOIN entidade G ON G.COD_ENTIDAD=B.COD_ENTIDAD 
				WHERE 
				  A.DAT_CADASTR BETWEEN '$dat_ini 00:00:00'  AND '$dat_fim 23:59:59' AND
				  A.COD_EMPRESA = $cod_empresa
				  AND A.COD_UNIVEND IN($lojasSelecionadas)
				  AND A.COD_STATUSCRED in (0,1,2,3,4,5,7,8) 
				  $andCreditos
				  order by A.DAT_CADASTR desc";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();

		while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

			$cont = 0;

			$newRow = array();

			$NOM_ARRAY_UNIDADE = (array_search($qrListaVendas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));

			if ($countLinha == 1) {
				$vendaIni = $qrListaVendas['DAT_CADASTR'];
			}

			$totalVenda = $totalVenda + $qrListaVendas['VAL_TOTVENDA'];

			$sqlToken = "select 
	                                                                        itemvenda.COD_VENDA,								
	                                                                        itemvenda.DES_PARAM1,
	                                                                        itemvenda.DES_PARAM2,
	                                                                        tokem.des_tokem,
	                                                                        tokem.COD_PDV,
	                                                                        tokem.cod_cliente,
	                                                                        max(if(itemvenda.DES_PARAM2=tokem.des_tokem,'S','N')) temToken
	                                                                        from itemvenda 
	                                                                        left join tokem on itemvenda.DES_PARAM2=tokem.des_tokem
	                                                                        where 
	                                                                                cod_venda='" . $qrListaVendas['COD_VENDA'] . "' limit 1 ";

			$tokenExec = mysqli_query(connTemp($cod_empresa, ''), $sqlToken);
			$queryToken = mysqli_fetch_assoc($tokenExec);
			//fnEscreve($sqlToken);
			/*
				echo "<pre>";
				print_r($queryToken);
				echo "</pre>";
				*/

			$colunaEspecial = $queryToken['DES_PARAM2'];
			if ($queryToken['temToken'] == 'S') {
				if ($qrListaVendas['COD_VENDAPDV'] == $queryToken['COD_PDV']) {
					$temToken = 'OK';
					$statusToken = "Token válido";
				} elseif ($qrListaVendas['NUM_CARTAO'] == $queryToken['cod_cliente']) {
					$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
					$statusToken = "Token já utilizado";
				} else {
					$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
					$statusToken = "Token inválido";
				}

				if ($qrListaVendas['NUM_CARTAO'] != $queryToken['cod_cliente']) {
					//$temToken = '<i class="fa fa-times-circle-o text-danger" aria-hidden="true"></i>';
					$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
					$statusToken = "Token pertence a outro usuario";
				}
			} elseif (
				!empty($qrListaVendas['NUM_CARTAO']) &&
				($queryToken['des_tokem'] != $queryToken['DES_PARAM2'])
			) {
				$temToken = '<i class="fa fa-lock text-danger" aria-hidden="true"></i>';
				$statusToken = "Token inexistente";
			} else {
				$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';

				if (!empty($queryToken['DES_PARAM1'])) {
					//$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
					$temToken = '<i class="fa fa-unlock-alt text-warning" aria-hidden="true"></i>';
					$statusToken = "Token não informado";
				} else {
					$statusToken = "";
				}
			}


			if ($qrListaVendas['COD_CLIENTE'] == 58272) {
				$temToken = "";
			}

			if (($qrListaVendas['COD_CLIENTE'] == 58272) and (!empty($queryToken['DES_PARAM1']))) {
				$temToken = '<i class="fa fa-exclamation-triangle text-danger" aria-hidden="true"></i>';
				$statusToken = "Cliente não cadastrado";
			}

			$arrayFinal = [];
			array_push($arrayFinal, $qrListaVendas['COD_VENDA']);
			array_push($arrayFinal, $qrListaVendas['NOM_CLIENTE']);
			array_push($arrayFinal, $qrListaVendas['NOM_ENTIDAD']);
			array_push($arrayFinal, $qrListaVendas['NUM_CARTAO']);
			array_push($arrayFinal, $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']);
			array_push($arrayFinal, fnDataFull($qrListaVendas['DAT_CADASTR']));
			array_push($arrayFinal, "R$ " . fnValor($qrListaVendas['VAL_TOTVENDA'], 2));
			array_push($arrayFinal, $queryToken['DES_PARAM1']);
			array_push($arrayFinal, $queryToken['DES_PARAM2']);
			array_push($arrayFinal, $qrListaVendas['DES_TOKEM']);
			array_push($arrayFinal, $statusToken);

			$nomes_colunas = array('AUTORIZAÇÃO', 'CLIENTE', 'CONVÊNIO', 'COD. EXTERNO', 'LOJA', 'DATA/HORA', 'VL. VENDA', 'PLACA', 'TOKEN', 'TOKEN GERADO', 'CONFORMIDADE');



			foreach ($arrayFinal as $objeto) {
				array_push($newRow, $objeto);
			}

			$array[] = $newRow;
			$cont++;
		}

		$arrayColumnsNames = array();
		foreach ($nomes_colunas as $objeto) {
			array_push($arrayColumnsNames, $objeto);
		}

		$writer->addRow($arrayColumnsNames);
		$writer->addRows($array);

		$writer->close();

		break;

	case 'paginar':

		$sql = "
					
			SELECT  count(*) as contador,
			SUM(A.VAL_TOTVENDA) AS VAL_TOTVENDA
			   FROM VENDAS A  FORCE INDEX (COD_UNIVEND,COD_CLIENTE,COD_STATUSCRED,DAT_CADASTR) 
			   INNER JOIN CLIENTES B ON A.COD_CLIENTE=B.COD_CLIENTE 
			   LEFT JOIN webtools.USUARIOS C ON C.COD_USUARIO = A.COD_VENDEDOR 
			   LEFT JOIN webtools.UNIDADEVENDA D ON D.COD_UNIVEND = A.COD_UNIVEND 
			   LEFT JOIN webtools.USUARIOS E ON E.COD_USUARIO = A.COD_USUCADA 
			   LEFT JOIN tokem F ON F.COD_PDV = A.cod_vendapdv LEFT JOIN entidade G ON G.COD_ENTIDAD=B.COD_ENTIDAD 
			WHERE 
				  A.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' AND
			  A.COD_EMPRESA = $cod_empresa  AND 
			  A.COD_UNIVEND IN($lojasSelecionadas) AND
			   A.COD_STATUSCRED in (0,1,2,3,4,5,7,8) 
			   $andCreditos
			  order by A.DAT_CADASTR desc														
				";

		//fnEscreve($sql);

		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$totalitens_por_pagina = mysqli_fetch_assoc($retorno);

		$numPaginas = ceil($totalitens_por_pagina['contador'] / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		/*$ARRAY_VENDEDOR1=array(
					   'sql'=>"select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa",
					   'cod_empresa'=>$cod_empresa,
					   'conntadm'=>$connAdm->connAdm(),
					   'IN'=>'N',
					   'nomecampo'=>'',
					   'conntemp'=>'',
					   'SQLIN'=> ""   
					   );
		$ARRAY_VENDEDOR=fnUniVENDEDOR($ARRAY_VENDEDOR1);*/

		//=======================================================


		//========================
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
		/*$ARRAY_VENDEDOR1=array(
														   'sql'=>"select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa",
														   'cod_empresa'=>$cod_empresa,
														   'conntadm'=>$connAdm->connAdm(),
														   'IN'=>'N',
														   'nomecampo'=>'',
														   'conntemp'=>'',
														   'SQLIN'=> ""   
														   );
											$ARRAY_VENDEDOR=fnUniVENDEDOR($ARRAY_VENDEDOR1);*/

		//=======================================================
		$sql = "

													SELECT A.COD_VENDA, 
													   A.COD_VENDAPDV, 
                                                                                                            A.COD_UNIVEND,
													   A.COD_MAQUINA, 
													   A.COD_VENDEDOR, 
													   A.COD_CUPOM, 
													   B.COD_CLIENTE, 
													   B.NOM_CLIENTE, 
													   B.NUM_CARTAO, 
													   A.DAT_CADASTR_WS, 
													   A.VAL_TOTVENDA, 
													   C.NOM_USUARIO AS VENDEDOR, 
													   E.NOM_USUARIO AS OPERADOR, 
													   F.DES_TOKEM, 
													   G.NOM_ENTIDAD 
													   FROM VENDAS A  FORCE INDEX (COD_UNIVEND,COD_CLIENTE,COD_STATUSCRED,DAT_CADASTR) 
													   INNER JOIN CLIENTES B ON A.COD_CLIENTE=B.COD_CLIENTE 
													   LEFT JOIN webtools.USUARIOS C ON C.COD_USUARIO = A.COD_VENDEDOR 
													   LEFT JOIN webtools.USUARIOS E ON E.COD_USUARIO = A.COD_USUCADA 
													   LEFT JOIN tokem F ON F.COD_PDV = A.cod_vendapdv 
                                                                                                           LEFT JOIN entidade G ON G.COD_ENTIDAD=B.COD_ENTIDAD 
													WHERE 
													  A.DAT_CADASTR_WS BETWEEN '$dat_ini 00:00:00'  AND '$dat_fim 23:59:59' AND
													  A.COD_EMPRESA = $cod_empresa
													  AND A.COD_UNIVEND IN($lojasSelecionadas)
													  AND A.COD_STATUSCRED in (0,1,2,3,4,5,7,8) 
													  $andCreditos
													  order by A.DAT_CADASTR_WS desc
													  limit $inicio,$itens_por_pagina
													  ";

		//fnEscreve($sql);											
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$countLinha = 1;
		while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
			$NOM_ARRAY_UNIDADE = (array_search($qrListaVendas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
			// $NOM_ARRAY_NON_VENDEDOR=(array_search($qrListaVendas['COD_VENDEDOR'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO'))); 

			if ($countLinha == 1) {
				$vendaIni = @$qrListaVendas['DAT_CADASTR'];
			}

			$totalVenda = $totalVenda + $qrListaVendas['VAL_TOTVENDA'];

			$sqlToken = "select 
                                                                                                            itemvenda.COD_VENDA,								
                                                                                                            itemvenda.DES_PARAM1,
                                                                                                            itemvenda.DES_PARAM2,
                                                                                                            tokem.des_tokem,
                                                                                                            tokem.COD_PDV,
                                                                                                            tokem.cod_cliente,
                                                                                                            max(if(itemvenda.DES_PARAM2=tokem.des_tokem,'S','N')) temToken
                                                                                                            from itemvenda 
                                                                                                            left join tokem on itemvenda.DES_PARAM2=tokem.des_tokem
                                                                                                            where 
                                                                                                                    cod_venda='" . $qrListaVendas['COD_VENDA'] . "' limit 1 ";

			$tokenExec = mysqli_query(connTemp($cod_empresa, ''), $sqlToken);
			$queryToken = mysqli_fetch_assoc($tokenExec);
			//fnEscreve($sqlToken);
			/*
												echo "<pre>";
												print_r($queryToken);
												echo "</pre>";
												*/

			$colunaEspecial = $queryToken['DES_PARAM2'];
			if ($queryToken['temToken'] == 'S') {
				if ($qrListaVendas['COD_VENDAPDV'] == $queryToken['COD_PDV']) {
					$temToken = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
					$statusToken = "Token válido";
				} elseif ($qrListaVendas['NUM_CARTAO'] == $queryToken['cod_cliente']) {
					$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
					$statusToken = "Token já utilizado";
				} else {
					$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
					$statusToken = "Token inválido";
				}

				if ($qrListaVendas['NUM_CARTAO'] != $queryToken['cod_cliente']) {
					//$temToken = '<i class="fa fa-times-circle-o text-danger" aria-hidden="true"></i>';
					$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
					$statusToken = "Token pertence a outro usuario";
				}
			} elseif (
				!empty($qrListaVendas['NUM_CARTAO']) &&
				($queryToken['des_tokem'] != $queryToken['DES_PARAM2'])
			) {
				$temToken = '<i class="fa fa-lock text-danger" aria-hidden="true"></i>';
				$statusToken = "Token inexistente";
			} else {
				$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';

				if (!empty($queryToken['DES_PARAM1'])) {
					//$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
					$temToken = '<i class="fa fa-unlock-alt text-warning" aria-hidden="true"></i>';
					$statusToken = "Token não informado";
				} else {
					$statusToken = "";
				}
			}


			if ($qrListaVendas['COD_CLIENTE'] == 58272) {
				$temToken = "";
			}

			if (($qrListaVendas['COD_CLIENTE'] == 58272) and (!empty($queryToken['DES_PARAM1']))) {
				$temToken = '<i class="fa fa-exclamation-triangle text-danger" aria-hidden="true"></i>';
				$statusToken = "Cliente não cadastrado";
			}


?>
			<tr style="background-color: #fff;" class="abreDetail_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
				<td><?php echo $qrListaVendas['COD_VENDA']; ?> </td>
				<?php
				if ($autoriza == 1) {
				?>
					<td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE']; ?></a></td>
				<?php
				} else {
				?>
					<td><?php echo $qrListaVendas['NOM_CLIENTE']; ?></td>
				<?php
				}
				?>
				<td><small><?php echo $qrListaVendas['NOM_ENTIDAD']; ?></small></td>
				<td><small><?php echo $qrListaVendas['NUM_CARTAO']; ?></small></td>
				<td><small><?php echo $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']; ?></small></td>
				<td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR_WS']); ?></small></td>
				<td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['VAL_TOTVENDA'], 2); ?></small></td>
				<!--
													  <td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['VAL_CREDITOS'], 2); ?></small></td>
													  <td><small><?php echo fnDataFull($qrListaVendas['DAT_EXPIRA']); ?></small></td>
													  -->
				<td><small><?php echo $queryToken['DES_PARAM1']; ?></small></td>
				<!-- <td><small><?php //echo $qrListaVendas['COD_MAQ_VENDEDOR']; 
								?></small></td>
													  <td><small><?php //echo $queryToken['DES_PUINA']; 
																	?></small></td>-->
				<td><small><?php echo $queryToken['DES_PARAM2']; ?> </small></td>
				<td><small><?php echo $qrListaVendas['DES_TOKEM']; ?> </small></td>

				<td class="text-center"><small><?php echo $temToken; ?></small></td>
				<td class="text-center"><small><?php echo $statusToken; ?></small></td>
			</tr>
		<?php

			$vendaFim = @$qrListaVendas['DAT_CADASTR'];
			$countLinha++;
		}

		?>

<?php


		break;
}
?>