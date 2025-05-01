<?php include "_system/_functionsMain.php"; 

//echo fnDebug('true');

$cod_empresa = fnLimpacampo($_GET['codEmpresa']);

$des_produto = str_replace(' ', '', fnLimpacampo($_GET['descricao']));
$descricaoCodigo = fnLimpacampo($_GET['descricaoCodigo']);

$categoria = fnLimpacampo($_GET['categoria']);
$subCategoria = fnLimpacampo($_GET['subcategoria']);
$fornecedor = fnLimpacampo($_GET['fornecedor']);
$persona = fnLimpacampo($_GET['persona']);
$campanha = fnLimpacampo($_GET['campanha']);


?>	
<div class="col-lg-9">

	<div class="push30"></div>					

	<div class="no-more-tables">
	
		<form name="formLista">
		
		<table class="table table-hover p-table">
		
		<tbody>	
		
		<?php 
			$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
			
			//fnEscreve("com pesquisa");
			if ($des_produto != "" ){
				if($descricaoCodigo == 'string'){
					$andProduto = ' AND A.DES_PRODUTO like "%'.$des_produto.'%"';
				}else{
					$andProduto = ' AND A.COD_PRODUTO = '.$des_produto. ' or A.COD_EXTERNO = "' .$des_produto. '"';
				}		
			}else { 			
				$andProduto = ' ';
			}
			
			$andCategoria = "";
			if ($categoria != "" ){
				$andCategoria = ' AND A.COD_CATEGOR in ('.$categoria.')';			
			}
			
			$andSubCategoria = "";
			if ($subCategoria != "" ){
				$andSubCategoria = ' AND A.COD_SUBCATE in ('.$subCategoria.')';			
			}			
		
			$andFornecedor = "";
			if ($fornecedor != "" ){
				$andFornecedor = ' AND A.COD_FORNECEDOR in ('.$fornecedor.')';			
			}

			if ($persona == 'true'){
				$andPersona = ' AND IFNULL(COD_PERSONA_TKT,0) >0 ';			
			}
			
			if ($campanha == 'true'){
				$andCampanha = ' AND (SELECT COUNT(*) FROM VANTAGEMEXTRAFAIXA WHERE COD_EMPRESA = A.COD_EMPRESA AND COD_PRODUTO = A.COD_PRODUTO)>0 AND
								     (SELECT COUNT(*) FROM CAMPANHAPRODUTO WHERE COD_EMPRESA = A.COD_EMPRESA AND COD_CATEGOR = A.COD_CATEGOR AND COD_SUBCATE = A.COD_SUBCATE )>0 ';			
			}						
				
			//se pesquisa dos produtos do ticket
			if (!empty($_GET['idP'])) {$andExterno = 'AND A.COD_EXTERNO = "'.$_GET['idP'].'"';}
			
			//fnEscreve("entrou");

			$sql="SELECT COUNT(*) AS contador FROM PRODUTOCLIENTE A
					LEFT JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR
					LEFT JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE
					LEFT JOIN PRODUTOTKT D ON A.COD_PRODUTO = D.COD_PRODUTO 
					where A.COD_EMPRESA='".$cod_empresa."' 
					".$andProduto."
					".$andCategoria."
					".$andSubCategoria."
					".$andFornecedor."
					".$andPersona."
					".$andCampanha."
					AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO";
				
			//fnEscreve($sql);
			$resPagina = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			$total = mysqli_fetch_assoc($resPagina);
			//seta a quantidade de itens por página, neste caso, 2 itens
			$registros =50;
			//calcula o número de páginas arredondando o resultado para cima
			$numPaginas = ceil($total['contador']/$registros);
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($registros*$pagina)-$registros;
			
			$sql1=" select A.*,
						   B.DES_CATEGOR as GRUPO,
						   C.DES_SUBCATE as SUBGRUPO,
							IFNULL((SELECT LOG_PRODTKT FROM PRODUTOTKT WHERE COD_EMPRESA=A.COD_EMPRESA AND COD_PRODUTO=A.COD_PRODUTO AND LOG_PRODTKT='S'),'N') AS LOG_PRODTKT,
							IFNULL((SELECT VAL_PRODTKT FROM PRODUTOTKT WHERE COD_EMPRESA=A.COD_EMPRESA AND COD_PRODUTO=A.COD_PRODUTO),0) AS VAL_PRODTKT,
							IFNULL((SELECT VAL_PROMTKT FROM PRODUTOTKT WHERE COD_EMPRESA=A.COD_EMPRESA AND COD_PRODUTO=A.COD_PRODUTO),0) AS VAL_PROMTKT,
							IFNULL((SELECT SUM(QTD_ESTOQUE) FROM PRODUTO_COMPLEMENTO WHERE COD_EMPRESA=A.COD_EMPRESA AND COD_PRODUTO=A.COD_PRODUTO),0) AS QTD_ESTOQUE,
							(SELECT COUNT(*) FROM CAMPANHAPRODUTO WHERE COD_EMPRESA=A.COD_EMPRESA  AND COD_CATEGOR = A.COD_CATEGOR AND COD_SUBCATE = A.COD_SUBCATE) AS CAMPANHA,
							(SELECT COUNT(*) FROM VANTAGEMEXTRAFAIXA WHERE COD_EMPRESA=A.COD_EMPRESA AND COD_PRODUTO=A.COD_PRODUTO) AS FAIXAS,
							IFNULL(COD_PERSONA_TKT,0) as COD_PERSONA,
							IFNULL((SELECT NOM_PRODTKT FROM PRODUTOTKT WHERE COD_EMPRESA=A.COD_EMPRESA AND COD_PRODUTO=A.COD_PRODUTO),'N') AS NOM_PRODTKT
						from PRODUTOCLIENTE A 
						LEFT JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
						LEFT JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
						LEFT JOIN PRODUTOTKT D  ON A.COD_PRODUTO = D.COD_PRODUTO						
						where A.COD_EMPRESA='".$cod_empresa."' 
						".$andProduto."
						".$andCategoria."
						".$andSubCategoria."
						".$andFornecedor."
						".$andPersona."
						".$andCampanha."
						AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO limit $inicio,$registros ";
			
			//fnEscreve($sql1);
			$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql1) or die(mysqli_error());
			
			$count=0;
			while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery))
			{														  
				$count++;

				if ($qrListaProduto['DES_IMAGEM'] != "") {
					$mostraDES_IMAGEM = "<img src='http://img.bunker.mk/media/clientes/$cod_empresa/produtos/".$qrListaProduto['DES_IMAGEM']."' style='max-width:70px; max-height: 100%'/>";	
				}else{ $mostraDES_IMAGEM = ""; }	
				
				if ($qrListaProduto['NOM_PRODTKT'] == "N") {
					$mostraNOM_PRODTKT = $qrListaProduto['DES_PRODUTO'];
					$tooltipNOM_PRODTKT = "style='opacity:0.4;' data-toggle='tooltip' data-placement='top' data-original-title='não possui ticket'";
				}else{ 
					$mostraNOM_PRODTKT = $qrListaProduto['NOM_PRODTKT']; 
					$tooltipNOM_PRODTKT = "data-toggle='tooltip' data-placement='top' data-original-title='com ticket'";
				}	

				if ($qrListaProduto['GRUPO'] == "") {
					$mostraGRUPO = "";	
				}else{ $mostraGRUPO = $qrListaProduto['GRUPO']." \ "; }	

				if ($qrListaProduto['SUBGRUPO'] == "") {
					$mostraSUBGRUPO = "";	
				}else{ $mostraSUBGRUPO = $qrListaProduto['SUBGRUPO']; }	

				if ($qrListaProduto['QTD_ESTOQUE'] == "0") {
					$tooltipQTD_ESTOQUE = "style='opacity:0.4;' data-toggle='tooltip' data-placement='top' data-original-title='sem estoque'";
				}else{ 
					$tooltipQTD_ESTOQUE = "data-toggle='tooltip' data-placement='top' data-original-title='em estoque'";
				}
			
				if ($qrListaProduto['COD_PERSONA'] == "0") {
					$tooltipCOD_PERSONA = "style='opacity:0.4;' data-toggle='tooltip' data-placement='top' data-original-title='sem persona'";
				}else{ 
					$tooltipCOD_PERSONA = "data-toggle='tooltip' data-placement='top' data-original-title='com persona'";
				}	
														
				if ($qrListaProduto['VAL_PROMTKT'] == "0.00") {
					$tooltipVAL_PROMTKT = "style='opacity:0.4;' data-toggle='tooltip' data-placement='top' data-original-title='sem preço promocional'";
				}else{ 
					$tooltipVAL_PROMTKT = "data-toggle='tooltip' data-placement='top' data-original-title='com preço promocional'";
				}
																								
				if ($qrListaProduto['CAMPANHA'] == "0" && $qrListaProduto['FAIXAS'] == "0" ) {
					$tooltipCAMPANHA = "style='opacity:0.4;' data-toggle='tooltip' data-placement='top' data-original-title='não possui campanha'";
				}else{ 
					$tooltipCAMPANHA = "data-toggle='tooltip' data-placement='top' data-original-title='com campanha'";
				}
											
				?>
					  <tr>
						  <td class="text-center p-imagem">
								<?php echo $mostraDES_IMAGEM; ?>                    
						  </td>
						  <td class="p-name">
							  <small><?php echo $qrListaProduto['DES_PRODUTO']; ?> <br/>
									<span class="f12"><i class="fa fa-ticket"></i> <?php echo $mostraNOM_PRODTKT; ?></span></small><br/>
									<span class="f12"><?php echo $mostraGRUPO; ?> <?php echo $mostraSUBGRUPO; ?></span></small>
							  <div class="push10"></div>
							  <a style="cursor: pointer" class="mostrarProduto_<?php echo $qrListaProduto['COD_PRODUTO']?>" onClick="mostrarFilho(<?php echo $qrListaProduto['COD_PRODUTO']?>)"><i class="fa fa-angle-right"></i></a>
						  </td>
						  
						  <td style="min-width: 280px">
							  <div class="row">
								  <div class="socials tex-center col-lg-12"> 
									  <a class="btn btn-circle btn-primary" <?php echo $tooltipNOM_PRODTKT; ?> > <i class="fa fa-ticket"></i></a> 
									  <a class="btn btn-circle-long btn-success" <?php echo $tooltipQTD_ESTOQUE; ?> > <?php echo fnValor($qrListaProduto['QTD_ESTOQUE'],0); ?> </a> 
									  <a class="btn btn-circle btn-info" <?php echo $tooltipCOD_PERSONA; ?>  ><i class="fa fa-male"></i></a> 
									  <a class="btn btn-circle btn-warning" <?php echo $tooltipVAL_PROMTKT; ?> ><i class="fa fa-usd"></i></a>
									  <a class="btn btn-circle btn-default" <?php echo $tooltipCAMPANHA; ?>  ><i class="fa fa-cart-arrow-down"></i></a> 
									  <a class="btn btn-circle btn-danger" <?php echo $tooltipCAMPANHA; ?> ><i class="fa fa-ban"></i></a> 
								  </div>															  
							  </div>
							  <div class="push10"></div>
							  <div class="row">
								  <div class="col-lg-12"> 
									<div class="row" style="font-size: 11px">
										<div class="col-lg-3"><i class="fa fa-step-forward"></i>&nbsp; 0,00</div>
										<div class="col-lg-7"><i class="fa fa-ticket"></i>&nbsp; <b>De:</b> R$ <?php echo $qrListaProduto['VAL_PRODTKT']?>
										&nbsp; <b>Por:</b> R$ <?php echo $qrListaProduto['VAL_PROMTKT']?></div>
									</div>
									
								  </div>															  
							  </div>														  
						  </td>
																			  
						  <td style="min-width: 90px">													  
							<div class="btn-group">
							  <a href="#" class="btn btn-default input-xs">Ação</a>
							  <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
							  <ul class="dropdown-menu">
								<li><a href="#" data-url="action.php?mod=<?php echo fnEncode(1194)?>&id=<?php echo fnEncode($cod_empresa)?>&idPrd=<?php echo fnEncode($qrListaProduto['COD_PRODUTO'])?>&pop=true" data-title="Produto" class="addBox">Editar Produto</a></li>
								<li class="disabled"><a href="#">Editar Produto na Campanha</a></li>
								<li class="disabled"><a href="#">Editar Produto Específico</a></li>
								<li class="disabled"><a href="#">Editar Produto no Ticket</a></li>
							  </ul>
							</div>														
						  </td>	

					  </tr>	
					  <tr style="border-bottom: 1px dashed #e5e7e9; display: none" id="conteudoTable_<?php echo $qrListaProduto['COD_PRODUTO']?>">
						  <td colspan='4' style="padding-top: 0; padding-bottom: 5px;">
							  <div id="conteudoProduto_<?php echo $qrListaProduto['COD_PRODUTO']?>"></div>
						  </td>
					  </tr>
				<?php
				
			}
		?>
			
		</tbody>
														
		</table>
		
		</form>

	</div>
	
	<div class="push30"></div>
	
	<table class="table">

	<tfoot>
		<tr>
		  <th colspan="100" style="text-align: justify;"><ul class="pagination pagination-sm">
		  <?php 
			for($i = 1; $i < $numPaginas + 1; $i++) {
				if ($pagina == $i){$paginaAtiva = "active";}else{$paginaAtiva = "";}	
			echo "<li class='pagination $paginaAtiva'><a onClick='atualizaPaginacao(".$i.");' style='text-decoration: none;'>".$i."</a></li>";     
			}													  
		  ?></ul>
		  </th>
		</tr>
	</tfoot>
													
	</table>
	<div class="totalResultadosAjx" style="display: none;"><?php echo fnValor($total['contador'],0);?></div>
</div>	
