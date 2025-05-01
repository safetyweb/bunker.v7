<?php 

	include '_system/_functionsMain.php'; 	

	//echo fnDebug('true');	

	$opcao = fnLimpaCampo($_GET['opcao']);

	switch ($opcao) {

		case 'paginar':

			$itens_por_pagina = $_GET['itens_por_pagina'];	
			$pagina = $_GET['idPage'];
			$pagina_parent = $_GET['idp'];
			$cod_empresa = fnDecode($_GET['id']);
			$cod_cliente  = fnLimpacampo($_REQUEST['COD_CLIENTE']);
			$nom_cliente  = fnLimpacampo($_REQUEST['NOM_CLIENTE']);	
			$num_cartao = fnLimpacampo($_REQUEST['NUM_CARTAO']);	
			$num_cgcecpf = fnLimpacampo($_REQUEST['NUM_CGCECPF']);
			$des_emailus = fnLimpacampo($_REQUEST['DES_EMAILUS']);

			if ($cod_cliente!=0){
				$andCodigo = 'and cod_cliente='.$cod_cliente; }
				else { $andCodigo = ' ';}

			if ($num_cartao!=0){
				$andNumCartao = 'and num_cartao='.$num_cartao; }
				else { $andNumCartao = ' ';}

			if ($num_cgcecpf!=0){
				$andcpf = 'and num_cgcecpf='.$num_cgcecpf; }
				else { $andcpf = ' ';}
															  
			if ($nom_cliente!=''){ 
				 $andNome = 'and nom_cliente like "%'.$nom_cliente.'%"';	} 
				else {$andNome = ' '; } 

			$sql = "SELECT COD_CLIENTE
					FROM  CLIENTES 
					WHERE COD_EMPRESA = $cod_empresa
					AND DES_EMAILUS = '$des_emailus'
                    $andCodigo
                    $andNome
                    $andNumCartao
                    $andcpf
                    ORDER by NOM_CLIENTE ";
			//fnEscreve($sql);
			
			$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
			$total_itens_por_pagina = mysqli_num_rows($retorno);
			
			$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);
			// fnescreve($total_itens_por_pagina);	
			
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;	
                                                            

			$sql = "SELECT COD_CLIENTE, NOM_CLIENTE, DES_EMAILUS, NUM_CGCECPF 
					FROM CLIENTES 
					WHERE COD_EMPRESA = $cod_empresa
					AND DES_EMAILUS = '$des_emailus'
                    $andCodigo
                    $andNome
                    $andNumCartao
                    $andcpf
                    ORDER BY NOM_CLIENTE 
                    LIMIT $inicio,$itens_por_pagina
            ";

            // fnEscreve($sql);

			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
																
			$count=0;
			while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery))
			  {														  
				$count++;
				?>								  
				
					<tr>
					  <td><?=$qrListaEmpresas['COD_CLIENTE']?></td>
					  <td>
					  	<a href='action.php?mod=<?php echo fnEncode(1024)?>&id=<?php echo fnEncode($cod_empresa)?>&idC=<?php echo fnEncode($qrListaEmpresas['COD_CLIENTE'])?>&pop=true'>
					  		<?=$qrListaEmpresas['NOM_CLIENTE']?>
					  	</a>
					  </td>
					  <td>
					  	<a href="#" class="editable" 
						  	data-type='text' 
						  	data-title='Editar email' 
						  	data-pk="<?php echo $qrListaEmpresas[COD_CLIENTE]; ?>" 
						  	data-name="DES_EMAILUS"  
						  	data-codempresa="<?=$cod_empresa?>" >

						  	<?=$qrListaEmpresas['DES_EMAILUS']?>
					  		
					  	</a>
					  </td>
					  <td><?=$qrListaEmpresas['NUM_CGCECPF']?></td>
					</tr>
					<input type='hidden' id='ret_ENCODE_<?=$count?>' value='<?=fnEncode($qrListaEmpresas['COD_CLIENTE'])?>'>
					<input type='hidden' id='ret_COD_CLIENTE_<?=$count?>' value='<?=$qrListaEmpresas['COD_CLIENTE']?>'>
					<input type='hidden' id='ret_NOM_CLIENTE_<?=$count?>' value='<?=$qrListaEmpresas['NOM_CLIENTE']?>'>
					<input type='hidden' id='ret_COD_EMPRESA_<?=$count?>' value='<?=$cod_empresa?>'>
					<?php
				}

				?>
				<script>
					$(function(){
					    $('.editable').editable({ 
					    	emptytext: '_______________',
					        url: 'ajxEditaClienteBlacklist.php',
			        		ajaxOptions:{type:'post'},
			        		params: function(params) {
						        params.codempresa = $(this).data('codempresa');
						        return params;
						    },
			        		success:function(data){
			        			parent.reloadPage("<?=$pagina_parent?>");
								location.reload();
							}
					    });
					});
				</script>
				<?php 

		break;

		case 'exc':

			$cod_empresa = fnDecode($_GET['id']);
			$des_emailus = fnLimpacampo(fnDecode($_REQUEST['DES_EMAILUS']));

			$sql = "UPDATE CLIENTES SET DES_EMAILUS = '' 
					WHERE DES_EMAILUS = '$des_emailus' 
					AND COD_EMPRESA = $cod_empresa";

			fnEscreve($sql);
			fnTestesql(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

		break;
		
		// EDITABLE
		default:
			
			$cod_cliente = fnLimpaCampoZero($_POST['pk']);
			$cod_empresa = fnLimpaCampoZero($_POST['codempresa']);
			$campo = fnLimpaCampo($_POST['name']);
			$valor = trim(fnLimpaCampo($_POST['value']));
			// fnEscreve($cod_empresa);
			// fnEscreve($campo);
			// fnEscreve($valor);


			$sql = "UPDATE CLIENTES SET $campo='$valor' WHERE COD_CLIENTE = $cod_cliente";
			fnEscreve($sql);
			fnTestesql(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

		break;

	}

						
?>