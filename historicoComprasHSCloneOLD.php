
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
			
			$cod_venda = fnLimpacampoZero($_REQUEST['COD_VENDA']);
			$cod_orcamento = fnLimpacampoZero($_REQUEST['COD_ORCAMENTO']);
			$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
			$cod_cliente = fnLimpacampoZero($_REQUEST['COD_CLIENTE']);
			$cod_lancamen = fnLimpacampoZero($_REQUEST['COD_LANCAMEN']);
			$cod_ocorren = fnLimpacampoZero($_REQUEST['COD_OCORREN']);
			$cod_univend = fnLimpacampoZero($_REQUEST['COD_UNIVEND']);
			$cod_formapa = fnLimpacampoZero($_REQUEST['COD_FORMAPA']);
			$tem_prodaux = fnLimpacampoZero($_REQUEST['TEM_PRODAUX']);			
			
			$val_totprodu = fnLimpacampo($_REQUEST['VAL_TOTPRODU']);
			$val_resgate = fnLimpacampo($_REQUEST['VAL_RESGATE']);
			$val_desconto = fnLimpacampo($_REQUEST['VAL_DESCONTO']);
			$val_totvenda = fnLimpacampo($_REQUEST['VAL_TOTVENDA']);
			$cod_vendapdv = fnLimpacampo($_REQUEST['COD_VENDAPDV']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){
				
					//$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
					


					//mensagem de retorno
					switch ($opcao)
					{
						case 'CAD':
							$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
							break;
						case 'ALT':
							$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
							break;
						case 'EXC':
							$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
							break;
						break;
					}			
					$msgTipo = 'alert-success';						  
				
			}  

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
	$sql = "select * from cnvassociado where Id_associado= '".$cod_cliente."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($depyl_clone->conndepylclone(),$sql) or die(mysqli_error());
	$qrBuscaCliente = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($arrayQuery)){
		
		$nom_cliente = $qrBuscaCliente['Nome'];
		$cod_cliente = $qrBuscaCliente['Id_associado'];
		@$num_cartao = $qrBuscaCliente['cod_usuario'];
		$num_cgcecpf = $qrBuscaCliente['cpf'];
        }else{
				
		$nom_cliente = "";
		$cod_cliente = "";
		$num_cartao = "";
		$num_cgcecpf = "";
	}
	//fnMostraForm();
	//fnEscreve($cod_cliente);
	//fnEscreve("Clone");
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
						$sql = "Select fnSaldoPontos(id_associado) as  saldopontos, 
                                                        ifnull((select fantasia from cnvloja where id_loja=A.id_loja),'') as loja 
                                                        from cnvassociado A where A.id_associado=$cod_cliente";
						
						//fnEscreve($sql);
						
						$arrayQuery = mysqli_query($depyl_clone->conndepylclone(),$sql) or die(mysqli_error());
						$qrBuscaTotais = mysqli_fetch_assoc($arrayQuery);
						//total regatado
                                                $sqlresgatado="select vw_stattrocas.* from  vw_stattrocas where  vw_stattrocas.id_associado=$cod_cliente";
                                                $rsresgatado=mysqli_fetch_assoc(mysqli_query($depyl_clone->conndepylclone(),$sqlresgatado));
                                                // ha expirar
                                                $sqlexpira="select ifnull(sum(saldopontos),0) as pontosaexpirar 
                                                            from cnvvenda where excluido=0 and pontoszeradosauto=0 
                                                            and id_associado=$cod_cliente 
                                                            and dataexpira>now() and dataexpira<=date_add(now(), interval 30 day)";
                                                 $rsexpira=mysqli_fetch_assoc(mysqli_query($depyl_clone->conndepylclone(),$sqlexpira));
                                               
						if (isset($arrayQuery)){
							$totais=$qrBuscaTotais['saldopontos']+$rsresgatado['pontostrocados'];
							$total_creditos = $totais;
							$total_debitos = $rsresgatado['pontostrocados'];
							$credito_disponivel = $qrBuscaTotais['saldopontos'];
							$credito_aliberar = $rsexpira['pontosaexpirar'];
							
						}else{
							
							$total_creditos = 0;
							$total_debitos = 0;
							$credito_disponivel = 0;
							$credito_aliberar = 0;
							$credito_expirados = 0;
							$credito_bloqueado = 0;
							
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
										<div class="widget-title">A expirar em 30 dias</div>
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
                                                                          <th>Valor</th>
									  <th>Crédito</th>
									  <th>Resgate</th>
									  <th>Expiraem</th>
									  <th>Obs</th>
									  <th>Loja</th>
									</tr>
								  </thead>
								<tbody>
								  
								<?php 
											
									$sql = "Select ifnull((Select pontos from cnvtroca where id_troca=cnvvenda.id_trocacredito),0) as resgate,
                                                                                cnvempresa.nome as grupo, cnvvenda.id_venda, datahora, cnvloja.fantasia as loja, 
                                                                                valor, pontos, saldopontos, nf,if(tipo='Manual','M','L') as tipo ,
                                                                                if(Lancamento='Pontos extras','PE',if(Lancamento='Venda com produtos','VP','VS')) as lancamento ,
                                                                                cnvvenda.obs,CASE WHEN PontosZeradosAuto = 1 THEN 'Expirado por inatividade' 
                                                                                WHEN  dataexpira>NOW() AND PontosZeradosAuto=0 THEN CONCAT(TO_DAYS(dataexpira)-TO_DAYS(NOW()),' dias') 
                                                                                WHEN dataexpira IS NULL THEN '-' ELSE 'Pontos expirados' END AS expiraem, if((select count(id_venda) 
                                                                                from cnvTrocaVenda where id_venda=cnvvenda.id_venda)>0,1,0) as TrocaEfetuada from cnvvenda 
                                                                                INNER JOIN  cnvloja ON cnvvenda.id_loja=cnvloja.id_loja  
                                                                                LEFT JOIN cnvempresa ON cnvempresa.id_empresa=cnvvenda.id_empresa 
                                                                               where  cnvvenda.id_associado= $cod_cliente
                                                                               and cnvvenda.excluido=0  order by datahora desc";
											
									//fnEscreve($sql);
									$arrayQuery = mysqli_query($depyl_clone->conndepylclone(),$sql) or die(mysqli_error());
									
									$count = 0;
									$valorTTotal = 0;
									$valorTRegaste = 0;
									$valorTDesconto = 0;
									$valorTvenda = 0;
									
									while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery))
									  {														  
										$count++;
											
										echo"
											<tr id="."cod_credito_".$qrBuscaProdutos['COD_CREDITO'].">															
											  <td><small>".fnDataFull($qrBuscaProdutos['datahora'])."</small></td>
											  <td><small>".$qrBuscaProdutos['nf']."</small></td>
                                                                                          <td class='text-right ".$textRed." ".$textRed."'><small>".fnValor($qrBuscaProdutos['valor'],2)."</small></td>
											  <td class='text-right ".$textRed." ".$textRed."'><small>".fnValor($qrBuscaProdutos['pontos'],2)."</small></td>
											  <td class='text-right ".$textRed." '><small>".fnValor($qrBuscaProdutos['resgate'],2)."</small></td>
											  <td><small>".$qrBuscaProdutos['expiraem']."</small></td>												
											  <td><small>".$qrBuscaProdutos['obs']."</small></td>												
											  <td><small>".$qrBuscaProdutos['loja']."</small></td>";
										echo"</tr>";
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
	
	<script type="text/javascript">
		$(document).ready(function(){

			
		});	
	
	</script>	
