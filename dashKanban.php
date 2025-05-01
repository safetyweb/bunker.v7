<?php

	// definir o numero de itens por pagina
	$itens_por_pagina = 20;	
	$pagina  = "1";

	$dias30="";
	$dat_ini="";
	$dat_fim="";
	
	$cod_externo = "";
	$cod_empresa = "";
	$nom_chamado = "";

	$cod_tpsolicitacao = "";
	$cod_status = "";
	$cod_integradora = "";
	$cod_plataforma = "";
	$cod_versaointegra = "";
	$cod_prioridade = "";

	$hashLocal = mt_rand();

	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
	$dias30 = fnFormatDate(date("Y-m-d"));
	
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
			
			$dat_ini = fnDataSql($_POST['DAT_INI']);
			$dat_fim = fnDataSql($_POST['DAT_FIM']);
			$cod_chamado = $_POST['COD_CHAMADO'];
			$cod_externo = $_POST['COD_EXTERNO'];
			$cod_empresa = $_POST['COD_EMPRESA'];
			$nom_chamado = $_POST['NOM_CHAMADO'];

			$cod_tpsolicitacao = $_POST['COD_TPSOLICITACAO'];
			$cod_status = $_POST['COD_STATUS'];
			$cod_integradora = $_POST['COD_INTEGRADORA'];
			$cod_plataforma = $_POST['COD_PLATAFORMA'];
			$cod_versaointegra = $_POST['COD_VERSAOINTEGRA'];
			$cod_prioridade = $_POST['COD_PRIORIDADE'];
			$cod_usuario = $_POST['COD_USUARIO'];
			$cod_usures = $_POST['COD_USURES'];
			
			
			//fnEscreve($cod_usuario);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			$usu_cadastr = $_SESSION["SYS_COD_USUARIO"];
						
			if ($opcao != ''){
				
				
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
      
	
	//busca dados da url		

	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}

	if(isset($_GET['x'])){
		$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
		$msgTipo = 'alert-success';
	}
	
	if($dat_ini == date('Y-m-d')){$datIniAND = " ";}else{$datIniAND = "DATE_FORMAT(SC.DAT_CHAMADO, '%Y-%m-%d') >= '$dat_ini' AND ";}

	if($dat_fim == date('Y-m-d')){$dat_fim = fnDataSql($hoje);}

	if($cod_externo == ""){$ANDcodExterno = " ";}else{$ANDcodExterno = "AND SC.COD_EXTERNO LIKE '%$cod_externo%' ";}

	if($cod_chamado == ""){$ANDcodChamado = " ";}else{$ANDcodChamado = "AND SC.COD_CHAMADO = $cod_chamado ";}

	if($cod_empresa == ""){$ANDcodEmpresa = " ";}else{$ANDcodEmpresa = "AND SC.COD_EMPRESA = $cod_empresa ";}

	if($nom_chamado == ""){$ANDnomChamado = " ";}else{$ANDnomChamado = "AND SC.NOM_CHAMADO LIKE '%$nom_chamado%' ";}

	if($cod_tpsolicitacao == ""){$ANDcodTipo = " ";}else{$ANDcodTipo = "AND SC.COD_TPSOLICITACAO = $cod_tpsolicitacao ";}

	if($cod_status == ""){$ANDcodStatus = "AND SC.COD_STATUS != 5 ";}else{$ANDcodStatus = "AND SC.COD_STATUS = $cod_status ";}

	if($cod_integradora == ""){$ANDcodIntegradora = " ";}else{$ANDcodIntegradora = "AND SC.COD_INTEGRADORA = $cod_integradora ";}

	if($cod_plataforma == ""){$ANDcodPlataforma = " ";}else{$ANDcodPlataforma = "AND SC.COD_PLATAFORMA = $cod_plataforma ";}

	if($cod_versaointegra == ""){$ANDcodVersaointegra = " ";}else{$ANDcodStatus = "AND SC.COD_VERSAOINTEGRA = $cod_versaointegra ";}

	if($cod_prioridade == ""){$ANDcodPrioridade = " ";}else{$ANDcodPrioridade = "AND SC.COD_PRIORIDADE = $cod_prioridade ";}

	if($cod_usuario == ""){$ANDcodUsuario = " ";}else{$ANDcodUsuario = "AND SC.COD_USUARIO = $cod_usuario ";}

	if($cod_usures == ""){$ANDcod_usures = " ";}else{$ANDcod_usures = "AND SC.COD_USURES = $cod_usures ";}



