<?php

include '../_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$opcao = "";
$auth = "";
$casasDec = "";
$dat_ini = "";
$dat_fim = "";
$lojasSelecionadas = "";
$condicaoCartao = "";
$andNome = "";
$andCreditos = "";
$andDataRetro = "";
$condicaoVendaPDV = "";
$tip_ordenac = "";
$dias30 = "";
$hoje = "";
$temUnivend = "";
$orderBy = "";
$nomeRel = "";
$arquivoCaminho = "";
$writer = "";
$arquivo = "";
$arrayQuery = [];
$headers = "";
$limpandostring = "";
$textolimpo = "";
$array = [];
$arrayColumnsNames = [];
$retorno = "";
$totalitens_pagina = "";
$totalitens_por_pagina = "";
$inicio = "";
$qrListaVendas = "";
$vendaIni = "";
$nomCanal = "";
$log_funciona = "";
$mostraCracha = "";
$usuario = "";
$totalVenda = 0;
$totalCreditos = 0;
$vendaFim = "";

//require_once '../js/plugins/Spout/Autoloader/autoload.php';

//use Box\Spout\Writer\WriterFactory;
//use Box\Spout\Common\Type;	

$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_univend = @$_POST['COD_UNIVEND'];
$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
$auth = fnDecode(@$_REQUEST['AUTH']);
$casasDec = @$_REQUEST['CASAS_DEC'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$lojasSelecionadas = @$_POST['LOJAS'];
$condicaoCartao = @$_GET['condicaoCartao'];
$andNome = @$_GET['andNome'];
$andCreditos = @$_GET['andCreditos'];
$andDataRetro = @$_GET['andDataRetro'];
$condicaoVendaPDV = @$_GET['condicaoVendaPDV'];

$tip_ordenac = fnLimpaCampoZero(@$_POST['TIP_ORDENAC']);

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}

if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

// if (strlen($cod_univend) == 0) {
// 	$cod_univend = "9999";
// }

if ($cod_univend == 0) {
	$cod_univend = "9999";
}

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

//rotina de controle de acessos por módulo
// include "moduloControlaAcesso.php";

