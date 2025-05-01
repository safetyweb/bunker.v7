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
	
	switch ($opcao) {

		case 'exportar':

			$cod_campanha = fnLimpaCampoZero($_POST['COD_CAMPANHA']);			
			$des_produto = fnLimpaCampo($_POST['DES_PRODUTO']);	
			$cod_externo = fnLimpaCampoZero($_POST['COD_EXTERNO']);
		
			$nomeRel = $_GET['nomeRel'];
			$arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 		

			$sql = "SELECT A.*,B.DES_CAMPANHA as NOM_CAMPANHA,P.DES_PRODUTO,P.COD_EXTERNO, 
					IFNULL(P.COD_PRODUTO,0) as COD_PRODUTO from VANTAGEMEXTRAFAIXA A
					LEFT join CAMPANHA B on A.COD_CAMPANHA= B.COD_CAMPANHA
					LEFT join produtocliente P on A.COD_PRODUTO = P.COD_PRODUTO
					WHERE A.TIP_FAIXAS = 'PRD'
					AND A.COD_EMPRESA = $cod_empresa
					$andCampanha
					$andExterno
					$andProduto
					order by A.COD_CAMPANHA, P.DES_PRODUTO";
					
			//fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {
					  
					// Colunas que são double converte com fnValor
					if($cont == 4 || $cont == 5 || $cont == 6){
						array_push($newRow, fnValor($objeto, 2));
					// Coloca # para o campos CODVENDAPDV
					}else if($cont == 97){
						array_push($newRow, '#' .$objeto);
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

			$sql="SELECT count(*) as CONTADOR from VANTAGEMEXTRAFAIXA A
					LEFT join CAMPANHA B on A.COD_CAMPANHA= B.COD_CAMPANHA
					LEFT join produtocliente P on A.COD_PRODUTO = P.COD_PRODUTO
					WHERE A.TIP_FAIXAS = 'PRD'
					AND A.COD_EMPRESA = $cod_empresa
					";	

			//fnEscreve($sql);
			
			$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			$total_itens_por_pagina = mysqli_fetch_assoc($retorno);
			
			$numPaginas = ceil($total_itens_por_pagina['CONTADOR']/$itens_por_pagina);															
					
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;													
		
			$sql="SELECT A.*,B.DES_CAMPANHA as NOM_CAMPANHA,P.DES_PRODUTO,P.COD_EXTERNO, 
					IFNULL(P.COD_PRODUTO,0) as COD_PRODUTO from VANTAGEMEXTRAFAIXA A
					LEFT join CAMPANHA B on A.COD_CAMPANHA= B.COD_CAMPANHA
					LEFT join produtocliente P on A.COD_PRODUTO = P.COD_PRODUTO
					WHERE A.TIP_FAIXAS = 'PRD'
					AND A.COD_EMPRESA = $cod_empresa
					order by A.COD_CAMPANHA, P.DES_PRODUTO limit $inicio,$itens_por_pagina";
			
			//fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$count=0;
			$countLinha = 1;
			while ($qrBuscaCampanhaExtra = mysqli_fetch_assoc($arrayQuery))
			  {														  
				$count++;
				
				if ($qrBuscaCampanhaExtra['TIP_FAIXEXT'] == "ABS") { $tipoGanho = $nom_tpcampa; }
				else { $tipoGanho = "%"; }
		
				echo"
					<tr>
					  <td>".$qrBuscaCampanhaExtra['COD_VANTAGEMFAIXA']."</td>
					  <td>".$qrBuscaCampanhaExtra['COD_EXTERNO']."</td>
					  <td>".$qrBuscaCampanhaExtra['NOM_CAMPANHA']."</td>
					  <td><a class='prod' href='action.do?mod=".fnEncode(1046)."&id=".fnEncode($cod_empresa)."&idP=".$qrBuscaCampanhaExtra['COD_EXTERNO']."'>".$qrBuscaCampanhaExtra['DES_PRODUTO']."</a></td>
					  <td>".number_format ($qrBuscaCampanhaExtra['QTD_FAIXEXT'],2,",",".")." ".$tipoGanho."</td>															
					  <td>".$qrBuscaCampanhaExtra['QTD_FAIXLIM']."</td>
					</tr>
					<input type='hidden' id='ret_COD_GERAL_".$count."' value='".$qrBuscaCampanhaExtra['COD_VANTAGEMFAIXA']."'>
					<input type='hidden' id='ret_VAL_FAIXINI_".$count."' value='".number_format ($qrBuscaCampanhaExtra['VAL_FAIXINI'],2,",",".")."'>
					<input type='hidden' id='ret_VAL_FAIXFIM_".$count."' value='".number_format ($qrBuscaCampanhaExtra['VAL_FAIXFIM'],2,",",".")."'>
					<input type='hidden' id='ret_QTD_FAIXEXT_".$count."' value='".number_format ($qrBuscaCampanhaExtra['QTD_FAIXEXT'],2,",",".")."'>
					<input type='hidden' id='ret_TIP_FAIXEXT_".$count."' value='".$qrBuscaCampanhaExtra['TIP_FAIXEXT']."'>
					<input type='hidden' id='ret_QTD_FAIXLIM_".$count."' value='".$qrBuscaCampanhaExtra['QTD_FAIXLIM']."'>
					<input type='hidden' id='ret_COD_PRODUTO_".$count."' value='".$qrBuscaCampanhaExtra['COD_PRODUTO']."'>
					<input type='hidden' id='ret_DES_PRODUTO_".$count."' value='".$qrBuscaCampanhaExtra['DES_PRODUTO']."'>
					"; 
					
					$countLinha++;
				  }											

			break; 		
	}
?>