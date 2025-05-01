<?php
	
	//echo "<h5>_".$opcao."</h5>";

	$hashLocal = mt_rand();

	$usuario = "Usuário";
	$cliente = "Cliente";
	$plural = "s";
	
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

			$cod_grupotr = fnLimpaCampoZero($_REQUEST['COD_GRUPOTR']);
			$des_grupotr = fnLimpaCampo($_REQUEST['DES_GRUPOTR']);
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){

				$sql = "CALL SP_ALTERA_GRUPOTRABALHO (
				 '".$cod_grupotr."', 
				 '".$des_grupotr."', 
				 '".$cod_empresa."', 
				 '".$opcao."'    
				) ";
				
				//echo $sql;
				
				mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());				
				
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
		//fnEscreve('entrou else');
	}

	$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

	$sql = "SELECT COD_USUARIOS_AGE FROM USUARIOS_AGENDA WHERE COD_USUARIO = $cod_usucada";
	$qrUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sql));
	$cod_usuarios_age = $qrUsu['COD_USUARIOS_AGE'];

	if($cod_usuarios_age == ""){
		$cod_usuarios_age = 0;
	}

	$andUsuariosAge = "AND COD_USUARIO IN($cod_usuarios_age)";

	if($cod_usuarios_age == "9999"){
		$andUsuariosAge = "";
	}

	// fnEscreve($cod_usuarios_age);
	
	//fnMostraForm();

?>

<style type="text/css">
	
	.fc-event-container a{
		text-decoration: none!important;
		font-weight: bold!important;
		cursor: pointer;
	}

	.fc-center h2, .fc-day-header{
		font-size: 14px!important;
		font-weight: 700;
	}
	.fc-content{
		color: #5c5c5c!important;
	}
	.alert-info {
	    background-color: rgba(235, 245, 251 ,0.75)!important;
	}
	/*
	#taskbar{
		height: 750px;
	}
	*/
	.btn {
    	height: 45px;
	}

	.badge{
	    display: table-cell;
	    border-radius: 30px 30px 30px 30px;
	    width: 23px!important;
	    height: 23px!important;
	    /*text-align: center;*/
	    color:white;
	    font-size:11px;
	    /*margin-right: auto;
	    margin-left: auto;*/
	}

	.txtBadge{
		display: table-cell;
		vertical-align: middle;
	}

	.txtSideBadge{
		position: relative;
		display: table-cell;
	}
	.iniciais{
		
	}
	.usuario-agenda{
		
	}

