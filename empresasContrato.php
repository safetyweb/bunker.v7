<?php
 //fnDebug('true');

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

			$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);			
			$val_pesquisa = fnLimpaCampo($_POST['INPUT']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];	
						
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
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);
		// $cod_contrat = fnDecode($_GET['idC']);
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

	if($val_pesquisa != ""){
		$esconde = " ";
	}else{
		$esconde = "display: none;";
	}

        
?>

					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<?php if ($popUp != "true"){  ?>							
							<div class="portlet portlet-bordered">
							<?php } else { ?>
							<div class="portlet" style="padding: 0 20px 20px 20px;" >
							<?php } ?>
							
								<?php if ($popUp != "true"){  ?>
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg; ?></span>
									</div>
									<?php include "atalhosPortlet.php"; ?>
								</div>
								<?php } ?>
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>									
													
									<div class="login-form">
									
										<form method="post" id="formLista" action="action.php?mod=<?php echo $DestinoPg; ?>&id=0">
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

										<div class="push30"></div>

										<div class="row">
											<form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

												<div class="col-xs-4 col-xs-offset-4">
												    <div class="input-group activeItem">
										                <div class="input-group-btn search-panel">
										                    <button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
										                    	<span id="search_concept">Sem filtro</span>&nbsp;
										                    	<span class="far fa-angle-down"></span>										                    	
										                    </button>
										                    <ul class="dropdown-menu" role="menu">
										                    	<li class="divisor"><a href="#">Sem filtro</a></li>
										                    	<!-- <li class="divider"></li> -->
											                    <li><a href="#NOM_EMPRESA">Razão social</a></li>
											                    <li><a href="#NOM_FANTASI">Nome fantasia</a></li>
											                    <li><a href="#NUM_CGCECPF">CNPJ</a></li>										                      
										                    </ul>
										                </div>
										                <input type="hidden" name="VAL_PESQUISA" value="" id="VAL_PESQUISA">         
										                <input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?=$val_pesquisa?>" onkeyup="buscaRegistro(this)">
										                <div class="input-group-btn"id="CLEARDIV" style="<?=$esconde?>">
										                	<button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
										                </div>
										                <div class="input-group-btn">
										                    <button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
										                </div>
										            </div>
										        </div>
										         	
										        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />							
												<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

											</form>
										    
										</div>

										<div class="push10"></div>	
																														
										<div class="col-lg-12">
										<h4>Escolha a empresa desejada</h4>
											<div class="no-more-tables">
																						
												<table class="table table-bordered table-striped table-hover tableSorter buscavel">
												  <thead>
													<tr>
													  <th class="{ sorter: false }" width="40"></th>
													  <th>Código</th>
													  <th>Nome Fantasia</th>
													  <th class="{ sorter: false }" width="40"></th>
													  <th>Código</th>
													  <th>Nome Fantasia</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
												
													$sql = "SELECT A.COD_EMPRESA, A.NOM_FANTASI 
															from empresas A where A.cod_empresa <> 1 
															and A.cod_exclusa = 0 
															order by A.NOM_FANTASI";

                                                    $arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
													// fnEscreve($sql);
													$univendAtiva = '';
													
													$count=0;
													while ($qrEmp = mysqli_fetch_assoc($arrayQuery))
													{			

														// $sqlCont = "SELECT COD_UNICONT FROM CONTRATO_UNIDADE WHERE COD_UNIVEND = $qrEmp[COD_UNIVEND] AND COD_CONTRAT = $cod_contrat";
														// $qrCont = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlCont));

														// $qtd_loja = 0;

														// if (!empty($qrCont['COD_UNICONT']) && isset($qrCont['COD_UNICONT']) && $qrCont['COD_UNICONT'] != '' ){		
														// 	$univendAtiva = 'checked';	
														// }else{ 
														// 	$univendAtiva = ''; 
														// }

														if($count % 2 == 0){ 
													        $abreTR = "<tr>";
													        $fechaTR = "";  
													    }else{
													    	$abreTR = "";
													        $fechaTR = "</tr>";
													    }

														// fnEscreve(($count % 2));								
														
														echo $abreTR."
															  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></td>
															  <td>".$qrEmp['COD_EMPRESA']."</td>
															  <td>".$qrEmp['NOM_FANTASI']."</td>
															  <input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".fnEncode($qrEmp['COD_EMPRESA'])."'>
															  <input type='hidden' id='ret_NOM_FANTASI_".$count."' value='".$qrEmp['NOM_FANTASI']."'>

															".$fechaTR;

														$count++; 
													}										
													
												?>
													
												</tbody>
												</table>
												
										</form>

											</div>
											
										</div>

									<span style="color:#fff;"><?php echo($count); ?></span>
									
									<div class="push10"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div>

	<?php
	if (!is_null($RedirectPg)) {
		$DestinoPg = fnEncode($RedirectPg);		
	}else {
		$DestinoPg = "";		
		}	
	?>	
	
	<script type="text/javascript">

		//Barra de pesquisa essentials ------------------------------------------------------
		$(document).ready(function(e){
			var value = $('#INPUT').val().toLowerCase().trim();
		    if(value){
		    	$('#CLEARDIV').show();
		    }else{
		    	$('#CLEARDIV').hide();
		    }
		    $('.search-panel .dropdown-menu').find('a').click(function(e) {
				e.preventDefault();
				var param = $(this).attr("href").replace("#","");
				var concept = $(this).text();
				$('.search-panel span#search_concept').text(concept);
				$('.input-group #VAL_PESQUISA').val(param);
				$('#INPUT').focus();
			});

		    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function(){
			    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
		    });

		    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function(){
		    	$("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
		    });

		    $('#CLEAR').click(function(){
		    	$('#INPUT').val('');
		    	$('#INPUT').focus();
		    	$('#CLEARDIV').hide();
		    	if("<?=$filtro?>" != ""){
		    		location.reload();
		    	}else{
		    		var value = $('#INPUT').val().toLowerCase().trim();
				    if(value){
				    	$('#CLEARDIV').show();
				    }else{
				    	$('#CLEARDIV').hide();
				    }
				    $(".buscavel tr").each(function (index) {
				        if (!index) return;
				        $(this).find("td").each(function () {
				            var id = $(this).text().toLowerCase().trim();
				            var sem_registro = (id.indexOf(value) == -1);
				            $(this).closest('tr').toggle(!sem_registro);
				            return sem_registro;
				        });
				    });
		    	}
		    });

		    // $('#SEARCH').click(function(){
		    // 	$('#formulario').submit();
		    // });
		    	
		    
		});

		function buscaRegistro(el){
			var filtro = $('#search_concept').text().toLowerCase();

			if(filtro == "sem filtro"){
			    var value = $(el).val().toLowerCase().trim();
			    if(value){
			    	$('#CLEARDIV').show();
			    }else{
			    	$('#CLEARDIV').hide();
			    }
			    $(".buscavel tr").each(function (index) {
			        if (!index) return;
			        $(this).find("td").each(function () {
			            var id = $(this).text().toLowerCase().trim();
			            var sem_registro = (id.indexOf(value) == -1);
			            $(this).closest('tr').toggle(!sem_registro);
			            return sem_registro;
			        });
			    });
			}
		}

	//-----------------------------------------------------------------------------------
		
		function retornaForm(index){

			nom_fantasi = $("#ret_NOM_FANTASI_"+index).val(),
			cod_empresa = $("#ret_COD_EMPRESA_"+index).val();

			$.confirm({
				title: 'Confirmação',
				animation: 'opacity',
                closeAnimation: 'opacity',
				content: 'Criar novo contrato na <b>'+nom_fantasi+'</b>?',
				buttons: {
					confirmar: function () {
						$.ajax({
							method: 'POST',
							url: 'ajxEmpresasContrato.php',
							data: { COD_EMPRESA: cod_empresa },
							success:function(data){
								try { 
									parent.location.reload();
									$(this).removeData('bs.modal');	
									parent.$('#popModal').modal('hide');
								} catch(err) {}
							}
						});
					},
					cancelar: function () {	
						
					},
				}
			});

			// cod_univend = $("#ret_COD_UNIVEND_"+index).val(),
			// cod_contrat = '<?=fnEncode($cod_contrat)?>',
			// cod_empresa = '<?=fnEncode($cod_empresa)?>',
			// qtd_loja = $('input:checkbox:checked').length;

			// if($("#LOG_UNIVEND_"+index).prop("checked") == true){
			// 	var log_univend = "S";
			// }else{
			// 	var log_univend = "N";
			// }

			// $.ajax({
			// 	method: 'POST',
			// 	url: 'ajxUnidadesContrato.php',
			// 	data: {
			// 			COD_UNIVEND: cod_univend, 
			// 			LOG_UNIVEND: log_univend, 
			// 			COD_EMPRESA: cod_empresa, 
			// 			COD_CONTRAT: cod_contrat
			// 	},
			// 	success:function(data){
			// 		try { 
			// 			parent.$('#QTD_LOJA_<?=$cod_contrat?>').text(qtd_loja);
			// 		} catch(err) {}
			// 	}
			// });	
		
		}	
		
		
	</script>	