switch ($opcao) {
	case 'exportar':

		$nomeRel = @$_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		/*writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 
			*/
		// Filtro por Grupo de Lojas
		include "../filtroGrupoLojas.php";

		$sql = "SELECT
					UV.NOM_FANTASI LOJA,
					US1.NOM_USUARIO VENDEDOR,
					US2.NOM_USUARIO ATENDENTE,
					US3.NOM_USUARIO USUARIO_CADASTRO,
					-- A.COD_UNIVEND,
					-- A.COD_VENDEDOR,
                    -- A.COD_ATENDENTE,
					-- A.COD_USUCADA,
					A.COD_VENDA,	
					A.COD_VENDAPDV,
					A.COD_CUPOM AS CUPOM,	
					B.COD_CLIENTE, 
					B.NOM_CLIENTE, 
					B.NUM_CARTAO, 
					B.NUM_CGCECPF AS CPF, 
					B.DAT_NASCIME AS DAT_NASCIMENTO, 
					B.DES_EMAILUS AS EMAIL,
					B.NUM_CELULAR AS CELULAR,
					A.DAT_CADASTR as DAT_PROCESSAMENTO, 
					A.DAT_CADASTR_WS DAT_VENDA_XML, 
					ROUND(truncate(A.VAL_TOTPRODU,4), 2) VAL_TOTPRODU,
                    ROUND(truncate(A.VAL_TOTVENDA,4),2) VAL_TOTVENDA,
                    ROUND(A.VAL_TOTPRODU - A.VAL_TOTVENDA, 2) AS VAL_DESCONTO,
					ROUND(IFNULL((SELECT SUM(VAL_CREDITO) 
					FROM CREDITOSDEBITOS WHERE CREDITOSDEBITOS.COD_VENDA = A.COD_VENDA
					AND CREDITOSDEBITOS.COD_CLIENTE = A.COD_CLIENTE 
					AND TIP_CREDITO = 'C'
					AND COD_STATUSCRED IN (0,1,2, 3, 4, 5, 7, 8,9)), 0), 2) VAL_CREDITOS, 
       				(SELECT MAX(DAT_EXPIRA) 
        			FROM CREDITOSDEBITOS WHERE CREDITOSDEBITOS.COD_VENDA = A.COD_VENDA 
        			AND CREDITOSDEBITOS.COD_CLIENTE = A.COD_CLIENTE  
               		AND TIP_CREDITO = 'C'
               		AND COD_STATUSCRED IN (0,1,2, 3, 4, 5, 7, 8,9)) DAT_EXPIRA,
					
					(SELECT NOM_CANAL FROM empresa_canais where COD_EXTERNO=canal.COD_CANAL_EXT
															  AND COD_EMPRESA=A.COD_EMPRESA
																		  ) NOM_CANAL,
																		  
					B.DAT_CADASTR AS DAT_CADASTRO_CLI													  
					FROM VENDAS A
					INNER JOIN CLIENTES B ON A.COD_CLIENTE = B.COD_CLIENTE
					LEFT JOIN canal_vendas canal ON canal.COD_VENDA = A.COD_VENDA
					LEFT JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = A.COD_UNIVEND
					LEFT JOIN USUARIOS US1 ON US1.COD_USUARIO = A.COD_VENDEDOR
					LEFT JOIN USUARIOS US2 ON US2.COD_USUARIO = A.COD_ATENDENTE
					LEFT JOIN USUARIOS US3 ON US3.COD_USUARIO = A.COD_USUCADA
					WHERE 
					   A.DAT_CADASTR_WS BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					  $andNome	
					  $condicaoCartao 
                      $andDataRetro     
					  AND A.COD_EMPRESA = $cod_empresa
					  AND A.COD_UNIVEND IN($lojasSelecionadas)
					  $condicaoVendaPDV
					  $andCreditos
					  AND A.COD_STATUSCRED in (0,1,2,3,4,5,7,8,9)
					  $orderBy";


		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			$row['VAL_TOTPRODU'] = fnValor($row['VAL_TOTPRODU'], 2);
			$row['VAL_TOTVENDA'] = fnValor($row['VAL_TOTVENDA'], 2);
			$row['VAL_DESCONTO'] = fnValor($row['VAL_DESCONTO'], 2);
			$row['VAL_CREDITOS'] = fnValor($row['VAL_CREDITOS'], 2);
			$row['CELULAR'] = fnmasktelefone($row['CELULAR']);
			//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
			//$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"');
		}
		fclose($arquivo);
		/*$array = array();
			{
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {
					  
					// Colunas que são double converte com fnValor
					if($cont == 12 || $cont == 13){

						array_push($newRow, fnValor($objeto, 2));

					}else if($cont == 14){

						array_push($newRow, fnValor($objeto, $casasDec));

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

		$sql = "SHOW TABLE STATUS LIKE 'VENDAS';";
		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$totalitens_pagina = mysqli_fetch_assoc($retorno);

		$sql = "SELECT 
					SUM((SELECT SUM(VAL_CREDITO) FROM CREDITOSDEBITOS WHERE COD_VENDA=A.COD_VENDA AND TIP_CREDITO='C') ) VAL_CREDITO,
					SUM(A.VAL_TOTVENDA) AS VAL_TOTVENDA										
					FROM VENDAS A
					LEFT JOIN CLIENTES B ON A.COD_CLIENTE=B.COD_CLIENTE
					LEFT JOIN webtools.USUARIOS C ON C.COD_USUARIO = A.COD_USUCADA
					LEFT JOIN UNIDADEVENDA D ON D.COD_UNIVEND = A.COD_UNIVEND
					WHERE 
					  $andNome
					  $condicaoCartao 
					   DATE_FORMAT(A.DAT_CADASTR_WS, '%Y-%m-%d') >= '$dat_ini' 
					  AND DATE_FORMAT(A.DAT_CADASTR_WS, '%Y-%m-%d') <= '$dat_fim' 
					  $andDataRetro     
					  AND A.COD_EMPRESA = $cod_empresa
					  $condicaoVendaPDV
					  $andCreditos														
					  AND A.COD_UNIVEND IN($lojasSelecionadas)
					  AND A.COD_STATUSCRED<>6
					";

		//fnEscreve($sql);

		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$totalitens_por_pagina = mysqli_fetch_assoc($retorno);
		$numPaginas = ceil($totalitens_pagina['Rows'] / $itens_por_pagina);

		//============================
		/*$ARRAY_UNIDADE1=array(
							'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
							'cod_empresa'=>$cod_empresa,
							'conntadm'=>$connAdm->connAdm(),
							'IN'=>'N',
							'nomecampo'=>'',
							'conntemp'=>'',
							'SQLIN'=> ""   
							);
			$ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);
			$ARRAY_VENDEDOR1=array(
							'sql'=>"select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa and cod_exclusa=0",
							'cod_empresa'=>$cod_empresa,
							'conntadm'=>$connAdm->connAdm(),
							'IN'=>'N',
							'nomecampo'=>'',
							'conntemp'=>'',
							'SQLIN'=> ""   
							);
			$ARRAY_VENDEDOR=fnUniVENDEDOR($ARRAY_VENDEDOR1);
			//  echo '<pre>';
			// print_r($ARRAY_VENDEDOR);
			//  echo '</pre>'; 
			*/
		//====================================    
		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		// Filtro por Grupo de Lojas
		include "../filtroGrupoLojas.php";

		$sql = "SELECT
							A.COD_UNIVEND,
							USU.NOM_USUARIO,
							uni.NOM_FANTASI,
							A.COD_VENDEDOR,
							A.COD_ATENDENTE,    
							A.COD_USUCADA,
							A.COD_VENDA,	
							A.COD_VENDAPDV,	
							B.COD_CLIENTE, 
							B.NOM_CLIENTE, 
							B.NUM_CARTAO, 
							B.LOG_FUNCIONA, 
							A.DAT_CADASTR, 
							A.DAT_CADASTR_WS, 
							ROUND(truncate(A.VAL_TOTPRODU,4), 2) VAL_TOTPRODU,
							ROUND(truncate(A.VAL_TOTVENDA,4),2) VAL_TOTVENDA,
							ROUND(IFNULL((SELECT SUM(VAL_CREDITO) 
							FROM CREDITOSDEBITOS WHERE CREDITOSDEBITOS.COD_VENDA = A.COD_VENDA
							AND CREDITOSDEBITOS.COD_CLIENTE = A.COD_CLIENTE 
							AND TIP_CREDITO = 'C'
							AND COD_STATUSCRED IN (0,1,2, 3, 4, 5, 7, 8,9)), 0), 2) VAL_CREDITOS, 
							(SELECT MAX(DAT_EXPIRA) 
							FROM CREDITOSDEBITOS WHERE CREDITOSDEBITOS.COD_VENDA = A.COD_VENDA 
							AND CREDITOSDEBITOS.COD_CLIENTE = A.COD_CLIENTE  
							AND TIP_CREDITO = 'C'
							AND COD_STATUSCRED IN (0,1,2, 3, 4, 5, 7, 8,9)) DAT_EXPIRA,
							(SELECT NOM_CANAL FROM empresa_canais where COD_EXTERNO=canal.COD_CANAL_EXT
							AND COD_EMPRESA=A.COD_EMPRESA
							) NOM_CANAL													
							FROM VENDAS A
							INNER JOIN CLIENTES B ON A.COD_CLIENTE=B.COD_CLIENTE
							LEFT JOIN USUARIOS USU ON USU.COD_USUARIO=A.COD_VENDEDOR
							LEFT JOIN canal_vendas canal ON canal.COD_VENDA=A.COD_VENDA
							LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
							WHERE 
							A.DAT_CADASTR_WS BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
							$andNome	
							$condicaoCartao 
							$andDataRetro     
							AND A.COD_EMPRESA = $cod_empresa
							AND A.COD_UNIVEND IN($lojasSelecionadas)
							$condicaoVendaPDV
							$andCreditos
							AND A.COD_STATUSCRED in (0,1,2,3,4,5,7,8,9)
							$orderBy limit $inicio,$itens_por_pagina ";


		//fnEscreve($sql);	
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$countLinha = 1;
		while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

			if ($countLinha == 1) {
				$vendaIni = $qrListaVendas['DAT_CADASTR_WS'];
			}

			if ($qrListaVendas['NOM_CANAL'] == "") {
				$nomCanal = "Canal não cadastrado";;
			} else {
				$nomCanal = $qrListaVendas['NOM_CANAL'];
			}

			$log_funciona = $qrListaVendas['LOG_FUNCIONA'];
			if ($log_funciona == "S") {
				$mostraCracha = '<i class="fas fa-address-card" aria-hidden="true"></i>';
			} else {
				$mostraCracha = "";
			}

