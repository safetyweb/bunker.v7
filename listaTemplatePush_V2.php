	<?php
	
	//echo fnDebug('true');

	// definir o numero de itens por pagina
	$itens_por_pagina = 20;	
	$pagina  = "1";
	
	$log_ativo = 'N';

	if(isset($_GET['pop'])){
		$popUp = fnLimpaCampo($_GET['pop']);
	}else{
		$popUp = '';
	}
	
	$cod_template = "";
 
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
			
			$cod_template = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}
			$nom_template = fnLimpaCampo($_REQUEST['NOM_TEMPLATE']);
			$abv_template = fnLimpaCampo($_REQUEST['ABV_TEMPLATE']);
			$des_template = fnLimpaCampo($_REQUEST['DES_TEMPLATE']);
			// - variáveis da barra de pesquisa -------------
			$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);			
			$val_pesquisa = fnLimpaCampo($_POST['INPUT']);
			// ----------------------------------------------
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
                      
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
		$cod_campanha = fnDecode($_GET['idc']);
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
	      
	//fnMostraForm();
	//fnEscreve($cod_campanha);

?>

<style>
	body{
		overflow: hidden;
	}
	.change-icon .fa + .fa,
	.change-icon:hover .fa:not(.fa-edit) {
	  display: none;
	}
	.change-icon:hover .fa + .fa:not(.fa-edit){
	  display: inherit;
	}
	
	.fa-edit:hover{
		color: #18bc9c;
		cursor: pointer;
	}

	.tile{
		cursor: pointer;
	}
	
	.item{
		padding-top: 0;
	}
