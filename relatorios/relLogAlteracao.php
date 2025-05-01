<?php
	
	//echo fnDebug('true');
	//fnMostraForm();
	// definir o numero de itens por pagina
	$itens_por_pagina = 200;
	$pagina = 1;
	
	$dias30="";
	$dat_ini="";
	$dat_fim="";
	$numCartao = "";
	$nomCliente = "";
	$cod_vendapdv = "";
	$tipoVenda = "T";
	$hashLocal = mt_rand();	
	
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	$dias30 = fnFormatDate(date("Y-m-d", strtotime($dias30. '- 1 day')));
	
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
			
			$cod_empresa_pg = fnLimpaCampo($_POST['COD_EMPRESA_PG']);
			$cod_modulos = fnLimpaCampo($_POST['COD_MODULOS']);
			$url = $_POST['URL'];
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
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_CLIENTE_AV, TIP_RETORNO FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
			$cod_cliente_av = $qrBuscaEmpresa['COD_CLIENTE_AV'];
			$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];

			if($tip_retorno == 1){
				$casasDec = 0;
			}else{
				$casasDec = 2;
			}
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
		
	if ($tipoVenda == "T"){
		$checkTodas = "checked"; 
		$checkCreditos = ""; 
	}else{
		$checkTodas = ""; 
		$checkCreditos = "checked"; 
	}	
	
	//busca revendas do usuário
	include "unidadesAutorizadas.php"; 
	
	//fnMostraForm();	
	//fnEscreve($dat_ini);
	//fnEscreve($cod_univendUsu);
	//fnEscreve($qtd_univendUsu);
	//fnEscreve($usuReportAdm);
	//fnEscreve($tipoVenda);
  
?>

<style>
table a:not(.btn), .table a:not(.btn) {
    text-decoration: none;
}
table a:not(.btn):hover, .table a:not(.btn):hover {
    text-decoration: underline;
}


#blocker
{
	display:none; 
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	opacity: .8;
	background-color: #fff;
	z-index: 1000;
}
	
