<?php

include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$opcao = "";
$casasDec = "";
$dat_ini = "";
$dat_fim = "";
$lojasSelecionadas = "";
$rel = "";
$dias30 = "";
$hoje = "";
$temUnivend = "";
$nomeRel = "";
$arquivo = "";
$writer = "";
$ARRAY_UNIDADE1 = "";
$ARRAY_UNIDADE = "";
$ARRAY_VENDEDOR1 = "";
$ARRAY_VENDEDOR = "";
$col_double = "";
$arrayQuery = [];
$array = [];
$row = "";
$newRow = "";
$objeto = "";
$NOM_ARRAY_NON_VENDEDOR = "";
$usuario = "";
$NOM_ARRAY_UNIDADE = "";
$arrayColumnsNames = [];

$opcao = @$_GET['opcao'];
$cod_empresa = fnDecode(@$_GET['id']);
$cod_univend = @$_POST['COD_UNIVEND'];
$casasDec = @$_REQUEST['CASAS_DEC'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$lojasSelecionadas = @$_POST['LOJAS'];
$rel = @$_GET['rel'];

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}
if (strlen($cod_univend) == 0) {
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

		$nomeRel = @$_GET['nomeRel'];
		$arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$writer = WriterFactory::create(Type::CSV);
		$writer->setFieldDelimiter(';');
		$writer->openToFile($arquivo);

		// Filtro por Grupo de Lojas
		include "filtroGrupoLojas.php";

		//============================
		/*$ARRAY_UNIDADE1=array(
		            'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa",
		            'cod_empresa'=>$cod_empresa,
		            'conntadm'=>$connAdm->connAdm(),
		            'IN'=>'N',
		            'nomecampo'=>'',
		            'conntemp'=>'',
		            'SQLIN'=> ""   
		            );
            * 
            */
		$ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);
		$ARRAY_VENDEDOR1 = array(
			'sql' => "select COD_USUARIO ,COD_USUARIO as COD_ATENDENTE,COD_USUARIO as COD_VENDEDOR ,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa",
			'cod_empresa' => $cod_empresa,
			'conntadm' => $connAdm->connAdm(),
			'IN' => 'N',
			'nomecampo' => '',
			'conntemp' => '',
			'SQLIN' => ""
		);
		$ARRAY_VENDEDOR = fnUniVENDEDOR($ARRAY_VENDEDOR1);

		$col_double = array();
		switch ($rel) {
			case 'vendas':
				$sql = "SELECT 
								V.COD_VENDA ID_VENDA,
								CLI.NUM_CGCECPF CPF,
								V.COD_CUPOM CUPOM,
								UNI.NOM_FANTASI LOJA,
								V.DAT_CADASTR DATA,
								SUM(i.QTD_PRODUTO) QTD_ITENS,
								V.VAL_TOTVENDA VALOR_TOTAL,
								V.VAL_DESCONTO DESCONTO,
								st.DES_STATUSCRED `CANCELADO?`
								FROM vendas  V
								INNER JOIN itemvenda i ON V.COD_VENDA= i.COD_VENDA
								INNER JOIN clientes CLI ON CLI.COD_CLIENTE=V.COD_CLIENTE
								LEFT JOIN unidadevenda UNI ON UNI.COD_UNIVEND=V.COD_UNIVEND
								LEFT JOIN  statuscredito st ON V.COD_STATUSCRED =st.COD_STATUSCRED
								WHERE V.cod_empresa=$cod_empresa AND V.COD_CLIENTE > '1' AND DATE(i.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim'
								AND V.COD_CLIENTE IN (SELECT COD_CLIENTE FROM clientes WHERE cod_empresa=$cod_empresa AND DATE(dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim')
								AND UNI.COD_UNIVEND IN($lojasSelecionadas)
								GROUP BY i.COD_VENDA";
				$col_double = array(5, 6, 7);
				break;
			case 'itens':
				$sql = "SELECT 
								V.COD_VENDA ID_VENDA,
								prod.EAN CODIGO_BARRAS,
								UNI.NOM_FANTASI LOJA,
								i.QTD_PRODUTO QUANTIDADE,
								i.VAL_UNITARIO VLR_UNITARIO,
								i.VAL_TOTITEM VLR_TOTAL,
								i.VAL_DESCONTO DESCONTO
								FROM vendas  V
								INNER JOIN itemvenda i ON V.COD_VENDA= i.COD_VENDA
								LEFT JOIN produtocliente prod ON prod.COD_PRODUTO=i.COD_PRODUTO
								INNER JOIN clientes CLI ON CLI.COD_CLIENTE=V.COD_CLIENTE
								LEFT JOIN unidadevenda UNI ON UNI.COD_UNIVEND=V.COD_UNIVEND
								LEFT JOIN  statuscredito st ON V.COD_STATUSCRED =st.COD_STATUSCRED
								WHERE V.cod_empresa=$cod_empresa AND V.COD_CLIENTE > '1' AND DATE(i.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim'
								AND V.COD_CLIENTE IN (SELECT COD_CLIENTE FROM clientes WHERE cod_empresa=$cod_empresa AND DATE(dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim')
								AND UNI.COD_UNIVEND IN($lojasSelecionadas)";
				$col_double = array(2, 3, 4, 5);
				break;
			case 'pgto':
				$sql = "SELECT 
								V.COD_VENDA ID_VENDA,
								fp.DES_FORMAPA FORMA_PAGTO,
								fp.COD_FORMAPA SEQUENCIA,
								TRUNCATE (V.VAL_TOTVENDA,2) VALOR_PAGO
								FROM vendas  V
								left join formapagamento fp ON fp.COD_FORMAPA=V.COD_FORMAPA
								WHERE V.cod_empresa=$cod_empresa AND V.COD_CLIENTE > '1' AND DATE(V.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim'
								AND V.COD_CLIENTE IN (SELECT COD_CLIENTE FROM clientes WHERE cod_empresa=$cod_empresa AND DATE(dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim')
								AND V.COD_UNIVEND IN($lojasSelecionadas)";
				$col_double = array(3);
				break;
		}

		fnEscreve($rel);
		fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();
		while ($row = mysqli_fetch_assoc($arrayQuery)) {
			$newRow = array();

			$cont = 0;
			foreach ($row as $objeto) {
				// Colunas que são double converte com fnValor
				if (in_array($cont, $col_double)) {

					array_push($newRow, fnValor($objeto, 2));
					/*
					}else if($cont == 14){

						array_push($newRow, fnValor($objeto, $casasDec));

					}else if($cont == 1 || $cont == 2){

						$NOM_ARRAY_NON_VENDEDOR=(array_search($objeto, array_column($ARRAY_VENDEDOR, 'COD_ATENDENTE')));
                        
                        if($objeto != "" && $objeto != 0){
                        	$usuario = $ARRAY_VENDEDOR[$NOM_ARRAY_NON_VENDEDOR]['NOM_USUARIO'];
                        }else{
                        	$usuario = "";
                        }

					    array_push($newRow, $usuario); 

					}else if($cont == 0){

						$NOM_ARRAY_UNIDADE=(array_search($objeto, array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
						array_push($newRow, $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']);
*/
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
}
