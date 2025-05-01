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
			
			$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);			
			$val_pesquisa = fnLimpaCampo($_POST['INPUT']);			

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			// if ($opcao != ''){
				
				
			// }  

		}
	}

	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
            
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = ".$cod_empresa;	
				
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaEmpresa)){
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
												
	}else {	
		$nom_empresa = "";
	}

	if(isset($_GET['idC'])){
		if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))){
		
			//busca dados do convênio
			$cod_conveni = fnDecode($_GET['idC']);
			// fnEscreve($cod_conveni);
		}
	}

	if($val_pesquisa != ""){
		$esconde = " ";
	}else{
		$esconde = "display: none;";
	}
//fnEscreve(fnDecode($_GET['mod']));
//fnEscreve($val_pesquisa);
?>
					
				<?php if ($popUp != "true"){  ?>							
					<div class="push30"></div> 
					<?php } ?>
					
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
										<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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
										
										<div class="push30"></div>

										<div class="row">

											<div class="col-xs-4">

												<div class="tabbable-line ">
													<ul class="nav nav-tabs">
														<li>
															<a href="action.do?mod=<?php echo fnEncode(1348)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_conveni); ?>" style="text-decoration: none;">
															<span class="fal fa-arrow-circle-left fa-2x"></span></a>
														</li>
													</ul>
												</div>
												
											</div> 

											<form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

												<div class="col-xs-4">
												    <div class="input-group activeItem">
										                <div class="input-group-btn search-panel">
										                    <button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
										                    	<span id="search_concept">Sem filtro</span>&nbsp;
										                    	<span class="far fa-angle-down"></span>										                    	
										                    </button>
										                    <ul class="dropdown-menu" role="menu">
										                    	<li class="divisor"><a href="#">Sem filtro</a></li>
										                    	<!-- <li class="divider"></li> -->										                      
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

										<div class="push30"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
											
												<form name="formLista" id="formLista" method="post" action="action.php?mod=<?php echo $DestinoPg; ?>&id=0">
										
												<table class="table table-bordered table-striped table-hover tableSorter buscavel">
												  <thead>
													<tr>
													  <th class="{sorter:false}" width="40"></th>
													  <th>Código</th>
													  <th>Contrato</th>
													  <th>Favorecido</th>
													  <th>Data Início</th>
													  <th>Data Fim</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php

													if($filtro != ""){
														$andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
													}else{
														$andFiltro = " ";
													}
												
													$sql = "SELECT CTT.*, CL.NOM_CLIENTE, CR.TIP_CONTROLE FROM CONTRATO CTT 
														LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = CTT.COD_CLIENTE
														LEFT JOIN CONTROLE_RECEBIMENTO CR ON CR.COD_CONTRAT = CTT.COD_CONTRAT 
																							AND CR.COD_CONVENI = CTT.COD_CONVENI
																							AND CR.COD_EMPRESA = CTT.COD_EMPRESA
														WHERE CTT.COD_EMPRESA = $cod_empresa 
														AND CTT.DES_TPCONTRAT = 'LIC' 
														AND CTT.COD_CONVENI = $cod_conveni
														GROUP BY CTT.COD_CONTRAT";															
												
													// fnEscreve($sql);
													
													$arrayQuery =  mysqli_query(connTemp($cod_empresa,''),$sql);
													
													$count=0;
													while ($qrContrat = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;
														
														echo"
															<tr>
															  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td class='text-center'>".$qrContrat['COD_CONTRAT']."</td>
															  <td>".$qrContrat['NRO_CONTRAT']."</td>
															  <td>".$qrContrat['NOM_CLIENTE']."</td>
															  <td>".fnDataShort($qrContrat['DAT_INI'])."</td>
															  <td>".fnDataShort($qrContrat['DAT_FIM'])."</td>
															</tr>
															<input type='hidden' id='ret_IDC_".$count."' value='".fnEncode($qrContrat['COD_CONTRAT'])."'>
															<input type='hidden' id='ret_IDT_".$count."' value='".fnEncode($qrContrat['TIP_CONTROLE'])."'>
															"; 
														  }											
													
												?>
													
												</tbody>
												</table>
												
												<div class="push50"></div>
												
												<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
												<input type="hidden" name="codBusca" id="codBusca" value="">
												<input type="hidden" name="nomBusca" id="nomBusca" value="">	
												
												</form>

											</div>
											
										</div>										
									
									<div class="push"></div>
									
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
	//fnEscreve($RedirectPg);	
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
			
			$("#codBusca").val($("#ret_ID_"+index).val());			
			$("#codBusca").val($("#ret_IDC_"+index).val());			
			$("#nomBusca").val($("#ret_NOM_EMPRESA_"+index).val());
			$('#formLista').attr('action', 'action.do?mod=<?php echo $DestinoPg; ?>&id=<?=fnEncode($cod_empresa)?>&idC=<?=fnEncode($cod_conveni)?>&idCT='+$("#ret_IDC_"+index).val()+'&idTp='+$("#ret_IDT_"+index).val());					
			$('#formLista').submit();					
		}
	
	</script>
	