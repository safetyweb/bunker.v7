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
	.dropdown-menu>li>a{
		color: unset;
	}
	.dropdown-menu>li>a:hover{
		text-decoration: none!important;
		background-color: #ECF0F1!important;
		color: #2C3E50!important;
	}
	.dropdown-toggle{
		width: 100%;
	}
	.dropleft ul{
		left: unset;
		right: 70%;
	}
</style>

	<link rel="stylesheet" href="css/widgets.css" />

	<div class="push30"></div>
	
	<div class="row">				
	
		<div class="col-md-12 margin-bottom-30">
			<!-- Portlet -->							
				<div class="portlet portlet-bordered">
				
					<div class="portlet-title">
						<div class="caption">
							<i class="fal fa-terminal"></i>
							<span class="text-primary"><?php echo $NomePg; ?></span>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
					<div class="portlet-body">
						
						<?php if ($msgRetorno <> '') { ?>	
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						 <?php echo $msgRetorno; ?>
						</div>
						<?php } ?>					
						
						<div class="col-md-12"><h4><span class="bolder">Lista de Contatos</span></div>
						
						<div class="pull-right">
							<a href="javascript:void(0)" class="btn btn-xs btn-info addBox pull-right" data-url="action.php?mod=<?php echo fnEncode(1559)?>&id=<?php echo fnEncode($cod_empresa)?>&tipo=<?php echo fnEncode('CAD')?>&pop=true" data-title="Adicionar Comunicação Avulsa" onclick='try {parent.abreModalPai($(this).attr("data-url"),$(this).attr("data-title"),"sm")} catch(err) {}'><i class="fa fa-plus fa-2x" aria-hidden="true" style="padding: 5px 5px;"></i></a>
						</div>	
						
						</h4>
						<div class="push20"></div>

						<div class="col-md-12">									
													
							<table class="table table-bordered table-striped table-hover tablesorter">

							  	<thead>
									<tr>
										<th>Nome da Comunicação</th>
										<th>Data de Criação</th>
										<th>Contatos</th>
										<th>Status</th>
										<th class="{sorter:false}"></th>
									</tr>
							  	</thead>
							  
								<tbody id="listaTemplates">

									<?php 

										$sql = "SELECT COD_COMUNICA FROM COMUNICACAO_AVULSA WHERE cod_empresa = $cod_empresa";	
			
										//fnEscreve($sql);
										$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
										$total_itens_por_pagina = mysqli_num_rows($retorno);
										
										$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);	

										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										$sql = "SELECT CA.*, CP.QTD_LISTA, CP.LOG_ATIVO, CP.DES_MENSAGEM FROM COMUNICACAO_AVULSA CA
										LEFT JOIN COMUNICAAV_PARAMETROS CP ON CP.COD_LISTA = CA.COD_LISTA
										WHERE CA.cod_empresa = $cod_empresa 
										ORDER BY CA.DAT_CADASTR DESC
										LIMIT $inicio,$itens_por_pagina";
												
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

										$view = 100;
										
										$count=0;
										while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)){														  
											$count++;

											if($qrBuscaModulos['DES_MENSAGEM'] == ""){
												$status = "Sem mensagem para envio";
											}else if($qrBuscaModulos['LOG_ATIVO'] == 0){
												$status = "Lista enviada";
											}else{
												$status = "Enviando";
											}	

											?>

												<tr>
										           	<td><?php echo $qrBuscaModulos['NOM_COMUNICA']; ?></td>
										           	<td><small><?php echo fnDataFull($qrBuscaModulos['DAT_CADASTR']); ?></td>
										           	<td><small><?php echo fnValor($qrBuscaModulos['QTD_LISTA'],0); ?></td>
										           	<td class="text-center"><small><?=$status?></small></td>
									           		<td class="text-center">
										           		<small>
										           			<div class="btn-group dropdown dropleft">
																<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																	ações &nbsp;
																	<span class="fas fa-caret-down"></span>
															    </button>
																<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																	<li class="text-info"><a href='javascript:void(0)' class='addBox' data-url="action.php?mod=<?php echo fnEncode(1559)?>&id=<?php echo fnEncode($cod_empresa)?>&idC=<?php echo fnEncode($qrBuscaModulos[COD_COMUNICA])?>&pop=true" data-title=""><i class='fas fa-pencil'></i> Editar </a></li>
																	<li class="text-success"><a href="action.do?mod=<?php echo fnEncode(1560);?>&id=<?php echo fnEncode($cod_empresa);?>&idL=<?php echo fnEncode($qrBuscaModulos[COD_LISTA]);?>"><i class='fas fa-external-link-square'></i> Acessar </a></li>
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
								
								
								
						<input type="hidden" class="input-sm" name="REFRESH_TEMPLATES" id="REFRESH_TEMPLATES" value="N">

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
								</div>
							</div>
						</div>	
							
						<div class="push20"></div> 
	
	<script type="text/javascript">

		parent.$("#conteudoAba").css("height","<?=$view?>vh");

		$(document).ready(function(){

			var numPaginas = <?php echo $numPaginas; ?>;
			if(numPaginas != 0){
				carregarPaginacao(numPaginas);
			}
			
			//modal close
			parent.$('.modal').on('hidden.bs.modal', function () {
				if($("#REFRESH_TEMPLATES").val() == "S"){
					reloadPage(1);
					$("#REFRESH_TEMPLATES").val('N');
				}
			});

			$('#popModal').find('.modal-content').css({
	              'width':'70vw',
	              'height':'auto',
	              'marginLeft':'auto',
	              'marginRight':'auto'
	        });
			$('#popModal').find('.modal-dialog').css({
				  'maxWidth':'100vw'
	       	});
			
		});

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxComunicacaoAvulsa.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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
		
		function RefreshTemplates(idEmp) {
			$.ajax({
				type: "GET",
				url: "ajxRefreshTemplatesEmail.php",
				data: { ajx1:idEmp},
				beforeSend:function(){
					$('#listaTemplates').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#listaTemplates").html(data); 
				},
				error:function(){
					$('#listaTemplates').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}
	
		function retornaForm(index){
			$("#formulario #COD_CONVENI").val($("#ret_COD_CONVENI_"+index).val());
			$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
			$("#formulario #COD_ENTIDAD").val($("#ret_COD_ENTIDAD_"+index).val()).trigger("chosen:updated");
			$("#formulario #NUM_PROCESS").val($("#ret_NUM_PROCESS_"+index).val());
			$("#formulario #NUM_CONVENI").val($("#ret_NUM_CONVENI_"+index).val());
			$("#formulario #NOM_CONVENI").val($("#ret_NOM_CONVENI_"+index).val());
			$("#formulario #NOM_ABREVIA").val($("#ret_NOM_ABREVIA_"+index).val());
			$("#formulario #DES_DESCRIC").val($("#ret_DES_DESCRIC_"+index).val());
			$("#formulario #VAL_VALOR").unmask().val($("#ret_VAL_VALOR_"+index).val());
			$("#formulario #VAL_CONTPAR").unmask().val($("#ret_VAL_CONTPAR_"+index).val());
			$("#formulario #DAT_INICINV").unmask().val($("#ret_DAT_INICINV_"+index).val());
			$("#formulario #DAT_FIMCONV").unmask().val($("#ret_DAT_FIMCONV_"+index).val());
			$("#formulario #DAT_ASSINAT").unmask().val($("#ret_DAT_ASSINAT_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');			
		}
		
	</script>	