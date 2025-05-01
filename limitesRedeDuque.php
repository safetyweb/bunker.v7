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
 
			$cod_consumo = fnLimpaCampoZero($_POST['COD_CONSUMO']);			
			$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);			
			$cod_categor = fnLimpaCampoZero($_POST['COD_CATEGOR']);
			$cod_entidad  = fnLimpaCampoZero($_POST['COD_ENTIDAD']);
			$qtd_limite = fnLimpaCampo($_POST['QTD_LIMITE']);
			$cod_tpunida  = fnLimpaCampoZero($_POST['COD_TPUNIDA']);
			$tip_limite  = fnLimpaCampo($_POST['TIP_LIMITE']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];			
			
			if ($opcao != ''){
			
				$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
 
				$sql = "CALL SP_ALTERA_REGRACONSUMO (
				 '".$cod_consumo."', 
				 '".$cod_empresa."', 
				 '".$cod_categor."', 
				 '".$cod_entidad."', 
				 '".fnValorSql($qtd_limite)."',
				 '".$cod_usucada."', 
				 '".$cod_tpunida."', 
				 '".$tip_limite."', 
				 '".$opcao."'    
				) ";
				
				//echo $sql;
	
				mysqli_query(connTemp($cod_empresa,''),trim($sql)) or die(mysqli_error());	
				//fnEscreve($sql2); 

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
			
		if (isset($qrBuscaEmpresa)){
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];

		}
		
		
		//busca dados da entidade
		$cod_entidad = fnDecode($_GET['idC']);	
		$sql = "SELECT COD_ENTIDAD, NOM_ENTIDAD FROM ENTIDADE where COD_EMPRESA = '".$cod_empresa."' and COD_ENTIDAD = '".$cod_entidad."' ";

		//fnEscreve($sql);
		$arrayQuery2 = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
		$qrBuscaEntidade = mysqli_fetch_assoc($arrayQuery2);
			
		if (isset($qrBuscaEntidade)){
			$nom_entidad = $qrBuscaEntidade['NOM_ENTIDAD'];

		}
		
												
	}else {
		$cod_empresa = 0;		
		$nom_empresa = "";		
		$cod_entidad = 0;		
		$nom_entidad = "";
	
	}
	
	//fnMostraForm();
	//fnEscreve($cod_empresa);
	
?>

