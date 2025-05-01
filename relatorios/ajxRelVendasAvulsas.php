<?php

include '../_system/_functionsMain.php';

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$cod_univend = "";
$cod_grupotr = "";
$cod_tiporeg = "";
$casasDec = "";
$cod_cupom = "";
$lojasSelecionadas = "";
$condicaoCartao = "";
$andNome = "";
$andCreditos = "";
$andDataRetro = "";
$condicaoVendaPDV = "";
$autoriza = "";
$tip_ordenac = "";



$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_univend = @$_POST['COD_UNIVEND'];
$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
$casasDec = @$_REQUEST['CASAS_DEC'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$cod_cupom = fnLimpaCampo(@$_POST['COD_CUPOM']);
$lojasSelecionadas = @$_POST['LOJAS'];
$condicaoCartao = @$_GET['condicaoCartao'];
$andNome = @$_GET['andNome'];
$andCreditos = @$_GET['andCreditos'];
$andDataRetro = @$_GET['andDataRetro'];
$condicaoVendaPDV = @$_GET['condicaoVendaPDV'];

$autoriza = fnLimpaCampoZero(@$_POST['AUTORIZA']);

$tip_ordenac = fnLimpaCampoZero(@$_POST['TIP_ORDENAC']);

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}
if (is_string($cod_univend) && strlen($cod_univend) == 0) {
	$cod_univend = "9999";
}

//faz pesquisa por revenda (geral)
if ($cod_univend == "9999") {
	$temUnivend = "N";
} else {
	$temUnivend = "S";
}

if ($tip_ordenac == 1) {
	$orderBy = "ORDER BY VAL_TOTVENDA DESC";
} else if ($tip_ordenac == 2) {
	$orderBy = "ORDER BY VAL_CREDITOS DESC";
} else {
	$orderBy = "ORDER BY A.DAT_CADASTR_WS DESC";
}

if ($cod_cupom == "") {
	$andCodCupom = " ";
} else {
	$andCodCupom = "AND A.COD_CUPOM = '" . $cod_cupom . "' ";
}

// Filtro por Grupo de Lojas
include "../filtroGrupoLojas.php";

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
	$ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);*/
/*$ARRAY_VENDEDOR1=array(
            'sql'=>"select COD_USUARIO ,COD_USUARIO as COD_ATENDENTE,COD_USUARIO as COD_VENDEDOR ,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa",
            'cod_empresa'=>$cod_empresa,
            'conntadm'=>$connAdm->connAdm(),
            'IN'=>'N',
            'nomecampo'=>'',
            'conntemp'=>'',
            'SQLIN'=> ""   
            );
	$ARRAY_VENDEDOR=fnUniVENDEDOR($ARRAY_VENDEDOR1);*/

