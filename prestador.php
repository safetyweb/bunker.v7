<?php

	echo fnDebug('true');

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

			$cod_prestad = fnLimpacampoZero($_REQUEST['COD_PRESTAD']);
			$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
			$cod_cadastr = $_SESSION["SYS_COD_USUARIO"];
			$nom_prestad = fnLimpacampo($_REQUEST['NOM_PRESTAD']);
			$des_abrevia = fnLimpacampo($_REQUEST['DES_ABREVIA']);
			$nom_respons = fnLimpacampo($_REQUEST['NOM_RESPONS']);
			$num_cgcecpf = fnLimpacampo($_REQUEST['NUM_CGCECPF']);
			$des_sufixo = fnLimpacampo($_REQUEST['DES_SUFIXO']);
			$cod_status = fnLimpacampoZero($_REQUEST['COD_STATUS']);
			if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}
			if (empty($_REQUEST['LOG_PRECUNI'])) {$log_precuni='N';}else{$log_precuni=$_REQUEST['LOG_PRECUNI'];}
			if (empty($_REQUEST['LOG_ESTOQUE'])) {$log_estoque='N';}else{$log_estoque=$_REQUEST['LOG_ESTOQUE'];}
			$num_escrica = fnLimpacampo($_REQUEST['NUM_ESCRICA']);
			$nom_fantasi = fnLimpacampo($_REQUEST['NOM_FANTASI']);
			$num_telefon = fnLimpacampo($_REQUEST['NUM_TELEFON']);
			$num_celular = fnLimpacampo($_REQUEST['NUM_CELULAR']);
			$des_enderec = fnLimpacampo($_REQUEST['DES_ENDEREC']);
			$num_enderec = fnLimpacampo($_REQUEST['NUM_ENDEREC']);
			$des_complem = fnLimpacampo($_REQUEST['DES_COMPLEM']);
			$des_bairroc = fnLimpacampo($_REQUEST['DES_BAIRROC']);
			$num_cepozof = fnLimpacampo($_REQUEST['NUM_CEPOZOF']);
			$nom_cidadec = fnLimpacampo($_REQUEST['NOM_CIDADEC']);
			$cod_estadof = fnLimpacampo($_REQUEST['COD_ESTADOF']);
			$cod_layout = fnLimpacampo($_REQUEST['COD_LAYOUT']);
			$cod_segment = fnLimpacampo($_REQUEST['COD_SEGMENT']);

			//fnEscreve($log_ativo);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];			
						
			if ($opcao != ''){
				
				$sql = "CALL SP_ALTERA_PRESTADOR (
				 '".$cod_prestad."',
				 '".$cod_empresa."', 
				 '".$cod_cadastr."', 
				 '".$nom_prestad."', 
				 '".$des_abrevia."', 
				 '".$nom_respons."', 
				 '".$num_cgcecpf."', 
				 '".$log_ativo."', 
				 '".$cod_status."', 
				 '".$num_escrica."', 
				 '".$nom_fantasi."', 
				 '".$num_telefon."', 
				 '".$num_celular."', 
				 '".$des_enderec."', 
				 '".$num_enderec."', 
				 '".$des_complem."', 
				 '".$des_bairroc."', 				 
				 '".$num_cepozof."',				 
				 '".$nom_cidadec."',    
				 '".$cod_estadof."',       
				 '".$cod_layout."',  
				 '".$log_precuni."',    
				 '".$log_estoque."',    
				 '".$cod_segment."',    
				 '".$des_sufixo."',    
				 '".$opcao."'    
				) ";
				
				//echo $sql;
				
				//fnEscreve($sql);
				mysqli_query(connTemp($cod_empresa,''),$sql);				
				
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

        
?>
		
	<div class="push30"></div> 
	
	<div class="row">				
	
		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="glyphicon glyphicon-calendar"></i>
						<span class="text-primary"> <?php echo $NomePg; ?></span>
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

					<?php $abaFormalizacao = 1090; include "abasFormalizacaoEmp.php"; ?>
					
					<div class="push30"></div> 

					<div class="login-form">
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
													
						<fieldset>
							<legend>Dados Gerais</legend> 
							
								<div class="row">	

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Prestador Ativo</label> 
											<div class="push5"></div>
												<label class="switch">
												<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S" >
												<span></span>
												</label>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Status</label>
												<select data-placeholder="Selecione um status" name="COD_STATUS" id="COD_STATUS" class="chosen-select-deselect" required>
													<option value=""></option>
													<?php 
													
														$sql = "SELECT * FROM STATUSSISTEMA ORDER BY DES_STATUS ";
														$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													
														while ($qrLista = mysqli_fetch_assoc($arrayQuery))
														  {														
															echo"
																  <option value='".$qrLista['COD_STATUS']."'>".$qrLista['DES_STATUS']."</option> 
																"; 
															  }											
													?> 
												</select>
												<script>$("#formulario #COD_STATUS").val("<?php echo $cod_status ?>").trigger("chosen:updated"); </script>																	
												<div class="help-block with-errors"></div>
										</div>
									</div>
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Sufixo do Prestador</label>
											<input type="text" class="form-control input-sm" name="DES_SUFIXO" id="DES_SUFIXO" maxlength="10" value="" data-error="Campo obrigatório" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									
																
								</div>
							
								<div class="row">
								
									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PRESTAD" id="COD_PRESTAD" value="">
										</div>
									</div>
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>" required>
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
										</div>														
									</div>
									
									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nome do Prestador</label>
											<input type="text" class="form-control input-sm" name="NOM_PRESTAD" id="NOM_PRESTAD" maxlength="100" data-error="Campo obrigatório" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nome Fantasia</label>
											<input type="text" class="form-control input-sm" name="NOM_FANTASI" id="NOM_FANTASI" maxlength="40" data-error="Campo obrigatório" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Abreviação</label>
											<input type="text" class="form-control input-sm" name="DES_ABREVIA" id="DES_ABREVIA" maxlength="5" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
								</div>
								
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Responsável</label>
											<input type="text" class="form-control input-sm" name="NOM_RESPONS" id="NOM_RESPONS" maxlength="50" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">CNPJ/CPF</label>
											<input type="text" class="form-control input-sm" name="NUM_CGCECPF" id="NUM_CGCECPF" maxlength="18" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Inscrição Estadual</label>
											<input type="text" class="form-control input-sm" name="NUM_ESCRICA" id="NUM_ESCRICA" maxlength="20" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Telefone Principal</label>
											<input type="text" class="form-control input-sm" name="NUM_TELEFON" id="NUM_TELEFON" maxlength="20">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
								</div>
								
								<div class="row">									
										
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Telefone Celular</label>
											<input type="text" class="form-control input-sm" name="NUM_CELULAR" id="NUM_CELULAR" maxlength="20">
											<div class="help-block with-errors"></div>
										</div>
									</div>
													
									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Endereço</label>
											<input type="text" class="form-control input-sm" name="DES_ENDEREC" id="DES_ENDEREC" maxlength="40">
											<div class="help-block with-errors"></div>
										</div>
									</div>	
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Número</label>
											<input type="text" class="form-control input-sm" name="NUM_ENDEREC" id="NUM_ENDEREC" maxlength="10">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Complemento</label>
											<input type="text" class="form-control input-sm" name="DES_COMPLEM" id="DES_COMPLEM" maxlength="20">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Bairro</label>
											<input type="text" class="form-control input-sm" name="DES_BAIRROC" id="DES_BAIRROC" maxlength="20">
											<div class="help-block with-errors"></div>
										</div>
									</div>	
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">CEP</label>
											<input type="text" class="form-control input-sm" name="NUM_CEPOZOF" id="NUM_CEPOZOF" maxlength="9">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Cidade</label>
											<input type="text" class="form-control input-sm" name="NOM_CIDADEC" id="NOM_CIDADEC" maxlength="40">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Estado</label>
												<select data-placeholder="Selecione um estado" name="COD_ESTADOF" id="COD_ESTADOF" class="chosen-select-deselect">
													<option value=""></option>					
													<option value="AC">AC</option> 
													<option value="AL">AL</option> 
													<option value="AM">AM</option> 
													<option value="AP">AP</option> 
													<option value="BA">BA</option> 
													<option value="CE">CE</option> 
													<option value="DF">DF</option> 
													<option value="ES">ES</option> 
													<option value="GO">GO</option> 
													<option value="MA">MA</option> 
													<option value="MG">MG</option> 
													<option value="MS">MS</option> 
													<option value="MT">MT</option> 
													<option value="PA">PA</option> 
													<option value="PB">PB</option> 
													<option value="PE">PE</option> 
													<option value="PI">PI</option> 
													<option value="PR">PR</option> 
													<option value="RJ">RJ</option> 
													<option value="RN">RN</option> 
													<option value="RO">RO</option> 
													<option value="RR">RR</option> 
													<option value="RS">RS</option> 
													<option value="SC">SC</option> 
													<option value="SE">SE</option> 
													<option value="SP">SP</option> 
													<option value="TO">TO</option> 							
												</select>	
											<div class="help-block with-errors"></div>
										</div>
									</div>														
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Layout</label>
												<select data-placeholder="Selecione uma skin" name="COD_LAYOUT" id="COD_LAYOUT" class="chosen-select-deselect" required>
													<option value=""></option>
													<?php 
													
														$sql = "select COD_LAYOUT, DES_LAYOUT from LAYOUTS order by DES_LAYOUT";
														$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													
														while ($qrLayout = mysqli_fetch_assoc($arrayQuery))
														  {														
															echo"
																  <option value='".$qrLayout['COD_LAYOUT']."'>".$qrLayout['DES_LAYOUT']."</option> 
																"; 
															  }											
													?>	
												</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>	
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Segmento</label>
												<select data-placeholder="Selecione um segmento" name="COD_SEGMENT" id="COD_SEGMENT" class="chosen-select-deselect" required>
													<option value=""></option>
													<?php 
													
														$sql = "select COD_SEGMENT, NOM_SEGMENT from SEGMENTOMARKA order by NOM_SEGMENT";
														$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													
														while ($qrLista = mysqli_fetch_assoc($arrayQuery))
														  {														
															echo"
																  <option value='".$qrLista['COD_SEGMENT']."'>".$qrLista['NOM_SEGMENT']."</option> 
																"; 
															  }											
													?> 
												</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Controle de Preço por Loja</label> 
											<div class="push5"></div>
												<label class="switch">
												<input type="checkbox" name="LOG_PRECUNI" id="LOG_PRECUNI" class="switch" value="S" >
												<span></span>
												</label>
										</div>
									</div>
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Controla Estoque</label> 
											<div class="push5"></div>
												<label class="switch">
												<input type="checkbox" name="LOG_ESTOQUE" id="LOG_ESTOQUE" class="switch" value="S" >
												<span></span>
												</label>
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
							  <!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
							
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
								
								<table class="table table-bordered table-striped table-hover tablesorter buscavel">
								  <thead>
									<tr>
									  <th width="40"></th>
									  <th>Código</th>
									  <th>Nome do Prestador</th>
									  <th>Nome Fantasia</th>
									  <th>Responsável</th>
									  <th>Telefones</th>
									  <th>Ativo</th>
									</tr>
								  </thead>
								<tbody>
								  
								<?php 
								
									$sql = "select * from prestador order by COD_PRESTAD";
									$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
									
									$count=0;
									while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery))
									  {														  
										$count++;
										  if ($qrListaEmpresas['LOG_ATIVO'] == 'S'){		
												$mostraAtivo = '<i class="fa fa-check-square-o" aria-hidden="true"></i>';	
											}else{ $mostraAtivo = ''; }	
										
										echo"
											<tr>
											  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
											  <td>".$qrListaEmpresas['COD_PRESTAD']."</td>
											  <td>".$qrListaEmpresas['NOM_PRESTAD']."</td>
											  <td>".$qrListaEmpresas['NOM_FANTASI']."</td>
											  <td>".$qrListaEmpresas['NOM_RESPONS']."</td>
											  <td>".$qrListaEmpresas['NUM_TELEFON']." / ".$qrListaEmpresas['NUM_CELULAR']."</td>
											  <td align='center'>".$mostraAtivo."</td>
											</tr>
											<input type='hidden' id='ret_COD_PRESTAD_".$count."' value='".$qrListaEmpresas['COD_PRESTAD']."'>
											<input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".$qrListaEmpresas['COD_EMPRESA']."'>
											<input type='hidden' id='ret_NOM_PRESTAD_".$count."' value='".$qrListaEmpresas['NOM_PRESTAD']."'>
											<input type='hidden' id='ret_DES_ABREVIA_".$count."' value='".$qrListaEmpresas['DES_ABREVIA']."'>
											<input type='hidden' id='ret_NOM_RESPONS_".$count."' value='".$qrListaEmpresas['NOM_RESPONS']."'>
											<input type='hidden' id='ret_NUM_CGCECPF_".$count."' value='".$qrListaEmpresas['NUM_CGCECPF']."'>
											<input type='hidden' id='ret_NUM_ESCRICA_".$count."' value='".$qrListaEmpresas['NUM_ESCRICA']."'>
											<input type='hidden' id='ret_LOG_ATIVO_".$count."' value='".$qrListaEmpresas['LOG_ATIVO']."'>
											<input type='hidden' id='ret_COD_ESTATUS_".$count."' value='".$qrListaEmpresas['COD_STATUS']."'>
											<input type='hidden' id='ret_LOG_PRECUNI_".$count."' value='".$qrListaEmpresas['LOG_PRECUNI']."'>
											<input type='hidden' id='ret_LOG_ESTOQUE_".$count."' value='".$qrListaEmpresas['LOG_ESTOQUE']."'>
											<input type='hidden' id='ret_NOM_FANTASI_".$count."' value='".$qrListaEmpresas['NOM_FANTASI']."'>
											<input type='hidden' id='ret_NUM_TELEFON_".$count."' value='".$qrListaEmpresas['NUM_TELEFON']."'>
											<input type='hidden' id='ret_NUM_CELULAR_".$count."' value='".$qrListaEmpresas['NUM_CELULAR']."'>
											<input type='hidden' id='ret_DES_ENDEREC_".$count."' value='".$qrListaEmpresas['DES_ENDEREC']."'>
											<input type='hidden' id='ret_NUM_ENDEREC_".$count."' value='".$qrListaEmpresas['NUM_ENDEREC']."'>
											<input type='hidden' id='ret_DES_COMPLEM_".$count."' value='".$qrListaEmpresas['DES_COMPLEM']."'>
											<input type='hidden' id='ret_DES_BAIRROC_".$count."' value='".$qrListaEmpresas['DES_BAIRROC']."'>
											<input type='hidden' id='ret_NUM_CEPOZOF_".$count."' value='".$qrListaEmpresas['NUM_CEPOZOF']."'>
											<input type='hidden' id='ret_NOM_CIDADEC_".$count."' value='".$qrListaEmpresas['NOM_CIDADEC']."'>
											<input type='hidden' id='ret_COD_ESTADOF_".$count."' value='".$qrListaEmpresas['COD_ESTADOF']."'>
											<input type='hidden' id='ret_COD_LAYOUT_".$count."' value='".$qrListaEmpresas['COD_LAYOUT']."'>
											<input type='hidden' id='ret_COD_SEGMENT_".$count."' value='".$qrListaEmpresas['COD_SEGMENT']."'>
											<input type='hidden' id='ret_DES_SUFIXO_".$count."' value='".$qrListaEmpresas['DES_SUFIXO']."'>
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
	
		$(document).ready(function(){
			
			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

		});	
		
		function retornaForm(index){
			$("#formulario #COD_PRESTAD").val($("#ret_COD_PRESTAD_"+index).val());
			$("#formulario #NOM_PRESTAD").val($("#ret_NOM_PRESTAD_"+index).val());
			$("#formulario #DES_ABREVIA").val($("#ret_DES_ABREVIA_"+index).val());
			$("#formulario #NOM_RESPONS").val($("#ret_NOM_RESPONS_"+index).val());
			$("#formulario #NUM_CGCECPF").val($("#ret_NUM_CGCECPF_"+index).val());
			$("#formulario #COD_ESTATUS").val($("#ret_COD_ESTATUS_"+index).val()).trigger("chosen:updated");
			if ($("#ret_LOG_ATIVO_"+index).val() == 'S'){$('#formulario #LOG_ATIVO').prop('checked', true);} 
			else {$('#formulario #LOG_ATIVO').prop('checked', false);}			
			if ($("#ret_LOG_PRECUNI_"+index).val() == 'S'){$('#formulario #LOG_PRECUNI').prop('checked', true);} 
			else {$('#formulario #LOG_PRECUNI').prop('checked', false);}
			if ($("#ret_LOG_ESTOQUE_"+index).val() == 'S'){$('#formulario #LOG_ESTOQUE').prop('checked', true);} 
			else {$('#formulario #LOG_ESTOQUE').prop('checked', false);}
			$("#formulario #NUM_ESCRICA").val($("#ret_NUM_ESCRICA_"+index).val());
			$("#formulario #NOM_FANTASI").val($("#ret_NOM_FANTASI_"+index).val());				
			$("#formulario #NUM_TELEFON").val($("#ret_NUM_TELEFON_"+index).val());
			$("#formulario #NUM_CELULAR").val($("#ret_NUM_CELULAR_"+index).val());
			$("#formulario #DES_ENDEREC").val($("#ret_DES_ENDEREC_"+index).val());
			$("#formulario #NUM_ENDEREC").val($("#ret_NUM_ENDEREC_"+index).val());
			$("#formulario #DES_COMPLEM").val($("#ret_DES_COMPLEM_"+index).val());
			$("#formulario #DES_BAIRROC").val($("#ret_DES_BAIRROC_"+index).val());
			$("#formulario #NUM_CEPOZOF").val($("#ret_NUM_CEPOZOF_"+index).val());
			$("#formulario #NOM_CIDADEC").val($("#ret_NOM_CIDADEC_"+index).val());				
			$("#formulario #COD_ESTADOF").val($("#ret_COD_ESTADOF_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_LAYOUT").val($("#ret_COD_LAYOUT_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_SEGMENT").val($("#ret_COD_SEGMENT_"+index).val()).trigger("chosen:updated");
			$("#formulario #DES_SUFIXO").val($("#ret_DES_SUFIXO_"+index).val());				

			
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}	
		
		
	</script>	