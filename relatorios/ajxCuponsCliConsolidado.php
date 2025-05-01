<?php 

	include '../_system/_functionsMain.php'; 
	require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);			
	
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	$lojasSelecionadas = $_POST['LOJAS'];
	// $num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CGCECPF']));
	// $nom_cliente = fnLimpaCampo($_POST['NOM_CLIENTE']);

	$ARRAY_UNIDADE1=array(
			   'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
			   'cod_empresa'=>$cod_empresa,
			   'conntadm'=>$connAdm->connAdm(),
			   'IN'=>'N',
			   'nomecampo'=>'',
			   'conntemp'=>'',
			   'SQLIN'=> ""   
			   );
	$ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);	
	
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}	
	if (strlen($cod_univend ) == 0){
		$cod_univend = "9999"; 
	}

	// if($nom_cliente != ""){
	// 	$andNome = "AND CL.NOM_CLIENTE LIKE '%$nom_cliente%'";
	// }else{
	// 	$andNome = "";
	// }

	// if($num_cgcecpf != ""){
	// 	$andCpf = "AND CL.NUM_CGCECPF = '$num_cgcecpf'";
	// }else{
	// 	$andCpf = "";
	// }
	
	switch ($opcao) {

		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 

			// Filtro por Grupo de Lojas
			include "filtroGrupoLojas.php";

			$sql = "SELECT A.COD_CLIENTE, 
						   A.COD_UNIVEND,
                                                   uni.nom_fantasi,
						   B.NUM_CARTAO, 
						   B.NOM_CLIENTE, 
						   COUNT(NUM_CUPOM) AS QTD_CUPOM,
						   (SELECT SUM(VAL_TOTVENDA) FROM CUPOM_CLIENTE_VENDA D 
							WHERE D.COD_CLIENTE = B.COD_CLIENTE 
							AND D.COD_UNIVEND = A.COD_UNIVEND 
							AND A.DAT_COMPRA BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59') AS VAL_TOTCOMPRA
					FROM GERACUPOM A, CLIENTES B 
					WHERE A.COD_CLIENTE = B.COD_CLIENTE
                                        LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
					AND A.COD_UNIVEND IN($lojasSelecionadas) 
					AND A.DAT_COMPRA BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
					AND A.COD_CLIENTE > 0 
					AND A.COD_EMPRESA = $cod_empresa
					GROUP BY B.NOM_CLIENTE, B.COD_UNIVEND
					ORDER BY B.NOM_CLIENTE, B.COD_UNIVEND
					";
			
			// fnEscreve($sql);		
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
								  
			$count=0;
			while ($qrCupom = mysqli_fetch_assoc($arrayQuery))
			{								
				$newRow = array();

			  	$NOM_ARRAY_UNIDADE=(array_search($qrCupom['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));

			  	array_push($newRow, $qrCupom['NOM_CLIENTE']);
			  	array_push($newRow, $qrCupom['NUM_CARTAO']);
			  	array_push($newRow, $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']);
			  	array_push($newRow, $qrCupom['QTD_CUPOM']);
			  	array_push($newRow, "R$ ".fnValor($qrCupom['VAL_TOTCOMPRA'],2));

			  	$array[] = $newRow;

				$count++;	

			}
			
			$arrayColumnsNames = array();
			$count = 0;
				
			array_push($arrayColumnsNames, "CLIENTE");						
			array_push($arrayColumnsNames, "CARTÃO");						
			array_push($arrayColumnsNames, "LOJA");						
			array_push($arrayColumnsNames, "QTD. CUPOM");						
			array_push($arrayColumnsNames, "VL. TOT. COMPRA");						

			$writer->addRow($arrayColumnsNames);
			$writer->addRows($array);

			$writer->close();

		break;
		    
		case 'paginar':

			// Filtro por Grupo de Lojas
			include "filtroGrupoLojas.php";
		
			$sql = "SELECT A.COD_CLIENTE
					FROM GERACUPOM A, CLIENTES B 
					WHERE A.COD_CLIENTE = B.COD_CLIENTE
					AND A.COD_UNIVEND IN($lojasSelecionadas) 
					AND A.DAT_COMPRA BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
					AND A.COD_CLIENTE > 0 
					AND A.COD_EMPRESA = $cod_empresa
					GROUP BY B.NOM_CLIENTE, B.COD_UNIVEND
					";

			//fnEscreve($sql);

			$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
			$totalitens_por_pagina = mysqli_num_rows($retorno);

			$numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);
			
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

			// Filtro por Grupo de Lojas
			include "filtroGrupoLojas.php";

			$sql = "SELECT A.COD_CLIENTE, 
						   A.COD_UNIVEND,
                                                   uni.nom_fantasi,
						   B.NUM_CARTAO, 
						   B.NOM_CLIENTE, 
						   COUNT(NUM_CUPOM) AS QTD_CUPOM,
						   (SELECT SUM(VAL_TOTVENDA) FROM CUPOM_CLIENTE_VENDA D 
							WHERE D.COD_CLIENTE = B.COD_CLIENTE 
							AND D.COD_UNIVEND = A.COD_UNIVEND 
							AND A.DAT_COMPRA BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59') AS VAL_TOTCOMPRA
					FROM GERACUPOM A, CLIENTES B 
					WHERE A.COD_CLIENTE = B.COD_CLIENTE
                                        LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
					AND A.COD_UNIVEND IN($lojasSelecionadas) 
					AND A.DAT_COMPRA BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
					AND A.COD_CLIENTE > 0 
					AND A.COD_EMPRESA = $cod_empresa
					GROUP BY B.NOM_CLIENTE, B.COD_UNIVEND
					ORDER BY B.NOM_CLIENTE, B.COD_UNIVEND
					LIMIT $inicio,$itens_por_pagina
					";
			
			// fnEscreve($sql);		
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
								  
			$count=0;
			while ($qrCupom = mysqli_fetch_assoc($arrayQuery))
			{								

			  	$NOM_ARRAY_UNIDADE=(array_search($qrCupom['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));

				$count++;	
				echo"
					<tr>
					  <td><small>".$qrCupom['NOM_CLIENTE']."</small></td>
					  <td><small>".$qrCupom['NUM_CARTAO']."</small></td>
					  <td><small>".$ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']."</small></td>
					  <td class='text-center'><small>".$qrCupom['QTD_CUPOM']."</small></td>
					  <td class='text-right'><small>".fnValor($qrCupom['VAL_TOTCOMPRA'],2)."</small></td>
					</tr>
					"; 
			}									

		break; 		
	}
?>