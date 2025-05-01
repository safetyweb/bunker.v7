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

$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$mostraXml = @$_GET['mostrarXML'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_univend = @$_POST['COD_UNIVEND'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$cod_vendapdv = @$_POST['COD_VENDAPDV'];
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


if (@$num_cgcecpf == "") {
	$andCpf = " ";
} else {
	$andCpf = "AND CL.NUM_CGCECPF = $num_cgcecpf ";
}

if ($cod_vendapdv == "") {
	$andVendaPDV = " ";
} else {
	$andVendaPDV = "AND CASE WHEN BV.COD_VENDAPDV IS NOT NULL THEN
						  BV.COD_VENDAPDV='$cod_vendapdv'
						  WHEN AV.COD_VENDAPDV IS NOT NULL THEN
						   AV.COD_VENDAPDV='$cod_vendapdv'
							WHEN  V.COD_VENDAPDV IS NOT NULL THEN
							V.COD_VENDAPDV='$cod_vendapdv' 

						END";
}

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
         * 
         */
/*$ARRAY_VENDEDOR1=array(
				   'sql'=>"select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa",
				   'cod_empresa'=>$cod_empresa,
				   'conntadm'=>$connAdm->connAdm(),
				   'IN'=>'N',
				   'nomecampo'=>'',
				   'conntemp'=>'',
				   'SQLIN'=> ""   
				   );
	$ARRAY_VENDEDOR=fnUniVENDEDOR($ARRAY_VENDEDOR1);
	*/

//faz pesquisa por revenda (geral)
if ($cod_univend == "9999") {
	$temUnivend = "N";
} else {
	$temUnivend = "S";
}

switch ($opcao) {
	case 'exportar':

		$nomeRel = $_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$sql = "SELECT 
						CL.NOM_CLIENTE AS CLIENTE,
						CL.COD_CLIENTE AS COD_CLIENTE,
						CL.NUM_CGCECPF AS NUM_CGCECPF, 
						A.DAT_USUCADA AS DT_EXCLUSAO, 
						A.VAL_EXCLUIDO AS VL_EXCLUIDO,
						A.LOG_TOTAL AS TIPO,
						uni.NOM_FANTASI AS LOJA,
						US.NOM_USUARIO AS USUARIO,
						CASE WHEN BV.COD_VENDAPDV IS NOT NULL THEN BV.COD_VENDAPDV WHEN AV.COD_VENDAPDV IS NOT NULL THEN AV.COD_VENDAPDV WHEN V.COD_VENDAPDV IS NOT NULL THEN V.COD_VENDAPDV END AS COD_PDV 
						FROM VENDAS_EXC A
						INNER JOIN CLIENTES CL ON A.COD_CLIENTE=CL.COD_CLIENTE
						LEFT JOIN VENDAS V ON V.COD_VENDA=A.COD_VENDA
						LEFT JOIN VENDAS_AVULSA AV ON AV.COD_VENDA=A.COD_VENDA
						LEFT JOIN VENDAS_BKP BV ON BV.COD_VENDA=A.COD_VENDA
						LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
						LEFT JOIN USUARIOS US ON US.COD_USUARIO = V.COD_VENDEDOR
						WHERE A.DAT_USUCADA BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
						AND A.COD_EMPRESA = $cod_empresa 
						AND V.COD_UNIVEND IN($lojasSelecionadas)
						$andCpf
						$andVendaPDV
						";

		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {
			$row['VL_EXCLUIDO'] = "R$" . fnValor($row['VL_EXCLUIDO'], 2);
			$row['COD_PDV'] = "#" . $row['COD_PDV'];
			//$limpandostring= fnAcentos(Utf8_ansi(json_encode($row)));
			//$textolimpo=json_decode($limpandostring,true);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"');
		}
		fclose($arquivo);

		/*$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();

				  

				  /*$NOM_ARRAY_UNIDADE=(array_search($row['LOJA'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
                                   * 
                                  
				  $NOM_ARRAY_NON_VENDEDOR=(array_search($row['USUARIO'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));
                                   
                                   
				  
				  $count = 0;
				  foreach ($row as $objeto) {

				  	if($count == 7){
				  		array_push($newRow, $pdv);
				  	}else if($count == 1){
				  		array_push($newRow, fnDataFull($objeto));
				  	}else if($count == 2){
				  		array_push($newRow, "R$ ".fnValor($objeto,2));
				  	}else if($count == 3){
				  		if($objeto == 'S'){
							$tipo = "Total";
						}else{
							$tipo = "Parcial";
						}
				  		array_push($newRow, $tipo);
				  	}else if($count == 4){
				  		array_push($newRow, $ARRAY_VENDEDOR[$NOM_ARRAY_NON_VENDEDOR]['NOM_USUARIO']);
				  	}else{
						array_push($newRow, $objeto);
					}
					$count++;
				  }
				$array[] = $newRow;
			}

			// echo "<pre>";
			// print_r($array);
			// echo "</pre>";
			
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
                      * 
                      */
		$ARRAY_VENDEDOR1 = array(
			'sql' => "select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa",
			'cod_empresa' => $cod_empresa,
			'conntadm' => $connAdm->connAdm(),
			'IN' => 'N',
			'nomecampo' => '',
			'conntemp' => '',
			'SQLIN' => ""
		);
		$ARRAY_VENDEDOR = fnUniVENDEDOR($ARRAY_VENDEDOR1);

		//fnEscreve(date('Y-m-d'));	
		//fnEscreve($dat_fim);

		$sql = "SELECT 1 FROM VENDAS_EXC A
								INNER JOIN CLIENTES CL ON  A.COD_CLIENTE=CL.COD_CLIENTE
								LEFT JOIN VENDAS V ON V.COD_VENDA=A.COD_VENDA
								LEFT JOIN VENDAS_AVULSA AV ON AV.COD_VENDA=A.COD_VENDA
								LEFT JOIN VENDAS_BKP BV ON BV.COD_VENDA=A.COD_VENDA
								LEFT JOIN USUARIOS US ON US.COD_USUARIO = V.COD_VENDEDOR
								WHERE 
								 A.DAT_USUCADA BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
								 AND A.COD_EMPRESA = $cod_empresa 
								 AND V.COD_UNIVEND IN($lojasSelecionadas)
								 $andCpf
								 $andVendaPDV
								";

		//fnEscreve($sql);

		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$totalitens_por_pagina = mysqli_num_rows($retorno);
		$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		$sql2 = "SELECT 
                                     CASE WHEN BV.COD_VENDAPDV IS NOT NULL THEN
                                       BV.COD_VENDAPDV
	                                 WHEN AV.COD_VENDAPDV IS NOT NULL THEN
	                                    AV.COD_VENDAPDV
	                                 WHEN  V.COD_VENDAPDV IS NOT NULL THEN
	                                 V.COD_VENDAPDV 
	                                 END AS COD_VENDAPDV,
                                     CL.NOM_CLIENTE, A.DAT_USUCADA AS DAT_EXCLUSA, A.VAL_EXCLUIDO, A.LOG_TOTAL, A.COD_USUCADA, A.COD_UNIVEND,uni.NOM_FANTASI,
                                     US.NOM_USUARIO
                                     FROM VENDAS_EXC A
                                     INNER JOIN CLIENTES CL ON  A.COD_CLIENTE=CL.COD_CLIENTE
                                     LEFT JOIN VENDAS V ON V.COD_VENDA=A.COD_VENDA
                                     LEFT JOIN VENDAS_AVULSA AV ON AV.COD_VENDA=A.COD_VENDA
                                     LEFT JOIN VENDAS_BKP BV ON BV.COD_VENDA=A.COD_VENDA
                                     LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
                                     LEFT JOIN USUARIOS US ON US.COD_USUARIO = V.COD_VENDEDOR
                                     WHERE 
                                      A.DAT_USUCADA BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
                                      AND A.COD_EMPRESA = $cod_empresa 
                                      AND V.COD_UNIVEND IN($lojasSelecionadas) 
                                      $andCpf
                                      $andVendaPDV
                                      LIMIT $inicio, $itens_por_pagina
                                   ";

		//fnEscreve($sql2);	

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql2);

		$countLinha = 1;
		while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

			/*$NOM_ARRAY_UNIDADE=(array_search($qrListaVendas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
                              * 
                              */
			// $NOM_ARRAY_NON_VENDEDOR=(array_search($qrListaVendas['COD_USUCADA'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));


			if ($qrListaVendas['LOG_TOTAL'] == 'S') {
				$tipo = "Total";
			} else {
				$tipo = "Parcial";
			}

?>
			<tr>
				<td><small><?php echo fnMascaraCampo($qrListaVendas['NOM_CLIENTE']); ?></small></td>
				<td><small><?php echo fnDataFull($qrListaVendas['DAT_EXCLUSA']); ?></small></td>
				<td><small><small>R$ </small><?php echo fnValor($qrListaVendas['VAL_EXCLUIDO'], 2); ?></small></td>
				<td><small><?= $tipo ?></small></td>
				<td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
				<td><small><?php echo $qrListaVendas['NOM_USUARIO']; ?></small></td>
				<td><small><?php echo $qrListaVendas['COD_VENDAPDV']; ?></small></td>
				<!-- <?php if ($mostraXml == "OK") { ?>
                                             <td><a class="btn btn-xs btn-default addBox" data-url="action.php?mod=<?php echo fnEncode(1244); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idE=<?php echo fnEncode($qrListaVendas['COD_ORIGEM']); ?>&pop=true" data-title="XML Recebido"><small><i class="fa fa-code"></i></small></a></td>
                                       <?php } ?> -->
			</tr>
<?php

			$countLinha++;
		}

		break;
}
?>