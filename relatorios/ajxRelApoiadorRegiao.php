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
	
	$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);

	//array dos filtros
	if (isset($_POST['COD_FILTRO'])){
		$arr_cod_filtro = $_POST['COD_FILTRO'];
		//print_r($Arr_COD_FILTRO);			 
	 
	   for ($i=0;$i<count($arr_cod_filtro);$i++) 
	   { 
		$cod_filtro = $cod_filtro.$arr_cod_filtro[$i].",";
	   } 
	   
	   $cod_filtro = rtrim($cod_filtro,',');
		
	}else{$cod_filtro = "0";}

	if (isset($_POST['COD_USUARIO'])){
		$arr_cod_usuario = $_POST['COD_USUARIO'];
		//print_r($Arr_COD_FILTRO);			 
	 
	   for ($i=0;$i<count($arr_cod_usuario);$i++) 
	   { 
		$cod_usuario = $cod_usuario.$arr_cod_usuario[$i].",";
	   } 
	   
	   $cod_usuario = rtrim($cod_usuario,',');
		
	}else{$cod_usuario = "0";}

	if(isset($_POST['COD_USUARIO2'])){
		$cod_usuario = fnLimpaCampoZero($_POST['COD_USUARIO2']);
	}

	if (isset($_POST['COD_MUNICIPIO_E'])){
		$arr_cod_municipio_e = $_POST['COD_MUNICIPIO_E'];
		//print_r($Arr_COD_FILTRO);			 
	 
	   for ($i=0;$i<count($arr_cod_municipio_e);$i++) 
	   { 
		$cod_municipio_e = $cod_municipio_e.$arr_cod_municipio_e[$i].",";
	   } 
	   
	   $cod_municipio_e = rtrim($cod_municipio_e,',');
		
	}else{$cod_municipio_e = "0";}
	
	if($cod_usuario != "" && $cod_usuario != 0){
		$andUsuario = "AND A.COD_USUARIO IN($cod_usuario)";
	}else{
		$andUsuario = "";
	}

	if($cod_municipio_e != "" && $cod_municipio_e != 0){
		$andMunicipio = "AND A.COD_MUNICIPIO_E IN($cod_municipio_e)";
	}else{
		$andMunicipio = "";
	}

	if($cod_filtro != "" && $cod_filtro != 0){
		$andFiltros = "AND A.COD_FILTRO IN($cod_filtro)";
	}else{
		$andFiltros = "";
	}
	
	switch ($opcao) {
		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 
			       
			$sql = "SELECT D.NOM_CLIENTE AS APOIADOR,
						   B.NOM_MUNICIPIO,
					(SELECT COUNT(*) FROM CLIENTES E WHERE  E.COD_MUNICIPIO=A.COD_MUNICIPIO) AS QTD_APOIADORES,
					(SELECT  SUM(QT_VOTOS_NOMINAIS) FROM ELEICOES F WHERE  F.CD_MUNICIPIO=A.COD_MUNICIPIO_E AND ANO_ELEICAO=2018 AND NR_CANDIDATO=31031) AS QTD_VOTOS,
					A.COD_FILTRO AS FILTRO
					FROM regiao_usuario A
					INNER JOIN MUNICIPIOS B ON B.COD_MUNICIPIO=A.COD_MUNICIPIO
					INNER JOIN WEBTOOLS.usuarios C ON C.COD_USUARIO=A.COD_USUARIO 
					LEFT JOIN CLIENTES D ON D.COD_CLIENTE=C.COD_INDICADOR
					WHERE A.COD_USUARIO != ''
					$andUsuario												
					$andMunicipio												
					$andFiltros																									
					ORDER BY D.NOM_CLIENTE";
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {

					if($cont == 4){

						$sqlFiltros = "SELECT DES_FILTRO FROM FILTROS_CLIENTE WHERE COD_EMPRESA = $cod_empresa AND COD_FILTRO IN($objeto) ORDER BY DES_FILTRO";
					  	$arrayFiltros = mysqli_query(connTemp($cod_empresa,''),$sqlFiltros);

					  	$filtros = "";

					  	while($qrFiltros = mysqli_fetch_assoc($arrayFiltros)){
					  		$filtros .= $qrFiltros['DES_FILTRO'].", "; 
					  	}

					  	$filtros = rtrim(trim($filtros),',');

						array_push($newRow, $filtros);

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

			$sql = "SELECT A.COD_USUARIO
					FROM regiao_usuario A
					INNER JOIN MUNICIPIOS B ON B.COD_MUNICIPIO=A.COD_MUNICIPIO
					INNER JOIN WEBTOOLS.usuarios C ON C.COD_USUARIO=A.COD_USUARIO
					WHERE A.COD_USUARIO != ''
					$andUsuario												
					$andMunicipio												
					$andFiltros";

			$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
			$totalitens_por_pagina = mysqli_num_rows($retorno);

			$numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);
			
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

			$sql = "SELECT D.NOM_CLIENTE,B.NOM_MUNICIPIO,
					(SELECT COUNT(*) FROM CLIENTES E WHERE  E.COD_MUNICIPIO=A.COD_MUNICIPIO) AS QTD_APOIADOR,
					(SELECT  SUM(QT_VOTOS_NOMINAIS) FROM ELEICOES F WHERE  F.CD_MUNICIPIO=A.COD_MUNICIPIO_E AND ANO_ELEICAO=2018 AND NR_CANDIDATO=31031) AS QTD_VOTOS,
					A.COD_FILTRO
					FROM regiao_usuario A
					INNER JOIN MUNICIPIOS B ON B.COD_MUNICIPIO=A.COD_MUNICIPIO
					INNER JOIN WEBTOOLS.usuarios C ON C.COD_USUARIO=A.COD_USUARIO 
					LEFT JOIN CLIENTES D ON D.COD_CLIENTE=C.COD_INDICADOR
					WHERE A.COD_USUARIO != ''
					$andUsuario												
					$andMunicipio												
					$andFiltros																									
					ORDER BY D.NOM_CLIENTE 
					LIMIT $inicio,$itens_por_pagina";
			
			//fnEscreve($sql);
	                                               
			//fnTestesql(connTemp($cod_empresa,''),$sql);											
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
								  
			$count=0;
			while ($qrApoia = mysqli_fetch_assoc($arrayQuery))
			{

			  	$sqlFiltros = "SELECT DES_FILTRO FROM FILTROS_CLIENTE WHERE COD_EMPRESA = $cod_empresa AND COD_FILTRO IN($qrApoia[COD_FILTRO]) ORDER BY DES_FILTRO";
			  	$arrayFiltros = mysqli_query(connTemp($cod_empresa,''),$sqlFiltros);

			  	$filtros = "";

			  	while($qrFiltros = mysqli_fetch_assoc($arrayFiltros)){
			  		$filtros .= $qrFiltros['DES_FILTRO'].", "; 
			  	}

			  	$filtros = rtrim(trim($filtros),',');				

				$count++;	
				echo"
					<tr>
					  <td>".$qrApoia['NOM_CLIENTE']."</td>
					  <td>".$qrApoia['NOM_MUNICIPIO']."</td>
					  <td>".$filtros."</td>
					  <td>".$qrApoia['QTD_APOIADOR']."</td>
					  <td>".$qrApoia['QTD_VOTOS']."</td>
					</tr>
					"; 
			}									

		break; 		
	}
?>