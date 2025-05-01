<?php
	
	//echo fnDebug('true');
	
	$itens_por_pagina = 50;
	$pagina = 1;
	$log_pago = "S";
	
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
			$cod_indicad = fnLimpaCampoZero($_POST['COD_INDICAD']);
			$cod_univend = fnLimpaCampoArray($_POST['COD_UNIVEND']);
			$cod_estado = fnLimpaCampoZero($_POST['COD_ESTADO']);
			$cod_municipio = fnLimpaCampoZero($_POST['COD_MUNICIPIO']);

			// fnEscreve($cod_univend);

			if (empty($_REQUEST['LOG_PAGO'])) {
				$log_pago = 'N';
			} else {
				$log_pago = $_REQUEST['LOG_PAGO'];
			}

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
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
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
	

	if($cod_empresa == 332){
		if($_SESSION['SYS_COD_USUARIO'] != "11478"){
			$andUnivendCombo = 'and cod_univend in(' . $_SESSION['SYS_COD_UNIVEND']. ')';
		}else{
			$andUnivendCombo = '';
		}
	}

	$check_termo = "";
	if($log_pago == "S"){
		$check_pago = "checked";
	}
	
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
								
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Campanhas</label>

											<select data-placeholder="Selecione uma ou mais campanhas" name="COD_UNIVEND[]" id="COD_UNIVEND" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
												<?php
												$sql = "SELECT COD_UNIVEND, NOM_FANTASI, LOG_ESTATUS FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa $andUnivendCombo AND LOG_ESTATUS = 'S' AND DAT_EXCLUSA IS NULL ORDER BY NOM_FANTASI ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
												while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery)) {

													if ($qrListaUnive['LOG_ESTATUS'] == 'N') {
														$disabled = "disabled";
													} else {
														$disabled = " ";
													}

													echo "
														<option value='" . $qrListaUnive['COD_UNIVEND'] . "'" . $disabled . ">" . ucfirst($qrListaUnive['NOM_FANTASI']) . "</option> 
													";
												}
												?>
											</select>
											<?php //fnEscreve($sql); 
											?>
											<div class="help-block with-errors"></div>

											<a class="btn btn-default btn-sm" id="iAll" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square" aria-hidden="true"></i> selecionar todos</a>&nbsp;
											<a class="btn btn-default btn-sm" id="iNone" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>

										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label ">Assessor</label>
											<select class="chosen-select-deselect" data-placeholder="Selecione o indicador" name="COD_INDICAD" id="COD_INDICAD">
												<option value=""></option>
												<?php

												$sql = "SELECT DISTINCT A.COD_INDICAD,
																(SELECT DISTINCT NOM_CLIENTE FROM CLIENTES WHERE CLIENTES.COD_CLIENTE=A.COD_INDICAD) AS NOM_INDICADOR 
														FROM CLIENTES A 
														WHERE A.COD_EMPRESA = $cod_empresa
														AND A.COD_INDICAD!=29007
														ORDER BY NOM_INDICADOR";
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

												while ($qrIndica = mysqli_fetch_assoc($arrayQuery)) {
												?>
													<option value="<?php echo $qrIndica['COD_INDICAD']; ?>"><?php echo $qrIndica['NOM_INDICADOR']; ?></option>
												<?php
												}
												?>
											</select>
											<script type="text/javascript">
												$('#COD_INDICAD').val('<?= $cod_indicad ?>').trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Estado</label>
												<select data-placeholder="Selecione um estado" name="COD_ESTADO" id="COD_ESTADO" class="chosen-select-deselect">
													<option value=""></option>
													<?php

														$sql = "SELECT COD_ESTADO, UF FROM ESTADO ORDER BY UF";
														$arrayEstado = mysqli_query(connTemp($cod_empresa,''),$sql);
														while($qrEstado = mysqli_fetch_assoc($arrayEstado)){
													?>
															<option value="<?=$qrEstado['COD_ESTADO']?>"><?=$qrEstado['UF']?></option>
													<?php
														}

													?>											
												</select>
                                                <script>
                                                	$("#formulario #COD_ESTADO").val("<?php echo $cod_estado; ?>").trigger("chosen:updated"); 
                                                </script>
											<div class="help-block with-errors"></div>
										</div>
									</div>	

									<div class="col-xs-2" id="relatorioCidade">
										<div class="form-group">
											<label for="inputName" class="control-label">Cidade</label>
												<select data-placeholder="Selecione um município" name="COD_MUNICIPIO" id="COD_MUNICIPIO" class="chosen-select-deselect">
													<option value=""></option>										
												</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">   
										<div class="form-group">
											<label for="inputName" class="control-label">Somente Contratos Pagos</label> 
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_PAGO" id="LOG_PAGO" class="switch" value="S" <?=$check_pago?>>
												<span></span>
											</label>
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-2">
										<div class="push20"></div>
										<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
									</div>
														
									
								</div>
									
						</fieldset>	
						
						<div class="push20"></div>
						
						<div>
							<div class="row">
								<div class="col-md-12">

									<div class="push20"></div>
									
									<?php

										if($nom_cliente != ""){
											$andNome = "AND CL.NOM_CLIENTE LIKE '%$nom_cliente%'";
										}else{
											$andNome = "";
										}

										if($num_cgcecpf != ""){
											$andCpf = "AND CL.NUM_CGCECPF = '$num_cgcecpf'";
										}else{
											$andCpf = "";
										}
										// Filtro por Grupo de Lojas
										// include "filtroGrupoLojas.php";
										
										// $sql = "CALL SP_RELAT_TOTALIZA_CUPOM_GERADO ( '$dat_ini' , '$dat_fim' ,'$lojasSelecionadas', $cod_empresa)";
												   
										// // fnEscreve($sql);
										// //fnTestesql(connTemp($cod_empresa,''),$sql);	
										// $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
										// $qrTotais = mysqli_fetch_assoc($arrayQuery);
										// $qtd_cliente_tot = $qrTotais['QTD_CLIENTE_TOT'];
										// $val_totvenda_tot = $qrTotais['VAL_TOTVENDA_TOT'];
										// $qtd_cupom_tot = $qrTotais['QTD_CUPOM_TOT'];
										// $qtd_venda_tot = $qrTotais['QTD_VENDA_TOT'];
										
									?>									
									
									<table class="table table-hover">
									
									  <thead>
										<tr>
										  <!-- <th class="text-center text-info">Total de Cupons<b> &nbsp; <?php echo fnValor($qtd_cupom_tot,0); ?></b></th>
										  <th class="text-center text-info">Total de Clientes<b> &nbsp; <?php echo fnValor($qtd_cliente_tot,0); ?></b></th>
										  <th class="text-center text-info">Total de Atendimentos<b> &nbsp; <?php echo fnValor($qtd_venda_tot,0); ?></b></th>
										  <th class="text-center text-info">Total de Faturamento  &nbsp; <b>R$ <?php echo fnValor($val_totvenda_tot,2); ?></b></th> -->
										</tr>
									  </thead>
									  
									</table>
									
									<div class="push10"></div>
									
									<table class="table table-bordered table-hover tablesorter">
									
									<thead>
										<tr>
											<th>Cod.</th>
											<th>Cod. Externo</th>
											<th>Colaborador</th>
											<th>CPF</th>
											<th>Campanha</th>
											<th>Dobrada</th>
											<th>Assessor</th>
											<th>Qtd. Contratos</th>
											<th>Val. Contrato</th>
											<th>Val. Pago</th>
										</tr>
									</thead>

									<tbody id="relatorioConteudo">							

									<?php

										// Filtro por Grupo de Lojas
										//include "filtroGrupoLojas.php";

										if($cod_indicad != 0){
											$andIndicad = "AND a.COD_INDICAD=$cod_indicad ";
										}else{
											$andIndicad = "";
										}

										if($cod_estado != 0){
											$andEstado = "AND a.COD_ESTADO=$cod_estado ";
										}else{
											$andEstado = "";
										}

										if($cod_municipio != 0){
											$andMunicipio = "AND a.COD_MUNICIPIO=$cod_municipio ";
										}else{
											$andMunicipio = "";
										}

										// if($cod_univend == 0){
										// 	$cod_univend = $_SESSION['SYS_COD_UNIVEND'];
										// }

										if($log_pago == "S"){
											$andPago = "and IFNULL((SELECT FORMAT(TRUNCATE(sum(val_credito),2),2,'pt_BR') FROM caixa WHERE caixa.cod_cliente=a.cod_cliente AND caixa.cod_contrat=i.cod_contrat AND caixa.tip_lancame='D' AND caixa.cod_exclusa=0),0) >0 ";
										}else{
											$andPago = "";
										}
									
										$sql = "SELECT 1
												FROM clientes a
												INNER JOIN unidadevenda f ON f.COD_UNIVEND=a.COD_UNIVEND AND f.COD_EMPRESA=a.cod_empresa
												WHERE a.cod_empresa=$cod_empresa AND 
												      a.cod_indicad!=29007 AND 
												      a.cod_univend in($cod_univend) 
												      $andIndicad
												      $andPago
												      $andEstado
													  $andMunicipio";
										//fnTestesql(connTemp($cod_empresa,''),$sql);		
										//fnEscreve($sql);

										$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
										$totalitens_por_pagina = mysqli_num_rows($retorno);

										$numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);
										
										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										// Filtro por Grupo de Lojas
										//include "filtroGrupoLojas.php";

										$sql = "SELECT 
												a.cod_cliente AS Codigo,
												a.cod_externo,
												a.LOG_TERMO as TERMO,
												case when a.LOG_ESTATUS='S' then
												'Ativo'
												when a.LOG_ESTATUS='N' then
												'Inativo'
												END STATUS,
												case when a.LOG_TERMO='S' then
												'Contrato Assinado'
												when a.LOG_TERMO='N' then
												'sem contrato assinado'
												END contrato,

												A.NOM_CLIENTE AS Colaborador,
												A.NUM_CGCECPF AS CPF,
												A.NUM_RGPESSO AS RG, 
												A.DES_EMAILUS AS Email,
												A.num_celular AS Celular,
												A.des_enderec as Endereço,
												A.num_enderec Numero,
												A.des_complem AS Complemento,
												A.des_bairroc AS Bairro,
												A.num_cepozof CEP,
												c.NOM_MUNICIPIO Cidade,
												d.uf Estado,
												e.num_pix AS conta,

												case when cod_profiss=364 then
												'Divulgador (Cabo Eleitoral)'
												when cod_profiss=365 then
												'Coordenador'
												when cod_profiss=366 then
												'Cessão de Serviço Voluntário'
												END AS cod_profis,
												F.NOM_UNIVEND AS campanha,
												h.des_filtro AS dobradas,
												a.cod_indicad,
												(SELECT nom_cliente FROM clientes g WHERE g.COD_CLIENTE=a.cod_indicad) AS acessor,
												(SELECT COUNT(*)FROM contrato_eleitoral i WHERE  i.cod_cliente=a.cod_cliente AND i.COD_EXCLUSA=0) qtd_contrato,
												i.cod_contrat AS Numero,
												IFNULL(FORMAT(TRUNCATE(i.VAL_CONTRAT,2),2,'pt_BR'),0) val_contrato,
												IFNULL((SELECT FORMAT(TRUNCATE(sum(val_credito),2),2,'pt_BR') FROM caixa WHERE caixa.cod_cliente=a.cod_cliente AND caixa.cod_contrat=i.cod_contrat AND caixa.tip_lancame='D' AND caixa.cod_exclusa=0),0) AS val_pago,
												case when tip_contrat = 1 then
												'Genérico'
												when tip_contrat = 2 then
												'Cabo Eleitoral'
												when tip_contrat = 3 then
												'Coordenador Cabo Eleitoral'
												when tip_contrat = 4 then
												'Cessão Serviços'
												when tip_contrat = 5 then
												'Cessão Gratuita de Veículos'
												END tipo_contrato


												FROM clientes a
												LEFT JOIN  MUNICIPIOS C ON A.COD_MUNICIPIO=C.COD_MUNICIPIO AND C.COD_ESTADO=35
												LEFT JOIN ESTADO D ON  A.COD_ESTADO=D.COD_ESTADO
												LEFT JOIN DADOS_BANCARIOS e ON 	A.COD_CLIENTE=E.COD_CLIENTE
												INNER JOIN unidadevenda f ON f.COD_UNIVEND=a.COD_UNIVEND AND f.COD_EMPRESA=a.cod_empresa
												LEFT JOIN cliente_filtros g ON g.cod_cliente=a.cod_cliente AND g.cod_empresa=a.cod_empresa AND cod_tpfiltro=43
												LEFT JOIN filtros_cliente h ON h.cod_filtro=g.cod_filtro AND h.cod_tpfiltro=43
												LEFT JOIN contrato_eleitoral i ON i.cod_cliente=a.cod_cliente AND i.COD_EXCLUSA=0

												WHERE a.cod_empresa=$cod_empresa AND 
												      a.cod_indicad!=29007 AND 
												      a.cod_univend in($cod_univend) 
												      $andIndicad 
												      $andPago
												      $andEstado
													  $andMunicipio
												      ORDER BY nom_cliente
												";
										// fnEscreve($sql);
                                        //fnTestesql(connTemp($cod_empresa,''),$sql);											
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
															  
										$count=0;
										while ($qrCupom = mysqli_fetch_assoc($arrayQuery))
										  {								

										  	if(strtoupper($qrCupom['TERMO']) == "S"){
										  		$contratoAssinado = "<span class='fal fa-check text-success'></span>";
										  	}else{
										  		$contratoAssinado = "<span class='fal fa-times text-danger'></span>";
										  	}

											$count++;	
											echo"
												<tr>
												  <td>".$qrCupom['Codigo']."</td>
												  <td>".$qrCupom['cod_externo']."</td>
												  <td>".$qrCupom['Colaborador']."</td>
												  <td>".$qrCupom['CPF']."</td>
												  <td>".$qrCupom['campanha']."</td>
												  <td>".$qrCupom['dobradas']."</td>
												  <td>".$qrCupom['acessor']."</td>
												  <td>".$qrCupom['qtd_contrato']."</td>
												  <td>".$qrCupom['val_contrato']."</td>
												  <td>".$qrCupom['val_pago']."</td>
												</tr>
												"; 
											  }											
                                                                                         
									?>
										</tbody>

										<tfoot>
											<tr>
												<th colspan="100">
													<a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
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
							
								
							</div>
						</div>
							
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />					
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
						
						<div class="push5"></div> 
						
						</form>
						
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

			if('<?=$cod_estado?>' != '0' && '<?=$cod_estado?>' != ''){
				carregaComboCidades('<?=$cod_estado?>');
			}

			var numPaginas = <?php echo $numPaginas; ?>;
			if(numPaginas != 0){
				carregarPaginacao(numPaginas);
			}

			//retorno combo multiplo - lojas
			$("#formulario #COD_UNIVEND").val('').trigger("chosen:updated");
			if ("<?=$cod_univend?>" != "" && "<?=$cod_univend?>" != "0") {
				var sistemasUni = "<?=$cod_univend?>";
				var sistemasUniArr = sistemasUni.split(',');
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
					$("#formulario #COD_UNIVEND option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
				}
				$("#formulario #COD_UNIVEND").trigger("chosen:updated");
			}

			$(document).on('keypress',function(e) {
			    if(e.which == 13) {
			        e.preventDefault();
			        $("#BUS").click();
			    }
			});	

			$("#COD_ESTADO").change(function(){
				cod_estado = $(this).val();
				carregaComboCidades(cod_estado);
			});
			
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
											url: "relatorios/ajxPagContratos.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>",
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
				url: "relatorios/ajxPagContratos.do?id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>&opcao=paginar",
				data: $('#formulario').serialize(),
				beforeSend:function(){
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#relatorioConteudo").html(data);										
					console.log(data);										
				},
				error:function(){
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});		
		}

		function carregaComboCidades(cod_estado){
	    	$.ajax({
				method: 'POST',
				url: 'ajxComboMunicipio.php?id=<?=fnEncode($cod_empresa)?>',
				data:{COD_ESTADO:cod_estado},
				beforeSend:function(){
					$('#relatorioCidade').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#relatorioCidade").html(data);
					$("#formulario #COD_MUNICIPIO").val("<?php echo $cod_municipio; ?>").trigger("chosen:updated");
					// $('#formulario').validator('validate');
				}
			});
	    }
		
	</script>	
   