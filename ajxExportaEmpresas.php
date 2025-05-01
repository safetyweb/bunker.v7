<?php 

	include '_system/_functionsMain.php'; 
	require_once 'js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);			
	$val_pesquisa = fnLimpaCampo($_POST['INPUT']);

	if($filtro != ""){
		$andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
	}else{
		$andFiltro = " ";
	}
	
	switch ($opcao) {
		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivo = 'media/excel/3_'.$nomeRel.'.csv';

			fnEscreve($arquivo);
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo);
			       
			if ($_SESSION["SYS_COD_MASTER"] == "2" ) {
				$sql = "SELECT 
						empresas.COD_EMPRESA,
						empresas.NOM_FANTASI,
						empresas.NOM_RESPONS AS RESPONSAVEL,
						(select NOM_USUARIO from webtools.usuarios where cod_empresa=3 and cod_usuario=empresas.cod_consultor) as COORDENADOR, 
						(SELECT D.NOM_FANTASI FROM EMPRESAS D WHERE D.COD_EMPRESA=empresas.COD_INTEGRADORA  ) NOM_INTEGRADORA,
						empresas.PCT_PARCEIRO,
						(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA) AS LOJAS,
						(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA AND UV.LOG_ESTATUS = 'S') AS LOJAS_ATIVAS,	
						empresas.LOG_INTEGRADORA AS SH,
						empresas.LOG_ATIVO AS ATIVA,
						B.COD_DATABASE AS BD,
						STATUSSISTEMA.DES_STATUS AS STATUS,
						empresas.LOG_CONSEXT AS DATAQUALITY,
						empresas.DAT_PRODUCAO AS PRODUCAO
						FROM empresas 
						LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=empresas.COD_STATUS 
						LEFT JOIN tab_database B ON B.cod_empresa=empresas.COD_EMPRESA 
						WHERE empresas.COD_EMPRESA <> 1
						$andFiltro
						ORDER by NOM_FANTASI";
			
			}else {
				$sql = "SELECT 
						empresas.COD_EMPRESA,
						empresas.NOM_FANTASI,
						empresas.NOM_RESPONS AS RESPONSAVEL,
						(select NOM_USUARIO from webtools.usuarios where cod_empresa=3 and cod_usuario=empresas.cod_consultor) as COORDENADOR, 
						(SELECT D.NOM_FANTASI FROM EMPRESAS D WHERE D.COD_EMPRESA=empresas.COD_INTEGRADORA  ) NOM_INTEGRADORA,
						empresas.PCT_PARCEIRO,
						(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA) AS LOJAS,
						(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA AND UV.LOG_ESTATUS = 'S') AS LOJAS_ATIVAS,	
						empresas.LOG_INTEGRADORA AS SH,
						empresas.LOG_ATIVO AS ATIVA,
						B.COD_DATABASE AS BD,
						STATUSSISTEMA.DES_STATUS AS STATUS,
						empresas.LOG_CONSEXT AS DATAQUALITY,
						empresas.DAT_PRODUCAO AS PRODUCAO 
						FROM empresas 
						LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=empresas.COD_STATUS 
						LEFT JOIN tab_database B ON B.cod_empresa=empresas.COD_EMPRESA 
						WHERE COD_MASTER IN (1,".$_SESSION["SYS_COD_MASTER"].",".$_SESSION["SYS_COD_EMPRESA"].")
						$andFiltro
						ORDER by NOM_FANTASI";
			}

			fnEscreve($sql);
					
			$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {

				  	if($cont == 5){
						array_push($newRow, fnValor($objeto,2)."%");
				  	}else if($cont == 10){
				  		if($objeto > 0){
							array_push($newRow, "S");
				  		}else{
							array_push($newRow, "N");
				  		}
				  	}else if($cont == 13){
						array_push($newRow, fnDataShort($objeto));
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

		break; 		
	}
?>