#blocker div
{
	position: absolute;
	top: 30%;
	left: 48%;
	width: 200px;
	height: 2em;
	margin: -1em 0 0 -2.5em;
	color: #000;
	font-weight: bold;
}
</style>

    <div id="blocker">
	   <div style="text-align: center;"><img src="../../images/loading2.gif"><br/> Aguarde. Processando... ;-)</div>
	</div>

	<div class="push30"></div> 
	
	<div class="row" id="div_Report">				
	
		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="glyphicon glyphicon-calendar"></i>
						<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
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
						<?php /*
							<h4>
							- coloca a url da tela desejada <br/>
							- pega módulo e empresa pro filtro de baixo <br/>
							- carrgar usuários com ajax <br/>
							</h4>
							*/?>
							
							<fieldset>
								<legend>Url do Cliente</legend> 
								
									<div class="row">
									
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputName" class="control-label">Url</label>
												<input type="url" class="form-control input-sm" name="URL" id="URL" value="<?=@$url?>">
											</div>														 
										</div>
										
										<div class="col-md-2">
											<div class="push20"></div>
											<button class="btn btn-primary btn-sm" onClick="validaURL();return false;"><i class="fal fa-brackets-curly" aria-hidden="true"></i>&nbsp; Validar Url</button>
										</div>									
										
									</div>
										
							</fieldset>	
							
							<div class="push20"></div>
							<?php /*
							<h4>
							- sempre data obrigatório pra limitar <br/>
							- pega módulo e empresa pro filtro de baixo <br/>
							</h4>
							*/?>
							<fieldset>
								<legend>Filtros</legend> 
								
									<div class="row">
										
										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label r_equired">Data Inicial</label>
												
												<div class="input-group date datePicker" id="DAT_INI_GRP">
													<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" r_equired/>
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
												<div class="help-block with-errors"></div>
											</div>
										</div>
										
										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label r_equired">Data Final</label>
												
												<div class="input-group date datePicker" id="DAT_FIM_GRP">
													<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" r_equired/>
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
												<div class="help-block with-errors"></div>
											</div>
										</div>	
										
										<div class="push10"></div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label r_equired">Empresa</label>
																											
													<select data-placeholder="Selecione uma empresa" name="COD_EMPRESA_PG" id="COD_EMPRESA_PG" class="chosen-select-deselect requiredChk" r_equired="required" >
														<option value=""></option>					
														<?php																	
															
															if ($_SESSION["SYS_COD_MASTER"] == 2 ) {
																$sql = "select A.COD_EMPRESA, A.NOM_FANTASI, 
																(select count(B.COD_DATABASE) FROM tab_database B where B.COD_EMPRESA = A.COD_EMPRESA) as COD_DATABASE   
																from empresas A where A.cod_empresa <> 1 and A.cod_exclusa = 0 order by A.NOM_FANTASI 
																";
																																		  
															}else {
																$sql = "select A.COD_EMPRESA, A.NOM_FANTASI, 
																(select count(B.COD_DATABASE) FROM tab_database B where B.COD_EMPRESA = A.COD_EMPRESA) as COD_DATABASE   
																from empresas A where A.COD_EMPRESA IN (1,".$_SESSION["SYS_COD_MULTEMP"].") and A.cod_exclusa = 0 order by A.NOM_FANTASI 
																";
															}																	
															
															$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
																														
															while ($qrListaEmpresa = mysqli_fetch_assoc($arrayQuery))
															  {													
																if ((int)$qrListaEmpresa['COD_DATABASE'] == 0){ $desabilitado = "disabled";}
																else {$desabilitado = "";}
																
																echo"
																	  <option value='".fnEncode($qrListaEmpresa['COD_EMPRESA'])."' ".$desabilitado.">".$qrListaEmpresa['NOM_FANTASI']."</option> 
																	"; 
															  }											
														?>	
													</select>
																							 
													<script>
													$(document).ready(function(){
														$("#formulario #COD_EMPRESA_PG").val("<?=@$cod_empresa_pg?>").trigger("chosen:updated");
													});
													</script>	
													<div class="help-block with-errors"></div>																
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label r_equired">Módulo</label>
																											
													<select data-placeholder="Selecione um módulo" name="COD_MODULOS" id="COD_MODULOS" class="chosen-select-deselect r_equiredChk" r_equired="required" >
														<option value=""></option>					
														<?php																	
															
															$sql = "SELECT COD_MODULOS,NOM_MODULOS FROM modulos ORDER BY DES_MODULOS";
															$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
															while ($qrLista = mysqli_fetch_assoc($arrayQuery)){
																echo"<option value='".fnEncode($qrLista['COD_MODULOS'])."'>".$qrLista['COD_MODULOS']."-".$qrLista['NOM_MODULOS']."</option>"; 
															  }											
														?>	
													</select>

													<script>
													$(document).ready(function(){
														$("#formulario #COD_MODULOS").val("<?=@$cod_modulos?>").trigger("chosen:updated");
													});
													</script>
													<div class="help-block with-errors"></div>																
											</div>
										</div>

										<?php /*
										<div class="col-md-3">
											<label for="inputName" class="control-label required">Módulo</label>
											<div class="input-group">
											<span class="input-group-btn">
											<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1477)?>&id=<?php echo fnEncode($cod_modulos)?>&pop=true" data-title="Busca Categoria"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;" ></i></a>
											</span>
											<input type="text" name="NOM_MODULOS" id="NOM_MODULOS" value="" maxlength="50" class="form-control input-sm" readonly style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
											<input type="hidden"name="COD_MODULOS" id="COD_MODULOS" value="">
											</div>
											<div class="help-block with-errors"></div>                                                      
										</div>
										*/?>
										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label">Usuário</label>
																											
													<select data-placeholder="Selecione um usuário" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect" >
														<option value=""></option>					
													</select>
																							 
													<script>$("#formulario #COD_USUARIO").val("<?php echo $cod_usuario; ?>").trigger("chosen:updated"); </script>	
													<div class="help-block with-errors"></div>																
											</div>
										
										</div>
										
										<div class="col-md-2">
											<div class="push20"></div>
											<button type="submit" onClick="buscaHistorico();return false;" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-history" aria-hidden="true"></i>&nbsp; Buscar Histórico</button>
										</div>									
										
									</div>
									
									<div class="push10"></div>
										
							</fieldset>	
							
							<div class="push20"></div>
							
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />							
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
							<input type="hidden" name="ITENS_POR_PAGINA" id="ITENS_POR_PAGINA" value="<?php echo $itens_por_pagina; ?>">
							<input type="hidden" name="FORM_DATA" id="FORM_DATA" value="<?php echo $pagina; ?>">
							
							<div class="push5"></div> 
						
						</form>
						<input type="hidden" name="PAGINA" id="PAGINA" value="0">
						<input type="hidden" name="ID_MIN" id="ID_MIN" value="0">
					</div>
				</div>
			</div>
									
			<div class="push30"></div> 
		
			<div class="portlet portlet-bordered">
				<div class="portlet-body">

					<div class="login-form">
						<div class="row">
							<div class="col-md-12" id="div_Produtos">
							
								<table id="table" class="table table-bordered table-hover tablesorter">
								
								  <thead>
									<tr>
									  <th class="{sorter:false}"></th>
									  <th><small>ID</small></th>
									  <th><small>Data/Hora</small></th>
									  <th><small>Usuário</small></th>
									  <th><small>Empresa</small></th>
									  <th><small>Módulo</small></th>
									  <?php /*
									  <th class="{sorter:false}"></th>
									  */ ?>
									</tr>
								  </thead>
								  
									<tbody id="relatorioConteudo">
					
									</tbody>

									<tfoot>
									
										<tr>
											<th colspan="100">
												<?php
												/*<a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>*/
												?>
											</th>
										</tr>														
										<tr>
										  <th class="" colspan="100">
											<center><ul id="paginacao" class="pagination-sm"></ul></center>
										  </th>
										</tr>
									</tfoot>
									
								</table>
																				
							</div>
							
							<?php
							
							function fullDateDiff($date1, $date2)
							{		
								$date1=strtotime($date1);
								$date2=strtotime($date2); 
								$diff = abs($date1 - $date2);
								
								$day = $diff/(60*60*24); // in day
								$dayFix = floor($day);
								$dayPen = $day - $dayFix;
								if($dayPen > 0)
								{
									$hour = $dayPen*(24); // in hour (1 day = 24 hour)
									$hourFix = floor($hour);
									$hourPen = $hour - $hourFix;
									if($hourPen > 0)
									{
										$min = $hourPen*(60); // in hour (1 hour = 60 min)
										$minFix = floor($min);
										$minPen = $min - $minFix;
										if($minPen > 0)
										{
											$sec = $minPen*(60); // in sec (1 min = 60 sec)
											$secFix = floor($sec);
										}
									}
								}
								$str = "";
								if($dayFix > 0)
									$str.= $dayFix."d ";
								if($hourFix > 0)
									$str.= $hourFix."h ";
								if($minFix > 0)
									$str.= $minFix."m ";
								if($secFix > 0)
									$str.= $secFix."s ";
								return $str;
							}
															
								//fnEscreve($vendaIni);
								//fnEscreve(fnDataFull($vendaIni));
								//fnEscreve(fnFormatDateTime($vendaIni));
								//fnEscreve($vendaFim);
								//fnEscreve(fnDataFull($vendaFim));
								//fnEscreve(fullDateDiff($vendaIni, $vendaFim));
								//fnEscreve(fnValor($totalVenda,2));
								//fnEscreve(fnValor($totalVenda,2));
								
								//$to_time = strtotime("2008-12-13 10:42:00");
								//$from_time = strtotime("2008-12-13 10:21:00");
								//fnEscreve(round(abs($vendaFim - $vendaini) / 60,2). " minute");									
								
															
							?>
							
							
						</div>
					</div>
						
					<div class="push50"></div>									
					
					<div class="push"></div>
					
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
		var loading = false;
		$(window).scroll(function() {
			if(($(window).scrollTop() + $(".navbar-fixed-left").height()) >= ($(document).height()-1200)) {
				   carregaMais();
			}
		});
		//datas
		$(function () {

			$.tablesorter.addParser({ id: "moeda", is: function(s) { return true; }, format: function(s) { return $.tablesorter.formatFloat(s.replace(new RegExp(/[^0-9,]/g),"")); }, type: "numeric" });
			
			var numPaginas = 0<?=@$numPaginas; ?>;
			if(numPaginas != 0){
				carregarPaginacao(numPaginas);
			}			
			
			// $('#DAT_FIM_GRP').datetimepicker({
			// 	 format: 'DD/MM/YYYY',
			// 	 maxDate : '<?=fnDataShort($hoje)?>',
			// 	}).on('changeDate', function(e){
			// 		$(this).datetimepicker('hide');
			// 	});
				
			$('#DAT_INI_GRP, #DAT_FIM_GRP').datetimepicker({
				 format: 'DD/MM/YYYY'
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});

			// $('#DAT_INI_GRP').datetimepicker({
			// 	 format: 'DD/MM/YYYY'
			// 	}).on('changeDate', function(e){
			// 		$(this).datetimepicker('hide');
			// 	});
			
			$("#DAT_INI_GRP").on("dp.change", function (e) {
				$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
			});
			
			$("#DAT_FIM_GRP").on("dp.change", function (e) {
				$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
			});
			
			$("#COD_EMPRESA_PG").change(function(){
				carregaComboUsuarios(this.value);
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
											url: "relatorios/ajxRelVendasAvulsas.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>&condicaoVendaPDV=<?php echo $condicaoVendaPDV; ?>",
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

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "relatorios/ajxRelLogAlteracao.php?PAGINA="+$("#PAGINA").val()+"&ID_MIN="+$("#ID_MIN").val(),
				data: $('#formulario').serialize(),
				beforeSend:function(){
					$('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
				},
				success:function(data){
					$("#relatorioConteudo").html(data);
					$(".tablesorter").trigger("updateAll");										
				},
				error:function(){
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});		
		}	

		function abreDetail(idBloco){
			var idItem = $('.abreDetail_' + idBloco)
			if (!idItem.is(':visible')){
				idItem.show();
				$('#bloco_'+idBloco).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');
			}else{
				idItem.hide();
				$('#bloco_'+idBloco).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
			}
		}


		function validaURL(){
			$.ajax({
				type: "POST",
				url: "relatorios/ajxValidaURL.do",
				data: $("#URL").serialize(),
				dataType: 'json',
				success:function(data){
					console.log(data);
					if (data.cod_empresa != '' && data.cod_empresa != undefined){
						$("#COD_EMPRESA_PG").val(data.url_params.id).trigger("chosen:updated").change();
					}
					if (data.cod_modulo != '' && data.cod_modulo != undefined){
						$("#COD_MODULOS").val(data.url_params.mod).trigger("chosen:updated").change();
					}
				}
			});		
		}

		function buscaHistorico(){
			loading = true;
			$("#blocker").show();
			$("#PAGINA").val(1);
			$("#ID_MIN").val(0);
			$("#FORM_DATA").val($("#formulario").serialize());
			carregaDados($("#FORM_DATA").val(),true);
		}
		function carregaMais(){
			if (loading){
				return false;
			}
			if ($("#PAGINA").val() <= 0){
				return false;
			}
			loading = true;
			//$("#blocker").show();
			$("#PAGINA").val((parseInt($("#PAGINA").val())+1));
			carregaDados($("#FORM_DATA").val(),false);
		}
		function carregaDados(data,limpa){
			$.ajax({
				type: "POST",
				url: "relatorios/ajxRelLogAlteracao.php?PAGINA="+$("#PAGINA").val()+"&ID_MIN="+$("#ID_MIN").val(),
				data: data,
				success:function(data){
					loading = false;
					if (data == ""){
						$("#PAGINA").val(0);
					}
					if (limpa){
						$("#relatorioConteudo").html(data);
					}else{
						$("#relatorioConteudo").html($("#relatorioConteudo").html()+data);
					}
					$("#blocker").hide();
				}
			});	
		}
		function carregaComboUsuarios(id_empresa){
			$.ajax({
				type: "POST",
				url: "relatorios/ajxRelLogAlteracao.php?acao=usuarios",
				data: {COD_EMPRESA_PG:id_empresa},
				success:function(data){
					$("#COD_USUARIO").html(data).trigger("chosen:updated");
				}
			});	
		}

		function abreDetail(idBloco){
			var idItem = $('.abreDetail_' + idBloco)
			if (!idItem.is(':visible')){
				idItem.show();
				$('#bloco_'+idBloco).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');
			}else{
				idItem.hide();
				$('#bloco_'+idBloco).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
			}
		}
	</script>	
   