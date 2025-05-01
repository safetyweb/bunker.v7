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

			$cod_grupotr = fnLimpaCampoZero($_REQUEST['COD_GRUPOTR']);
			$des_grupotr = fnLimpaCampo($_REQUEST['DES_GRUPOTR']);
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];


			// - variáveis da barra de pesquisa -------------
			$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);			
			$val_pesquisa = fnLimpaCampo($_POST['INPUT']);
			// ----------------------------------------------

						
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
	
	//busca perfil do usuário 
	//18 - mais cash
	$sql1 = "select cod_usuario,cod_defsist,cod_perfils
			from usuarios
			where cod_empresa = ".$_SESSION["SYS_COD_EMPRESA"]." and
				  cod_defsist = 18 and
				  cod_usuario = ".$_SESSION["SYS_COD_USUARIO"]." ";
				  
	//fnEscreve($sql1);			  
	if ($_SESSION["SYS_COD_SISTEMA"] == 3){
		$cod_perfils = '9999';	
		
	} else {
		$arrayQuery1 = mysqli_query($connAdm->connAdm(),$sql1) or die(mysqli_error());
		$qrBuscaPerfil = mysqli_fetch_assoc($arrayQuery1);
		$cod_perfils = $qrBuscaPerfil['cod_perfils'];	
	}
	  
	//busca modulos autorizados
	$sql2 = "select cod_modulos from perfil
			where cod_sistema=18 and
			cod_perfils in($cod_perfils)";
	
	//fnEscreve($sql2);			
	$arrayQuery2 = mysqli_query($connAdm->connAdm(),$sql2) or die(mysqli_error());
	
	$count=0;
	while ($qrBuscaAutorizacao = mysqli_fetch_assoc($arrayQuery2))
	  {
		$cod_modulos_aut = $qrBuscaAutorizacao['cod_modulos'];
		$modulosAutorizados = $modulosAutorizados.$cod_modulos_aut.",";
	  }
	   
	   $arrayAutorizado = explode(",", $modulosAutorizados);
	
	
	//fnEscreve($sql2);

	$arrayParamAutorizacao = array('COD_MODULO'=>"9999",
						'MODULOS_AUT'=>$arrayAutorizado,
						'COD_SISTEMA'=>$_SESSION["SYS_COD_SISTEMA"]);

	//echo "<pre>";	
	//print_r($arrayParamAutorizacao);	
	//echo "</pre>";	


	// esquema do X da barra - (recarregar pesquisa)
	if($val_pesquisa != ""){
		$esconde = " ";
	}else{
		$esconde = "display: none;";
	}
	// ---------------------------------------------
	
	
	//fnEscreve($_SESSION["SYS_COD_SISTEMA"]);	
	//fnMostraForm();
	//fnEscreve($modulosRelatorios);
?>


<style>


#services {}
#services .services-top {
    padding: 70px 0 50px;
}
#services .services-list {
    padding-top: 50px;
}
.services-list .service-block {
    margin-bottom: 25px;
}
.services-list .service-block .ico {
    font-size: 38px;
    float: left;
}
.services-list .service-block .text-block {
    margin-left: 58px;
}
.services-list .service-block .text-block .name {
    font-size: 20px;
    font-weight: 900;
    margin-bottom: 5px;
}
.services-list .service-block .text-block .info {
    font-size: 16px;
    font-weight: 300;
    margin-bottom: 10px;
}
.services-list .service-block .text-block .text {
    font-size: 12px;
    line-height: normal;
    font-weight: 300;
}
.highlight {
    color: #2ac5ed;
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
										<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
									</div>
									
									<?php 
									switch ($_SESSION["SYS_COD_SISTEMA"]) {
										case 3: //adm marka
											$formBack = "1189";
											break;
										case 18: //mais cash
											$formBack = "1681";
											break;
									}
									
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
									//1190 - Lista relatórios - adm
									//1189 - Lista relatórios - campanhas
									if (fnDecode($_GET['mod']) == 1182){
										$abaCampanhas = 1182;

										//liberação das abas
										$abaPersona	= "S";
										$abaVantagem = "S";
										$abaRegras = "N";
										$abaComunica = "N";
										$abaAtivacao = "N";
										$abaResultado = "N";

										//$abaPersonaComp = "completed ";
										$abaPersonaComp = "";
										$abaCampanhaComp = " ";
										$abaRegrasComp = "";
										$abaComunicaComp = "";
										$abaResultadoComp = "active ";
										//revalidada na aba de regras	
										$abaAtivacaoComp = "";	
										
										include "abasCampanhasConfig.php";
										echo "<div class='push30'></div>";
										}						
									//fnEscreve()	
									?>
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																													
										<!-- barra de pesquisa -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------  -->
									
										<div class="row" style="display: none;">
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
											                    <!-- <li><a href="#NOM_EMPRESA">Razão social</a></li>
											                    <li><a href="#NOM_FANTASI">Nome fantasia</a></li>
											                    <li><a href="#NUM_CGCECPF">CNPJ</a></li> -->										                      
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

										<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->

										<div class="row">
											<div class="services-list buscavel">
											
												<div class="row" style="margin: 0 0 0 1px;">
												
													<div class="col-sm-6 col-md-4">
														<div class="service-block" style="visibility: visible;">
															<div class="ico fal fa-users highlight"></div>
															<div class="text-block">
																<h4>Clientes</h4>
																<div class="text">Seu tesouro está aqui</div>
																<div class="push10"></div>
																
																<?php if(fnControlaAcesso("1685",$arrayParamAutorizacao) === true) { ?>
																<a href="https://adm.bunker.mk/action.do?mod=<?php echo fnEncode(1685)."&id=".fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Tokens Enviados</a> <br/>
																<?php } ?>	
																
																<?php if(fnControlaAcesso("1102",$arrayParamAutorizacao) === true) { ?>
																<a href="https://adm.bunker.mk/action.do?mod=<?php echo fnEncode(1102)."&id=".fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Clientes</a> <br/>
																<?php } ?>
																
																<?php if(fnControlaAcesso("1245",$arrayParamAutorizacao) === true) { ?>
																<a href="https://adm.bunker.mk/action.do?mod=<?php echo fnEncode(1245)."&id=".fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Vendas Estornadas</a> <br/>
																<?php } ?>
																
																<?php if(fnControlaAcesso("1229",$arrayParamAutorizacao) === true) { ?>
																<a href="https://adm.bunker.mk/action.do?mod=<?php echo fnEncode(1229)."&id=".fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Clientes Top 100</a> <br/>
																<?php } ?>
																
																
															</div>
														</div>
													</div>	

												</div>
													
												
												
											</div>
											
										</div>
        
             										
		
										
										</form>
										
										<div class="push50"></div>
									
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
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
				
	</script>	