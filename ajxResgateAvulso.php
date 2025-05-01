<?php include "_system/_functionsMain.php"; 
$buscaAjx1 = fnLimpacampo($_GET['ajx1']); //unidade
$buscaAjx2 = fnLimpacampo($_GET['ajx2']); //empresa
$buscaAjx3 = fnLimpacampo($_GET['ajx3']); //valor
//$buscaAjx3 = 1713; //valor
//fnEscreve($buscaAjx2);
?>
	<option value=""></option>

	<?php 
	
		/*
		$sqlVerifica="SELECT COUNT(*) AS CONTROLA_ESTOQUE FROM ESTOQUE_PRODUTO
						WHERE COD_EMPRESA = $buscaAjx2 AND 
						COD_UNIVEND = $buscaAjx1 ";
		*/
		
		$sqlVerifica="SELECT COUNT(*) AS CONTROLA_ESTOQUE FROM ESTOQUE_PRODUTO
						WHERE COD_EMPRESA = $buscaAjx2 AND 
						IFNULL(cod_exclusa,0)=0  ";
						
		//fnEscreve($sqlVerifica);
		
		$arrayQuery2 = mysqli_query(connTemp($buscaAjx2,""),$sqlVerifica);
		$qrVerifica = mysqli_fetch_assoc($arrayQuery2);
		
		$controla_estoque = $qrVerifica['CONTROLA_ESTOQUE'];
		
		$txtControle = "selecione uma unidade";				
		if ( $buscaAjx1 > 0){
			
			if ( $controla_estoque > 0){
				//fnEscreve("controla estoque");				
				$txtControle = "controle de estoque";				
				$sqlEstoque="SELECT A.COD_PRODUTO, A.DES_PRODUTO, A.NUM_PONTOS from PRODUTOPROMOCAO A, ESTOQUE_PRODUTO B
								WHERE A.COD_EMPRESA = $buscaAjx2
								AND A.COD_EMPRESA=B.COD_EMPRESA
								AND A.COD_PRODUTO=B.COD_PRODUTO
								AND B.QTD_ESTOQUE>0
								AND A.COD_EXCLUSA = 0 
								AND A.LOG_ATIVO = 'S'
								AND A.NUM_PONTOS <= $buscaAjx3
								AND B.COD_UNIVEND = $buscaAjx1
								order BY A.NUM_PONTOS ";			

			}else{
				//fnEscreve("NÃO controla estoque");				
				$txtControle = "sem controle de estoque";				
				$sqlEstoque="SELECT COD_PRODUTO, DES_PRODUTO, NUM_PONTOS from PRODUTOPROMOCAO
					where COD_EMPRESA = $buscaAjx2
					AND COD_EXCLUSA = 0 
					AND LOG_ATIVO = 'S'
					AND NUM_PONTOS <= $buscaAjx3
					order by NUM_PONTOS ";			
				
			}
			
			//fnEscreve($sqlEstoque);		
			$arrayQuery = mysqli_query(connTemp($buscaAjx2,""),$sqlEstoque) or die(mysqli_error());

			while ($qrListaProdutoPromo = mysqli_fetch_assoc($arrayQuery)) {

				if($qrListaProdutoPromo['NUM_PONTOS'] == 0){
					$disabled = "disabled";
				}else{
					$disabled = "";
				}				
				echo"<option ".$disabled." num-pontos='".$qrListaProdutoPromo['NUM_PONTOS']."' cod-option='".$qrListaProdutoPromo['COD_PRODUTO']."' value='".$qrListaProdutoPromo['COD_PRODUTO']."'>".$qrListaProdutoPromo['DES_PRODUTO']."&nbsp; (".$qrListaProdutoPromo['NUM_PONTOS']." pontos) </option>";
			}
		
		}
	
	?>	
