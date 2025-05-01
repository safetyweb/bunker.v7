<?php

include '../_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$array = "";
$key = "";
$default = "";
$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$opcao = "";
$pagina = "";
$andFiltro = "";
$andFiltroInconsist = "";
$andUnidade = "";
$lojasSelecionadas = "";
$hoje = "";
$Data = "";
$filtro = "";
$sqlUni = "";
$val_pesquisa = "";
$qrUni = "";
$ARRAY_UNIDADE1 = "";
$ARRAY_UNIDADE = "";
$orUnidade = "";
$nomeRel = "";
$arquivoCaminho = "";
$arrayQuery = "";
$arquivo = "";
$headers = "";
$row = "";
$limpandostring = "";
$textolimpo = "";
$newRow = "";
$objeto = "";
$sexo = "";
$arrayColumnsNames = "";
$writer = "";
$sql2 = "";
$retorno = "";
$inicio = "";
$qrListaPersonas = "";
$NOM_ARRAY_UNIDADE = "";
$loja = "";
$mostraSexo = "";
$arrayParamAutorizacao = "";
$colCliente = "";
$colCartao = "";

function getInput($array, $key, $default = '')
{
	return isset($array[$key]) ? $array[$key] : $default;
}

// echo fnDebug('true');

$dias30 = "";
$dat_ini = "";
$dat_fim = "";

$opcao = getInput($_GET, 'opcao');
$itens_por_pagina = getInput($_GET, 'itens_por_pagina');
$pagina = getInput($_GET, 'idPage');
$cod_empresa = fnDecode(getInput($_GET, 'id'));

$dat_ini = fnDataSql(getInput($_POST, 'DAT_INI'));
$dat_fim = fnDataSql(getInput($_POST, 'DAT_FIM'));
$andFiltro = getInput($_POST, 'FILTRO');
$andFiltroInconsist = getInput($_POST, 'FILTRO_INCONSIST');
$andUnidade = getInput($_POST, 'UNIDADE');
$lojasSelecionadas = getInput($_POST, 'LOJAS');

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" || $dat_ini == " ") {
	$dat_ini = " ";
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

if (trim($dat_ini) != "") {
	$Data = "B.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' AND";
} else {
	$Data = "";
}

