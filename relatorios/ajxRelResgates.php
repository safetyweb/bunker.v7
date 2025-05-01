<?php

include '../_system/_functionsMain.php';

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_univend = @$_POST['COD_UNIVEND'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$lojasSelecionadas = @$_POST['LOJAS'];
$nom_cliente = @$_POST['NOM_CLIENTE'];
$num_cartao = @$_POST['NUM_CARTAO'];
$autoriza = fnLimpaCampoZero(@$_POST['AUTORIZA']);

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

if ($num_cartao == "") {
	$andCartao = "";
} else {
	$andCartao = "AND B.NUM_CARTAO = $num_cartao";
}

if ($nom_cliente == "") {
	$andNome = "";
} else {
	$andNome = "AND B.NOM_CLIENTE LIKE '%$nom_cliente%' ";
}


/*$ARRAY_UNIDADE1=array(
            'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa",
            'cod_empresa'=>$cod_empresa,
            'conntadm'=>$connAdm->connAdm(),
            'IN'=>'N',
            'nomecampo'=>'',
            'conntemp'=>'',
            'SQLIN'=> ""   
            );
	$ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);
         * 
         */
/*$ARRAY_VENDEDOR1 = array(
	'sql' => "select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa",
	'cod_empresa' => $cod_empresa,
	'conntadm' => $connAdm->connAdm(),
	'IN' => 'N',
	'nomecampo' => '',
	'conntemp' => '',
	'SQLIN' => ""
);
$ARRAY_VENDEDOR = fnUniVENDEDOR($ARRAY_VENDEDOR1);
*/

switch ($opcao) {
	case 'exportar':

		$nomeRel = $_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$sql = "SELECT 	A.COD_CLIENTE, 
						B.NOM_CLIENTE, 
						B.NUM_CARTAO,  
						uni.NOM_FANTASI,
						A.COD_CUPOM, 
						A.DAT_CADASTR, 
						ROUND(A.VAL_TOTPRODU,2) VAL_TOTPRODU , 
						ROUND(A.VAL_RESGATE,2)  VAL_RESGATE, 
						ROUND(A.VAL_TOTVENDA,2) VAL_TOTVENDA, 
						A.COD_VENDEDOR, 
						A.COD_VENDAPDV, 
						A.COD_UNIVEND,
						US.NOM_USUARIO,
						US.COD_EXTERNO 
				FROM VENDAS A 
				INNER JOIN CLIENTES B ON B.COD_CLIENTE = A.COD_CLIENTE
				LEFT JOIN USUARIOS US ON US.COD_USUARIO = A.COD_VENDEDOR										 
				LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND	  
				WHERE A.VAL_RESGATE > 0 AND 
				A.DAT_CADASTR_WS BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' AND 
				A.COD_STATUSCRED in (0,1,2,3,4,5,7,8,9) AND 
				A.COD_CREDITOU != 4 AND 
				A.COD_EMPRESA = $cod_empresa AND 
				A.COD_UNIVEND IN ($lojasSelecionadas) 
				$andNome																	   
				$andCartao																	   
				-- GROUP BY A.COD_CLIENTE, B.NOM_CLIENTE, B.NUM_CARTAO 
				ORDER BY A.DAT_CADASTR_WS";
		// fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			$row['VAL_TOTPRODU'] = fnValor($row['VAL_TOTPRODU'], 2);
			$row['VAL_RESGATE'] = fnValor($row['VAL_RESGATE'], 2);
			$row['VAL_TOTVENDA'] = fnValor($row['VAL_TOTVENDA'], 2);
			//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
			//$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"');
		}
		fclose($arquivo);

		/*$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {
					  
					// Colunas que são double converte com fnValor
					if($cont == 6 || $cont == 7 || $cont == 8){
						array_push($newRow,"R$".fnValor($objeto, 2));
					}else if($cont == 11){
						$NOM_ARRAY_NON_VENDEDOR=(array_search($objeto, array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));
						array_push($newRow, $ARRAY_VENDEDOR[$NOM_ARRAY_NON_VENDEDOR]['NOM_USUARIO']);
					}else{
						array_push($newRow, $objeto);
					}
					  
					$cont++;
				  }
				$array[] = $newRow;
			}
			
			$arrayColumnsNames = array();
			while($row = mysqli_fetch_field($arrayQuery))
			{
				array_push($arrayColumnsNames, $row->name);
			}			

			$writer->addRow($arrayColumnsNames);
			$writer->addRows($array);

			$writer->close();
			*/
		break;
	case 'paginar':

		// Filtro por Grupo de Lojas
		include "../filtroGrupoLojas.php";

		$sql = "SELECT COUNT(*) CONTADOR, SUM(A.VAL_RESGATE) VAL_CREDITO, SUM(A.VAL_TOTPRODU) AS VAL_VINCULADO 
						FROM VENDAS A 
						INNER JOIN CLIENTES B ON B.COD_CLIENTE = A.COD_CLIENTE
						LEFT JOIN USUARIOS US ON US.COD_USUARIO = A.COD_VENDEDOR
						LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
						WHERE A.VAL_RESGATE > 0 AND 
						A.DAT_CADASTR_WS BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' AND 
						A.COD_STATUSCRED in (0,1,2,3,4,5,7,8,9) AND 
						A.COD_EMPRESA = $cod_empresa AND 
						A.COD_UNIVEND IN ($lojasSelecionadas) 
						AND A.COD_VENDA > 0
					   $andNome																	   
					   $andCartao																	   
					   ";

		// fnEscreve($sql);
		//fnTestesql(connTemp($cod_empresa,''), $sql);
		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

		$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

		// Filtro por Grupo de Lojas
		include "../filtroGrupoLojas.php";

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		$sql = "SELECT 	A.COD_CLIENTE, 
						B.NOM_CLIENTE, 
						B.NUM_CARTAO,  
						uni.NOM_FANTASI,
						A.COD_CUPOM, 
						A.DAT_CADASTR, 
						ROUND(A.VAL_TOTPRODU,2) VAL_TOTPRODU , 
						ROUND(A.VAL_RESGATE,2)  VAL_RESGATE, 
						ROUND(A.VAL_TOTVENDA,2) VAL_TOTVENDA, 
						A.COD_VENDEDOR, 
						A.COD_VENDAPDV, 
						A.COD_UNIVEND,
						US.NOM_USUARIO,
						US.COD_EXTERNO 
				FROM VENDAS A 
				INNER JOIN CLIENTES B ON B.COD_CLIENTE = A.COD_CLIENTE
				LEFT JOIN USUARIOS US ON US.COD_USUARIO = A.COD_VENDEDOR										 
				LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
				WHERE A.VAL_RESGATE > 0 AND 
				A.DAT_CADASTR_WS BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' AND 
				A.COD_STATUSCRED in (0,1,2,3,4,5,7,8,9) AND 
				A.COD_CREDITOU != 4 AND 
				A.COD_EMPRESA = $cod_empresa AND 
				A.COD_UNIVEND IN ($lojasSelecionadas) 
				$andNome																	   
				$andCartao																	   
				 -- GROUP BY A.COD_CLIENTE, B.NOM_CLIENTE, B.NUM_CARTAO 
				 ORDER BY A.DAT_CADASTR_WS  limit $inicio,$itens_por_pagina";

		// fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$count = 0;
		while ($qrListaResgates = mysqli_fetch_assoc($arrayQuery)) {
			/*$NOM_ARRAY_UNIDADE=(array_search($qrListaResgates['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
			 $NOM_ARRAY_NON_VENDEDOR=(array_search($qrListaResgates['COD_VENDEDOR'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));
                          * 
                          */
			if ($autoriza == 1) {
				$colCliente = "<td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaResgates['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrListaResgates['NOM_CLIENTE']) . "</a></small></td>";
			} else {
				$colCliente = "<td><small>" . fnMascaraCampo($qrListaResgates['NOM_CLIENTE']) . "</small></td>";
			}
			$count++;

			echo "
				<tr>
				  " . $colCliente . "
				  <td><small>" . fnMascaraCampo($qrListaResgates['NUM_CARTAO']) . "</small></td>
				  <td><small>" . @$qrListaResgates['nom_fantasi'] . "</small></td>
				  <td><small>" . fnDataFull($qrListaResgates['DAT_CADASTR']) . "</small></td>
				  <td class='text-center'><small><small>R$</small> " . fnValor($qrListaResgates['VAL_TOTVENDA'], 2) . "</small></td>
				  <td class='text-center'><small><small>R$</small> " . fnValor($qrListaResgates['VAL_RESGATE'], 2) . "</small></td>
				  <td class='text-center'><small><small>R$</small> " . fnValor($qrListaResgates['VAL_TOTPRODU'], 2) . "</small></td>
				  <td><small>" . $qrListaResgates['COD_VENDAPDV'] . "</small></td>
				  <td><small>" . $qrListaResgates['COD_CUPOM'] . "</small></td>
				  <td><small>" . $qrListaResgates['NOM_USUARIO'] . "</small></td>
				</tr>
				";
		}

		break;
}
