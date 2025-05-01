<?php
	
	//echo fnDebug('true');
	
	$cod_empresa = fnDecode(fnLimpacampo($_REQUEST['id']));
	$cod_recibo = fnDecode(fnLimpacampo($_REQUEST['idR']));
	$cod_cliente = fnDecode(fnLimpacampo($_REQUEST['idC']));
	//fnEscreve($cod_empresa);
	//fnEscreve($cod_recibo);
	//fnEscreve($cod_cliente);
	
	if ($cod_cliente == "0"){
		//busca dados do resgate - prêmio
		$sql = "SELECT B.NUM_CARTAO, B.COD_CLIENTE, B.NOM_CLIENTE, B.NUM_CELULAR, B.NUM_CGCECPF, C.DES_PRODUTO, C.COD_PRODUTO, A.QTD_PRODUTO, A.VAL_UNITARIO, A.VAL_TOTPROD, A.DAT_CADASTR, U.NOM_USUARIO 
				FROM CREDITOSDEBITOS A, CLIENTES B, PRODUTOPROMOCAO C, webtools.usuarios U
				WHERE  
				A.COD_CLIENTE=B.COD_CLIENTE AND
				A.COD_CLIENTE=B.COD_CLIENTE AND
				A.COD_PRODUTO=C.COD_PRODUTO AND
				A.TIP_CREDITO='D' AND
				U.COD_USUARIO=A.COD_USUCADA AND
				A.COD_EMPRESA= $cod_empresa AND
				A.COD_CREDITO= $cod_recibo
		";
		$tipoPremio = 1;
		$casasDec = 0;
	}else{
		//busca dados do resgate - créditos
		$sql = "SELECT B.NUM_CARTAO, B.COD_CLIENTE, B.NOM_CLIENTE, B.NUM_CELULAR, B.NUM_CGCECPF, 'Resgate manual de créditos' as DES_PRODUTO, '0' as COD_PRODUTO, A.QTD_PRODUTO, A.VAL_UNITARIO, A.VAL_TOTPROD, A.DAT_CADASTR, U.NOM_USUARIO 
				FROM CREDITOSDEBITOS A, CLIENTES B, webtools.usuarios U
				WHERE  
				A.COD_CLIENTE=B.COD_CLIENTE AND
				A.COD_CLIENTE=B.COD_CLIENTE AND
				A.TIP_CREDITO='D' AND
				U.COD_USUARIO=A.COD_USUCADA AND
				A.COD_EMPRESA= $cod_empresa AND
				A.COD_CREDITO= $cod_recibo
		";	
                
        $sqlempre = "SELECT COD_EMPRESA, NOM_FANTASI, TIP_RETORNO, TIP_CAMPANHA,NUM_DECIMAIS_B FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQueryempre = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlempre));		
	
                if ($tip_retorno == 2){
                        $casasDec = $arrayQueryempre['NUM_DECIMAIS_B'];
                }else { $casasDec = '0'; }
		  
	}	
	
	//fnEscreve($sql);
	//dados da query
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$qrBuscaRecibo = mysqli_fetch_assoc($arrayQuery);
	
	$cod_cliente = $qrBuscaRecibo['COD_CLIENTE'];
	$nom_cliente = $qrBuscaRecibo['NOM_CLIENTE'];
	$num_celular = $qrBuscaRecibo['NUM_CELULAR'];
	$num_cgcecpf = $qrBuscaRecibo['NUM_CGCECPF'];
	$nom_usuario = $qrBuscaRecibo['NOM_USUARIO'];
	$num_cartao = $qrBuscaRecibo['NUM_CARTAO'];
	$cod_produto = $qrBuscaRecibo['COD_PRODUTO'];
	$des_produto = $qrBuscaRecibo['DES_PRODUTO'];
	$qtd_produto = $qrBuscaRecibo['QTD_PRODUTO'];
	$val_unitario = $qrBuscaRecibo['VAL_UNITARIO'];
	$val_totprod = $qrBuscaRecibo['VAL_TOTPROD'];
	$dat_cadastr = $qrBuscaRecibo['DAT_CADASTR'];
	
	//fnEscreve($cod_produto);

	//busca saldo do cliente
	$sql = "SELECT 
            
	   (SELECT Sum(val_saldo) 
	   FROM   creditosdebitos 
	   WHERE  cod_cliente = A.cod_cliente 
		   AND tip_credito = 'C' 
		   AND COD_STATUSCRED = 1 
		   AND ((log_expira='S' and dat_expira > Now())or(log_expira='N'))) AS CREDITO_DISPONIVEL 
		  
		  FROM CREDITOSDEBITOS A
		  WHERE COD_CLIENTE = $cod_cliente
		  AND COD_EMPRESA = $cod_empresa
		  GROUP BY COD_CLIENTE
	";
	
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$qrBuscaSaldo = mysqli_fetch_assoc($arrayQuery);
 	 
	$credito_disponivel = $qrBuscaSaldo['CREDITO_DISPONIVEL'];
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
		
		
		<?php 
		//CAMPANHA CUPOM DA GEF
		//SE CUPOM CAMPANHA 2020 NÃ0 IMPRIME PRIMEIRA PARTE
		if ($cod_empresa == 119 && $cod_produto == 1999 ) {			
		}
		else{
		?>
		
			<div style="width: 372px;">
				<h5> <b>RECIBO DE RESGATE AVULSO </b></h5>
							
				<div class="push15"></div>
				
				<b><?php echo $nom_cliente; ?> </b>
				<div class="push5"></div>
				
				Cartão: <b><?php echo $num_cartao; ?> </b>
				<div class="push5"></div>
				
				Atendente: <b><?php echo $nom_usuario; ?></b> 
				<div class="push5"></div>
				
				Data: <b><?php echo fnDataFull($dat_cadastr); ?> </b>
				<div class="push5"></div>			
				
				Saldo: <b><?php echo fnValor($credito_disponivel,$casasDec); ?></b> 
				<div class="push5"></div>
				
				<div class="push10"></div>
				
				Autorização: <b> <?php echo $cod_recibo; ?> </b>
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
			
			</div>
			
				<?php 
				}
			
			//FIM IF CAMPANHA 2020 GEF	
			}	
			?>
				
			
			
			<?php 
			//CUPOM CAMPANHA 2020
			if ($cod_empresa == 119 && $cod_produto == 1999 ) {			
			?>
			<!--
			<div style="width: 190px; height: 80px; border: 3px solid #000; border-radius: 5px; padding: 10px;">
				
				<h5 class="text-center"> <b>198px x 85px </b></h5>
				
			</div>
			-->
			
			<style>

			.f8 {font-size: 8px; font-weight: bold !important;}			
			.f10 {font-size: 10px; font-weight: bold !important;}
			.push4 {height: 4px; clear:both;} 			
			.push2 {height: 2px; clear:both;} 			
			
			</style>
			
			<div class="push5"></div>
			<div class="push2"></div>
			<div style="width: 265px; height: 100px; border-radius: 5px; padding: 2px 5px; 2px 5px;" class="f8">

				<!--
				<h5 class="text-center"> <b>7cm x 3cm </b></h5>
				-->
				
				<center><img src="images\logo_gef.png" width="15%"> <span style="font-size: 10px;"><b>INVERNO PREMIADO</b></span></center>
				<div class="push4"></div><hr style="margin:0; border-top: 1px dashed #000;"/>
				
				<small class="f8">NOME: </small> <b class="f12"><?php echo substr($nom_cliente, 0, 33); ?> </b>
				<div class="push3"></div>
				
				<small class="f8">CPF: </small> <b class="f10"><?php echo fnCompletaDoc($num_cgcecpf,'F'); ?></b> &nbsp;
				<div class="push3"></div>
				
				<small class="f8">TEL: </small> <b class="f10"><?php echo $num_celular; ?></b> 
				<div class="push5"></div>
				
				<!--
				<small class="f8">RESPONDA:</small class="f8"> <b class="f12">Qual posto que sorteia 3 carros 0km e 3 motos 0km para você?</b> &nbsp;
				<span style="border: 1px solid #000; width: 8px; height: 10px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp; <span class="f10">Posto GEF </span> &nbsp;&nbsp;
				<span style="border: 1px solid #000; width: 8px; height: 10px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp; <span class="f10">Outros </span>
				<div class="push4"></div>
				
				<hr style="margin:0; border-top: 1px dashed #000;"/><center>
				<div class="push3"></div>
				<small class="f8">SECAP/ME Nº:</small> <b class="f10"> 06.010136/2020</b> </center>
				-->
				<hr style="margin:0; border-top: 1px dashed #000;"/><center>
				
			</div>
			
			<?php 
			}
			?>			
				
		<div class="push10"></div>
		<hr class="hidden-print">	
		<div class="form-group text-right col-lg-12 hidden-print">

			<a href="javascript:window.print();" name="PRINT" id="PRINT" class="btn btn-primary" tabindex="5"><i class="fa fa-print" aria-hidden="true"></i>&nbsp; Imprimir Recibo &nbsp;</a>

		</div>		
		
		
	</div>
	
	<!-- aba A4 -->
	<div id="A4" class="tab-pane fade">
	
	
	</div>
	
</div>