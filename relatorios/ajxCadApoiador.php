<?php 

	include '../_system/_functionsMain.php'; 
	require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));	

	include "labelLibrary.php";

	// fnEscreve($opcao);		
	
	$cod_indicad = fnLimpaCampo($_REQUEST['COD_INDICAD']);
	$dat_ini_busca = $_POST['DAT_INI'];
	$dat_fim_busca = $_POST['DAT_FIM'];
	$dat_anive_ini = $_POST['DAT_ANIVE_INI'];
	$dat_anive_fim = $_POST['DAT_ANIVE_FIM'];
	$nom_cliente = fnLimpaCampo($_REQUEST['NOM_CLIENTE']);
	$num_cgcecpf  = fnLimpaDoc(fnLimpacampo($_REQUEST['NUM_CGCECPF']));
	$des_igreja  = fnLimpacampo($_REQUEST['DES_IGREJA']);	
	$des_local  = fnLimpacampo($_REQUEST['DES_LOCAL']);
	$des_superb  = fnLimpacampo($_REQUEST['DES_SUPERB']);
	if (empty($_REQUEST['LOG_EXTERNO'])) {$log_externo='N';}else{$log_externo=$_REQUEST['LOG_EXTERNO'];}
	if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}
	if (empty($_REQUEST['LOG_TERMO'])) {$log_termo='N';}else{$log_termo=$_REQUEST['LOG_TERMO'];}

	$cod_estado = fnLimpacampoZero($_REQUEST['COD_ESTADO']);
	$cod_municipio = fnLimpacampoZero($_REQUEST['COD_MUNICIPIO']);

	if ($num_cgcecpf!=''){ 
		$andCpf = 'AND NUM_CGCECPF ='.$num_cgcecpf; 
	}else {
		$andCpf = ' '; 
	}

	if($cod_indicad != ""){
		$andIndicad = "AND CL.COD_INDICAD = $cod_indicad";
	}else{
		$andIndicad = "";
	}

	if($nom_cliente != ""){
		$andNome = "AND CL.NOM_CLIENTE LIKE '%$nom_cliente%'";
	}else{
		$andNome = "";
	}

	if ($des_igreja!=''){ 
		$andIgreja = "AND DA.DES_IGREJA = '$des_igreja'";
	}else{
		$andIgreja = ' '; 
	}

	if ($des_local!=''){ 
		$andLocal = "AND DA.DES_LOCAL = '$des_local'";
	}else{
		$andLocal = ' '; 
	} 

	if($dat_anive_ini != "" || $dat_anive_fim != ""){

		if($dat_anive_ini != ""){
			$dat_anive_ini = "RIGHT(STR_TO_DATE('".$dat_anive_ini."', '%d/%m/%Y'),5)";
		}else{
			$dat_anive_ini = "RIGHT(STR_TO_DATE('01/01', '%d/%m/%Y'),5)";
		}

		if($dat_anive_fim != ""){
			$dat_anive_fim = "RIGHT(STR_TO_DATE('".$dat_anive_fim."', '%d/%m/%Y'),5)";
		}else{
			$dat_anive_fim = "RIGHT(STR_TO_DATE('31/12', '%d/%m/%Y'),5)";
		}

		$andAnive = "AND RIGHT(STR_TO_DATE(CL.DAT_NASCIME, '%d/%m/%Y'),5) BETWEEN $dat_anive_ini AND $dat_anive_fim";
	}else{
		$andAnive = "";
	}

	if($dat_ini_busca != ""){
		$dat_ini_busca = fnDataSql($dat_ini_busca);
		$andDataIni = "AND CL.DAT_CADASTR > '$dat_ini_busca 00:00:00'";
	}else{
		$andDataIni = "";
	}

	if($dat_fim_busca != ""){
		$dat_fim_busca = fnDataSql($dat_fim_busca);
		$andDataFim = "AND CL.DAT_CADASTR < '$dat_fim_busca 23:59:59'";
	}else{
		$andDataFim = "";
	}

	if ($log_externo == 'S'){ 													
		$andExterno = 'and cod_externo != ""'; 
	}else {
		$andExterno = ' '; 
	}

	if ($log_ativo == 'N'){ 													
		$andAtivo = 'and CL.LOG_ESTATUS = "N"'; 
	}else {
		$andAtivo = 'and CL.LOG_ESTATUS = "S"'; 
	}

	if($cod_estado != 0){
		$andEstado = "AND CL.COD_ESTADO = $cod_estado";
	}else{
		$andEstado = "";
	}

	if($cod_municipio != 0){
		$andCidade = "AND CL.COD_MUNICIPIO = $cod_municipio";
	}else{
		$andCidade = "";
	}

	if($log_termo == "S" && $cod_empresa == 332){
		$andTermo = " AND LOG_TERMO = 'S' ";
	}else{
		$andTermo = "";
	}

	// fnEscreve($opcao);
	
	switch ($opcao) {

		case 'exportar':

			$cod_grupotr = fnLimpaCampo($_REQUEST['COD_GRUPOTR']);	
			$cod_tiporeg = fnLimpaCampo($_REQUEST['COD_TIPOREG']);
			$cod_envolv = fnLimpaCampo($_REQUEST['COD_ENVOLV']);
			$cod_entidad = fnLimpaCampo($_REQUEST['COD_ENTIDAD']);
			$cod_cargo = fnLimpaCampo($_REQUEST['COD_CARGO']);
			$cod_partido = fnLimpaCampo($_REQUEST['COD_PARTIDO']);

			if($cod_grupotr != ""){
				$andGrupo = "AND (SELECT distinct A.COD_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
							  WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
							  A.COD_TPFILTRO=B.COD_TPFILTRO AND
						      A.COD_FILTRO=B.COD_FILTRO AND
						      A.COD_TPFILTRO=29 AND
						      B.COD_CLIENTE=A.COD_CLIENTE)=$cod_grupotr";
			}else{
				$andGrupo = "";
			}

			if($cod_tiporeg != ""){
				$andReg = "AND (SELECT distinct A.COD_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
							 WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
							 A.COD_TPFILTRO=B.COD_TPFILTRO AND
						     A.COD_FILTRO=B.COD_FILTRO AND
						     A.COD_TPFILTRO=28 AND
						     B.COD_CLIENTE=A.COD_CLIENTE)=$cod_tiporeg";
			}else{
				$andReg = "";
			}

			if($cod_cargo != ""){
				$andCargo = "AND (SELECT distinct A.COD_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
							 WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
							 A.COD_TPFILTRO=B.COD_TPFILTRO AND
						     A.COD_FILTRO=B.COD_FILTRO AND
						     A.COD_TPFILTRO=32 AND
						     B.COD_CLIENTE=A.COD_CLIENTE)=$cod_cargo";
			}else{
				$andCargo = "";
			}

			if($cod_entidad != ""){
				$andEnt = "AND (SELECT distinct A.COD_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
							 WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
							 A.COD_TPFILTRO=B.COD_TPFILTRO AND
						     A.COD_FILTRO=B.COD_FILTRO AND
						     A.COD_TPFILTRO=31 AND
						     B.COD_CLIENTE=A.COD_CLIENTE)=$cod_entidad";
			}else{
				$andEnt = "";
			}

			if($cod_partido != ""){
				$andPart = "AND (SELECT distinct A.COD_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
							 WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
							 A.COD_TPFILTRO=B.COD_TPFILTRO AND
						     A.COD_FILTRO=B.COD_FILTRO AND
						     A.COD_TPFILTRO=33 AND
						     B.COD_CLIENTE=A.COD_CLIENTE)=$cod_partido";
			}else{
				$andPart = "";
			}

			if($cod_envolv != ""){
				$andEnvolv = "AND (SELECT distinct A.COD_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
							 WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
							 A.COD_TPFILTRO=B.COD_TPFILTRO AND
						     A.COD_FILTRO=B.COD_FILTRO AND
						     A.COD_TPFILTRO=30 AND
						     B.COD_CLIENTE=A.COD_CLIENTE)=$cod_envolv";
			}else{
				$andEnvolv = "";
			}

			$nomeRel = $_GET['nomeRel'];
			$arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 
                               
			$sql = "SELECT A.COD_CLIENTE AS CODIGO,
					A.DAT_NASCIME,
					('') AS IDADE,
					A.DES_EMAILUS AS EMAIL,
					(SELECT NOM_CLIENTE FROM CLIENTES WHERE CLIENTES.COD_CLIENTE=A.COD_INDICAD) AS NOM_INDICADOR,
					A.NOM_CLIENTE AS NOM_COLABORADOR,
					(SELECT DISTINCT DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
					WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
							A.COD_TPFILTRO=B.COD_TPFILTRO AND
					      A.COD_FILTRO=B.COD_FILTRO AND
					      A.COD_TPFILTRO=32 AND
					      B.COD_CLIENTE=A.COD_CLIENTE) AS CARGO,
					(SELECT DISTINCT DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
					WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
							A.COD_TPFILTRO=B.COD_TPFILTRO AND
					      A.COD_FILTRO=B.COD_FILTRO AND
					      A.COD_TPFILTRO=33 AND
					      B.COD_CLIENTE=A.COD_CLIENTE) AS PARTIDO,
					(SELECT DISTINCT DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
					WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
							A.COD_TPFILTRO=B.COD_TPFILTRO AND
					      A.COD_FILTRO=B.COD_FILTRO AND
					      A.COD_TPFILTRO=31 AND
					      B.COD_CLIENTE=A.COD_CLIENTE) AS 'REPRESENTAÇÃO/ENTIDADE',
					(SELECT DISTINCT DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
					WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
							A.COD_TPFILTRO=B.COD_TPFILTRO AND
					      A.COD_FILTRO=B.COD_FILTRO AND
					      A.COD_TPFILTRO=29 AND
					      B.COD_CLIENTE=A.COD_CLIENTE) AS GRUPO_TRABALHO,
					(SELECT DISTINCT DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
					WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
							A.COD_TPFILTRO=B.COD_TPFILTRO AND
					      A.COD_FILTRO=B.COD_FILTRO AND
					      A.COD_TPFILTRO=28 AND
					      B.COD_CLIENTE=A.COD_CLIENTE) AS REGIAO_TRABALHO,
					(SELECT DISTINCT DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
					WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
							A.COD_TPFILTRO=B.COD_TPFILTRO AND
					      A.COD_FILTRO=B.COD_FILTRO AND
					      A.COD_TPFILTRO=30 AND
					      B.COD_CLIENTE=A.COD_CLIENTE) AS ENVOLVIMENTO,
					      A.NUM_TELEFON,
						  A.NUM_CELULAR
					FROM CLIENTES A
					LEFT JOIN DADOS_APOIADOR DA ON DA.COD_CLIENTE = A.COD_CLIENTE
					WHERE
					A.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
					AND A.COD_EMPRESA = $cod_empresa
					$andIndicad
					$andGrupo
					$andReg
					$andCargo
					$andEnt
					$andPart
					$andEnvolv
					$andNome
					$andAnive
					$andIgreja
					$andLocal
					$andEstado
					$andCidade
					ORDER BY NOM_CLIENTE";
					
			// fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){

				$idade = "";

				if($row['DAT_NASCIME'] != ""){
			  		$idade = date_diff(date_create(fnDataSql($row['DAT_NASCIME'])), date_create('now'))->y;
			  	}

				  $newRow = array();
				  
				  $cont = 0;
				  $cont2 = 0;
				  foreach ($row as $objeto) {

				  	if($cont == 2 && $cont2 == 0){
				  		$objeto = $idade;
				  		array_push($newRow, $objeto);
				  		$cont = 1;
				  		$cont2++;
				  	}else{
						array_push($newRow, $objeto);
					}
					  
					$cont++;
				  }
				$array[] = $newRow;
			}

			$cont = 0;
			$cont2 = 0;
			$arrayColumnsNames = array();
			while($row = mysqli_fetch_field($arrayQuery))
			{
				if($cont == 2 && $cont2 == 0){
					array_push($arrayColumnsNames, "IDADE");
					$cont = 1;
					$cont2++;
				}else{
					array_push($arrayColumnsNames, $row->name);
				}

				$cont++;

			}			

			$writer->addRow($arrayColumnsNames);
			$writer->addRows($array);

			$writer->close();
			
		break;

		case 'exportar2':

			$count_filtros = fnLimpacampo($_REQUEST['COUNT_FILTROS']);
			$cod_filtro = "";
			$cod_tpfiltro = "";
			$andFiltros = "";
			$des_tpfiltros = [];
			$colunas = "";
			$filtros = "";

			if($count_filtros != ""){

			for ($i=0; $i < $count_filtros; $i++) {

				$cod_filtro = "";

				if (isset($_POST["COD_FILTRO_$i"])){

					$Arr_COD_FILTRO = $_POST["COD_FILTRO_$i"];

					if(fnLimpacampo($_POST["COD_TPFILTRO_$i"]) != ''){

						$cod_filtro = $cod_filtro.fnLimpacampo($_POST["COD_TPFILTRO_$i"]).":";

					}

				    for ($j=0;$j<count($Arr_COD_FILTRO);$j++){

						$cod_filtro = $cod_filtro.$Arr_COD_FILTRO[$j].",";
						$filtros = $filtros.$Arr_COD_FILTRO[$j].",";

				    }

				}

				if($_POST["COD_FILTRO_$i"] != ''){

					$cod_filtro = rtrim($cod_filtro,',');

					$tpFiltros_e_filtros = $tpFiltros_e_filtros.$cod_filtro.';'; 

					$filtros_div = explode(':', $cod_filtro);

					$cod_tpfiltro = $filtros_div[0];
					$cod_filtros = $filtros_div[1];

					$sql = "SELECT DES_TPFILTRO FROM TIPO_FILTRO WHERE COD_TPFILTRO = $cod_tpfiltro";
					$qrTipo = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
					array_push($des_tpfiltros, $qrTipo['DES_TPFILTRO']);
					$campo = explode(' ',strtoupper(fnacentos($qrTipo['DES_TPFILTRO'])));

					$colunas .= $campo[0].$i.".DES_FILTRO AS $campo[0],";

					$cod_filtros = rtrim(ltrim($cod_filtros,','),',');

					$innerJoin .= "
								  INNER JOIN CLIENTE_FILTROS ".$campo[0]." ON ".$campo[0].".COD_FILTRO IN($cod_filtros) AND ".$campo[0].".COD_TPFILTRO = $cod_tpfiltro AND ".$campo[0].".COD_CLIENTE=CL.COD_CLIENTE 
								  LEFT JOIN FILTROS_CLIENTE ".$campo[0].$i." ON ".$campo[0].".COD_FILTRO = ".$campo[0].$i.".COD_FILTRO
					";

				}	

			}

			$filtros = rtrim(ltrim($filtros,','),',');
			// fnEscreve($tpFiltros_e_filtros);


			// echo "<pre>";
			// print_r($des_tpfiltros);
			// echo "</pre>";

		}

			$nomeRel = $_GET['nomeRel'];
			$arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo);

			if($des_superb != ""){

				$arraybusca=array(
					'conn'=>connTemp('$cod_empresa', ''),
					'param_busca'=>'like',  
					'TextoConsulta'=> "%$des_superb%",
					'joinFiltros' => $innerJoin,
					'colunasAdicionais' => $colunas,
					'tipo' => 'export',
					'limite' => ''
				);

				$count = 0;

				$arrayQuery=fnConsultaMULT($arraybusca);

			}else{ 

				if($cod_empresa == 332){
					$colunas .= "DB.NUM_BANCO AS BANCO,
								 DB.NUM_AGENCIA AS AGENCIA,
								 DB.NUM_CONTACO AS CC,
								 DB.NUM_PIX AS PIX,
								 CASE WHEN TIP_PIX = 1 THEN 'CELULAR'
								 	  WHEN TIP_PIX = 2 THEN 'EMAIL'
								      WHEN TIP_PIX = 3 THEN 'CPF'
								      ELSE ''
								 END AS TIPO_CHAVE,
								 ";

					$innerJoin .= " LEFT JOIN DADOS_BANCARIOS DB ON DB.COD_CLIENTE = CL.COD_CLIENTE ";
				}

                               
				$sql = "SELECT CL.COD_CLIENTE AS CODIGO,
							   CL.COD_EXTERNO AS COD_EXTERNO,
							   CL.NOM_CLIENTE AS NOME,
							   CL.DAT_NASCIME AS NASCIMENTO,
							   CL.DAT_NASCIME AS IDADE,
							   CL.DES_EMAILUS AS EMAIL,
							   CL.DES_ENDEREC AS ENDERECO,
							   CL.NUM_ENDEREC AS NUMERO,
							   CL.DES_COMPLEM AS COMPLEMENTO,
							   CL.DES_BAIRROC AS BAIRRO,
							   CL.NUM_CEPOZOF AS CEP,
							   CL.NOM_CIDADEC AS CIDADE,
							   CL.COD_ESTADOF AS ESTADO,
							   (SELECT A.NOM_CLIENTE FROM CLIENTES A WHERE A.COD_CLIENTE = CL.COD_INDICAD) AS NOM_INDICADOR,       
							   CL.DAT_CADASTR,
						       $colunas
							   CL.NUM_CELULAR,
							   CL.NUM_TELEFON,
							   A.NOM_ENTIDAD AS IGREJA,
							   B.DES_GRUPOENT AS DISTRITO
						FROM CLIENTES CL
						LEFT JOIN DADOS_APOIADOR DA ON DA.COD_CLIENTE = CL.COD_CLIENTE
						LEFT JOIN ENTIDADE A ON A.COD_ENTIDAD = DA.COD_GRUPOENT
						LEFT JOIN ENTIDADE_GRUPO B ON B.COD_GRUPOENT = A.COD_GRUPOENT

						$innerJoin
						 
						WHERE CL.COD_EMPRESA = $cod_empresa
						$andCodigo
						$andNome
						$andCartao
						$andCpf
						$andIndicad
						$andAnive
						$andDataIni
						$andDataFim
						$andExterno
						$andAtivo
						$andIgreja
						$andLocal
						$andEstado
						$andCidade
						$andTermo
						AND CL.LOG_AVULSO = 'N' 
						GROUP BY CL.cod_cliente		
						ORDER BY CL.NOM_CLIENTE";
						
				fnEscreve($sql);
						
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

			}

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){

				  $newRow = array();

				  $cep = substr_replace(str_pad(str_replace('-', '', $row['CEP']), 8, '0', STR_PAD_LEFT), '-', 5, 0);
				  
				  $cont = 0;
				  foreach ($row as $objeto) {

				  	if($cont == 4){
				  		$idade = date_diff(date_create(fnDataSql($objeto)), date_create('now'))->y;
				  		array_push($newRow, $idade);
				  	}else if($cont == 10){
				  		array_push($newRow, $cep);
				  	}else{
						array_push($newRow, $objeto);
					}
					  
					$cont++;
				  }
				$array[] = $newRow;
			}

			$cont = 0;
			$cont2 = 0;
			$arrayColumnsNames = array();
			while($row = mysqli_fetch_field($arrayQuery))
			{
				
				array_push($arrayColumnsNames, $row->name);
				
				$cont++;

			}			

			$writer->addRow($arrayColumnsNames);
			$writer->addRows($array);

			$writer->close();
			
		break;
                
                case 'exportar 3':
                    
                    $nomeRel = $_GET['nomeRel'];
                    $arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';

                    $writer = WriterFactory::create(Type::CSV);
                    $writer->setFieldDelimiter(';');
                    $writer->openToFile($arquivo); 
                    
                    $sql = "SELECT CL.COD_CLIENTE, 
                                    CL.NOM_CLIENTE, 
                                    CL.DAT_NASCIME, 
                                    CL.DES_EMAILUS, 
                                    CL.DAT_CADASTR, 
                                    CL.NUM_CELULAR, 
                                    CL.NUM_TELEFON, 
                                    CL.NUM_CEPOZOF CEP, 
                                    CL.NOM_CIDADEC, 
                                    CL.COD_ESTADOF,
                                    CL.COD_EXTERNO,
                                 (
                                 SELECT A.NOM_CLIENTE
                                 FROM CLIENTES A
                                 WHERE A.COD_CLIENTE = CL.COD_INDICAD) AS NOM_INDICADOR

                                 FROM CLIENTES CL
                                 LEFT JOIN DADOS_APOIADOR DA ON DA.COD_CLIENTE = CL.COD_CLIENTE
                                 WHERE CL.COD_EMPRESA = $cod_empresa AND 
                                 CL.LOG_ESTATUS = 'S' AND 
                                 CL.LOG_AVULSO = 'N' AND 
                                 CL.cod_municipio IN(SELECT DISTINCT cod_municipio FROM regiao_usuario
                                                     WHERE cod_empresa=$cod_empresa AND 
                                                     cod_tpfiltro=28 AND 
                                                     cod_filtro IN(80))
                                 GROUP BY CL.cod_cliente
                                 ORDER BY CL.NOM_CLIENTE";
                    
                    $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){

				  $newRow = array();

				  $cep = substr_replace(str_pad(str_replace('-', '', $row['CEP']), 8, '0', STR_PAD_LEFT), '-', 5, 0);
				  
				  $cont = 0;
				  foreach ($row as $objeto) {

				  	if($cont == 4){
				  		$idade = date_diff(date_create(fnDataSql($objeto)), date_create('now'))->y;
				  		array_push($newRow, $idade);
				  	}else if($cont == 10){
				  		array_push($newRow, $cep);
				  	}else{
						array_push($newRow, $objeto);
					}
					  
					$cont++;
				  }
				$array[] = $newRow;
			}

			$cont = 0;
			$cont2 = 0;
			$arrayColumnsNames = array();
			while($row = mysqli_fetch_field($arrayQuery))
			{
				
				array_push($arrayColumnsNames, $row->name);
				
				$cont++;

			}			

			$writer->addRow($arrayColumnsNames);
			$writer->addRows($array);

			$writer->close();
		    break;
		case 'paginar':

				// Filtro por Grupo de Lojas
				// include "filtroGrupoLojas.php";
			
				$sql = "SELECT A.COD_CLIENTE
						FROM CLIENTES A
						LEFT JOIN DADOS_APOIADOR DA ON DA.COD_CLIENTE = A.COD_CLIENTE
						WHERE 
						A.COD_EMPRESA = $cod_empresa
						$andDataIni
						$andDataFim
						$andIndicad
						$andGrupo
						$andReg
						$andCargo
						$andEnt
						$andPart
						$andEnvolv
						$andNome
						$andAnive
						$andIgreja
						$andLocal
						$andEstado
						$andCidade
						$andTermo
						-- FILTRO UNIVERSAL PARA DATAS DE NASCIMENTO, COM CONVERSÃO DE DATA (SEM ANO)
						-- AND RIGHT(STR_TO_DATE(A.DAT_NASCIME, '%d/%m/%Y'),5) BETWEEN $dat_anive_ini AND $dat_anive_fim
						";
				// fnTestesql(connTemp($cod_empresa,''),$sql);		
				//fnEscreve($sql);

				$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
				$totalitens_por_pagina = mysqli_num_rows($retorno);

				$numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);

				// fnEscreve($totalitens_por_pagina);
				
				//variavel para calcular o início da visualização com base na página atual
				$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

				// Filtro por Grupo de Lojas
				// include "filtroGrupoLojas.php";

				$sql = "SELECT A.COD_CLIENTE, A.DAT_CADASTR, A.DAT_NASCIME, A.DES_EMAILUS,
						(SELECT NOM_CLIENTE FROM CLIENTES WHERE CLIENTES.COD_CLIENTE=A.COD_INDICAD) AS NOM_INDICADOR,
						A.NOM_CLIENTE AS NOM_COLABORADOR,
						(SELECT DISTINCT DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
						WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
								A.COD_TPFILTRO=B.COD_TPFILTRO AND
						      A.COD_FILTRO=B.COD_FILTRO AND
						      A.COD_TPFILTRO=32 AND
						      B.COD_CLIENTE=A.COD_CLIENTE) AS CARGO,
						(SELECT DISTINCT DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
						WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
								A.COD_TPFILTRO=B.COD_TPFILTRO AND
						      A.COD_FILTRO=B.COD_FILTRO AND
						      A.COD_TPFILTRO=29 AND
						      B.COD_CLIENTE=A.COD_CLIENTE) AS GRUPO_TRABALHO,
						(SELECT DISTINCT DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
						WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
								A.COD_TPFILTRO=B.COD_TPFILTRO AND
						      A.COD_FILTRO=B.COD_FILTRO AND
						      A.COD_TPFILTRO=28 AND
						      B.COD_CLIENTE=A.COD_CLIENTE) AS REGIAO_TRABALHO,
						(SELECT DISTINCT DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
						WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
								A.COD_TPFILTRO=B.COD_TPFILTRO AND
						      A.COD_FILTRO=B.COD_FILTRO AND
						      A.COD_TPFILTRO=30 AND
						      B.COD_CLIENTE=A.COD_CLIENTE) AS ENVOLVIMENTO,
						(SELECT DISTINCT DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
						WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
								A.COD_TPFILTRO=B.COD_TPFILTRO AND
						      A.COD_FILTRO=B.COD_FILTRO AND
						      A.COD_TPFILTRO=33 AND
						      B.COD_CLIENTE=A.COD_CLIENTE) AS PARTIDO,
						(SELECT DISTINCT DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
						WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
								A.COD_TPFILTRO=B.COD_TPFILTRO AND
						      A.COD_FILTRO=B.COD_FILTRO AND
						      A.COD_TPFILTRO=31 AND
						      B.COD_CLIENTE=A.COD_CLIENTE) AS ENTIDADE,
						      A.NUM_TELEFON,
							  A.NUM_CELULAR
						FROM CLIENTES A
						LEFT JOIN DADOS_APOIADOR DA ON DA.COD_CLIENTE = A.COD_CLIENTE
						WHERE
						A.COD_EMPRESA = $cod_empresa
						$andDataIni
						$andDataFim
						$andIndicad
						$andGrupo
						$andReg
						$andCargo
						$andEnt
						$andPart
						$andEnvolv
						$andNome
						$andAnive
						$andIgreja
						$andLocal
						$andEstado
						$andCidade
						$andTermo
						-- AND RIGHT(STR_TO_DATE(A.DAT_NASCIME, '%d/%m/%Y'),5) BETWEEN $dat_anive_ini AND $dat_anive_fim
						ORDER BY NOM_CLIENTE
						LIMIT $inicio,$itens_por_pagina
						";
				
				// fnEscreve($sql);
				//fnTestesql(connTemp($cod_empresa,''),$sql);											
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
									  
				$count=0;
				while ($qrApoia = mysqli_fetch_assoc($arrayQuery))
				  {

				  	$idade = "";

				  	if($qrApoia['NUM_CELULAR'] != "" && $qrApoia['NUM_TELEFON'] != ""){
				  		
				  		$tel = $qrApoia['NUM_CELULAR']."<br><div class='push5'></div>".$qrApoia['NUM_TELEFON'];
				  		
				  	}else if($qrApoia['NUM_CELULAR'] != "" && $qrApoia['NUM_TELEFON'] == ""){
				  		
				  		$tel = $qrApoia['NUM_CELULAR'];
				  		
				  	}else{
				  		
				  		$tel = $qrApoia['NUM_TELEFON'];
				  		
				  	}

				  	if($qrApoia['DAT_NASCIME'] != ""){
				  		$idade = date_diff(date_create(fnDataSql($qrApoia['DAT_NASCIME'])), date_create('now'))->y;
				  	}

					$count++;	
					echo"
						<tr>
						  <td><a href='action.do?mod=".fnEncode(1024)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($qrApoia['COD_CLIENTE'])."' class='f14' target='_blank'><small>".$qrApoia['NOM_COLABORADOR']."</small></a></td>
						  <td class='text-center'><small>".$qrApoia['DAT_NASCIME']."</small></td>
						  <td class='text-center'><small>".$idade."</small></td>
						  <td><small>".$qrApoia['DES_EMAILUS']."</small></td>
						  <td><small>".$qrApoia['NOM_INDICADOR']."</small></td>
						  <td class='text-center'><small>".fnDataShort($qrApoia['DAT_CADASTR'])."</small></td>
						  <td><small>".$qrApoia['CARGO']."</small></td>
						  <td><small>".$qrApoia['PARTIDO']."</small></td>
						  <td><small>".$qrApoia['ENTIDADE']."</small></td>
						  <td><small>".$qrApoia['GRUPO_TRABALHO']."</small></td>
						  <td><small>".$qrApoia['REGIAO_TRABALHO']."</small></td>
						  <td><small>".$qrApoia['ENVOLVIMENTO']."</small></td>
						  <td><small>".$tel."</small></td>
						</tr>
						"; 
					  }
				?>
				
				<th class="" colspan="100">
					<center><small style="font-weight: normal;">Resultados: <b><?=$inicio?></b> a <b><?=( $totalitens_por_pagina < ($itens_por_pagina+$inicio) ? $totalitens_por_pagina : ($itens_por_pagina+$inicio))?></b> de <b><?=$totalitens_por_pagina?></b> registros.</small></center>
				</th>

			
				<?php 									

			break; 		
	}
?>