if ($filtro != "") {
	if ($filtro == "UNIDADE") {
		$sqlUni = "SELECT COD_UNIVEND FROM UNIDADEVENDA 
			WHERE (NOM_FANTASI LIKE '%$val_pesquisa%' 
			OR NUM_CGCECPF = '$val_pesquisa' 
			OR NOM_UNIVEND LIKE '%$val_pesquisa%')
			AND COD_EMPRESA = $cod_empresa";
		// fnEscreve($sqlUni);
		$qrUni = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlUni));

		// fnEscreve($qrUni['COD_UNIVEND']);

		$andFiltro = " ";
		$andUnidade = " AND B.COD_UNIVEND IN ($qrUni[COD_UNIVEND]) ";
	} else {
		$andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
		$andUnidade = "";
	}
} else {
	$andFiltro = " ";
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

if ($andUnidade == "") {
	$orUnidade = "AND (B.COD_UNIVEND IN(0,$lojasSelecionadas) OR B.COD_UNIVEND = 0 OR B.COD_UNIVEND IS NULL)";
	if ($andFiltroInconsist == "AND ( B.COD_UNIVEND = '0'  or B.COD_UNIVEND is null)") {
		$orUnidade = "";
	}
} else {

	if ($andFiltroInconsist == "AND ( B.COD_UNIVEND = '0'  or B.COD_UNIVEND is null)") {
		$andUnidade = "";
		$orUnidade = "";
	}
}

// fnEscreve($andFiltroInconsist);
// fnEscreve($andUnidade);
// fnEscreve($orUnidade);

switch ($opcao) {
	case 'exportar':

		$nomeRel = getInput($_GET, 'nomeRel');
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$sql = "SELECT  B.COD_CLIENTE,
							B.NUM_CARTAO,
							B.NUM_CGCECPF,
							B.NOM_CLIENTE,
							B.DES_EMAILUS,
							B.DAT_CADASTR,
							B.DAT_NASCIME,
							B.COD_SEXOPES,
							B.COD_UNIVEND,
							uni.NOM_FANTASI,
							B.IDADE
					FROM CLIENTES B
					LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=B.COD_UNIVEND
					WHERE B.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
					AND B.COD_EMPRESA = $cod_empresa 
					AND CASE
					WHEN B.cod_sexopes = 3 THEN '1'
					WHEN B.cod_sexopes = 0 THEN '1'
					WHEN Date_format(Str_to_date(B.dat_nascime, '%d/%m/%Y'), '%Y-%m-%d')> Date_format(CURRENT_DATE(), '%Y-%m-%d') THEN '1'
					WHEN B.dat_nascime IS NULL THEN '1'
					WHEN B.dat_nascime = '' THEN '1'
					WHEN B.cod_univend = '0' THEN '1'
					WHEN B.ano <= '1910' THEN '1'
					WHEN  B.cod_univend IS NULL  THEN '1'
					WHEN B.IDADE BETWEEN '0' AND '17' THEN '1'
					ELSE '0'
					END IN (1,1,1,1,1,1,1,1,1)
					AND  B.cod_univend IN(0,$lojasSelecionadas)
					order by B.NOM_CLIENTE";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			switch ($row['COD_SEXOPES']) {
				case '1':
					$row['COD_SEXOPES'] = 'H';
					break;
				case '2':
					$row['COD_SEXOPES'] = 'M';
					break;
				default:
					$row['COD_SEXOPES'] = 'Indefinido';
					break;
			}
			//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
			//$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"');

			//echo "<pre>";
			//print_r($row);
			//echo "</pre>";
		}
		fclose($arquivo);
		/*
			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {
					  
					// Colunas que são double converte com fnValor
					if($cont == 7){

						switch ($objeto) {
							case '1':
								$sexo = 'H';
								break;
							case '2':
								$sexo = 'M';
								break;
							
							default:
								$sexo = 'Indefinido';
								break;
						}

						array_push($newRow, $sexo);

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

		$sql2 = "SELECT B.COD_CLIENTE,B.NUM_CARTAO,B.NUM_CGCECPF,B.NOM_CLIENTE,
								B.DES_EMAILUS,B.DAT_CADASTR,B.DAT_NASCIME ,B.COD_SEXOPES,B.COD_UNIVEND,B.IDADE
								FROM CLIENTES B
								WHERE
									B.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
							   AND B.COD_EMPRESA = $cod_empresa 
							   AND CASE
									   WHEN B.cod_sexopes = 3 THEN '1'
									   WHEN B.cod_sexopes = 0 THEN '1'
									   WHEN Date_format(Str_to_date(B.dat_nascime, '%d/%m/%Y'), '%Y-%m-%d')> Date_format(CURRENT_DATE(), '%Y-%m-%d') THEN '1'
									   WHEN B.dat_nascime IS NULL THEN '1'
									   WHEN B.dat_nascime = '' THEN '1'
									   WHEN B.cod_univend = '0' THEN '1'
									   WHEN B.ano <= '1910' THEN '1'
									   WHEN  B.cod_univend IS NULL  THEN '1'
									   WHEN B.IDADE BETWEEN '0' AND '17' THEN '1'
									   ELSE '0'
									   END IN (1,1,1,1,1,1,1,1,1)
									  AND  B.cod_univend IN(0,$lojasSelecionadas)
							   	order by B.NOM_CLIENTE
			";

		// fnEscreve($sql2);
		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql2);
		$total_itens_por_pagina = mysqli_num_rows($retorno);

		$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		$sql = "SELECT B.COD_CLIENTE,B.NUM_CARTAO,B.NUM_CGCECPF,B.NOM_CLIENTE,
								B.DES_EMAILUS,B.DAT_CADASTR,B.DAT_NASCIME ,B.COD_SEXOPES,B.COD_UNIVEND,uni.NOM_FANTASI,B.IDADE
								FROM CLIENTES B
								LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=B.COD_UNIVEND
                                                                WHERE
                                                                B.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
                                                                AND B.COD_EMPRESA = $cod_empresa 
                                                                AND CASE
                                                                            WHEN B.cod_sexopes = 3 THEN '1'
									   WHEN B.cod_sexopes = 0 THEN '1'
									   WHEN Date_format(Str_to_date(B.dat_nascime, '%d/%m/%Y'), '%Y-%m-%d')> Date_format(CURRENT_DATE(), '%Y-%m-%d') THEN '1'
									   WHEN B.dat_nascime IS NULL THEN '1'
									   WHEN B.dat_nascime = '' THEN '1'
									   WHEN B.cod_univend = '0' THEN '1'
									   WHEN B.ano <= '1910' THEN '1'
									   WHEN  B.cod_univend IS NULL  THEN '1'
									   WHEN B.IDADE BETWEEN '0' AND '17' THEN '1'
									   ELSE '0'
									   END IN (1,1,1,1,1,1,1,1,1)
									  AND  B.cod_univend IN(0,$lojasSelecionadas)
							   	order by B.NOM_CLIENTE limit $inicio,$itens_por_pagina";

		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$count = 0;
		while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {
			$count++;
			/*$NOM_ARRAY_UNIDADE=(array_search($qrListaPersonas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
                                 * 
                                 */
			$loja = "";
			if ($qrListaPersonas['COD_UNIVEND'] != 0 && $qrListaPersonas['COD_UNIVEND'] != "") {
				$loja = $qrListaPersonas['NOM_FANTASI'];
			}

			if ($qrListaPersonas['COD_SEXOPES'] == 1) {
				$mostraSexo = '<i class="fa fa-male" aria-hidden="true"></i>';
			}

			if ($qrListaPersonas['COD_SEXOPES'] == 2) {
				$mostraSexo = '<i class="fa fa-female" aria-hidden="true"></i>';
			}

			if ($qrListaPersonas['COD_SEXOPES'] == 3) {
				$mostraSexo = '<i class="fa fa-venus-mars" aria-hidden="true"></i>';
			}
			if (fnControlaAcesso("1024", $arrayParamAutorizacao) === true) {
				$colCliente = "<td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaPersonas['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrListaPersonas['NOM_CLIENTE']) . "</a></small></td>";
				$colCartao = "<td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaPersonas['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrListaPersonas['NUM_CARTAO']) . "</a></small></td>";
			} else {
				$colCliente = "<td><small>" . fnMascaraCampo($qrListaPersonas['NOM_CLIENTE']) . "</small></td>";
				$colCartao = "<td><small>" . fnMascaraCampo($qrListaPersonas['NUM_CARTAO']) . "</small></td>";
			}

			echo "
                    <tr>
                    	<td></td>
                        " . $colCliente . "
						" . $colCartao . "
						<td><small>" . fnMascaraCampo($qrListaPersonas['NUM_CGCECPF']) . "</small></td>
						<td><small>" . fnMascaraCampo(strtolower($qrListaPersonas['DES_EMAILUS'])) . "</small></td>
                        <td class='text-center'><small>" . $mostraSexo . "</small></td>
						<td><small>" . fnMascaraCampo($qrListaPersonas['DAT_NASCIME']) . "</small></td>
						<td>" . $qrListaPersonas['IDADE'] . "</small></td>
						<td><small>" . fnDataFull($qrListaPersonas['DAT_CADASTR']) . "</small></td>
                        <td>" . $loja . "</small></td>
                        <td><small><a class='btn btn-xs btn-default addBox' href='action.do?mod=" . fnEncode(1343) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaPersonas['COD_CLIENTE']) . "&pop=true' data-title='Reprocessamento de Inconsistências'><small><i class='fas fa-cog'></i></small></a></td>
					</tr>
                ";
		}

		if ($inicio == 0) {

?>
			<script type="text/javascript">
				$('.pagination-sm').twbsPagination('destroy');
				var numPaginas = <?php echo $numPaginas; ?>;
				carregarPaginacao(numPaginas);
			</script>
<?php

		}


		break;
}
?>