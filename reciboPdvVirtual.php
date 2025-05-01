<?php
	
	//echo fnDebug('true');
	
	$cod_empresa = fnDecode(fnLimpacampo($_REQUEST['id']));
	$cod_orcamento = fnDecode(fnLimpacampo($_REQUEST['idpdv']));
	// fnEscreve($cod_empresa);
	// fnEscreve($cod_orcamento);
	//fnEscreve($cod_cliente);


	$sqlempre = "SELECT COD_EMPRESA, NOM_FANTASI, TIP_RETORNO, TIP_CAMPANHA,NUM_DECIMAIS_B FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
	//fnEscreve($sql);
	$arrayQueryempre = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlempre));		

    if ($tip_retorno == 2){
            $casasDec = $arrayQueryempre['NUM_DECIMAIS_B'];
    }else { $casasDec = '0'; }
	
	
	//busca dados da venda
	$sql = "SELECT VEN.*, CL.COD_CLIENTE, CL.NOM_CLIENTE, CL.NUM_CELULAR, CL.NUM_CGCECPF, CL.NUM_CARTAO , US.NOM_USUARIO
			FROM VENDAS VEN 
			INNER JOIN CLIENTES CL ON CL.COD_CLIENTE = VEN.COD_CLIENTE
			LEFT JOIN USUARIOS US ON US.COD_EXTERNO = VEN.COD_VENDEDOR
			WHERE VEN.COD_EMPRESA = $cod_empresa 
			AND VEN.COD_VENDAPDV = $cod_orcamento";	  
	
	// fnEscreve($sql);
	//dados da query
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
	$qrBuscaRecibo = mysqli_fetch_assoc($arrayQuery);
	
	$cod_cliente = $qrBuscaRecibo['COD_CLIENTE'];
	$nom_cliente = $qrBuscaRecibo['NOM_CLIENTE'];
	$val_totprod = $qrBuscaRecibo['VAL_TOTPRODU'];
	$cupom = $qrBuscaRecibo['COD_CUPOM'];
	$num_celular = $qrBuscaRecibo['NUM_CELULAR'];
	$num_cgcecpf = $qrBuscaRecibo['NUM_CGCECPF'];
	$nom_usuario = $qrBuscaRecibo['NOM_USUARIO'];
	$num_cartao = $qrBuscaRecibo['NUM_CARTAO'];
	$dat_cadastr = $qrBuscaRecibo['DAT_CADASTR'];
	

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
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
	$qrBuscaSaldo = mysqli_fetch_assoc($arrayQuery);
 	 
	$credito_disponivel = $qrBuscaSaldo['CREDITO_DISPONIVEL'];

	// fnEscreve($credito_disponivel);

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
			<h5> <b>RECIBO AVULSO </b></h5>
						
			<div class="push15"></div>
			
			<b><?php echo $nom_cliente; ?> </b>
			<div class="push5"></div>
			
			Cartão: <b><?php echo $num_cartao; ?> </b>
			<div class="push5"></div>

			<?php if($cupom != ""){ ?>

				Cupom: <b><?php echo $cupom; ?></b> 
				<div class="push5"></div>

			<?php } ?>
			
			<!-- Atendente: <b><?php echo $nom_usuario; ?></b> 
			<div class="push5"></div> -->
			
			Data: <b><?php echo fnDataFull($dat_cadastr); ?> </b>
			<div class="push5"></div>	

			<!-- Total da venda: <b><?php echo fnValor($val_totprod,2); ?></b> 
			<div class="push5"></div> -->		
			
			Saldo: <b><?php echo fnValor($credito_disponivel,2); ?></b> 
			<div class="push5"></div>	
				
			
			
						
				
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