</style>

					<!-- <script type="text/javascript" src="js/plugins/fullcalendar/core/main.css"></script>
					<script type="text/javascript" src="js/plugins/fullcalendar/core/main.min.js"></script> -->
					
					<link href='js/plugins/fullcalendar/core/main.css' rel='stylesheet' />
				    <link href='js/plugins/fullcalendar/daygrid/main.css' rel='stylesheet' />
				    <link href='js/plugins/fullcalendar/timegrid/main.css' rel='stylesheet' />
				    <link href='js/plugins/fullcalendar/list/main.css' rel='stylesheet' />
				    <link href='js/plugins/fullcalendar/bootstrap/main.css' rel='stylesheet' />

				    <script src='js/plugins/fullcalendar/core/main.js'></script>
				    <script src='js/plugins/fullcalendar/daygrid/main.js'></script>
				    <script src='js/plugins/fullcalendar/timegrid/main.js'></script>
				    <script src='js/plugins/fullcalendar/list/main.js'></script>
				    <script src='js/plugins/fullcalendar/bootstrap/main.js'></script>
				    <script src='js/printThis.js'></script>

				    <script type="text/javascript" src="js/plugins/fullcalendar/core/locales-all.min.js"></script>

					<!-- <script type="text/javascript" src="js/plugins/fullcalendar/google-calendar/main.min.css"></script>
					<script type="text/javascript" src="js/plugins/fullcalendar/google-calendar/main.min.js"></script> -->
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

									<?php 
									//manu superior - empresas
									$abaEmpresa = 1400;									
									switch ($_SESSION["SYS_COD_SISTEMA"]) {
										case 14: //rede duque
											include "abasEmpresaDuque.php";
											break;
										case 15: //quiz
											include "abasEmpresaQuiz.php";
											break;
										case 16: //gabinete
											//include "abasGabinete.php";
											//$usuario = "Colaborador";
											$usuario = "Participantes";
											$cliente = "Apoiadores";
											$plural = " ";
											break;
										default;
											include "abasEmpresaConfig.php";
											break;
									}									
									?>
									
									<div class="push10"></div> 
			
									<div class="login-form">

										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

											<div class="row">
												
												<div id="taskbar" class="col-md-12">
																			
													<div class="form-group">
													<h4><?=$usuario.$plural?> Autorizados</h4>
													
															<select data-placeholder="Selecione os usuários" name="COD_USUARIOS_AGE[]" id="COD_USUARIOS_AGE" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
																<?php 
																	
																	$sql = "SELECT COD_USUARIO, NOM_USUARIO from usuarios
																	where COD_EMPRESA = $cod_empresa
																	AND usuarios.DAT_EXCLUSA is null
																	$andUsuariosAge
																	order by usuarios.NOM_USUARIO ";
																	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																
																	while ($qrLista = mysqli_fetch_assoc($arrayQuery))
																	  {														
																		echo"
																			  <option value='".$qrLista['COD_USUARIO']."'>".$qrLista['NOM_USUARIO']."</option> 
																			"; 
																		  }											
																?> 
															</select>
															<script>$('#COD_USUARIOS_AGE').val('<?=$cod_usucada?>').trigger('chosen:updated');</script>
														<div class="help-block with-errors"></div>
													</div>	
													
												</div>
												
											</div>
												
											<div class="row">
												
												<div class="push20"></div>
												
												<!-- ferramentas extras -->
												<div class="col-md-2">
													
													<a href="javascript:void(0)" name="CAD" id="CAD" class="btn btn-primary btn-block getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Novo Evento</a>

													<div class="push20"></div>
														
													<h4>Eventos</h4>
													<?php 
												
													$sql = "SELECT * FROM TIPO_EVENTO WHERE COD_EMPRESA = $cod_empresa";	
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
													
													$count=0;
													while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
													  {
														$count++;
													  ?>
															<div class="col-md-12 col-sm-12">
																<span class='badge text-center' style='color: #FFF; background-color: <?=$qrBuscaModulos['DES_COR']?>;'>
																	<span class='txtBadge'>
																		<span class="<?=$qrBuscaModulos['DES_ICONE']?> f12"></span>
																	</span>
																</span>
																<span class="txtSideBadge f12">&nbsp; <?=$qrBuscaModulos['DES_TPEVENT']." (".$qrBuscaModulos['ABV_TPEVENT'].")"?></span>
															<div class="push10"></div>
															</div>

														<?php }?>											
													
													
												</div>

												<!-- calendario -->
												<div class="col-md-10">
													<div id="calendar"></div>
												</div>
												
											</div>

										</form>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div>

					<input type="hidden" name="REFRESH_TAREFA" id="REFRESH_TAREFA" value="N">

					<!-- modal -->									
					<div class="modal fade" id="popModal" tabindex='-1'>
						<div class="modal-dialog" style="">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title"></h4>
								</div>
								<div class="modal-body">
									<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
								</div>		
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->

					<a type="hidden" name="btnCad" id="btnCad" class="addBox"></a>
					<!-- <a type="hidden" name="btnPrint" id="btnPrint" class="addBox"></a> -->
					<a type="hidden" name="btnPrint" id="btnPrint" class="addBox"></a>
	
	<script type="text/javascript">
		
		$(function(){

			$(".addBox").click(function() {
				
				$('#popModal').find('.modal-content').css({
					'width': '100vw',
					'height': 'auto',
					'marginLeft': 'auto',
					'marginRight': 'auto'
				});
				$('#popModal').find('.modal-dialog').css({
					'maxWidth': '100vw'
				});
				
			});

			var calendarEl = document.getElementById('calendar');

	        var calendar = new FullCalendar.Calendar(calendarEl, {
	        	locale: 'pt-br',
	        	plugins: [ 'dayGrid' , 'timeGrid', 'list', 'bootstrap' ],
	        	themeSystem: 'bootstrap',
	        	height: 750,
	        	defaultView: 'timeGridWeek',
	        	customButtons: {
				    imprimir: {
				      click: function() {

				      	//$("#calendar").printThis();
				      	view = calendar.view;
				      	dat_ini = view.currentStart.toISOString().substring(0,10);
				      	dat_fim = view.currentEnd.toISOString().substring(0,10);
				      	
						// alert(dat_ini+"   "+dat_fim);

				        $('#btnPrint').attr('data-url',"action.php?mod=<?php echo fnEncode(1443)?>&id=<?php echo fnEncode($cod_empresa)?>&idU="+JSON.stringify($('#COD_USUARIOS_AGE').val())+"&dat_ini="+dat_ini+"&dat_fim="+dat_fim+"&pop=true").attr('data-title',"Imprimir Agenda").click();
				      }
				    }
				},
				views: {
			      listDay: { buttonText: 'Dia' }
			    },
	        	header: {
			        left: 'imprimir prev,next today',
			        center: 'title, addEventButton',
			        right: 'listDay,timeGridWeek,dayGridMonth, listWeek',
			    },
			    eventLimit: true,
			    eventSources: [{
			    	id: 'AJX',
	                url: 'ajxAgenda.php?id=<?=fnEncode($cod_empresa)?>&idU='+JSON.stringify($('#COD_USUARIOS_AGE').val()),
	                method: 'POST', // Send post data,
	                extraParams: {COD_USUARIOS_AGE: $('#COD_USUARIOS_AGE').val()},
	                success: function(data){
	                	console.log(data);
	                	// alert($('#COD_USUARIOS_AGE').val());
	                },
	                error: function(data) {
	                	console.log(data);
	                    alert('Ocorreu um erro ao carregar os eventos, Tente novamente mais tarde');
	            	}
	        	}],
				//loading: function( isLoading ) {
			    //       if(isLoading) {// isLoading gives boolean value
			    //           $('#wait').show();
			    //       } else {
			    //           $('#wait').hide();
			    //       }
			    // },
	        	eventClick: function(info) {
	        		$('#btnCad').attr('data-url',"action.php?mod=<?php echo fnEncode(1402)?>&id=<?php echo fnEncode($cod_empresa)?>&idE="+info.event.id+"&pop=true").attr('data-title',"Editar Evento - "+info.event.title).click();
	        	}

	        });

	        calendar.render();
	        calendar.getEventSourceById('AJX').refetch();

	        $(".fc-imprimir-button").html('<i class="fal fa-print"></i>');

	        //modal close
			$('.modal').on('hidden.bs.modal', function () {
				if($('#REFRESH_TAREFA').val() == "S"){

					calendar.getEventSourceById('AJX').remove();
					calendar.addEventSource({
				    	id: 'AJX',
		                url: 'ajxAgenda.php?id=<?=fnEncode($cod_empresa)?>&idU='+JSON.stringify($('#COD_USUARIOS_AGE').val()),
		                method: 'POST', // Send post data,
		                extraParams: {COD_USUARIOS_AGE: $('#COD_USUARIOS_AGE').val()},
		                success: function(data){
		                	console.log(data);
		                	// alert($('#COD_USUARIOS_AGE').val());
		                },
		                error: function(data) {
		                	console.log(data);
		                    alert('Ocorreu um erro ao carregar os eventos, Tente novamente mais tarde');
		            	}
			        });		

				}
			});

			$('#CAD').click(function(){
				$('#btnCad').attr('data-url',"action.php?mod=<?php echo fnEncode(1402)?>&id=<?php echo fnEncode($cod_empresa)?>&pop=true").attr('data-title',"Novo Evento").click();
			});

			$('#COD_USUARIOS_AGE').change(function(){

			 	calendar.getEventSourceById('AJX').remove();
				calendar.addEventSource({
			    	id: 'AJX',
	                url: 'ajxAgenda.php?id=<?=fnEncode($cod_empresa)?>&idU='+JSON.stringify($('#COD_USUARIOS_AGE').val()),
	                method: 'POST', // Send post data,
	                extraParams: {COD_USUARIOS_AGE: $('#COD_USUARIOS_AGE').val()},
	                success: function(data){
	                	console.log(data);
	                	// alert($('#COD_USUARIOS_AGE').val());
	                },
	                error: function(data) {
	                	console.log(data);
	                    alert('Ocorreu um erro ao carregar os eventos, Tente novamente mais tarde');
	            	}
		        });

			});

			// ajax para debug
	        $.ajax({
	        	url: 'ajxAgenda.php?id=<?=fnEncode($cod_empresa)?>&idU='+JSON.stringify($('#COD_USUARIOS_AGE').val()),
	         	method: 'POST', // Send post data,
	         	success: function(data){
	         	console.log(data);
	         	// alert($('#COD_USUARIOS_AGE').val());
	         	},
	         	error: function(data) {
	         		console.log(data);
	         	    alert('Ocorreu um erro ao carregar os eventos, Tente novamente mais tarde');
	            }
	        });

		});
		
	</script>	