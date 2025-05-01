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
	$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CGCECPF']));
	$nom_cliente = fnLimpaCampo($_POST['NOM_CLIENTE']);
	
	
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
		$andNome = "AND CL.NOM_CLIENTE LIKE '%$nom_cliente%'";
	}else{
		$andNome = "";
	}

	if($num_cgcecpf != ""){
		$andCpf = "AND CL.NUM_CGCECPF = '$num_cgcecpf'";
	}else{
		$andCpf = "";
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
                               
			$sql = "SELECT UN.NOM_FANTASI AS LOJA, 
						   CL.NOM_CLIENTE AS CLIENTE, 
						   CL.NUM_CGCECPF AS CPF, 
						   CL.DES_EMAILUS AS EMAIL,
						   EE.MSG_ENVIO AS MSG,
						   EE.COD_CUPOM AS CUPONS,
						   EE.COD_VENDA,
						   EE.DAT_ENVIO
					FROM LOG_ENVIOEMAIL EE
					LEFT JOIN GERACUPOM GC ON GC.COD_VENDA = EE.COD_VENDA AND GC.COD_CLIENTE=EE.COD_CLIENTE 
					LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = EE.COD_CLIENTE
					LEFT JOIN WEBTOOLS.UNIDADEVENDA UN ON UN.COD_UNIVEND = GC.COD_UNIVEND
					WHERE
					EE.DAT_ENVIO BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					AND GC.COD_UNIVEND IN($lojasSelecionadas)
					AND EE.COD_EMPRESA = $cod_empresa
					$andNome
					$andCpf
					GROUP BY EE.COD_CLIENTE
					ORDER BY EE.DAT_ENVIO DESC";
					
			//fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {
					  
					if($cont == 5){
						array_push($newRow, fnDataFull($objeto));
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
		    
		case 'paginar':

			// Filtro por Grupo de Lojas
			include "filtroGrupoLojas.php";			
            //============================
		
			$sql = "SELECT EE.*, GC.COD_UNIVEND, CL.NOM_CLIENTE FROM LOG_ENVIOEMAIL EE
					LEFT JOIN GERACUPOM GC ON GC.COD_VENDA = EE.COD_VENDA
					LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = EE.COD_CLIENTE
					WHERE
					EE.DAT_ENVIO BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					AND GC.COD_UNIVEND IN($lojasSelecionadas)
					AND EE.COD_EMPRESA = $cod_empresa
					";

			// fnEscreve($sql);

			$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
			$totalitens_por_pagina = mysqli_num_rows($retorno);

			$numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);
			
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

			// Filtro por Grupo de Lojas
			include "filtroGrupoLojas.php";

			$sql = "SELECT EE.*, GC.COD_UNIVEND, CL.NOM_CLIENTE FROM LOG_ENVIOEMAIL EE
					LEFT JOIN GERACUPOM GC ON GC.COD_VENDA = EE.COD_VENDA
					LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = EE.COD_CLIENTE
					WHERE
					EE.DAT_ENVIO BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					AND GC.COD_UNIVEND IN($lojasSelecionadas)
					AND EE.COD_EMPRESA = $cod_empresa
					ORDER BY EE.DAT_ENVIO DESC
					LIMIT $inicio,$itens_por_pagina
					";
			
			// fnEscreve($sql);		
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
								  
			$count=0;
			while ($qrEnvio = mysqli_fetch_assoc($arrayQuery))
			{								

			  	$sqlUni = "SELECT NOM_FANTASI FROM UNIDADEVENDA WHERE COD_UNIVEND=".$qrEnvio['COD_UNIVEND'];

			  	$qrEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlUni));

				$count++;	
				echo"
					<tr>
					  <td><small>".$qrEmp['NOM_FANTASI']."</small></td>
					  <td><small>".$qrEnvio['NOM_CLIENTE']."</small></td>
					  <td><small>".fnDataFull($qrEnvio['DAT_ENVIO'])."</small></td>
					  <td><small>".$qrEnvio['MSG_ENVIO']."</small></td>
					  <td><small>".$qrEnvio['COD_CUPOM']."</small></td>
					  <td class='text-center'><small>".$qrEnvio['COD_VENDA']."</small></td>
					</tr>
					"; 
			}									

		break; 		
	}
?>