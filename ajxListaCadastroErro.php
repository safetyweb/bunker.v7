<?php include '_system/_functionsMain.php'; 

	//echo fnDebug('true');

	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);	
?>

<div class="row">
	<div class="col-lg-12">

		<div class="no-more-tables">
		
			<form name="formLista" id="formLista" method="post" action="">
															 
															   
			<table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
				<thead>
				<tr>
				  <th class="bg-primary">Nome</th>
				  <th class="bg-primary">Cartão</th>
				  <th class="bg-primary">CPF</th>
				  <th class="bg-primary">e-Mail</th>
				  <th class="bg-primary">Sexo</th>
				  <th class="bg-primary">Nascimento</th>
				  <th class="bg-primary">Cadastro</th>
				  <th class="bg-primary">Origem</th>
				</tr>
				</thead>
				
				<tbody>
															  
			<?php
				$sql = "SELECT COUNT(*) as CONTADOR FROM CLIENTES B
						WHERE 
						B.LOG_AVULSO='N' AND
						B.COD_EMPRESA = $cod_empresa AND
						B.COD_SEXOPES = 3 ";
						
				//fnEscreve($sql);
				
				$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
				$total_itens_por_pagina = mysqli_fetch_assoc($retorno);
				
				$numPaginas = ceil($total_itens_por_pagina['CONTADOR']/$itens_por_pagina);
				
				//variavel para calcular o início da visualização com base na página atual
				$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
													
				
				$sql = "SELECT B.COD_CLIENTE,B.NUM_CARTAO,B.NUM_CGCECPF,B.NOM_CLIENTE,
						B.DES_EMAILUS,B.DAT_CADASTR,B.DAT_NASCIME ,B.COD_SEXOPES, C.NOM_UNIVEND 
						FROM CLIENTES B, unidadevenda C
						WHERE 
						B.COD_UNIVEND=C.COD_UNIVEND AND
						B.LOG_AVULSO='N' AND
						B.COD_EMPRESA = $cod_empresa AND
						B.COD_SEXOPES = 3	
						order by B.NOM_CLIENTE limit $inicio,$itens_por_pagina";
						
				//fnEscreve($sql);
				
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
				
				$count=0;
				while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery))
				  {														  
					$count++;
												
					 if ($qrListaPersonas['COD_SEXOPES'] == 1){		
							$mostraSexo = '<i class="fa fa-male" aria-hidden="true"></i>';	
						}

					 if ($qrListaPersonas['COD_SEXOPES'] == 2){		
							$mostraSexo = '<i class="fa fa-female" aria-hidden="true"></i>';	
						}	

					 if ($qrListaPersonas['COD_SEXOPES'] == 3){		
							$mostraSexo = '<i class="fa fa-venus-mars" aria-hidden="true"></i>';	
						}	
						
					echo"
						<tr>
						  <td><small><a href='action.do?mod=".fnEncode(1024)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($qrListaPersonas['COD_CLIENTE'])."' target='_blank'>".$qrListaPersonas['NOM_CLIENTE']."</a></td>
						  <td><small>".$qrListaPersonas['NUM_CARTAO']."</small></td>
						  <td><small>".$qrListaPersonas['NUM_CGCECPF']."</small></td>
						  <td><small>".$qrListaPersonas['DES_EMAILUS']."</small></td>
						  <td class='text-center'>".$mostraSexo."</td>
						  <td><small>".$qrListaPersonas['DAT_NASCIME']."</small></td>
						  <td><small>".fnDataFull($qrListaPersonas['DAT_CADASTR'])."</small></td>
						  <td><small>".$qrListaPersonas['NOM_UNIVEND']."</small></td>
						</tr>
						";
					  }											
				
			?>
				
			</tbody>
			
			<tfoot>
				<tr>
				  <th class="" colspan="100"><ul class="pagination pagination-sm">
				  <?php
					for($i = 1; $i < $numPaginas + 1; $i++) {
						if ($pagina == $i){$paginaAtiva = "active";}else{$paginaAtiva = "";}	
					echo "<li class='pagination $paginaAtiva'><a href='#' onclick='reloadPage($i);' style='text-decoration: none;'>".$i."</a></li>";   
					}													  
				  ?></ul>
				  </th>
				</tr>
			</tfoot>

			</table>
			
			<div class="push"></div>
			
			</form>
													 
		</div>
		
	</div>											
</div>