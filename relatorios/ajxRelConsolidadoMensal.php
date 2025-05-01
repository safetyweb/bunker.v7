<?php

include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

echo fnDebug('true');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$hoje = '';
$cod_univend = '';
$ARRAY_UNIDADE = [];


$opcao = @$_GET['opcao'];
$cod_empresa = fnDecode(@$_GET['id']);
$casasDec = @$_REQUEST['CASAS_DEC'];
$cod_univend = @$_POST['COD_UNIVEND'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$lojasSelecionadas = @$_POST['LOJAS'];

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

if (is_array($cod_univend)) {
	$cod_univend = "9999";
} elseif (strlen($cod_univend) == 0) {
	$cod_univend = "9999";
}

//faz pesquisa por revenda (geral)
if ($cod_univend == "9999") {
	$temUnivend = "N";
} else {
	$temUnivend = "S";
}

switch ($opcao) {
	case 'exportar':

		$nomeRel = $_GET['nomeRel'];
		$arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$writer = WriterFactory::create(Type::CSV);
		$writer->setFieldDelimiter(';');
		$writer->openToFile($arquivo);
		/*$ARRAY_UNIDADE1=array(
                                            'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa",
                                            'cod_empresa'=>$cod_empresa,
                                            'conntadm'=>$connAdm->connAdm(),
                                            'IN'=>'N',
                                            'nomecampo'=>'',
                                            'conntemp'=>'',
                                            'SQLIN'=> ""   
                                            );
                        $ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);
                         * 
                         */

		//CUIDADO COM A POSIÇÃO DO SELECT - DIFERENTE DO ORIGINAL
		$sql = "SELECT uni.NOM_FANTASI,
								A.cod_univend COD_UNIVEND,
								Sum(A.qtd_totfideliz) QTD_TOTFIDELIZ, 								
								Round(Sum(A.val_totfideliz), 2) VAL_TOTFIDELIZ, 
								Sum(qtd_cliente_fideliz) QTD_CLIENTE_FIDELIZ, 
								(SELECT Count(*) FROM clientes WHERE clientes.cod_univend = A.cod_univend AND dat_cadastr BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' ) CLIENTES_PERIODO, 
                                                                SUM(D.QTD_CLIENTE_GERADO) QTD_CLIENTE_GERADO,								
                                                                Round(SUM(D.VAL_CREDITO_GERADO), $casasDec) 'CREDITOS/PONTOS GERADOS',
								Round(( Round(Sum(A.val_totfideliz), 2) / Sum(A.qtd_totfideliz)  ), 2) VAL_TKTMEDIO, 
								Round(( Round(Sum(A.val_totfideliz), 2) / Sum(A.qtd_cliente_fideliz)  ), 2) VAL_CLIENTE, 
								Sum(D.qtd_resgate) QTD_RESGATE, 
								Round(SUM(D.VAL_RESGATE),2) VAL_RESGATE,
								Sum(D.qtd_cliente_resgate) QTD_CLIENTE_RESGATE, 
								Round(Sum(A.val_totvenda), 2) VAL_TOTVENDA, 
								Round((( Sum(A.qtd_totfideliz) / Sum(A.qtd_totvenda) ) * 100 ), 2) PCT_FIDELIZADO, 
								Sum(Ifnull(A.qtd_ticket, 0)) QTD_TICKET, 
								Round(Sum(Ifnull(A.val_ticket, 0)), 2) VAL_TICKET, 
								SUM(D.VAL_VINCULADO) VAL_VINCULADO,
								Sum(A.qtd_totavulsa) QTD_TOTAVULSA, 
								Sum(A.qtd_clientes_prim) AS CLIENTE_PRIMEIRACOMPRA, 
								Sum(Ifnull(A.qtd_totvenda, 0)) QTD_TOTVENDA 
								
								FROM vendas_diarias A 
								LEFT JOIN CREDITOSDEBITOS_DIARIAS D ON D.COD_EMPRESA=A.COD_EMPRESA AND D.COD_UNIVEND=A.COD_UNIVEND AND D.COD_VENDEDOR=A.COD_VENDEDOR AND D.DAT_MOVIMENTO=A.DAT_MOVIMENTO
                                                                LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
								WHERE A.dat_movimento BETWEEN '$dat_ini' AND '$dat_fim' AND 
								A.cod_empresa = $cod_empresa AND 
								A.cod_univend IN ($lojasSelecionadas) 
								GROUP BY A.cod_univend 
								ORDER BY A.cod_univend";

		//CAMPOS ADICIONAIS
		/*
								Round(Sum(A.val_totvenda), 2) VAL_TOTVENDA, 
								Round((( Sum(A.qtd_totfideliz) / Sum(A.qtd_totvenda) ) * 100 ), 2) PCT_FIDELIZADO, 
								Sum(Ifnull(A.qtd_ticket, 0)) QTD_TICKET, 
								Round(Sum(Ifnull(A.val_ticket, 0)), 2) VAL_TICKET, 
								SUM(D.VAL_VINCULADO) VAL_VINCULADO,
								Sum(A.qtd_totavulsa) QTD_TOTAVULSA, 
								Sum(A.qtd_clientes_prim) AS CLIENTE_PRIMEIRACOMPRA, 
								Sum(Ifnull(A.qtd_totvenda, 0)) QTD_TOTVENDA 
								*/


		//http://adm.bunker.mk/action.do?mod=pz0uTJZkKC8%C2%A2&id=B9TDAr5BXZg%C2%A2
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$qtd_totvenda = 0;
		$val_totvenda = 0;
		$qtd_totfideliz = 0;
		$val_totfideliz = 0;
		$qtd_ticket = 0;
		$val_resgate = 0;
		$val_vinculado = 0;
		$qtd_totavulsa = 0;
		$val_creditogerado = 0;
		$cliente_primeiracompra = 0;
		$clientes_periodo = 0;
		$qtd_cliente_fideliz = 0;
		$qtd_resgate = 0;
		$qtd_cliente_resgate = 0;
		$QTD_CLIENTE_GERADO = 0;

		$array = array();




		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			$NOM_ARRAY_UNIDADE = array_search($row['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND'));


			if ($NOM_ARRAY_UNIDADE !== false) {
				$row['NOM_UNIVEND'] = $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'];
			} else {
				$row['NOM_UNIVEND'] = 'Unidade não encontrada'; // Mensagem padrão se não encontrado
			}


			$newRow = [];


			$qtd_totfideliz += $row['QTD_TOTFIDELIZ'];
			$val_totfideliz += $row['VAL_TOTFIDELIZ'];
			$qtd_ticket += $row['QTD_TICKET'];
			$val_resgate += $row['VAL_RESGATE'];
			$val_vinculado += $row['VAL_VINCULADO'];
			$qtd_totavulsa += $row['QTD_TOTAVULSA'];
			$val_creditogerado += $row['CREDITOS/PONTOS GERADOS'];
			$val_totvenda += $row['VAL_TOTVENDA'];
			$cliente_primeiracompra += $row['CLIENTE_PRIMEIRACOMPRA'];
			$clientes_periodo += $row['CLIENTES_PERIODO'];
			$qtd_cliente_fideliz += $row['QTD_CLIENTE_FIDELIZ'];
			$qtd_resgate += $row['QTD_RESGATE'];
			$qtd_cliente_resgate += $row['QTD_CLIENTE_RESGATE'];
			$QTD_CLIENTE_GERADO += $row['QTD_CLIENTE_GERADO'];
			$cont = 0;


			foreach ($row as $objeto) {

				if ($cont == 7) {
					array_push($newRow, fnValor($objeto, $casasDec));
				} else if (
					$cont == 3 || $cont == 7 || $cont == 8 || $cont == 9 || $cont == 10 ||
					$cont == 11 || $cont == 12 || $cont == 13 || $cont == 14 || $cont == 15 ||
					$cont == 16 || $cont == 17
				) {
					array_push($newRow, fnValor($objeto, 2));
				} else {
					array_push($newRow, $objeto);
				}

				$cont++;
			}


			$array[] = $newRow;
		}


		$ticketMedio = fnValor($val_totfideliz /  $qtd_totfideliz, 2);
		$valorCliente = fnValor($val_totfideliz / $qtd_cliente_fideliz, 2);

		/* ADICIONANDO TOTALIZADOR AO RELATÓRIO */
		array_push($array, array(
			"NOM_UNIVEND" => "Total",
			' ' => '',
			'QTD_TOTFIDELIZ' => $qtd_totfideliz,
			'VAL_TOTFIDELIZ' => fnValor($val_totfideliz, 2),
			'QTD_CLIENTE_FIDELIZ' => $qtd_cliente_fideliz,
			'CLIENTES_PERIODO' => $clientes_periodo,
			'QTD_CLIENTE_GERADO' => $QTD_CLIENTE_GERADO,
			'CREDITOS/PONTOS GERADOS' => fnValor($val_creditogerado, 2),
			'VAL_TKTMEDIO' => $ticketMedio,
			'VAL_CLIENTE' => $valorCliente,
			'QTD_RESGATE' => $qtd_resgate,
			'VAL_RESGATE' => fnValor($val_resgate, 2),
			'QTD_CLIENTE_RESGATE' => $qtd_cliente_resgate,
			'VAL_TOTVENDA' => fnValor($val_totvenda, 2)
		));

		$arrayColumnsNames = array();
		while ($row = mysqli_fetch_field($arrayQuery)) {
			array_push($arrayColumnsNames, $row->name);
		}
		// array_push($arrayColumnsNames, 'NOM_UNIVEND');
		$writer->addRow($arrayColumnsNames);
		$writer->addRows($array);

		$writer->close();

		break;
	case 'paginar':

		break;
}
