<?php
	
	//echo fnDebug('true');
	
	// definir o numero de itens por pagina
	$itens_por_pagina = 50;
	
	// Página default
	$pagina = 1;
	
	$dias30="";
	$dat_ini="";
	$dat_fim="";
	$hashLocal = mt_rand();	
	
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
		
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
			
			$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);			
			$cod_tpusuario = fnLimpaCampoZero($_POST['COD_TPUSUARIO']);			
			$log_estatus = fnLimpaCampo($_POST['LOG_ESTATUS']);			
			$dat_ini = fnDataSql($_POST['DAT_INI']);
			$dat_fim = fnDataSql($_POST['DAT_FIM']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){
				
				
			}  

		}
	}

	//busca dados url
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
												
	}else {
		$cod_empresa = 0;	
		$nom_empresa = "";
	}
	
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}
	 
	
	//fnMostraForm();	
	//fnEscreve($dat_ini);
	//fnEscreve($dat_fim);
	//fnEscreve($cod_univendUsu);
	//fnEscreve($qtd_univendUsu);
	//fnEscreve($lojasAut);
	//fnEscreve($usuReportAdm);
	//fnEscreve($lojasReportAdm);
	
?>

<style>
.text-white p, .text-white h4{
	color: #FCFCFC!important;
}
.info-header hr{
	margin: 5px 0px!important;
}
.panel:hover{
	/*pointer-events: none!important;*/
	-webkit-box-shadow: 1px 2px 2px 1px rgba(0,0,0,.2);
    box-shadow: 1px 2px 2px 1px rgba(0,0,0,.2);
}
.tooltip-inner {
	max-width: 70%;
	margin-left: auto;
	margin-right: auto;
 	word-wrap: break-word;
}

