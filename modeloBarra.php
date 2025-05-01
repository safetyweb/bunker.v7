<?php
	
	//echo "<h5>_".$opcao."</h5>";

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
	
	//fnMostraForm();

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
									
									<?php $abaEmpresa = 1025; include "abasEmpresaConfig.php"; ?>
									
									<div class="push30"></div> 
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
									
										<fieldset>
											<legend>Teste</legend>
											<div class="row">

												<div class="col-xs-4 col-xs-offset-4">
												    <div class="input-group activeItems">
										                <div class="input-group-btn search-panel">
										                    <button type="button" class="btn btn-outline dropdown-toggle form-control rounded-left" id="FILTERS" data-toggle="dropdown">
										                    	<span id="search_concept">Filtros</span>&nbsp;
										                    	<span class="fal fa-caret-down"></span>										                    	
										                    </button>
										                    <ul class="dropdown-menu" role="menu">
										                      <li><a href="#PRODUTO">Produto</a></li>
										                      <li><a href="#CATEGORIA">Categoria</a></li>
										                      <li><a href="#CODIGO">Código</a></li>
										                      <li><a href="#less_than">Less than < </a></li>
										                      <li class="divider"></li>
										                      <li><a href="#all">Sem filtro</a></li>
										                    </ul>
										                </div>
										                <input type="hidden" name="VAL_PESQUISA" value="all" id="VAL_PESQUISA">         
										                <input type="text" id="INPUT" class="form-control remove-side-borders" name="INPUT" onkeyup="buscaRegistro(this)">
										                <div class="input-group-btn"id="CLEARDIV">
										                	<button class="btn btn-outline form-control remove-side-borders" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
										                </div>
										                <div class="input-group-btn">
										                    <button class="btn btn-outline form-control rounded-right" id="SEARCH" type="button">&nbsp;<span class="fal fa-search"></span></button>
										                </div>
										            </div>
										        </div>

											</div>
										</fieldset>

										<div class="push30"></div>
																				
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
											  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											  <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
											
										</div>
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover tableSorter buscavel">
												  <thead>
													<tr>
													  <th class="{ sorter: false }" width="40"></th>
													  <th>Código</th>
													  <th>Nome do Grupo</th>
													  <th>Categoria</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
												
													$sql = "SELECT PC.COD_PRODUTO, PC.DES_PRODUTO, CT.DES_CATEGOR FROM PRODUTOCLIENTE PC 
															LEFT JOIN CATEGORIA CT ON PC.COD_CATEGOR = CT.COD_CATEGOR 
															WHERE PC.COD_EMPRESA = 7 AND PC.COD_CATEGOR != 0 AND PC.LOG_IMPORT = 'S' AND PC.COD_USUCADA = 11478 
															ORDER BY RAND() limit 20";
													$arrayQuery = mysqli_query(connTemp(7,""),trim($sql)) or die(mysqli_error());
													//fnEscreve($sql);
													$count=0;
													while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;	
														echo"
															<tr>
															  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrBuscaModulos['COD_PRODUTO']."</td>
															  <td>".$qrBuscaModulos['DES_PRODUTO']."</td>
															  <td>".$qrBuscaModulos['DES_CATEGOR']."</td>
															</tr>
															<input type='hidden' id='ret_COD_PRODUTO_".$count."' value='".$qrBuscaModulos['COD_PRODUTO']."'>
															<input type='hidden' id='ret_DES_PRODUTO_".$count."' value='".$qrBuscaModulos['DES_PRODUTO']."'>
															<input type='hidden' id='ret_DES_PRODUTO_".$count."' value='".$qrBuscaModulos['DES_CATEGOR']."'>
															"; 
														  }											

												?>
													
												</tbody>
												</table>
												
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
	
	<script type="text/javascript">

	//Barra de pesquisa essentials ------------------------------------------------------
		$(document).ready(function(e){

		    $('.search-panel .dropdown-menu').find('a').click(function(e) {
				e.preventDefault();
				var param = $(this).attr("href").replace("#","");
				var concept = $(this).text();
				$('.search-panel span#search_concept').text(concept);
				$('.input-group #VAL_PESQUISA').val(param);
				$('#INPUT').focus();
			});

		    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function(){
			    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItems");
		    });

		    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function(){
		    	$("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItems");
		    });

		    $('#CLEAR').click(function(){
		    	$('#INPUT').val('');
		    	$('#INPUT').focus();
		    	$('#CLEARDIV').hide();
		    });
		    
		});

		function buscaRegistro(el){
			var filtro = $('#search_concept').text().toLowerCase();

			if(filtro == "filtros" || filtro == "sem filtro"){
			    var value = $(el).val().toLowerCase().trim();
			    if(value != ""){
			    	$('#CLEARDIV').show();
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
				
	</script>	