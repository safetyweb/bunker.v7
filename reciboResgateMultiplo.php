<?php
	
	//echo fnDebug('true');


	$Arr_COD_RECIBO = array_filter(json_decode($_REQUEST['idR']));

	if (isset($Arr_COD_RECIBO)){
			//array das unidades de venda
			$countRecibo = 0;
			if (isset($Arr_COD_RECIBO)){
			   for ($i=0;$i<count($Arr_COD_RECIBO);$i++) 
			   { 
				$str_recibo.=$Arr_COD_RECIBO[$i].',';
				$countRecibo ++; 
			   } 
			   $str_recibo = substr($str_recibo,0,-1);
			}		
	        $cod_recibo = $str_recibo;
		}else{
		$cod_recibo = "9999";
	}

	//fnEscreve($cod_recibo);	
	
	$cod_empresa = fnDecode(fnLimpacampo($_REQUEST['id']));
	$cod_cliente = fnDecode(fnLimpacampo($_REQUEST['idC']));
	//fnEscreve($cod_empresa);
	//fnEscreve($cod_recibo);
	//fnEscreve($cod_cliente);
	
	if ($cod_cliente == "0"){
		//busca dados do resgate - prêmio
		$sql = "SELECT B.NUM_CARTAO, B.COD_CLIENTE, B.NOM_CLIENTE, C.DES_PRODUTO, A.QTD_PRODUTO, A.VAL_UNITARIO, A.VAL_TOTPROD, A.DAT_CADASTR, U.NOM_USUARIO 
				FROM CREDITOSDEBITOS A, CLIENTES B,PRODUTOPROMOCAO C, webtools.usuarios U
				WHERE  
				A.COD_CLIENTE=B.COD_CLIENTE AND
				A.COD_CLIENTE=B.COD_CLIENTE AND
				A.COD_PRODUTO=C.COD_PRODUTO AND
				A.TIP_CREDITO='D' AND
				U.COD_USUARIO=A.COD_USUCADA AND
				A.COD_EMPRESA= $cod_empresa AND
				A.COD_CREDITO IN($cod_recibo)
		";
		$tipoPremio = 1;
		$casasDec = 0;
	}else{
		//busca dados do resgate - créditos
		$sql = "SELECT B.NUM_CARTAO, B.COD_CLIENTE, B.NOM_CLIENTE, 'Resgate manual de créditos' as DES_PRODUTO, A.QTD_PRODUTO, A.VAL_UNITARIO, A.VAL_TOTPROD, A.DAT_CADASTR, U.NOM_USUARIO 
				FROM CREDITOSDEBITOS A, CLIENTES B, webtools.usuarios U
				WHERE  
				A.COD_CLIENTE=B.COD_CLIENTE AND
				A.COD_CLIENTE=B.COD_CLIENTE AND
				A.TIP_CREDITO='D' AND
				U.COD_USUARIO=A.COD_USUCADA AND
				A.COD_EMPRESA= $cod_empresa AND
				A.COD_CREDITO IN($cod_recibo)
		";
		$tipoPremio = 2;
		$casasDec = 2;
	}	
	
	//fnEscreve($sql);
	//dados da query
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

	?>
<div class="push20 hidden-print"></div> 

<ul class="nav nav-tabs hidden-print">
  <li class="active"><a data-toggle="tab" href="#80C">Impressão 80 colunas</a></li>
  <!--<li><a data-toggle="tab" href="#A4">Impressão A4</a></li>-->
</ul>

