<?php
	
	//echo fnDebug('true');
	
	$hashLocal = mt_rand();	
	
	$tem_prodaux = "";
	
	if( $_SERVER['REQUEST_METHOD']=='POST' )
	{
		$request = md5( implode( $_POST ) );
		
		if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
		{
			$msgRetorno = 'Essa página já foi utilizada';
			$msgTipo = 'alert-warning';
		}
		else
		{
			$_SESSION['last_request']  = $request;

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
	 

		}
	}
	
	//busca dados url
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);
		$cod_cliente = fnDecode($_GET['idC']);	
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
												
	}else {
		$cod_empresa = 0;	
		$nom_empresa = "";
	}
	

	//busca dados do cliente
	$sql = "SELECT NOM_CLIENTE, NUM_CARTAO, NUM_CGCECPF, COD_CLIENTE FROM CLIENTES where COD_CLIENTE = '".$cod_cliente."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$qrBuscaCliente = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($arrayQuery)){
		
		$nom_cliente = $qrBuscaCliente['NOM_CLIENTE'];
		$cod_cliente = $qrBuscaCliente['COD_CLIENTE'];
		$num_cartao = $qrBuscaCliente['NUM_CARTAO'];
		$num_cgcecpf = $qrBuscaCliente['NUM_CGCECPF'];

	}else{
				
		$nom_cliente = "";
		$cod_cliente = "";
		$num_cartao = "";
		$num_cgcecpf = "";
			
	}
    	
	//fnMostraForm();
	//fnEscreve($cod_cliente);
	
