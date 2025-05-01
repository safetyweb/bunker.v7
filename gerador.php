<?php

	//echo fnDebug('true');
	
	$hashLocal = mt_rand();	
	$mostraPagina = "false";
	
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

			$basedados = $_REQUEST['BASEDADOS'];
			$tabelas = $_REQUEST['TABELAS'];
			$lista = $_REQUEST['LISTA'];
				   
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];	
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':
						$msgRetorno = "Rotinas atualizadas com <strong>sucesso!</strong>";	
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
			
			$mostraPagina = "true";

		}
  
	//fnMostraForm();
	//fnEscreve($mostraPagina);
	//fnEscreve($basedados);
	//fnEscreve($lista);

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
									<?php include "atalhosPortlet.php"; ?>
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
											
											<div class="row">
												
												<div class="col-md-3">
													<div class="form-group">
														<label for="inputName" class="control-label">Conexão DB</label>
															<select data-placeholder="Selecione um database" name="BASEDADOS" id="BASEDADOS" class="chosen-select-deselect">
																<option value="">&nbsp;</option>					
																<?php
																	
																	$sql = "SELECT *
																			FROM information_schema.TABLES  
																			where TABLE_SCHEMA not in('information_schema','mysql','performance_schema','zabbix') group by TABLE_SCHEMA																																	
																			";
																	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																
																	while ($qrListaDB = mysqli_fetch_assoc($arrayQuery))
																	  {	
																		if ($qrListaDB['TABLE_SCHEMA'] == "teste_marka") {$tipoSAdm = " (ADM)";} else {$tipoSAdm = "";}
																		echo"
																			  <option value='".$qrListaDB['TABLE_SCHEMA']."'>".$qrListaDB['TABLE_SCHEMA'].$tipoSAdm."</option> 
																			"; 
																		  }
																?>	
															</select>
															<div class="help-block with-errors"></div>
													</div>
												</div>
												
												<div class="col-md-4">
													<div class="form-group">
														<label for="inputName" class="control-label">Tabelas</label>
															<div id="divId_Tab">
															<select data-placeholder="Selecione a tabela desejada" name="TABELAS" id="TABELAS" class="chosen-select-deselect">
																<option value="">&nbsp;</option>					
																<?php 																	
																	$sql = "SELECT * FROM information_schema.TABLES  
																																						  where  TABLE_SCHEMA='".$qrListaDB['TABLE_SCHEMA']."' and TABLE_NAME not like '%vw_%'";
																	$arrayQuery = mysqli_query($connGERADOR->connGERADOR(),$sql) or die(mysqli_error());
																
																	while ($qrListaTabelas = mysqli_fetch_assoc($arrayQuery))
																	  {														
																		echo"
																			  <option value='".$qrListaTabelas['TABLE_NAME']."'>".$qrListaTabelas['TABLE_NAME']."</option> 
																			"; 
																		  }											
																?>	
															</select>
																
															</div>	
														<div class="help-block with-errors"></div>
													</div>
												</div>
									
												<div class="col-md-3">
													<div class="push20"></div>
													<button type="button" name="COL" id="COL" class="btn btn-primary btn-sm getBtn"><i class="fa fa-table" aria-hidden="true"></i>&nbsp; Carregar Campos</button> 
												</div>
												
											</div>
																						
											<div class="push20"></div>
												
											<div id="divId_Col">	

												<?php if ($mostraPagina == "false") { 
												//tela default sem campos ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
												?>
												
												<div class="push50"></div>
												<hr>	
												<div class="form-group text-right col-lg-12">
													
													  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
													  <button type="button" class="btn btn-primary disabled"><i class="fa fa-code" aria-hidden="true"></i>&nbsp; Gerar Código</button>
													
												</div>
												
												<?php } else  { 
												//montagem das rotinas dinâmicas +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
												?>

												<div class="row">
													
													<div  class="col-sm-12">

														<div class="col-xs-1"> <!-- required for floating -->
														  <!-- Nav tabs -->
														  <ul class="vTab nav nav-tabs tabs-left text-center">
															<li class="vTab text-center"><a href="#abaColunas" data-toggle="tab">
															<i class=" fa fa-table" style="margin: 10px 0 2px 0"></i><br/></a></li>
															<li class="active vTab text-center"><a href="#abaForm" data-toggle="tab">
															<i class="fa fa-file-text-o" style="margin: 10px 0 2px 0"></i><br/></a></li>
															<li class="vTab text-center"><a href="#abaCodigos" data-toggle="tab">
															<i class="fa fa-code" style="margin: 10px 0 2px 0"></i><br/></a></li>
															<li class="vTab text-center disabled"><a href="#abaBD" data-toggle="tab">
															<i class="fa fa-database" style="margin: 10px 0 2px 0"></i><br/></a></li>
														  </ul>
														</div>
														
														<div class="col-xs-11">
														  <!-- Tab panes -->
														  <div class="tab-content">
															<div class="tab-pane" id="abaColunas" style="padding: 0 20px 0 20px;">
																<h4 style="margin: 0 0 5px 0;">Colunas da Tabela </h4>
															
																<div class="row" style="padding: 20px;">
																										
																<div class="col-md-2">   
																	<div class="form-group">
																		<label for="inputName" class="control-label">Tela com Lista</label> 
																		<div class="push5"></div>
																			<label class="switch">
																			<input type="checkbox" name="LISTA" id="LISTA" class="switch" value="S" >
																			<span></span>
																			</label>
																	</div>
																</div>

																<div class="push10"></div>	
																
																<?php
																$sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS   
																		where   TABLE_SCHEMA = '".$basedados."' and TABLE_NAME = '".$tabelas."'";
																$arrayQuery = mysqli_query($connGERADOR->connGERADOR(),$sql) or die(mysqli_error());
																//fnEscreve($sql);
																?>	                                              

																<table class="table table-striped" style="margin-bottom: 0;">
																	
																	<thead>
																	<tr>
																	<th></th>
																	<th>Coluna</th>
																	<th>Tipo/Tam./Def.</th>
																	<th>Default</th>
																	<th>Lista</th>
																	<th>Título</th>
																	</tr>
																	</thead>
																
																	<tbody>
																	<?php
																	$valor=0;
																	while ($qrListaTabelas = mysqli_fetch_assoc($arrayQuery))
																	{														

																	?>
																	<tr>
																		<td><input type="checkbox" style="height: 18px; width:18px;" value="<?php echo $qrListaTabelas['COLUMN_NAME']; ?>" name="colCampo[]"/> &nbsp; </td>
																		<td><?php echo $qrListaTabelas['COLUMN_NAME']; ?></td>
																		<td><?php echo $qrListaTabelas['DATA_TYPE'];?> | <?php echo $qrListaTabelas['CHARACTER_MAXIMUM_LENGTH']; ?> | <?php echo $qrListaTabelas['COLUMN_DEFAULT']; ?></td>
																		<td>
																			<select style="width:80px;" name="colTipo[]">
																				<option value=""></option>
																				<option value="text">texto</option>
																				<option value="select">combo</option>
																				<option value="radio">radio</option>
																				<option value="check">check</option>
																				<option value="password">senha</option>
																			</select>
																		</td>
																		<td><input type="checkbox" style="height: 18px; width:18px;" value="" name="colCombo[]" multiple="multiple"/> &nbsp; </td>
																		<td><div style="min-width: 80px; min-height: 25px; border-bottom: 1px solid #cecece; cursor:text;" contentEditable="true"></div></td>
																	</tr>

																	<?php
																	$valor++;    
																	}?>
																	</tbody>
																
																</table>													
																								
																</div>
															
															</div>
															
															<div class="tab-pane active" id="abaForm">
																<h4 style="margin: 0 0 5px 0;">Formulário Gerado </h4>
															
																<div class="row pull-right" style="padding: 20px;">

																	<div style="float: right;">
																		<button type="button" name="COPIA" id="COPIA" class="btn btn-info btn-sm getBtn"><i class="fa fa-copy" aria-hidden="true"></i>&nbsp; Copiar para Clipboard</button> 
																	</div>
																</div>
																
																<div class="row" style="padding: 20px;">
																	
																	<div class="push20"></div>
																
																<?php 

if (isset($_POST['colCampo'])){
$Arr_COD_MULTEMP = $_POST['colCampo'];
	
	$code.= '
	<fieldset>
		<legend>Nome do Bloco</legend> 
	
			<div class="row">
	';

	for ($i=0;$i<count($Arr_COD_MULTEMP);$i++) 
	{ 

	$code.='
				<div class="col-md-3">
					<div class="form-group">
						<label for="inputName" class="control-label">'.$Arr_COD_MULTEMP[$i].'</label>
						<input type="text" class="form-control input-sm" name="'.$Arr_COD_MULTEMP[$i].'" id="'.$Arr_COD_MULTEMP[$i].'" value="">
					</div>
					<div class="help-block with-errors"></div>
				</div>       
	';    
	}
	
	
	$code.= '	
			</div>
			
			<div class="push10"></div>
			
	</fieldset>
	
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
	
	';
	if ($lista = "S") {
		
	$codeLista= "	
	<div class='push50'></div>
	
	<div class='col-lg-12'>

		<div class='no-more-tables'>
	
			<form name='formLista'>
			
			<table class='table table-bordered table-striped table-hover'>
			  <thead>
				<tr>
				  <th class='bg-primary' width='40'></th>
				  <th class='bg-primary'>Código</th>
				  <th class='bg-primary'>Categoria</th>
				  <th class='bg-primary'>Nome do Submenu</th>
				</tr>
			  </thead>
			<tbody>
			  
				<tr>
				  <td align='center'><input type='radio' name='radio1' onclick='retornaForm('".'$count'."')'></th>
				  <td>'".'$qrBuscaModulos["COD_SEGITEM"]'."'</td>
				  <td>'".'$qrBuscaModulos["NOM_SEGMENT"]'."'</td>
				  <td>'".'$qrBuscaModulos["NOM_SEGITEM"]'."'</td>
				</tr>
				
			</tbody>
			</table>
			
			</form>

		</div>
		
	</div>										

<div class='push'></div>	
	
	";	
	}	
	
	echo $code ;	
	
	$textArea = '<div id="AREACODE_OFF" style="display: none;"><textarea id="AREACODE" rows="1" style="width: 100%;">'.$code.' '.$codeLista.'</textarea></div>';

}else{$cod_multemp = "0";}
																
																
																?>
																
																<div class="push50"></div>
																
																<?php echo $textArea; ?>
																<?php echo $codeLista; ?>
																
																															
																</div>
															
															
															</div>
															<div class="tab-pane" id="abaCodigos">
																<h4 style="margin: 0 0 5px 0;">Códigos Gerados </h4>
																
																<div class="push20"></div>
															
																<div class="row">

																	<div class="col-md-6">
																	
																		<fieldset>
																			<legend>Retorno (Javascript) </legend> 

																			<div class="row">
																			
																				<div class="col-md-12">
																			
																			
																				</div>

																			</div>
																			
																		</fieldset>
																		
																	</div>

																	<div class="col-md-6">

																		<fieldset>
																			<legend>Retorno (Php) </legend> 

																			<div class="row">
																			
																				<div class="col-md-12">
																			
																			
																				</div>

																			</div>
																			
																		</fieldset>

																	</div>

																</div>
															
															
															</div>
															<div class="tab-pane" id="abaBD">
															
															</div>
														  </div>
														</div>

														<div class="clearfix"></div>

													</div>

													<div class="push10"></div>
													<hr>	
													<div class="form-group text-right col-lg-12">
														
														  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
														  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-code" aria-hidden="true"></i>&nbsp; Gerar Código</button>
														
													</div>

													<input type="hidden" name="opcao" id="opcao" value="">
													<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
													<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		


													<div class="push50"></div>
													<div class="push"></div>

												</div>	
									
											
											<?php } ?>
											
											</div>
											
											
										</form>

								<div class="push20"></div>
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div> 
						
	<script type="text/javascript">
	
		$(document).ready(function() {
			
			$(".disabled").click(function (e) {
					e.preventDefault();
					return false;
			});
			
		});
		
		$("#COPIA").click(function(){			
			$("#AREACODE_OFF").show();
			$("#AREACODE").select();
			document.execCommand('copy');
			$("#AREACODE_OFF").hide();
		});		
		
		// ajax
		$("#BASEDADOS").change(function () {
			var codBusca = $("#BASEDADOS").val();
			buscaTabelas(codBusca,0);
			//alert(codBusca);
		});

		$("#COL").click(function () {
			var codBusca = $("#BASEDADOS").val();
			var codBusca2 = $("#TABELAS").val();
			buscaColunas(codBusca, codBusca2);
			//alert(codBusca);
		});

		function buscaTabelas(idDB, idTab) {
			$.ajax({
				type: "GET",
				url: "ajxBuscaTabelas.php",
				data: { ajx1:idDB, ajx2:idTab},
				beforeSend:function(){
					$('#divId_Tab').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#divId_Tab").html(data); 
				},
				error:function(){
					$('#divId_Tab').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}

		function buscaColunas(idDB,idTab) {
			$.ajax({
				type: "GET",
				url: "ajxBuscaColunas.php",
				data: { ajx1:idDB, ajx2:idTab },
				beforeSend:function(){
					$('#divId_Col').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#divId_Col").html(data); 
				},
				error:function(){
					$('#divId_Col').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}

		<?php if ($mostraPagina == "true") { ?>
		$("#BASEDADOS").val("<?php echo $basedados; ?>").trigger("chosen:updated"); 
		buscaTabelas('<?php echo $basedados; ?>','<?php echo $tabelas ; ?>');
		<?php } ?> 
		
		function retornaForm(index){
			$("#formulario #ID").val($("#ret_ID_"+index).val());
			$("#formulario #DES_SISTEMA").val($("#ret_DES_SISTEMA_"+index).val());
			$("#formulario #DES_ABREVIA").val($("#ret_DES_ABREVIA_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	