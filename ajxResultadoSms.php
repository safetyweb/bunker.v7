<?php 

	include './_system/_functionsMain.php'; 
	require_once './js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$cod_empresa = fnDecode($_GET['id']);			
	$cod_campanha = fnLimpaCampoZero($_REQUEST['COD_CAMPANHA']);
	$cod_disparo = fnLimpaCampoZero($_REQUEST['COD_DISPARO']);

	$nomeRel = $_GET['nomeRel'];
	$arquivo = './media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';

	$writer = WriterFactory::create(Type::CSV);
	$writer->setFieldDelimiter(';');
	$writer->openToFile($arquivo);

	if($cod_disparo != 0){	
	
		switch ($opcao) {

			case 'sent':
				$andFiltro = "AND STATUS_ENVIO = 1";
			break;

			case 'lidos':
				$andFiltro = "AND COD_LEITURA = 1";
			break;
			
			case 'nlidos':
				$andFiltro = "AND COD_LEITURA = 0";
			break;
			
			case 'hbounce':
				$andFiltro = "AND STATUS_ENVIO = 3";
			break;
			
			case 'sbounce':
				$andFiltro = "AND STATUS_ENVIO = 2";
			break;
			
			case 'optout':
				$andFiltro = "AND COD_OPTOUT_ATIVO = 1";
			break;

		} 
				       
		$sql = "SELECT DES_EMAILUS,
					   NOM_CLIENTE,
					   DAT_NASCIME,
					   dat_leitura AS DAT_LEITURA,
					   TIP_NAVEGADOR,
					   TIP_MODELO,
					   DAT_OPOUT,
					   DES_MOTIVO
				FROM EMAIL_LISTA_RET
			    WHERE COD_EMPRESA = $cod_empresa
			    AND COD_CAMPANHA = $cod_campanha
			    AND ID_DISPARO = $cod_disparo 
			    $andFiltro";
				
		//fnEscreve($sql);
				
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

		$array = array();
		while($row = mysqli_fetch_assoc($arrayQuery)){
			  $newRow = array();
			  
			  $cont = 0;
			  foreach ($row as $objeto) {
				array_push($newRow, $objeto);
				$cont++;
			  }
			$array[] = $newRow;
		}

		$arrayColumnsNames = array();
		while($row = mysqli_fetch_field($arrayQuery))
		{
			array_push($arrayColumnsNames, $row->name);
		}		

    }else{

    	$sql = "SELECT
					CEM.COD_DISPARO,
					CEM.QTD_DIFERENCA,
					CEM.QTD_CONTATOS,
					CEM.QTD_EXCLUSAO,
					SUM(CEM.QTD_DISPARADOS) AS QTD_DISPARADOS,
					SUM(CEM.QTD_SUCESSO) AS QTD_SUCESSO,
					SUM(CEM.QTD_FALHA) AS QTD_FALHA,
					SUM(CEM.QTD_LIDOS) AS QTD_LIDOS,
					SUM(CEM.QTD_NLIDOS) AS QTD_NLIDOS,
					SUM(CEM.QTD_OPTOUT) AS QTD_OPTOUT,
					SUM(CEM.QTD_CLIQUES) AS QTD_CLIQUES,
					CEM.DAT_ENVIO,
					TE.NOM_TEMPLATE,
					EL.QTD_LISTA,
					EL.DES_PATHARQ,
					EL.COD_GERACAO,
					EL.COD_CONTROLE,
					EL.COD_LOTE
				FROM CONTROLE_ENTREGA_MAIL CEM
				LEFT JOIN TEMPLATE_EMAIL TE ON TE.COD_EXT_TEMPLATE = CEM.ID_TEMPLETE
				LEFT JOIN EMAIL_LOTE EL ON EL.COD_DISPARO_EXT = CEM.COD_DISPARO
				WHERE CEM.COD_EMPRESA = $cod_empresa
				AND CEM.COD_CAMPANHA = $cod_campanha
				AND EL.LOG_TESTE = 'N' 
				GROUP BY CEM.COD_DISPARO
				ORDER BY EL.COD_CONTROLE DESC";
				
		// fnEscreve($sql);
				
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

		$array = array();

		while($row = mysqli_fetch_assoc($arrayQuery)){

			$newRow = array();

			$pct_sucesso = ($row['QTD_SUCESSO']/$row['QTD_DISPARADOS'])*100;
			$pct_falha = ($row['QTD_FALHA']/$row['QTD_DISPARADOS'])*100;
			$pct_lidos = ($row['QTD_LIDOS']/$row['QTD_DISPARADOS'])*100;
			$pct_nlidos = ($row['QTD_NLIDOS']/$row['QTD_DISPARADOS'])*100;
			  
			array_push($newRow, $row['COD_DISPARO']);
			array_push($newRow, "#".$row['COD_CONTROLE']."/".$row['COD_LOTE']);
			array_push($newRow, $row['DAT_ENVIO']);
			array_push($newRow, fnValor($row['QTD_LISTA'],0));
			array_push($newRow, fnValor($row['QTD_CONTATOS'],0));
			array_push($newRow, fnValor($row['QTD_EXCLUSAO']+$row['QTD_DIFERENCA'],0));
			array_push($newRow, fnValor($row['QTD_DISPARADOS'],0));
			array_push($newRow, fnValor($row['QTD_SUCESSO'],0));
			array_push($newRow, fnValor($pct_sucesso,2)."%");
			array_push($newRow, fnValor($row['QTD_FALHA'],0));
			array_push($newRow, fnValor($pct_falha,2)."%");
			array_push($newRow, fnValor($row['QTD_LIDOS'],0));
			array_push($newRow, fnValor($pct_lidos,2)."%");
			array_push($newRow, fnValor($row['QTD_NLIDOS'],0));
			array_push($newRow, fnValor($pct_nlidos,2)."%");
			array_push($newRow, fnValor($row['QTD_OPTOUT'],0));
			array_push($newRow, fnValor($row['QTD_CLIQUES'],0));

			$array[] = $newRow;

		}

		$arrayColumnsNames = array();
		array_push($arrayColumnsNames, "DISPARO");
		array_push($arrayColumnsNames, "LOTE");
		array_push($arrayColumnsNames, "DATA DE ENVIO");
		array_push($arrayColumnsNames, "LISTA");
		array_push($arrayColumnsNames, "CONTATOS");
		array_push($arrayColumnsNames, "EXCLUSÃO");
		array_push($arrayColumnsNames, "DISPARADOS");
		array_push($arrayColumnsNames, "SUCESSO");
		array_push($arrayColumnsNames, "% SUCESSO");
		array_push($arrayColumnsNames, "FALHAS");
		array_push($arrayColumnsNames, "% FALHAS");
		array_push($arrayColumnsNames, "LIDOS");
		array_push($arrayColumnsNames, "% LIDOS");
		array_push($arrayColumnsNames, "NÃO LIDOS");
		array_push($arrayColumnsNames, "% NÃO LIDOS");
		array_push($arrayColumnsNames, "OPT OUT");
		array_push($arrayColumnsNames, "CLIQUES");
    }

    $writer->addRow($arrayColumnsNames);
	$writer->addRows($array);

	$writer->close();
?>