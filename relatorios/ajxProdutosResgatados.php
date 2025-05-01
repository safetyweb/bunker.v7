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
	$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_CGCECPF']));
	$cod_categor = $_REQUEST['COD_CATEGOR'];
	$cod_subcate = $_REQUEST['COD_SUBCATE'];
	if (empty($_REQUEST['LOG_AGRUPA'])) {$log_agrupa='N'; $checked = "";}else{$log_agrupa=$_REQUEST['LOG_AGRUPA']; $checked = "checked";}
	$des_produto = fnLimpaCampo($_REQUEST['DES_PRODUTO']);
	
	
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
	//faz pesquisa por revenda (geral)
	if ($cod_univend == "9999"){$temUnivend = "N";} else {$temUnivend = "S";}

	if($num_cgcecpf != ''){
		$andCpf = "AND B.NUM_CGCECPF = '$num_cgcecpf' ";
	}else{
		$andCpf = "";
	}

	if($cod_categor != '' && $cod_categor != 0){
		$andCat = "AND C.COD_CATEGOR = $cod_categor ";
	}else{
		$andCat = "";
	}	

	if($cod_subcate != '' && $cod_subcate != 0){
		$andSub = "AND C.COD_SUBCATE = $cod_subcate ";
	}else{
		$andSub = "";
	}

	if($des_produto != ''){
		$andProd = "AND B.DES_PRODUTO LIKE '%$des_produto%' ";
	}else{
		$andProd = "";
	}	

	if($log_agrupa == 'S'){
		$orderBy = "ORDER  BY A.COD_UNIVEND, A.DAT_REPROCE DESC";
	}else{
		$orderBy = "ORDER  BY A.DAT_REPROCE DESC";
	}	
	
	switch ($opcao) {
		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 

			// Filtro por Grupo de Lojas
			include "filtroGrupoLojas.php";			

			$sql = "SELECT 
				       A.COD_PRODUTO, 
				       B.DES_PRODUTO,
						 C.DES_CATEGOR, 
						 D.DES_SUBCATE,
				       sum(A.QTD_PRODUTO) AS QTD_PRODUTO, 
				       sum(A.VAL_UNITARIO) AS VAL_UNITARIO, 
				       sum(A.VAL_TOTPROD) AS VAL_TOTPROD
					FROM   CREDITOSDEBITOS A 
					      
					       INNER JOIN PRODUTOPROMOCAO B 
					               ON A.COD_PRODUTO = B.COD_PRODUTO 
					       LEFT JOIN CATEGORIA C 
					               ON B.COD_CATEGOR = C.COD_CATEGOR       
							 LEFT JOIN SUBCATEGORIA D 
					               ON B.COD_SUBCATE = D.COD_SUBCATE         			 
					WHERE  A.COD_EMPRESA = $cod_empresa 
					       AND A.TIP_CREDITO = 'D' 
					       AND A.COD_PRODUTO > 0
						   AND A.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9)							   
					       AND A.COD_UNIVEND IN ($lojasSelecionadas) 
					       AND A.DAT_REPROCE BETWEEN   '".fnDataSql($dat_ini)." 00:00:00' AND '".fnDataSql($dat_fim)." 23:59:59'
					       $andProd
					       $andCat
					       $andSub 
					GROUP BY  A.cod_produto
					ORDER  BY A.dat_reproce DESC
					
					   ";
					
			fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {
					  
					// Colunas que são double converte com fnValor
					if($cont == 4 || $cont == 5 || $cont == 6){
						array_push($newRow, fnValor($objeto, 0));
					// Coloca # para o campos CODVENDAPDV
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

		break;   

		case 'paginar':															
				
				// Filtro por Grupo de Lojas
				include "filtroGrupoLojas.php";

                //  $ARRAY_UNIDADE1=array(
                // 'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
                // 'cod_empresa'=>$cod_empresa,
                // 'conntadm'=>$connAdm->connAdm(),
                // 'IN'=>'N',
                // 'nomecampo'=>'',
                // 'conntemp'=>'',
                // 'SQLIN'=> ""   
                // );

                // $ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);

                $sql="SELECT COUNT(*) as CONTADOR 
						from CREDITOSDEBITOS A 
						   INNER JOIN PRODUTOPROMOCAO B 
								   ON A.COD_PRODUTO = B.COD_PRODUTO 
						   LEFT JOIN CAT_PROMOCAO C 
								   ON B.COD_CATEGOR = C.COD_CATEGOR       
							 LEFT JOIN SUB_PROMOCAO D 
								   ON B.COD_SUBCATE = D.COD_SUBCATE         			 
	                    WHERE  A.COD_EMPRESA = $cod_empresa 
						       AND A.TIP_CREDITO = 'D' 
						       AND A.COD_PRODUTO > 0
							   AND A.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9)															   
						       AND A.COD_UNIVEND IN ($lojasSelecionadas) 
						       AND A.DAT_REPROCE BETWEEN   '".fnDataSql($dat_ini)." 00:00:00' AND '".fnDataSql($dat_fim)." 23:59:59'
						       $andProd
						       $andCat
						       $andSub
						GROUP BY  A.cod_produto";

				//fnEscreve($sql);
                                                              
				$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
				$total_itens_por_pagina = mysqli_fetch_assoc($retorno);
				
				$numPaginas = ceil($total_itens_por_pagina['CONTADOR']/$itens_por_pagina);	
				
				//variavel para calcular o início da visualização com base na página atual
				$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
                                                                                
                $sql="SELECT 
				       A.COD_PRODUTO, 
				       B.DES_PRODUTO,
						 C.DES_CATEGOR, 
						 D.DES_SUBCATE,
				       sum(A.QTD_PRODUTO) AS QTD_PRODUTO, 
				       sum(A.VAL_UNITARIO) AS VAL_UNITARIO, 
				       sum(A.VAL_TOTPROD) AS VAL_TOTPROD
				FROM   CREDITOSDEBITOS A
				       INNER JOIN PRODUTOPROMOCAO B 
				               ON A.COD_PRODUTO = B.COD_PRODUTO 
				       LEFT JOIN CAT_PROMOCAO C 
				               ON B.COD_CATEGOR = C.COD_CATEGOR       
						 LEFT JOIN SUB_PROMOCAO D 
				               ON B.COD_SUBCATE = D.COD_SUBCATE         			 
				WHERE  A.COD_EMPRESA = $cod_empresa 
				       AND A.TIP_CREDITO = 'D' 
				       AND A.COD_PRODUTO > 0
					   AND A.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9)															   
				       AND A.COD_UNIVEND IN ($lojasSelecionadas) 
				       AND A.DAT_REPROCE BETWEEN   '".fnDataSql($dat_ini)." 00:00:00' AND '".fnDataSql($dat_fim)." 23:59:59'
				       $andProd
				       $andCat
				       $andSub 
				GROUP BY  A.cod_produto
				ORDER  BY A.DAT_REPROCE DESC
				LIMIT  $inicio, $itens_por_pagina";	   
				
				//fnEscreve($sql);
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
				
				$countLinha = 1;
				while ($qrListaProd = mysqli_fetch_assoc($arrayQuery))
				  {
                    $NOM_ARRAY_UNIDADE=(array_search($qrListaProd['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));

					?>	
						<tr>
						  <td><small><?=$qrListaProd['DES_PRODUTO']?></small></td>
						  <td><small></small><?=$qrListaProd['DES_CATEGOR']?></td>
						  <td><small></small><?=$qrListaProd['DES_SUBCATE']?></td>
						  <td class="text-center"><small><?=$qrListaProd['COD_PRODUTO']?></small></td>
						  <td class="text-center"><small><?=fnValor($qrListaProd['QTD_PRODUTO'],0)?></small></td>
						  <td class="text-center"><small><?=fnValor($qrListaProd['VAL_UNITARIO'],0)?></small></td>
						  <td class="text-center"><small> <?=fnValor($qrListaProd['VAL_TOTPROD'],0)?></small></td>
						</tr>
					<?php
				  $countLinha++;	
				  }


			break; 		
	}
?>