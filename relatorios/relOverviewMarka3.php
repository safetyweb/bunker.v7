<?php
	
	//echo fnDebug('true');

	$hashLocal = mt_rand();	
	
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
	   
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			if ($opcao != ''){

				
			}

		}
	}

		
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$cod_empresa = 69;	
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI, DAT_CADASTR FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
			$dat_cadastr = $qrBuscaEmpresa['DAT_CADASTR'];
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}
	
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	//$dias30 = fnFormatDate($valorDataPrimeiraVenda['primeira_venda']);
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
	//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
	
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}	
	
	//fnMostraForm();
	//fnEscreve($qtd_univendUsu);
	//fnEscreve($lojas);
	//fnEscreve($lojasAut);
	//fnEscreve($cod_univend);
	//fnEscreve($lojasSelecionadas);
	
	$hor_ini = " 00:00";
	$hor_fim = " 23:59";
	
	$cod_empresa = 69;	
	
	
?>

	<style>
	table a:not(.btn), .table a:not(.btn) {
		text-decoration: none;
	}
	table a:not(.btn):hover, .table a:not(.btn):hover {
		text-decoration: underline;
	}
	</style>
			
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg; ?></span>
									</div>
									<?php 
									include "backReport.php"; 
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
															<label for="inputName" class="control-label required">Empresa</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="Marka Fidelização">
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
										
										<div class="push50"></div>
										
										<div class="row text-center">											
														
											<div class="form-group text-center col-lg-12">
												<h4>Tipo de Campanha Principal</h4>
												<div class="push20"></div>
												
												<table class="table table-bordered table-striped table-hover">
												  <thead>
													<tr>
													  <th>Cod.</th>
													  <th>Nome da Empresa</th>
													  <th class="text-center">Campanha Principal</th>
													  <th class="text-center">Campanha Empresa</th>
													  <th class="text-center">Casas Dec.</th>
													</tr>
												  </thead>
												<tbody id="div_refreshDesafio">											
												
												
												<?php 
												
													if ($_SESSION["SYS_COD_MASTER"] == "2" ) {
													$sql = "SELECT 
															STATUSSISTEMA.DES_STATUS,
															empresas.NOM_FANTASI,
															empresas.COD_EMPRESA, 
															empresas.TIP_CAMPANHA, 
															empresas.TIP_RETORNO, 
															(select NOM_USUARIO from webtools.usuarios where cod_empresa=3 and cod_usuario=empresas.cod_consultor) as NOM_CONSULTOR, 
															(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA) AS LOJAS,
															(SELECT D.NOM_FANTASI FROM EMPRESAS D WHERE D.COD_EMPRESA=empresas.COD_INTEGRADORA  ) NOM_INTEGRADORA,
															B.COD_DATABASE, 
															B.NOM_DATABASE 
															FROM empresas 
															LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=empresas.COD_STATUS 
															LEFT JOIN tab_database B ON B.cod_empresa=empresas.COD_EMPRESA
															
															WHERE empresas.COD_EMPRESA <> 1
															
															AND empresas.LOG_ATIVO = 'S'
															
															ORDER by NOM_FANTASI
													";
													
													}
													
													//fnEscreve($sql);
													
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													//fnTestesql($connAdm->connAdm(),$sql);
													
													$count=0;
													$totLOjas=0;
													
													while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;
														$empBusca = $qrListaEmpresas['COD_EMPRESA'];
														$empCampanha = $qrListaEmpresas['TIP_CAMPANHA'];
														$tip_retorno = $qrListaEmpresas['TIP_RETORNO'];
														
														//tipo de campanha principal
														$sql = " SELECT distinct(CAMPANHA.TIP_CAMPANHA) FROM CAMPANHA WHERE CAMPANHA.COD_EMPRESA=$empBusca
																  ";														
														//fnEscreve($sql);
														
														$arrayQueryEmp = mysqli_query(connTemp($empBusca,''),$sql) or die(mysqli_error());
														$qrBuscaDetalheEmpresa = mysqli_fetch_assoc($arrayQueryEmp);
														
														$tip_campanha = $qrBuscaDetalheEmpresa['TIP_CAMPANHA'];

														//tipo de campanha principal
														//$sqlUpd = " UPDATE empresas SET tip_campanha=$tip_campanha WHERE cod_empresa=$empBusca ";
														//fnEscreve($sqlUpd);
														//$arrayQuery = mysqli_query($connAdm->connAdm(),$sqlUpd) or die(mysqli_error());
														//$qrBuscaUpdate = mysqli_fetch_assoc($arrayQueryUpd);
														
														?> 

														<tr>
														  <td class="text-center"><small><?php echo $empBusca; ?></td>
														  <td><?php echo $qrListaEmpresas['NOM_FANTASI']; ?></td>
														  <td class="text-center"><small><?php echo $tip_campanha; ?></td>
														  <td class="text-center"><small><?php echo $empCampanha; ?></td>
														  <td class="text-center"><small><?php echo $tip_retorno; ?></td>
														</tr>														
														
														<?php			
														  }
														?> 

													
												</tbody>
												</table>												
										
											</div>
											
										</div>										
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />							
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
										
										</form>
										
										<div class="push50"></div>
										
					</div>					
						
					<div class="push20"></div> 
			
	
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />				

	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>    
    <script src="js/plugins/Chart_Js/utils.js"></script>
    	
    <script>
	
		//datas
		$(function () {
			
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
			
			$(".exportarCSV").click(function() {
				$.confirm({
					title: 'Exportação',
					content: '' +
					'<form action="" class="formName">' +
					'<div class="form-group">' +
					'<label>Insira o nome do arquivo:</label>' +
					'<input type="text" placeholder="Nome" class="nome form-control" required />' +				
					'</div>' +
					'</form>',
					buttons: {
						formSubmit: {
							text: 'Gerar',
							btnClass: 'btn-blue',
							action: function () {
								var nome = this.$content.find('.nome').val();
								if(!nome){
									$.alert('Por favor, insira um nome');
									return false;
								}
								
								$.confirm({
									title: 'Mensagem',
									type: 'green',
									icon: 'fa fa-check-square-o',
									content: function(){
										var self = this;
										return $.ajax({
											url: "relatorios/ajxRelCadastrosRT.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>&LOJAS=<?php echo $lojasSelecionadas; ?>", 
											data: $('#formulario').serialize(),
											method: 'POST'
										}).done(function (response) {
											self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
											var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
											SaveToDisk('media/excel/' + fileName, fileName);
											console.log(response);
										}).fail(function(){
											self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
										});
									},							
									buttons: {
										fechar: function () {
											//close
										}									
									}
								});								
							}
						},
						cancelar: function () {
							//close
						},
					}
				});				
			});			
		});	
		
		//graficos
        $(document).ready( function() {
			
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			
            $('#main-pie').pieChart({
                barColor: '#2c3e50',
                trackColor: '#eee',
                lineCap: 'round',
                lineWidth: 8,
                onStep: function (from, to, percent) {
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });	
			
        });

	</script>	
   