<div class="tab-content">
	<!-- aba 80 colunas -->
	<div id="80C" class="tab-pane fade in active">

		<div class="push30 hidden-print"></div>
		
			<div style="width: 372px;">
				<h5> <b>RECIBO DE RESGATE AVULSO </b></h5>
							
				<div class="push15"></div>
				<div class="push15"></div>

				<?php

				$i=0;
				$credito_disponivel=0; 

				while($qrBuscaRecibo = mysqli_fetch_assoc($arrayQuery)){

					//fnEscreve('loop');
				
					$cod_cliente = $qrBuscaRecibo['COD_CLIENTE'];
					$nom_cliente = $qrBuscaRecibo['NOM_CLIENTE'];
					$nom_usuario = $qrBuscaRecibo['NOM_USUARIO'];
					$num_cartao = $qrBuscaRecibo['NUM_CARTAO'];
					$des_produto = $qrBuscaRecibo['DES_PRODUTO'];
					$qtd_produto = $qrBuscaRecibo['QTD_PRODUTO'];
					$val_unitario = $qrBuscaRecibo['VAL_UNITARIO'];
					$val_totprod = $qrBuscaRecibo['VAL_TOTPROD'];
					$dat_cadastr = $qrBuscaRecibo['DAT_CADASTR'];

					$sql = "CALL `SP_CONSULTA_SALDO_CLIENTE`('$cod_cliente')";
								
					$arrayQuerySaldo = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
					$qrBuscaTotais = mysqli_fetch_assoc($arrayQuerySaldo);
					
					if (isset($arrayQuerySaldo)){
						
						$credito_disponivel = $qrBuscaTotais['CREDITO_DISPONIVEL'];
					}

					$insert_log="INSERT INTO log_resgate (COD_CLIENTE,
					                         COD_EMPRESA,
					                         NOM_CLIENTE,
					                         NOM_USUARIO,
					                         NUM_CARTAO,
					                         DES_PRODUTO,
					                         QTD_PRODUTO,
					                         VAL_UNITARIO,
					                         VAL_TOTPROD,
					                         DAT_CADASTR,
					                         COD_CREDITO,
					                         CREDITO_DISPONIVEL) VALUES 
					                         ('$cod_cliente', 
					                        '$cod_empresa', 
					                        '$nom_cliente', 
					                        '$nom_usuario', 
					                        '$num_cartao', 
					                        '$des_produto', 
					                        '$qtd_produto', 
					                        '$val_unitario', 
					                        '$val_totprod', 
					                        '$dat_cadastr',
					                        '$cod_recibo',    
					                        '$credito_disponivel')";
					mysqli_query(connTemp($cod_empresa,''), $insert_log);

				if($i == 0){

				?>			
				
						<b><?php echo $nom_cliente; ?> </b>
						<div class="push5"></div>
						
						Cartão: <b><?php echo $num_cartao; ?> </b>
						<div class="push5"></div>
						
						Atendente: <b><?php echo $nom_usuario; ?></b> 
						<div class="push5"></div>
						
						Data: <b><?php echo fnDataFull($dat_cadastr); ?> </b>
						<div class="push5"></div>			

						<div class="push10"></div>

				<?php } ?>
				
				Autorização: <b> <?php echo $Arr_COD_RECIBO[$i]; ?> </b>
				<div class="push5"></div>
				
				Prêmio: <b> <?php echo $des_produto; ?> </b>
				<div class="push5"></div>
				
				<?php 
				//se premio
				if ($tipoPremio == 1) {			
				?>
					Qtd: <b><?php echo fnValor($qtd_produto,0); ?> </b>
					<div class="push5"></div>
					
					Pontos: <b><?php echo fnValor($val_totprod,0); ?> </b>
					<div class="push5"></div>
				<?php 
				} else {
				//se pontos	
				?>
					Créditos: <b><?php echo fnValor($val_totprod,2); ?> </b>
					<div class="push5"></div>
				<?php 
				}
				?>
			</div>
					
			<div class="push10"></div>	

			<?php 
			$i++;
			} 

			?>
			Saldo: <b><?php echo fnValor($credito_disponivel,$casasDec); ?></b> 
			<div class="push5"></div>

		</div>
				
		<!-- aba A4 -->
		<div id="A4" class="tab-pane fade">


	</div>

</div>

<div class="push10"></div>

<div class="form-group text-right col-lg-12 hidden-print">

	<a href="javascript:window.print();" name="PRINT" id="PRINT" class="btn btn-primary" tabindex="5"><i class="fa fa-print" aria-hidden="true"></i>&nbsp; Imprimir Recibo &nbsp;</a>

</div>