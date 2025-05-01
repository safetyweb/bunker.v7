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
	
	$cod_univend = $_POST['COD_UNIVEND'];
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	$lojasSelecionadas = $_POST['LOJAS'];
	
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
	
	switch ($opcao) {
		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 			
					
			$sql = "SELECT  DISTINCT  A.COD_CLIENTE, 
					   A.NUM_CARTAO, 
					   A.NUM_CGCECPF, 
					   A.NOM_CLIENTE, 
					   A.DES_EMAILUS, 
					   A.DAT_CADASTR, 
					   A.DAT_NASCIME, 
					   A.NUM_CELULAR, 
					   A.COD_SEXOPES,
						(SELECT ifnull(SUM(VAL_SALDO),0)
						FROM CREDITOSDEBITOS 
						WHERE COD_CLIENTE=A.COD_CLIENTE AND
						TIP_CREDITO='C' AND
						COD_STATUSCRED=1 AND
						(DAT_EXPIRA > NOW() or(LOG_EXPIRA='N'))
						) AS CREDITO_DISPONIVEL,
						(SELECT  ifnull(SUM(VAL_SALDO),0)
						FROM CREDITOSDEBITOS 
						WHERE COD_CLIENTE=A.cod_cliente AND
						TIP_CREDITO='C' AND
						COD_STATUSCRED=2 AND
						(DAT_EXPIRA > NOW() or(LOG_EXPIRA='N')) ) AS CREDITO_LIBERAR 																			
					FROM CLIENTES A, VENDAS B 
					WHERE 
					A.COD_CLIENTE=B.COD_CLIENTE AND
					A.COD_EMPRESA = $cod_empresa 
					AND DATE_FORMAT(B.DAT_CADASTR_WS, '%Y-%m-%d') >= '$dat_ini' 
					AND DATE_FORMAT(B.DAT_CADASTR_WS, '%Y-%m-%d') <= '$dat_fim' 
					AND A.LOG_AVULSO='N'
					AND B.COD_UNIVEND IN($lojasSelecionadas)
					order by A.NOM_CLIENTE ";
					
					
			//fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$array = array();
			
			while($row = mysqli_fetch_assoc($arrayQuery)){
				$array[] = $row;
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

			$sql = "SELECT COUNT(DISTINCT A.COD_CLIENTE) as CONTADOR
					FROM CLIENTES A, VENDAS B 
					WHERE 
					A.COD_CLIENTE=B.COD_CLIENTE AND
					A.COD_EMPRESA = $cod_empresa 
					AND DATE_FORMAT(B.DAT_CADASTR_WS, '%Y-%m-%d') >= '$dat_ini' 
					AND DATE_FORMAT(B.DAT_CADASTR_WS, '%Y-%m-%d') <= '$dat_fim' 
					AND A.LOG_AVULSO='N'
					AND B.COD_UNIVEND IN($lojasSelecionadas) ";
			//fnEscreve($sql);
			
			$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			$total_itens_por_pagina = mysqli_fetch_assoc($retorno);
			
			$numPaginas = ceil($total_itens_por_pagina['CONTADOR']/$itens_por_pagina);
			
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
					
			$sql = "SELECT  DISTINCT  A.COD_CLIENTE, 
					   A.NUM_CARTAO, 
					   A.NUM_CGCECPF, 
					   A.NOM_CLIENTE, 
					   A.DES_EMAILUS, 
					   A.DAT_CADASTR, 
					   A.DAT_NASCIME, 
					   A.NUM_CELULAR, 
					   A.COD_SEXOPES,
						(SELECT ifnull(SUM(VAL_SALDO),0)
						FROM CREDITOSDEBITOS 
						WHERE COD_CLIENTE=A.COD_CLIENTE AND
						TIP_CREDITO='C' AND
						COD_STATUSCRED=1 AND
						(DAT_EXPIRA > NOW() or(LOG_EXPIRA='N'))
						) AS CREDITO_DISPONIVEL,

						(SELECT  ifnull(SUM(VAL_SALDO),0)
						FROM CREDITOSDEBITOS 
						WHERE COD_CLIENTE=A.cod_cliente AND
						TIP_CREDITO='C' AND
						COD_STATUSCRED=2 AND
						(DAT_EXPIRA > NOW() or(LOG_EXPIRA='N')) ) AS CREDITO_LIBERAR 																			
					FROM CLIENTES A, VENDAS B 
					WHERE 
					A.COD_CLIENTE=B.COD_CLIENTE AND
					A.COD_EMPRESA = $cod_empresa 
					AND DATE_FORMAT(B.DAT_CADASTR_WS, '%Y-%m-%d') >= '$dat_ini' 
					AND DATE_FORMAT(B.DAT_CADASTR_WS, '%Y-%m-%d') <= '$dat_fim' 
					AND A.LOG_AVULSO='N'
					AND B.COD_UNIVEND IN($lojasSelecionadas)
					order by A.NOM_CLIENTE limit $inicio, $itens_por_pagina ";
					
			//fnEscreve($sql);
			
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			
			$count=0;
			while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery))
			  {														  
				$count++;
											
				 if ($qrListaPersonas['COD_SEXOPES'] == 1){		
						$mostraSexo = '<i class="fa fa-male" aria-hidden="true"></i>';	
					}else{ $mostraSexo = '<i class="fa fa-female" aria-hidden="true"></i>'; }	
											
				echo"
					<tr>
					  <td><small><a href='action.do?mod=".fnEncode(1024)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($qrListaPersonas['COD_CLIENTE'])."' target='_blank'>".$qrListaPersonas['NOM_CLIENTE']."</a></td>
					  <td><small>".$qrListaPersonas['NUM_CARTAO']."</small></td>
					  <td><small>".$qrListaPersonas['NUM_CGCECPF']."</small></td>
					  <td><small>".$qrListaPersonas['DES_EMAILUS']."</small></td>
					  <td><small>".$qrListaPersonas['NUM_CELULAR']."</small></td>
					  <td class='text-center'>".$mostraSexo."</td>
					  <td><small>".$qrListaPersonas['DAT_NASCIME']."</small></td>
					  <td class='text-center'><small>".$qrListaPersonas['CREDITO_DISPONIVEL']."</small></td>
					  <td class='text-center'><small>".$qrListaPersonas['CREDITO_LIBERAR']."</small></td>
					  <td><small>".fnDataFull($qrListaPersonas['DAT_CADASTR'])."</small></td>
					</tr>
					"; 
				  }

			break; 		
	}
?>