?>


			
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
						$formBack = "1019";
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
						
						<div class="push20"></div>

						<div class="login-form">
						
							<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

								<fieldset>
									<legend>Filtros</legend>
								
									<div class="row" >

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Data Inicial</label>
												
												<div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
													<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value=""/>
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
												
												<div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
													<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>"/>
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Código Externo</label>
												<input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" maxlength="45" value="<?php echo $cod_externo; ?>">
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Código do Chamado</label>
												<input type="text" class="form-control input-sm" name="COD_CHAMADO" id="COD_CHAMADO" maxlength="45" value="<?php echo $cod_externo; ?>">
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Empresa</label>
												<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione a empresa" name="COD_EMPRESA" id="COD_EMPRESA">
													<option value=""></option>
													<?php 
														
															$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM EMPRESAS";
															$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
														
															while ($qrEmpresa = mysqli_fetch_assoc($arrayQuery))
															  {
															  	?>
															  	<option value="<?php echo $qrEmpresa['COD_EMPRESA']; ?>"><?php echo $qrEmpresa['NOM_FANTASI']; ?></option>
															  	<?php } ?>
												</select>
												<div class="help-block with-errors"></div>
											</div>														
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Título do Chamado</label>
												<input type="text" class="form-control input-sm" name="NOM_CHAMADO" id="NOM_CHAMADO" maxlength="50" value="<?php echo $nom_chamado; ?>">
											</div>
										</div>

									</div>

									<div class="row">

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Tipo de Solicitação</label>
												<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione o tipo" name="COD_TPSOLICITACAO" id="COD_TPSOLICITACAO">
													<option value=""></option>
													<?php 
														
															$sql = "SELECT * FROM SAC_TPSOLICITACAO";
															$arrayQuery = mysqli_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());
														
															while ($qrSolicitacao = mysqli_fetch_assoc($arrayQuery))
															  {
															  	?>
															  	<option value="<?php echo $qrSolicitacao['COD_TPSOLICITACAO']; ?>"><?php echo $qrSolicitacao['DES_TPSOLICITACAO']; ?></option>
															  	<?php } ?>
												</select>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Status</label>
												<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione o status" name="COD_STATUS" id="COD_STATUS">
													<option value=""></option>
													<?php 
														
															$sql = "SELECT * FROM SAC_STATUS";
															$arrayQuery = mysqli_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());
														
															while ($qrStatus = mysqli_fetch_assoc($arrayQuery))
															  {
															  	?>
															  	<option value="<?php echo $qrStatus['COD_STATUS']; ?>"><?php echo $qrStatus['DES_STATUS']; ?></option>
															  	<?php } ?>
												</select>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Integradora</label>
													<select data-placeholder="Selecione a integradora" name="COD_INTEGRADORA" id="COD_INTEGRADORA" class="chosen-select-deselect">
														<option value=""></option>
														<?php 
														
															$sql = "select * from empresas where COD_EMPRESA <> 1 and LOG_INTEGRADORA = 'S' order by NOM_FANTASI";
															$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
														
															while ($qrListaIntegradora = mysqli_fetch_assoc($arrayQuery))
															  {	
														  
																echo"
																	  <option value='".$qrListaIntegradora['COD_EMPRESA']."'>".$qrListaIntegradora['NOM_FANTASI']."</option>
																	"; 
																  }											
														?>	
													</select>
												<div class="help-block with-errors"></div>
											</div>
											
										</div>

										<div class="col-md-1">
											<div class="form-group">
												<label for="inputName" class="control-label">Plataforma</label>
												<select class="chosen-select-deselect requiredChk" data-placeholder="Plataforma" name="COD_PLATAFORMA" id="COD_PLATAFORMA">
													<option value=""></option>
														<?php 
														
															$sql = "SELECT * FROM SAC_PLATAFORMA";
															$arrayQuery = mysqli_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());
														
															while ($qrPlataforma = mysqli_fetch_assoc($arrayQuery))
															  {
															  	?>
															  	<option value="<?php echo $qrPlataforma['COD_PLATAFORMA']; ?>"><?php echo $qrPlataforma['DES_PLATAFORMA']; ?></option>
															  	<?php } ?>
												</select>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Versão da Integração</label>
												<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione a versão" name="COD_VERSAOINTEGRA" id="COD_VERSAOINTEGRA">
													<option value=""></option>
														<?php 
														
															$sql = "SELECT * FROM SAC_VERSAOINTEGRA";
															$arrayQuery = mysqli_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());
														
															while ($qrIntegracao = mysqli_fetch_assoc($arrayQuery))
															  {
															  	?>
															  	<option value="<?php echo $qrIntegracao['COD_VERSAOINTEGRA']; ?>"><?php echo $qrIntegracao['DES_VERSAOINTEGRA']; ?></option>
															  	<?php } ?>
												</select>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-1"> 
											<div class="form-group">
												<label for="inputName" class="control-label">Prioridade</label>
												<select class="chosen-select-deselect requiredChk" data-placeholder="Prioridade" name="COD_PRIORIDADE" id="COD_PRIORIDADE">
													<option value=""></option>
													<?php 
														
															$sql = "SELECT * FROM SAC_PRIORIDADE";
															$arrayQuery = mysqli_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());
														
															while ($qrPrioridade = mysqli_fetch_assoc($arrayQuery))
															  {
															  	?>
															  	<option value="<?php echo $qrPrioridade['COD_PRIORIDADE']; ?>"><?php echo $qrPrioridade['DES_PRIORIDADE']; ?></option>
															  	<?php } ?>
												</select>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Responsável TI</label>
													<select data-placeholder="Selecione um usuário" name="COD_USURES" id="COD_USURES" class="chosen-select-deselect requiredChk" style="width:100%;">
														<option value=""></option>
														<option value="">Todos os Responsáveis</option>
														<optgroup label="Usuários Marka">
													    <?php 
													
															$sql = "SELECT COD_USUARIO, NOM_USUARIO from usuarios 
															where (usuarios.COD_EMPRESA = 2 OR usuarios.COD_EMPRESA = 3)
															and usuarios.DAT_EXCLUSA is null 
															AND COD_TPUSUARIO IN(9,6,1,3) 
															AND LOG_ESTATUS = 'S' order by  usuarios.NOM_USUARIO ";
															$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
														
															while ($qrLista = mysqli_fetch_assoc($arrayQuery))
															  {														
																echo"
																  <option value='".$qrLista['COD_USUARIO']."'>".$qrLista['NOM_USUARIO']."</option> 
																"; 
															  }											
														?> 
													    </optgroup>
													</select>
												<div class="help-block with-errors"></div>
											</div>
										</div>

									</div>

									<div class="row">

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label">Solicitante</label>
													<div id="relatorioUsu">
														<select data-placeholder="Usuários Marka" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect requiredChk" style="width:100%;">
															
														</select>
													</div>
												<div class="help-block with-errors">requisito: selecionar empresa</div>
											</div>
										</div>
										
										<div class="col-md-2">
											<div class="push20"></div>
											<button type="submit" name="BUS" id="BUS" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
										</div>

									</div>

								</fieldset>
																	
							
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
																	
							</form>
							 
							<div class="push30"></div>

							

							<!-- Lists container -->
							<!-- <section class="lists-container">

								<div class="list">

									<h3 class="list-title">Tasks to Do</h3>

									<ul class="list-items">
										<li>Complete mock-up for client website</li>
										<li>Email mock-up to client for feedback</li>
										<li>Update personal website header background image</li>
										<li>Update personal website heading fonts</li>
										<li>Add google map to personal website</li>
										<li>Begin draft of CSS Grid article</li>
										<li>Read new CSS-Tricks articles</li>
										<li>Read new Smashing Magazine articles</li>
										<li>Read other bookmarked articles</li>
										<li>Look through portfolios to gather inspiration</li>
										<li>Create something cool for CodePen</li>
										<li>Post latest CodePen work on Twitter</li>
										<li>Listen to new Syntax.fm episode</li>
										<li>Listen to new CodePen Radio episode</li>
									</ul>

									<button class="add-card-btn btn">Add a card</button>

								</div>

							</section> -->

							<section id="kanban_view">
								<iframe src="templates/kanban/pages/kanban.php" frameborder="0" style="width: 100%; height: 100%"></iframe>
							</section>

					</div>								
				
				</div>
			</div>
			<!-- fim Portlet -->
		</div>
		
	</div>					
						
	<div class="push20"></div>

	<link rel="stylesheet" href="js/plugins/menu-dropdown/menu.min.css" />
	<script type="text/javascript" src="js/plugins/menu-dropdown/menu.min.js"></script>

	<link href='https://bevacqua.github.io/dragula/dist/dragula.css' rel='stylesheet' type='text/css' />
	<script src='https://bevacqua.github.io/dragula/dist/dragula.js'></script>

	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>

	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
	
	<script type="text/javascript">

		

		$(document).ready(function(){

			var boxArray = document.getElementsByClassName("movable");
			var boxes = Array.prototype.slice.call(boxArray);
			dragula({ containers: boxes });


			$('.menu').menu();

			retornaForm(0);

			$('#COD_EMPRESA').val('<?=$cod_empresa?>').trigger('chosen:updated');

			var idEmp = $('#COD_EMPRESA').val();
			buscaCombo(idEmp);
			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				 maxDate : 'now',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
				
			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			
			// var numPaginas = <?php echo $numPaginas; ?>;
			// if(numPaginas != 0){
			// 	carregarPaginacao(numPaginas);
			// }			

		});

		function retornaForm(index){

			var plataforma = '<?php echo $cod_plataforma; ?>';
			if(plataforma != 0 && plataforma != ""){$("#formulario #COD_PLATAFORMA").val(<?php echo $cod_plataforma; ?>).trigger("chosen:updated");}

			var empresa = '<?php echo $cod_empresa; ?>';
			if(empresa != 0 && empresa != ""){$("#formulario #COD_EMPRESA").val(<?php echo $cod_empresa; ?>).trigger("chosen:updated");}

			var versaointegra = '<?php echo $cod_versaointegra; ?>';
			if(versaointegra != 0 && versaointegra != ""){$("#formulario #COD_VERSAOINTEGRA").val(<?php echo $cod_versaointegra; ?>).trigger("chosen:updated");}

			var integradora = '<?php echo $cod_integradora; ?>';
			if(integradora != 0 && integradora != ""){$("#formulario #COD_INTEGRADORA").val(<?php echo $cod_integradora; ?>).trigger("chosen:updated");}

			var tpsolicitacao = '<?php echo $cod_tpsolicitacao; ?>';
			if(tpsolicitacao != 0 && tpsolicitacao != ""){$("#formulario #COD_TPSOLICITACAO").val(<?php echo $cod_tpsolicitacao; ?>).trigger("chosen:updated");}

			var prioridade = '<?php echo $cod_prioridade; ?>';
			if(prioridade != 0 && prioridade != ""){$("#formulario #COD_PRIORIDADE").val(<?php echo $cod_prioridade; ?>).trigger("chosen:updated");}

			var status = '<?php echo $cod_status; ?>';
			if(status != 0 && status != ""){$("#formulario #COD_STATUS").val(<?php echo $cod_status; ?>).trigger("chosen:updated");}

			var usures = '<?php echo $cod_usures; ?>';
			if(usures != 0 && usures != ""){$("#formulario #COD_USURES").val(<?php echo $cod_usures; ?>).trigger("chosen:updated");}
			
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}

		// $("body").click(function(e){

	 //    	objClick = e.target.id;
	 //    	// alert(objClick);

	 //    	if(!y && (objClick == "btnDrop")){
	 //    		abreMenuTile();
	 //   		}else if(y && !$(".dropdown2 li a").is(e.target)){
	 //   			fechaMenuTile();
	 //   		}

	 //    });

	    $("#COD_EMPRESA").change(function() {
			var idEmp = $('#COD_EMPRESA').val();
			buscaCombo(idEmp);
		});	 	

	    // function fechaMenuTile(){
	    // 	$(".dropdown2").animate({
	   	// 		"opacity": "0",
	   	// 		"top": "-50px"
	   	// 	},"fast",function(){
	   	// 		$(".dropdown2").css('z-index', -99999);
	   	// 	});

   		// 	y = false;
	    // }

	    // function abreMenuTile(){
	    // 	$(".dropdown2").css('z-index', 99999).animate({
	   	// 		"opacity": "1",
	   	// 		"top": "-27px"
	   	// 	},"fast");

    	// 	y = true;
	    // }

		function buscaCombo(idEmp){
			$.ajax({
				type: "GET",
				url: "ajxAddSuporte.php",
				data: { ajxEmp:idEmp },
				beforeSend:function(){
					$('#relatorioUsu').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					// console.log(data);	
					$('#relatorioUsu').html($('#relatorioUsuario',data));															
					$('#COD_USUARIO').chosen();															
					$('#COD_USUARIO').val('<?=$cod_usuario?>').trigger('chosen:updated');															
				},
				error:function(){
					$('#relatorioUsu').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Empresa não encontrada...</p>');
				}
			});
		}

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxRelSuporte.do?opcao=paginar&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
				data: $('#formulario').serialize(),
				beforeSend:function(){
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#relatorioConteudo").html(data);										
				},
				error:function(){
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});		
		}

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
										url: "ajxRelSuporte.do?opcao=exportar&nomeRel="+nome,
										data: $('#formulario').serialize(),
										method: 'POST'
									}).done(function (response) {
										self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
										var fileName = '0_' + nome + '.csv';
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
		
	</script>	