<style>
table a:not(.btn), .table a:not(.btn) {
    text-decoration: none;
}
table a:not(.btn):hover, .table a:not(.btn):hover {
    text-decoration: underline;
}
</style>

			
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
									
									<?php 
									//menu superior - empresas
									$abaEmpresa = 1183;	
									switch ($_SESSION["SYS_COD_SISTEMA"]) {
										case 14: //rede duque
											include "abasEmpresaDuque.php";
											break;
									}									
									?>									
									
									<div class="push30"></div> 
																
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										<fieldset>
											<legend>Dados Gerais</legend>  
															
												<div class="row">
												
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CONSUMO" id="COD_CONSUMO" value="">
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Empresa</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
															<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
														</div>														
													</div>	
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Cliente</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_ENTIDAD" id="NOM_ENTIDAD" value="<?php echo $nom_entidad ?>">
															<input type="hidden" class="form-control input-sm" name="COD_ENTIDAD" id="COD_ENTIDAD" value="<?php echo $cod_entidad ?>">
														</div>														
													</div>													
												
													<div class="col-md-4">
														<label for="inputName" class="control-label required">Grupo de Produto </label>
														<div class="input-group">
														<span class="input-group-btn">
														<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary addBox" data-url="action.php?mod=<?php echo fnEncode(1043)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?php echo fnEncode($cod_campanha)?>&pop=true" data-title="Busca Produtos"><i class="fa fa-search" aria-hidden="true"></i></a>
														</span>
														<input type="text" name="DES_CATEGOR" id="DES_CATEGOR" class="form-control input-sm leituraOff" style="border-radius: 0 3px 3px  0;" readonly="readonly" placeholder="Buscar grupo de produto...">
														<input type="hidden" name="COD_CATEGOR" id="COD_CATEGOR" value="">
														</div>																
													</div>												
												
												</div>
												
												<div class="push10"></div>
												
												<div class="row">
												
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Quantidade Máx.</label>
															<input type="text" class="form-control input-sm text-center money" name="QTD_LIMITE" id="QTD_LIMITE" maxlength="10" value="">
															<span class="help-block with-errors"></span>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Tipo do Limite</label>
																<select data-placeholder="Selecione uma unidade de medida" name="COD_TPUNIDA" id="COD_TPUNIDA" class="chosen-select-deselect">
																	<option value="">&nbsp;</option>											  
																	<?php
																		$sql = "select * from TIPOUNIDADEMEDIDA where COD_TPUNIDA in (3,4,5) order by COD_TPUNIDA";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																		
																		while ($qrListaMedida = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrListaMedida['COD_TPUNIDA']."'>".$qrListaMedida['DES_TIPONOME']."</option> 
																				"; 
																			  }	
																	?>
																</select>	
															<div class="help-block with-errors"></div>
														</div>
													</div>											
											

												
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Limite</label>
																<select data-placeholder="Selecione o grupo" name="TIP_LIMITE" id="TIP_LIMITE" class="chosen-select-deselect">
																	<option value="">&nbsp;</option>											  
																		<option value="DIA">Por Dia</option> 
																		<option value="SEM">Por Semana</option> 
																		<option value="MES">Por Mês</option> 
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>											

												</div>
												
										</fieldset>	
													
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
											  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											  <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
											
										</div>
										
										<input type="hidden" name="LOG_HABITKT" id="LOG_HABITKT" value="N">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
	
										<!-- modal -->									
										<div class="modal fade" id="popModalAux" tabindex='-1'>
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
										
										
										<div class="push50"></div>
										
										<div id="div_Ordena"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover table-sortable">
												  <thead>
													<tr>
													  <th class="bg-primary" width="40"></th>
													  <th class="bg-primary">Código</th>
													  <th class="bg-primary">Código Externo</th>
													  <th class="bg-primary">Categoria</th>
													  <th class="bg-primary">Qtd. Máxima</th>
													  <th class="bg-primary">Tipo Limite</th>
													  <th class="bg-primary">Limite</th>
													</tr>
												  </thead>
												<tbody>
  
												<?php
												
													$sql=" SELECT A.*,
															V.DES_TIPONOME,
															C.COD_EXTERNO,
															C.DES_CATEGOR 
															FROM REGRACONSUMO A 
															inner join webtools.tipounidademedida V on A.COD_TPUNIDA= V.COD_TPUNIDA
															inner join categoria C on A.COD_CATEGOR= C.COD_CATEGOR
															WHERE A.COD_ENTIDAD = $cod_entidad 
															AND A.COD_EMPRESA = $cod_empresa 
															AND A.COD_EXCLUSA=0 ";
													
													//fnEscreve($sql);
													//fnTestesql(connTemp($cod_empresa,''),$sql);
													
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													
													$count=0;
													
													while ($qrBuscaProdutosCliente = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;
														
														switch ($qrBuscaProdutosCliente['TIP_LIMITE']) {
															case "DIA": 
																$mostraTipo = "ao dia";
																break;
															case "SEM":
																$mostraTipo = "na semana";
																break;
															case "MES":
																$mostraTipo = "ao mês";
																break;
														}															
														
														echo"
															<tr>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrBuscaProdutosCliente['COD_CONSUMO']."</td>
															  <td>".$qrBuscaProdutosCliente['COD_EXTERNO']."</td>
															  <td>".$qrBuscaProdutosCliente['DES_CATEGOR']."</td>
															  <td>".number_format($qrBuscaProdutosCliente['QTD_LIMITE'],2,",",".")."</td>
															  <td>".$qrBuscaProdutosCliente['DES_TIPONOME']."</td>
															  <td>".$mostraTipo."</td>
															</tr>
															<input type='hidden' id='ret_COD_CONSUMO_".$count."' value='".$qrBuscaProdutosCliente['COD_CONSUMO']."'>
															<input type='hidden' id='ret_COD_CATEGOR_".$count."' value='".$qrBuscaProdutosCliente['COD_CATEGOR']."'>
															<input type='hidden' id='ret_DES_CATEGOR_".$count."' value='".$qrBuscaProdutosCliente['DES_CATEGOR']."'>
															<input type='hidden' id='ret_COD_ENTIDAD_".$count."' value='".$qrBuscaProdutosCliente['COD_ENTIDAD']."'>
															<input type='hidden' id='ret_QTD_LIMITE_".$count."' value='".number_format($qrBuscaProdutosCliente['QTD_LIMITE'],2,",",".")."'>
															<input type='hidden' id='ret_COD_TPUNIDA_".$count."' value='".$qrBuscaProdutosCliente['COD_TPUNIDA']."'>
															<input type='hidden' id='ret_TIP_LIMITE_".$count."' value='".$qrBuscaProdutosCliente['TIP_LIMITE']."'>
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
					
 	<script>
		
        $(document).ready( function() {
			
			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			
        });
		
		function retornaForm(index){
			
			$("#formulario #COD_CONSUMO").val($("#ret_COD_CONSUMO_"+index).val());
			$("#formulario #COD_CATEGOR").val($("#ret_COD_CATEGOR_"+index).val());
			$("#formulario #DES_CATEGOR").val($("#ret_DES_CATEGOR_"+index).val());
			$("#formulario #COD_ENTIDAD").val($("#ret_COD_ENTIDAD_"+index).val());
			$("#formulario #QTD_LIMITE").val($("#ret_QTD_LIMITE_"+index).val());
			$("#formulario #COD_TPUNIDA").val($("#ret_COD_TPUNIDA_"+index).val()).trigger("chosen:updated");
			$("#formulario #TIP_LIMITE").val($("#ret_TIP_LIMITE_"+index).val()).trigger("chosen:updated");
			
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	
   