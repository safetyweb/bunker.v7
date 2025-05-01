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
	$nom_cliente = fnLimpaCampo($_REQUEST['NOM_CLIENTE']);
	$num_cgcecpf_ind = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_CGCECPF_IND']));
	$nom_cliente_ind = fnLimpaCampo($_REQUEST['NOM_CLIENTE_IND']);	
	
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

	if($nom_cliente != ""){
		$andNome = "AND C.NOM_CLIENTE LIKE '%$nom_cliente%'";
	}else{
		$andNome = "";
	}

	if($num_cgcecpf != ""){
		$andCpf = "AND C.NUM_CGCECPF = '$num_cgcecpf'";
	}else{
		$andCpf = "";
	}

	if($nom_cliente_ind != ""){
		$andNomeInd = "AND C.COD_INDICAD IN(SELECT COD_CLIENTE FROM CLIENTES WHERE NOM_CLIENTE LIKE '%$nom_cliente_ind%')";
	}else{
		$andNomeInd = "";
	}

	if($num_cgcecpf_ind != ""){
		$andCpfInd = "AND C.COD_INDICAD = (SELECT COD_CLIENTE FROM CLIENTES WHERE NUM_CGCECPF = '$num_cgcecpf_ind')";
	}else{
		$andCpfInd = "";
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
                        //============================
                               
			$sql = "SELECT C.NOM_CLIENTE AS INDICADO, 
						   C.NUM_CGCECPF AS CPF,
						   (SELECT NOM_CLIENTE FROM CLIENTES WHERE CLIENTES.COD_CLIENTE=A.COD_INDICAD) INDICADOR,
						   (SELECT NUM_CGCECPF FROM CLIENTES WHERE CLIENTES.COD_CLIENTE=A.COD_INDICAD) CPF_INDICADOR,
						   B.NOM_FANTASI AS LOJA,
						   (SELECT COUNT(1) FROM GERACUPOM WHERE GERACUPOM.COD_VENDA=A.COD_VENDA AND GERACUPOM.COD_INDICADOR > 0) CUPONS,
						   A.VAL_TOTVENDA,
						   A.QTD_ITEM AS ITENS,
						   (A.VAL_TOTVENDA/A.QTD_VENDA) TKT_MEDIO
					FROM CUPOM_CLIENTE_VENDA A,
					unidadevenda b,
					CLIENTES C
					WHERE
					a.cod_univend = b.cod_univend
					AND A.COD_CLIENTE = C.COD_CLIENTE
					AND a.cod_indicad > 0 
					AND A.COD_EMPRESA=$cod_empresa
					AND a.dat_CADASTR >= '$dat_ini 00:00:00'
					AND a.dat_CADASTR <= '$dat_fim 23:59:59'
					AND A.cod_univend IN($lojasSelecionadas)
					$andNome
					$andCpf
					$andNomeInd 
					$andCpfInd";
					
			//fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {

				  	if($cont == 6 || $cont == 8){
				  		array_push($newRow, "R$ ".fnValor($objeto,2));
				  	}else{					
						array_push($newRow, $objeto);
					}
					  
					$cont++;
				  }
					
				$array[] = $newRow;
			}
			
			$arrayColumnsNames = array();
			$count = 0;
			while($row = mysqli_fetch_field($arrayQuery))
			{
				
				array_push($arrayColumnsNames, $row->name);
				
				$count++;
			}			

			$writer->addRow($arrayColumnsNames);
			$writer->addRows($array);

			$writer->close();

		break;

	}