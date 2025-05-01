<?php
	
	//echo fnDebug('true');
	//fnMostraForm();
	// definir o numero de itens por pagina
	$itens_por_pagina = 50;
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
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
	//$dias30 = fnFormatDate(date("Y-m-d"));
	//$cod_univend = "9999"; //todas revendas - default
	
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
			$cod_grupotr = $_REQUEST['COD_GRUPOTR'];	
			$cod_tiporeg = $_REQUEST['COD_TIPOREG'];
			$dat_ini = fnDataSql($_POST['DAT_INI']);
			$dat_fim = fnDataSql($_POST['DAT_FIM']);
			$numCartao = $_POST['NUM_CARTAO'];
			$nomCliente = $_POST['NOM_CLIENTE'];
			$cod_vendapdv = $_POST['COD_VENDAPDV'];
			$tipoVenda = $_POST['tipoVenda'];
			$tip_ordenac = fnLimpaCampoZero($_POST['TIP_ORDENAC']);
			
			//fixo nos testes
			if (empty($_REQUEST['LOG_BLOQUEIA'])) {$log_bloqueia='N';}else{$log_bloqueia=$_REQUEST['LOG_BLOQUEIA'];}
			//$log_bloqueia='N';

			if (empty($_REQUEST['LOG_CLEARTKN'])) {$log_cleartkn='N';}else{$log_cleartkn=$_REQUEST['LOG_CLEARTKN'];}
			
			$dat_filtro = fnLimpaCampo($_POST['DAT_FIM']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao == 'CAD'){

				$sql1 = "CALL SP_PENDENCIA_HISTORICO(
							'".$cod_empresa."',
							'".$cod_univend."',
							'".$_SESSION["SYS_COD_USUARIO"]."',    
							'".$log_bloqueia."',
							'".fnDataSql($dat_filtro)."',   
							'".$log_cleartkn."'
					);";

				mysqli_query(connTemp($cod_empresa, ''), $sql1);
 				
				//fnEscreve($sql1);
				//echo($sql1);
				
				//mensagem de retorno
				switch ($opcao) {

					case 'CAD':

						$msgRetorno = "Pendências geradas com <strong>sucesso!</strong>";

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
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_CLIENTE_AV, TIP_RETORNO, LOG_CADTOKEN FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
			$cod_cliente_av = $qrBuscaEmpresa['COD_CLIENTE_AV'];
			$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];
			$log_cadtoken = $qrBuscaEmpresa['LOG_CADTOKEN'];

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
	//fnEscreve($lojasSelecionadas);
	//fnEscreve($cod_univendUsu);
	//fnEscreve($qtd_univendUsu);
	//fnEscreve($lojasAut);
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
</style>
		
	<div class="push30"></div> 
	
	<div class="row" id="div_Report">				
	
		<div class="col-md12 margin-bottom-30">

			<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="fal fa-terminal"></i>
						<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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
					
						
													
							<fieldset>
								<legend>Filtros para Pendenciamento</legend> 
								
									<div class="row">
										
										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">Data de Cadastro Até</label>
												
												<div class="input-group date datePicker" id="DAT_FIM_GRP">
													<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required/>
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
												<div class="help-block with-errors"></div>
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label required">Unidade de Atendimento</label>
												<?php include "unidadesAutorizadasComboMulti.php"; ?>
											</div>
										</div>
										
										<div class="col-md-2">
											<div class="push20"></div>
											<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-cogs" aria-hidden="true"></i>&nbsp; Filtrar Massa para Pendenciamento</button>
										</div>									
										
									</div>
										
							</fieldset>	
						
							<div class="push20"></div>
						
							<div class="row">
								<div class="col-md-12">

									<div class="push20"></div>
									
									<?php
										if($nomCliente == ""){
											$andNome = " ";
										}else {
											$andNome = "AND NOM_CLIENTE LIKE '%".$nomCliente."%' ";
										}
									
										if($numCartao == ""){
											$condicaoCartao = " ";
										}else {
											$condicaoCartao = "AND B.NUM_CARTAO = $numCartao ";
										}
										
										if($dat_fim == date('Y-m-d')){
											$andDataRetro = " ";
										}else {
											$andDataRetro = "AND A.DAT_CADASTR < NOW() ";
										}

										// Filtro por Grupo de Lojas
										include "filtroGrupoLojas.php";
										
										$sql = "SELECT (
												SELECT COUNT(COD_CLIENTE)
												FROM CLIENTES
												WHERE cod_empresa = $cod_empresa
												AND COD_UNIVEND IN($lojasSelecionadas)) AS QTD_TOTAL, 

												COUNT(COD_CLIENTE) QTD_TOTAL_NAO_ACEITE
												FROM CLIENTES
												WHERE cod_empresa = $cod_empresa AND 
													  DAT_CADASTR <= '$dat_fim 23:59:59' 
													  AND log_avulso='N' 
													  AND log_termo='N'
													  AND COD_UNIVEND IN($lojasSelecionadas)
										";
												   
										//fnEscreve($sql);
										$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
										$totalitens_por_pagina = mysqli_fetch_assoc($retorno);
										$numPaginas = ceil($totalitens_por_pagina['QTD_TOTAL_NAO_ACEITE']/$itens_por_pagina);
										
										$qtd_total = $totalitens_por_pagina['QTD_TOTAL'];
										//fnEscreve($totalitens_por_pagina['QTD_TOTAL']);
										$qtd_total_nao_aceite = $totalitens_por_pagina['QTD_TOTAL_NAO_ACEITE'];
										$pct_qtd_total_nao_aceite = ($qtd_total_nao_aceite*100)/$qtd_total;

										
									?>
								</div>
							</div>
						
						<div class="push5"></div> 
						
					</div>
					
				</div>
				
			</div>
									
			<div class="push20"></div> 
			
			<?php
			if ($opcao == 'ALT'){
			?>

			<div class="row">
	
				<div class="col-md-12 col-lg-12 margin-bottom-30">
					<!-- Portlet -->
					<div class="portlet portlet-bordered">
					
						<div class="portlet-body">	
						
							<div class="row text-center">
						
								<style>
									.shadow2 {
										padding: 15px 0 10px 0;										
									}											
								</style>
						
								<div class="col-md-4">
									<div class="shadow2">											
										<div class="col-md-8 top-content">
											<p>Total Geral de Cadastros</p>
											<label><?php echo fnValor($qtd_total,0); ?></label>
										</div>
										<div class="col-md-4">	   
											<div id="main-pie" class="pie-title-center" data-percent="100">
												<span class="pie-value">100%</span>
											</div>
										</div>
										<div class="clearfix"> </div>
									</div>	
								</div>	

								<div class="col-md-4">
									<div class="shadow2">											
										<div class="col-md-8 top-content">
											<p>Clientes Não Conforme</p>
											<label><?php echo fnValor($qtd_total_nao_aceite,0); ?></label>
										</div>
										<div class="col-md-4">	   
											<div id="main-pie2" class="pie-title-center" data-percent="<?php echo fnValor($pct_qtd_total_nao_aceite,2); ?>">
												<span class="pie-value"><?php echo fnValor($pct_qtd_total_nao_aceite,2); ?>%</span>
											</div>
										</div>
										<div class="clearfix"> </div>
									</div>	
								</div>
								
								<div class="col-md-1">

								</div>

								<div class="col-md-3">
								
									<div class="push20"></div>

									<div class="col-xs-6">

										<div class="form-group pull-left">
											<label class="switch switch-small">
											<input type="checkbox" name="LOG_BLOQUEIA" id="LOG_BLOQUEIA" class="switch" value="S" />
											<span></span>
											</label> 
											<div class="push"></div>								
											<label for="inputName" class="control-label"> &nbsp;Bloquear Saldo Disponível</label>
											<div class="help-block with-errors"></div>
										</div>
										
									</div>

									<div class="col-xs-6">

										<?php if ($log_cadtoken == "N") { ?>
										<div class="disabledBlock"></div>
										<?php } ?>

										<div class="form-group pull-left">
											<label class="switch switch-small">
											<input type="checkbox" name="LOG_CLEARTKN" id="LOG_CLEARTKN" class="switch" value="S" />
											<span></span>
											</label>
											<div class="push"></div> 								
											<label for="inputName" class="control-label"> &nbsp;Limpar Tokens</label>
											<div class="help-block with-errors"></div>
										</div>
										
									</div>	
									
									<div class="push20"></div>
									<button type="submit" name="CAD" id="CAD" class="btn btn-danger btn-sm btn-block getBtn"><i class="fal fa-cogs" aria-hidden="true"></i>&nbsp; Pendenciar Clientes</button>

								</div>
								
								<div class="col-md-2">
								</div>									
								
								
							</div>					
			
						</div>
					<!-- fim Portlet -->
					</div>
				
				</div>
				
			</div>	

			<?php
			}  
			?>	

			<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
			<input type="hidden" name="CASAS_DEC" id="CASAS_DEC" value="<?=$casasDec?>">
			<input type="hidden" name="opcao" id="opcao" value="">
			<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />							
			<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">	
			<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
			<input type="hidden" name="RONE" id="RONE" value="<?php echo $cod_empresa; ?>">
				
			</form>

			<div class="portlet portlet-bordered">
				<div class="portlet-body">

					<div class="login-form">
							<div class="row">
								<div class="col-md-12" id="div_Produtos">
									
                                <div class="no-more-tables">

                                    <table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
                                      
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Data do Pendenciamento</th>
                                                <th>Cadastros Até</th>
                                                <th>Unidade</th>
                                                <th>Total Histórico</th>
                                                <th>Total Pendenciado</th>
                                                <th>Bloqueio Saldo</th>
                                                <th>Usuário</th>
                                            </tr>
                                        </thead>
                                        <tbody id="relatorioConteudo">

                                            <?php
                                          
                                            if ($cod_empresa != 0) {

                                                //variavel para calcular o início da visualização com base na página atual
                                                $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
                                               
                                                $sql = "SELECT A.COD_PENDENCIA,A.COD_UNIVEND,C.NOM_FANTASI,A.COD_EMPRESA,QTD_PENDENTE,A.QTD_CLIENTES,LOG_BLOQUEA,A.DAT_PENDENCIA,A.DAT_FILTRO_COM,B.NOM_USUARIO,B.COD_USUARIO 
															FROM PENDENCIA A
															LEFT JOIN USUARIOS B ON A.COD_USUCADA=B.COD_USUARIO AND A.COD_USUCADA=B.COD_USUARIO
															LEFT JOIN UNIDADEVENDA C ON A.COD_UNIVEND=C.COD_UNIVEND
															WHERE  
															A.COD_EMPRESA= $cod_empresa 
															AND A.COD_UNIVEND IN($lojasSelecionadas)
															ORDER BY A.DAT_PENDENCIA DESC";           

                                                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                                //fnEscreve($sql);
                                                $count = 0;
                                                while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
                                         
                                                    if ($qrLista['COD_UNIVEND'] == 9999) {
                                                        $unidade = "Todas unidades";
                                                    } else {
                                                        $unidade = $qrLista['NOM_FANTASI'];
                                                    }  
													
                                                    if ($qrLista['LOG_BLOQUEA'] == "S") {
                                                        $bloqueado = "Sim";
                                                    } else {
                                                        $bloqueado = "Não";
                                                    }
													
                                                    $count++;

                                                    echo"
													
												<tr>
												  <td><small>" . $qrLista['COD_PENDENCIA'] . "</small></td>
												  <td><small>" . fnDataFull($qrLista['DAT_PENDENCIA']) . "</small></td>
												  <td><small>" . fnDataShort($qrLista['DAT_FILTRO_COM']) . "</small></td>
												  <td><small>" . $unidade . "</small></td>
												  <td><small>" . $qrLista['QTD_CLIENTES'] . "</small></td>
												  <td><small>" . $qrLista['QTD_PENDENTE'] . "</small></td>
												  <td><small>" . $bloqueado . "</small></td>
												  <td> <small>" . $qrLista['NOM_USUARIO'] . "</small></td>
												</tr>
												";
																}
                                            }
                                            ?>

                                        </tbody>


                                    </table>


																					
								</div>
								
							</div>		

						
					<div class="push30"></div>	
					
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

	<script src="js/pie-chart.js"></script>
	
    <script>
	
		//datas
		$(function () {

			$.tablesorter.addParser({ id: "moeda", is: function(s) { return true; }, format: function(s) { return $.tablesorter.formatFloat(s.replace(new RegExp(/[^0-9,]/g),"")); }, type: "numeric" });
			
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
											url: "relatorios/ajxClientesLGPD.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>&condicaoCartao=<?php echo $condicaoCartao; ?>&andCreditos=<?php echo $andCreditos; ?>&condicaoVendaPDV=<?php echo $condicaoVendaPDV; ?>&andNome=<?php echo $andNome; ?>",
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
				url: "relatorios/ajxClientesLGPD.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>&condicaoCartao=<?php echo $condicaoCartao; ?>&andCreditos=<?php echo $andCreditos; ?>&condicaoVendaPDV=<?php echo $condicaoVendaPDV; ?>&andNome=<?php echo $andNome; ?>",
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
		
		//graficos
        $(document).ready( function() {
			
            $('#main-pie').pieChart({
                barColor: '#3bb2d0',
                trackColor: '#eee',
                lineCap: 'round',
                lineWidth: 8,
                onStep: function (from, to, percent) {
                    $(this.element).find('.pie-value').text(percent.toFixed(2) + '%');
                }
            });	

            $('#main-pie2').pieChart({
				barColor: '#E74C3C',
				trackColor: '#eee',
                lineCap: 'round',
                lineWidth: 8,
                onStep: function (from, to, percent) {
                    $(this.element).find('.pie-value').text(percent.toFixed(2) + '%');
                }
            });	

			
        });		
		
	</script>	
   