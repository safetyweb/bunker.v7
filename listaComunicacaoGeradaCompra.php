<?php
	
	//echo fnDebug('true');
	
	// definir o numero de itens por pagina
	$itens_por_pagina = 100;
	
	// Página default
	$pagina = 1;
	
	$dias30="";
	$dat_ini="";
	$dat_fim="";
	$hashLocal = mt_rand();	
	
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
	$cod_univend = "9999"; //todas revendas - default
	
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
			$cod_univend = $_POST['COD_UNIVEND'];
			$dat_ini = fnDataSql($_POST['DAT_INI']);
			$dat_fim = fnDataSql($_POST['DAT_FIM']);
			$cod_vendapdv = $_POST['COD_VENDAPDV'];

			
			
			
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
	if (strlen($cod_univend ) == 0){
		$cod_univend = "9999"; 
	}	
	
	//busca revendas do usuário
	include "unidadesAutorizadas.php"; 
	
	
	//fnEscreve($_SESSION["SYS_COD_HOME"]);
	//fnEscreve($_SESSION["SYS_PAG_HOME"]);	
	
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
table a:not(.btn), .table a:not(.btn) {
    text-decoration: none;
}
table a:not(.btn):hover, .table a:not(.btn):hover {
    text-decoration: underline;
}
.rounded-shadow{
	--webkit-box-shadow: 10px 10px 5px -5px rgba(0,0,0,0.05);
	-moz-box-shadow: 10px 10px 5px -5px rgba(0,0,0,0.05);
	box-shadow: 10px 10px 5px -5px rgba(0,0,0,0.05);
	border-radius: 11px 11px 11px 11px;
	-moz-border-radius: 11px 11px 11px 11px;
	-webkit-border-radius: 11px 11px 11px 11px;
	border: 0px solid #000000;
}
</style>
		
	<div class="push30"></div> 
	
	<div class="row" id="div_Report">				
	
		<div class="col-md-12">
			<!-- Portlet -->
			<div class="portlet portlet-bordered margin-bottom-30 rounded-shadow">
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
									
										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label required">Empresa</label>
												<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
												<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
											</div>														
										</div>					
										
										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">Data Inicial</label>
												
												<div class="input-group date datePicker" id="DAT_INI_GRP">
													<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required/>
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
												<div class="help-block with-errors"></div>
											</div>
										</div>
										
										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">Data Final</label>
												
												<div class="input-group date datePicker" id="DAT_FIM_GRP">
													<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required/>
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
						
							<div class="push20"></div>

						</form>
						
					</div>								
				
				</div>
			</div>
			<!-- fim Portlet -->
		</div>
	</div>

	<div class="row">

		<div class="col-md-6">

			<div class="row">
		
				<div class="col-md-6">							
				
					<div class="portlet portlet-bordered margin-bottom-30 rounded-shadow">
					
						<div class="portlet-body">						
						
						<div class="push100"></div>								
						<div class="push100"></div>							
							
						</div>
					</div>
					
				</div>	

				<div class="col-md-6">							
				
					<div class="portlet portlet-bordered margin-bottom-30 rounded-shadow">
					
						<div class="portlet-body">						
						
						<div class="push100"></div>								
						<div class="push100"></div>							
							
						</div>
					</div>
					
				</div>		

				<div class="col-md-12">							
				
					<div class="portlet portlet-bordered margin-bottom-30 rounded-shadow">
					
						<div class="portlet-body">						
						
						<div class="push100"></div>								
						<div class="push100"></div>							
							
						</div>
					</div>
					
				</div>

			</div>

		</div>

		<div class="col-md-6">

			<div class="row">

				<div class="col-md-12">							
					
					<div class="portlet portlet-bordered margin-bottom-30 rounded-shadow">
					
						<div class="portlet-body">						
						
							<div class="push100"></div>								
							<div class="push100"></div>
							<div class="push100"></div>								
							<div class="push100"></div>
							<div class="push50"></div>
							<div class="push20"></div>

							<!-- <div class="push30"></div>						 -->
							
						</div>
					</div>
					
				</div>

			</div>

		</div>

	</div>

	<div class="row">
		
		<div class="col-md-3">							
				
			<div class="portlet portlet-bordered margin-bottom-30 rounded-shadow">
			
				<div class="portlet-body">						
				
				<div class="push100"></div>								
				<div class="push100"></div>							
					
				</div>
			</div>
			
		</div>

		<div class="col-md-3">							
				
			<div class="portlet portlet-bordered margin-bottom-30 rounded-shadow">
			
				<div class="portlet-body">						
				
				<div class="push100"></div>								
				<div class="push100"></div>							
					
				</div>
			</div>
			
		</div>

		<div class="col-md-3">							
				
			<div class="portlet portlet-bordered margin-bottom-30 rounded-shadow">
			
				<div class="portlet-body">						
				
				<div class="push100"></div>								
				<div class="push100"></div>							
					
				</div>
			</div>
			
		</div>

		<div class="col-md-3">							
				
			<div class="portlet portlet-bordered margin-bottom-30 rounded-shadow">
			
				<div class="portlet-body">						
				
				<div class="push100"></div>								
				<div class="push100"></div>							
					
				</div>
			</div>
			
		</div>

	</div>
	
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />	
	
    <script>
	
		//datas
		$(function () {
			
			var numPaginas = <?php echo $numPaginas; ?>;
			if(numPaginas != 0){
				carregarPaginacao(numPaginas);
			}			
			
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
				url: "ajxListaComunicacaoGeradaCompra.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
				data: $('#formulario').serialize(),
				beforeSend:function(){
					$('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
				},
				success:function(data){
					$("#relatorioConteudo").html(data);										
				},
				error:function(){
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});		
		}	

		
	</script>	
   