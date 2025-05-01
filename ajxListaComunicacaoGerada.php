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
	
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	
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

			break;     
		case 'paginar':

			$sql="select count(*) as CONTADOR
					from gera_comunicacao
				where 
				  DATE_FORMAT(DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' 
				  AND DATE_FORMAT(DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim' 
				  AND COD_VENDA = 0";
					  
			//fnEscreve($sql);

			$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			$totalitens_por_pagina = mysqli_fetch_assoc($retorno);
			$numPaginas = ceil($totalitens_por_pagina['CONTADOR']/$itens_por_pagina);
			
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

			//,MSG,DES_VENDA 
			//select dinâmico do relatório
			$sql="select gera.COD_COMUNIC,
						 gera.DAT_CADASTR,
						 gera.COD_COMUNICACAO,
						 gera.COD_TIPCOMU,
						 gera.COD_CLIENTE,
						 gera.COD_VENDA,
						 gera.LOG_ENVIADO,
						 comunicacao.DES_COMUNICACAO,
						 comunicacao_tipo.DES_TIPCOMU,
						 clientes.NOM_CLIENTE
					from gera_comunicacao gera
					inner join $connAdm->DB.comunicacao on gera.COD_COMUNICACAO = comunicacao.COD_COMUNICACAO
					inner join $connAdm->DB.comunicacao_tipo on gera.COD_TIPCOMU = comunicacao_tipo.COD_TIPCOMU
					inner join clientes on gera.COD_CLIENTE = clientes.COD_CLIENTE
				where 
				  DATE_FORMAT(gera.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' 
				  AND DATE_FORMAT(gera.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim' 
				  AND COD_VENDA = 0 limit $inicio,$itens_por_pagina";  
				//fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());


		$countLinha = 1;
		while ($qrListaComunica = mysqli_fetch_assoc($arrayQuery)) {	
			?>
				<tr>
				  <td class="text-center"><?php echo $qrListaComunica['COD_COMUNIC']?></td>
				  <td><?php echo fnDataFull($qrListaComunica['DAT_CADASTR'])?></td>
				  <td><?php echo $qrListaComunica['DES_COMUNICACAO']?></td>
				  <td><?php echo $qrListaComunica['DES_TIPCOMU']?></td>
				  <td><?php echo $qrListaComunica['NOM_CLIENTE']?></td>
				  <td class="text-center">
					  <?php
					  if($qrListaComunica['LOG_ENVIADO'] == 'S'){
						  echo "<div class='btn btn-xs btn-success' style='cursor: initial'>Enviado</div>";
					  } else {
						  echo "<div class='btn btn-xs btn-warning' style='cursor: initial'>Não Enviado</div>"; 
					  }
					  ?>
				  </td>
				  <td>log</td>
				</tr>
			<?php
			 
		}									

			break; 		
	}
?>