</style>

	<link rel="stylesheet" href="css/widgets.css" />
	
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
						
						<div class="row">
						
							<div class="col-md-12">
								<h4 style="margin: 0 0 5px 0;"><span class="bolder">Lista de Templates</span></h4>
							</div>

						</div>
						
						<!-- barra de pesquisa -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------  -->
						<div class="push30"></div>

						<div class="row">
							<form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

								<!-- <div class="col-md-4">   
									<div class="form-group">
										<label for="inputName" class="control-label">Mostrar todas as templates</label> 
										<div class="push5"></div>
										<label class="switch">
										<input type="checkbox" name="LOG_ALL" id="LOG_ALL" class="switch" value="S" onchange="reloadPage(1)" <?=($joinTempl != '' ? '' : 'checked')?> >
										<span></span>
									</label>
									</div>
								</div> -->

								<div class="col-xs-4 col-md-offset-4">

								    <div class="input-group activeItem">
						                <div class="input-group-btn search-panel">
						                    <button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
						                    	<span id="search_concept">Sem filtro</span>&nbsp;
						                    	<span class="far fa-angle-down"></span>										                    	
						                    </button>
						                    <ul class="dropdown-menu" role="menu">
						                    	<li class="divisor"><a href="#">Sem filtro</a></li>
						                    	<li class="divider"></li>
						                    	<li><a class="item-filtro" href="#NOM_TEMPLATE">Nome da Template</a></li>
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

						        <div class="col-md-4">
									<a href="javascript:void(0)" class="btn btn-xs btn-info addBox pull-right" data-url="action.php?mod=<?php echo fnEncode(1877)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>&tipo=<?php echo fnEncode('CAD')?>&pop=true" data-title="Template do SMS" onclick='try {parent.abreModalPai($(this).attr("data-url"),$(this).attr("data-title"),"sm")} catch(err) {}'><i class="fa fa-plus fa-2x" aria-hidden="true" style="padding: 5px 5px;"></i></a>
								</div>
						         	
						        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />							
								<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							</form>
						    
						</div>

						<div class="push30"></div>

						<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->

						<div class="push20"></div>

						<div class="col-md-12">									
													
							<table class="table table-bordered table-striped table-hover tablesorter buscavel">

							  	<thead>
									<tr>
										<th>Nome da Template</th>
										<th>Data de Criação</th>
										<th>Data de Alteração</th>
										<th>Ativo</th>
										<th class="{sorter:false}"></th>
									</tr>
							  	</thead>
							  
								<tbody id="listaTemplates">

									<?php 

										if ($filtro != "") {
											$andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
										} else {
											$andFiltro = " ";
										}

										$sql = "SELECT COD_TEMPLATE FROM TEMPLATE_PUSH WHERE cod_empresa = $cod_empresa AND LOG_ATIVO = 'S' $andFiltro";	
			
										//fnEscreve($sql);
										$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
										$total_itens_por_pagina = mysqli_num_rows($retorno);
										
										$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);	

										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										$sql = "SELECT * FROM TEMPLATE_PUSH
										WHERE cod_empresa = $cod_empresa 
										AND LOG_ATIVO = 'S'
										$andFiltro
										ORDER BY DAT_CADASTR DESC
										LIMIT $inicio,$itens_por_pagina";
												
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
										
										$count=0;
										while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)){														  
											$count++;	

											// fnEscreve('teste');

											if($qrBuscaModulos['LOG_ATIVO'] == "S"){
												$ativo = "<span class='fas fa-check text-success' style='padding: 5px 5px;'></span>";
											}else{
												$ativo = "<span class='fas fa-times text-danger' style='padding: 5px 5px;'></span>";
											}

											if($qrBuscaModulos['DAT_ALTERAC'] != ""){
												$alteradoEm = fnDataShort($qrBuscaModulos['DAT_ALTERAC']);
											}else{
												$alteradoEm = "";
											}

											?>

												<tr>
										           <td><?=$qrBuscaModulos['NOM_TEMPLATE']?></td>
										           <td><small><?=fnDataFull($qrBuscaModulos['DAT_CADASTR'])?></td>
										           <td><small><?=$alteradoEm?></td>
										           <td class='text-center'>
										                 <?=$ativo?>
										           </td>
										           <td class="text-center">
									           		<small>
									           			<div class="btn-group dropdown dropleft">
															<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																ações &nbsp;
																<span class="fas fa-caret-down"></span>
														    </button>
															<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																<li>
																	<a data-url="action.php?mod=<?=fnEncode(1877)?>&id=<?=fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>&idT=<?=fnEncode($qrBuscaModulos['COD_TEMPLATE'])?>&tipo=<?=fnEncode('ALT')?>&pop=true" 
																	   data-title="Template Push" 
																	   onclick='try {parent.abreModalPai($(this).attr("data-url"),$(this).attr("data-title"),"sm")} catch(err) {}'>Editar
																	</a>
																</li>
																<li class="divider"></li>
																<li><a href="javascript:void(0)" onclick='clonaTemplate("<?=fnEncode($qrBuscaModulos[COD_TEMPLATE])?>")'>Clonar Template</a></li>
																<li><a href="javascript:void(0)" onclick='excTemplate("<?=fnEncode($qrBuscaModulos[COD_TEMPLATE])?>")'>Excluir</a></li>
															</ul>
														</div>
									           		</small>
									           	   </td>
										        </tr>
									<?php 
										}
									?>

							    </tbody>

							    <tfoot>
									<tr>
									  <th class="" colspan="100">
										<center><ul id="paginacao" class="pagination-sm"></ul></center>
									  </th>
									</tr>
								</tfoot>

							</table>
						
						</div>

						<div class="push100"></div> 
						<div class="push100"></div>
						<div class="col-md-12">
							<a href="javascript:void(0)" class="btn btn-primary" onclick="parent.$('#AUTOMACAO').click();">Próximo Passo&nbsp;&nbsp;<span class="fal fa-arrow-right"></span></a>
						</div>
						<div class="push100"></div>		
								
								
								
						<input type="hidden" class="input-sm" name="REFRESH_TEMPLATES" id="REFRESH_TEMPLATES" value="N">
							
						<div class="push20"></div> 
	
	<script type="text/javascript">

		parent.$("#conteudoAba").css("height", ($(".portlet").height()+50) + "px");

		var current_page = 1;

		<?php include 'barraPesquisaJS.js'; ?>

		$(document).ready(function(){

			var numPaginas = <?php echo $numPaginas; ?>;
			if(numPaginas != 0){
				carregarPaginacao(numPaginas);
			}
			

			jQuery('#paginacao').on('page',function(event, page) {
			    current_page = page;
			    // console.log('current_page', current_page);
			});
			
			//modal close
			parent.$('.modal').on('hidden.bs.modal', function () {
				reloadPage(current_page);
				parent.$("#conteudoAba").css("height", ($(".portlet").height()+50) + "px");
			});
			
		});

		function excTemplate(idTemp) {
			parent.$.alert({
	          title: "Confirmação",
	          content: "Deseja mesmo excluir a template?",
	          type: 'red',
	          buttons: {
	            "Excluir": {
	               btnClass: 'btn-danger',
	               action: function(){
	               	    $.ajax({
							type: "POST",
							url: "ajxListaTemplatePush_V2.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($cod_campanha); ?>&idPage="+current_page+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
							data: {COD_TEMPLATE:idTemp},
							beforeSend:function(){
								$('#listaTemplates').html('<div class="loading" style="width: 100%;"></div>');
							},
							success:function(data){
								$("#listaTemplates").html(data);		
								parent.$("#conteudoAba").css("height", ($(".portlet").height()+50) + "px");					
							},
							error:function(){
								$('#listaTemplates').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
							}
						});
	               }
	            },
	            "Cancelar": {
	               action: function(){
	                
	               }
	            }
	          }
	        });		
		}

		function clonaTemplate(idTemp) {
			parent.$.alert({
	          title: "Confirmação",
	          content: "Deseja clonar a template?",
	          type: 'yellow',
	          buttons: {
	            "Clonar": {
	               btnClass: 'btn-warning',
	               action: function(){
	               	    $.ajax({
							type: "POST",
							url: "ajxListaTemplatePush_V2.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($cod_campanha); ?>&idPage="+current_page+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>&clone=true",
							data: {COD_TEMPLATE:idTemp},
							beforeSend:function(){
								$('#listaTemplates').html('<div class="loading" style="width: 100%;"></div>');
							},
							success:function(data){
								$("#listaTemplates").html(data);		
								parent.$("#conteudoAba").css("height", ($(".portlet").height()+50) + "px");					
							},
							error:function(){
								$('#listaTemplates').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
							}
						});
	               }
	            },
	            "Cancelar": {
	               action: function(){
	                
	               }
	            }
	          }
	        });		
		}

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxListaTemplatePush_V2.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($cod_campanha); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
				beforeSend:function(){
					$('#listaTemplates').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#listaTemplates").html(data);		
					//console.log(data);					
				},
				error:function(){
					$('#listaTemplates').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});		
		}

	</script>	