.info-header p, .panel p, .panel h4{
	margin: 5px 0px !important;
}
.tooltip-arrow,
.red-tooltip + .tooltip > .tooltip-inner 
{
	background-color: #f9fafb;
	color: #3c3c3c;
	margin-top: -190px!important;
}
.tooltip.in{
	opacity:1!important;
	pointer-events: none!important;
}
.tooltip .tooltip-arrow {
  top: 15!important;
  border-bottom-color: #f9fafb!important; /* black */
  background-color: transparent!important;
}
</style>
		
	<div class="push30"></div> 
	
	<div class="row" id="div_Report">				
	
		<div class="col-md-12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="glyphicon glyphicon-calendar"></i>
						<span class="text-primary"> <?php echo $NomePg; ?></span>
					</div>
					
					<?php 
					//$formBack = "1015";
					include "atalhosPortlet.php"; 
					?>	
					
				</div>
				<div class="portlet-body">

					<?php if ($msgRetorno <> '') { ?>	
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 <?php echo $msgRetorno; ?>
					</div>
					<?php } ?>						
						
				
					<div class="login-form">
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
													
							<fieldset>
								<legend>Filtros</legend> 
							
								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Data Inicial</label>
											
											<div class="input-group date datePicker" id="DAT_INI_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>">
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Data Final</label>
											
											<div class="input-group date datePicker" id="DAT_FIM_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>">
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
									<div class="col-md-2">
										<div class="push20"></div>
										<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
									</div>				
									
								</div>
										
							</fieldset>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
							
							<div class="push5"></div> 
					
						</form>

					</div>

				</div>

			</div>
						
			<div class="push30"></div>

			<div class="portlet portlet-bordered">

				<div class="portlet-body">
						
					<div class="row">

						<div class="col-md-8 col-md-offset-2">

							<div class="row">

								<div class="col-md-4">

									<div class="push20"></div>
									
									<div class="col-xs-10 col-xs-offset-1">

										<img src="data:image/jpeg;base64,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" width="100%" style="margin-right: auto!important; margin-left: auto!important; max-width: 250px;">
										
									</div>

									<div class="push10"></div>

									<div class="col-xs-8 col-xs-offset-2 info-header">
										
										<p class="f14"><b>DETALHES DA CAMPANHA</b></p>
										<hr>
										<p class="f14"><b>Campanha</b></p>
										<p class="f14">Teste Dev</p>
										<hr>
										<p class="f14"><b>Canal</b></p>
										<p class="f14">SMS</p>
										<hr>
										<p class="f14"><b>Disparo</b></p>
										<p class="f14"><?=fnDataFull(date("Y-m-d H:i:s"))?></p>
										<hr>
										<p class="f14"><b>Intervalo de Análise</b></p>
										<p class="f14"><?=fnDataShort(date("Y-m-d"))?> à <?=fnDataShort(date("Y-m-d"))?></p>
										<hr>
										<p class="f14"><b>Data de Avaliação</b></p>
										<p class="f14"><?=fnDataShort(date("Y-m-d"))?></p>
										<hr>
										<p class="f14"><b>Total de Clientes</b></p>
										<p class="f14">9.876</p>

									</div>

									<div class="push10"></div>

								</div>

								<div class="col-md-8">

									<div class="push20"></div>

									<div class="row">

										<div class="col-xs-12">

											<div class="panel panel-success caixa-texto">
												<div class="panel-heading">

													<div class="push50"></div>

													<div class="col-xs-6 col-xs-offset-3 red-tooltip" data-html="true" data-toggle='tooltip' data-placement='bottom' data-original-title='
													<div class="row">
														<div class="col-xs-12 text-left">
															<div class="push10"></div>
															<p class="f14" style="margin: 0px!important;"><b>Detalhes do Resultado</b></p>
															<hr style="margin: 5px 0px!important;">
															<p class="f12">Com base no comportamento de compra do Grupo de Controle (clientes que não receberam comunicação), espontaneamente geraria um faturamento de <b>R$ 69.359,85</b></p>
															<div class="push10"></div>
															<p class="f12">Por impacto da comunicação, o faturamento foi de <b>R$112.570,88</b></p>
															<div class="push10"></div>
															<p class="f12"><b>Dessa forma, podemos afirmar que o resultado real da ação foi de R$ 42.847,22</b></p>
															<div class="push10"></div>
														</div>
													</div>
													'>

														<p class="f14">Investimento Total</p>
														<h4><b>R$ 0,00</b></h4>

														<div class="push10"></div>
														<hr>
														<div class="push10"></div>
														<p class="f14">Resultado da Campanha</p>
														<div class="push5"></div>
														<p style="font-size: 48px;"><b><span style="font-size: 32px;">R$</span> 98.765,43</b></p>
														<div class="push5"></div>
														<p class="f14">Faturamento Total R$ 987.654,32</p>
														<div class="push10"></div>
														<hr>
														<div class="push5"></div>
														<p class="f14">Retorno Sobre Investimento</p>
														<p class="f21"><b>00x</b></p>

													</div>


													<div class="push50"></div>

												</div>

											</div>

										</div>

									</div>

									<div class="push20"></div>
									
								</div>

							</div>

							<div class="row">
								
								<div class="col-xs-3">
									
									<div class="panel panel-default">
										<div class="panel-heading"><b>Clientes Impactados</b></div>
										<div class="panel-body">1.234</div>
									</div>

								</div>

								<div class="col-xs-3">

									<div class="panel panel-default">
										<div class="panel-heading"><b>Clientes que Retornaram</b></div>
										<div class="panel-body">999</div>
									</div>

								</div>

								<div class="col-xs-3">

									<div class="panel panel-default">
										<div class="panel-heading"><b>Compras Realizadas</b></div>
										<div class="panel-body">5.432</div>
									</div>

								</div>

								<div class="col-xs-3">

									<div class="panel panel-default">
										<div class="panel-heading"><b>Itens Comprados</b></div>
										<div class="panel-body">9.876</div>
									</div>

								</div>

							</div>

							<div class="row">
								
								<div class="col-xs-3">
									
									<div class="panel panel-default">
										<div class="panel-heading">
											1.234
											<div class="push"></div>
											<b>Clientes Impactados</b></div>
									</div>

								</div>

								<div class="col-xs-3">

									<div class="panel panel-default">
										<div class="panel-heading">
											999
											<div class="push"></div>
											<b>Clientes que Retornaram</b></div>
									</div>

								</div>

								<div class="col-xs-3">

									<div class="panel panel-default">
										<div class="panel-heading">
											5.432
											<div class="push"></div>
											<b>Compras Realizadas</b></div>
									</div>

								</div>

								<div class="col-xs-3">

									<div class="panel panel-default">
										<div class="panel-heading">
											9.876
											<div class="push"></div>
											<b>Itens Comprados</b></div>
									</div>

								</div>

							</div>

							<div class="row">
								
								<div class="col-xs-3 text-center">

									<div class="col-xs-12" style="border: 1px solid #edeeef; border-radius: 10px;">

										<div class="push10"></div>
										<p class="f10">Clientes Impactados</p>
										<p class="text-primary" style="font-size: 24px;"><b>1.234</b></p>

									</div>

								</div>

								<div class="col-xs-3 text-center">

									<div class="col-xs-12" style="border: 1px solid #edeeef; border-radius: 10px;">

										<div class="push10"></div>
										<p class="f10">Clientes que Retornaram</p>
										<p class="text-primary" style="font-size: 24px;"><b>999</b></p>

									</div>

								</div>

								<div class="col-xs-3 text-center">

									<div class="col-xs-12" style="border: 1px solid #edeeef; border-radius: 10px;">

										<div class="push10"></div>
										<p class="f10">Compras Realizadas</p>
										<p class="text-primary" style="font-size: 24px;"><b>5.432</b></p>

									</div>

								</div>

								<div class="col-xs-3 text-center">

									<div class="col-xs-12" style="border: 1px solid #edeeef; border-radius: 10px;">

										<div class="push10"></div>
										<p class="f10">Itens Comprados</p>
										<p class="text-primary" style="font-size: 24px;"><b>9.876</b></p>

									</div>

								</div>

							</div>

							<div class="push10"></div>

							<div class="row">
								
								<div class="col-xs-12 info-dash">

									<div class="panel panel-default">
										<div class="panel-heading" style="font-size: 19px;"><b>Taxa de Engajamento nas Lojas</b></div>
										<div class="panel-body">
											
											<div class="col-xs-6">
												
												<div class="row">
													
													<div class="col-xs-12 text-right">
														
														<p class="f18"><b>Grupo de Ação</b></p>
														<p class="f12">Receberam a comunicação</p>
														<p class="f16"><b>9.876 Clientes</b></p>

													</div>

													<div class="col-xs-5 col-xs-offset-7">

														<div class="col-xs-12" style="border: 1px solid #edeeef; border-radius: 10px;">

															<div class="push20"></div>
															<p class="f10">Taxa de Engajamento</p>
															<p class="text-success" style="font-size: 32px;"><b>20,9%</b></p>
															<div class="push20"></div>

														</div>

													</div>

													<div class="push10"></div>

													<div class="col-xs-5 col-xs-offset-7">
														<span class="fas fa-caret-down fa-2x text-success"></span>
													</div>

													<div class="push10"></div>
															
													<div class="col-xs-5 col-xs-offset-7">
														<div class="panel panel-success">
															<div class="panel-heading">
																<p class="f10">Ticket Médio por Compra</p>
																<p class="f16"><b>R$ 99,99</b></p>
															</div>
														</div>
													</div>

													<div class="col-xs-5 col-xs-offset-7">
														<div class="panel panel-success">
															<div class="panel-heading">
																<p class="f10">Gasto Médio p/ Cliente</p>
																<p class="f16"><b>R$ 99,99</b></p>
															</div>
														</div>
													</div>

													<div class="col-xs-5 col-xs-offset-7">
														<div class="panel panel-success">
															<div class="panel-heading">
																<p class="f10">VVR (Gasto a Mais por Cashback Resgatado)</p>
																<p class="f16"><b>1.234% (1.2X mais)</b></p>
																<p style="font-size: 8px;">Para Cada R$1,00 resgatado, o cliente gastou R$11,00</p>
															</div>
														</div>
													</div>

												</div>

											</div>

											<div class="col-xs-6">
												
												<div class="row">
													
													<div class="col-xs-12 text-left">
														
														<p class="f18"><b>Grupo de Controle</b></p>
														<p class="f12">Não Receberam comunicação</p>
														<p class="f16"><b>6.789 Clientes</b></p>

													</div>

													<div class="col-xs-5">

														<div class="col-xs-12" style="border: 1px solid #edeeef; border-radius: 10px;">

															<div class="push20"></div>
															<p class="f10">Taxa de Engajamento</p>
															<p class="text-primary" style="font-size: 32px;"><b>10,8%</b></p>
															<div class="push20"></div>

														</div>

													</div>

													<div class="push10"></div>

													<div class="col-xs-5">
														<span class="fas fa-caret-down fa-2x text-primary"></span>
													</div>

													<div class="push10"></div>
															
													<div class="col-xs-5">
														<div class="panel panel-primary">
															<div class="panel-heading">
																<p class="f10">Ticket Médio por Compra</p>
																<p class="f16"><b>R$ 99,99</b></p>
															</div>
														</div>
													</div>
													<div class="push"></div>

													<div class="col-xs-5">
														<div class="panel panel-primary">
															<div class="panel-heading">
																<p class="f10">Gasto Médio p/ Cliente</p>
																<p class="f16"><b>R$ 99,99</b></p>
															</div>
														</div>
													</div>
													<div class="push"></div>

													<div class="col-xs-5">
														<div class="panel panel-primary">
															<div class="panel-heading">
																<p class="f10">VVR (Gasto a Mais por Cashback Resgatado)</p>
																<p class="f16"><b>1.234% (1.2X mais)</b></p>
																<p style="font-size: 8px;">Para Cada R$1,00 resgatado, o cliente gastou R$11,00</p>
															</div>
														</div>
													</div>
													<div class="push"></div>

												</div>

											</div>

										</div>
									</div>

								</div>

							</div>

						</div>
						
					</div>
						
					<div class="push50"></div>									
					
					<div class="push"></div>
					
					</div>								
				
				</div>

				<div class="push30"></div>

				<div class="portlet portlet-bordered">

					<div class="portlet-body">
							
						<div class="row">

							<div class="col-md-8">

								<div class="row">

									<div class="col-md-4">

										<div class="push20"></div>
										
										<div class="col-xs-10 col-xs-offset-1">

											<img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxIQERUQEBIVERAVGBgVFhcWGRUaGBYVGBcWFhcYGBgaHSggHhopHRcXITEiJSkrLi4uGB8zODMtNygtLisBCgoKDg0OGxAQGy4mICUtLy01LS0tLy8tNS8vLy0tLS8tLy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLf/AABEIALcBEwMBEQACEQEDEQH/xAAcAAEAAgMBAQEAAAAAAAAAAAAABgcDBAUCCAH/xABKEAABAwICBQYGEgEDAwUAAAABAAIDBBESIQUGMUFRBxMiYXHRMjSBkaHCFBUXM0JSU1RicnODkpOxssHSI4Ki8Bbh8SQlNUNj/8QAGwEBAAIDAQEAAAAAAAAAAAAAAAQFAgMGAQf/xAA6EQACAQMABgcHBAICAgMAAAAAAQIDBBEFEiExQVETM1JhcYGRFBUiMqGx0QZi4fBCwRYjU/EkNIL/2gAMAwEAAhEDEQA/AJbr/rm+J5paV2F498kG1pOeFvXxKtLKyU10k93BFPf3zg+jp7+LK0mlc8lz3F7jtLiST2kq5jFRWEUkpOTyzwvTwIAgCZATICZATIC8AXoC8BvaL0tPSuxwSOYb3IB6LvrN2Fa6tGFRYkjbSrTpPMXguXU/WJtfDjsGys6MjeB3EfRPeNy5+5t3RnjhwOltLlV4Z48TvKMSjWj0fE2QzNjYJXZOeGjEe07V7rPGDWqUFLXS28zZXhsCAIDy5gNiRe2zqWEoRk02tx7lniepZHhxuDcTgxt97jsA617KSjjPgexhKWdVZxtMGl9HiphdC5xYHWzFrizg4bQRtG9JR1lg2W9Z0aiqJZwRKo5OYCXSSVU5Ju5znGO53kklq0u2i3vLqP6jq046sacUl4/kr7TMNOyQspnySMGWN+HpH6IAHR6ztUiOjotZbKiv+vK8ZYp04tc9v5NG3asvd0ObNP8Az67/APFD6nZ0joVsVHT1TXOcZi4OBtZpF7WsL7jtWuNhByayyXV/Wt1CjGoqcdvj+TRodIyQlpa4lrHFwadlyLO7LjglbRNKrTdObeH/AHJT3X6wuK66qKbxlrOcJ5xvLC0NViqaDFmTkRvaevvXz+40RcUbroMbXufDHMu7XSFG4o9JF+KJI6vYy0TyZLZOdtz7N6v5aTt6GLWs9fZiT4ZNSoTnmcdnJHpzoYG42WJd4Od/NwCylOx0fTdaksuW7+8EeJVaz1ZcDSdMyo8P/HLsDvgnqKqpXFvpLZV+CpwfBkhQnQ+XbE0p4HMOFwsf17FS3NrVt56lRYf93EqFSM1mJn0Q60zfKPQVN0JPVvYd+V9DVdLNJkoX0MpwgPnOrnMkj5HeE9znHtcST+q6yEVGKiuBxs5OUnJ8TEsjEIAgJLqe7R4EntgLno837514vA8m1Qrrp8roidaez7emJHzmr/xfRUqJi+5/YmZsOX3HOav/ABfRU96Yvuf2GbDl9xzmr/xfRU96Yvuf2GbDl9xzmr/xfRUpi+5/YZsOX3ILpsw+yJPY3vGL/H4Xg2Hxs9t9qsqOvqLX3lXX1Nd9HuJ7yZsojTv5wRGfEcfOYb4LC1sXwdvluqvSDq9IsZx3Fto1UejecZ7yB6e5n2TL7H94xnBbZbq6r3t1WVpQ1+jjr7ypuNTpHqbjQW40kz5KqrBWll7CSNwtxc0hw8tg70qt0ml0a55LLRc9Wtq80WTp2thpGmrlxXaMADSeliNwA29r5bVz09WL12dbaUKtzJUKfHb/AFmtqxrTFX4gxrmPZmWutsO8EGy9p1VPcbr/AEZVs2tdpp8Ud1zgMzkFsK4w+zYvlGfib3r3DMOkhzXqPZsXyjPxN70wx0kOaP1lXGTYPYSdgDhf9Uwz1Ti9zMpaDtF1jgyPE8zWNL3kNa0EknIADaSV6eSkorL3FU6663uqf8MV2U3E5GXr+r1efql0qaW17znL69nV+GGVH7kQut5VYP1ATSujx6Cgd8SU+l8jfWWiOyqy4qLWsIvk/wAkLW8pjf0NpaSkk5yI2uMLhuc3h28DuUe6t+npuCeG1vW9Eyzu5W1RTW1cVzJ7QVjJmCRhuD5wd4PWvlF7Z1bWs6dTeuPPvPo1pc07mkqkNxsKLlkjAXgNqGrywSDGz0t+qVaUNI/B0VwtaH1XgzROht1obGZIIcMjHtOJmIC+8XOxw3Fb7e3VG4p1qUtaGstvFdzMJz1oSjJYeCTLvSpCA+biuuOLM8VHI8YmRvcOLWuI84CwdSEdjaM1Tk9yPftbP8jL+B/cvOmp9pep70U+THtbP8jL+B/cnTU+0vUdFPkx7Wz/ACMv4H9ydLT7S9R0VTkx7Wz/ACMv4H9y86Wn2l6joqnJj2tn+Rl/A/uTpafaXqOinyY9rZ/kZfwP7k6Wn2l6joqnJj2tn+Rl/A/uTpafaXqOinyY9rZ/kZfwP7k6aHaXqOinyY9rZ/kZfwP7k6aHaXqOinyY9rZ/kZfwP7l701PtL1HRT5MwzQuYbPa5h22cCDbjYrJSUllMwcWt5J+TWZrK3E7YI3/q0fyqnTdzTt7XpKjwsos9EU3O4SXJll6WootJQOhLi3MEG2bXDYbbxtXMW17QvoPo3u9Tr7atVsKyqJfya2qOqbaAvfzhlkeA0m2EBoN7AXKmUqSgbtJaVne4TjhI6+m/Fpvs3/tK3R3opa/Vy8CmKaKOqsy7Yqo5NOyOYnY11vAkO47CdtjmprzHwOXio19m6X0f8n5T6Anc97Xs5lsWcr5BZsY4k7+oC9166kcGMLSq5NSWEt7OpqvNGK6COBvQx5vcBjkIBz+i2+xo8pO7Gaeq2yRazgq8Iw3Z38WXCoR05o6a0WyrhdBLfC7eNoIzBHYVlGTi8o016Ma0HCRVuldIaR0fJzD532HgEgOa5m4jED5tylxjCayc9Wq3NvLUkzC3XCY++xU0w+nE3+LJ0SMVpGp/lFPyM0es1KffdGwHrZ0P4XjpS4SMle0X81JeRJal8NRoaV1PFzMYu4MJvYtkDnZ+crUsqoslhNwqWUnBYXIrJSznQvQb+h9KOpn4hmw+E3iOrrCq9KaMp31LVeyS3P8AvAsNHaQnZ1NZbuKLP0bR+yI2yxPY6NwuDc36wRbIjguK/wCNXSeJNI7mnpKlUipQy0bzdBO3vHmPet8f0xPjUXoeu+XBGVugm73nyAKRH9M0v8pv6Gt30uCM0Wh42kG7iRnt4KZR0DbU2mm9m3fyMJXc5cjoq7IoQHzcuuOLRcPJX4j96/1VQaR67yR0Wi+o82TBQCyCAIAgCAIAgCAIAgKh5VvHm/Ys/dIr3RnVPxOd0p13kanJ1BzlWWA2vE/0Fh/hQP1JYq8s+jzh5TX1NuhavRXOe5lpwxNpI3zSu6LRdxF8gP1K5nROinZuTcst+h0d5dxcdZ7EjJoLTcNYwyQk2BwkOFiDt2diupQcXhkK3uIV460DLpof+nm+zf8AtK8jvRlX6uXgUwwugFoWuMxFnShp6F8i2LLbuL9vC20zfm3nL7aSxBbeePsfp0tW2jaZJS2LJgIJFjuIt0hbLpXyyXurAdPcbNr2HU1Wha6tglawxHH047HCDY9KMn4P0TmOsbMJv4WiRawTrxmljbtX4LfUM6UIDl6xaDjrYjFJkdrHb2O4jq4jes4TcXlEe5to14arKX0to2SlldDKLOHmcNzm9RU6MlJZRydejKjNxkaayNRN9UdaqanpjTVDHuBc4mzWuaWutkQT27lGqU5OWUXFlfUqdLo6iOpJo/Q9RAaoNMMQdgLm42WcbZYcxvG5Y61RPBKdKyqw6TGF6HIl1WoJPF9IsB3CQsP8tPoWfSTW9ER2NtP5KnqaNTqVM3pMlglZe2Jr/Ta36FR7vSNG1p69TZ3cxT0PVqSxBprmdDRklRoicizpqU++YQThttd1EeYj0RrO/heZg/hqR3r8dxKdvW0dNNfFTfEsykqWSsbJG4OY4XBG8Le1jYW0JqcVKO5lba7Plk0gaeN72FzGFha5wDXYSTit8AgZnda/EGFWbc9VHW6LjTp2XTySeG000tq2bs8eXPcbnJrVF08zBI6RjGCznEnG4u6Ts9gysBwHElZ0JZbRo05R1acKjik5N7Ety2YX57+4nGkq3mWg2uSbBRdJ6Q9ipqWMtvBQ0KPSyxk1Y9Nst0mkHfZV1P8AUlFxWvFpm6VlNPYyg19SOARcPJZ4j96/1VQaR67yR0Wi+o82TBQCxCAIAgCAIAgCAIAgKh5VvHm/Ys/dIr3RnVPxOd0p13kY+S3x/wC6f6q90l1XmeaL6/yLdqadsjHRvaHMcC1wOwg7VRJ4OhnFTTi9xraJ0TDSs5uBmBpNzmSSeJJzXspOW810aEKK1YI3libggCAIAgOTp7T0VI3pdKQjosG09Z4DrUa5uoUFt38iZaWVS5l8OxcWfmgtMifHG+wnjJDwNnaL7r5doS3uFUzF70Lq0dFKa+WW48a06vR10WF3Rlbcxv3tPA8WneFMpzcWVF3aRuIYe/gyma+ikgkdFK3C9psR/IO8Hipykmso5SrSlTk4y3muvTAk9E++h6hvCoYfPg7lqfWIs6b/APhSXecPRmj31D8DB1uJ2NHE9yjX9/SsqXSVPJcWzRZWVS7qakPN8ifaO0eynZgjHad7jxK+Y3+kKt5V6So/BcEfQrKyp2tNQh5vmdLS8DXOc1wDmvAJB2EEBSr6rUt7zpabw8Jr0PYU4VqOpNZW1Hf0TUscwNaAzCAMIyAA2WHBdbo3ScLyGf8AJb0V1W36HYt3AgmuWjqj2camCGSQtawRlrXEBwBu4nYQL+DvO3IWMirGWvrJHR6MuKDtFQqTSTbby+HJePPgbvJ7o2SKeaR8L4GvYOi5pAa8OOINv8HO44XtuzyoRabeCPpm5hUpwpxnrare3muGe/gyb1FO2QYXi4Xtza0riGpUWUUUJyg8xPEdHG0WDBbrzWuno+2hFRUFsPZVZt5bPnZd0cQi4eSzxH71/qqg0j13kjotF9R5smCgFiEAQBAEAQBAEAQBAVDyrePN+xZ+6RXujOqfic7pTrvIx8lvj/3T/VXukuq8zzRfX+RcSoTowgCAIAgCAi2smtbYbxQWfLsLvgs73dX/AIVbd36p/DDa/sXFhouVb46myP1ZD9EtM9Ux0ri7pc5I4/FYMZv1ZW8qqqGatdOTzx9C9u9WhbNQWOC89hrUeknxyidjrSXLj14jdwPUbrVC4lCp0ie031LWFSj0Ut2MehamhtJsqYhKzsI3tdvBXTUK0a0FKJxdzbTt6jhM5mt+rLa2O4s2dg6DuP0XfRPo/WVTqajKm9s1Xjs+ZFO1VO6J7o5Glr2mzgdoKnJ52o5acJQk4yW07mjHf+2VYOwSwHzusf0WqeddY7yfQa9lmnzRKdAw0zYW8yXYTmTZpJdvxZjPqXzrSc+kuGrtyUl3LHl3HZ6NhThQTt8NPjx8zptijP8A9tu1h/gqAre0lurY8Ysn69Rf4/UzaWZYxkG4LBnxspem4JOlJPOYrb4Gu1eVJd5pwylhDmmxCqaFedCoqlN4aJE4KawyTaPrRK2+xw2j/m5fQdHaRheU8rZJb1/eBT1qLpvHA21Ymkw0kj3NvIzm3XPRuDlc2NxxGaxg21tWDKainiLyjMsjE+bl1xxiLh5LPEfvX+qqDSPXeSOi0X1HmyYKAWIQBAEAugF0AugF0AugF0BENatSRXTifn+bswMtgxbC43viHxlNtr3oYauMlddWHTz1s48jzqvqOKGfn+f5zoubhwYfCtnfEeC9uL3poauMC10f0E9fWz5ExUEsQgCAIAgI/rjLKyK7C5sWyQsALwDs2kWbxO3YoV9KcaeVnHHG8sdGwpyq4kk3wzuIGxtJ8J9QOxkX9lRpW/Fy+h0zd3wUPV/g6vO09FznMvldUmMNaXNbZuMNdu3gEKVrUbfWUG9bHHvIOrcXmr0iWopbcPfg5sNfUzOEbXGRztgwRk+lqjRrV6j1U8vwX4Js7e2ox1pLC8X+Se6t6GdTgvleDK4C4aGtaLbMmgXPWVe2tvKmsye1nL3t1GtLEFhLntZ3FLIJFtdNVRWM5yOzaloyO54+K7+DuW2lU1dj3FdfWSrx1o/MivKG7KOtif0X4qcYTkcQldcW49yky+ZMpaaaoVIvfs+5K9SdVZmRulmcY8Y6Ef6OeNxtlbht4Cm0xY072GF8y3P/AF4F5oWFW2zOT2Ph/s6EsRYS1wsQvm9ehOjNwmsNHWwmprKOhR1geWRPja4eCCduxX1jpFV3TtqtNNbssiVaDhmcWzA+aE7Yi36rv4KiVLiwlJqVFrwZsUKyWyXqj9piA8GIvD9wIab9RIOxe2jpKsnaualwTSa8+4VFLV/7MYJMy9hfbv7V3sNbVWtvKl79hiq6kRtxODiLgdEFxzIGwdqTkorLMoQc3hGZZGB83Lrji0XDyV+I/ev9VUGkeu8kdHovqPNkwUAsQgCA1tIn/FJ9R37StdX5H4Gyl1kfFFS0jZZXNjjLnPdkBc965aHSTkoxbz4s7mr0NKDnNLC7iQnVCqt78zHa+HG66n+7q2Pn28sv8lT73ts9Xs54RytE6MnqXujY+zmC7sTnW223X3qNQo1a0nFPd3sm3NzQoQU5R2PkkfmmNHT0rgyVx6QuC1ziDx8q8r0atF4k/qzK0r0LmLcI7u5H7ofR09U4tidbCLkuc4AX2bEt6NWu2ovd3s8u7ihbJOcd/JI/NKaPnp5RC9xc9wBbhc43ubDy3CVqNWlNQbefFnttcUK9N1FHCXNI6seqNUQC6VrHHY0vdf0KUtH1sbZY7ssgS0tbJ7IZXPCOBVtkie6N7nBzTY9InNQainCTi28rvZbUuiqwU4pYfcYued8Z3nKw1pc36s2dFDsr0R5fUOAuXuA+sV6nNvCb9WFSg90V6I702ur5Q2GmvHHFEXyym2MiNgvhByF3WFzc57l0MazcUlwW0p/csaWalba5SxGPDa+PlyJnqrpttbTtlyEg6MjfivG3yHaO1Sac9eOSj0hZytK7pvdvT5o3qmubGbOv1nhfIXUW50hSt5as/XlwWSPClKa2GUOZI05hzSLHeCDuKk06tOtHMGmjBqUHyZW2sOr5p5mtbnDI4Bh4Emxaf+bFQ3Vo6VRJbmzrLLSCrUW5fNFbfyYYNHy1tRIYh0S83cfBa2+XotksY0Z3FV6vPebJXNOzt4qb243cSwdCaEipW2YLvPhPO13cOpXtvbQoxxHfzOXurypcyzLdy4HSJUgin6gCA5c+r9O+obVOjBlaMjuJGxxG9w3H/ss1OWMEeVrSlU6RradRYEg0tI0AlHB42H+D1Kq0noyF5DlJbn+TfQrum+44NO0slaHCxDgPSuMtYToXkYzWGmWdRqdJtcjG2IudhaLkkrSqE61d04LLbZm5xhHLJHo6gEQ4vO0/wOpd1o3RkLOHOT3v8FTXruo+43FaGgIAgPm5dccWi4eSvxH71/qqg0j13kjo9F9R5smCgFiEAQGtpL3mT6jv2la6vyS8DZR6yPivuVpqrXMgqWPkyYQWk/FvsP8AziudsqsadVOW7cdhpOhOtbuMN+xne07q3JJI6qpZMZd0rA57PgOGVurJT7mznKTq05Zz/dhVWWkadOCoV44x/dqNfk+B5+UOvfBnfbfFnfrWrRafSTzyN2m2nRp6u7P+jNpgeytHiUZyQOIdxsDhd6MLvItlx/3W2vxi/wCDTZv2W86PhJL8r8H7okexaJjtklTKwdeEuA/aCf8AUlD/AKLdPjJr++gun7VdyXCEX9vyfmtNU2HSMMrhdrWtJ7MTxf038i8vJqF1CT3L+TLR9KVWxqQjvb/Bsaf0D7McKqmlDjhAtfLLZhcNh6lsubV130tKRosr5WqdGtDj/c8yFVbHte5suLnAbOxZm/WVTVFJSanvOloypygnT+XuNGoqwzLa7h3rZToSnt4EqFJyObNMXm7j5NwVhCnGCwiXCmo7jswUb2aPfK1jnGokEYLWk4YYrvcbgZXeAOvCpKi1TyuJVVK8Kl/GnJpKCb//AE9n0W0/NUNPmhqA5x/wv6Mo4Dc63EfpdKVTUe0z0rYq8ofB8y2r8eZYb6sPkLzmx2X+g7P4K4+re695KdT5Xsa7t38nKqjq09Vb19zw8OieQCQRvG8biotRVbOs4wk1jc1xXBmcdWrHLRsSV4lZzc7cTcjibk4EZhw6wreh+oJOOpcRyua2M0+zOEtam/I6+iuZawRwANaB4IyPaePaumsrq3rQxRflxINx0rnrVN5vKcaCuuUfSbBUwwyYubaxznlvhMMhs17LfCbgJtvBI3qLXmtZJnTaEtZyoTqw35WE9zxvT8c+pH3ae0hQyc2Zy8AAtLrPY9hza9pdnhI61pdSpTeMltCwsL2GuoYfHGxp8U//AEdqg5S5BlPA13XGS0+Y3/VbI3XNEGt+mYvqp+v8EioNfqKTJz3Qu/8A0abfiFx5yt0a8GVNbQV5T3R1vD8byQ0dbFMMUUjJG8WODh6FtTT3FXUpTpvE4tPvWDYXprNKvohJYiwkbmDxtuPUqy/0dG4xOOycdqf5N1Ks4ZT3M90NEIxxccyf4HUs7GwhbJv/ACe1v8dx5Vqub7jaVgaggCAID5uXXHFouHkr8R+9f6qoNI9d5I6PRfUebJgoBYhAEBraS95k+o79pWur8kvA2Uesj4r7lWaF0b7Jk5oPDHYSW4thItl/zguZt6PTS1c4O1vLr2eCnq5WSW6s6Fno3ufNI1sAabjFkTuOeQ7Va2dtVoSbm9niUOkL2hdRUacXrZ5HjVSobLXVMjPAcLjrGIZ+Xb5VjZzU7mpJbmZaRpyp2dKEt6NTUuoBknpX5skxG3WCWuHlB9CwsJrXnTlueTdpWk1Tp1o71hf7R+a0VYNZBA3wITGLfSLm/oMKxvaideEFuWD3R1Fq1qVXvkn9vyZNbqQTV0cReGYowMR2A3eR5zl5Vle0+kuIxzjYeaMrOjZzqJZw93oZdA6t1NNOHmRrYh4VnGzhbhZZW1nWo1NZy2Gu90jb3FLVUXrcO44Ot1Wyaqe6MgtsG3GwkDMg/wA9ShX1SM6zceBaaKpTpW6Ut+8jbqvmjhMFPIBsL48yOssc3NTaNdTjnCLRW3SLKqTXhL8pnUoK2lfHNJNQRARNZbm3ytxPe7C1u02yxHyKTGUGm3Eg16F1Tqwp068m5Z3pbElvNXTGsLpDGKbnKWGJgY1jZHbbkkkg57tvBYTq5xq7CRZ6MVNTdfE5SectG1oel0hWDEHOMI2vka14/wBIcCXHs86wqVZwhrtNruWSPeTsLV6uFrck2vXD2EvoYWQsEYBkw5XfYdfgtsAOA3LkLi7ouq59Hlvnu9EUlRzqy1m8Z5flm/7Jje0c6DibkMNgMO6/YpPttrcQj7VF6y2fDsWP4I3RVIN9G9j5niolwOLWMa22w+ESNxuepabq4VvUdOlTiscd7xweWZU4a6zKTf0OvoWI4Mbjcu2fVGxdNoSlPoemqPbL7EG6ktbVjuR0VdkYo7XCu56tnkv0Q7AOxnR/gnyqtrSzNn0XRNHobSEeaz6nX0Pot9VSinqQIXA3o3yENc4m5dEGnpGM2ve2W7ctkIOUcS8ituruFtddNQesv80tq8c7snMpdVKt+IvjEEbCQ6SYhjBY2OZzI6wLHitaoye8sKmmLWOFF6ze5R2sxTNo4Mml1bKN5vHAD1AdN/nAKPUj3/YR9suNsv8Arj6y/CLD5NIT7GdO4NBlfkGtDQGM6IAA3XxedS6Hy5OW061G4VKLb1VxeXl7WS9bylMJpmmQS26YaWg5+CTcjhtCx1Fra3Ey15aurw3niSvibIIXSMErs2sLhiI6htWeHjJpdWClqN7eRsrw2GGlnLwSWOZZxbZ2RNja46juWMZa3DBnOOq8ZyZlkYBAfNy644xFw8lniP3r/VVBpHrvJHRaL6jzZMFALEIAgNbSXvMn1HftK11fkl4Gyj1kfFfcp0Lkjv8AGTJJM5ws5znDgST+qyc5NYbZjGnCLykl5HhriNhI7F4m1uPXFS3oA2zGRTL3nrSawzPQ0xmkay9i74R3dZWFSpqrLNNeoqNNyxu4G7VaDmBOYeRltsfT3qPK9ipatTKa5kalfUWtiwak8czRheJMPA4i3uUiNzGawpfU3wlQk8xxk02Nc8lkVnSbQzYXD6B3u+jt4XV5Y6NpV6KqSbXgczpj9QXFpdOhSjF7E9ucnLqZy/IixHnB3gqwWiIU03CTyV9j+ta0biMa8Eo5w8Zyv/Rmlfhpo4xtke6V3Y0GJnp5xRVCTSgltZ3ruaMasrmpJKEYpJ8NvxP/AETTUrUqOWNlVUnnA4YmxjwbXI6fHZs2dqkQttV/HvOfvP1G68cWuyL/AMuPly+5YscYaA1oAAyAGQA4AKSc8228swVNEyTwm58RkfOoN1o63uV/2R28+JnCtOHys5VToVwzYcQ4HI9y5q7/AE7UhtoPK5PeTqd6nsmaradzi1jgWuBw5/FzN/Jn6FDjaVK0oUprEk8bez/BsdWMMyW7f5/ySZjQAANgyXeU4KEVFbkVTeXlniqLgxxYLvwnCOLrZDzrJ7j2GNZa24puqo6ij6QpJGSbTNI0SEHeWYbxszvmbnrUGUZR3I7qjVoXSxKqsdlPV9c4bOHJUPc/nHPc6S98ZJLrg3Buc8itDbzkto0acYaiSUeXA6o1lqHS87O907CCx8bjZjo3ZObhGQPXtuAtnTSzlkCWibdUtSktV70+OeG00dKUgid0CXwvGOJ29zDuP0wei4cR1rGUcPZuJVrcOrD41iUdkl3/AIe9F3av0XMU0MO9rGg/Wtd3pJVlBYikfO7yt01edTm2dBZEYICO1+qUU1Y2tL3hzS1xaLWLmWwm+0bBl1LYqjUdUg1LGE6yqtskS1k4qrXfWKrirXxslfCxmHAG5BwLQcR+Nc3GeWShV6k4ywjstD6OtKtqpzipN5z3dxZOiJ3yQRPlGGRzGucODiATkpkW2k2cncQhCrKMHlJvHgbi9NJ83LrmcWi4eSvxH71/qqg0j13kjo9F9T5smCgFiEAQGtpL3mT6jv2la6vyS8DZR6yPivuU6FyR9ACAIAgOxq5Fd7ncBbz/APhV+kJ4io8yvv5/Cokqhj51th740ZfSbwPWNyUaTvqTiusitj5rk+/kUcpdFLPBmGGBzjZrSePV2lQaNpWqyxCL/vebJVIRW1mCs0dAc5Wse4Z3Fha2/H3edWVCtO1epGq3J7MRezPe3/oiV6FOv8c4LZxe8ilTX0UxtUiR0jBfnorDniCf8ZBF7WsBIczbOy+j041IxRxVSrbTk9fhxXHu/k5uk2smBnp2BjABjiG2KwAvf4bCc8XE5gb8qcFB974i7uqtzTilJ6sVhR5fnxLY1K8Qp/qfyVGqfOy5sf8A68PA7awJYQBAfhC81U3kH6vQEAQHNr9AUs/vsEbjxsAfOM1hKEZb0SqN7cUerm15kbr+TemfnDJJCeGT2+Y5+lapW0XuLaj+ormHzpS+jOfTaiTxuZG58c0AlZIDm1zLEY7NIIIc0WIvtsd2eKoNbOBvq6bpVE5xTjNxa5p8vR8SxlKOYCAIDFUyFrHOAuQCVouqrpUZVEstLJlCOtJJnI0ZpGR8ga44gb7hlldcxorS9zWuVTqPKf0J9xbQhDWR1paVjyHPY1xGwkAkdhOxdbggRnKKwmZl6YhAfNy644xFw8lniP3r/VVBpHrvJHRaL6jzZMFALEIAgNbSXvMn1HftK11fkl4Gyj1kfFfcp0Lkj6AEAQBASHV2aJrDjcMZdfDcDKwAzP8ACg3MYuetNN44L/bKfSEasqnwrZjedw1LssJwgZgNyF+PX5VDlfVk1qPVS4LYv58yv6KPHa+8zur3Pu2Q9A5EAWt9JTPetSu3Cu/hls2bMd/5NXs6gsw3o4+nrshkaciRhy+llceQ3Ue1pyo3ccrOq89zwb1QV3F0s41k13og/tcPjH0dy7P37V7C+pA/4Tbf+SX0PcVJgIe17muGwi3csXp2q18i+plH9GW8XlVZfQuTV+nEdNEwbAwHcPC6Wwbs1axnrpSfErVRVHNNbk2vqdBenoQBAEAQBAEBydaNLOpKd07I+ccCBbOwubXNtwWcI6zwRrqu6NNzSyetW9KOq6dk72c2518s7ZEi4vuNrpOOq8HttWdamptYOosCQEAQBACEayDFFTMYSWtAJ4BR6VpQpScqcUmzOVSUlhsyqQYBAEB83LrjjEXDyV+I/ev9VUGkeu8kdFovqPNkwUAsQgCA19INJikAzJY4egrCptg/A2UnicW+aKoGiqj5CX8Du5cv7PV7LO39sodtepqOaQbHIjI9q1NY2EhNNZR+Lw9NiChlkGJkT3t4taSPOFsjSnJZimzTO4pQeJSSfiZPamo+Qk/A7uWXs9Xssw9sodtepkioKpngxTN7GvCwlaTlvhnyMJXFrL5pRNyJ9a3bFI760bv1FlHlorW3QaI8lZvdNLzPWkTUzMaw00jbG9w15uM8tnWtlOxrQxlN42LYY26t6NRzVRbTm+1VR8hL+B3ct/s9Xssm+2UO2vUHRVR8hL+B3cjt6vZY9sodtepa2jWkQxgixDGAg7jhC6eksQXgjiazTqSa5v7mythqCAIAgCAIAgBCAAIAgCAIAgCAIAgCAID5uXXHFouHkr8R+9f6qoNI9d5I6PRfUebJgoBYhAEAQHM1i0h7Hp3yDwrYW/WdkPNt8ij3VXoqTkSrK36evGHDj4FTlcsdyljYSrVrVN01pagFsW0N2Of28G+kq0tLBz+Opu5FHpDSyp5p0dr58ifRRBgDWgNaMgBkAOpXiSSwjmZScnlntengQEX1k11ioZhDJFI9xaH3bhtYki2Z+ipdCznWjrRaINxfQoT1ZI86u67xVs3MMikY7CXXdhtYW4HrXteznRjrNo8t76FaeqkSpQyeEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQHzcuuOLRcPJX4j96/1VQaR67yR0ei+o82TBQCxCAIAgIFr5WmWZlMy7sOZAzJe7YLdn6qk0lVc5qlE6TQ1FU6Uq89mfsjoatapiO0tQA6TaGbQ3rPF3oC32mj1D46m/kRdIaVlVzTpbI8+ZLVaFKEAQBAVDyrePN+xZ+6RXujOqfic7pTrvIx8lvj4+zf6q90l1XmeaM67yLiVCdGEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQHzcuuOLRcPJX4j96/1VQaR67yR0ei+o82TBQCxCAID8fsNsygOVonQjYXOmf06h5Jc/hf4LeA3KNRtowk5vbJ8SXcXcqsVTWyK3L8nWUkiBAEAQBAVDyrePN+xZ+6RXujOqfic7pTrvIx8lvj4+zf6q90l1XmeaM67yLiVCdGEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQHzcuuOMRcPJX4j96/1VQaR67yR0Wi+o82TBQCxCAIAgCAIAgCAIAgKh5VvHm/Ys/dIr3RnVPxOd0p13kY+S3x8fZv8AVXukuq8zzRnXeRcSoTowgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCA+bl1xxiLh5K/EfvX+qqDSPXeSOi0X1HmyYKAWIQBAEAQBAEAQBAEBUPKt4837Fn7pFe6M6p+JzulOu8jHyW+Pj7N/qr3SXVeZ5ozrvIuJUJ0YQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAfNxXXHFku1X14NDBzAgEnSLsReW7bZWwngq+4semnra2Cxtr90Iaijk6/upu+aj8w/wBFo91/u+hv97vs/Ue6m75qPzD/AEXvur930Hvd9n6j3U3fNR+Yf6J7q/d9B73fZ+o91N3zUfmH+ie6v3fQe932fqPdTd81H5h/onur930Hvd9n6j3U3fNR+Yf6J7q/d9B73fZ+o91N3zUfmH+ie6v3fQe932fqPdTd81H5h/onur930Hvd9n6j3U3fNR+Yf6J7q/d9B73fZ+o91N3zUfmH+i891fu+g97vs/UiWtOnTXzicx82QwMsHYthcb3sPjKfbW/Qw1c52kC6uOnnrYwdfkt8fH2b/VUfSXVeZI0Z13kXEqE6MIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgPm4rrjiwgCAIDLTUz5XYI2OkdtwsaXGw25DNYynGKzJ4MoxcnhLJt+0VX81qPypP6rX7RS7S9TZ7PV7L9B7RVfzWo/Kk/qntFLtL1Hs9Xsv0HtFV/Naj8qT+qe0Uu0vUez1ey/Qe0dX81n/Kk/qvfaKXaXqedBV7L9DnraaggCAICX8lvj4+zf6qr9JdV5ljozrvIuJUJ0YQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAfNxXXHFhAEAQEs5MP/AJBn1H/oq/SPU+ZY6M6/yZcqoTowgCA8S+Cewot5jLcz5wbsXXHHPefqHgQBAS/kt8fH2b/VVfpLqvMsdGdd5FxKhOjCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAID5yqYSx7mHa1xae1pIP6LrIy1kmcbKOq8GNZYMQvAF6DZoK6WnfzkLzG8AjELXsdu1YVKcaixJZRnTqSpvWi8M6f/WFf86k/29y0+xUOyb/ba/aY/wCsK/51J/t7k9iodke21+0x/wBYV/zqT/b3J7FQ7I9tr9pg631/zqT/AG9yex0OyPba/aOGpJFCAIAgJlyUxE1pdubE4nylgCrtJv8A6l4llotZrZ7i31RHRBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEB//9k=" width="100%" style="margin-right: auto!important; margin-left: auto!important; max-width: 250px;">
											
										</div>

										<div class="push10"></div>

										<div class="col-xs-8 col-xs-offset-2 info-header">
											
											<p class="f14"><b>DETALHES DA CAMPANHA</b></p>
											<hr>
											<p class="f14"><b>Campanha</b></p>
											<p class="f14">Teste Dev</p>
											<hr>
											<p class="f14"><b>Canal</b></p>
											<p class="f14">SMS</p>
											<hr>
											<p class="f14"><b>Disparo</b></p>
											<p class="f14"><?=fnDataFull(date("Y-m-d H:i:s"))?></p>
											<hr>
											<p class="f14"><b>Intervalo de Análise</b></p>
											<p class="f14"><?=fnDataShort(date("Y-m-d"))?> à <?=fnDataShort(date("Y-m-d"))?></p>
											<hr>
											<p class="f14"><b>Data de Avaliação</b></p>
											<p class="f14"><?=fnDataShort(date("Y-m-d"))?></p>
											<hr>
											<p class="f14"><b>Total de Clientes</b></p>
											<p class="f14">9.876</p>

										</div>

										<div class="push10"></div>

									</div>

									<div class="col-md-8">

										<div class="push20"></div>

										<div class="row">

											<div class="col-xs-12">

												<div class="panel panel-success caixa-texto">
													<div class="panel-heading">

														<div class="push50"></div>

														<div class="col-xs-6 col-xs-offset-3 red-tooltip" data-html="true" data-toggle='tooltip' data-placement='bottom' data-original-title='
														<div class="row">
															<div class="col-xs-12 text-left">
																<div class="push10"></div>
																<p class="f14" style="margin: 0px!important;"><b>Detalhes do Resultado</b></p>
																<hr style="margin: 5px 0px!important;">
																<p class="f12">Com base no comportamento de compra do Grupo de Controle (clientes que não receberam comunicação), espontaneamente geraria um faturamento de <b>R$ 69.359,85</b></p>
																<div class="push10"></div>
																<p class="f12">Por impacto da comunicação, o faturamento foi de <b>R$112.570,88</b></p>
																<div class="push10"></div>
																<p class="f12"><b>Dessa forma, podemos afirmar que o resultado real da ação foi de R$ 42.847,22</b></p>
																<div class="push10"></div>
															</div>
														</div>
														'>

															<p class="f14">Investimento Total</p>
															<h4><b>R$ 0,00</b></h4>

															<div class="push10"></div>
															<hr>
															<div class="push10"></div>
															<p class="f14">Resultado da Campanha</p>
															<div class="push5"></div>
															<p style="font-size: 48px;"><b><span style="font-size: 32px;">R$</span> 98.765,43</b></p>
															<div class="push5"></div>
															<p class="f14">Faturamento Total R$ 987.654,32</p>
															<div class="push10"></div>
															<hr>
															<div class="push5"></div>
															<p class="f14">Retorno Sobre Investimento</p>
															<p class="f21"><b>00x</b></p>

														</div>


														<div class="push50"></div>

													</div>

												</div>

											</div>

										</div>

										<div class="push20"></div>
										
									</div>

								</div>

								<div class="row">
									
									<div class="col-xs-3">
										
										<div class="panel panel-default">
											<div class="panel-heading"><b>Clientes Impactados</b></div>
											<div class="panel-body">1.234</div>
										</div>

									</div>

									<div class="col-xs-3">

										<div class="panel panel-default">
											<div class="panel-heading"><b>Clientes que Retornaram</b></div>
											<div class="panel-body">999</div>
										</div>

									</div>

									<div class="col-xs-3">

										<div class="panel panel-default">
											<div class="panel-heading"><b>Compras Realizadas</b></div>
											<div class="panel-body">5.432</div>
										</div>

									</div>

									<div class="col-xs-3">

										<div class="panel panel-default">
											<div class="panel-heading"><b>Itens Comprados</b></div>
											<div class="panel-body">9.876</div>
										</div>

									</div>

								</div>

							</div>

							<div class="col-md-4">
								
								<div class="row">
									
									<div class="col-xs-12 info-dash">

										<div class="push20"></div>

										<div class="panel panel-default">
											<div class="panel-heading" style="font-size: 19px;"><b>Taxa de Engajamento nas Lojas</b></div>
											<div class="panel-body">
												
												<div class="col-xs-6">
													
													<div class="row">
														
														<div class="col-xs-12 text-right">
															
															<p class="f18"><b>Grupo de Ação</b></p>
															<p class="f12">Receberam a comunicação</p>
															<p class="f16"><b>9.876 Clientes</b></p>

														</div>

														<div class="col-xs-10 col-xs-offset-2">

															<div class="col-xs-12" style="border: 1px solid #edeeef; border-radius: 10px;">

																<div class="push20"></div>
																<p class="f10">Taxa de Engajamento</p>
																<p class="text-success" style="font-size: 32px;"><b>20,9%</b></p>
																<div class="push20"></div>

															</div>

														</div>

														<div class="push10"></div>

														<div class="col-xs-10 col-xs-offset-2">
															<span class="fas fa-caret-down fa-2x text-success"></span>
														</div>

														<div class="push10"></div>
																
														<div class="col-xs-10 col-xs-offset-2">
															<div class="panel panel-success">
																<div class="panel-heading">
																	<p class="f10">Ticket Médio por Compra</p>
																	<p class="f16"><b>R$ 99,99</b></p>
																</div>
															</div>
														</div>

														<div class="col-xs-10 col-xs-offset-2">
															<div class="panel panel-success">
																<div class="panel-heading">
																	<p class="f10">Gasto Médio p/ Cliente</p>
																	<p class="f16"><b>R$ 99,99</b></p>
																</div>
															</div>
														</div>

														<div class="col-xs-10 col-xs-offset-2">
															<div class="panel panel-success">
																<div class="panel-heading">
																	<p class="f10">VVR (Gasto a Mais por Cashback Resgatado)</p>
																	<p class="f16"><b>1.234% (1.2X mais)</b></p>
																	<p style="font-size: 8px;">Para Cada R$1,00 resgatado, o cliente gastou R$11,00</p>
																</div>
															</div>
														</div>

													</div>

												</div>

												<div class="col-xs-6">
													
													<div class="row">
														
														<div class="col-xs-12 text-left">
															
															<p class="f18"><b>Grupo de Controle</b></p>
															<p class="f12">Não Receberam comunicação</p>
															<p class="f16"><b>6.789 Clientes</b></p>

														</div>

														<div class="col-xs-10">

															<div class="col-xs-12" style="border: 1px solid #edeeef; border-radius: 10px;">

																<div class="push20"></div>
																<p class="f10">Taxa de Engajamento</p>
																<p class="text-primary" style="font-size: 32px;"><b>10,8%</b></p>
																<div class="push20"></div>

															</div>

														</div>

														<div class="push10"></div>

														<div class="col-xs-10">
															<span class="fas fa-caret-down fa-2x text-primary"></span>
														</div>

														<div class="push10"></div>
																
														<div class="col-xs-10">
															<div class="panel panel-primary">
																<div class="panel-heading">
																	<p class="f10">Ticket Médio por Compra</p>
																	<p class="f16"><b>R$ 99,99</b></p>
																</div>
															</div>
														</div>
														<div class="push"></div>

														<div class="col-xs-10">
															<div class="panel panel-primary">
																<div class="panel-heading">
																	<p class="f10">Gasto Médio p/ Cliente</p>
																	<p class="f16"><b>R$ 99,99</b></p>
																</div>
															</div>
														</div>
														<div class="push"></div>

														<div class="col-xs-10">
															<div class="panel panel-primary">
																<div class="panel-heading">
																	<p class="f10">VVR (Gasto a Mais por Cashback Resgatado)</p>
																	<p class="f16"><b>1.234% (1.2X mais)</b></p>
																	<p style="font-size: 8px;">Para Cada R$1,00 resgatado, o cliente gastou R$11,00</p>
																</div>
															</div>
														</div>
														<div class="push"></div>

													</div>

												</div>

											</div>
										</div>

									</div>

								</div>

							</div>
							
						</div>
							
						<div class="push50"></div>									
						
						<div class="push"></div>
						
						</div>								
					
					</div>

			</div>
			<!-- fim Portlet -->
		</div>
		
	</div>
	
	<div class="push20"></div>
	
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />	
	
    <script>
	
		//datas
		$(function () {

			// var numPaginas = <?php echo $numPaginas; ?>;
			// if(numPaginas != 0){
			// 	carregarPaginacao(numPaginas);
			// }
			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				 maxDate : 'now',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
			
			$("#DAT_INI_GRP").on("dp.change", function (e) {
				$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
			});
			
			$("#DAT_FIM_GRP").on("dp.change", function (e) {
				$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
			});
				

		});	

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "relatorios/ajxRelLogUsuarios.do?idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
				data: $('#formulario').serialize(),
				beforeSend:function(){
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#relatorioConteudo").html(data);										
				},
				error:function(data){
					console.log(data);
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});		
		}	
		
	</script>	
   