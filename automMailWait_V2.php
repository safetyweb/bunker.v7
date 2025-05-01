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
			
			$cod_aguardo = fnLimpaCampoZero($_REQUEST['COD_AGUARDO']);
			$cod_campanha = fnLimpaCampoZero($_REQUEST['COD_CAMPANHA']);
			$cod_template = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$qtd_aguardo = fnLimpaCampoZero($_REQUEST['QTD_AGUARDO']);
			$tip_aguardo = fnLimpaCampo($_REQUEST['TIP_AGUARDO']);
			$tip_condicao = fnLimpaCampo($_REQUEST['TIP_CONDICAO']);

			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
			
                      
			if ($opcao != ''){	

				$sql = "SELECT COD_TEMPLATE, 
								 CASE 
								 	  WHEN COD_BLTEMPL = 22 THEN 'MSG'
									  WHEN COD_BLTEMPL = 23 THEN 'WAIT'
									  WHEN COD_BLTEMPL = 24 THEN 'TAG'
									  ELSE ''
								 END AS TIP_BLANTERIOR
						FROM TEMPLATE_AUTOMACAO
						WHERE COD_CAMPANHA = $cod_campanha 
						AND COD_EMPRESA = $cod_empresa
						AND NUM_ORDENAC = (SELECT NUM_ORDENAC FROM TEMPLATE_AUTOMACAO WHERE COD_TEMPLATE = $cod_template AND COD_CAMPANHA = $cod_campanha AND COD_EMPRESA = $cod_empresa)-1";

				// fnEscreve($sql);

				$qrBloco = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
				$cod_blanterior = $qrBloco['COD_TEMPLATE'];
				$tip_blanterior = $qrBloco['TIP_BLANTERIOR'];

				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$sql = "INSERT INTO AGUARDO_EMAIL(
												COD_EMPRESA,
												COD_CAMPANHA,
												COD_TEMPLATE,
												COD_BLANTERIOR,
												TIP_BLANTERIOR,
												QTD_AGUARDO,
												TIP_AGUARDO,
												TIP_CONDICAO,
												COD_USUCADA
											) VALUES(
												$cod_empresa,
												$cod_campanha,
												$cod_template,
												$cod_blanterior,
												'$tip_blanterior',
												$qtd_aguardo,
												'$tip_aguardo',
												'$tip_condicao',
												$cod_usucada
											)";
							
						// fnEscreve($sql);
		                mysqli_query(connTemp($cod_empresa,''),$sql);

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':

						$sql = "UPDATE AGUARDO_EMAIL
										COD_BLANTERIOR = $cod_blanterior,
										TIP_BLANTERIOR = '$tip_blanterior',
										QTD_AGUARDO = $qtd_aguardo,
										TIP_AGUARDO = '$tip_aguardo',
										TIP_CONDICAO = '$tip_condicao'
								WHERE COD_CAMPANHA = $cod_campanha
								AND COD_AGUARDO = $cod_aguardo";
							
						// fnEscreve($sql);
		                mysqli_query(connTemp($cod_empresa,''),$sql);

						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
						break;
					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
						break;
					break;
				}

				?>
					<script>
						parent.mudaAba(parent.$('#conteudoAba').attr('src')+"&rnd="+Math.random());
					</script>
				<?php
						
				$msgTipo = 'alert-success';
			}                
		}
	}
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
            
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$cod_template = fnDecode($_GET['idt']);	
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

	$sql = "SELECT * FROM AGUARDO_EMAIL 
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_CAMPANHA = $cod_campanha 
			AND COD_TEMPLATE = $cod_template";

	$qrWait = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

	if(isset($qrWait)){
		$cod_aguardo = $qrWait['COD_AGUARDO'];
		$qtd_aguardo = $qrWait['QTD_AGUARDO'];
		$tip_aguardo = $qrWait['TIP_AGUARDO'];
		$tip_condicao = $qrWait['TIP_CONDICAO'];
	}else{
		$cod_aguardo = "";
		$qtd_aguardo = "";
		$tip_aguardo = "";
		$tip_condicao = "";
	}

	// fnEscreve($cod_template);
	// fnEscreve($cod_campanha);

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
													
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																
						<fieldset>
						<legend>Dados Gerais</legend> 
					
							<div class="row">           
					
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Aguardar</label>
										<input type="text" class="form-control input-sm" name="QTD_AGUARDO" id="QTD_AGUARDO" value="<?php echo $qtd_aguardo ?>" maxlength="50">
									</div>
									<div class="help-block with-errors"></div>
								</div>       
					
								<div class="col-md-4">					
									<div class="form-group">
										<label for="inputName" class="control-label">Tipo do aguardo</label>
										<select data-placeholder="Selecione o tipo do aguardo" name="TIP_AGUARDO" id="TIP_AGUARDO" class="chosen-select-deselect" tabindex="1">
											<option value=""></option>
											<option value="horas">Horas</option>
											<option value="dias">Dias</option>
																		
										</select>                                                   
									</div> 
									<script>$("#TIP_AGUARDO").val("<?=$tip_aguardo?>").trigger("chosen:updated");</script>  
								</div>

								<div class="col-md-4">					
									<div class="form-group">
										<label for="inputName" class="control-label">Condição</label>
										<select data-placeholder="Selecione a condição" name="TIP_CONDICAO" id="TIP_CONDICAO" class="chosen-select-deselect" tabindex="1">
											<option value=""></option>
											<option value="lida">Mensagem lida</option>
																		
										</select>                                                   
									</div> 
									<script>$("#TIP_CONDICAO").val("<?=$tip_condicao?>").trigger("chosen:updated");</script>  
								</div>						
						
							</div>
							
							<div class="push10"></div>
							
					</fieldset>
						
																
						<div class="push10"></div>
						<hr>	
						<div class="form-group text-right col-lg-12">
							
							  <!--<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>-->
							  <?php
								if($cod_aguardo == 0){
									?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button> 
									<?php
								}else{
									?>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
									<?php
								}
							  ?>
							  
							  <!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
							
						</div>
						
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
						<input type="hidden" name="COD_TEMPLATE" id="COD_TEMPLATE" value="<?=$cod_template?>">
						<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?=$cod_campanha?>">
						<input type="hidden" name="COD_AGUARDO" id="COD_AGUARDO" value="<?=$cod_aguardo?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
						
						<div class="push5"></div> 
						
						</form>
						
						<div class="push50"></div>									
					
					<div class="push"></div>
					
					</div>								
				
				</div>
			</div>
			<!-- fim Portlet -->
		</div>
		
	</div>					
		
	<div class="push20"></div> 
	
	<script type="text/javascript">
	
		
		
	</script>	