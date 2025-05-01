<?php 

include "_system/_functionsMain.php";

if(isset($_GET['acao'])){
	$acao = fnLimpaCampo($_GET['acao']);
}else{
	$acao='';
}

switch($acao){

	case 'paginar':

		$cod_empresa = $_POST['COD_EMPRESA'];
		$cod_univend = $_POST['COD_UNIVEND'];


		if($filtro != ""){
			$andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
		}else{
			$andFiltro = " ";
		}

		$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;

		//variáveis da pesquisa
		$cod_externo = fnLimpacampo(@$_REQUEST['COD_EXTERNO']);
		$pesquisa = fnLimpacampo(@$_REQUEST['pesquisa']);
		$des_produto = fnLimpacampo(@$_REQUEST['DES_PRODUTO']);
		
		//pesquisa no form local
		$andExternoTkt = ' ';
		if (empty($_REQUEST['pesquisa'])){
			//fnEscreve("sem pesquisa");
			$andProduto = ' ';
			$andExterno = ' ';
		}else{
			//fnEscreve("com pesquisa");
			if ($des_produto != "" ){
				$andProduto = 'AND A.DES_PRODUTO like "%'.$des_produto.'%"'; }
				else { $andProduto = ' ';}
				
			if ($cod_externo  != "" ){
				$andExterno = 'AND A.COD_EXTERNO = "'.$cod_externo.'"'; }
				else { $andExterno = ' ';}
			
		}
		
		//se pesquisa dos produtos do ticket
		if (!empty($_GET['idP'])) {$andExterno = 'AND A.COD_EXTERNO = "'.$_GET['idP'].'"';}
		
		//fnEscreve("entrou");
		
		$sql="SELECT COUNT(*) AS CONTADOR from PRODUTOPROMOCAO
				where COD_EMPRESA=$cod_empresa 
				$andProduto
				$andExterno 
				AND COD_EXCLUSA=0 
				-- AND log_markapontos = 0 
				$andFiltro order by DES_PRODUTO";
	                                                      
		$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
		$total_itens_por_pagina = mysqli_fetch_assoc($retorno);
		
		$numPaginas = ceil($total_itens_por_pagina['CONTADOR']/$itens_por_pagina);	
		
		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		$sql1="SELECT PP.*,
				(SELECT EP.QTD_ESTOQUE FROM ESTOQUE_PRODUTO EP WHERE EP.COD_UNIVEND = $cod_univend AND EP.COD_EMPRESA = $cod_empresa AND EP.COD_PRODUTO = PP.COD_PRODUTO) AS QTD_ESTOQUE
				FROM PRODUTOPROMOCAO PP
				where PP.COD_EMPRESA=$cod_empresa 
				$andProduto
				$andExterno 
				AND PP.COD_EXCLUSA=0 
				-- AND PP.log_markapontos = 0 
				$andFiltro order by PP.DES_PRODUTO limit 0,50";

		$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql1) or die(mysqli_error());

		// fnEscreve($cod_univend);
		//fnEscreve($sql1);
		
		$count=0;
		while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery))
		{														  
			$count++;

			if ($qrListaProduto['DES_IMAGEM'] != "") {
				$mostraDES_IMAGEM = '<i class="fa fa-check-square-o" aria-hidden="true"></i>';	
			}else{ $mostraDES_IMAGEM = ''; }

			?>	
			
				<tr>
				  <td><input type='radio' name='radio1' onclick='retornaForm(<?=$count?>)'></th>
				  <td><?=$qrListaProduto['COD_PRODUTO']?></td>
				  <td><?=$qrListaProduto['COD_EXTERNO']?></td>
				  <td><?=$qrListaProduto['DES_PRODUTO']?></td>
				  <td><?=$qrListaProduto['NUM_PONTOS']?></td>

				  <td class='text-center'>
				  	<a href="javascript:void(0);" class="editable-estoque" 
					  	data-type='text' 
					  	data-title='Editar Estoque' data-pk="<?php echo $qrListaProduto['COD_PRODUTO']; ?>" 
					  	data-name="QTD_ESTOQUE"
					  	data-count="<?=$count?>"><?=fnValor($qrListaProduto['QTD_ESTOQUE'],0)?>
				  		
				  	</a>
				  </td>

				</tr>

				<input type='hidden' id='ret_COD_PRODUTO_<?=$count?>' value='<?=$qrListaProduto['COD_PRODUTO']?>'>  
				<input type='hidden' id='ret_COD_EXTERNO_<?=$count?>' value='<?=$qrListaProduto['COD_EXTERNO']?>'>
				<input type='hidden' id='ret_DES_PRODUTO_<?=$count?>' value='<?=$qrListaProduto['DES_PRODUTO']?>'>
				<input type='hidden' id='ret_COD_EAN_<?=$count?>' value='<?=$qrListaProduto['EAN']?>'>
				<input type='hidden' id='ret_DES_DISPONIBILIDADE_<?=$count?>' value='<?=$qrListaProduto['DES_DISPONIBILIDADE']?>'>
				<input type='hidden' id='ret_DES_TIPOENTREGA_<?=$count?>' value='<?=$qrListaProduto['DES_TIPOENTREGA']?>'>
				<input type='hidden' id='ret_NUM_PONTOS_<?=$count?>' value='<?=$qrListaProduto['NUM_PONTOS']?>'>
				<input type='hidden' id='ret_DES_IMAGEM_<?=$count?>' value='<?=$qrListaProduto['DES_IMAGEM']?>'>
				<input type='hidden' id='ret_LOG_ATIVO_<?=$count?>' value='<?=$qrListaProduto['LOG_ATIVO']?>'>

			<?php 
		}
		?>
			<script>											
				$(function(){
				    $('.editable-estoque').editable({ 
				    	emptytext: '0',
				        url: 'ajxCalculaEstoque.php',
		        		ajaxOptions:{type:'post'},
		        		params: function(params) {
					        params.count = $(this).data('count');
					        params.COD_EMPRESA = <?=$cod_empresa?>;
					        params.COD_UNIVEND = $('#COD_UNIVEND').val();
					        return params;
					    },
		        		success:function(data){
							console.log(data);
						}
				    });
				});
				
			</script>
		<?php 
	break;

	default:

		$cod_produto = $_POST['pk'];
		$qtd_estoque = $_POST['value'];
		$cod_empresa = $_POST['COD_EMPRESA'];
		$cod_univend = $_POST['COD_UNIVEND'];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];


		// fnEscreve($cod_produto);
		// fnEscreve($qtd_estoque);
		// fnEscreve($cod_empresa);
		//fnEscreve($cod_univend);

		$sql = "SELECT COUNT(COD_ESTOQUE) AS LINHAS, COD_ESTOQUE FROM ESTOQUE_PRODUTO WHERE COD_PRODUTO = $cod_produto AND COD_EMPRESA = $cod_empresa AND COD_UNIVEND = $cod_univend";
		$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql);
		$qrEst = mysqli_fetch_assoc($arrayQuery);
		$linhas = $qrEst['LINHAS'];

		if($linhas == 1){
			
			$sql1 = "UPDATE ESTOQUE_PRODUTO SET
							COD_UNIVEND='$cod_univend',
							QTD_ESTOQUE=$qtd_estoque,
							DAT_ALTERAC=NOW(),
							COD_ALTERAC=$cod_usucada
					WHERE COD_ESTOQUE =".$qrEst['COD_ESTOQUE'];
		}else{
			$sql1 = "INSERT INTO ESTOQUE_PRODUTO(
								COD_EMPRESA,
								COD_UNIVEND,
								COD_PRODUTO,
								QTD_ESTOQUE,
								COD_USUCADA
								) VALUES(
								$cod_empresa,
								'$cod_univend',
								$cod_produto,
								$qtd_estoque,
								$cod_usucada
								)";
		}
		//fnEscreve($linhas);
		//fnEscreve($sql1);
		//fnEscreve($sql1);
		mysqli_query(connTemp($cod_empresa,""),$sql1);

	break;

}




?>