?>
<style>
.widget .widget-title {
    font-size: 14px;
}
.widget .widget-int {
    font-size: 18px;
	padding: 0 0 10px 0;
}
.widget .widget-item-left .fa, .widget .widget-item-right .fa, .widget .widget-item-left .glyphicon, .widget .widget-item-right .glyphicon {
    font-size: 35px;
}
</style>
	<div class="push30"></div> 
	
	<div class="row">				
	
		<div class="col-md12 margin-bottom-30">
				
				<div class="portlet-body" style="max-width: 1100px;">

					<?php if ($msgRetorno <> '') { ?>	
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 <?php echo $msgRetorno; ?>
					</div>
					<?php } ?>
					
					<div class="push30"></div> 

					<div class="login-form">
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
				
						<div class="row">
												
							<div class="col-md-4">
								<label for="inputName" class="control-label">Nome do Usuário</label>
								<div class="push5"></div> 
								<h4><?php echo $nom_cliente;?></h4>										
							</div>															
									
							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label">Número do Cartão</label>
									<div class="push5"></div> 
									<h4><?php echo $num_cartao;?></h4>
								</div>
							</div>
							
						</div>
						
						<div class="push20"></div>
																			
						<?php 
						
						//busca dados do cliente
						$sql = "SELECT (SELECT Sum(val_credito) 
								FROM   creditosdebitos 
								WHERE  cod_cliente = A.cod_cliente
									   AND cod_statuscred <> 6												
									   AND tip_credito = 'C')  AS TOTAL_CREDITOS,
									   
								(SELECT Sum(val_credito) 
								FROM   creditosdebitos 
								WHERE  cod_cliente = A.cod_cliente 
									   AND tip_credito = 'D')  AS TOTAL_DEBITOS,
									   
								(SELECT Sum(val_saldo) 
								FROM   creditosdebitos 
								WHERE  cod_cliente = A.cod_cliente 
									   AND tip_credito = 'C' 
									   AND COD_STATUSCRED = 1 
									   AND ((log_expira='S' and dat_expira > Now())or(log_expira='N'))) AS CREDITO_DISPONIVEL, 
									   
								(SELECT Sum(val_credito) 
								FROM   creditosdebitos 
								WHERE  cod_cliente = A.cod_cliente 
									   AND tip_credito = 'C' 
									   AND COD_STATUSCRED = 2 
									   AND dat_expira > Now()) AS CREDITO_ALIBERAR
						
						FROM CREDITOSDEBITOS A
						WHERE COD_CLIENTE=$cod_cliente
						AND COD_EMPRESA = $cod_empresa
						GROUP BY COD_CLIENTE
						";
						
						//fnEscreve($sql);
						
						$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
						$qrBuscaTotais = mysqli_fetch_assoc($arrayQuery);
						
						
						if (isset($arrayQuery)){
							
							$total_creditos = $qrBuscaTotais['TOTAL_CREDITOS'];
							$total_debitos = $qrBuscaTotais['TOTAL_DEBITOS'];
							$credito_disponivel = $qrBuscaTotais['CREDITO_DISPONIVEL'];
							$credito_aliberar = $qrBuscaTotais['CREDITO_ALIBERAR'];
						}else{
							
							$total_creditos = 0;
							$total_debitos = 0;
							$credito_disponivel = 0;
							$credito_aliberar = 0;
						}
						
						?>													
																			

						<div class="row">
							
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

								<div class="widget widget-default widget-item-icon">
									<div class="widget-item-left">
										<span class="fa fa-cart-plus"></span>
									</div>                             
									<div class="widget-data">
										<div class="widget-int">
										<div class="push10"></div>
										<?php echo fnValor($total_creditos,2); ?></div>
										<div class="widget-title">Total Ganho</div>
										<div class="widget-subtitle">													
										<div class="push5"></div>
										</div>
									</div>
								</div>  

							</div>											
							
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

								<div class="widget widget-default widget-item-icon">
									<div class="widget-item-left">
										<span class="fa fa-cart-arrow-down"></span>
									</div>                             
									<div class="widget-data">
										<div class="widget-int">
										<div class="push10"></div>
										<?php echo fnValor($total_debitos,2); ?></div>
										<div class="widget-title">Total Resgatado</div>
										<div class="widget-subtitle">													
										<div class="push5"></div>
										</div>
									</div>
								</div>  

							</div>
							
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

								<div class="widget widget-default widget-item-icon">
									<div class="widget-item-left">
										<span class="fa fa-shopping-bag"></span>
									</div>                             
									<div class="widget-data">
										<div class="widget-int">
										<div class="push10"></div>
										<?php echo fnValor($credito_disponivel,2); ?></div>
										<div class="widget-title">Saldo Disponível</div>
										<div class="widget-subtitle">													
										<div class="push5"></div>
										</div>
									</div>
								</div> 
								
							</div>											

							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

								<div class="widget widget-default widget-item-icon">
									<div class="widget-item-left">
										<span class="fa fa-clock-o"></span>
									</div>                             
									<div class="widget-data">
										<div class="widget-int">
										<div class="push10"></div>
										<?php echo fnValor($credito_aliberar,2); ?></div>
										<div class="widget-title">Saldo à Liberar</div>
										<div class="widget-subtitle">													
										<div class="push5"></div>
										</div>
									</div>
								</div>  

							</div>
							
						</div>	
						
						<div class="push20"></div>

						<div class="row">
											
							<div class="col-md-12" id="div_Produtos">

								<table class="table table-bordered table-hover">
								  <thead>
									<tr>
									  <!--<th></th>-->
									  <th>Data</th>
									  <th>ID Venda</th>
									  <th>Tipo</th>
									  <th>Status</th>
									  <th>Crédito</th>
									  <th>Resgate</th>
									  <th>Expiração</th>
									  <th>Origem</th>
									  <th>Loja</th>
									</tr>
								  </thead>
								<tbody>
								  
								<?php 
											
									$sql = "SELECT 
											A.COD_CREDITO, 
											A.COD_CAMPAPROD,
											A.COD_ITEMVEN, 
											A.COD_CLIENTE,
											A.COD_VENDA,
											A.TIP_CREDITO, 
											A.DAT_CADASTR, 
											A.DAT_LIBERA,
											A.LOG_EXPIRA,
											A.DAT_EXPIRA,
											A.TIP_PONTUACAO,
											A.VAL_PONTUACAO,
											sum(A.VAL_CREDITO) VAL_CREDITO,
											sum(A.VAL_SALDO) VAL_SALDO,											
											A.COD_STATUSCRED,
											H.DES_STATUSCRED,
											A.COD_CAMPANHA,
											A.TIP_CAMPANHA,
											A.COD_PERSONA, 
											A.DES_OPERACA ,
											B.ABV_TPCAMPA,
											C.ABR_CAMPANHA,
											D.DES_PERSONA,
											E.DES_ABREVIA,
											G.NOM_UNIVEND,
											F.COD_VENDAPDV

											FROM CREDITOSDEBITOS A
											LEFT JOIN WEBTOOLS.TIPOCAMPANHA B ON A.TIP_CAMPANHA=B.COD_TPCAMPA 
											LEFT JOIN CAMPANHA C ON C.COD_CAMPANHA=A.COD_CAMPANHA
											LEFT JOIN PERSONA  D  ON  D.COD_PERSONA=A.COD_PERSONA
											LEFT JOIN STATUSMARKA E ON E.COD_STATUS=A.COD_STATUS
											LEFT JOIN VENDAS F ON F.COD_VENDA=A.COD_VENDA
											LEFT JOIN WEBTOOLS.UNIDADEVENDA G ON G.COD_UNIVEND=F.COD_UNIVEND
											LEFT JOIN STATUSCREDITO H ON H.COD_STATUSCRED=A.COD_STATUSCRED

											WHERE A.COD_CLIENTE = $cod_cliente
											AND A.COD_STATUSCRED <> 6
											AND A.COD_STATUS <> 15  
											AND A.COD_EMPRESA = $cod_empresa 											
											group by A.COD_VENDA
											ORDER BY
											A.DAT_CADASTR DESC
											";
											
									//fnEscreve($sql);
									$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
									
									$count = 0;
									$valorTTotal = 0;
									$valorTRegaste = 0;
									$valorTDesconto = 0;
									$valorTvenda = 0;
									
									while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery))
									  {														  
										$count++;
										if ($qrBuscaProdutos['TIP_CREDITO'] == "D"){
											$textRed = "text-danger";
											$valorCred = 0;
											$valorDeb = $qrBuscaProdutos['VAL_CREDITO'];
											$tag_campanha = "";
											$tag_persona =  "";	
											$diff_dias =  "";	
											$opcaoExpandir =  "";
										}else {
											$textRed = "";
											$valorCred = $qrBuscaProdutos['VAL_CREDITO'];
											$valorDeb = 0;
											if ($qrBuscaProdutos['COD_VENDA'] != 0){
												$tag_campanha = "<li class='tag'><span class='label label-info'>● &nbsp; ".$qrBuscaProdutos['ABR_CAMPANHA']."</span></li>";
												$tag_persona =  "<li class='tag'><span class='label label-warning'>● &nbsp; ".$qrBuscaProdutos['DES_PERSONA']."</span></li>";
												$opcaoExpandir =  "<a href='javascript:void(0);' onclick='abreDetail(".$qrBuscaProdutos['COD_CREDITO'].")'><i class='fa fa-plus' aria-hidden='true'></i></a>";
											}else{
												$tag_campanha = "";
												$tag_persona =  "";	
												$opcaoExpandir =  "";
											}

											//$diff_dias = fnDateDif($qrBuscaProdutos['DAT_CADASTR'],$qrBuscaProdutos['DAT_EXPIRA'])." dia(s)";	
											//<!--<td>".$diff_dias."</td>-->
										}													
											
										echo"
											<tr id="."cod_credito_".$qrBuscaProdutos['COD_CREDITO'].">															
											  <!--<td class='text-center'>".$opcaoExpandir."</td>-->
											  <td><small>".fnDataFull($qrBuscaProdutos['DAT_CADASTR'])."</small></td>
											  <td><small>".$qrBuscaProdutos['COD_VENDAPDV']."</small></td>												
											  <td class='text-center ".$textRed." '><small>".$qrBuscaProdutos['TIP_CREDITO']."</small></td>												
											  <td class='".$textRed."'><small>".$qrBuscaProdutos['DES_STATUSCRED']."</small></td>
											  <td class='text-right ".$textRed." ".$textRed."'><small>".fnValor($valorCred,2)."</small></td>
											  <td class='text-right ".$textRed." '><small>".fnValor($valorDeb,2)."</small></td>
											  <td><small>".fnDataFull($qrBuscaProdutos['DAT_EXPIRA'])."</small></td>												
											  <td><small>".$qrBuscaProdutos['DES_ABREVIA']."</small></td>												
											  <td><small>".$qrBuscaProdutos['NOM_UNIVEND']."</small></td>";
										echo"											
											</tr>";
										echo"
											<tr style='display:none; background-color: #fff;' id='abreDetail_".$qrBuscaProdutos['COD_CREDITO']."' idvenda='".$qrBuscaProdutos['COD_VENDA']."'>
												<td></td>
												<td colspan='11'>
												<div id='mostraDetail_".$qrBuscaProdutos['COD_CREDITO']."'>
												</div>
												</td>
											</tr>														  
											";
										  }											

								?>								
																				
							</div>
							
						</div>
						
						</form>
					</div>								
				</div>
			</div>
			<!-- fim Portlet -->
		</div>
	</div>	