switch ($opcao) {
	case 'exportar':

		$nomeRel = $_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$sql = "SELECT A.COD_UNIVEND AS LOJA,
			    		   UNI.NOM_FANTASI  UNIDADE,
					       A.COD_VENDEDOR AS VENDEDOR,
						   USU.NOM_USUARIO USUARIO, 
					       A.COD_ATENDENTE AS ATENDENTE, 
					       A.COD_USUCADA, 
					       A.COD_VENDA, 
					       A.COD_VENDAPDV, 
					       B.COD_CLIENTE, 
					       B.NOM_CLIENTE AS CLIENTE,
					       a.COD_CUPOM AS CUPOM,
					       A.DAT_CADASTR, 
					       A.DAT_CADASTR_WS, 
					       A.VAL_TOTPRODU, 
					       A.VAL_TOTVENDA 
					FROM  VENDAS_AVULSA A 
					       INNER JOIN CLIENTES B ON A.COD_CLIENTE = B.COD_CLIENTE
						   LEFT JOIN UNIDADEVENDA UNI ON UNI.COD_UNIVEND = A.COD_UNIVEND
						   left JOIN USUARIOS USU ON USU.COD_USUARIO=A.COD_VENDEDOR 
					WHERE A.DAT_CADASTR_WS BETWEEN '$dat_ini 00:00'AND '$dat_fim 23:59:59' 
					       AND A.COD_EMPRESA = $cod_empresa 
					       AND A.COD_UNIVEND IN($lojasSelecionadas) 
					       AND A.COD_STATUSCRED != 6 
					       $condicaoVendaPDV
					       $andCodCupom
					ORDER BY A.DAT_CADASTR_WS DESC";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			$row['VAL_TOTPRODU'] = fnValor($row['VAL_TOTPRODU'], 2);
			$row['VAL_TOTVENDA'] = fnValor($row['VAL_TOTVENDA'], 2);

			//$limpandostring= fnAcentos(Utf8_ansi(json_encode($row)));
			//$textolimpo=json_decode($limpandostring,true);
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
					if($cont == 0){


					}else if($cont == 1 || $cont == 2){

						$NOM_ARRAY_NON_VENDEDOR=(array_search($objeto, array_column($ARRAY_VENDEDOR, 'COD_ATENDENTE')));
                        
                        if($objeto != "" && $objeto != 0){
                        	$usuario = $ARRAY_VENDEDOR[$NOM_ARRAY_NON_VENDEDOR]['NOM_USUARIO'];
                        }else{
                        	$usuario = "";
                        }

					    array_push($newRow, $usuario); 

					}else if($cont == 11 || $cont == 12){

						array_push($newRow, fnValor($objeto, 2));

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
			///
			*/

		break;
	case 'paginar':

		$sql = "SELECT sum(1) as contador
						FROM VENDAS_AVULSA A  
						WHERE A.DAT_CADASTR_WS BETWEEN '$dat_ini 00:00'AND '$dat_fim 23:59:59' 
						       AND A.COD_EMPRESA = $cod_empresa 
						       AND A.COD_UNIVEND IN($lojasSelecionadas) 
						       AND A.COD_STATUSCRED != 6 
						       $condicaoVendaPDV
						       $andCodCupom 
						ORDER BY A.DAT_CADASTR_WS DESC ";

		//fnEscreve($sql);
		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$totalitens_por_pagina = mysqli_fetch_assoc($retorno);
		$numPaginas = ceil($totalitens_por_pagina['contador'] / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		// Filtro por Grupo de Lojas
		include "../filtroGrupoLojas.php";

		//============================
		/*$ARRAY_UNIDADE1 = array(
										'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
										'cod_empresa' => $cod_empresa,
										'conntadm' => $connAdm->connAdm(),
										'IN' => 'N',
										'nomecampo' => '',
										'conntemp' => '',
										'SQLIN' => ""
									);
									$ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);
									$ARRAY_VENDEDOR1 = array(
										'sql' => "select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa",
										'cod_empresa' => $cod_empresa,
										'conntadm' => $connAdm->connAdm(),
										'IN' => 'N',
										'nomecampo' => '',
										'conntemp' => '',
										'SQLIN' => ""
									);
									*/
		@$ARRAY_VENDEDOR = fnUniVENDEDOR($ARRAY_VENDEDOR1);
		//echo '<pre>';
		//  print_r($ARRAY_VENDEDOR);
		//echo '</pre>';

		//====================================    
		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		// Filtro por Grupo de Lojas
		include "../filtroGrupoLojas.php";

		$sql = "SELECT A.COD_UNIVEND,
												   UNI.NOM_FANTASI, 
												   A.COD_VENDEDOR,
												   USU.NOM_USUARIO, 
												   A.COD_ATENDENTE, 
												   A.COD_USUCADA, 
												   A.COD_VENDA,
												   A.COD_CUPOM,
												   A.COD_VENDAPDV, 
												   B.COD_CLIENTE, 
												   B.NOM_CLIENTE, 
												   A.DAT_CADASTR, 
												   A.DAT_CADASTR_WS, 
												   A.VAL_TOTPRODU, 
												   A.VAL_TOTVENDA 
											FROM  VENDAS_AVULSA A 
												   INNER JOIN CLIENTES B ON A.COD_CLIENTE = B.COD_CLIENTE
												   LEFT JOIN UNIDADEVENDA UNI ON UNI.COD_UNIVEND = A.COD_UNIVEND
												   left JOIN USUARIOS USU ON USU.COD_USUARIO=A.COD_VENDEDOR
											WHERE A.DAT_CADASTR_WS BETWEEN '$dat_ini 00:00'AND '$dat_fim 23:59:59' 
												   AND A.COD_EMPRESA = $cod_empresa 
												   AND A.COD_UNIVEND IN($lojasSelecionadas) 
												   AND A.COD_STATUSCRED != 6 
												   $condicaoVendaPDV
												   $andCodCupom
											ORDER BY A.DAT_CADASTR_WS DESC 
											LIMIT $inicio,$itens_por_pagina";


		//fnEscreve($sql);	
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$countLinha = 1;
		while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
			$NOM_ARRAY_NON_ATENDENTE = null;

			// Verifica se $ARRAY_VENDEDOR está definido e é um array
			if (isset($ARRAY_VENDEDOR) && is_array($ARRAY_VENDEDOR)) {
				$NOM_ARRAY_NON_ATENDENTE = array_search($qrListaVendas['COD_ATENDENTE'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO'));
			}

			if ($countLinha == 1) {
				$vendaIni = $qrListaVendas['DAT_CADASTR_WS'];
			}
?>
			<tr style="background-color: #fff;" class="abreDetail_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
				<td><?php echo $qrListaVendas['COD_VENDA']; ?></td>
				<?php if ($autoriza == 1) { ?>
					<td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE']; ?></a></td>
				<?php } else { ?>
					<td><?php echo $qrListaVendas['NOM_CLIENTE']; ?></td>
				<?php } ?>
				<td><small><?php echo $qrListaVendas['COD_CUPOM']; ?></small></td>
				<td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
				<td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR_WS']); ?></small></td>
				<td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['VAL_TOTPRODU'], 2); ?></small></td>
				<td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['VAL_TOTVENDA'], 2); ?></small></td>
				<td><small><?php echo $qrListaVendas['NOM_USUARIO']; ?></small></td>
				<td><small><?= $qrListaVendas['NOM_USUARIO'] ?></small></td>
				<td><small><?php echo $qrListaVendas['COD_VENDAPDV']; ?></small></td>
			</tr>
<?php
			@$totalVenda = @$totalVenda + @$qrListaVendas['VAL_TOTVENDA'];
			@$totalCreditos = isset($qrListaVendas['VAL_CREDITOS']) ? $totalCreditos + $qrListaVendas['VAL_CREDITOS'] : $totalCreditos;

			$vendaFim = $qrListaVendas['DAT_CADASTR_WS'];
			$countLinha++;
		}


		break;
}
?>