?>
			<tr style="background-color: #fff;" class="abreDetail_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
				<td><?php echo $qrListaVendas['COD_VENDA']; ?></td>
				<?php if ($auth == 'S') { ?>
					<td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE'] . "&nbsp;" . $mostraCracha; ?></a></td>
				<?php } else { ?>
					<td><?php echo $qrListaVendas['NOM_CLIENTE'] . "&nbsp;" . $mostraCracha; ?></td>
				<?php } ?>
				<td class="text-right"><small><?php echo fnMascaraCampo($qrListaVendas['NUM_CARTAO']); ?></small></td>
				<td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>

				<td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR_WS']); ?></small></td>
				<td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['VAL_TOTPRODU'], 2); ?></small></td>
				<td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['VAL_TOTVENDA'], 2); ?></small></td>
				<td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['VAL_TOTPRODU'] - $qrListaVendas['VAL_TOTVENDA'], 2); ?></small></td>
				<td class="text-right"><small><?php echo fnValor($qrListaVendas['VAL_CREDITOS'], $casasDec); ?></small></td>
				<td><small><?php echo fnDataFull($qrListaVendas['DAT_EXPIRA']); ?></small></td>
				<td><small><?php echo $qrListaVendas['NOM_USUARIO']; ?></small></td>
				<td><small><?= $usuario ?></small></td>
				<td><small><?php echo $qrListaVendas['COD_VENDAPDV']; ?></small></td>
			</tr>
<?php

			@$totalVenda = @$totalVenda + $qrListaVendas['VAL_TOTVENDA'];
			@$totalCreditos = @$totalCreditos + $qrListaVendas['VAL_CREDITOS'];

			$vendaFim = $qrListaVendas['DAT_CADASTR_WS'];
			$countLinha++;
		}

		break;
}
?>