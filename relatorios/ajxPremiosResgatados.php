<?php 

	include '../_system/_functionsMain.php'; 
	//echo fnDebug('true');
	$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);			
	
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	$lojasSelecionadas = $_POST['LOJAS'];
	$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_CGCECPF']));
	$cod_categor = $_REQUEST['COD_CATEGOR'];
	$cod_subcate = $_REQUEST['COD_SUBCATE'];
	if (empty($_REQUEST['LOG_AGRUPA'])) {$log_agrupa='N'; $checked = "";}else{$log_agrupa=$_REQUEST['LOG_AGRUPA']; $checked = "checked";}
	
	
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

	if($num_cgcecpf != ''){
		$andCpf = "AND B.NUM_CGCECPF = '$num_cgcecpf' ";
	}else{
		$andCpf = "";
	}

	if($cod_categor != '' && $cod_categor != 0){
		$andCat = "AND C.COD_CATEGOR = $cod_categor ";
	}else{
		$andCat = "";
	}	

	if($cod_subcate != '' && $cod_subcate != 0){
		$andSub = "AND C.COD_SUBCATE = $cod_subcate ";
	}else{
		$andSub = "";
	}

	if($log_agrupa == 'S'){
		$orderBy = "ORDER  BY A.COD_UNIVEND, A.DAT_REPROCE DESC";
	}else{
		$orderBy = "ORDER  BY A.DAT_REPROCE DESC";
	}
	
	switch ($opcao) {
		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivoCaminho = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';


			// Filtro por Grupo de Lojas
			include "filtroGrupoLojas.php";

			$sql = "SELECT	B.NOM_CLIENTE,
							B.COD_CLIENTE,
							A.COD_CREDITO, 
							A.COD_UNIVEND,
							B.NUM_CARTAO,
							UNI.NOM_FANTASI,
							US.NOM_USUARIO, 
							A.DAT_REPROCE, 
							A.COD_PRODUTO, 
							A.COD_STATUSCRED, 
							A.COD_USUCADA, 
							C.DES_PRODUTO, 
							A.QTD_PRODUTO, 
							A.VAL_UNITARIO, 
							A.VAL_TOTPROD,
							c.VAL_PRODUTO AS CUSTO_TOTAL,
							VENINFO.DES_COMENTA 
					FROM   CREDITOSDEBITOS A 
							INNER JOIN CLIENTES B ON A.COD_CLIENTE = B.COD_CLIENTE 
							INNER JOIN PRODUTOPROMOCAO C ON A.COD_PRODUTO = C.COD_PRODUTO
							LEFT JOIN UNIDADEVENDA UNI ON UNI.COD_UNIVEND = A.COD_UNIVEND 
							LEFT JOIN USUARIOS US ON US.COD_USUARIO = A.COD_VENDEDOR
							LEFT JOIN venda_info VENINFO ON VENINFO.COD_VENDA=A.COD_CREDITO AND VENINFO.DES_TIPO=3
					WHERE  A.COD_EMPRESA = $cod_empresa 
							AND A.TIP_CREDITO = 'D' 
							AND A.COD_PRODUTO > 0 
							AND A.COD_UNIVEND IN ( $lojasSelecionadas ) 
							AND A.DAT_REPROCE BETWEEN   '" . fnDataSql($dat_ini) . " 00:00:00' AND '" . fnDataSql($dat_fim) . " 23:59:59'
							$andCpf
							$andCat
							$andSub
							$orderBy
					   ";
					
			//fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

			$arquivo = fopen($arquivoCaminho, 'w', 0);

			while ($headers = mysqli_fetch_field($arrayQuery)) {
				$CABECHALHO[] = $headers->name;
			}
			fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

			while ($row = mysqli_fetch_assoc($arrayQuery)) {
				$row[VAL_UNITARIO] = fnValor($row[VAL_UNITARIO], 2);
                $row[VAL_TOTPROD] = fnValor($row[VAL_TOTPROD], 2);
                $row[QTD_PRODUTO] = fnValor($row[QTD_PRODUTO], 2);
                $row[CUSTO_TOTAL] = fnValor(($row[QTD_PRODUTO]*$row[CUSTO_TOTAL]), 2);
				//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
				//$textolimpo = json_decode($limpandostring, true);
				$array = array_map("utf8_decode", $row);
				fputcsv($arquivo, $array, ';', '"', '\n');

				//echo "<pre>";
				//print_r($row);
				//echo "</pre>";
			}
			fclose($arquivo);
			/*
			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){

				

				$newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {
					  
					// Colunas que são double converte com fnValor
					if($cont == 5 || $cont == 6 || $cont == 7){
						array_push($newRow, fnValor($objeto, 0));
					// Muda cod_univend para nome da unidade
					}else if($cont == 1){
						$NOM_ARRAY_UNIDADE=(array_search($objeto, array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
						array_push($newRow, $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']);
					// Muda cod_usucada para nome do usuario
					}else if($cont == 8){
						$NOM_ARRAY_NON_VENDEDOR=(array_search($objeto, array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));
						array_push($newRow, $ARRAY_VENDEDOR[$NOM_ARRAY_NON_VENDEDOR]['NOM_USUARIO']);
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
			*/
			break;      
		case 'paginar':			
				
				// Filtro por Grupo de Lojas
				include "filtroGrupoLojas.php";

               $sql="SELECT COUNT(*) as CONTADOR from CREDITOSDEBITOS A 
	                    where
	                               A.COD_EMPRESA='$cod_empresa' AND
	                               A.TIP_CREDITO='D' AND
	                               A.COD_PRODUTO > 0 AND
	                               A.COD_UNIVEND in ($lojasSelecionadas) and
	                               A.DAT_REPROCE BETWEEN   '".fnDataSql($dat_ini)." 00:00:00' AND '".fnDataSql($dat_fim)." 23:59:59'
	                               $andCpf
							       $andCat
							       $andSub";

				//fnEscreve($sql);
                                                              
				$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
				$total_itens_por_pagina = mysqli_fetch_assoc($retorno);
				
				$numPaginas = ceil($total_itens_por_pagina['CONTADOR']/$itens_por_pagina);	
				
				//variavel para calcular o início da visualização com base na página atual
				$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
                                                                                
                $sql="SELECT B.NOM_CLIENTE,
							B.COD_CLIENTE,
							A.COD_CREDITO, 
							A.COD_UNIVEND,
							UNI.NOM_FANTASI,
							US.NOM_USUARIO, 
							A.DAT_REPROCE, 
							A.COD_PRODUTO, 
							A.COD_STATUSCRED, 
							A.COD_USUCADA, 
							C.DES_PRODUTO, 
							C.VAL_PRODUTO, 
							A.QTD_PRODUTO, 
							A.VAL_UNITARIO, 
							A.VAL_TOTPROD,
							VENINFO.DES_COMENTA 
					FROM   CREDITOSDEBITOS A 
							INNER JOIN CLIENTES B ON A.COD_CLIENTE = B.COD_CLIENTE 
							INNER JOIN PRODUTOPROMOCAO C ON A.COD_PRODUTO = C.COD_PRODUTO
							LEFT JOIN UNIDADEVENDA UNI ON UNI.COD_UNIVEND = A.COD_UNIVEND 
							LEFT JOIN USUARIOS US ON US.COD_USUARIO = A.COD_VENDEDOR
							LEFT JOIN venda_info VENINFO ON VENINFO.COD_VENDA=A.COD_CREDITO AND VENINFO.DES_TIPO=3
					WHERE  A.COD_EMPRESA = $cod_empresa 
							AND A.TIP_CREDITO = 'D' 
							AND A.COD_PRODUTO > 0 
							AND A.COD_UNIVEND IN ( $lojasSelecionadas ) 
							AND A.DAT_REPROCE BETWEEN   '" . fnDataSql($dat_ini) . " 00:00:00' AND '" . fnDataSql($dat_fim) . " 23:59:59'
							$andCpf
							$andCat
							$andSub
							$orderBy  
					LIMIT  $inicio, $itens_por_pagina";	   
				
				//fnEscreve($sql);
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
				
				$countLinha = 1;
				while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery))
				  {

				  	$custo = $qrListaVendas['QTD_PRODUTO'] * $qrListaVendas['VAL_PRODUTO'];

					?>	
						<tr id="<?=$qrListaVendas['COD_CREDITO']?>">
						  <td><small><?php echo $qrListaVendas['NOM_CLIENTE']; ?></small></td>
						  <td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
						  <td><small><?php echo $qrListaVendas['DES_PRODUTO']; ?></small></td>
						  <td class="text-center"><small><?php echo $qrListaVendas['COD_PRODUTO']; ?></small></td>
						  <td class="text-center"><small><?php echo fnDataFull($qrListaVendas['DAT_REPROCE']); ?></small></td>
						  <td class="text-center"><small><?php echo fnValor($qrListaVendas['QTD_PRODUTO'],0); ?></small></td>
						  <td class="text-center"><small><?php echo fnValor($qrListaVendas['VAL_UNITARIO'],0); ?></small></td>
						  <td class="text-center"><small> <?php echo fnValor($qrListaVendas['VAL_TOTPROD'],0); ?></small></td>
						  <td class="text-center"><small> <?php echo fnValor($custo,0); ?></small></td>
						  <td class="text-center"><small><?=$qrListaVendas['NOM_USUARIO'];?></small></td>
						  <td class="text-center"><small><?php echo $qrListaVendas['DES_COMENTA']; ?></small></td>

						  <?php 
						  	if($qrListaVendas['COD_STATUSCRED'] == 6){ 
						  ?>

						  		<td class="text-center"><span class="fas fa-check" style="color: #18BC9C;"></td>
						  	</tr>

						  <?php 	
							}else{ 
						  ?>

						  		<td class="text-center"><a href="javascript:void(0)" class="btn btn-danger btn-xs" onclick="estornaResgate(<?=$qrListaVendas['COD_CREDITO']?>,<?=$cod_usucada?>,<?=$cod_empresa?>)"><span class="fas fa-trash"></a></td>
						  	</tr>							
						  <?php 
							}  

				  $countLinha++;	
				  }	

				  ?>
				  <script>
					function estornaResgate(cod_credito, cod_usucada){
						$.confirm({
							title: 'Atenção!',
							animation: 'opacity',
			                closeAnimation: 'opacity',
							content: 'Deseja realmente efetuar o estorno?',
							buttons: {

								confirmar: function () {
									$.ajax({
										type: "POST",
										url: "relatorios/ajxEstornaResgate.php",
										data: {COD_CREDITO:cod_credito, COD_USUCADA:cod_usucada, COD_EMPRESA:<?=$cod_empresa?>},
										// beforeSend:function(){
										// 	$('#'+cod_credito).html('<div class="loading" style="width: 100%;"></div>');
										// },
										success:function(data){
											// $("#"+cod_credito).html(data); 
											$("#"+cod_credito).css('background','#FCF3CF'); 
											//console.log(data); 
										},
										error:function(data){
											$('#'+cod_credito).html(data);
											console.log(data); 
										}
									});

								},
								cancelar: function () {
									
									
								},
							}
						});
					}
				</script>
				  <?php 							

			break; 		
	}
?>

