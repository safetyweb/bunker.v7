<?php 

	include '../_system/_functionsMain.php'; 
	require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$total = $_GET['total'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);			
	
	$cod_univend = $_POST['COD_UNIVEND'];
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	$lojasSelecionadas = $_POST['LOJAS'];
	$nom_cliente = $_POST['NOM_CLIENTE'];
	$num_cartao = $_POST['NUM_CARTAO'];	
	
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
	
        //variáveis da pesquisa
		$cod_externo = fnLimpacampo($_REQUEST['COD_EXTERNO']);
		$pesquisa = fnLimpacampo($_REQUEST['pesquisa']);
		$des_produto = fnLimpacampo($_REQUEST['DES_PRODUTO']);
		
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
        
	switch ($opcao) {  
                case 'exportar':
                    
                    $nomeRel = $_GET['nomeRel'];
                    $arquivo = '/media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

                    $writer = WriterFactory::create(Type::CSV);
                    $writer->setFieldDelimiter(';');
                    $writer->openToFile($arquivo);
                    
                    $sql="select A.*,B.DES_CATEGOR as GRUPO,C.DES_SUBCATE as SUBGRUPO from PRODUTOCLIENTE A 
			LEFT JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
			LEFT JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
			where A.COD_EMPRESA='".$cod_empresa."' 
			".$andProduto."
			".$andExterno." 
			AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO";
                    
                   $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $array = array();
        while ($row = mysqli_fetch_assoc($arrayQuery)) {
            $newRow = array();

            $cont = 0;
            foreach ($row as $objeto) {

                // Colunas que são double converte com fnValor

                    array_push($newRow, $objeto);
                }

                $cont++;
            
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
                case 'paginar':

		
		
		//fnEscreve("entrou");
		
		$sql="select COUNT(*) as contador from PRODUTOCLIENTE A 
				left JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
				left JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
				where A.COD_EMPRESA='".$cod_empresa."' 
				".$andProduto."
				".$andExterno." 
				AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO";
														  
		$resPagina = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
		$total = mysqli_fetch_assoc($resPagina);
		//seta a quantidade de itens por página, neste caso, 2 itens
		$registros =50;
		//calcula o número de páginas arredondando o resultado para cima
		$numPaginas = ceil($total['contador']/$registros);
		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($registros*$pagina)-$registros;

		$sql1="select A.*,B.DES_CATEGOR as GRUPO,C.DES_SUBCATE as SUBGRUPO from PRODUTOCLIENTE A 
			LEFT JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
			LEFT JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
			where A.COD_EMPRESA='".$cod_empresa."' 
			".$andProduto."
			".$andExterno." 
			AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO limit $inicio,$registros";

		//fnEscreve($sql1);
		$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql1) or die(mysqli_error());
		
		$count=0;
		while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery))
		{														  
			$count++;

			if ($qrListaProduto['DES_IMAGEM'] != "") {
				$mostraDES_IMAGEM = '<i class="fa fa-check-square-o" aria-hidden="true"></i>';	
			}else{ $mostraDES_IMAGEM = ''; }	
			
			echo "
				<tr>
				  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
				  <td>".$qrListaProduto['COD_PRODUTO']."</td>
				  <td>".$qrListaProduto['COD_EXTERNO']."</td>
				  <td>".$qrListaProduto['GRUPO']."</td>
				  <td>".$qrListaProduto['SUBGRUPO']."</td>
				  <td>".$qrListaProduto['DES_PRODUTO']."</td>
				  <td class='text-center'>".$mostraDES_IMAGEM."</td>
				</tr>
				<input type='hidden' id='ret_COD_PRODUTO_".$count."' value='".$qrListaProduto['COD_PRODUTO']."'>  
				<input type='hidden' id='ret_COD_EXTERNO_".$count."' value='".$qrListaProduto['COD_EXTERNO']."'>
				<input type='hidden' id='ret_DES_PRODUTO_".$count."' value='".$qrListaProduto['DES_PRODUTO']."'>
				<input type='hidden' id='ret_COD_CATEGOR_".$count."' value='".$qrListaProduto['COD_CATEGOR']."'>
				<input type='hidden' id='ret_COD_SUBCATE_".$count."' value='".$qrListaProduto['COD_SUBCATE']."'>
				<input type='hidden' id='ret_COD_FORNECEDOR_".$count."' value='".$qrListaProduto['COD_FORNECEDOR']."'>
				<input type='hidden' id='ret_COD_EAN_".$count."' value='".$qrListaProduto['EAN']."'>
				<input type='hidden' id='ret_ATRIBUTO1_".$count."' value='".$qrListaProduto['ATRIBUTO1']."'>
				<input type='hidden' id='ret_ATRIBUTO2_".$count."' value='".$qrListaProduto['ATRIBUTO2']."'>
				<input type='hidden' id='ret_ATRIBUTO3_".$count."' value='".$qrListaProduto['ATRIBUTO3']."'>
				<input type='hidden' id='ret_ATRIBUTO4_".$count."' value='".$qrListaProduto['ATRIBUTO4']."'>
				<input type='hidden' id='ret_ATRIBUTO5_".$count."' value='".$qrListaProduto['ATRIBUTO5']."'>
				<input type='hidden' id='ret_ATRIBUTO6_".$count."' value='".$qrListaProduto['ATRIBUTO6']."'>
				<input type='hidden' id='ret_ATRIBUTO7_".$count."' value='".$qrListaProduto['ATRIBUTO7']."'>
				<input type='hidden' id='ret_ATRIBUTO8_".$count."' value='".$qrListaProduto['ATRIBUTO8']."'>
				<input type='hidden' id='ret_ATRIBUTO9_".$count."' value='".$qrListaProduto['ATRIBUTO9']."'>
				<input type='hidden' id='ret_ATRIBUTO10_".$count."' value='".$qrListaProduto['ATRIBUTO10']."'>
				<input type='hidden' id='ret_ATRIBUTO11_".$count."' value='".$qrListaProduto['ATRIBUTO11']."'>
				<input type='hidden' id='ret_ATRIBUTO12_".$count."' value='".$qrListaProduto['ATRIBUTO12']."'>
				<input type='hidden' id='ret_ATRIBUTO13_".$count."' value='".$qrListaProduto['ATRIBUTO13']."'>
				<input type='hidden' id='ret_DES_IMAGEM_".$count."' value='".$qrListaProduto['DES_IMAGEM']."'>
				<input type='hidden' id='ret_LOG_PRODPBM_".$count."' value='".$qrListaProduto['LOG_PRODPBM']."'>
				<input type='hidden' id='ret_LOG_HABITEXC_".$count."' value='".$qrListaProduto['LOG_HABITEXC']."'>
				";										
                }
			break; 		
        }
  
?>