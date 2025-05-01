<?php 

	include '_system/_functionsMain.php';
	require_once 'js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;	
	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);

	$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);			
	$val_pesquisa = fnLimpaCampo($_POST['INPUT']);

	// fnEscreve($filtro);
	// fnEscreve($val_pesquisa);
	
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

	if($filtro != ""){
		$andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
	}else{
		$andFiltro = " ";
	}

	//faz pesquisa por revenda (geral)
	if ($cod_univend == "9999"){$temUnivend = "N";} else {$temUnivend = "S";}	
	
	switch ($opcao) {  

		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivo = 'media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 
			       
			$sql1="SELECT A.COD_PRODUTO,
							A.COD_EXTERNO,
							B.DES_CATEGOR as GRUPO,
							C.DES_SUBCATE as SUBGRUPO,
							A.DES_PRODUTO,
							A.NUM_PONTOS,
							A.VAL_PRODUTO,
					(SELECT SUM(EP.QTD_ESTOQUE) FROM ESTOQUE_PRODUTO EP WHERE EP.COD_EMPRESA = $cod_empresa AND EP.COD_PRODUTO = A.COD_PRODUTO) AS QTD_ESTOQUE
					from PRODUTOPROMOCAO A 
					LEFT JOIN CAT_PROMOCAO B ON A.COD_CATEGOR = B.COD_CATEGOR 
					LEFT JOIN SUB_PROMOCAO C ON A.COD_SUBCATE = C.COD_SUBCATE 
					where A.COD_EMPRESA = $cod_empresa 
					AND A.COD_EXCLUSA=0 
					$andFiltro order by A.DES_PRODUTO";
					
			//fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql1);

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {
					  
					// Colunas que são double converte com fnValor
					if($cont == 6){

						array_push($newRow, fnValor($objeto, 2));

					}else if($cont == 7){

						array_push($newRow, fnValor($objeto, 0));

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

			//variáveis da pesquisa
			$cod_externo = fnLimpacampo(@$_REQUEST['COD_EXTERNO']);
			$pesquisa = fnLimpacampo(@$_REQUEST['pesquisa']);
			$des_produto = fnLimpacampo(@$_REQUEST['DES_PRODUTO']);
			
			//pesquisa no form local
			$andExternoTkt = ' ';
			if (empty($_REQUEST['pesquisa'])){
				//fnEscreve("sem pesquisa");
				$andProduto = ' ';
				$andExterno = ' ';
			}else{
				//fnEscreve("com pesquisa");
				if ($des_produto != "" ){
					$andProduto = 'AND A.DES_PRODUTO like "%'.$des_produto.'%"'; }
					else { $andProduto = ' ';}
					
				if ($cod_externo  != "" ){
					$andExterno = 'AND A.COD_EXTERNO = "'.$cod_externo.'"'; }
					else { $andExterno = ' ';}
				
			}
			
			//se pesquisa dos produtos do ticket
			if (!empty($_GET['idP'])) {$andExterno = 'AND A.COD_EXTERNO = "'.$_GET['idP'].'"';}
			
			//fnEscreve("entrou");
			
			$sql="select A.COD_PRODUTO from PRODUTOPROMOCAO A 
				LEFT JOIN CAT_PROMOCAO B ON A.COD_CATEGOR = B.COD_CATEGOR 
				LEFT JOIN SUB_PROMOCAO C ON A.COD_SUBCATE = C.COD_SUBCATE 
				where A.COD_EMPRESA='".$cod_empresa."' 
				AND A.COD_EXCLUSA=0 
				$andFiltro";

			//fnEscreve($sql);
                                                                
			$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			$total_itens_por_pagina = mysqli_num_rows($retorno);

			// fnEscreve($total_itens_por_pagina);
			
			$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);	
			
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

			$sql1="select A.*,B.DES_CATEGOR as GRUPO,C.DES_SUBCATE as SUBGRUPO,
                (SELECT SUM(EP.QTD_ESTOQUE) FROM ESTOQUE_PRODUTO EP WHERE EP.COD_EMPRESA = $cod_empresa AND EP.COD_PRODUTO = A.COD_PRODUTO) AS QTD_ESTOQUE 
                from PRODUTOPROMOCAO A 
				LEFT JOIN CAT_PROMOCAO B ON A.COD_CATEGOR = B.COD_CATEGOR 
				LEFT JOIN SUB_PROMOCAO C ON A.COD_SUBCATE = C.COD_SUBCATE 
				where A.COD_EMPRESA='".$cod_empresa."' 
				AND A.COD_EXCLUSA=0 
				$andFiltro order by A.DES_PRODUTO limit $inicio,$itens_por_pagina";

			//fnEscreve($sql1);
			
            $arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql1) or die(mysqli_error());
			
			$count=0;
			while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery))
			{														  
				$count++;

				if ($qrListaProduto['log_markapontos'] == "1") {
					$markapontosAtivo = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
				} else {
					$markapontosAtivo = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
				}

				if ($qrListaProduto['DES_IMAGEM'] != "") {
					$mostraDES_IMAGEM = '<a href="https://img.bunker.mk/media/clientes/'.$cod_empresa.'/produtospromo/'.$qrListaProduto['DES_IMAGEM'].'" target="_blank">Visualizar</a>';	
				}else{ $mostraDES_IMAGEM = ''; }	
				
				echo "
					<tr>
					  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
					  <td>".$qrListaProduto['COD_PRODUTO']."</td>
					  <td>".$qrListaProduto['COD_EXTERNO']."</td>
					  <td>".$qrListaProduto['GRUPO']."</td>
					  <td>".$qrListaProduto['SUBGRUPO']."</td>
					  <td>".$qrListaProduto['DES_PRODUTO']."</td>
					  <td>".$qrListaProduto['NUM_PONTOS']."</td>
					  <td>".fnValor($qrListaProduto['QTD_ESTOQUE'],0)."</td>
					  <td class='text-center'>".$mostraDES_IMAGEM."</td>
					  <td class='text-center'>".$markapontosAtivo."</td>
					</tr>
					<input type='hidden' id='ret_COD_PRODUTO_".$count."' value='".$qrListaProduto['COD_PRODUTO']."'>  
					<input type='hidden' id='ret_COD_EXTERNO_".$count."' value='".$qrListaProduto['COD_EXTERNO']."'>
					<input type='hidden' id='ret_DES_PRODUTO_".$count."' value='".$qrListaProduto['DES_PRODUTO']."'>
					<input type='hidden' id='ret_COD_CATEGOR_".$count."' value='".$qrListaProduto['COD_CATEGOR']."'>
					<input type='hidden' id='ret_COD_SUBCATE_".$count."' value='".$qrListaProduto['COD_SUBCATE']."'>
					<input type='hidden' id='ret_COD_FORNECEDOR_".$count."' value='".$qrListaProduto['COD_FORNECEDOR']."'>
					<input type='hidden' id='ret_COD_EAN_".$count."' value='".$qrListaProduto['EAN']."'>
					<input type='hidden' id='ret_DES_DISPONIBILIDADE_".$count."' value='".$qrListaProduto['DES_DISPONIBILIDADE']."'>
					<input type='hidden' id='ret_DES_TIPOENTREGA_".$count."' value='".$qrListaProduto['DES_TIPOENTREGA']."'>
					<input type='hidden' id='ret_NUM_PONTOS_".$count."' value='".$qrListaProduto['NUM_PONTOS']."'>
					<input type='hidden' id='ret_VAL_PRODUTO_" . $count . "' value='" . fnValor($qrListaProduto['VAL_PRODUTO'],2) . "'>
					<input type='hidden' id='ret_DES_IMAGEM_".$count."' value='".$qrListaProduto['DES_IMAGEM']."'>
					<input type='hidden' id='ret_LOG_ATIVO_".$count."' value='".$qrListaProduto['LOG_ATIVO']."'>
					<input type='hidden' id='ret_LOG_MARKAPONTOS_".$count."' value='".$qrListaProduto['log_markapontos']."'>
					";
			}										

			break;

			case 'estoque':

			$cod_univend = fnLimpaCampo($_GET['idU']);

				if($filtro != ""){
					$andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
				}else{
					$andFiltro = " ";
				}
				
				//pesquisa no form local
				$andExternoTkt = ' ';
				if (empty($_REQUEST['pesquisa'])){
					//fnEscreve("sem pesquisa");
					$andProduto = ' ';
					$andExterno = ' ';
				}else{
					//fnEscreve("com pesquisa");
					if ($des_produto != "" ){
						$andProduto = 'AND A.DES_PRODUTO like "%'.$des_produto.'%"'; }
						else { $andProduto = ' ';}
						
					if ($cod_externo  != "" ){
						$andExterno = 'AND A.COD_EXTERNO = "'.$cod_externo.'"'; }
						else { $andExterno = ' ';}
					
				}
				
				//se pesquisa dos produtos do ticket
				if (!empty($_GET['idP'])) {$andExterno = 'AND A.COD_EXTERNO = "'.$_GET['idP'].'"';}
				
				//fnEscreve("entrou");
				
				$sql="SELECT COUNT(*) AS CONTADOR from PRODUTOPROMOCAO
					where COD_EMPRESA=$cod_empresa 
					$andProduto
					$andExterno 
					AND COD_EXCLUSA=0 
					-- AND log_markapontos = 0 
					$andFiltro order by DES_PRODUTO";
                                                                  
				$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
				$total_itens_por_pagina = mysqli_fetch_assoc($retorno);
				
				$numPaginas = ceil($total_itens_por_pagina['CONTADOR']/$itens_por_pagina);	
				
				//variavel para calcular o início da visualização com base na página atual
				$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

				$sql1="SELECT PP.*,
					(SELECT EP.QTD_ESTOQUE FROM ESTOQUE_PRODUTO EP WHERE EP.COD_UNIVEND = $cod_univend AND EP.COD_EMPRESA = $cod_empresa AND EP.COD_PRODUTO = PP.COD_PRODUTO) AS QTD_ESTOQUE
					FROM PRODUTOPROMOCAO PP
					where PP.COD_EMPRESA=$cod_empresa 
					$andProduto
					$andExterno 
					AND PP.COD_EXCLUSA=0 
					-- AND PP.log_markapontos = 0 
					$andFiltro order by PP.DES_PRODUTO limit $inicio,$itens_por_pagina";

				// fnEscreve($unidade_default);

				//fnEscreve($pagina);

				$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql1) or die(mysqli_error());
				
				$count=0;
				while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery))
				{														  
					$count++;

					if ($qrListaProduto['DES_IMAGEM'] != "") {
						$mostraDES_IMAGEM = '<a href="./media/clientes/'.$cod_empresa.'/produtospromo/'.$qrListaProduto['DES_IMAGEM'].'" target="_blank">Visualizar</a>';	
					}else{ $mostraDES_IMAGEM = ''; }

					?>	
					
						<tr>
						  <td><input type='radio' name='radio1' onclick='retornaForm(<?=$count?>)'></th>
						  <td><?=$qrListaProduto['COD_PRODUTO']?></td>
						  <td><?=$qrListaProduto['COD_EXTERNO']?></td>
						  <td><?=$qrListaProduto['DES_PRODUTO']?></td>
						  <td><?=$qrListaProduto['NUM_PONTOS']?></td>

						  <td class='text-center'>
						  	<a href="javascript:void(0);" class="editable-estoque" 
							  	data-type='text' 
							  	data-title='Editar Estoque' data-pk="<?php echo $qrListaProduto['COD_PRODUTO']; ?>" 
							  	data-name="QTD_ESTOQUE"
							  	data-count="<?=$count?>"><?=fnValor($qrListaProduto['QTD_ESTOQUE'],0)?>
						  		
						  	</a>
						  </td>

						</tr>

						<input type='hidden' id='ret_COD_PRODUTO_<?=$count?>' value='<?=$qrListaProduto['COD_PRODUTO']?>'>  
						<input type='hidden' id='ret_COD_EXTERNO_<?=$count?>' value='<?=$qrListaProduto['COD_EXTERNO']?>'>
						<input type='hidden' id='ret_DES_PRODUTO_<?=$count?>' value='<?=$qrListaProduto['DES_PRODUTO']?>'>
						<input type='hidden' id='ret_COD_EAN_<?=$count?>' value='<?=$qrListaProduto['EAN']?>'>
						<input type='hidden' id='ret_DES_DISPONIBILIDADE_<?=$count?>' value='<?=$qrListaProduto['DES_DISPONIBILIDADE']?>'>
						<input type='hidden' id='ret_DES_TIPOENTREGA_<?=$count?>' value='<?=$qrListaProduto['DES_TIPOENTREGA']?>'>
						<input type='hidden' id='ret_NUM_PONTOS_<?=$count?>' value='<?=$qrListaProduto['NUM_PONTOS']?>'>
						<input type='hidden' id='ret_VAL_PRODUTO_<?=$count?>' value='<?=fnValor($qrListaProduto['VAL_PRODUTO'],2)?>'>
						<input type='hidden' id='ret_DES_IMAGEM_<?=$count?>' value='<?=$qrListaProduto['DES_IMAGEM']?>'>
						<input type='hidden' id='ret_LOG_ATIVO_<?=$count?>' value='<?=$qrListaProduto['LOG_ATIVO']?>'>

					<?php 
				}
			?>
				<script>
					
					$(function(){
					    $('.editable-estoque').editable({ 
					    	emptytext: '0',
					        url: 'ajxCalculaEstoque.php',
			        		ajaxOptions:{type:'post'},
			        		params: function(params) {
						        params.count = $(this).data('count');
						        params.COD_EMPRESA = <?=$cod_empresa?>;
						        params.COD_UNIVEND = $('#COD_UNIVEND').val();
						        return params;
						    },
			        		success:function(data){
								//console.log(data);
							}
					    });
					});

				</script>

			<?php 
			break; 

			default:

				$log_ativo = fnLimpaCampo($_REQUEST['LOG_ATIVO']);

				$sql = "UPDATE ESTOQUE_PRODUTO SET LOG_ATIVO = '$log_ativo' WHERE COD_EMPRESA = $cod_empresa";
				mysqli_query(connTemp($cod_empresa,""),$sql);

			break;	
	}
?>